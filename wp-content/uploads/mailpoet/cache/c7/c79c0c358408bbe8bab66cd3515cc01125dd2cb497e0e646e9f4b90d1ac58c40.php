<?php

/* newsletter/templates/blocks/automatedLatestContent/settings.hbs */
class __TwigTemplate_931547a353d2b07a45499e58725f1376d254398578f1a0291fb392d9048046ce extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<h3>";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Post selection");
        echo "</h3>

<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 4
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show max:");
        echo "</div>
    <div class=\"mailpoet_form_field_input_option\">
        <input type=\"text\" class=\"mailpoet_input mailpoet_input_small mailpoet_automated_latest_content_show_amount\" value=\"{{ model.amount }}\" maxlength=\"2\" size=\"2\" />
        <select class=\"mailpoet_select mailpoet_select_large mailpoet_automated_latest_content_content_type\">
            <option value=\"post\" {{#ifCond model.contentType '==' 'post'}}SELECTED{{/ifCond}}>";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Posts");
        echo "</option>
            <option value=\"page\" {{#ifCond model.contentType '==' 'page'}}SELECTED{{/ifCond}}>";
        // line 9
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pages");
        echo "</option>
            <option value=\"mailpoet_page\" {{#ifCond model.contentType '==' 'mailpoet_page'}}SELECTED{{/ifCond}}>";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet pages");
        echo "</option>
        </select>
    </div>
</div>

