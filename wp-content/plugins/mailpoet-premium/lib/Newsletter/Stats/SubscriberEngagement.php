<?php
namespace MailPoet\Premium\Newsletter\Stats;

use MailPoet\Listing\Handler;
use MailPoet\Models\NewsletterLink;
use MailPoet\Models\StatisticsClicks;
use MailPoet\Models\StatisticsNewsletters;
use MailPoet\Models\StatisticsOpens;
use MailPoet\Models\StatisticsUnsubscribes;
use MailPoet\Models\Subscriber;
use MailPoet\WP\Functions as WPFunctions;

use function MailPoet\Util\array_column;

class SubscriberEngagement {
  const STATUS_OPENED = 'opened';
  const STATUS_CLICKED = 'clicked';
  const STATUS_UNSUBSCRIBED = 'unsubscribed';
  const STATUS_UNOPENED = 'unopened';

  private $group;
  private $filters;
  private $search;
  private $sort_by;
  private $sort_order;
  private $offset;
  private $limit;
  private $newsletter_id;

  function __construct($data = []) {
    // check if sort order was specified or default to "desc"
    $sort_order = (!empty($data['sort_order'])) ? $data['sort_order'] : 'desc';
    // constrain sort order value to either be "asc" or "desc"
    $sort_order = ($sort_order === 'asc') ? 'asc' : 'desc';

    // sanitize sort by
    $sortable_columns = ['email', 'status', 'created_at'];
    $sort_by = (!empty($data['sort_by']) && in_array($data['sort_by'], $sortable_columns))
      ? $data['sort_by']
      : '';

    if (empty($sort_by)) {
      $sort_by = 'created_at';
    }

    $this->group = (isset($data['group']) ? $data['group'] : null);
    $this->filters = (isset($data['filter']) ? $data['filter'] : null);
    $this->search = (isset($data['search']) ? $data['search'] : null);
    $this->sort_by = $sort_by;
    $this->sort_order = $sort_order;
    $this->offset = (isset($data['offset']) ? (int)$data['offset'] : 0);
    $this->limit = (isset($data['limit']) ? (int)$data['limit'] : Handler::DEFAULT_LIMIT_PER_PAGE);
    $this->newsletter_id = (isset($data['params']['id']) ? (int)$data['params']['id'] : null);
  }

  function get() {
    $count_query = $this->getStatsQuery(true);
    if (empty($count_query)) {
      return $this->emptyResponse();
    }

    $count = Subscriber::rawQuery(
      ' SELECT COUNT(*) as cnt FROM ( ' . $count_query . ' ) t '
    )->findArray();
    $count = $count[0]['cnt'];

    $stats_query = $this->getStatsQuery();
    $items = Subscriber::rawQuery(
      $stats_query . ' ORDER BY ' . $this->sort_by . ' ' . $this->sort_order . ' LIMIT ' . $this->limit . ' OFFSET ' . $this->offset
    )->findArray();

    return [
      'count' => $count,
      'filters' => $this->filters(),
      'groups' => $this->groups(),
      'items' => $items,
    ];
  }

  private function getStatsQuery($count = false, $group = null, $apply_constraints = true) {
    $filter_constraint = '';
    $search_constraint = '';

    if ($apply_constraints) {
      $filter_constraint = $this->getFilterConstraint();
      $search_constraint = $this->getSearchConstraint();
      if (($search_constraint) === false) {
        // Nothing was found by search
        return false;
      }
    }

    $queries = [];

    $fields = [
      'opens.subscriber_id',
      '"' . self::STATUS_OPENED . '" as status',
      'opens.created_at',
      'subscribers.email',
      'subscribers.first_name',
      'subscribers.last_name',
    ];

    $queries[self::STATUS_OPENED] = '(SELECT '
      . self::getColumnList($fields, $count) . ' '
      . 'FROM ' . StatisticsOpens::$_table . ' opens '
      . 'LEFT JOIN ' . Subscriber::$_table . ' subscribers ON subscribers.id = opens.subscriber_id '
      . 'WHERE opens.newsletter_id = "' . $this->newsletter_id . '" ' . $search_constraint . ') ';

    $fields = [
      'clicks.subscriber_id',
      '"' . self::STATUS_CLICKED . '" as status',
      'clicks.created_at',
      'subscribers.email',
      'subscribers.first_name',
      'subscribers.last_name',
    ];

    $queries[self::STATUS_CLICKED] = '(SELECT '
      . self::getColumnList($fields, $count) . ' '
      . 'FROM ' . StatisticsClicks::$_table . ' clicks '
      . 'LEFT JOIN ' . Subscriber::$_table . ' subscribers ON subscribers.id = clicks.subscriber_id '
      . 'WHERE clicks.newsletter_id = "' . $this->newsletter_id . '" ' . $search_constraint . $filter_constraint . ') ';

    $fields = [
      'unsubscribes.subscriber_id',
      '"' . self::STATUS_UNSUBSCRIBED . '" as status',
      'unsubscribes.created_at',
      'subscribers.email',
      'subscribers.first_name',
      'subscribers.last_name',
    ];

    $queries[self::STATUS_UNSUBSCRIBED] = '(SELECT '
      . self::getColumnList($fields, $count) . ' '
      . 'FROM ' . StatisticsUnsubscribes::$_table . ' unsubscribes '
      . 'LEFT JOIN ' . Subscriber::$_table . ' subscribers ON subscribers.id = unsubscribes.subscriber_id '
      . 'WHERE unsubscribes.newsletter_id = "' . $this->newsletter_id . '" ' . $search_constraint . ') ';

    $fields = [
      'sent.subscriber_id',
      '"' . self::STATUS_UNOPENED . '" as status',
      'sent.sent_at as created_at',
      'subscribers.email',
      'subscribers.first_name',
      'subscribers.last_name',
    ];

    $queries[self::STATUS_UNOPENED] = '(SELECT '
      . self::getColumnList($fields, $count) . ' '
      . 'FROM ' . StatisticsNewsletters::$_table . ' sent '
      . 'LEFT JOIN ' . Subscriber::$_table . ' subscribers ON subscribers.id = sent.subscriber_id '
      . 'LEFT JOIN ' . StatisticsOpens::$_table . ' opens ON sent.subscriber_id = opens.subscriber_id '
      . ' AND opens.newsletter_id = sent.newsletter_id ' . 'WHERE sent.newsletter_id = "' . $this->newsletter_id . '" '
      . ' AND opens.id IS NULL ' . $search_constraint . ') ';

    $group = $group ?: $this->group;

    if (isset($queries[$group])) {
      $stats_query = $queries[$group];
    } else {
      $stats_query = join(
        ' UNION ALL ',
        [
          $queries[self::STATUS_OPENED],
          $queries[self::STATUS_CLICKED],
          $queries[self::STATUS_UNSUBSCRIBED],
        ]
      );
    }

    return $stats_query;
  }

