<?php

/* newsletter/templates/blocks/footer/block.hbs */
class __TwigTemplate_40f75127c1a7963b4a9336c383689bbc51a302089fadaa1b92110f1fbf02bad5 extends Twig_Template
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
        echo "<div class=\"mailpoet_tools\"></div>
<style type=\"text/css\">
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content,
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content p {
        color: {{ model.styles.text.fontColor }};
        font-family: {{fontWithFallback model.styles.text.fontFamily }};
        font-size: {{ model.styles.text.fontSize }};
        background-color: {{ model.styles.block.backgroundColor }};
        text-align: {{ model.styles.text.textAlign }};
    }
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content a,
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content a:hover,
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content a:active,
    .mailpoet_editor_view_{{ viewCid }} .mailpoet_content a:visited {
        color: {{ model.styles.link.fontColor }};
        text-decoration: {{ model.styles.link.textDecoration }};
    }
</style>
<div class=\"mailpoet_content mailpoet_text_content\">{{{ model.text }}}</div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/footer/block.hbs";
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
        return new Twig_Source("", "newsletter/templates/blocks/footer/block.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/footer/block.hbs");
    }
}
