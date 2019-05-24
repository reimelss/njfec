<?php
$_product = wc_get_product( $pID );
$price = null;
if(edd_has_variable_prices(get_the_ID())){
    $price = "<span class='price'>".edd_price_range(get_the_ID(), false)."</span>";
}else{
    $price = "<span class='price'>".edd_price(get_the_ID(), false)."</span>";
}
$html = $htmlDetail = $htmlTitle = null;

$html .= "<div class='{$grid} {$class}'>";
$html .= '<div class="rt-holder">';

        $html .= '<div class="rt-img-holder">';
            $html .= '<div class="overlay">';
                $html .= "<a class='view-search' href='{$pLink}'><i class='fa fa-search'></i></a>";
            $html .= '</div> ';
            $html .= "<a href='{$pLink}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
        $html .= '</div> ';

        if(in_array('title', $items)){
            $htmlTitle = "<h2><a href='{$pLink}'>{$title}</a></h2>";
        }

        $htmlDetail .= $htmlTitle . $_rating;
        if(!empty($htmlDetail)){
            $html .= "<div class='rt-detail'>{$htmlDetail}</div>";
        }


$html .= '</div>';
    $html .="<ul class='product-meta'><li><a class ='add-to-cart' href='?add-to-cart={$pID}'>".__("Add To Cart", "the-post-grid")."</a></li><li>{$price}</li></ul>";
$html .='</div>';

echo $html;