<?php
namespace MailPoet\Config\PopulatorData\Templates;

use MailPoet\WP\Functions as WPFunctions;

if (!defined('ABSPATH')) exit;


class NewsletterBlank121Column {

  private $assets_url;
  private $external_template_image_url;
  private $template_image_url;
  private $social_icon_url;

  function __construct($assets_url) {
    $this->assets_url = $assets_url;
    $this->external_template_image_url = 'https://ps.w.org/mailpoet/assets/newsletter-templates/newsletter-blank-1-2-1-column';
    $this->template_image_url = $this->assets_url . '/img/blank_templates';
    $this->social_icon_url = $this->assets_url . '/img/newsletter_editor/social-icons';
  }

  function get() {
    return [
      'name' => WPFunctions::get()->__("Newsletter: Blank 1:2:1 Column", 'mailpoet'),
      'categories' => json_encode(['standard']),
      'readonly' => 1,
      'thumbnail' => $this->getThumbnail(),
      'body' => json_encode($this->getBody()),
    ];
  }

  private function getBody() {
    return [
      "content" => [
        "type" => "container",
        "orientation" => "vertical",
        "styles" => [
          "block" => [
            "backgroundColor" => "transparent",
          ],
        ],
        "blocks" => [
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "#f8f8f8",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "header",
                    "text" => WPFunctions::get()->__("Display problems? <a href=\"[link:newsletter_view_in_browser_url]\">Open this email in your web browser.</a>", 'mailpoet'),
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                      ],
                      "text" => [
                        "fontColor" => "#222222",
                        "fontFamily" => "Arial",
                        "fontSize" => "12px",
                        "textAlign" => "center",
                      ],
                      "link" => [
                        "fontColor" => "#6cb7d4",
                        "textDecoration" => "underline",
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "#ffffff",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "spacer",
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                        "height" => "30px",
                      ],
                    ],
                  ],
                  [
                    "type" => "image",
                    "link" => "",
                    "src" => $this->template_image_url . "/fake-logo.png",
                    "alt" => WPFunctions::get()->__("Fake logo", 'mailpoet'),
                    "fullWidth" => false,
                    "width" => "598px",
                    "height" => "71px",
                    "styles" => [
                      "block" => [
                        "textAlign" => "center",
                      ],
                    ],
                  ],
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<h1 style=\"text-align: center;\"><strong>Let's Get Started!</strong></h1>\n<p>It's time to design your newsletter! In the right sidebar, you'll find four menu items that will help you customize your newsletter:</p>\n<ol>\n<li>Content</li>\n<li>Columns</li>\n<li>Styles</li>\n<li>Preview</li>\n</ol>", 'mailpoet'),
                  ],
                  [
                    "type" => "divider",
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                        "padding" => "13px",
                        "borderStyle" => "dotted",
                        "borderWidth" => "3px",
                        "borderColor" => "#aaaaaa",
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "transparent",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<h2>This template has...</h2>", 'mailpoet'),
                  ],
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<p>In the right sidebar, you can add layout blocks to your email:</p>\n<ul>\n<li>1 column</li>\n<li>2 columns</li>\n<li>3 columns</li>\n</ul>", 'mailpoet'),
                  ],
                ],
              ],
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<h2>... a 2-column layout.</h2>", 'mailpoet'),
                  ],
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<p>You can change a layout's background color by clicking on the settings icon on the right edge of the Designer. Simply hover over this area to see the Settings (gear) icon.</p>", 'mailpoet'),
                  ],
                ],
              ],
            ],
          ],
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "transparent",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "divider",
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                        "padding" => "13px",
                        "borderStyle" => "dotted",
                        "borderWidth" => "3px",
                        "borderColor" => "#aaaaaa",
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "transparent",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "text",
                    "text" => WPFunctions::get()->__("<h3 style=\"text-align: center;\"><span style=\"font-weight: 600;\">Let's end with a single column. </span></h3>\n<p style=\"line-height: 25.6px;\">In the right sidebar, you can add these layout blocks to your email:</p>\n<p style=\"line-height: 25.6px;\"></p>\n<ul style=\"line-height: 25.6px;\">\n<li>1 column</li>\n<li>2 columns</li>\n<li>3 columns</li>\n</ul>", 'mailpoet'),
                  ],
                ],
              ],
            ],
          ],
          [
            "type" => "container",
            "orientation" => "horizontal",
            "styles" => [
              "block" => [
                "backgroundColor" => "#f8f8f8",
              ],
            ],
            "blocks" => [
              [
                "type" => "container",
                "orientation" => "vertical",
                "styles" => [
                  "block" => [
                    "backgroundColor" => "transparent",
                  ],
                ],
                "blocks" => [
                  [
                    "type" => "divider",
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                        "padding" => "24.5px",
                        "borderStyle" => "solid",
                        "borderWidth" => "3px",
                        "borderColor" => "#aaaaaa",
                      ],
                    ],
                  ],
                  [
                    "type" => "social",
                    "iconSet" => "grey",
                    "icons" => [
                      [
                        "type" => "socialIcon",
                        "iconType" => "facebook",
                        "link" => "http://www.facebook.com",
                        "image" => $this->social_icon_url . "/02-grey/Facebook.png",
                        "height" => "32px",
                        "width" => "32px",
                        "text" => "Facebook",
                      ],
                      [
                        "type" => "socialIcon",
                        "iconType" => "twitter",
                        "link" => "http://www.twitter.com",
                        "image" => $this->social_icon_url . "/02-grey/Twitter.png",
                        "height" => "32px",
                        "width" => "32px",
                        "text" => "Twitter",
                      ],
                    ],
                  ],
                  [
                    "type" => "divider",
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                        "padding" => "7.5px",
                        "borderStyle" => "solid",
                        "borderWidth" => "3px",
                        "borderColor" => "#aaaaaa",
                      ],
                    ],
                  ],
                  [
                    "type" => "footer",
                    "text" => WPFunctions::get()->__("<p><a href=\"[link:subscription_unsubscribe_url]\">Unsubscribe</a> | <a href=\"[link:subscription_manage_url]\">Manage your subscription</a><br />Add your postal address here!</p>", 'mailpoet'),
                    "styles" => [
                      "block" => [
                        "backgroundColor" => "transparent",
                      ],
                      "text" => [
                        "fontColor" => "#222222",
                        "fontFamily" => "Arial",
                        "fontSize" => "12px",
                        "textAlign" => "center",
                      ],
                      "link" => [
                        "fontColor" => "#6cb7d4",
                        "textDecoration" => "none",
                      ],
                    ],
                  ],
                ],
              ],
            ],
          ],
        ],
      ],
      "globalStyles" => [
        "text" => [
          "fontColor" => "#000000",
          "fontFamily" => "Arial",
          "fontSize" => "16px",
        ],
        "h1" => [
          "fontColor" => "#111111",
          "fontFamily" => "Trebuchet MS",
          "fontSize" => "30px",
        ],
        "h2" => [
          "fontColor" => "#222222",
          "fontFamily" => "Trebuchet MS",
          "fontSize" => "24px",
        ],
        "h3" => [
          "fontColor" => "#333333",
          "fontFamily" => "Trebuchet MS",
          "fontSize" => "22px",
        ],
        "link" => [
          "fontColor" => "#21759B",
          "textDecoration" => "underline",
        ],
        "wrapper" => [
          "backgroundColor" => "#ffffff",
        ],
        "body" => [
          "backgroundColor" => "#eeeeee",
        ],
      ],
    ];
  }

  private function getThumbnail() {
    return $this->external_template_image_url . '/thumbnail.20190411-1500.jpg';
  }

}


