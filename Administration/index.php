<?php
session_start();
session_regenerate_id();
require_once('../system/function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Administration | Log in</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0c2623">
  <link rel="icon" href="<?php echo htmlspecialchars(webdata('web_fav'));?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="../css/cwp-base.css">
  <link rel="stylesheet" href="../css/cwp-auth.css">
  <script src="../assets/dist/js/historry.js"></script>
  <script src="../assets/dist/js/sweetalert.min.js"></script>
</head>
<body class="cwp-body">

<?php
if(isset($_POST['login']) && !empty($_POST['login_id']) && !empty($_POST['login_pwd']) ){

$login_id = safe_str($_POST['login_id']);
$login_pwd = safe_str($_POST['login_pwd']);
$login_otp = safe_str($_POST['login_otp']);
if(login_locked()){echo '<script>swal("Too many attempts", "Try again after 10 minutes.", "error");</script>';redirect('','1500');exit;}

$res = sql_query("SELECT * FROM `tb_partner` WHERE login_id='".$login_id."' AND login_otp='".$login_otp."' AND type='1' ");
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
/*
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
*/
?>
<div class="auth">
  <aside class="auth-side">
    <a href="../index.php" aria-label="CWP Pay home"><img src="../images/cwp-logo-dark.svg" alt="CWP Pay" width="130" height="40"></a>
    <div>
      <h1>Administration</h1>
      <p>Manage merchants, accounts, transactions and settlements.</p>
      <ul class="auth-points"><li>Merchant accounts and plans</li><li>Transactions and settlements</li><li>Platform settings</li></ul>
    </div>
    <a class="auth-back" href="../index.php">&larr; Back to website</a>
  </aside>
  <main class="auth-main">
    <div class="auth-card">
      <h2>Admin sign in</h2>
      <p class="auth-sub">Restricted area. Sign in with your admin credentials.</p>
      <form class="auth-form" action="" method="post">
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
      <div class="auth-actions">
        <button type="submit" name="login" class="btn-cwp">Sign in</button>
      </div>
      </form>
      
    </div>
  </main>
</div>
<script src="../js/jquery.min.js"></script>
<script src="../assets/dist/js/custom-new.js"></script>
<script src="../js/cwp-auth.js"></script>
</body>
</html>
