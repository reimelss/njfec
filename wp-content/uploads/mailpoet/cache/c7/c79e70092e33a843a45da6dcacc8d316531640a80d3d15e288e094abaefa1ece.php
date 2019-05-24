<?php

/* subscribers/importExport/export.html */
class __TwigTemplate_75f7de6fb2da39c56adc766c2f4380bee7a8bc36b0e130fe8f8c75556e6bc643 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "subscribers/importExport/export.html", 1);
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
        echo "<div id=\"mailpoet_subscribers_export\" class=\"wrap\">
  <h1 class=\"title\">
    ";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export");
        echo "
    <a class=\"page-title-action\" href=\"?page=mailpoet-subscribers#/\">";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back to Subscribers");
        echo "</a>
  </h1>
  ";
        // line 9
        if (twig_test_empty((isset($context["segments"]) ? $context["segments"] : null))) {
            // line 10
            echo "  <div class=\"error\">
    <p>";
            // line 11
            echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yikes! Couldn't find any subscribers");
            echo "</p>
  </div>
  ";
        }
        // line 14
        echo "  <div class=\"inside\">
    <!-- Template data -->
  </div>
</div>
<script id=\"mailpoet_subscribers_export_template\" type=\"text/x-handlebars-template\">
  <div id=\"export_result_notice\" class=\"updated mailpoet_hidden\">
    <!-- Result message -->
  </div>
  <table class=\"form-table\">
    <tbody>
    {{#if exportConfirmedOption}}
    <tr>
      <th scope=\"row\">
        ";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export confirmed subscribers only");
        echo "
      </th>
      <td>
        <div>
          <label>
            <input type=\"radio\"
                   name=\"option_confirmed\" value=1>";
        // line 33
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Yes");
        echo "
          </label>
          &nbsp;
          <label>
            <input type=\"radio\"
                   name=\"option_confirmed\" value=0 checked>";
        // line 38
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("No");
        echo "
          </label>
        </div>
      </td>
    </tr>
    {{/if}}
    {{#if segments}}
    <tr>
      <th scope=\"row\">
        <label for=\"export_lists\">
          ";
        // line 48
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Pick one or multiple lists");
        echo "
        </label>
      </th>
      <td>
        <select id=\"export_lists\" data-placeholder=\"";
        // line 52
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Select", "Verb");
        echo "\" multiple=\"multiple\"></select>
      </td>
    </tr>
    {{/if}}
    <tr>
      <th scope=\"row\">
        <label for=\"export_columns\">
          ";
        // line 59
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("List of fields to export");
        echo "
        </label>
      </th>
      <td>
        <select id=\"export_columns\" data-placeholder=\"";
        // line 63
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Select", "Verb");
        echo "\" multiple=\"multiple\"></select>
      </td>
    </tr>
    {{#if groupBySegmentOption}}
    <tr class=\"mailpoet_group_by_list mailpoet_hidden\">
      <th scope=\"row\">
        ";
        // line 69
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Group subscribers by list");
        echo "
      </th>
      <td>
        <input type=\"checkbox\" name=\"option_group_by_list\" checked>
      </td>
    </tr>
    {{/if}}
    <tr>
      <th scope=\"row\">
        ";
        // line 78
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Format");
        echo "
      </th>
      <td>
        <label>
          <input type=\"radio\" name=\"option_format\"
           value=\"csv\"
           checked>";
        // line 84
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("CSV file");
        echo "
        </label>
        &nbsp;
        <label>
          <input type=\"radio\" name=\"option_format\"
           value=\"xlsx\">";
        // line 89
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Excel file");
        echo "
        </label>
      </td>
    </tr>
    <tr class=\"mailpoet_export_process\">
      <th scope=\"row\">
        <a href=\"javascript:;\"
         class=\"button-primary button-disabled wysija mailpoet_export_process\">";
        // line 96
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Export");
        echo "</a>
      </th>
    </tr>
    </tbody>
  </table>
</script>
</div>

";
        // line 104
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateStylesheet("importExport.css");
        echo "

<script type=\"text/javascript\">
  var
    segments = ";
        // line 108
        echo (isset($context["segments"]) ? $context["segments"] : null);
        echo ",
    segmentsWithConfirmedSubscribers =
      ";
        // line 110
        echo (isset($context["segmentsWithConfirmedSubscribers"]) ? $context["segmentsWithConfirmedSubscribers"] : null);
        echo ",
    subscriberFieldsSelect2 =
      ";
        // line 112
        echo (isset($context["subscriberFieldsSelect2"]) ? $context["subscriberFieldsSelect2"] : null);
        echo ",
    exportData = {
     segments: segments.length || null,
     segmentsWithConfirmedSubscribers: segmentsWithConfirmedSubscribers.length || null,
     exportConfirmedOption: true,
     groupBySegmentOption: (segments.length > 1 || segmentsWithConfirmedSubscribers.length > 1) ? true : null
    };
</script>
";
    }

    // line 122
    public function block_translations($context, array $blocks = array())
    {
        // line 123
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("serverError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Server error:"), "exportMessage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s subscribers were exported. Get the exported file [link]here[/link].")));
        // line 126
        echo "
";
    }

    public function getTemplateName()
    {
        return "subscribers/importExport/export.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  219 => 126,  217 => 123,  214 => 122,  201 => 112,  196 => 110,  191 => 108,  184 => 104,  173 => 96,  163 => 89,  155 => 84,  146 => 78,  134 => 69,  125 => 63,  118 => 59,  108 => 52,  101 => 48,  88 => 38,  80 => 33,  71 => 27,  56 => 14,  50 => 11,  47 => 10,  45 => 9,  40 => 7,  36 => 6,  32 => 4,  29 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "subscribers/importExport/export.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/subscribers/importExport/export.html");
    }
}
