<?php
require_once('layouts/header.php');

$account = json_decode($userdata['settle_account']);
if(isset($_GET['paystatus'])){
if($_GET['paystatus']=='success'){
    echo '<script>swal("Success!", "Plan Activated Successfully!", "success");</script>';
}else{
    echo '<script>swal("Alert!", "Payment failed!", "error");</script>';
}
}
if(isset($_POST['trial']) && $_POST['trial'] =='free'){
    	$start_date = date('d-m-Y H:i:s');
		$exp_date = date('d-m-Y H:i:s', strtotime($start_date. ' + 3 days'));
		if($userdata['trial_done']=='0'){
		    $usql = sql_query("UPDATE `tb_partner` SET start_date='$start_date', expire_date='$exp_date', trial_done='1'  WHERE id='".$userdata['id']."'");
		    if($usql){
		         echo '<script>swal("Success!", "Trial Activated Successfully!", "success");</script>';
		    }else{
		        echo '<script>swal("Alert!", "Update Error!", "error");</script>';
		    }
		}else{
		    echo '<script>swal("Alert!", "Already Trial taken active genuine Plan!", "error");</script>';
		}
}
if(isset($_POST['plan']) && $_POST['plan'] =='basic'){
       // get payment data from db
        
        $activeKeyData = ['key'=>'d9fsdfsdfe','secret'=>'sfdsdfds','upiuid'=>'sdfdsfdsffsd@paytm'];
		$keyId = $activeKeyData['key'];
		$keySecret = $activeKeyData['secret'];
		$upiuid = $activeKeyData['upiuid'];
		$request_id = rand(1111,9999).time();
		$callback_url = "https://cwp-pay.freedev.app/activePlan.php";
		sql_query("UPDATE  `tb_partner` SET plan_txn_id='$request_id' WHERE id='".$userdata['id']."'");
		$amount = 199;

        $client_name = $userdata['name'];
		$client_email = $userdata['email'];
		$client_mobile = $userdata['mobile'];

        $paramList["upiuid"] = $upiuid;
        $paramList["token"] = $keyId;
        $paramList["orderId"] = $request_id;
        $paramList["txnAmount"] = $amount;
        $paramList["txnNote"] = "Payment received from ".$client_name;
        $paramList["cust_Mobile"] = $client_mobile;
        $paramList["cust_Email"] = $client_email;
        $paramList["callback_url"] = $callback_url;
        require_once('system/checksum.php');
        $checkSum = CxrChecksum::generateSignature($paramList,$keySecret);
        $paramList['checksum']=$checkSum;
        
        if(isset($paramList['checksum'])){
                echo '<html>
                <head>
                <title>Gateway Check Out Page</title>
                </head>
                <body>
                	<center><h1>Please do not refresh this page...</h1></center>
				<form method="post" action="https://cwp-pay.freedev.app/order/paytm" name="f1">
                		<table border="1">
                			<tbody>';
                			foreach($paramList as $name => $value) {
                				echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
                			}
                echo '</tbody>
                    </table>
                		<script type="text/javascript">
                			 document.f1.submit();
                		</script>
                	</form>
                </body>
                </html>';
           
        }else{
            echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
            redirect('','1500');
        }
}
?>

      <!-- adminx-content-aside -->
      <div class="adminx-content">
        <!-- <div class="adminx-aside">

        </div> -->

        <div class="adminx-main-content">
          <div class="container-fluid">
            <!-- BreadCrumb -->
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
<?php $left = getExpiredays(date('d-m-Y H:i:s'), $userdata['expire_date']); ?>
<div class="p-plans">
  <?php if($left >= 0){ ?><p class="p-plan-status"><b><?php echo (int)$left;?> days</b> left on your current plan.</p>
  <?php }else{ ?><p class="p-plan-status is-over">Your plan has expired. Pick a plan below to keep taking payments.</p><?php } ?>
  <div class="p-plan-grid">
    <form class="p-plan p-plan--main" action="" method="POST">
      <span class="p-plan-flag">Most chosen</span>
      <h3>Standard</h3>
      <p class="p-plan-price">&#8377;199<small>for 30 days</small></p>
      <ul>
        <li>Unlimited payments</li>
        <li>0% transaction fee</li>
        <li>Real-time bank settlement</li>
      </ul>
      <input name="plan" type="hidden" value="basic">
      <button type="submit" class="btn bg-white text-dark">Activate now</button>
    </form>
    <form class="p-plan" action="" method="POST">
      <h3>Free trial</h3>
      <p class="p-plan-price">&#8377;0<small>for 3 days</small></p>
      <ul>
        <li>Unlimited payments</li>
        <li>0% transaction fee</li>
        <li>One trial per account</li>
      </ul>
      <input name="trial" type="hidden" value="free">
      <button type="submit" class="btn bg-mix text-white">Start trial</button>
    </form>
  </div>
</div>
</div></div></div>
<?php
require_once('layouts/footer.php');
?>