<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_select_option\">
        <select class=\"mailpoet_select mailpoet_automated_latest_content_categories_and_tags\" multiple=\"multiple\">
          {{#each model.terms}}
            <option value=\"{{ id }}\" selected=\"selected\">{{ text }}</option>
          {{/each}}
        </select>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"mailpoet_automated_latest_content_include_or_exclude\" class=\"mailpoet_automated_latest_content_include_or_exclude\" value=\"include\" {{#ifCond model.inclusionType '==' 'include'}}CHECKED{{/ifCond}}/>
            ";
        // line 26
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Include");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"mailpoet_automated_latest_content_include_or_exclude\" class=\"mailpoet_automated_latest_content_include_or_exclude\" value=\"exclude\" {{#ifCond model.inclusionType '==' 'exclude'}}CHECKED{{/ifCond}} />
            ";
        // line 32
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Exclude");
        echo "
        </label>
    </div>
</div>

<hr class=\"mailpoet_separator\" />


<div class=\"mailpoet_form_field\">
    <a href=\"javascript:;\" class=\"mailpoet_automated_latest_content_show_display_options\">";
        // line 41
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Display options");
        echo "</a>
</div>
<div class=\"mailpoet_automated_latest_content_display_options mailpoet_closed\">
    <div class=\"mailpoet_form_field\">
        <a href=\"javascript:;\" class=\"mailpoet_automated_latest_content_hide_display_options\">";
        // line 45
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Hide display options");
        echo "</a>
    </div>

    <div class=\"mailpoet_form_field\">
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_display_type\" class=\"mailpoet_automated_latest_content_display_type\" value=\"excerpt\" {{#ifCond model.displayType '==' 'excerpt'}}CHECKED{{/ifCond}}/>
                ";
        // line 52
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Excerpt");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_display_type\" class=\"mailpoet_automated_latest_content_display_type\" value=\"full\" {{#ifCond model.displayType '==' 'full'}}CHECKED{{/ifCond}}/>
                ";
        // line 58
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Full post");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_display_type\" class=\"mailpoet_automated_latest_content_display_type\" value=\"titleOnly\" {{#ifCond model.displayType '==' 'titleOnly'}}CHECKED{{/ifCond}} />
                ";
        // line 64
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Title only");
        echo "
            </label>
        </div>
    </div>

    <div class=\"mailpoet_form_field\">
        <div class=\"mailpoet_form_field_title\">";
        // line 70
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Title Format");
        echo "</div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_format\" class=\"mailpoet_automated_latest_content_title_format\" value=\"h1\" {{#ifCond model.titleFormat '==' 'h1'}}CHECKED{{/ifCond}}/>
                ";
        // line 74
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Heading 1");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_format\" class=\"mailpoet_automated_latest_content_title_format\" value=\"h2\" {{#ifCond model.titleFormat '==' 'h2'}}CHECKED{{/ifCond}}/>
                ";
        // line 80
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Heading 2");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_format\" class=\"mailpoet_automated_latest_content_title_format\" value=\"h3\" {{#ifCond model.titleFormat '==' 'h3'}}CHECKED{{/ifCond}}/>
                ";
        // line 86
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Heading 3");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option mailpoet_automated_latest_content_title_as_list {{#ifCond model.displayType '!=' 'titleOnly'}}mailpoet_hidden{{/ifCond}}\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_format\" class=\"mailpoet_automated_latest_content_title_format\" value=\"ul\" {{#ifCond model.titleFormat '==' 'ul'}}CHECKED{{/ifCond}}/>
                ";
        // line 92
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show as list");
        echo "
            </label>
        </div>
    </div>

    <div class=\"mailpoet_form_field\">
        <div class=\"mailpoet_form_field_title\">";
        // line 98
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Title Alignment");
        echo "</div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_alignment\" class=\"mailpoet_automated_latest_content_title_alignment\" value=\"left\" {{#ifCond model.titleAlignment '==' 'left'}}CHECKED{{/ifCond}} />
                ";
        // line 102
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Left");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_alignment\" class=\"mailpoet_automated_latest_content_title_alignment\" value=\"center\" {{#ifCond model.titleAlignment '==' 'center'}}CHECKED{{/ifCond}} />
                ";
        // line 108
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Center");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_alignment\" class=\"mailpoet_automated_latest_content_title_alignment\" value=\"right\" {{#ifCond model.titleAlignment '==' 'right'}}CHECKED{{/ifCond}} />
                ";
        // line 114
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Right");
        echo "
            </label>
        </div>
    </div>

    <div class=\"mailpoet_form_field mailpoet_automated_latest_content_title_as_link {{#ifCond model.titleFormat '===' 'ul'}}mailpoet_hidden{{/ifCond}}\">
        <div class=\"mailpoet_form_field_title\">";
        // line 120
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Title as links");
        echo "</div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_as_links\" class=\"mailpoet_automated_latest_content_title_as_links\" value=\"true\" {{#if model.titleIsLink}}CHECKED{{/if}}/>
                ";
        // line 124
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_title_as_links\" class=\"mailpoet_automated_latest_content_title_as_links\" value=\"false\" {{#unless model.titleIsLink}}CHECKED{{/unless}}/>
                ";
        // line 130
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
            </label>
        </div>
    </div>

    <hr class=\"mailpoet_separator mailpoet_automated_latest_content_image_separator {{#ifCond model.displayType '===' 'titleOnly'}}mailpoet_hidden{{/ifCond}}\" />

    <div class=\"mailpoet_form_field mailpoet_automated_latest_content_featured_image_position_container {{#ifCond model.displayType '!==' 'excerpt'}}mailpoet_hidden{{/ifCond}}\">
        <div class=\"mailpoet_form_field_title\">";
        // line 138
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Featured image position");
        echo "</div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_featured_image_position\" class=\"mailpoet_automated_latest_content_featured_image_position\" value=\"belowTitle\" {{#ifCond model.featuredImagePosition '==' 'belowTitle' }}CHECKED{{/ifCond}}/>
                ";
        // line 142
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Below title");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_featured_image_position\" class=\"mailpoet_automated_latest_content_featured_image_position\" value=\"aboveTitle\" {{#ifCond model.featuredImagePosition '==' 'aboveTitle' }}CHECKED{{/ifCond}}/>
                ";
        // line 148
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Above title");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_featured_image_position\" class=\"mailpoet_automated_latest_content_featured_image_position\" value=\"none\" {{#ifCond model.featuredImagePosition '==' 'none' }}CHECKED{{/ifCond}}/>
                ";
        // line 154
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("None");
        echo "
            </label>
        </div>
    </div>

    <div class=\"mailpoet_automated_latest_content_non_title_list_options {{#ifCond model.displayType '==' 'titleOnly'}}{{#ifCond model.titleFormat '==' 'ul'}}mailpoet_hidden{{/ifCond}}{{/ifCond}}\">
        <div class=\"mailpoet_form_field mailpoet_automated_latest_content_image_full_width_option {{#ifCond model.displayType '==' 'titleOnly'}}mailpoet_hidden{{/ifCond}}\">
            <div class=\"mailpoet_form_field_title\">";
        // line 161
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Image width");
        echo "</div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"imageFullWidth\" class=\"mailpoet_automated_latest_content_image_full_width\" value=\"true\" {{#if model.imageFullWidth}}CHECKED{{/if}}/>
                    ";
        // line 165
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Full width");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"imageFullWidth\" class=\"mailpoet_automated_latest_content_image_full_width\" value=\"false\" {{#unless model.imageFullWidth}}CHECKED{{/unless}}/>
                    ";
        // line 171
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Padded");
        echo "
                </label>
            </div>
        </div>

        <hr class=\"mailpoet_separator\" />

        <div class=\"mailpoet_form_field\">
            <div class=\"mailpoet_form_field_title\">";
        // line 179
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show author");
        echo "</div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_author\" class=\"mailpoet_automated_latest_content_show_author\" value=\"no\" {{#ifCond model.showAuthor '==' 'no'}}CHECKED{{/ifCond}}/>
                    ";
        // line 183
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_author\" class=\"mailpoet_automated_latest_content_show_author\" value=\"aboveText\" {{#ifCond model.showAuthor '==' 'aboveText'}}CHECKED{{/ifCond}}/>
                    ";
        // line 189
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Above text");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_author\" class=\"mailpoet_automated_latest_content_show_author\" value=\"belowText\" {{#ifCond model.showAuthor '==' 'belowText'}}CHECKED{{/ifCond}}/>
                    ";
        // line 195
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Below text");
        echo "<br />
                </label>
            </div>
            <div class=\"mailpoet_form_field_title mailpoet_form_field_title_small\">";
        // line 198
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preceded by:");
        echo "</div>
            <div class=\"mailpoet_form_field_input_option mailpoet_form_field_block\">
                <input type=\"text\" class=\"mailpoet_input mailpoet_input_full mailpoet_automated_latest_content_author_preceded_by\" value=\"{{ model.authorPrecededBy }}\" />
            </div>
        </div>

        <div class=\"mailpoet_form_field\">
            <div class=\"mailpoet_form_field_title\">";
        // line 205
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show categories");
        echo "</div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_categories\" class=\"mailpoet_automated_latest_content_show_categories\" value=\"no\" {{#ifCond model.showCategories '==' 'no'}}CHECKED{{/ifCond}}/>
                    ";
        // line 209
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_categories\" class=\"mailpoet_automated_latest_content_show_categories\" value=\"aboveText\" {{#ifCond model.showCategories '==' 'aboveText'}}CHECKED{{/ifCond}}/>
                    ";
        // line 215
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Above text");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_categories\" class=\"mailpoet_automated_latest_content_show_categories\" value=\"belowText\" {{#ifCond model.showCategories '==' 'belowText'}}CHECKED{{/ifCond}}/>
                    ";
        // line 221
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Below text");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_title mailpoet_form_field_title_small\">";
        // line 224
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preceded by:");
        echo "</div>
            <div class=\"mailpoet_form_field_input_option mailpoet_form_field_block\">
                <input type=\"text\" class=\"mailpoet_input mailpoet_input_full mailpoet_automated_latest_content_categories\" value=\"{{ model.categoriesPrecededBy }}\" />
            </div>
        </div>

        <hr class=\"mailpoet_separator\" />

        <div class=\"mailpoet_form_field\">
            <div class=\"mailpoet_form_field_title mailpoet_form_field_title_small\">";
        // line 233
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("\"Read more\" text");
        echo "</div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_read_more_type\" class=\"mailpoet_automated_latest_content_read_more_type\" value=\"link\" {{#ifCond model.readMoreType '==' 'link'}}CHECKED{{/ifCond}}/>
                    ";
        // line 237
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Link");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_read_more_type\" class=\"mailpoet_automated_latest_content_read_more_type\" value=\"button\" {{#ifCond model.readMoreType '==' 'button'}}CHECKED{{/ifCond}}/>
                    ";
        // line 243
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Button");
        echo "
                </label>
            </div>

            <div class=\"mailpoet_form_field_input_option mailpoet_form_field_block\">
                <input type=\"text\" class=\"mailpoet_input mailpoet_input_full mailpoet_automated_latest_content_read_more_text {{#ifCond model.readMoreType '!=' 'link'}}mailpoet_hidden{{/ifCond}}\" value=\"{{ model.readMoreText }}\" />
            </div>

            <div class=\"mailpoet_form_field_input_option mailpoet_form_field_block\">
                <a href=\"javascript:;\" class=\"mailpoet_automated_latest_content_select_button {{#ifCond model.readMoreType '!=' 'button'}}mailpoet_hidden{{/ifCond}}\">";
        // line 252
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Design a button");
        echo "</a>
            </div>
        </div>

        <hr class=\"mailpoet_separator\" />
    </div>

    <div class=\"mailpoet_form_field\">
        <div class=\"mailpoet_form_field_title mailpoet_form_field_title_small\">";
        // line 260
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sort by");
        echo "</div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_sort_by\" class=\"mailpoet_automated_latest_content_sort_by\" value=\"newest\" {{#ifCond model.sortBy '==' 'newest'}}CHECKED{{/ifCond}}/>
                ";
        // line 264
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Newest");
        echo "
            </label>
        </div>
        <div class=\"mailpoet_form_field_radio_option\">
            <label>
                <input type=\"radio\" name=\"mailpoet_automated_latest_content_sort_by\" class=\"mailpoet_automated_latest_content_sort_by\" value=\"oldest\" {{#ifCond model.sortBy '==' 'oldest'}}CHECKED{{/ifCond}}/>
                ";
        // line 270
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Oldest");
        echo "
            </label>
        </div>
    </div>

    <div class=\"mailpoet_automated_latest_content_non_title_list_options {{#ifCond model.displayType '==' 'titleOnly'}}{{#ifCond model.titleFormat '==' 'ul'}}mailpoet_hidden{{/ifCond}}{{/ifCond}}\">
        <div class=\"mailpoet_form_field\">
            <div class=\"mailpoet_form_field_title mailpoet_form_field_title_small\">";
        // line 277
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show divider between posts");
        echo "</div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
            <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_divider\"class=\"mailpoet_automated_latest_content_show_divider\" value=\"true\" {{#if model.showDivider}}CHECKED{{/if}}/>
                    ";
        // line 281
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
                </label>
            </div>
            <div class=\"mailpoet_form_field_radio_option\">
                <label>
                    <input type=\"radio\" name=\"mailpoet_automated_latest_content_show_divider\"class=\"mailpoet_automated_latest_content_show_divider\" value=\"false\" {{#unless model.showDivider}}CHECKED{{/unless}}/>
                    ";
        // line 287
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
                </label>
            </div>
            <div>
                <a href=\"javascript:;\" class=\"mailpoet_automated_latest_content_select_divider\">";
        // line 291
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select divider");
        echo "</a>
            </div>
        </div>

    </div>
</div>

<div class=\"mailpoet_form_field\">
    <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 299
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>

";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/automatedLatestContent/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  474 => 299,  463 => 291,  456 => 287,  447 => 281,  440 => 277,  430 => 270,  421 => 264,  414 => 260,  403 => 252,  391 => 243,  382 => 237,  375 => 233,  363 => 224,  357 => 221,  348 => 215,  339 => 209,  332 => 205,  322 => 198,  316 => 195,  307 => 189,  298 => 183,  291 => 179,  280 => 171,  271 => 165,  264 => 161,  254 => 154,  245 => 148,  236 => 142,  229 => 138,  218 => 130,  209 => 124,  202 => 120,  193 => 114,  184 => 108,  175 => 102,  168 => 98,  159 => 92,  150 => 86,  141 => 80,  132 => 74,  125 => 70,  116 => 64,  107 => 58,  98 => 52,  88 => 45,  81 => 41,  69 => 32,  60 => 26,  41 => 10,  37 => 9,  33 => 8,  26 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/automatedLatestContent/settings.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/automatedLatestContent/settings.hbs");
    }
}
