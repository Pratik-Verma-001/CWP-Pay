<?php
error_reporting(0);
require_once('lib/Config.php');
require_once('lib/Checksum.php');
//print_r($_POST);
$verifySignature = '';
$array = array();
$paramList = array();


$secret = 'rCGqsuzd'; // Your Secret Key.
$status = $_POST['status']; // Its Payment Status Only, Not Txn Status.
$message = $_POST['message']; // Txn Message.
$cust_Mobile = $_POST['cust_Mobile']; // Txn Message.
$cust_Email = $_POST['cust_Email']; // Txn Message.
$hash = $_POST['hash']; // Encrypted Hash / Generated Only SUCCESS Status.
$checksum = $_POST['checksum'];  // Checksum verifySignature / Generated Only SUCCESS Status.

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Payment result | CWP Pay demo</title>
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
<section class="demo-card"><h2>Payment result</h2><p class="sub">This is what your callback page receives.</p><div class="demo-result">
<?php
// Payment Status.
if($status=="SUCCESS"){
	
$paramList = hash_decrypt($hash,$secret);
$verifySignature = CxrChecksum::verifySignature($paramList, $secret, $checksum);

// Checksum verify.
if($verifySignature){
$array = json_decode($paramList);

// Payment Response
echo "<pre>";
echo "Payment Status: $status<br>";	
echo "Payment Message: $message<br>";	
echo "Customer Mobile: $cust_Mobile<br>";
echo "Customer Email: $cust_Email<br>";
echo "Payment hash: $hash<br>";
echo "Payment Checksum: $checksum<hr>";	
foreach ($array as $key => $value) {
  echo "<b>$key:</b> <b style='color:green'>$value</b><hr>";
}	
echo '<h2><b style="color:green">Checksum Verified!</b></h2>';	
	
}else{
	
// Payment Response
echo "<pre>";
echo "Payment Status: $status<br>";	
echo "Payment Message: $message<br>";		
echo '<h2><b style="color:red">Checksum Invalid!</b></h2>';
	
}	
	

}


?>
</div></section>
</div>
</body>
</html>
