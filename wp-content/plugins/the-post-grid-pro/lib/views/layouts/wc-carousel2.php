<?php
$_product = wc_get_product( $pID );
$price = "<span class='price'>".$_product->get_price_html()."</span>";
$_rating = $_product->get_rating_html();
$pType = $_product->product_type;
if(!empty($_rating)){
    $_rating = "<div class='product-rating'>".$_rating."</div>";
}
$html = $htmlDetail = $htmlTitle = null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
$html .= '<div class="rt-holder">';

	if($imgSrc) {
		$html .= '<div class="rt-img-holder">';
		$html .= "<a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
		$html .= '</div> ';
	}
    if(in_array('title', $items)){
        $htmlTitle = "<h3 class='product-title'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'>{$title}</a></h3>";
    }
    $html_pinfo .="<div class='product-meta'>
    <div class='price-area'>{$price}</div>
    <div><a href='{$pLink}?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'>".__("Add To Cart", "the-post-grid-pro")."</a></div>
    </div>";
    $htmlDetail .= $htmlTitle . $_rating. $html_pinfo;
    if(!empty($htmlDetail)){
        $html .= "<div class='rt-detail rt-woo-info'>{$htmlDetail}</div>";
    }
$html .= '</div>';
$html .='</div>';
echo $html;