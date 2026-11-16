<!DOCTYPE html>
<html lang="en">
<head>
<title>Test demo | CWP Pay demo</title>
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
<div class="demo-grid">
  <aside class="demo-aside">
    <h1>Try a test payment.</h1>
    <p>This demo shows what your customer sees when they pay through CWP Pay.</p>
    <ol>
      <li>Pick an amount and enter your details.</li>
      <li>Scan the QR code with any UPI app.</li>
      <li>See the verified result on your callback page.</li>
    </ol>
  </aside>
  <section class="demo-card">
    <h2>Payment details</h2>
    <p class="sub">Fill in the form and continue to the checkout page.</p>
    <form class="demo-form" action="txnProcess.php" method="post">
      <div>
        <label for="gateway_type">Gateway type</label>
        <select id="gateway_type" name="gateway_type" required>
          <option value="Advanced">Advanced</option>
          <option value="Robotics">Robotics</option>
          <option value="Normal">Normal</option>
        </select>
      </div>
      <div class="two">
        <div><label for="txnAmount">Amount (&#8377;)</label><input id="txnAmount" type="text" inputmode="decimal" name="txnAmount" value="1" required></div>
        <div><label for="txnNote">Note</label><input id="txnNote" type="text" name="txnNote" value="Test Payment" placeholder="Payment note" required></div>
      </div>
      <div class="two">
        <div><label for="cust_Mobile">Mobile number</label><input id="cust_Mobile" type="tel" inputmode="numeric" name="cust_Mobile" placeholder="10 digit number" maxlength="10" required></div>
        <div><label for="cust_Email">Email</label><input id="cust_Email" type="email" name="cust_Email" placeholder="you@example.com" required></div>
      </div>
      <button type="submit" class="btn-cwp">Continue to payment</button>
    </form>
  </section>
</div>
</div>
</body>
</html>
