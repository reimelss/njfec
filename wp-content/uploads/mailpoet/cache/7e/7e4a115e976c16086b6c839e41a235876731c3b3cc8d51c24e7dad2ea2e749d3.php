<?php

use MailPoetVendor\Twig\Environment;
use MailPoetVendor\Twig\Error\LoaderError;
use MailPoetVendor\Twig\Error\RuntimeError;
use MailPoetVendor\Twig\Markup;
use MailPoetVendor\Twig\Sandbox\SecurityError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedTagError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFilterError;
use MailPoetVendor\Twig\Sandbox\SecurityNotAllowedFunctionError;
use MailPoetVendor\Twig\Source;
use MailPoetVendor\Twig\Template;

/* subscribers/importExport/import/step_data_manipulation.html */
class __TwigTemplate_516ddac29ce012382276ee133dbb1bd588bd01124b85ea4c5c05ad6886b19a1d extends \MailPoetVendor\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<div id=\"step_data_manipulation\" class=\"mailpoet_hidden\">
  <div id=\"subscribers_data_parse_results\">
    <!-- Template data -->
  </div>

  <script id=\"subscribers_data_parse_results_template\" type=\"text/x-handlebars-template\">
    <div class=\"error\">
      <p>{{{notice}}} <a class=\"mailpoet_subscribers_data_parse_results_details_show\" href=\"javascript:;\">";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show more details");
        echo "</a><p>
      <div class=\"mailpoet_subscribers_data_parse_results_details mailpoet_hidden\">
        <hr>
        {{#if duplicate}}
        <p>{{{duplicate}}}</p>
        {{/if}}
        {{#if invalid}}
        <p>{{{invalid}}}</p>
        {{/if}}
      </div>
    </div>
  </script>

  <div class=\"inside\">
    <br>
    <!-- Subscribers Data -->
    <div id=\"subscribers_data\">
      <table class=\"mailpoet_subscribers widefat fixed\">
        <!-- Template data -->
      </table>
    </div>

    <table class=\"mailpoet_subscribers form-table\">
      <tbody>
      <!-- MP3 Segments -->
      <tr class=\"mailpoet_segments mailpoet_hidden\">
        <th scope=\"row\">
          <label>
            ";
        // line 36
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pick one or more list(s)");
        echo "
            <p class=\"description\">";
        // line 37
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pick the list that you want to import these subscribers to.");
        echo "
          </label>
        </th>
        <td>
          <select id=\"mailpoet_segments_select\" data-placeholder=\"";
        // line 41
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Select", "Verb");
        echo "\" multiple=\"multiple\"></select>
          <a href=\"javascript:;\" class=\"mailpoet_create_segment\">";
        // line 42
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Create a new list");
        echo "</a>
        </td>
      </tr>
      <tr class=\"mailpoet_no_segments mailpoet_hidden\">
        <th scope=\"row\">
          ";
        // line 47
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("To add subscribers to a mailing segment, [link]create a list[/link]."), "javascript:;", ["target" => "_blank", "class" => "mailpoet_create_segment"]);
        // line 51
        echo "
        </th>
      </tr>
      <tr>
        <th scope=\"row\">
          <label>
            ";
        // line 57
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Update existing subscribers' information");
        echo "
          </label>
        </th>
        <td>
          <label>
            <input type=\"radio\" name=\"subscriber_update_option\" value=\"yes\"
             checked><span>";
        // line 63
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "</span>
          </label>
          <label>
            <input type=\"radio\" name=\"subscriber_update_option\"
             value=\"no\"><span>";
        // line 67
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "</span>
          </label>
        </td>
      </tr>
      <tr>
        <th style=\"width: 300px;\">
          <a href=\"javascript:;\" id=\"return_to_previous\"
             class=\"button-primary wysija button\">";
        // line 74
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Previous step");
        echo " </a>
          &nbsp;&nbsp;
          <a href=\"javascript:;\" id=\"next_step\"
           data-automation-id=\"import-next-step\"
           class=\"button-primary wysija button-disabled\">";
        // line 78
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Next step");
        echo " </a>
        </th>
      </tr>
      </tbody>
    </table>

    <!-- subscribers data template -->
    <script id=\"subscribers_data_template\" type=\"text/x-handlebars-template\">
      <thead>
      <th>
        ";
        // line 88
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Match data");
        echo "
      </th>
      {{#show_and_match_columns .}}
      {{#.}}
      <th>
        <select class=\"mailpoet_subscribers_column_data_match\" data-column-id=\"{{column_id}}\" data-validation-rule=\"false\" id=\"column_{{@index}}\">
      </th>
      {{/.}}
      {{/show_and_match_columns}}
      </thead>
      <tbody>
      {{> subscribers_data_template_partial}}
      </tbody>
    </script>

    <script id=\"subscribers_data_template_partial\" type=\"text/x-handlebars-template\">
      {{#if header}}
      <tr class=\"mailpoet_header\">
        <td></td>
        {{#header}}
        <td>
          {{this}}
        </td>
        {{/header}}
      </tr>
      {{/if}}
      {{#subscribers}}
      <tr>
        <td>
          {{calculate_index @index}}
        </td>
        {{#.}}
        <td>
          {{sanitize_data this}}
        </td>
        {{/.}}
      </tr>
      {{/subscribers}}
    </script>

    <!-- New segment template -->
    <script id=\"new_segment_template\" type=\"text/x-handlebars-template\">
      <p>
        <label>";
        // line 131
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Name");
        echo ":</label>
        <input id=\"new_segment_name\" type=\"text\" name=\"name\"/>
      </p>
      <p class=\"mailpoet_validation_error\" data-error=\"segment_name_required\">
        ";
        // line 135
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please specify a name.");
        echo "
      </p>
      <p class=\"mailpoet_validation_error\" data-error=\"segment_name_not_unique\">
        ";
        // line 138
        echo \MailPoetVendor\twig_escape_filter($this->env, sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("Another record already exists. Please specify a different \"%1\$s\"."), "name"), "html", null, true);
        echo "
      </p>
      <p>
        <label>";
        // line 141
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Description");
        echo ":</label>
        <br/>
        <textarea id=\"new_segment_description\" cols=\"40\" rows=\"3\" name=\"description\"/>
      </p>

      <hr/>

      <p class=\"mailpoet_align_right\">
        <input type=\"submit\" value=\"";
        // line 149
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done");
        echo "\" id=\"new_segment_process\"
         class=\"button-primary \"/>
        <input type=\"submit\" value=\"";
        // line 151
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Cancel");
        echo "\" id=\"new_segment_cancel\"
         class=\"button-primary\"/>
      </p>

      </form>
    </script>

    <!-- New custom field logic -->
    ";
        // line 159
        $this->loadTemplate("form/custom_fields.html", "subscribers/importExport/import/step_data_manipulation.html", 159)->display($context);
        // line 160
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "subscribers/importExport/import/step_data_manipulation.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  243 => 160,  241 => 159,  230 => 151,  225 => 149,  214 => 141,  208 => 138,  202 => 135,  195 => 131,  149 => 88,  136 => 78,  129 => 74,  119 => 67,  112 => 63,  103 => 57,  95 => 51,  93 => 47,  85 => 42,  81 => 41,  74 => 37,  70 => 36,  39 => 8,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "subscribers/importExport/import/step_data_manipulation.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/subscribers/importExport/import/step_data_manipulation.html");
    }
}
