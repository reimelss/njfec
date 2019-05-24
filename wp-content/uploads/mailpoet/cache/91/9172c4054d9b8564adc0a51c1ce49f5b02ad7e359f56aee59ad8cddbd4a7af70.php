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

/* subscribers/importExport/import.html */
class __TwigTemplate_4beb1fb697dcb754e92bb1a2669a649655fa70971b4ff90f6f6c4a2e624e4f93 extends \MailPoetVendor\Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        // line 3
        $this->parent = $this->loadTemplate("layout.html", "subscribers/importExport/import.html", 3);
        $this->blocks = [
            'content' => [$this, 'block_content'],
            'translations' => [$this, 'block_translations'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "layout.html";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["csvDescription"] = $this->env->getExtension('MailPoet\Twig\I18n')->translate("This file needs to be formatted in a CSV style (comma-separated-values.) Look at some [link]examples on our support site[/link].");
        // line 2
        $context["csvKBLink"] = "http://docs.mailpoet.com/article/126-importing-subscribers-with-csv-files";
        // line 3
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_content($context, array $blocks = [])
    {
        // line 5
        echo "<div id=\"mailpoet_subscribers_import\" class=\"wrap\">
  <h1 class=\"title\">
    ";
        // line 7
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Import");
        echo "
    <a class=\"page-title-action\" href=\"?page=mailpoet-subscribers#/\">";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back to Subscribers");
        echo "</a>
  </h1>
  <!-- STEP 1: method selection -->
  ";
        // line 11
        $this->loadTemplate("subscribers/importExport/import/step_method_selection.html", "subscribers/importExport/import.html", 11)->display($context);
        // line 12
        echo "  <!-- STEP 2: Input validation -->
  ";
        // line 13
        $this->loadTemplate("subscribers/importExport/import/step_input_validation.html", "subscribers/importExport/import.html", 13)->display($context);
        // line 14
        echo "  <!-- STEP 3: subscriber data manipulation -->
  ";
        // line 15
        $this->loadTemplate("subscribers/importExport/import/step_data_manipulation.html", "subscribers/importExport/import.html", 15)->display($context);
        // line 16
        echo "  <!-- STEP 4: results -->
  ";
        // line 17
        $this->loadTemplate("subscribers/importExport/import/step_results.html", "subscribers/importExport/import.html", 17)->display($context);
        // line 18
        echo "</div>

";
        // line 20
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateStylesheet("importExport.css");
        echo "

<script type=\"text/javascript\">
  var
    maxPostSize = '";
        // line 24
        echo \MailPoetVendor\twig_escape_filter($this->env, (isset($context["maxPostSize"]) ? $context["maxPostSize"] : null), "html", null, true);
        echo "',
    maxPostSizeBytes = '";
        // line 25
        echo \MailPoetVendor\twig_escape_filter($this->env, (isset($context["maxPostSizeBytes"]) ? $context["maxPostSizeBytes"] : null), "html", null, true);
        echo "',
    importData = {},
    mailpoetColumnsSelect2 = ";
        // line 27
        echo (isset($context["subscriberFieldsSelect2"]) ? $context["subscriberFieldsSelect2"] : null);
        echo ",
    mailpoetColumns = ";
        // line 28
        echo (isset($context["subscriberFields"]) ? $context["subscriberFields"] : null);
        echo ",
    mailpoetSegments = ";
        // line 29
        echo (isset($context["segments"]) ? $context["segments"] : null);
        echo ";
    ";
        // line 30
        $context["newUser"] = ((((isset($context["is_new_user"]) ? $context["is_new_user"] : null) == true)) ? ("true") : ("false"));
        // line 31
        echo "    var mailpoet_is_new_user = ";
        echo \MailPoetVendor\twig_escape_filter($this->env, (isset($context["newUser"]) ? $context["newUser"] : null), "html", null, true);
        echo ";
</script>
";
    }

    // line 35
    public function block_translations($context, array $blocks = [])
    {
        // line 36
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(["noMailChimpLists" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No active lists found"), "serverError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Server error:"), "select" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select", "Form input type"), "csvKBLink" =>         // line 40
(isset($context["csvKBLink"]) ? $context["csvKBLink"] : null), "wrongFileFormat" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Only comma-separated (CSV) file formats are supported."), "maxPostSizeNotice" => \MailPoetVendor\twig_replace_filter($this->env->getExtension('MailPoet\Twig\I18n')->translate("Your CSV is over %s and is too big to process. Please split the file into two or more sections."), ["%s" =>         // line 42
(isset($context["maxPostSize"]) ? $context["maxPostSize"] : null)]), "dataProcessingError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your data could not be processed. Please make sure it is in the correct format."), "noValidRecords" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No valid records were found. This file needs to be formatted in a CSV style (comma-separated). Look at some [link]examples on our support site.[/link]"), "importNoticeSkipped" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s records had issues and were skipped."), "importNoticeInvalid" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s emails are not valid: %2\$s"), "importNoticeDuplicate" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s emails appear more than once in your file: %2\$s"), "hideDetails" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Hide details"), "showDetails" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Show more details"), "segmentSelectionRequired" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Please select at least one list"), "addNewList" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add new list"), "addNewField" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add new field"), "addNewColumuserColumnsn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add new list"), "userColumns" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("User fields"), "selectedValueAlreadyMatched" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("The selected value is already matched to another field."), "confirmCorrespondingColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Confirm that this field corresponds to the selected field."), "columnContainInvalidElement" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("One of the fields contains an invalid email. Please fix it before continuing."), "january" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("January"), "february" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("February"), "march" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("March"), "april" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("April"), "may" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("May"), "june" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("June"), "july" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("July"), "august" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("August"), "september" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("September"), "october" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("October"), "november" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("November"), "december" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("December"), "noDateFieldMatch" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Do not match as a 'date field' if most of the rows for that field return the same error."), "emptyFirstRowDate" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("First row date cannot be empty."), "verifyDateMatch" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Verify that the date in blue matches the original date."), "pm" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("PM"), "am" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("AM"), "dateMatchError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Error matching date"), "columnContainsInvalidDate" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("One of the fields contains an invalid date. Please fix before continuing."), "listCreateError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Error adding a new list:"), "columnContainsInvalidElement" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("One of the fields contains an invalid email. Please fix before continuing."), "customFieldCreateError" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Custom field could not be created"), "subscribersCreated" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s subscribers added to %2\$s."), "subscribersUpdated" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%1\$s existing subscribers were updated and added to %2\$s."), "subscribersAgreed" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("The subscribers on this list agreed to receive your emails"), "importNoAction" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No subscribers were added or updated."), "importNoWelcomeEmail" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Note: Imported subscribers will not receive any Welcome Emails"), "dontEmailSubscribers" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Don’t email subscribers who didn’t signup to your list. If you do, consider yourself a spammer."), "readSupportArticle" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Read the support article."), "sentOnceYear" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("You have sent to this list at least once in the last year"), "emailAddressesWillBounce" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Email addresses that no longer exist will bounce. Spam filters will be suspicious if 5% of your list bounces."), "useServices" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Use services like %1\$s, %2\$s or %3\$s to clean your lists before sending with MailPoet."), "youUnderstand" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("You understand the risk of not respecting the above"), "weWillSuspend" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("If you send with the MailPoet Sending Service, we will automatically suspend your account if our systems detect bad behavior."), "previousStep" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Previous step"), "nextStep" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Next step"), "seeVideo" => $this->env->getExtension('MailPoet\Twig\I18n')->translate(" See video guide"), "importAgain" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Import again"), "viewSubscribers" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("View subscribers"), "methodPaste" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Paste the data into a text box"), "methodUpload" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Upload a file"), "methodMailChimp" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Import from MailChimp"), "methodMailChimpLabel" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Enter your MailChimp API key"), "methodMailChimpVerify" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Verify"), "methodMailChimpSelectList" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select list(s)"), "methodMailChimpSelectPlaceholder" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Select", "Verb"), "pasteLabel" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Copy and paste your subscribers from Excel/Spreadsheets"), "pasteDescription" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("This file needs to be formatted in a CSV style (comma-separated-values.) Look at some [link]examples on our support site[/link]."), "methodSelectionHead" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("How would you like to import subscribers?")]);
        // line 107
        echo "
";
    }

    public function getTemplateName()
    {
        return "subscribers/importExport/import.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  128 => 107,  126 => 42,  125 => 40,  124 => 36,  121 => 35,  113 => 31,  111 => 30,  107 => 29,  103 => 28,  99 => 27,  94 => 25,  90 => 24,  83 => 20,  79 => 18,  77 => 17,  74 => 16,  72 => 15,  69 => 14,  67 => 13,  64 => 12,  62 => 11,  56 => 8,  52 => 7,  48 => 5,  45 => 4,  41 => 3,  39 => 2,  37 => 1,  22 => 3,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "subscribers/importExport/import.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/subscribers/importExport/import.html");
    }
}
