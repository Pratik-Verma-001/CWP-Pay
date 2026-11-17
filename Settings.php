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
<?php
if(isset($_POST['pwdupdate']) && !empty($_POST['current_pwd']) && !empty($_POST['new_pwd']) && !empty($_POST['confirm_new_pwd']) ){
	
$current_pwd = safe_str($_POST['current_pwd']);
$new_pwd = safe_str($_POST['new_pwd']);
$confirm_new_pwd = safe_str($_POST['confirm_new_pwd']);
$pwd = $userdata['login_pwd'];
if(pwd_verify($current_pwd,$pwd)){
	
if($new_pwd==$confirm_new_pwd){

if(sql_query("UPDATE `tb_partner` SET login_pwd='".pwd_hash($confirm_new_pwd)."' WHERE id='".$userdata['id']."' ")){
echo '<script>swal("Successfully!", "Password Changed!", "success");</script>';
redirect('','1500');		
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','1500');
}

}else{
echo '<script>swal("Alert!", "Confirm Password Not Match!", "error");</script>';
redirect('','1500');
}	
	
}else{
echo '<script>swal("Alert!", "Current Password Not Match!", "error");</script>';
redirect('','1500');
}


}
?>

<?php
if(isset($_POST['userupdate']) && 
!empty($_POST['mobile']) && 
!empty($_POST['email']) && 
!empty($_POST['account_no'])&& 
!empty($_POST['beneficiary_name'])&& 
!empty($_POST['ifsc']) ){
	
$mobile = safe_str($_POST['mobile']);
$email = safe_str($_POST['email']);
$account_no = safe_str($_POST['account_no']);
$beneficiary_name = safe_str($_POST['beneficiary_name']);
$ifsc = safe_str($_POST['ifsc']);

$settle_account = json_encode(array("account_no"=>$account_no, "beneficiary_name"=>$beneficiary_name, "ifsc"=>$ifsc));

if(sql_query("UPDATE `tb_partner` SET mobile='".$mobile."', email='".$email."', settle_account='".$settle_account."' WHERE id='".$userdata['id']."' ")){
echo '<script>swal("Successfully!", "Profile Updated!", "success");</script>';
redirect('','1500');		
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','1500');
}

}
?>

<?php
if(isset($_POST['generate'])){
	
$token = api_token_gen();
$secret = GenRandomString(10);

if(sql_query("UPDATE `tb_partner` SET token='".$token."', secret='".$secret."' WHERE id='".$userdata['id']."' ")){
echo '<script>swal("Successfully!", "Generate New Credentials!", "success");</script>';
redirect('','1500');		
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','1500');
}

}
?>

