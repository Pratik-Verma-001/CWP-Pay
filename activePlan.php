<?php
require_once('system/function.php');

// get payment data from db

$activeKeyData = ['key'=>'d9fe71-a2e52e','secret'=>'rDPgvngh','upiuid'=>'pafh@paytm'];
$keyId = $activeKeyData['key'];
$keySecret = $activeKeyData['secret'];

$status = $_POST['status'];
$message = $_POST['message'];
$hash = $_POST['hash'];
$checksum = $_POST['checksum'];

echo '<meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="css/cwp-base.css"><div class="cwp-body" style="min-height:100vh;display:grid;place-items:center;text-align:center;padding:24px"><div><img src="images/cwp-logo-light.svg" alt="CWP Pay" width="130" height="38"><h1 style="font-size:1.7rem;margin-top:1.2rem">Processing your payment</h1><p style="color:var(--muted);margin-top:.5rem">Please do not reload this page.</p></div></div>';

// Payment Status.
if($status=="SUCCESS"){
    $paramList = hash_decrypt($hash,$keySecret);
    require_once('system/checksum.php');
    $verifySignature = CxrChecksum::verifySignature($paramList, $keySecret, $checksum);
    if($verifySignature){
        $array = json_decode($paramList,true); 
        // print_r($array);
        // save payment request data
		$payment_request_id = $array['txnId'];
		$request_id = $array['orderId'];
		$amount = $array['txnAmount'];
		$start_date = date('d-m-Y H:i:s');
		$ssql = sql_query("SELECT * FROM tb_partner WHERE plan_txn_id='$request_id' LIMIT 1 ");
		$sdata = mysqli_fetch_assoc($ssql);
		$exp_date = date('d-m-Y H:i:s', strtotime($sdata['expire_date']. ' + 30 days'));
		$usql = sql_query("UPDATE `tb_partner` SET start_date='$start_date', expire_date='$exp_date' WHERE plan_txn_id='".$request_id."'");
        $user = sql_fetch_array($usql);
		return redirect('plans?paystatus=success','1');
    }
}else{
    redirect('plans?paystatus=failed','1');
}



?>