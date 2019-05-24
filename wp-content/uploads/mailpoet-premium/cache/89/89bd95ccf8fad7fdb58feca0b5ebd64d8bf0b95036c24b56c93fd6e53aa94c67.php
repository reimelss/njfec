<?php

/* newsletters/automatic_emails.html */
class __TwigTemplate_79e5041ace37c63895056a37ec9201bfe109a40a69efda417df40c85b2818bc8 extends Twig_Template
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
        echo "<script type=\"text/javascript\">
  var mailpoet_premium_automatic_emails = ";
        // line 2
        echo json_encode(($context["automatic_emails"] ?? null));
        echo ";
</script>

";
        // line 5
        $this->displayBlock('translations', $context, $blocks);
    }

    public function block_translations($context, array $blocks = array())
    {
        // line 6
        echo "  ";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->localize(array("automaticEmail" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Automatic Email"), "tip" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Tip:"), "selectAutomaticEmailsEventsConditionsHeading" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Select %1s events conditions"), "sendAutomaticEmailWhenHeading" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Send this %1s Automatic Email when..."), "automaticEmailActivated" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your %1s Automatic Email is now activated!"), "automaticEmailActivationFailed" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Your %1s Automatic Email could not be activated, please check the settings."), "listingScheduleSendToCustomer" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("This email is sent %1s to customer."), "listingScheduleSendToList" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("This email is sent %1s to %2s."), "automaticEmailEventOptionsNotConfigured" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("You need to configure email options before this email can be sent."), "sentToXCustomers" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Sent to %\$1d customers"), "feedbackButton" => $this->env->getExtension('MailPoet\Twig\I18n')->translate("Click [link]here[/link] to give your feedback on this feature. We’d love to hear from you.")));
        // line 18
        echo "
";
    }

    public function getTemplateName()
    {
        return "newsletters/automatic_emails.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 18,  35 => 6,  29 => 5,  23 => 2,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "newsletters/automatic_emails.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet-premium/views/newsletters/automatic_emails.html");
    }
}
