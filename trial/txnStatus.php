<!DOCTYPE html>
<html lang="en">
<head>
<title>Payment status | CWP Pay demo</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<link rel="icon" href="../favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,800&family=Instrument+Sans:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="../css/cwp-base.css">
<link rel="stylesheet" href="../css/cwp-demo.css">
</head>
<body class="cwp-body">
<div class="wrap demo">
<div class="demo-top"><a href="../index.php"><img src="../images/cwp-logo-light.svg" alt="CWP Pay" width="130" height="38"></a><a class="btn-cwp btn-cwp--ghost btn-cwp--sm" href="../index.php">Back to website</a></div>
<section class="demo-card">
<h2>Payment status check</h2>
<p class="sub">Enter the order ID to see its current status.</p>
<form class="demo-form" action="" method="post">
<div><label for="orderId">Order ID</label><input id="orderId" type="text" name="orderId" placeholder="Order ID"></div>
<button type="submit" class="btn-cwp">Check status</button>
</form>
<div class="demo-result" style="margin-top:1.4rem">
<?php
error_reporting(0);
require_once('lib/Config.php');
require_once('lib/CxrChecksum.php');
if(isset($_POST['orderId'])){
$post = array(
    'token' => $token,
    'orderId' => $_POST['orderId']
	);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $CWP_STATUS_URL,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => $post
));
$response = curl_exec($curl);
curl_close($curl);
//echo $response;
$array = json_decode($response);
echo "Payment Status: ".$array->status."<br>";	
echo "Payment Message: ".$array->message."<hr>";	
foreach ($array->result as $key => $value) {
  echo "<b>$key:</b> <b style='color:green'>$value</b><hr>";
}
}
?>
</div></section>
</div>
</body>
</html>
