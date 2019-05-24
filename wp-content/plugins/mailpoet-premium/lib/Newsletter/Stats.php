<?php
namespace MailPoet\Premium\Newsletter;

use MailPoet\Models\Newsletter;
use MailPoet\Models\StatisticsClicks;

class Stats {
  static function getClickedLinks(Newsletter $newsletter) {
    $group_by = 'clicks.link_id';
    if ($newsletter->type === Newsletter::TYPE_WELCOME) {
      $group_by = 'links.url'; // the same URL can have multiple link IDs
    }

    return StatisticsClicks::tableAlias('clicks')
      ->selectExpr(
        'clicks.*, links.url, COUNT(DISTINCT clicks.subscriber_id) as cnt'
      )
      ->join(
        MP_NEWSLETTER_LINKS_TABLE,
        'links.id = clicks.link_id',
        'links'
      )
      ->where('newsletter_id', $newsletter->id)
      ->groupBy($group_by)
      ->orderByDesc('cnt')
      ->findArray();
  }
}
