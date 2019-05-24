<?php

/* subscribers/importExport/import/step2.html */
class __TwigTemplate_a4c534a9a9b91b688d3f817493c338679947b3f92542a52d614a10b97cff1210 extends Twig_Template
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
        echo "<div id=\"step2\" class=\"mailpoet_hidden\">
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
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("To add subscribers to a mailing segment, [link]create a list[/link]."), "javascript:;", array("target" => "_blank", "class" => "mailpoet_create_segment"));
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
        <th>
          <a href=\"javascript:;\" id=\"return_to_step1\"
             class=\"button-primary wysija button\">";
        // line 74
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Previous step");
        echo " </a>
          &nbsp;&nbsp;
          <a href=\"javascript:;\" id=\"step2_process\"
           class=\"button-primary wysija button-disabled\">";
        // line 77
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
        // line 87
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
        // line 130
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Name");
        echo ":</label>
        <input id=\"new_segment_name\" type=\"text\" name=\"name\"/>
      </p>
      <p class=\"mailpoet_validation_error\" data-error=\"segment_name_required\">
        ";
        // line 134
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please specify a name.");
        echo "
      </p>
      <p class=\"mailpoet_validation_error\" data-error=\"segment_name_not_unique\">
        ";
        // line 137
        echo twig_escape_filter($this->env, sprintf($this->env->getExtension('MailPoet\Twig\I18n')->translate("Another record already exists. Please specify a different \"%1\$s\"."), "name"), "html", null, true);
        echo "
      </p>
      <p>
        <label>";
        // line 140
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Description");
        echo ":</label>
        <br/>
        <textarea id=\"new_segment_description\" cols=\"40\" rows=\"3\" name=\"description\"/>
      </p>

      <hr/>

      <p class=\"mailpoet_align_right\">
        <input type=\"submit\" value=\"";
        // line 148
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Done");
        echo "\" id=\"new_segment_process\"
         class=\"button-primary \"/>
        <input type=\"submit\" value=\"";
        // line 150
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Cancel");
        echo "\" id=\"new_segment_cancel\"
         class=\"button-primary\"/>
      </p>

      </form>
    </script>

    <!-- New custom field logic -->
    ";
        // line 158
        $this->loadTemplate("form/custom_fields.html", "subscribers/importExport/import/step2.html", 158)->display($context);
        // line 159
        echo "  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "subscribers/importExport/import/step2.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  231 => 159,  229 => 158,  218 => 150,  213 => 148,  202 => 140,  196 => 137,  190 => 134,  183 => 130,  137 => 87,  124 => 77,  118 => 74,  108 => 67,  101 => 63,  92 => 57,  84 => 51,  82 => 47,  74 => 42,  70 => 41,  63 => 37,  59 => 36,  28 => 8,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "subscribers/importExport/import/step2.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/subscribers/importExport/import/step2.html");
    }
}
