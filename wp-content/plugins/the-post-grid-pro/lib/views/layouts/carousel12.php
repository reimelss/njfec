<?php
$html = $titleHtml = $metaHtml = $contentHtml= null;
if(in_array('title', $items)){
    $titleHtml .= "<h3><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'>{$title}</a></h3>";
}
if(in_array('post_date', $items) && $date){
    $metaHtml .= "<span class='date-meta'><i class='fa fa-calendar'></i> {$date}</span>";
}
if(in_array('author', $items)){
    $metaHtml .= "<span class='author'><i class='fa fa-user'></i>{$author}</span>";
 }
if(in_array('categories', $items) && $categories){
    $metaHtml .= "<span class='categories-links'><i class='fa fa-folder-open-o'></i>{$categories}</span>";
}
if(in_array('tags', $items) && $tags){
    $metaHtml .= "<span class='post-tags-links'><i class='fa fa-tags'></i>{$tags}</span>";
}
$num_comments = get_comments_number(); // get_comments_number returns only a numeric value
if(in_array('comment_count', $items) && $comment){
    $metaHtml .= '<span class="comment-count"><a href="' . get_comments_link() .'"><i class="fa fa-comments-o"></i> '. $num_comments.'</a></span>';
}
if(in_array('excerpt', $items)){
    $contentHtml .= "<p class='entry-content'>{$excerpt}</p>";
}
$postMetaBottom = null;
if(in_array('social_share', $items)){
    global $rtTPG;
    $postMetaBottom .= $rtTPG->rtShare($pID);
}
if(in_array('read_more', $items)){
    $postMetaBottom .= "<span class='read-more'><a data-id='{$pID}' class='{$anchorClass}' href='{$pLink}'>{$read_more_text}</a></span>";
}
if(!empty($postMetaBottom)){
    $contentHtml .= "<p><div class='post-meta'>$postMetaBottom</div></p>";
}

$html .= "<div class='{$grid} {$class}' data-id='{$pID}'>";
    $html .= '<div class="rt-holder">';
        $html .= "<div class='overlay'>";
			if($imgSrc) {
				$html .= "<a href='{$pLink}'><img class='rt-img-responsive top' src='{$imgSrc}' alt='{$title}'></a>";
			}
            $html .= "<div class='post-info'>{$titleHtml}<p class='post-meta-user'>{$metaHtml}</p>{$contentHtml}</div>";
            
        $html .="</div>";
    $html .= '</div>';
$html .='</div>';

echo $html;