<?php
if(isset($_POST['webhookupdate']) && !empty($_POST['txn_status_webhook'])){

$json_string = json_encode(array(
"txnStatus"=>'TXN_SUCCESS', 
"resultInfo"=>'Txn Success',
"orderId"=>time(),
"txnAmount"=>rand(000,999),
"txnId"=>GenRandomString(12),
"bankTxnId"=>rand(0000,9999),
"paymentMode"=>'UPI',
"txnDate"=>date_time("Y-m-d h:i:s A"),
"utr"=> '0000'.rand(00000000,99999999),
"sender_vpa"=>'demosender@bankupi',
"sender_name"=>'DEMO SENDER',
"sender_note"=>'Test Txn',
"payee_vpa"=>webdata("upi_prefix").rand(0000,9999).webdata("upi_bank"),
));	

$hash = hash_encrypt($json_string, $userdata['secret']);
//hash_decrypt($hash,$userdata['secret']);
$arr = array(
"status"=>'SUCCESS', 
"message"=>'Test Transactions',
"hash"=>$hash
);

$post_data = json_encode($arr);
$txn_status_webhook = safe_str($_POST['txn_status_webhook']);
$res = curl_post($txn_status_webhook,$post_data,'');

if(sql_query("UPDATE `tb_partner` SET txn_status_webhook='".$txn_status_webhook."' WHERE id='".$userdata['id']."' ")){
echo '<script>swal("Successfully!", "Updated Transaction Status Webhook Url!", "success");</script>';
redirect('','1500');		
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','1500');
}

}
?>
<div class="p-cols">
  <div class="p-stack">
    <section class="p-card">
      <header class="p-card-head"><h2>Profile and payout account</h2><p>Where we reach you, and the bank account your settlements go to.</p></header>
      <form action="" method="post" class="p-form">
        <div class="p-two">
          <div class="p-field"><label for="f_company_name">Company name</label><input type="text" id="f_company_name" name="company_name" value="<?php echo htmlspecialchars($userdata['company_name']);?>" maxlength="50" readonly></div>
          <div class="p-field"><label for="f_pan_no">PAN number</label><input type="text" id="f_pan_no" name="pan_no" value="<?php echo htmlspecialchars($userdata['pan_no']);?>"  readonly></div>
        </div>
        <div class="p-two">
          <div class="p-field"><label for="f_mobile">Mobile number</label><input type="tel" id="f_mobile" name="mobile" value="<?php echo htmlspecialchars($userdata['mobile']);?>" maxlength="10" inputmode="numeric" onkeypress="return isNumber(event)" required></div>
          <div class="p-field"><label for="f_email">Email address</label><input type="email" id="f_email" name="email" value="<?php echo htmlspecialchars($userdata['email']);?>" maxlength="50" required></div>
        </div>
        <div class="p-field"><label for="f_account_no">Account number</label><input type="text" id="f_account_no" name="account_no" value="<?php echo htmlspecialchars($account->account_no);?>" maxlength="34" required></div>
        <div class="p-two">
          <div class="p-field"><label for="f_beneficiary_name">Beneficiary name</label><input type="text" id="f_beneficiary_name" name="beneficiary_name" value="<?php echo htmlspecialchars($account->beneficiary_name);?>" minlength="5" maxlength="35" required></div>
          <div class="p-field"><label for="f_ifsc">IFSC</label><input type="text" id="f_ifsc" name="ifsc" value="<?php echo htmlspecialchars($account->ifsc);?>" minlength="11" maxlength="11" required></div>
        </div>
        <div class="p-actions"><button type="submit" name="userupdate" class="btn bg-white text-dark">Save profile</button></div>
      </form>
    </section>
    <section class="p-card">
      <header class="p-card-head"><h2>Change password</h2><p>Use at least 8 characters. Maximum is 12.</p></header>
      <form action="" method="post" class="p-form">
        <div class="p-field"><label for="f_current_pwd">Current password</label><input type="password" id="f_current_pwd" name="current_pwd" value="" maxlength="15" autocomplete="current-password" required></div>
        <div class="p-two">
          <div class="p-field"><label for="f_new_pwd">New password</label><input type="password" id="f_new_pwd" name="new_pwd" value="" maxlength="12" autocomplete="new-password" required></div>
          <div class="p-field"><label for="f_confirm_new_pwd">Confirm new password</label><input type="password" id="f_confirm_new_pwd" name="confirm_new_pwd" value="" maxlength="12" autocomplete="new-password" required></div>
        </div>
        <div class="p-actions"><button type="submit" name="pwdupdate" class="btn bg-white text-dark">Change password</button></div>
      </form>
    </section>
  </div>
  <section class="p-card p-card--dark">
    <header class="p-card-head"><h2>API credentials</h2><p>Use these in your website or app. Keep the secret key private.</p></header>
    <form action="" method="post" class="p-form">
      <div class="p-field"><label for="f_token">Production API token</label><div class="p-copyrow"><input type="text" id="f_token" name="token" value="<?php echo htmlspecialchars($userdata['token']);?>"  readonly><button type="button" class="p-copy" data-copy="#f_token">Copy</button></div></div>
      <div class="p-field"><label for="f_secret">Production secret key</label><div class="p-copyrow"><input type="text" id="f_secret" name="secret" value="<?php echo htmlspecialchars($userdata['secret']);?>"  readonly><button type="button" class="p-copy" data-copy="#f_secret">Copy</button></div></div>
      <div class="p-field"><label for="f_txn_url">Transaction URL</label><div class="p-copyrow"><input type="text" id="f_txn_url" name="txn_url" value="<?php echo htmlspecialchars(webdata('socket').base_url());?>/order/process"  readonly><button type="button" class="p-copy" data-copy="#f_txn_url">Copy</button></div></div>
      <div class="p-field"><label for="f_txn_status_url">Transaction status URL</label><div class="p-copyrow"><input type="text" id="f_txn_status_url" name="txn_status_url" value="<?php echo htmlspecialchars(webdata('socket').base_url());?>/order/status"  readonly><button type="button" class="p-copy" data-copy="#f_txn_status_url">Copy</button></div></div>
      <div class="p-field"><label for="f_stage">Test transaction URL</label><div class="p-copyrow"><input type="text" id="f_stage" name="stage" value="<?php echo htmlspecialchars(webdata('socket').base_url());?>/stage/process"  readonly><button type="button" class="p-copy" data-copy="#f_stage">Copy</button></div></div>
      <div class="p-actions"><button type="submit" name="generate" class="btn bg-white text-dark" data-confirm="Generate new credentials? Your old token and secret stop working.">Generate new credentials</button></div>
    </form>
  </section>
</div>
</div></div></div>
<?php
require_once('layouts/footer.php');
?>