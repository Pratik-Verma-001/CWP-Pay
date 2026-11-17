<?php
require_once('layouts/header.php');
?>

      <!-- adminx-content-aside -->
      <div class="adminx-content">
        <!-- <div class="adminx-aside">

        </div> -->

        <div class="adminx-main-content">
          <div class="container-fluid">
            <!-- BreadCrumb -->
<!-- Modal -->
<div class="modal fade" id="AddFunds" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content p-4 card-nblue">
   <form action="" method="post">
    <div class="form-group">
      <label class="text-white">UTR Number</label>
      <input type="text" class="form-control form-control-sm" placeholder="UTR / RRN / Bank Reference Number" name="rrn" maxlength="12" onkeypress="return isNumber(event)">
    </div>
    <button type="submit" name="addfund" class="btn bg-white text-dark btn-sm"><i class="fa fa-bolt"></i> Submit</button>
<p class="text-white mt-4">If the money is transferred through IMPS/UPI, the amount will be reflected in your account within 10 mins.
<br>If the money is transferred through NEFT/RTGS, the amount will be reflected in your Account within 3 working hours.</p>
  </form>
    </div>
  </div>
</div>
<?php
if(isset($_POST['addfund']) && !empty($_POST['rrn']) ){
$rrn = safe_str($_POST['rrn']);
$res = sql_query("SELECT * FROM `tb_virtualtxn` WHERE bank_ref_num='".$rrn."' ");
$txn_data = sql_fetch_array($res);
if(sql_num_rows($res)==1){
$query = sql_query("SELECT * FROM `tb_transactions` WHERE rrn='".$rrn."' ");

if(sql_num_rows($query)==0 && is_numeric($txn_data['bene_account_no'])){
$order_id = GenRandomString();		
$pay_upi = $txn_data['rmtr_account_no'];
$mode = $txn_data['payment_type'];
$upi_id = $txn_data['bene_account_no'];
$newrrn = $txn_data['bank_ref_num'];
$txn_amount = $txn_data['amount'];	
$settle_amount = $txn_data['settled_amount'];
$txn_type = 'CREDIT';
$remark = $txn_data['rmtr_to_bene_note'];

$closing_balance = $userdata['balance'] + $settle_amount;

$result = sql_query("INSERT INTO `tb_transactions`(`token`, `user_uid`, `order_id`, `date`, `time`, `type`, `mode`, `pay_upi`, `upi_id`, `rrn`, `txn_amount`, `settle_amount`, `closing_balance`, `txn_type`, `remark`, `status`) 
VALUES ('".$userdata['token']."','".$userdata['id']."','$order_id','".date_time("Y-m-d")."','".date_time("h:i:s A")."','Funds Added','$mode','$pay_upi','$upi_id','$newrrn','$txn_amount','$settle_amount','$closing_balance','$txn_type','$remark','COMPLETED')");

if($result){
sql_query("UPDATE `tb_partner` SET balance='".$closing_balance."' WHERE id='".$userdata['id']."' ");
echo '<script>swal("Successfully!", "Funds Added!", "success");</script>';	
redirect('','1500');	
}else{
echo '<script>swal("Alert!", "Service is Down!", "error");</script>';
redirect('','2000');
}
	
	
}else{
echo '<script>swal("Duplicate!", "UTR / RRN / Bank Reference Number!", "error");</script>';
redirect('','1500');
}


}else{
echo '<script>swal("Invalid!", "UTR / RRN / Bank Reference Number!", "error");</script>';
redirect('','1500');
}	
}
?>

<?php
$start_date = $userdata['start_date'];
$exp_date = $userdata['expire_date'];
$days_left = (int)getExpiredays(date('d-m-Y H:i:s'),$exp_date);
$total_days = (int)getExpiredays($start_date,$exp_date);
$pct = $total_days>0 ? max(0,min(100,round($days_left/$total_days*100))) : 0;
$low = ($days_left <= 3);
?>
<div class="p-stats">
  <section class="p-hero">
    <p class="p-hero-label">Account balance</p>
    <p class="p-hero-amt"><span>&#8377;</span><b id="balance"><?php echo moneyformat($userdata['balance']);?></b>
      <button type="button" id="load" class="p-refresh" onclick="getBalance()" aria-label="Refresh balance"><svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 11a8 8 0 1 0-2.3 5.7M20 4v7h-7"/></svg></button></p>
    <div class="p-hero-actions">
      <button type="button" class="btn bg-white text-dark" data-toggle="modal" data-target="#AddFunds">Add funds</button>
      <a href="Settlement" class="btn p-ghost">Bank settlement</a>
    </div>
  </section>
  <section class="p-card p-plan-card">
    <p class="p-label">Your plan</p>
    <p class="p-big <?php echo $low ? 'is-low' : '';?>"><b><?php echo $days_left;?></b> days left</p>
    <div class="p-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $pct;?>"><i style="width:<?php echo $pct;?>%"></i></div>
    <p class="p-sub"><?php echo $low ? 'Renew soon so payments keep working.' : 'Active until '.htmlspecialchars($exp_date).'.';?></p>
    <a class="p-link-sm" href="plans">View plans</a>
  </section>
  <section class="p-card p-bank">
    <p class="p-label">Add funds by bank transfer to <?php echo htmlspecialchars(webdata('company_name'));?></p>
    <dl>
      <dt>Company name</dt><dd><?php echo htmlspecialchars(company_account('name'));?></dd>
      <dt>Account / IFSC</dt><dd><span id="bank_acc"><?php echo htmlspecialchars(company_account('account'));?> / <?php echo htmlspecialchars(company_account('ifsc'));?></span> <button type="button" class="p-copy" data-copy="#bank_acc">Copy</button></dd>
    </dl>
  </section>
</div>
<nav class="p-tiles" aria-label="Quick links">
  <a href="UPIsSettings"><span>UPI details</span><small>Set where money arrives</small></a>
  <a href="MyQRCode"><span>My QR code</span><small>Print or share</small></a>
  <a href="Developer"><span>Developer</span><small>API and test checkout</small></a>
  <a href="Settings"><span>Settings</span><small>Profile and credentials</small></a>
</nav>
<h2 class="p-section-title">Today's transactions</h2>
<div class="row mt-2">
              <div class="col">
                <div class="card mb-grid">
                  <div class="table-responsive-md">
				  
                    <table class="table table-hover">
					<form action="" method="post"> 
                      <thead>
                        <tr class="card-nblue">
                          <th class="text-white">Account Statement</th>
                          <th class="text-white">Transactions Details</th>
						  <th class="text-white">
						  <input type="date" name="from_date" value="" class="form-control form-control-sm">
						  </th>
						  
						  <th class="text-white">
						  <input type="date" name="to_date" value="" class="form-control form-control-sm">
						  </th>
						  
                          <th class="text-white">
						  <input type="number" name="rows_input" value="10" class="form-control form-control-sm" placeholder="Show entries">
						  </th>
						  
<th class="text-white" colspan="3">
<div class="input-group">
<input class="form-control form-control-sm" name="search_input" placeholder="Search ID / Bank Ref Number" style=" height: 30.1px; ">
<div class="input-group-append">
<button class="btn bg-mix text-white btn-sm" name="filter" type="submit"><i class="fa fa-search"></i></button>
</div>
</div>
</th>
<th class="text-white">
<button class="btn bg-mix text-white btn-sm" onclick="Export('Transactions')"  type="button">Export <i class="fa fa-external-link-alt"></i></button>
</th>
						  
                        </tr>
                      </thead>
					</form>
					
                      <thead>
                        <tr class="bg-mix">
                          <th class="text-white">ID</th>
                          <th class="text-white">Time</th>
                          <th class="text-white">Type</th>
                          <th class="text-white">Payment Mode</th>
                          <th class="text-white">Txn Amount</th>
                          <th class="text-white">Settled Amount</th>
                          <th class="text-white">Closing Balance</th>
                          <th class="text-white">Credit/Debit</th>
                          <th class="text-white">Status</th>
						  
                        </tr>
                      </thead>
                      <tbody>
<?php
$sql = "SELECT * FROM `tb_transactions` WHERE date between '".date_time("Y-m-d")."' AND '".date_time("Y-m-d")."' AND user_uid='".$userdata['id']."' ORDER BY `id` DESC ";	

if(isset($_POST['filter']) AND 
!empty($_POST['from_date']) AND 
!empty($_POST['to_date']) AND 
!empty($_POST['rows_input']) ){
	
$from_date = date("Y-m-d", strtotime($_POST['from_date']));	
$to_date = date("Y-m-d", strtotime($_POST['to_date']));	
$rows_input = safe_str($_POST['rows_input']);
$search_input = safe_str($_POST['search_input']);
$concat = '';
if($search_input!=''){
$concat	= " CONCAT(`order_id`,`rrn`) LIKE '%$search_input%' AND ";
}
	
$sql = "SELECT * FROM `tb_transactions` WHERE $concat date between '".$from_date."' AND '".$to_date."' AND user_uid='".$userdata['id']."' ORDER BY `id` DESC LIMIT $rows_input ";	
 
}

$query  = sql_query($sql);
$sl = 1;
while($rows = sql_fetch_array($query)){ 

if($rows['txn_type']=='CREDIT'){
$txn_type = 'success';	
}else if($rows['txn_type']=='DEBIT'){
$txn_type = 'danger';		
}else{
$txn_type = 'info';		
}
?>						  
						<tr>
                          <td>#<?php echo $rows['order_id'];?><br><?php echo $rows['client_orderid'];?></td>
                          <td><?php echo $rows['date'];?> <?php echo $rows['time'];?></td>
                          <td><?php echo strtoupper($rows['upi_id']);?><br><?php echo $rows['type'];?></td>
                          <td><?php echo strtoupper($rows['pay_upi']);?><br><?php echo $rows['mode'];?>-<?php echo $rows['rrn'];?></td>
                          <td>₹<?php echo moneyformat($rows['txn_amount']);?></td>
                          <td>₹<?php echo moneyformat($rows['settle_amount']);?></td>
                          <td>₹<?php echo moneyformat($rows['closing_balance']);?></td>
                          <td class="text-<?php echo $txn_type;?>"><?php echo $rows['txn_type'];?></td>
                          <td><?php echo $rows['status'];?></td>
                        </tr>
<?php
$sl++;}
?>											
						
                      </tbody>
                    </table>
					
					<div class="row">
					<div class="col-sm-12 col-md-5">
					
					</div>
					</div>
					
                  </div>
                </div>
              </div>
            </div>

            
            
          </div>
        </div>
      </div>
<?php
require_once('layouts/footer.php');
?>    
<script>
function getBalance(){
    
document.getElementById("load").innerHTML = "<img src='assets/img/animated_spinner.webp' width='20' alt=''>";        
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        
      var obj = JSON.parse(this.responseText);
      
      if(obj.status=='SUCCESS'){
      document.getElementById("load").innerHTML = "<svg class=\"ico\" viewBox=\"0 0 24 24\" aria-hidden=\"true\"><path d=\"M20 11a8 8 0 1 0-2.3 5.7M20 4v7h-7\"/></svg>"; 
      document.getElementById("balance").innerHTML = obj.balance;        
      }else{
      document.getElementById("load").innerHTML = "<svg class=\"ico\" viewBox=\"0 0 24 24\" aria-hidden=\"true\"><path d=\"M20 11a8 8 0 1 0-2.3 5.7M20 4v7h-7\"/></svg>"; 
      document.getElementById("balance").innerHTML = 'Error, Try Agian!';        
      }   
        
    }
  };
  xhttp.open("POST", "UserBalance",true);
  xhttp.send();    
    
    
}   

$(document).ready(function() {
  //  $('#datatable').DataTable();
} );
 
</script>     