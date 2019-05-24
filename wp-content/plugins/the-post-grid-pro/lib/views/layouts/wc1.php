<?php

$html = $htmlDetail = $htmlTitle = $price = $vPrice = null;

$_product = wc_get_product( $pID );
$_rating = $_product->get_rating_html();
$pType = $_product->product_type;
$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';

        $html .= '<div class="rt-img-holder">';
            $html .= '<div class="overlay">';
                $html .= "<div class='product-more'>
                            <ul>
                                <li><a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'><i class='fa fa-shopping-cart'></i></a></li>
                                <li><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'><i class='fa fa-search'></i></a></li>
                            </ul>
                        </div> ";
            $html .= '</div>';
		if($imgSrc) {
			$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
		}
        $html .= '</div> ';

            if(in_array('title', $items)){
                $title = "<h3  class='product-title'><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'>{$title}</a></h3>";
            }
            if(!empty($_rating)){
                $_rating = "<div class='product-rating'>".$_rating."</div>";
            }

            if($_product->is_type( 'simple' )){
                $p = $_product->get_price_html();
                if($p) {
                    $price .= "<span class='price'>{$p}</span>";
                }
            }else if($_product->is_type( 'variable' )){
                global $rtTPG;
                $p = $rtTPG->custom_variation_price($_product);
                if($p) {
                    $price .= "<span class='price'>{$p}</span>";
                }
            }

            $html .= "<div class='rt-woo-info'>{$title}{$_rating}{$price}</div>";

    $html .= '</div>';
$html .='</div>';

echo $html;