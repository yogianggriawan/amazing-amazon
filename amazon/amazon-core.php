<?php
include('amazon-options.php');
add_action( 'wp_ajax_my_action', 'my_action' );
add_action( 'wp_ajax_nopriv_my_action', 'my_action' );
add_action( 'wp_ajax_asinposter', 'asinposter' );
add_action( 'wp_ajax_nopriv_asinposter', 'asinposter' );

// You can also use the method above to place anything in other sections of WordPress 
//No Javascript used
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
function asinposter(){

	if(isset($_POST['data2'])&&!isset($_POST['arrayke'])){
         echo count($_POST['data']);
         die();
     }else{
		global $wpdb; // this is how you get access to the database
		$i = $_POST['arrayke'];
		$posts = $_POST['data'];
		$posts = array_values($posts);
		$asin = $posts[$i]['asin'];
		$apiurl = 'https://www.amazon.com/s/ref=lp_14284819011_ex_n_1?rh=n%3A13727921011%2Cn%3A%2113727922011%2Cn%3A14284819011&bbn=14284819011&ie=UTF-8&qid=1490572388&node=14284819011&k='.$asin.'&imgRes=0&imgCrop=true&carrier=&manufacturer=Xiaomi&model=HM+1SW&deviceType=A1MPSLFC7L5AFK&osVersion=18&deviceDensityClassification=320&deviceScreenLayout=SCREENLAYOUT_SIZE_NORMAL&serial=f35bb2ed&buildProduct=armani&buildFingerprint=Xiaomi%2Farmani%2Farmani%3A4.3%2FJLS36C%2FJHCMIBL50.0%3Auser%2Frelease-keys&simOperator=51010&phoneType=PHONE_TYPE_GSM&dataVersion=v0.2&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&cri=rrhMfPJfpl656svD&uaAppName=mShop&uaAppType=Application&uaAppVersion=10.5.0.100';
		$results = get_api($apiurl);
		foreach($results->correctedCategoryResults->results->items as $data) {
		$asin = $data->asin;
		$title = $data->title;
		if(isset($data->brandName)){
			$brandName = $data->brandName;
		}else{
			$brandName = 'Unavailable';
		}
		$image = str_replace('_AC_SL{IMG_RES}_QL70_','_AC_US250',$data->image->url);
		if(isset($data->prices->buy->price)){
			$prices = $data->prices->buy->price;
		}else{
			$prices = $data->prices->usedAndNewOffers->price;
		}
				$descount = 1;
				$plain = '';
				if(isset($data->description)){
					foreach($data->description as $desc) {
					if($desc->style == 'PLAIN' && $descount <= 2){
						$plain .= $desc->text.'</br>';
						$descount++;
					}
				}
				}
				
		$is_exsist = wp_exist_page_by_title($title);
		if(empty($is_exsist)){
			$brandascat=array();
			$my_cat_id = wp_create_category($brandName);
			array_push($brandascat, $my_cat_id);
							$my_post = array(
						'post_title' => $title,
						'comment_status'	=>	'closed',
						'ping_status'		=>	'closed',	
						'post_content' => $plain,
						'post_status' => 'publish',
						'post_category' => $brandascat
						);

					$result = wp_insert_post( $my_post );
					if ($result) {
						add_post_meta($result, 'image', $image);
						add_post_meta($result, 'brand', $brandName);
						add_post_meta($result, 'price', $prices);
						add_post_meta($result, 'asin', $asin);
						}
			echo $title;
		}else{
			echo 'post exists '.$title;
		}
		}
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
function get_api($url){
	$body = @file_get_contents($url);
	return json_decode($body);
  //return $body;
}
//apisample = https://www.amazon.com/s/ref=lp_14284819011_ex_n_1?rh=n%3A13727921011%2Cn%3A%2113727922011%2Cn%3A14284819011&bbn=14284819011&ie=UTF-8&qid=1490572388&node=14284819011&k=gopro&imgRes=0&imgCrop=true&carrier=&manufacturer=Xiaomi&model=HM+1SW&deviceType=A1MPSLFC7L5AFK&osVersion=18&deviceDensityClassification=320&deviceScreenLayout=SCREENLAYOUT_SIZE_NORMAL&serial=f35bb2ed&buildProduct=armani&buildFingerprint=Xiaomi%2Farmani%2Farmani%3A4.3%2FJLS36C%2FJHCMIBL50.0%3Auser%2Frelease-keys&simOperator=51010&phoneType=PHONE_TYPE_GSM&dataVersion=v0.2&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&cri=rrhMfPJfpl656svD&uaAppName=mShop&uaAppType=Application&uaAppVersion=10.5.0.100
function get_api_curl($url){
		$curl = curl_init();

  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
  $header[] = "Cache-Control: max-age=0";
  $header[] = "Connection: keep-alive";
  $header[] = "Keep-Alive: 300";
  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
  $header[] = "Accept-Language: en-us,en;q=0.5";
  $header[] = "Pragma: ";

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_USERAGENT, "Amazon.com/10.5.0.100 (Android/4.3/HM 1SW)");
  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
  curl_setopt($curl, CURLOPT_REFERER, "https://www.amazon.com/gp/aw/d/B01D0YCYG0/ref%3Dmp_s_a_1_1?ie=UTF8&qid=1491357302&sr=1-1&m=ATVPDKIKX0DER&pi=SL162_SX145_CR0,0,145,162_QL70");
  curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

  $html = curl_exec($curl);
  curl_close($curl);

  return json_decode($html);
}
function wp_exist_page_by_title($title_str) {
		global $wpdb;
		return $wpdb->get_row('SELECT ID FROM '.$wpdb->posts.' WHERE post_title LIKE "%' . str_replace('"', '\"', $title_str) . '%"  && post_type = "post"', 'ARRAY_N');
	}
function sanitize($str,$separator = '-') {

  setlocale(LC_ALL, 'en_US.UTF8');
  $clean = iconv('UTF-8', 'ASCII//TRANSLIT', trim($str));
  $clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
  $clean = strtolower(trim($clean, '-'));
  $clean = preg_replace("/[\/_| -]+/", $separator, $clean);

  return $clean;
}

?>