<?php

/* update.html */
class __TwigTemplate_246e26f3806e3e4026fd097118afdb90760c53b9958dbd0e124c7022fcb3932d extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.html", "update.html", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
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
        echo "
<div class=\"wrap mailpoet-about-wrap\">
  <h1>";
        // line 6
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Greetings, humans.");
        echo "</h1>

  <p class=\"about-text\">";
        // line 8
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("The new MailPoet. Simply better. And with regular updates.");
        echo "</p>
  <div class=\"mailpoet-logo\"><img src=\"";
        // line 9
        echo $this->env->getExtension('MailPoet\Twig\Assets')->generateImageUrl("welcome_template/mailpoet-logo.png");
        echo "\" alt=\"MailPoet Logo\" /></div>

  <h2 class=\"nav-tab-wrapper wp-clearfix\">
    <a href=\"admin.php?page=mailpoet-welcome\" class=\"nav-tab\">";
        // line 12
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Welcome");
        echo "</a>
    <a href=\"admin.php?page=mailpoet-update\" class=\"nav-tab nav-tab-active\">";
        // line 13
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("What's New");
        echo "</a>
  </h2>

  <div id=\"mailpoet-changelog\" class=\"feature-section one-col\">
    <h2 class=\"mailpoet-feature-top\">";
        // line 17
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("List of Changes");
        echo "</h2>
    ";
        // line 18
        if ((isset($context["changelog"]) ? $context["changelog"] : null)) {
            // line 19
            echo "      ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["changelog"]) ? $context["changelog"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 20
                echo "        <h3>";
                echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "version", array()), "html", null, true);
                echo "</h3>
        <ul>
          ";
                // line 22
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["item"], "changes", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["change"]) {
                    // line 23
                    echo "            <li>";
                    echo twig_escape_filter($this->env, $context["change"], "html", null, true);
                    echo "</li>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['change'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 25
                echo "        </ul>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            echo "    ";
        } else {
            // line 28
            echo "      <p style=\"text-align: center\">";
            echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("See readme.txt for a changelog.");
            echo "</p>
    ";
        }
        // line 30
        echo "    <a class=\"button button-secondary\" href=\"https://wordpress.org/plugins/mailpoet/#developers\" target=\"_blank\">";
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("View all changes");
        echo " &rarr;</a>
  </div>

  <div class=\"feature-section one-col mailpoet_centered\">
    <h2>";
        // line 34
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Care to Give Your Opinion?");
        echo "</h2>

    ";
        // line 36
        if ((isset($context["is_new_user"]) ? $context["is_new_user"] : null)) {
            // line 37
            echo "      <div class=\"pd-embed\" id=\"pd_1516701033\"></div>
      <script type=\"text/javascript\">
        var _polldaddy = [] || _polldaddy;

        _polldaddy.push( {
          type: \"iframe\",
          auto: \"1\",
          domain: \"mailpoet.polldaddy.com/s/\",
          id: \"experience-so-far\",
          placeholder: \"pd_1516701033\"
        } );

        (function(d,c,j){if(!document.getElementById(j)){var pd=d.createElement(c),s;pd.id=j;pd.src=('https:'==document.location.protocol)?'https://polldaddy.com/survey.js':'http://i0.poll.fm/survey.js';s=document.getElementsByTagName(c)[0];s.parentNode.insertBefore(pd,s);}}(document,'script','pd-embed'));
      </script>
    ";
        } else {
            // line 52
            echo "      <script type=\"text/javascript\" charset=\"utf-8\" src=\"https://secure.polldaddy.com/p/9927957.js\"></script>
      <noscript><a href=\"https://polldaddy.com/poll/9927957/\">In what category are you in?</a></noscript>
    ";
        }
        // line 55
        echo "
  <hr>

  <div class=\"feature-section one-col mailpoet_centered\">
    <a class=\"button button-primary go-to-plugin\" href=\"admin.php?page=mailpoet-newsletters\">";
        // line 59
        echo $this->env->getExtension('MailPoet\Twig\I18n')->translate("Awesome! Now, take me to MailPoet");
        echo " &rarr;</a>
  </div>

</div>

<script type=\"text/javascript\">
  jQuery(function(\$) {
    \$(function() {
      MailPoet.trackEvent(
        'User has updated MailPoet',
        {'MailPoet Free version': window.mailpoet_version}
      );
    });
    \$('#mailpoet_analytics_enabled').on('click', function() {
      var is_enabled = \$(this).is(':checked') ? true : '';
      MailPoet.Ajax.post({
        api_version: window.mailpoet_api_version,
        endpoint: 'settings',
        action: 'set',
        data: {
          analytics: { enabled: (is_enabled)}
        }
      }).fail(function(response) {
        if (response.errors.length > 0) {
          MailPoet.Notice.error(
            response.errors.map(function(error) { return error.message; }),
            { scroll: true }
          );
        }
      });
    });

  });

</script>
";
    }

    public function getTemplateName()
    {
        return "update.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  150 => 59,  144 => 55,  139 => 52,  122 => 37,  120 => 36,  115 => 34,  107 => 30,  101 => 28,  98 => 27,  91 => 25,  82 => 23,  78 => 22,  72 => 20,  67 => 19,  65 => 18,  61 => 17,  54 => 13,  50 => 12,  44 => 9,  40 => 8,  35 => 6,  31 => 4,  28 => 3,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "update.html", "/home3/njdecorg/public_html/wp-content/plugins/mailpoet/views/update.html");
    }
}
