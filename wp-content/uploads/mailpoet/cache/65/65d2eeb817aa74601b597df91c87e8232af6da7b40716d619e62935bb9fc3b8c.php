<?php

/* newsletter/templates/blocks/text/block.hbs */
class __TwigTemplate_2eea9732762301af540844ab8ce8840171ea7a637da6e9867d34705898fc130d extends Twig_Template
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
<div
    class=\"mailpoet_content mailpoet_text_content\"
    {{#if model.styles.block.paddingTop }}style=\"padding-top:{{model.styles.block.paddingTop}};\"{{/if}}
>
    {{{ model.text }}}
</div>
<div class=\"mailpoet_block_highlight\"></div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/text/block.hbs";
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
        return new Twig_Source("", "newsletter/templates/blocks/text/block.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/text/block.hbs");
    }
}
