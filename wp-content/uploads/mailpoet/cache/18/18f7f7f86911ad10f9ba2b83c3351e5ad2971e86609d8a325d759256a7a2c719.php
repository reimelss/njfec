<?php

/* blank.html */
class __TwigTemplate_037cc7e9c107b3d0cac0eba64da44abddd95b3cd536cf1301041c20e8a3c2b40 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "blank.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'translations' => array($this, 'block_translations'),
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
        echo "  ";
        echo do_action(("mailpoet_pages_" . (isset($context["page_name"]) ? $context["page_name"] : null)));
        echo "
";
    }

    // line 7
    public function block_translations($context, array $blocks = array())
    {
        // line 8
        echo "  ";
        echo do_action(("mailpoet_pages_translations_" . (isset($context["page_name"]) ? $context["page_name"] : null)));
        echo "
";
    }

    public function getTemplateName()
    {
        return "blank.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 8,  39 => 7,  32 => 4,  29 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "blank.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/blank.html");
    }
}
