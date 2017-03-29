<?php
add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action' );

function my_action() {
	if(isset($_POST['data2'])&&!isset($_POST['arrayke'])){
         echo count($_POST['data']);
         die();
     }else{
		global $wpdb; // this is how you get access to the database
		$i = $_POST['arrayke'];
		$posts = $_POST['data'];
		$posts = array_values($posts);
		$title = $posts[$i]['title'];
		$content = $posts[$i]['content'];
		$image = $posts[$i]['image'];
		$price = $posts[$i]['price'];
		$brand = $posts[$i]['brand'];
		$asin = $posts[$i]['asin'];

		$is_exsist = wp_exist_page_by_title($title);
		if(empty($is_exsist)){
			$brandascat=array();
			$my_cat_id = wp_create_category($brand);
			array_push($brandascat, $my_cat_id);
							$my_post = array(
						'post_title' => $title,
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',	
						'post_content' => $content,
						'post_status' => 'publish',
						'post_category' => $brandascat
						);

					$result = wp_insert_post( $my_post );
					if ($result) {
						add_post_meta($result, 'image', $image);
						add_post_meta($result, 'brand', $brand);
						add_post_meta($result, 'price', $price);
						add_post_meta($result, 'asin', $asin);
						}
		}
		echo $title;
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}

function get_api($url){
	$body = @file_get_contents($url);
	return json_decode($body);
  //return $body;
}
//apisample = https://www.amazon.com/s/ref=lp_14284819011_ex_n_1?rh=n%3A13727921011%2Cn%3A%2113727922011%2Cn%3A14284819011&bbn=14284819011&ie=UTF-8&qid=1490572388&node=14284819011&k=gopro&imgRes=0&imgCrop=true&carrier=&manufacturer=Xiaomi&model=HM+1SW&deviceType=A1MPSLFC7L5AFK&osVersion=18&deviceDensityClassification=320&deviceScreenLayout=SCREENLAYOUT_SIZE_NORMAL&serial=f35bb2ed&buildProduct=armani&buildFingerprint=Xiaomi%2Farmani%2Farmani%3A4.3%2FJLS36C%2FJHCMIBL50.0%3Auser%2Frelease-keys&simOperator=51010&phoneType=PHONE_TYPE_GSM&dataVersion=v0.2&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&cri=rrhMfPJfpl656svD&uaAppName=mShop&uaAppType=Application&uaAppVersion=10.5.0.100
function wp_exist_page_by_title($title_str) {
		global $wpdb;
		return $wpdb->get_row("SELECT ID FROM ".$wpdb->posts." WHERE post_title LIKE '%" . $title_str . "%'  && post_type = 'post'", 'ARRAY_N');
	}

?>