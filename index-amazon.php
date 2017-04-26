<?php
$trackingid = get_option('tracking_id');
include('node.php');
$apiurl = 'https://www.amazon.com/s/ref%3Dmh_s9_acsd_hps_clnk_r?node='.$node.'&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=mobile-hybrid-7&pf_rd_r=DHFN303RBZGSX8976543&pf_rd_t=1201&pf_rd_p=3c61a3dd-557c-4777-ae1c-df3d3f363e7c&pf_rd_i=1263206011&imgRes=0&imgCrop=true&carrier=&manufacturer=Xiaomi&model=HM+1SW&deviceType=A1MPSLFC7L5AFK&osVersion=18&deviceDensityClassification=320&deviceScreenLayout=SCREENLAYOUT_SIZE_NORMAL&serial=f35bb2ed&buildProduct=armani&buildFingerprint=Xiaomi%2Farmani%2Farmani%3A4.3%2FJLS36C%2FJHCMIBL50.0%3Auser%2Frelease-keys&simOperator=51010&phoneType=PHONE_TYPE_GSM&ie=UTF-8&dataVersion=v0.2&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&cri=FQngXfFipTg03B8y&uaAppName=mShop&uaAppType=Application&uaAppVersion=10.5.0.100';
$results = get_api($apiurl);
if(isset($results)){
	foreach($results->results->sections as $sections) {
		foreach ($sections->items as $data) {
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
			}elseif(isset($data->prices->usedAndNewOffers->price)){
				$prices = $data->prices->usedAndNewOffers->price;
			}else{
				$prices = 'Unavailable';
			}
			
			?>
			<article id="post-1543" class="post-1543 post type-post status-publish format-standard hentry category-tas-pria-wanita">
					<header class="entry-header">

					<div class="entry-meta"><span class="byline"> by <span class="author vcard"><?php echo $brandName; ?></span></span></div><!-- .entry-meta -->
					<h3 class="entry-title"><?php echo $title.' '.$asin; ?></h3>	</header><!-- .entry-header -->

				
				<div class="entry-content">
				<div class="post-thumbnail" style="width: 33.33333333333333%;float: left;">
				<img width="360" height="360" src="<?php echo $image; ?>" />
				</div>
				<div class="post-content" style="width: 66.66666666666666%;float: left;">
					<div class="labelright">
					<span class="green size18"><b><?php echo $prices; ?></b></span><br>
					<span class="size11 grey">(Min. Order: 1 Piece)</span>
					<br><br>
					<div style="text-align:right;">
					<button onclick="window.open('https://www.amazon.com/gp/product/<?php echo $asin; ?>/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=<?php echo $asin; ?>&linkCode=as2&tag=<?php echo $trackingid; ?>&linkId=c535a10d9f2e5680f211ecc31c5a8d4f','_blank')" class="redirect bookbtn mt1" title="View Detail">View Detail</button>
				</div>
				</div>
					<div class="labelleft2">
						<p><?php
					$descount = 1;
					if(isset($data->description)){
						foreach($data->description as $desc) {
							if($desc->style == 'PLAIN' && $descount <= 2){
								echo $plain = $desc->text.'</br>';
								$descount++;
							}
						}
					}
					

					?></p>
					</div>
				</div>	
					
				</div><!-- .entry-content -->
			</article>
			<?php

		}
		}
		
}

?>