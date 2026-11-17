<?php
require_once('system/function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo htmlspecialchars(webdata('company_name'));?> | Create account</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0c2623">
  <link rel="icon" href="<?php echo htmlspecialchars(webdata('web_fav'));?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="css/cwp-base.css">
  <link rel="stylesheet" href="css/cwp-auth.css">
  <script src="assets/dist/js/historry.js"></script>
  <script src="assets/dist/js/sweetalert.min.js"></script>
</head>
<body class="cwp-body">

<?php
if(isset($_GET['paystatus'])){
if($_GET['paystatus']=='success'){
    echo '<script>swal("Registered Mobile Number!", "Login Credentials Has Been Sent Successfully!", "success");</script>';
}else{
    echo '<script>swal("Alert!", "Payment failed!", "error");</script>';
    redirect('https://cwp-pay.freedev.app/CreateAccount','1500');
} }
if(isset($_POST['create_account']) && 
!empty($_POST['pan_no']) &&  
!empty($_POST['name']) &&  
!empty($_POST['company_name']) &&  
!empty($_POST['uid_no']) &&  
!empty($_POST['email']) &&  
!empty($_POST['mobile']) ){
    
  $signup =  userSignup($_POST);
  if($signup){
      echo '<script>swal("Registration Success", "login Using your PAN Number!", "success");</script>';
      redirect('/Login','1500');
  }else{
   echo '<script>swal("Alert!", "Registration  failed!", "error");</script>';
   redirect('/CreateAccount','1500');
   } 
  
}

function userSignup($data){
$pan_no = safe_str($data['pan_no']);
$name = strip_tags($data['name']);
$company_name = strip_tags($data['company_name']);
$uid_no = safe_str($data['uid_no']);
$email = strip_tags($data['email']);
$mobile = safe_str($data['mobile']);
$payid = $data['payid'];

$res = sql_query("SELECT * FROM `tb_partner` WHERE pan_no='".$pan_no."' || uid_no='".$uid_no."' || email='".$email."' || mobile='".$mobile."' ");
if(sql_num_rows($res)==''){
$rand = GenRandomString(3).time();  
$pwd = pwd_hash($pan_no);
$query = sql_query("INSERT INTO tb_partner (`login_id`, `login_pwd`, `name`, `company_name`, `mobile`, `email`, `pan_no`, `uid_no`, `status`, `payid`, `password_bckup`) 
VALUES ('".$pan_no."','".$pwd."','".$name."','".$company_name."','".$mobile."','".$email."','".$pan_no."','".$uid_no."','active','".$payid."','')");
if($query){
     return ['status'=>'su'];

}else{
    return ['status'=>'er'];
}



}else{
    return ['status'=>'ae'];
}	
	
}
?>
<div class="auth">
  <aside class="auth-side">
    <a href="index.php" aria-label="CWP Pay home"><img src="images/cwp-logo-dark.svg" alt="CWP Pay" width="130" height="40"></a>
    <div>
      <h1>Start taking UPI payments today.</h1>
      <p>Create an account, add your UPI and test with the free 3-day trial.</p>
      <ul class="auth-points"><li>No card needed for the trial</li><li>0% fee on every payment</li><li>WhatsApp support</li></ul>
    </div>
    <a class="auth-back" href="index.php">&larr; Back to website</a>
  </aside>
  <main class="auth-main">
    <div class="auth-card auth-card--wide">
      <h2>Create your account</h2>
      <p class="auth-sub">It takes about two minutes. Your PAN number becomes your first password.</p>
      <form class="auth-form" action="" method="post">
      <div class="field">
        <label for="pan_no">PAN number</label>
        <input type="text" id="pan_no" name="pan_no" placeholder="ABCDE1234F" required maxlength="10" minlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]" autocapitalize="characters" autocomplete="off" onchange="panValidation(this.value)">
      </div>
      <div class="row2">
      <div class="field">
        <label for="name">Name as per PAN</label>
        <input type="text" id="name" name="name" placeholder="Full name" required maxlength="50" autocomplete="name">
      </div>
      <div class="field">
        <label for="company_name">Business name</label>
        <input type="text" id="company_name" name="company_name" placeholder="Your business" required maxlength="50" autocomplete="organization">
      </div>
      </div>
      <div class="field">
        <label for="uid_no">Aadhaar number</label>
        <input type="text" id="uid_no" name="uid_no" placeholder="12 digit number" required minlength="12" maxlength="12" inputmode="numeric" data-digits autocomplete="off">
      </div>
      <div class="row2">
      <div class="field">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required maxlength="50" autocomplete="email">
      </div>
      <div class="field">
        <label for="mobile">Mobile number</label>
        <input type="tel" id="mobile" name="mobile" placeholder="10 digit number" required minlength="10" maxlength="10" inputmode="numeric" data-digits autocomplete="tel-national">
      </div>
      </div>
      <label class="check"><input type="checkbox" required> <span>I accept the <a href="TermsConditions">Terms &amp; Conditions</a></span></label>
      <div class="auth-actions">
        <button type="submit" name="create_account" class="btn-cwp">Create account</button>
      </div>
      </form>
      <p class="auth-alt">Already registered? <a href="Login">Log in</a></p>
    </div>
  </main>
</div>
<script src="js/jquery.min.js"></script>
<script src="assets/dist/js/custom-new.js"></script>
<script src="js/cwp-auth.js"></script>
</body>
</html>
