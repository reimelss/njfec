<?php

/* subscribers/importExport/import/step1.html */
class __TwigTemplate_af963d3bb8f58225a966fce2d0700af5f38bd23eba6a997f41a0c30a305dffee extends Twig_Template
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
        echo "<div id=\"step1\" class=\"mailpoet_hidden\">
  <div class=\"inside\">
    <!-- Method selection -->
    <table class=\"mailpoet_subscribers form-table\">
      <tbody>
      <tr>
        <th scope=\"row\">
          ";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("How would you like to import subscribers?");
        echo "
        </th>
        <td>
          <div id=\"select_method\">
            <label>
              <input type=\"radio\" name=\"select_method\"><span>";
        // line 13
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Paste the data into a text box");
        echo "</span>
            </label> <label>
            <input type=\"radio\" name=\"select_method\"><span>";
        // line 15
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Upload a file");
        echo "</span>
          </label> <label>
            <input type=\"radio\" name=\"select_method\"><span>";
        // line 17
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Import from MailChimp");
        echo "</php></span>
          </label>
          </div>
        </td>
      </tr>
      </tbody>
    </table>

    ";
        // line 25
        $context["badgeClassName"] = (((($context["is_new_user"] ?? null) == true)) ? ("mailpoet_badge mailpoet_badge_video") : ("mailpoet_badge mailpoet_badge_video mailpoet_badge_video_grey"));
        // line 26
        echo "    <a class=\"";
        echo twig_escape_filter($this->env, ($context["badgeClassName"] ?? null), "html", null, true);
        echo "\" href=\"https://beta.docs.mailpoet.com/article/242-video-guide-importing-subscribers-using-a-csv-file\" target=\"_blank\">
        <span class=\"dashicons dashicons-format-video\"></span>";
        // line 27
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("See video guide");
        echo "
    </a>

    <!-- Paste -->
    <div id=\"method_paste\" class=\"mailpoet_hidden\">
      <table class=\"mailpoet_subscribers form-table\">
        <tbody>
        <tr>
          <th scope=\"row\">
            <label for=\"paste_input\"> ";
        // line 36
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Copy and paste your subscribers from Excel/Spreadsheets");
        echo "
              <p class=\"description\">
                ";
        // line 38
        echo MailPoet\Util\Helpers::replaceLinkTags(($context["csvDescription"] ?? null), ($context["csvKBLink"] ?? null), array("target" => "_blank"));
        echo "
              </p>
            </label>
          </th>
          <td>
            <textarea id=\"paste_input\" rows=\"15\" data-placeholder=\"Email, First Name, Last Name\\njohn@doe.com, John, Doe\\nmary@smith.com, Mary, Smith\\njohnny@walker.com, Johnny, Walker\" class=\"regular-text code\"></textarea>
          </td>
        </tr>
        </tbody>
      </table>
      <div class=\"mailpoet_method_process\">
        <!-- Template data -->
      </div>
    </div>

    <!-- CSV file -->
    <div id=\"method_file\" class=\"mailpoet_hidden\">
      <table class=\"mailpoet_subscribers form-table\">
        <tbody>
        <tr>
          <th scope=\"row\">
            <label for=\"file_local\">
              ";
        // line 60
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Upload a file");
        echo "
              <p class=\"description\">
                ";
        // line 62
        echo MailPoet\Util\Helpers::replaceLinkTags(($context["csvDescription"] ?? null), ($context["csvKBLink"] ?? null), array("target" => "_blank"));
        echo "
              </p>
            </label>
          </th>
          <td>
            <input type=\"file\" id=\"file_local\" accept=\".csv\" />
          </td>
        </tr>
        </tbody>
      </table>
      <div class=\"mailpoet_method_process\">
        <!-- Template data -->
      </div>
    </div>

    <!-- Mailchimp -->
    <div id=\"method_mailchimp\" class=\"mailpoet_hidden\">
      <table class=\"mailpoet_subscribers form-table\">
        <tbody>
        <tr>
          <th scope=\"row\">
            <label for=\"mailchimp_key\">
              ";
        // line 84
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Enter your MailChimp API key");
        echo "
            </label>
          </th>
          <td>
            <input type=\"text\" id=\"mailchimp_key\">
            <button id=\"mailchimp_key_verify\" class=\"button\">";
        // line 89
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Verify");
        echo "</button>
            <span class=\"mailpoet_mailchimp-key-status\"></span>
          </td>
        </tr>
        <tr id=\"mailchimp_lists\" class=\"mailpoet_hidden\">
          <th scope=\"row\">
            <label>
              ";
        // line 96
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select list(s)");
        echo "
            </label>
          </th>
          <td>
            <select class=\"mailchimp_lists_select\" data-placeholder=\"";
        // line 100
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Select", "Verb");
        echo "\" multiple=\"multiple\"></select>
          </td>
        </tr>
        </tbody>
      </table>
      <div class=\"mailpoet_method_process\">
        <!-- Template data -->
      </div>
    </div>

    <!-- Next button & spam notice template -->
    <script id=\"method_process_template\" type=\"text/x-handlebars-template\">
      <table class=\"mailpoet_subscribers form-table mailpoet_hidden\">
        <tbody>
        <tr>
          <th scope=\"row\">
            ";
        // line 116
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Did these subscribers ask to be in your list?");
        echo "
            <p class=\"description\">
              ";
        // line 118
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("If the answer is \"no\", consider yourself a spammer.");
        echo "
              <br/>
              ";
        // line 120
        echo MailPoet\Util\Helpers::replaceLinkTags($this->env->getExtension('MailPoet\Twig\I18n')->translate("[link]Read more at our Knowledge Base[/link]"), "http://docs.mailpoet.com/article/127-checklist-before-importing-subscribers", array("target" => "_blank"));
        // line 124
        echo "
            </p>
          </th>
        </tr>
        <tr>
          <th scope=\"row\">
            <a href=\"javascript:;\"
             class=\"button-primary button-disabled wysija mailpoet_process\">";
        // line 131
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Next step");
        echo " </a>
          </th>
        </tr>
        </tbody>
      </table>
    </script>
  </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "subscribers/importExport/import/step1.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  201 => 131,  192 => 124,  190 => 120,  185 => 118,  180 => 116,  161 => 100,  154 => 96,  144 => 89,  136 => 84,  111 => 62,  106 => 60,  81 => 38,  76 => 36,  64 => 27,  59 => 26,  57 => 25,  46 => 17,  41 => 15,  36 => 13,  28 => 8,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "subscribers/importExport/import/step1.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/subscribers/importExport/import/step1.html");
    }
}
