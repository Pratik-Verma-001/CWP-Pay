<?php
require_once('system/function.php');
$cwp_name = webdata('company_name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#0c2623">
  <title><?php echo htmlspecialchars(webdata('web_tag'));?></title>
  <meta name="description" content="Accept UPI payments straight into your own bank account with 0% transaction fee. Orders are matched and verified automatically.">
  <link rel="icon" href="<?php echo htmlspecialchars(webdata('web_fav'));?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
  <link rel="stylesheet" href="css/cwp-base.css">
  <link rel="stylesheet" href="css/cwp-site.css">
</head>
<body class="cwp-body">
<header class="site-header" id="top">
  <div class="wrap bar">
    <a class="brand" href="index.php"><img src="images/cwp-logo-light.svg" alt="CWP Pay" width="130" height="38"></a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Menu">
      <svg class="m" viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      <svg class="x" viewBox="0 0 24 24"><path d="M6 6l12 12M18 6L6 18"/></svg>
    </button>
    <nav class="nav" id="site-nav" aria-label="Main">
      <a class="nav-link" href="#how">How it works</a>
      <a class="nav-link" href="#savings">Savings</a>
      <a class="nav-link" href="#Pricing">Pricing</a>
      <a class="nav-link" href="#Support">Support</a>
      <a class="nav-link" href="trial/index.php">Try demo<span class="tag">Free</span></a>
      <div class="nav-cta">
        <a class="btn-cwp btn-cwp--ghost btn-cwp--sm" href="Login">Log in</a>
        <a class="btn-cwp btn-cwp--sm" href="CreateAccount">Create account</a>
      </div>
    </nav>
  </div>
</header>
<main>
