<?php

/* newsletter/templates/blocks/text/settings.hbs */
class __TwigTemplate_cbb0d50c7fe83b2385989b2f0c5769f301c440e421423152b50319abf491ecff extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo "</h3>
";
        // line 2
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Text");
        echo ": <textarea name=\"text\" class=\"text\" rows=\"5\" cols=\"40\">{{ model.text }}</textarea>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/text/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/text/settings.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/text/settings.hbs");
    }
}
