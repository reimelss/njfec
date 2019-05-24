<?php

$html = $htmlDetail =null;
$postMetaBottom2 = null;
if(in_array('social_share', $items)){
    global $rtTPG;
    $postMetaBottom2 .= $rtTPG->rtShare($pID);
}
if(in_array('read_more', $items)){
    $postMetaBottom2 .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'>{$read_more_text}</a></span>";
}
$html .= "<div class='{$grid} {$class} {$isoFilter}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
if($imgSrc) {
	$html .= '<div class="rt-img-holder">';
	$html .= "<a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'><img class='rt-img-responsive' src='{$imgSrc}' alt='{$title}'></a>";
	$html .= '</div> ';
}
            if(in_array('title', $items)){
                $htmlDetail .= "<h3 class='entry-title'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'>{$title}</a></h3>";
            }
            $postMetaTop = $postMetaMid =null;

            if(in_array('categories', $items) && $categories){
                $postMetaTop .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
            }
            if(in_array('tags', $items) && $tags){
                $postMetaTop .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
            }
                
            if(!empty($postMetaTop)){
                $htmlDetail .= "<div class='post-meta-tags'>{$postMetaTop}</div>";
            }
            if(!empty($postMetaMid)){
                $htmlDetail .= "<div class='post-meta-tags'>{$postMetaMid}</div>";
            }
            //$html .= "</hr>";
            if(in_array('excerpt', $items)){
                $htmlDetail .= "<div class='entry-content'><p>{$excerpt}</p></div>";
            }
            $postMetaBottom = null;
            
            if(in_array('author', $items)){
                $postMetaBottom .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
            }
            if(in_array('post_date', $items) && $date){
              $postMetaBottom .= "<span class='date'><i class='fa fa-calendar'></i>{$date}</span>";
            }
            if(in_array('comment_count', $items) && $comment){
                $postMetaBottom .= "<span class='comment-link'><i class='fa fa-comments-o'></i>{$comment}</span>";
            }
            if(!empty($htmlDetail)){
                $html .="<div class='rt-detail'>$htmlDetail</div>";
            }
            if(!empty($postMetaBottom2)){
                $html .= "<div class='post-meta'>$postMetaBottom2</div>";
            }
            if(!empty($postMetaBottom)){
                $html .= "<div class='post-meta-user'>$postMetaBottom</div>";
            }
    $html .= '</div>';
$html .='</div>';

echo $html;