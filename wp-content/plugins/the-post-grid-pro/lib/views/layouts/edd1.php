<?php

$html = $htmlDetail = $htmlTitle =null;
$price = null;
if(edd_has_variable_prices(get_the_ID())){
    $price = "<span class='price'>".edd_price_range(get_the_ID(), false)."</span>";
}else{
    $price = "<span class='price'>".edd_price(get_the_ID(), false)."</span>";
}
$html .= "<div class='{$grid} {$class}'>";
    $html .= '<div class="rt-holder">';

        $html .= '<div class="rt-img-holder">';
            $html .= '<div class="overlay">';
                $html .= "<div class='product-more'>
                            <ul>
                                <li><a class='{$anchorClass}' data-id='{$pID}'><i class='fa fa-shopping-cart'></i></a></li>
                                <li><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'><i class='fa fa-search'></i></a></li>
                            </ul>
                        </div> ";
            $html .= '</div>';
            $html .= "<a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
        $html .= '</div> ';
            if(in_array('title', $items)){
                $title = "<h2><a class='{$anchorClass}' data-id='{$pID}' href='{$pLink}'>{$title}</a></h2>";
            }
            $html .= "<div class='rt-woo-info'>{$title}{$price}</div>";
    $html .= '</div>';
$html .='</div>';

echo $html;