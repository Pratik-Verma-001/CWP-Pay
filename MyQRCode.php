<?php
require_once('layouts/header.php');

$account = json_decode($userdata['settle_account']);
?>

      <!-- adminx-content-aside -->
      <div class="adminx-content">
        <!-- <div class="adminx-aside">

        </div> -->

        <div class="adminx-main-content">
          <div class="container-fluid">
            <!-- BreadCrumb -->
<?php if($userdata['upi_active']>0){ ?> 
<?php
if(isset($_POST['upiupdate']) && 
!empty($_POST['upi_id']) && 
!empty($_POST['upi_name']) && 
!empty($_POST['ifsc']) ){
	
$upi_id = safe_str($_POST['upi_id']);
$upi_name = safe_str($_POST['upi_name']);
$ifsc = safe_str($_POST['ifsc']);
$allow = array("paytm", "sbi");

if(isUPI($upi_id,$allow)){
$upis = json_encode(array("upi_id"=>$upi_id, "upi_name"=>$upi_name, "ifsc"=>$ifsc));

if(sql_query("UPDATE `tb_partner` SET upis='".$upis."' WHERE id='".$userdata['id']."' ")){
echo '<script>swal("Successfully!", "UPI Details Updated!", "success");</script>';
redirect('','1500');		
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','1500');
}

}else{
echo '<script>swal("Alert!", "UPI Address is not valid, currently support '.implode(", ",$allow).'", "error");</script>';
redirect('','1500');
}

}

?>
<div class="p-qrpage">
  <section class="p-card p-qrcard" id="qr-print">
    <img class="p-qr-bhim" src="../assets/img/bhimupi.png" alt="BHIM UPI">
    <h2><?php echo htmlspecialchars(get_upi_id($userdata['id'],"upi_name"));?></h2>
    <div class="p-qr-img"><img src="<?=my_qr_code($userdata['id']);?>" alt="Your UPI QR code"></div>
    <p class="p-qr-cap">Scan and pay using any UPI app</p>
    <img class="p-qr-apps" src="../assets/img/upiapps.png" alt="Supported UPI apps">
  </section>
  <aside class="p-card p-qrside">
    <header class="p-card-head"><h2>Share your QR</h2><p>Put it at your counter or send it to customers.</p></header>
    <div class="p-copyrow"><input type="text" id="qr_upi" value="<?php echo htmlspecialchars(get_upi_id($userdata['id'],"upi_id"));?>" readonly><button type="button" class="p-copy" data-copy="#qr_upi">Copy UPI ID</button></div>
    <div class="p-actions"><button type="button" class="btn bg-white text-dark" onclick="window.print()">Print QR code</button></div>
  </aside>
</div>
<?php }else{ ?>
<div class="p-cta">
  <h2>Accept payments directly in your bank account</h2>
  <p>0% transaction fee. Ask us to switch on the UPI Smart Gateway for your account.</p>
  <button type="button" class="btn bg-white text-dark" onclick="window.open('https://wa.me/91<?=support('mobile')?>?text=<?=urlencode("I'm interested in your UPI Smart Gateway")?>');">Activate on WhatsApp</button>
  <?php if(webdata("notification")!=""){ ?><div class="p-cta-note"><?=webdata("notification")?></div><?php } ?>
</div>
<?php } ?>
</div></div></div>
<?php
require_once('layouts/footer.php');
?>
<!--script src= "https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js"> </script>
<script> 
function html_to_img(elm,pre){	
          $(pre).html("");
			// Global variable 
			var element = $(elm); 
		
			// Global variable 
			var getCanvas; 

				html2canvas(element, { 
					onrendered: function(canvas) { 
						$(pre).append(canvas); 
						getCanvas = canvas; 
					} 
				}); 

}

//html_to_img("#html-content-holder","#previewImage");
</script--> 