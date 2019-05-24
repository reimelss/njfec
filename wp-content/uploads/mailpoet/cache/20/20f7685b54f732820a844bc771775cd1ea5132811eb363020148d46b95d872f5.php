<?php

/* invalidkey.html */
class __TwigTemplate_458b74c471b18ac5772f1545b6c08f4966d368fd823f42ff7b9c9ec356e6edcb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "invalidkey.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
<div class=\"wrap mailpoet-about-wrap\">
  <h1>";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("All sending is currently paused!");
        echo "</h1>

  <p class=\"about-text\">
    ";
        // line 9
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your key to send with MailPoet is invalid.");
        echo "
  </p>

  <p>
      <a class=\"button button-primary\" target=\"_blank\" href=\"https://account.mailpoet.com?s=";
        // line 13
        echo twig_escape_filter($this->env, (isset($context["subscriber_count"]) ? $context["subscriber_count"] : null), "html", null, true);
        echo "\">";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Visit MailPoet.com to purchase a key");
        echo "</a>
    </p>
</div>
";
    }

    public function getTemplateName()
    {
        return "invalidkey.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  48 => 13,  41 => 9,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "invalidkey.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/invalidkey.html");
    }
}
