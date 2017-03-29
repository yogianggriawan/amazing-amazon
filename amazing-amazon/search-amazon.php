<span id="tt"></span> 
<?php

$apiurl = 'https://www.amazon.com/s/ref=lp_14284819011_ex_n_1?rh=n%3A13727921011%2Cn%3A%2113727922011%2Cn%3A14284819011&bbn=14284819011&ie=UTF-8&qid=1490572388&node=14284819011&k='.str_replace(' ', '+', get_search_query()).'&imgRes=0&imgCrop=true&carrier=&manufacturer=Xiaomi&model=HM+1SW&deviceType=A1MPSLFC7L5AFK&osVersion=18&deviceDensityClassification=320&deviceScreenLayout=SCREENLAYOUT_SIZE_NORMAL&serial=f35bb2ed&buildProduct=armani&buildFingerprint=Xiaomi%2Farmani%2Farmani%3A4.3%2FJLS36C%2FJHCMIBL50.0%3Auser%2Frelease-keys&simOperator=51010&phoneType=PHONE_TYPE_GSM&dataVersion=v0.2&cid=08e6b9c8bdfc91895ce634a035f3d00febd36433&format=json&cri=rrhMfPJfpl656svD&uaAppName=mShop&uaAppType=Application&uaAppVersion=10.5.0.100';
$results = get_api($apiurl);
if(isset($results->correctedCategoryResults->results->items)){
	$i = 0;
	$len = count($results->correctedCategoryResults->results->items);
	$data_array = array();
	if(is_user_logged_in()){
		echo '<form method="post" id="kotak">';
	}
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
		array_push($data_array, $title)

		?>
		<article id="post-1543" class="post-1543 post type-post status-publish format-standard hentry category-tas-pria-wanita">
				<header class="entry-header">

				<div class="entry-meta"><span class="byline"> by <span class="author vcard"><?php echo $brandName; ?></span></span></div><!-- .entry-meta -->
				<h3 class="entry-title"><?php echo $title; ?></h3>	</header><!-- .entry-header -->

			
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
				<button onclick="window.open('https://www.amazon.com/gp/product/<?php echo $asin; ?>/ref=as_li_tl?ie=UTF8&camp=1789&creative=9325&creativeASIN=<?php echo $asin; ?>&linkCode=as2&tag=mijong-20&linkId=c535a10d9f2e5680f211ecc31c5a8d4f','_blank')" class="redirect bookbtn mt1" title="View Detail">View Detail</button>
			</div>
			</div>
				<div class="labelleft2">
					<p><?php
				$descount = 1;
				$plain = '';

				foreach($data->description as $desc) {
					if($desc->style == 'PLAIN' && $descount <= 2){
						echo $plain .= $desc->text.'</br>';
						$descount++;
					}
				}

				?></p>
				</div>
				
				
			</div><!-- .entry-content -->
		</article>
		<?php
		if(is_user_logged_in()){
		?>
		<input  type="hidden" id="asin<?php echo $i;?>" name="data[<?php echo $i;?>][asin]" value="<?php echo $asin;?>" />
		<input  type="hidden" id="title_<?php echo $i;?>" name="data[<?php echo $i;?>][title]" value="<?php echo $title;?>" />
		<input type="hidden" id="content_<?php echo $i;?>" name="data[<?php echo $i;?>][content]" value="<?php echo $plain;?>" />
		<input type="hidden" id="image<?php echo $i;?>" name="data[<?php echo $i;?>][image]" value="<?php echo $image;?>" />
		<input type="hidden" id="price<?php echo $i;?>" name="data[<?php echo $i;?>][price]" value="<?php echo $prices;?>" />
		<input type="hidden" id="brand<?php echo $i;?>" name="data[<?php echo $i;?>][brand]" value="<?php echo $brandName;?>" />
	<?php
		}
		$i++;
	} 
	if(is_user_logged_in()){
	?>
			<input type="hidden" name="action" value="my_action" />
		</form>
		<script type="text/javascript" >
		jQuery(document).ready(function($) {

			var data = {
				'action': 'my_action',
				'post_id': '#<?php echo json_encode($data_array); ?>#'
			};
			var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			 var form = $('#kotak').serializeArray();
              var jumlaharray = 0;
			//cari jumlah array --------------- //

              form.push({name:'data2',value: 1});
			jQuery.post(ajaxurl, form, function(response) {
				//alert('Got this from the server: ' + response);

				
				<?php 
					if($i == 0){?>
						$("#tt").html('Selesai!');
					<?php }else{ ?>
						//alert('<?php echo $i.' dari '.$len; ?>');
						$("#tt").html('<br><img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>"> Posting produk ke <?php echo $i.' dari '.$len; ?>... Maybe take a long time...</br>'); 
						                 jumlaharray = response;
                 var jumlahform = form.length+1;

                 var datajumlah = {jumlahform: {name:'arrayke',value: jumlaharray}};
			//form = $.extend(form,datajumlah);
                 var jumlaharrayawal=jumlaharray;	
                 updateDebug(form, jumlaharray,jumlaharrayawal);
					<?php }
				?>
			});
			          function updateDebug(data, jumlaharray,jumlaharrayawal){

              data.push({name:'arrayke',value: jumlaharray-1});
              $.ajax({
               type: 'POST',
               url: ajaxurl,
               data: data,
               success: function( response ) {		
                   $("#tt").append("<p>Sukses: "+jumlaharray+"/"+jumlaharrayawal+" "+response+"</p>");
 

               if ( jumlaharray>1 ) {
                 jumlaharray = jumlaharray - 1;
                 updateDebug( data, jumlaharray,jumlaharrayawal);
             }
             else {

               $("#tt").html("<p>Proses selesai, silakan cek post.</p>");
               //alert("All Download Completed");
               //location.reload();

           }
       },
       error: function( response ) {
          $("#regenthumbs-debuglist").append("<li>Ada yang salah, ojo lapor, aku mumet</li>")

          if ( jumlaharray>0 ) {
             jumlaharray = jumlaharray - 1;
             updateDebug( data, jumlaharray,jumlaharrayawal);
         }
         else {
           $("#regenthumbs-debuglist").append("<li>Proses selesai, silakan cek gallery</li>")
           alert("All Download Completed");
          // location.reload();
       }
   }

});


          }
		});
		</script>

	<?php
	}
}


?>