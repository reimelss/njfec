<?php

/* form/templates/preview.hbs */
class __TwigTemplate_4dcae7a3fd54a136576eb80b05f7042ba4017b991b611f0e241d2f093d084b87 extends Twig_Template
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
        echo "<style type=\"text/css\" id=\"mailpoet_form_preview_styles\">{{{ css }}}</style>
<div class=\"mailpoet_form\">
  <div class=\"mailpoet_message\">
    <p class=\"mailpoet_validate_success\">";
        // line 4
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This is a success message.");
        echo "</p>
    <p class=\"mailpoet_validate_error\">";
        // line 5
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("This is an error message.");
        echo "</p>
  </div>
  {{{ html }}}
</div>
";
    }

    public function getTemplateName()
    {
        return "form/templates/preview.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  28 => 5,  24 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "form/templates/preview.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/form/templates/preview.hbs");
    }
}
