<?php

/* newsletter/templates/blocks/container/oneColumnLayoutWidget.hbs */
class __TwigTemplate_5068329016b183a14591a673d618b8fc290ad253da03e2cfc910e120501f3569 extends Twig_Template
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
        echo "<div class=\"mailpoet_widget_icon\">
";
        // line 2
        echo twig_source($this->env, "newsletter/templates/svg/layout-icons/1-column.svg");
        echo "
</div>
<div class=\"mailpoet_widget_title\">";
        // line 4
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("1 column");
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/container/oneColumnLayoutWidget.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  27 => 4,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/container/oneColumnLayoutWidget.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/container/oneColumnLayoutWidget.hbs");
    }
}
