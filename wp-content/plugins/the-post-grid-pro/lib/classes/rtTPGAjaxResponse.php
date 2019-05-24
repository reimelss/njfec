<?php

if(!class_exists('rtTPGAjaxResponse')):

    class rtTPGAjaxResponse
    {
        function __construct(){
            add_action(	'wp_ajax_rtTPGSettings', array($this, 'rtTPGSaveSettings'));
            add_action( 'wp_ajax_rtTPGShortCodeList', array($this, 'shortCodeList'));
            add_action( 'wp_ajax_rtTPGTaxonomyListByPostType', array($this, 'rtTPGTaxonomyListByPostType'));
            add_action( 'wp_ajax_rtTPGIsotopeFilter', array($this, 'rtTPGIsotopeFilter'));
            add_action( 'wp_ajax_rtTPGTermListByTaxonomy', array($this, 'rtTPGTermListByTaxonomy'));
            add_action( 'wp_ajax_defaultFilterItem', array($this, 'defaultFilterItem'));
            add_action( 'wp_ajax_getCfGroupListAsField', array($this, 'getCfGroupListAsField'));
        }

        function getCfGroupListAsField(){
            global $rtTPG;
            $error = true;
            $data = $msg = null;
            if($rtTPG->verifyNonce()){
	            $fields = array();
            	$post_type = !empty($_REQUEST['post_type']) ? $_REQUEST['post_type'] : null;
	            if ( $cf = $rtTPG->checkWhichCustomMetaPluginIsInstalled() && $post_type) {
		            $fields['cf_group'] = array(
			            "type"        => "checkbox",
			            "name"        => "cf_group",
			            "holderClass" => "tpg-hidden cf-fields cf-group",
			            "label"       => "Custom Field group",
			            "multiple"    => true,
			            "alignment"   => "vertical",
			            "id"          => "cf_group",
			            "options"     => $rtTPG->get_groups_by_post_type( $post_type, $cf )
		            );
		            $error = false;
		            $data = $rtTPG->rtFieldGenerator( $fields );
	            }
            }else{
                $msg = __('Server Error !!', 'the-post-grid-pro');
            }
            $response = array(
                'error'=> $error,
                'msg' => $msg,
                'data' => $data
            );
            wp_send_json( $response );
            die();
        }

        function defaultFilterItem(){
            global $rtTPG;
            $rtTPG->rtTPGSettingFields();
            $error = true;
            $data = $msg = null;
            if($rtTPG->verifyNonce()){
                if($filter = $_REQUEST['filter']){
                    $error = false;
                    $msg = __('Success', 'the-post-grid-pro');
                    $data .= "<option value=''>".__('Show All', 'the-post-grid-pro')."</option>";
                    $items = $rtTPG->rt_get_all_term_by_taxonomy($filter);
                    if(!empty($items)){
                        foreach ($items as $id => $item){
                            $data .= "<option value='{$id}'>{$item}</option>";
                        }
                    }
                }
            }else{
                $msg = __('Session Error !!', 'the-post-grid-pro');
            }
            $response = array(
                'error'=> $error,
                'msg' => $msg,
                'data' => $data
            );
            wp_send_json( $response );
            die();
        }

        function rtTPGSaveSettings(){
            global $rtTPG;
            $rtTPG->rtTPGSettingFields();
            $error = true;
            if($rtTPG->verifyNonce()){
                unset($_REQUEST['action']);
                unset($_REQUEST[$rtTPG->nonceId()]);
                unset($_REQUEST['_wp_http_referer']);
                update_option( $rtTPG->options['settings'], $_REQUEST);
                $response = array(
                    'error'=> true,
                    'msg' => __('Settings successfully updated', 'the-post-grid-pro')
                );
            }else{
                $response = array(
                    'error'=> $error,
                    'msg' => __('Session Error !!', 'the-post-grid-pro')
                );
            }
            wp_send_json( $response );
            die();
        }

        function rtTPGTaxonomyListByPostType(){
            global $rtTPG;
            $error = true;
            $msg = $data = null;
            if($rtTPG->verifyNonce()){
                $error = false;
                $taxonomies = $rtTPG->rt_get_all_taxonomy_by_post_type(isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : null);
                if(is_array($taxonomies) && !empty($taxonomies) ){
                    $data .= $rtTPG->rtFieldGenerator(
                        array(
                        	'tpg_taxonomy' => array(
                            'type' => 'checkbox',
                            'label' => 'Taxonomy',
                            'id' => 'post-taxonomy',
                            "multiple" => true,
                            'options' => $taxonomies
	                        )
                        )
                    );
                }else{
                    $data = __('<div class="field-holder">No Taxonomy found</div>', 'the-post-grid-pro');
                }

            }else{
                $msg = __('Security error', 'the-post-grid-pro');
            }
            wp_send_json( array('error' => $error, 'msg' => $msg, 'data' => $data) );
            die();
        }

        function rtTPGIsotopeFilter(){
            global $rtTPG;
            $error = true;
            $msg = $data = null;
            if($rtTPG->verifyNonce()){
                $error = false;
                $taxonomies = $rtTPG->rt_get_taxonomy_for_filter(isset($_REQUEST['post_type']) ? $_REQUEST['post_type'] : null);
                if(is_array($taxonomies) && !empty($taxonomies) ){
                    foreach($taxonomies as $tKey => $tax){
                        $data .= "<option value='{$tKey}'>{$tax}</option>";
                    }
                }
            }else{
                $msg = __('Security error', 'the-post-grid-pro');
            }
            wp_send_json( array('error' => $error, 'msg' => $msg, 'data' => $data) );
            die();
        }

        function rtTPGTermListByTaxonomy(){
            global $rtTPG;
            $error = true;
            $msg = $data = null;
            if($rtTPG->verifyNonce()){
                $error = false;
                $taxonomy = isset($_REQUEST['taxonomy']) ? $_REQUEST['taxonomy'] : null;
                $data .="<div class='term-filter-item-container {$taxonomy}'>";
                    $data .= $rtTPG->rtFieldGenerator(
                        array(
	                        'term_'.$taxonomy => array(
	                            'type' => 'select',
	                            'label' => ucfirst(str_replace('_', ' ', $taxonomy)),
	                            'class' => 'rt-select2 full',
	                            'id'    => 'term-'.mt_rand(),
	                            'holderClass' => "term-filter-item {$taxonomy}",
	                            'value' => null,
	                            "multiple" => true,
	                            'options' => $rtTPG->rt_get_all_term_by_taxonomy($taxonomy)
	                        )
                        )
                    );
                    $data .= $rtTPG->rtFieldGenerator(
                        array(
	                        'term_operator_'.$taxonomy => array(
                                'type' => 'select',
	                            'label' => 'Operator',
	                            'class' => 'rt-select2 full',
	                            'holderClass' => "term-filter-item-operator {$taxonomy}",
	                            'options' => $rtTPG->rtTermOperators()
	                        )
                        )
                    );
                $data .="</div>";
            }else{
                $msg = __('Security error', 'the-post-grid-pro');
            }
            wp_send_json( array('error' => $error, 'msg' => $msg, 'data' => $data) );
            die();
        }

        function shortCodeList(){
            global $rtTPG;
            $html = null;
            $scQ = new WP_Query( array('post_type' => $rtTPG->post_type, 'order_by' => 'title', 'order' => 'DESC', 'post_status' => 'publish', 'posts_per_page' => -1) );
            if ( $scQ->have_posts() ) {

                $html .= "<div class='mce-container mce-form'>";
                $html .= "<div class='mce-container-body'>";
                $html .= '<label class="mce-widget mce-label" style="padding: 20px;font-weight: bold;" for="scid">'.__('Select Short code',  'the-post-grid-pro').'</label>';
                $html .= "<select name='id' id='scid' style='width: 150px;margin: 15px;'>";
                $html .= "<option value=''>".__('Default',  'the-post-grid-pro')."</option>";
                while ( $scQ->have_posts() ) {
                    $scQ->the_post();
                    $html .="<option value='".get_the_ID()."'>".get_the_title()."</option>";
                }
                $html .= "</select>";
                $html .= "</div>";
                $html .= "</div>";
            }else{
                $html .= "<div>".__('No shortCode found.', 'the-post-grid-pro')."</div>";
            }
            echo $html;
            die();
        }

    }

endif;