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

<div class="p-cols p-cols--even">
  <section class="p-card">
    <header class="p-card-head"><h2>UPI details</h2><p>Customers pay to this UPI address.</p></header>
    <form action="" method="post" class="p-form">
      <div class="p-field"><label for="upi_id">UPI address</label><input type="text" name="upi_id" id="upi_id" value="<?php echo htmlspecialchars(get_upi_id($userdata['id'],"upi_id"));?>" onchange="UPIValid(this.value);" placeholder="name@paytm" required><span class="p-hint">Only @paytm and @sbi addresses are supported.</span></div>
      <div class="p-field"><label for="upi_name">Bank account name</label><input type="text" name="upi_name" id="upi_name" value="<?php echo htmlspecialchars(get_upi_id($userdata['id'],"upi_name"));?>" placeholder="Name on your bank account" required></div>
      <div class="p-field"><label for="ifsc">IFSC code</label><input type="text" name="ifsc" id="ifsc" value="<?php echo htmlspecialchars(get_upi_id($userdata['id'],"ifsc"));?>" onkeyup="icsf_valid(this.value);" placeholder="11 characters" minlength="11" maxlength="11" required><span id="ifsc_bank" class="p-hint"></span></div>
      <div class="p-actions"><button type="submit" name="upiupdate" class="btn bg-white text-dark">Save UPI details</button></div>
      <script>
function icsf_valid(ifsc){
document.getElementById("ifsc_bank").innerHTML = '';
 if(ifsc.length==11){
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var myObj = JSON.parse(this.responseText);
      document.getElementById("ifsc_bank").innerHTML = myObj.BANK+', '+myObj.BRANCH;
    }
  };
  xhttp.open("GET", "https://ifsc.razorpay.com/"+ifsc, true);
  xhttp.send();
  
}

}

function UPIValid(upi_id) { 
  if(isUPI(upi_id)!=true){
  alert("UPI Adress is not valid");
  document.getElementById("upi_id").value = ""; 
  }
}


function isUPI(upi) {
    return upi.endsWith('@paytm') || upi.endsWith('@sbi');
}
</script>
    </form>
  </section>
  <div class="p-stack">
    <section class="p-card">
      <header class="p-card-head"><h2>Connected device</h2><p>The phone that watches your payments.</p></header>
      <?php $dev_on = (get_device($userdata['id'],16)!=""); ?>
      <div class="p-device">
        <div class="p-device-top">
          <b><?=htmlspecialchars(get_device($userdata['id'],16))?> <?=htmlspecialchars(get_device($userdata['id'],17))?></b>
          <span class="p-pill <?php echo $dev_on ? 'is-ok' : 'is-off';?>"><?php echo $dev_on ? 'Connected' : 'Disconnected';?></span>
        </div>
        <dl>
          <dt>Android ID</dt><dd><?=htmlspecialchars(get_device($userdata['id'],15))?></dd>
          <dt>Android version</dt><dd><?=htmlspecialchars(get_device($userdata['id'],1))?></dd>
          <dt>Last seen</dt><dd><?=misec_datetime(get_device($userdata['id'],21),"Y-m-d h:i:s")?></dd>
        </dl>
      </div>
    </section>
    <section class="p-card">
      <header class="p-card-head"><h2>Works with these apps</h2><p>Install one on the connected phone. Your customers can pay from any UPI app, including Google Pay, PhonePe, Freecharge, BHIM and SBI.</p></header>
      <div class="p-apps"><a class="p-app" href="https://play.google.com/store/apps/details?id=net.one97.paytm" target="_blank" rel="noopener"><img src="assets/img/bank_paytm.jpg" alt="Paytm"><b>Paytm</b><span>Get the app</span></a><a class="p-app" href="https://play.google.com/store/apps/details?id=com.sbi.upi" target="_blank" rel="noopener"><img src="assets/img/bank_sbi.jpg" alt="SBI Pay"><b>SBI Pay</b><span>Get the app</span></a><a class="p-app" href="../docs/com.mitragateway.mitragatewayapp.apk" target="_blank" rel="noopener"><img src="assets/img/smartgateway-copy.jpg" alt="Mitra Gateway"><b>Mitra Gateway</b><span>Get the app</span></a></div>
    </section>
  </div>
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