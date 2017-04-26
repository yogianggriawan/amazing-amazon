<?php
function sitemap_item() {
	global $wp_query;
//sitemap item

	global $xml;
	$sitemapitem = 1000;
	if(!empty($wp_query->query_vars['xml_sitemap'])){ 
if($wp_query->query_vars['xml_sitemap'] == 'params='){
    			header( 'X-Robots-Tag: noindex, follow', true );
			header( 'Content-Type: text/xml' );
			echo '<?xml version="1.0" encoding="UTF-8"?>
  <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  
$count_posts = wp_count_posts();

$published_posts = $count_posts->publish;
$paging = floor($published_posts/$sitemapitem);
					for($i=1;$i<$paging;$i++){
						echo '<sitemap><loc>'.get_home_url().'/sitemap-index'.$i.'.xml</loc></sitemap>';
					}
					echo '</sitemapindex>';
			die();
}else{
    $sitemapindex = $wp_query->query_vars['xml_sitemap'];
    $sitemapindex = str_replace('params=index','',$sitemapindex);
header( 'X-Robots-Tag: noindex, follow', true );
			header( 'Content-Type: application/xml' );
			echo '<?xml version="1.0" encoding="UTF-8"?>
			<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
			http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
query_posts( 'posts_per_page='.$sitemapitem.'&orderby=modified&order=DESC&paged='. $sitemapindex );
while ( have_posts() ) : the_post(); 
echo '<url><loc> '.get_the_permalink().'</loc><changefreq>daily</changefreq><priority>0.9</priority></url>';
endwhile;
			echo '</urlset>';
			die();
}



	}
}
add_action('parse_query', 'sitemap_item', 10, 0);
?>