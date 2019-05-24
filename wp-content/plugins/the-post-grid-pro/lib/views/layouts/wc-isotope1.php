<?php

$_product = wc_get_product( $pID );
$_rating = $_product->get_rating_html();
$pType = $_product->product_type;
$html = $htmlDetail = $htmlTitle =null;

$html .= "<div class='{$grid} {$class} {$isoFilter}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';

if($imgSrc) {
	$html .= '<div class="rt-img-holder">';
	$html .= '<div class="overlay">';
	$html .= "<div class='product-more'>
                            <ul>
                                <li><a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'><i class='fa fa-shopping-cart'></i></a></li>
                                <li><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'><i class='fa fa-search'></i></a></li>
                            </ul>
                        </div> ";
	$html .= '</div>';
	$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
	$html .= '</div> ';
}
            if(in_array('title', $items)){
                $title = "<h3 class='product-title'><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'>{$title}</a></h3>";
            }
            if(!empty($_rating)){
                $_rating = "<div class='product-rating'>".$_rating."</div>";
            }

            $price = "<span class='price'>".$_product->get_price_html()."</span>";

            $html .= "<div class='rt-woo-info rt-detail'>{$title}{$_rating}{$price}</div>";

    $html .= '</div>';
$html .='</div>';

echo $html;