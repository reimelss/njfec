<?php

/* dynamicSegmentsTranslations.html */
class __TwigTemplate_42aa3eba46e0371cc1cd31f679c1a6205efae41cc54dd1dfeb9a017779122923 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'translations' => array($this, 'block_translations'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        $this->displayBlock('translations', $context, $blocks);
    }

    public function block_translations($context, array $blocks = array())
    {
        // line 2
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("pageTitle" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Segments (beta)"), "formPageTitle" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Segment (beta)"), "formSegmentTitle" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Segment"), "new" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Add New"), "backToList" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back"), "name" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Name"), "description" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Description"), "descriptionTip" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("This text box is for your own use and is never shown to your subscribers."), "segmentUpdated" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("List successfully updated!"), "segmentAdded" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("List successfully added!"), "save" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Save"), "segmentType" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Type"), "wpUserRole" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("WordPress user roles"), "email" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Email"), "nameColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Name"), "subscribersCountColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Number of subscribers"), "updatedAtColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Modified on"), "loadingDynamicSegmentItems" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Loading data…"), "noDynamicSegmentItemsFound" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No segments found"), "numberOfItemsSingular" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("1 item"), "numberOfItemsMultiple" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("%\$1d items"), "previousPage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Previous page"), "firstPage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("First page"), "nextPage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Next page"), "lastPage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Last page"), "currentPage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Current page"), "pageOutOf" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("of", "Page X of Y"), "edit" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Edit"), "viewSubscribers" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("View Subscribers"), "notSentYet" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Not sent yet"), "selectLinkPlaceholder" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select link"), "selectNewsletterPlaceholder" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select newsletter"), "selectActionPlaceholder" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select action"), "selectUserRolePlaceholder" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select user role"), "emailActionOpened" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("opened", "Dynamic segment creation: when newsletter was opened"), "emailActionNotOpened" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("not opened", "Dynamic segment creation: when newsletter was not opened"), "emailActionClicked" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("clicked", "Dynamic segment creation: when a newsletter link was clicked"), "emailActionNotClicked" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("not clicked", "Dynamic segment creation: when a newsletter link was not clicked"), "searchLabel" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Search")));
        // line 42
        echo "
";
    }

    public function getTemplateName()
    {
        return "dynamicSegmentsTranslations.html";
    }

    public function getDebugInfo()
    {
        return array (  29 => 42,  26 => 2,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "dynamicSegmentsTranslations.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/dynamicSegmentsTranslations.html");
    }
}
