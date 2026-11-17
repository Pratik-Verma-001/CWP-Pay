<?php
session_start();
session_regenerate_id();
require_once('system/function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo htmlspecialchars(webdata('company_name'));?> | Log in</title>
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
if(isset($_POST['login']) && !empty($_POST['login_id']) && !empty($_POST['login_pwd']) ){

$login_id = safe_str($_POST['login_id']);
$login_pwd = safe_str($_POST['login_pwd']);
$login_otp = safe_str($_POST['login_otp']);
if(login_locked()){echo '<script>swal("Too many attempts", "Try again after 10 minutes.", "error");</script>';redirect('','1500');exit;}

$res = sql_query("SELECT * FROM `tb_partner` WHERE login_id='".$login_id."' AND login_otp='".$login_otp."' AND status='active' ");
$result = sql_fetch_array($res);
$pwd = $result['login_pwd'];
if(sql_num_rows($res)>0 && pwd_verify($login_pwd,$pwd) && $result['status']=="active" && $result['login_otp']==$login_otp){
login_ok();
pwd_upgrade($result['id'],$login_pwd,$pwd);
$rand = GenRandomString(8);    
sql_query("UPDATE `tb_partner` SET login_otp='".$rand."' WHERE id='".$result['id']."' ");
$_SESSION['is_login'] = 'YES';	
$_SESSION['uid'] = $result['id'];	
$_SESSION['is_otp'] = $rand;

echo '<script>swal("Successfully!", "Login your Account!", "success");</script>';	
redirect('Dashboard','1500');
}else{
login_fail();
echo '<script>swal("Alert!", "Credentials is invalid!", "error");</script>';
redirect('','1500');
}	
	
}
?>

<?php
if(isset($_POST['pwd_reset']) && !empty($_POST['login_id']) ){

$login_id = safe_str($_POST['login_id']);

$res = sql_query("SELECT * FROM `tb_partner` WHERE login_id='".$login_id."' AND status='active' ");
$result = sql_fetch_array($res);
$pwd = $result['login_pwd'];
if(sql_num_rows($res)>0 && $result['status']=="active"){
$rand = GenRandomString(3).time();    
$pwd = pwd_hash($rand);
if(sql_query("UPDATE `tb_partner` SET login_pwd='".$pwd."' WHERE id='".$result['id']."' ")){
$msg = "Payment Gateway New Login Pwd is: $rand, never share with anyone. ".webdata('socket').base_url();




$sms_status = SendSMS($result['mobile'],$msg);  


$email_message = "<html><head><title>Payment Gateway New Login Pwd is: $rand, never share with anyone.</title></head><body style='font-family: Arial, Helvetica, sans-serif;'>
    <table style='border:1px solid #ccc;border-radius:8px;' cellpadding='8'>
    <tr><td><img src='" .webdata("company_logo"). "' width='100' style='background: linear-gradient(#14705f,#2fd69a); border-radius: 10px; padding: 5px;'/></td></tr>
    <tr><td style>Greetings " . $result['name'] . "</td></tr>
    <tr><td>Your Username: <b>$login_id</b></td></tr>
    <tr><td>Your Password: <b>$rand</b></td></tr>
  
    <tr><td>We are sharing a Password to access your account.(<b style='color:red;'>never share with anyone.</b>)</td></tr>
    <tr><td>Thank you</td></tr>
    <tr><td>Admin - " . webdata('company_name') . "</td></tr>
    <tr><td>Email: " . support('email') . "</td></tr>
    <tr><td>Phone: " . support('mobile') . "</td></tr></table></body></html>";
    
$mail_status = SendMail($result['email'], " ".webdata('company_name')." ($rand) is your Password for secure access", $email_message);

echo '<script>swal("Registered Mobile Number!", "New Password Has Been Sent Successfully!", "success");</script>';
//redirect('','2000');
}else{
echo '<script>swal("Alert!", "Server is Down!", "error");</script>';
redirect('','2000');
}

}else{
echo '<script>swal("Alert!", "Credentials is invalid!", "error");</script>';
redirect('','2000');
}	
	
}


?>
<div class="auth">
  <aside class="auth-side">
    <a href="index.php" aria-label="CWP Pay home"><img src="images/cwp-logo-dark.svg" alt="CWP Pay" width="130" height="40"></a>
    <div>
      <h1>Your payments, checked for you.</h1>
      <p>Log in to see which orders are paid, which are waiting, and what reached your bank.</p>
      <ul class="auth-points"><li>0% fee on every payment</li><li>Orders matched automatically</li><li>Works with every UPI app</li></ul>
    </div>
    <a class="auth-back" href="index.php">&larr; Back to website</a>
  </aside>
  <main class="auth-main">
    <div class="auth-card">
      <h2>Welcome back</h2>
      <p class="auth-sub">Sign in to see your payments and orders.</p>
      <form class="auth-form" action="Login" method="post">
      <div class="field">
        <label for="login_id">Username</label>
        <input type="text" id="login_id" class="lid" name="login_id" placeholder="Your login ID" autocomplete="username" required>
      </div>
      <div class="field">
        <label for="login_pwd">Password</label>
        <div class="pw"><input type="password" id="login_pwd" class="lpwd" name="login_pwd" placeholder="Your password" autocomplete="current-password" ><button type="button" aria-pressed="false">Show</button></div>
      </div>
      <div class="field">
        <label for="login_otp">OTP code</label>
        <div class="otp-row">
          <input type="password" id="login_otp" name="login_otp" maxlength="8" placeholder="8 characters" autocomplete="one-time-code">
          <div id="load"><button type="button" class="btn btn-primary btn-sm" onclick="SendOtpCode();">Send OTP code</button></div>
        </div>
        <span class="hint">We send the code to your registered email and mobile.</span>
      </div>
      <label class="check"><input type="checkbox" required> <span>I accept the terms of service</span></label>
      <div class="auth-actions">
        <button type="submit" name="login" class="btn-cwp">Sign in</button>
        <button type="submit" name="pwd_reset" class="link-btn" formnovalidate>Forgot password?</button>
      </div>
      </form>
      <p class="auth-alt">New here? <a href="CreateAccount">Create an account</a></p>
    </div>
  </main>
</div>
<script src="js/jquery.min.js"></script>
<script src="assets/dist/js/custom-new.js"></script>
<script src="js/cwp-auth.js"></script>
</body>
</html>
