<?php

/* scripts.html */
class __TwigTemplate_afa142b954b59b87c64b1d1ea6d984ca29637f1206a6dbe99dfc860818d44dae extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateJavascript("premium.js");
    }

    public function getTemplateName()
    {
        return "scripts.html";
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
        return new Twig_Source("", "scripts.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/scripts.html");
    }
}
