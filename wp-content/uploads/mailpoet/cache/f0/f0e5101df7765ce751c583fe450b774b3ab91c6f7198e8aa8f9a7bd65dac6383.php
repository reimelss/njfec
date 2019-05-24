<?php

/* newsletter/templates/blocks/container/settings.hbs */
class __TwigTemplate_f4e1ac5e22048a078b8e8df88e366dc1137b2fb8d0422bc30195fec7b717d60e extends Twig_Template
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
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Columns");
        echo "</h3>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"text\" name=\"background-color\" class=\"mailpoet_field_container_background_color mailpoet_color\" value=\"{{ model.styles.block.backgroundColor }}\" />
        </div>
        <div class=\"mailpoet_form_field_title mailpoet_form_field_title_inline\">";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Background color");
        echo "</div>
    </label>
</div>
<div class=\"mailpoet_form_field\">
    <label>
        <div class=\"mailpoet_form_field_title\">";
        // line 12
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Background image");
        echo "</div>
        <div class=\"mailpoet_form_field_input_option\">
            <input type=\"text\" name=\"src\" class=\"mailpoet_input mailpoet_field_image_address\" value=\"{{ model.image.src }}\" placeholder=\"http://\" /><br />
        </div>
    </label>
</div>
<div class=\"mailpoet_form_field\">
    <input type=\"button\" name=\"select-image\" class=\"button button-secondary mailpoet_button_full mailpoet_field_image_select_image\" value=\"{{#if model.image.src}}";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select another image"), "html_attr");
        echo "{{else}}";
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select image"), "html_attr");
        echo "{{/if}}\" />
</div>
<div class=\"mailpoet_form_field\">
    <div class=\"mailpoet_form_field_title\">";
        // line 22
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Display options");
        echo "</div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"display_type\" class=\"mailpoet_field_display_type\" value=\"scale\" {{#ifCond model.image.display '===' 'scale'}}CHECKED{{/ifCond}}/>
            ";
        // line 26
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Scale");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"display_type\" class=\"mailpoet_field_display_type\" value=\"fit\" {{#ifCond model.image.display '===' 'fit'}}CHECKED{{/ifCond}}/>
            ";
        // line 32
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Fit");
        echo "
        </label>
    </div>
    <div class=\"mailpoet_form_field_radio_option\">
        <label>
            <input type=\"radio\" name=\"display_type\" class=\"mailpoet_field_display_type\" value=\"tile\" {{#ifCond model.image.display '===' 'tile'}}CHECKED{{/ifCond}}/>
            ";
        // line 38
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Tile");
        echo "
        </label>
    </div>
</div>

<div class=\"mailpoet_form_field\">
    <input type=\"button\" class=\"button button-primary mailpoet_done_editing\" value=\"";
        // line 44
        echo twig_escape_filter($this->env, $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done"), "html_attr");
        echo "\" />
</div>
";
    }

    public function getTemplateName()
    {
        return "newsletter/templates/blocks/container/settings.hbs";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 44,  80 => 38,  71 => 32,  62 => 26,  55 => 22,  47 => 19,  37 => 12,  29 => 7,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletter/templates/blocks/container/settings.hbs", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/newsletter/templates/blocks/container/settings.hbs");
    }
}
