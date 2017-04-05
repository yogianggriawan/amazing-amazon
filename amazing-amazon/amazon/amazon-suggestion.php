
<div class="related"> Related:
<?php
$apiurl = 'https://completion.amazon.com/search/complete?method=complete&q='.str_replace(' ', '+', get_search_query()).'&search-alias=aps&conf=1&sv=android-mshop&l=en&mkt=1&client-id=unknown';
$results = get_api($apiurl);
$i=0;
foreach ($results as $value) {
	if($i==1){
		foreach ($value  as $suggest) {
			global $wpdb; 
			$ID = 0;
			$meta_value = strip_tags($suggest);
				$success = $wpdb->query( "INSERT INTO ".$wpdb->prefix."stt2_meta ( `post_id`,`meta_value`,`meta_count` ) VALUES ( '".$ID."', '".$meta_value."', 1 )
			ON DUPLICATE KEY UPDATE `meta_count` = `meta_count` + 1" ) ;
			echo '<span style="margin-left: 12px;"><a href="http://' . $_SERVER['HTTP_HOST'].'/search/'.sanitize(strip_tags($suggest)).'"><strong class="keywords">'.$suggest.'</strong></a></span>';
		}
	}
	$i++;
}
?>
</div>