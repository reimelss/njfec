<?php

/* newsletter/templates/blocks/posts/settingsSelection.hbs */
class __TwigTemplate_27dc67999b6030068ad9d6d34cdaffe6f15a2e36c61a1490b9067c03fb49d0f9 extends Twig_Template
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
        echo "<div class=\"mailpoet_settings_posts_selection_controls\">
    <div class=\"mailpoet_post_selection_filter_row\">
        <input type=\"text\" name=\"\" class=\"mailpoet_input mailpoet_input_full mailpoet_posts_search_term\" value=\"\" placeholder=\"";
        // line 3
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Search...");
        echo "\" />
    </div>
    <div class=\"mailpoet_post_selection_filter_row\"><select name=\"mailpoet_posts_content_type\" class=\"mailpoet_select mailpoet_select_half_width mailpoet_settings_posts_content_type\">
            <option value=\"post\" {{#ifCond model.contentType '==' 'post'}}SELECTED{{/ifCond}}>";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Posts");
        echo "</option>
            <option value=\"page\" {{#ifCond model.contentType '==' 'page'}}SELECTED{{/ifCond}}>";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pages");
        echo "</option>
            <option value=\"mailpoet_page\" {{#ifCond model.contentType '==' 'mailpoet_page'}}SELECTED{{/ifCond}}>";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("MailPoet pages");
        echo "</option>
        </select><select class=\"mailpoet_select mailpoet_select_half_width mailpoet_posts_post_status\">
            <option value=\"publish\" selected=\"selected\">";
        // line 10
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Published");
        echo "</option>
            <option value=\"future\">";
        // line 11
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Scheduled");
        echo "</option>
            <option value=\"draft\">";
        // line 12
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Draft");
        echo "</option>
            <option value=\"pending\">";
        // line 13
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pending Review");
        echo "</option>
            <option value=\"private\">";
        // line 14
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Private");
        echo "</option>
        </select></div>
    <div class=\"mailpoet_post_selection_filter_row\">
        <select class=\"mailpoet_select mailpoet_posts_categories_and_tags\" multiple=\"multiple\">
          {{#each model.terms}}
            <option value=\"{{ id }}\" selected=\"selected\">{{ text }}</option>
          {{/each}}
        </select>
    </div>
</div>
<div class=\"mailpoet_post_selection_container\">
</div>
<div class=\"mailpoet_post_selection_loading\" style=\"visibility: hidden;\">
  ";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Loading posts...");
        echo "
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/posts/settingsSelection.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  74 => 27,  58 => 14,  54 => 13,  50 => 12,  46 => 11,  42 => 10,  37 => 8,  33 => 7,  29 => 6,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/posts/settingsSelection.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/posts/settingsSelection.hbs");
    }
}
