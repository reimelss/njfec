<?php

/* newsletters/ga_tracking.html */
class __TwigTemplate_4d3cbe974076b8fc61be25575eff673461da6a5bde469561ba10339918c06da6 extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("gaCampaignLine" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Google Analytics Campaign"), "gaCampaignTip" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("For example, \"Spring email\". [link]Read the guide.[/link]")));
    }

    public function getTemplateName()
    {
        return "newsletters/ga_tracking.html";
    }

    public function isTraitable()
    {
        return false;
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
        return new Twig_Source("", "newsletters/ga_tracking.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/newsletters/ga_tracking.html");
    }
}
