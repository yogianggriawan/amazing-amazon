<!-- <span id="tt"></span>  -->
<?php if(get_post_meta($post->ID, "asin", $single = true) != ""){ 
$asin = get_post_meta($post->ID, "asin", $single = true);
$apiurl = 'https://www.amazon.com/gp/nemo/spd/handlers/spd-shov.html?ASIN='.$asin.'&ie=UTF8&searchTerms=&searchIndex=&referringSearchEngine=Amazon&wName=sp_phoneapp_detail2&num=100';
$related = get_api_curl($apiurl);
if(isset($related->data)){
	$i = 0;
	$data_array = array();
	$len = count($related->data);
	if(is_user_logged_in() OR !is_user_logged_in()){
		echo '<form method="post" id="kotak">';
	}
	foreach($related->data as $data) {
		$asin = $data->oid;
		array_push($data_array, $asin);
		echo '<input  type="hidden" id="asin'.$i.'" name="data['.$i.'][asin]" value="'.$asin.'" />
		';
		$i++;
	}
	if(is_user_logged_in() OR !is_user_logged_in()){
		echo '<input type="hidden" name="action" value="asinposter" />';
		echo '</form>';
	}
	?>
				<script type="text/javascript" >
		jQuery(document).ready(function($) {

			var data = {
				'action': 'asinposter',
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
						$("#tt").html('<br><img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>"> Posting <?php echo $i.' produk ' ?>... Maybe take a long time...</br>'); 
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