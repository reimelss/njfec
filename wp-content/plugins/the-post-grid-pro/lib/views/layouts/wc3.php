<?php
$_product = wc_get_product( $pID );
$price = "<span class='price'>".$_product->get_price_html()."</span>";
$_rating = null;
$_rating = $_product->get_rating_html();
$pType = $_product->product_type;
if(!empty($_rating)){
    $_rating = "<div class='product-rating'>".$_rating."</div>";
}
$html = $htmlDetail = $htmlTitle =null;

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
if($imgSrc) {
	$html .= "<div class='rt-col-xs-12 rt-col-sm-12 rt-col-md-5 rt-col-lg-5'>";
	$html .= "<div class='grid-img rt-img-holder'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a></div>";
	$html .= "</div>";
	$content_area = "rt-col-xs-12 rt-col-sm-12 rt-col-md-7 rt-col-lg-7";
}else{
	$content_area = "rt-col-md-12";
}
        $html .= "<div class='{$content_area}'>";
            $html .= "<div class='rt-detail rt-woo-info'>";
                if(in_array('title', $items)){
                    $html .= "<h3 class='product-title'><a href='{$pLink}' class='{$anchorClass}' data-id='{$pID}'>{$title}</a> {$price}</h3>";
                }
                $html .=$_rating;
                $html .="<p>{$excerpt}</p>";
                $html .="<a href='?add-to-cart={$pID}' class='rt-wc-add-to-cart' data-id='{$pID}' data-type='{$pType}'>".__("Add To Cart", "the-post-grid-pro")."</a>";
            $html .= '</div>';
        $html .= '</div>';
    $html .= '</div>';
$html .='</div>';

echo $html;