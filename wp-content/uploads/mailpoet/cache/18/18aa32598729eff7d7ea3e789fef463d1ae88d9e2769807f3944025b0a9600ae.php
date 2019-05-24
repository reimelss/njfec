<?php

/* newsletter/templates/blocks/posts/settingsSinglePost.hbs */
class __TwigTemplate_6c4f35a56970f6915924462f15a230b2fcc8e61d1365068887e76da5b80f2eef extends Twig_Template
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
        echo "<div class=\"mailpoet_settings_posts_single_post\">
    <label>
        <input id=\"mailpoet_select_post_{{ index }}\" class=\"mailpoet_select_post_checkbox\" type=\"checkbox\" class=\"checkbox\" value=\"\" name=\"post_selection\">
        {{#ellipsis model.post_title 40 '...'}}{{/ellipsis}}
    </label>
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/posts/settingsSinglePost.hbs";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/posts/settingsSinglePost.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/posts/settingsSinglePost.hbs");
    }
}
