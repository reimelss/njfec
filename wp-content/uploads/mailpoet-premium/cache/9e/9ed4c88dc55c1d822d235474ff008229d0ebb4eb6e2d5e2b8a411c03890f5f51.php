<?php

/* dynamicSegments.html */
class __TwigTemplate_0f0ab7f497f568d41f841697b53ca2cb2bea1a41312703a59e5e4af212eac4f5 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "

";
        // line 3
        $this->displayBlock('content', $context, $blocks);
    }

    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
  <div id=\"dynamic_segments_container\"></div>

  <script type=\"text/javascript\">
    var mailpoet_listing_per_page = ";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["items_per_page"]) ? $context["items_per_page"] : null), "html", null, true);
        echo ";
    var wordpress_editable_roles_list = ";
        // line 9
        echo json_encode((isset($context["wordpress_editable_roles_list"]) ? $context["wordpress_editable_roles_list"] : null));
        echo ";
    var mailpoet_newsletters_list = ";
        // line 10
        echo json_encode((isset($context["newsletters_list"]) ? $context["newsletters_list"] : null));
        echo ";
  </script>

";
    }

    public function getTemplateName()
    {
        return "dynamicSegments.html";
    }

    public function getDebugInfo()
    {
        return array (  44 => 10,  40 => 9,  36 => 8,  30 => 4,  24 => 3,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "dynamicSegments.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/dynamicSegments.html");
    }
}
