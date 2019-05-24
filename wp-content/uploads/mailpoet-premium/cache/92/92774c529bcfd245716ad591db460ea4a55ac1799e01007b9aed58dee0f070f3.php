<?php

/* newsletters/campaign_stats.html */
class __TwigTemplate_b1855ebcab3f10776214bc077e4d7452334441256ba1c522909d83bbd0c064ae extends Twig_Template
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
        echo "<script type=\"text/javascript\">
  var mailpoet_shortcode_links = ";
        // line 2
        echo json_encode((isset($context["shortcode_links"]) ? $context["shortcode_links"] : null));
        echo ";
</script>

";
        // line 5
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("statsTitle" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Stats"), "loadingStats" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Loading..."), "backToList" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Back"), "statsPreviewNewsletter" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Preview in browser"), "statsDateSent" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Date"), "statsFromAddress" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("From"), "statsToSegments" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("To"), "statsReplyToAddress" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Reply-to"), "statsTotalSent" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sent to"), "percentageOpened" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("opened", "Percentage of subscribers that opened a newsletter link"), "percentageClicked" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("clicked", "Percentage of subscribers that clicked a newsletter link"), "percentageUnsubscribed" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("unsubscribed", "Percentage of subscribers that unsubscribed from a newsletter"), "readMoreOnStats" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Read more on stats."), "googleAnalytics" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Google Analytics campaign name"), "clickedLinks" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Clicked Links"), "noClickedLinksFound" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No clicked links found"), "linkColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Link"), "uniqueClicksColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Unique clicks"), "subscriberEngagement" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Subscriber Engagement"), "loadingEngagementItems" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Loading data..."), "noEngagementItemsFound" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("No engagement data found"), "subscriberColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Subscriber"), "statusColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Status"), "dateAndTimeColumn" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Date and time"), "opened" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Opened", "Subscriber engagement status - subscriber opened a newsletter"), "clicked" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Clicked", "Subscriber engagement status - subscriber clicked a newsletter link"), "unsubscribed" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Unsubscribed", "Subscriber engagement status - subscriber unsubscribed from a newsletter"), "unopened" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Unopened", "Subscriber engagement status - subscriber did not open a newsletter"), "createSegment" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Create Segment", "Button label on Subscriber engagement page"), "savingSegment" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Saving..."), "successMessage" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Segment \"%s\" created. [link]Create and send an email to it.[/link]"), "segmentExists" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Segment already exists."), "deletedSubscriber" => $this->env->getExtension('MailPoet\Twig\I18n')->translateWithContext("Deleted subscriber", "A fallback for an email of a deleted subscriber")));
        // line 39
        echo "
";
    }

    public function getTemplateName()
    {
        return "newsletters/campaign_stats.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 39,  28 => 5,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletters/campaign_stats.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/newsletters/campaign_stats.html");
    }
}