  private function getFilterConstraint() {
    // Filter by link clicked
    $link_constraint = '';
    if (!empty($this->filters['link'])) {
      $link = NewsletterLink::findOne((int)$this->filters['link']);
      if ($link instanceof NewsletterLink) {
        $this->group = self::STATUS_CLICKED;
        $link_constraint = ' AND clicks.link_id = "' . $link->id . '"';
      }
    }

    return $link_constraint;
  }

  private function getSearchConstraint() {
    // Search recipients
    $subscriber_ids = [];
    if (!empty($this->search)) {
      $subscriber_ids = Subscriber::select('id')->filter('search', $this->search)->findArray();
      $subscriber_ids = array_column($subscriber_ids, 'id');
      if (empty($subscriber_ids)) {
        return false;
      }
    }
    $subscribers_constraint = '';
    if (!empty($subscriber_ids)) {
      $subscribers_constraint = sprintf(
        ' AND subscribers.id IN (%s) ',
        join(',', array_map('intval', $subscriber_ids))
      );
    }

    return $subscribers_constraint;
  }

  static function getColumnList(array $fields, $count = false) {
    // Select ID field only for counting
    return $count ? reset($fields) : join(', ', $fields);
  }

  function filters() {
    $links = StatisticsClicks::tableAlias('clicks')
      ->selectExpr(
        'clicks.link_id, links.url, COUNT(DISTINCT clicks.subscriber_id) as cnt'
      )
      ->join(
        MP_NEWSLETTER_LINKS_TABLE,
        'links.id = clicks.link_id',
        'links'
      )
      ->where('newsletter_id', $this->newsletter_id)
      ->groupBy('clicks.link_id')
      ->orderByAsc('links.url')
      ->findArray();


    $link_list = [];
    $link_list[] = [
      'label' => WPFunctions::get()->__('Filter by link clicked', 'mailpoet-premium'),
      'value' => '',
    ];

    foreach ($links as $link) {
      $label = sprintf(
        '%s (%s)',
        $link['url'],
        number_format($link['cnt'])
      );

      $link_list[] = [
        'label' => $label,
        'value' => $link['link_id'],
      ];
    }

    $filters = [
      'link' => $link_list,
    ];

    return $filters;
  }

  function groups() {
    $newsletter_id = $this->newsletter_id;

    $groups = [
      [
        'name' => self::STATUS_CLICKED,
        'label' => WPFunctions::get()->x('Clicked', 'Subscriber engagement filter - filter those who clicked on a newsletter link', 'mailpoet-premium'),
        'count' => StatisticsClicks::where('newsletter_id', $newsletter_id)->count(),
      ],
      [
        'name' => self::STATUS_OPENED,
        'label' => WPFunctions::get()->x('Opened', 'Subscriber engagement filter - filter those who opened a newsletter', 'mailpoet-premium'),
        'count' => StatisticsOpens::where('newsletter_id', $newsletter_id)->count(),
      ],
      [
        'name' => self::STATUS_UNSUBSCRIBED,
        'label' => WPFunctions::get()->x('Unsubscribed', 'Subscriber engagement filter - filter those who unsubscribed from a newsletter', 'mailpoet-premium'),
        'count' => StatisticsUnsubscribes::where('newsletter_id', $newsletter_id)->count(),
      ],
    ];

    array_unshift(
      $groups,
      [
        'name' => 'all',
        'label' => WPFunctions::get()->x('All', 'Subscriber engagement filter - filter those who performed any action (e.g., clicked, opened, unsubscribed)', 'mailpoet-premium'),
        'count' => array_sum(array_column($groups, 'count')),
      ]
    );

    $unopened_count = Subscriber::rawQuery(
      ' SELECT COUNT(*) as cnt FROM ( ' . $this->getStatsQuery(true, self::STATUS_UNOPENED, false) . ' ) t '
    )->findArray();
    $unopened_count = (int)$unopened_count[0]['cnt'];

    $groups[] = [
      'name' => self::STATUS_UNOPENED,
      'label' => WPFunctions::get()->x('Unopened', 'Subscriber engagement filter - filter those who did not open a newsletter', 'mailpoet-premium'),
      'count' => $unopened_count,
    ];

    return $groups;
  }

  function emptyResponse() {
    return [
      'count' => 0,
      'filters' => $this->filters(),
      'groups' => $this->groups(),
      'items' => [],
    ];
  }
}
