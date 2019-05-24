<?php global $rtTPG; ?>

<div class="wrap">
    <div id="upf-icon-edit-pages" class="icon32 icon32-posts-page"><br /></div>
    <h2><?php _e('The Post Grid Settings', 'the-post-grid-pro'); ?></h2>
    <h3><?php _e('General settings', 'the-post-grid-pro');?>
        <a style="margin-left: 15px; font-size: 15px;" href="http://demo.radiustheme.com/wordpress/plugins/the-post-grid/" target="_blank"><?php _e('Documentation',  'the-post-grid-pro') ?></a>
    </h3>

    <div class="rt-setting-wrapper">
        <div class="rt-response"></div>
        <form id="rt-tpg-settings-form">
            <?php

            $html = null;
            $html .='<div id="settings-tabs" class="rt-tabs rt-tab-container">';
                $html .='<ul class="tab-nav rt-tab-nav">
								<li><a href="#popup-fields">'.__('PopUp field selection', 'the-post-grid-pro').'</a></li>
                                <li><a href="#social-share">'.__('Social Share','the-post-grid-pro').'</a></li>
								<li><a href="#custom-css">'.__('Custom Css', 'the-post-grid-pro').'</a></li>
                                <li><a href="#other-settings">'.__('Other Settings','the-post-grid-pro').'</a></li>
                                <li><a href="#plugin-license">'.__('Plugin License','the-post-grid-pro').'</a></li>
							  </ul>';

                $html .= '<div id="popup-fields" class="rt-tab-content">';
                    $html .= $rtTPG->rtFieldGenerator($rtTPG->rtTpgSettingsDetailFieldSelection());
                $html .='</div>';

                $html .= '<div id="social-share" class="rt-tab-content">';
                $html .= $rtTPG->rtFieldGenerator($rtTPG->rtTPGSettingsSocialShareFields());
                $html .='</div>';

                $html .= '<div id="custom-css" class="rt-tab-content">';
                    $html .= $rtTPG->rtFieldGenerator($rtTPG->rtTPGSettingsCustomCssFields());
                $html .='</div>';

                $html .= '<div id="other-settings" class="rt-tab-content">';
                    $html .= $rtTPG->rtFieldGenerator($rtTPG->rtTPGSettingsOtherSettingsFields(), true);
                $html .='</div>';

                $html .= '<div id="plugin-license" class="rt-tab-content">';
                    $html .= $rtTPG->rtFieldGenerator($rtTPG->rtTPGLicenceField());
                    $settings = get_option( $rtTPG->options['settings'] );
                    $status = !empty($settings['license_status']) &&  $settings['license_status'] === 'valid' ? 'valid' : false;
                    if(!empty( $settings['license_key']) ){
	                    $html .= '<div class="field-holder " id="license_activation_holder">
                                <div class="field-label"><label>Activate License</label></div>
                                <div class="field">';
                                if( $status !== false && $status == 'valid' ) {
	                                $html .= "<input type='submit' class='button-secondary rt-licensing-btn danger' name='license_deactivate' value='Deactivate License'/>";
                                }else{
	                                $html .= "<input type='submit' class='button-secondary rt-licensing-btn button-primary' name='license_activate' value='Activate License'/>";
                                }
                        $html .='</div></div>';
                    }
                $html .='</div>';

            $html .='</div>';

            echo $html;
            ?>
            <p class="submit-wrap"><input type="submit" name="submit" class="button button-primary rtSaveButton" value="Save Changes"></p>

            <?php wp_nonce_field( $rtTPG->nonceText(), $rtTPG->nonceId() ); ?>
        </form>

        <div class="rt-response"></div>
    </div>
</div>
