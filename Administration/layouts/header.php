<?php
session_start();
session_regenerate_id();
require_once('../system/function.php');
$uid = $_SESSION['uid'];
$is_otp = $_SESSION['is_otp'];
$usql = sql_query("SELECT * FROM `tb_partner` WHERE id='".$uid."' AND login_otp='".$is_otp."' ");
$userdata = sql_fetch_array($usql);

if($userdata['type']!=1){
redirect('Logout','0');	
exit();
}

$plan_slab = sql_fetch_array(sql_query("SELECT * FROM `tb_slab` WHERE id='".$userdata['slab']."' "));

if(!isset($_SESSION['uid']) || !isset($_SESSION['is_otp']) || $is_otp!=$userdata['login_otp']){
redirect('Logout','0');	
}
require_once('../inc/panel.php');
$cwp_items = array(
  array('Dashboard','Dashboard','grid'),
  array('UPIsAccounts','UPIs Accounts','at'),
  array('Transactions','Transactions','list'),
  array('PaytmReports','Paytm Reports','report'),
  array('UPITransactions','UPI Reports','phone'),
  array('Settlement','Settlement','bank'),
  array('Settings','Settings','cog'),
  array('Users','Users','users'),
  array('Slab','Slab','tag'),
);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars(webdata('company_name'));?> | Administration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script>try{var t=localStorage.getItem('cwp-theme');if(t)document.documentElement.setAttribute('data-theme',t);}catch(e){}</script>
    <link rel="stylesheet" type="text/css" href="../assets/dist/css/adminx.css" media="screen" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <link rel="icon" href="<?php echo htmlspecialchars(webdata('web_fav'));?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="../css/cwp-base.css">
    <link rel="stylesheet" href="../css/cwp-panel.css">
    <script src="../assets/dist/js/historry.js"></script>
    <script src="../assets/dist/js/sweetalert.min.js"></script>
  </head>
  <body class="cwp-body panel">
    <div class="adminx-container p-shell">
      <aside class="p-side" id="p-side" aria-label="Sidebar">
        <div class="p-side-head">
          <a href="Dashboard" class="p-logo"><img src="../images/cwp-logo-dark.svg" alt="CWP Pay" width="120" height="34"></a>
          <button type="button" class="p-icon-btn p-side-close" aria-label="Close menu"><?php echo cwp_icon('close');?></button>
        </div>
        <?php echo cwp_nav($cwp_items);?>
        <a href="Logout" class="p-link p-logout"><?php echo cwp_icon('logout');?><span>Log out</span></a>
      </aside>
      <div class="p-scrim" hidden></div>
      <header class="p-top">
        <button type="button" class="p-icon-btn p-menu-btn" aria-label="Open menu" aria-controls="p-side" aria-expanded="false"><?php echo cwp_icon('menu');?></button>
        <a href="Dashboard" class="p-top-logo"><img src="../images/cwp-logo-light.svg" alt="CWP Pay" width="104" height="30"></a>
        <div class="p-top-end">
          <button type="button" class="p-icon-btn p-theme" aria-label="Switch light or dark theme"><span class="t-sun"><?php echo cwp_icon('sun');?></span><span class="t-moon"><?php echo cwp_icon('moon');?></span></button>
          <span class="p-user"><?php echo htmlspecialchars($userdata['name']);?></span>
          <a href="Logout" class="p-icon-btn" aria-label="Log out"><?php echo cwp_icon('logout');?></a>
        </div>
      </header>

