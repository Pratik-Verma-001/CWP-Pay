<?php
session_start();
session_regenerate_id();
require_once('../system/function.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars(webdata('company_name'));?> | Payments</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0c2623">
    <meta name="robots" content="noindex">
    <link rel="icon" href="<?php echo htmlspecialchars(webdata('web_fav'));?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../css/cwp-base.css">
    <link rel="stylesheet" href="../css/cwp-pay.css">
    <script src="../assets/dist/js/sweetalert.min.js"></script>
  </head>
<body class="cwp-body pay">
<main class="pay-wrap">
<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
if(isset($_POST['upiuid'])&& 
!empty($_POST['token'])&& 
!empty($_POST['orderId'])&& 
!empty($_POST['txnAmount'])&& 
!empty($_POST['txnNote'])&&
!empty($_POST['callback_url'])&& 
!empty($_POST['checksum']) ){

$upiuid = safe_str($_POST['upiuid']);
$token = db_escape(strip_tags($_POST['token']));
$orderId = safe_str($_POST['orderId']);
$txnAmount = safe_str($_POST['txnAmount']);
$txnNote = safe_str($_POST['txnNote']);
$callback_url = strip_tags($_POST['callback_url']);
$checksum = $_POST['checksum'];

$res = sql_query("SELECT * FROM `tb_partner` WHERE token='".$token."' AND status='active' ");
$result = sql_fetch_array($res);
if(sql_num_rows($res)>0 && $result['token']==$token){

$txn_query = sql_query("SELECT * FROM `tb_transactions` WHERE client_orderid='".$orderId."' ");
if(sql_num_rows($txn_query)==''){
$paramList = array();
$paramList["upiuid"] = $upiuid;
$paramList["token"] = $token;
$paramList["orderId"] = $orderId;
$paramList["txnAmount"] = $txnAmount;
$paramList["txnNote"] = $txnNote;
$paramList["callback_url"] = $callback_url;	
require_once('../system/checksum.php');
$verifySignature = CxrChecksum::verifySignature($paramList, $result['secret'], $checksum);
if($verifySignature){
$upi_query = sql_query("SELECT * FROM `tb_upis` WHERE upi_uid='".$upiuid."' AND uid='".$result['id']."' AND status='ACTIVATED' ");
$upi_result = sql_fetch_array($upi_query);	

if(sql_num_rows($upi_query)>0){
$txn_ref_id = GenRandomString().time();	
$_SESSION['muid'] = $result['id'];
$_SESSION['auth_token'] = $result['token'];
$_SESSION['txn_ref_id'] = $txn_ref_id;
$_SESSION['upi_id'] = $upi_result['upi_id'];
$_SESSION['client_orderid'] = $orderId;	
echo "<script>
setTimeout(function(){ 
document.getElementById(\"qrcode\").innerHTML = '';
GenerateQR('".$upi_result['upi_id']."', '".$upi_result['upi_name']."', '".$txnAmount."', '".$txn_ref_id."', '".$txnNote."'); 
var upilink = document.getElementById(\"qrcode\").title;
document.getElementById(\"upilink\").href = upilink;
}, 1000);
</script>";	

$json_string = json_encode(array(
"txnStatus"=>'TXN_SUCCESS', 
"resultInfo"=>'Txn Success',
"orderId"=>$orderId,
"txnAmount"=>$txnAmount,
"txnId"=>GenRandomString(12),
"bankTxnId"=>rand(0000,9999),
"paymentMode"=>'UPI',
"txnDate"=>date_time("Y-m-d h:i:s A"),
"utr"=> '0000'.rand(00000000,99999999),
"sender_vpa"=>'demosender@bankupi',
"sender_note"=>$txnNote,
"payee_vpa"=>webdata("upi_prefix").rand(0000,9999).webdata("upi_bank"),
));	

$hash = hash_encrypt($json_string, $result['secret']);

$checksum = CxrChecksum::generateSignature($json_string,$result['secret']);

$html = '
<form id="formSubmit" action="'.$callback_url.'" method="post" style="display:none;">
<input type="hidden" id="status" name="status">
<input type="hidden" id="message" name="message">
<input type="hidden" id="hash" name="hash">
<input type="hidden" id="checksum" name="checksum">
</form>
<script type="text/javascript" src="../assets/dist/js/txnStatusTest.js"></script>';
echo $html;
}else{
$html = '
<h2 style="color:white" class="text-center">Please do not refresh this page...</h2>
<form id="formSubmit" action="'.$callback_url.'" method="post" style="display:none;">
<input type="hidden" name="status" value="FAILED" >
<input type="hidden" name="message" value="upiuid is invalid or not active" >
<input type="hidden" name="hash" value="false">
<input type="hidden" name="checksum" value="false">
</form>
<script type="text/javascript">
setTimeout(function(){ 
document.getElementById("formSubmit").submit();
}, 1000);
</script>
';	
echo $html;
exit();	
}
	
	
}else{
$html = '
<h2 style="color:white" class="text-center">Please do not refresh this page...</h2>
<form id="formSubmit" action="'.$callback_url.'" method="post" style="display:none;">
<input type="hidden" name="status" value="FAILED" >
<input type="hidden" name="message" value="Checksum Mismatch" >
<input type="hidden" name="hash" value="false">
<input type="hidden" name="checksum" value="false">
</form>
<script type="text/javascript">
setTimeout(function(){ 
document.getElementById("formSubmit").submit();
}, 1000);
</script>
';	
echo $html;	
exit();	
}


}else{
$html = '
<h2 style="color:white" class="text-center">Please do not refresh this page...</h2>
<form id="formSubmit" action="'.$callback_url.'" method="post" style="display:none;">
<input type="hidden" name="status" value="FAILED" >
<input type="hidden" name="message" value="This Order Id is Already Taken" >
<input type="hidden" name="hash" value="false">
<input type="hidden" name="checksum" value="false">
</form>
<script type="text/javascript">
setTimeout(function(){ 
document.getElementById("formSubmit").submit();
}, 1000);
</script>
';	
echo $html;		
exit();	
}



}else{
$html = '
<h2 style="color:white" class="text-center">Please do not refresh this page...</h2>
<form id="formSubmit" action="'.$callback_url.'" method="post" style="display:none;">
<input type="hidden" name="status" value="FAILED" >
<input type="hidden" name="message" value="Unauthorized Access or Token Is Invalid" >
<input type="hidden" name="hash" value="false">
<input type="hidden" name="checksum" value="false">
</form>
<script type="text/javascript">
setTimeout(function(){ 
document.getElementById("formSubmit").submit();
}, 1000);
</script>
';	
echo $html;		
exit();	
}

	
}else{
echo '<h2 style="color:white" class="text-center">Parameter Missing<br>Redirect...</h2>';
redirect(webdata('socket').base_url(),'2000');		
exit();	
}

}else{
echo '<h2 style="color:white" class="text-center">Unauthorized Access<br>Redirect...</h2>';
redirect(webdata('socket').base_url(),'2000');		
exit();	
}
?>

<div class="pay-brand"><img src="../images/cwp-logo-dark.svg" alt="CWP Pay" width="120" height="32"></div>
<section class="pay-card">
  <header class="pay-head">
    <a class="pay-back" href="<?php echo htmlspecialchars($callback_url);?>" aria-label="Cancel and go back"><svg viewBox="0 0 24 24"><path d="M15 5l-7 7 7 7"/></svg></a>
    <div class="pay-merchant"><b><?php echo htmlspecialchars($result['company_name']);?></b><span>Order <?php echo htmlspecialchars($orderId);?></span></div>
  </header>
  <div class="pay-amount"><small>Amount to pay</small><strong>&#8377;<?php echo $txnAmount;?></strong></div>
  <p class="pay-hint">Scan with any UPI app</p>
  <div class="pay-apps">
    <img src="../assets/img/gpay.png" alt="Google Pay">
    <img src="../assets/img/paytm.png" alt="Paytm">
    <img src="../assets/img/phonepe.png" alt="PhonePe">
    <img src="../assets/img/bhim_logo.png" alt="BHIM">
    <img src="../assets/img/amazonpay.png" alt="Amazon Pay">
  </div>
  <div class="pay-qr"><div id="qrcode"><img src="../assets/img/loading.cc387905.gif" alt="Loading QR code" width="200"></div></div>
  <button type="button" id="upilink" onclick="TxnStatus('success','<?php echo $hash;?>','<?php echo $checksum;?>')" class="pay-btn">Mark as success (test)</button>
    <button type="button" id="upilinkb" onclick="TxnStatus('failed','','')" class="pay-btn pay-btn--danger">Mark as failed (test)</button>
  <div class="pay-timer"><i></i><span>Expires in <span id="upitimer"></span></span></div>
  <p class="pay-note">Keep this page open. It updates by itself once your payment is received.</p>
</section>
<p class="pay-secure">Secured by CWP Pay</p>
</main>

    <!-- If you prefer jQuery these are the required scripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="../assets/dist/js/custom-new.js"></script>
	<script src="../assets/dist/js/qrcode.min.js"></script>
	<script>upiCountdown("upitimer",005,00,'<?php echo $callback_url;?>');</script>
	
  </body>
</html> 