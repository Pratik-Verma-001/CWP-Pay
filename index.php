<?php
require('inc/head.php');
$wa = preg_replace('/\D/', '', support('mobile'));
?>
<section class="hero" id="Home">
  <div class="wrap hero-grid">
    <div>
      <h1>Take UPI payments with <span class="nofee">no fee</span> on every rupee.</h1>
      <p class="hero-sub">Money goes straight to your own bank account. CWP Pay watches for each payment, matches it to the order, and marks it paid. No settlement wait, no cut.</p>
      <div class="hero-actions">
        <a class="btn-cwp" href="CreateAccount?trial=true">Start 3-day free trial</a>
        <a class="btn-cwp btn-cwp--ghost" href="trial/index.php">Try the demo</a>
      </div>
      <p class="hero-note">No card needed. Works with PhonePe, Google Pay, Paytm and every other UPI app.</p>
    </div>
    <div class="ledger" id="ledger" aria-label="Example of payments being matched">
      <div class="ledger-head">
        <span>Today, live matching</span>
        <button class="ledger-replay" type="button">Replay</button>
      </div>
      <div class="ledger-total">
        <small>Received in your bank</small>
        <strong id="ledger-total">&#8377;0</strong>
      </div>
      <ul class="ledger-rows" id="ledger-rows"></ul>
      <p class="ledger-foot">Sample data to show how matching works.</p>
    </div>
  </div>
</section>

<section class="section savings" id="savings">
  <div class="wrap savings-grid">
    <div>
      <h2>See what a gateway fee costs you.</h2>
      <p class="section-lead">Most gateways keep a slice of every sale. Move the slider to your monthly sales and see what stays with you.</p>
    </div>
    <div class="calc">
      <div class="calc-row">
        <label for="calc-sales">Monthly sales</label>
        <output id="calc-sales-out" for="calc-sales">&#8377;2,00,000</output>
      </div>
      <input id="calc-sales" type="range" min="10000" max="2000000" step="10000" value="200000">
      <fieldset>
        <legend>Fee your gateway charges now</legend>
        <div class="rates">
          <label><input type="radio" name="rate" value="1" checked><span>1%</span></label>
          <label><input type="radio" name="rate" value="1.5"><span>1.5%</span></label>
          <label><input type="radio" name="rate" value="2"><span>2%</span></label>
          <label><input type="radio" name="rate" value="2.5"><span>2.5%</span></label>
        </div>
      </fieldset>
      <div class="bars" aria-hidden="true">
        <div class="bar-line"><div class="top"><span>Your current gateway</span><b id="calc-fee-out">&#8377;0</b></div><div class="track"><i id="calc-fee-bar"></i></div></div>
        <div class="bar-line"><div class="top"><span>CWP Pay fee</span><b>&#8377;0</b></div><div class="track"><i class="cwp"></i></div></div>
      </div>
      <div class="keep" id="calc-keep">
        <strong id="calc-save-out">&#8377;0</strong>
        <p id="calc-save-note">more stays with you each month, minus the &#8377;199 plan.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="how">
  <div class="wrap">
    <h2>Paid in three steps.</h2>
    <ol class="steps">
      <li>
        <h3>Add your UPI</h3>
        <p>Connect your UPI ID or Paytm Business account. Your customers pay you, not us.</p>
      </li>
      <li>
        <h3>Create an order</h3>
        <p>Call the API or use a QR code. Each order gets a unique amount and a payment link.</p>
      </li>
      <li>
        <h3>We confirm it</h3>
        <p>CWP Pay checks the payment, matches it to the order and sends your webhook within seconds.</p>
      </li>
    </ol>
  </div>
</section>

<section class="section apps">
  <div class="wrap">
    <h2>Customers pay with the app they already use.</h2>
    <ul class="apps-list">
      <li><img src="images/bank_gpay.jpg" alt="Google Pay"></li>
      <li><img src="images/bank_phonepe.jpg" alt="PhonePe"></li>
      <li><img src="images/bank_bhim_upi.jpg" alt="BHIM UPI"></li>
      <li><img src="images/bank_freecharge.jpg" alt="Freecharge"></li>
      <li><img src="images/bank_sbi.jpg" alt="SBI Pay"></li>
      <li><img src="images/bank_airtel.jpg" alt="Airtel Thanks"></li>
      <li><img src="images/bank_amazon_pay.jpg" alt="Amazon Pay"></li>
      <li><img src="images/bank_payzapp.jpg" alt="PayZapp"></li>
    </ul>
    <small>Logos belong to their owners. All of these apps support BHIM UPI.</small>
  </div>
</section>

<section class="section pricing" id="Pricing">
  <div class="wrap">
    <h2>One plan. No fee per payment.</h2>
    <p class="section-lead">No setup fee and no limit on payments. Pick the trial to try everything first.</p>
    <div class="plans">
      <article class="plan">
        <h3>Free trial</h3>
        <p class="price">&#8377;0<small>for 3 days</small></p>
        <ul>
          <li>0% transaction fee</li>
          <li>Unlimited payments and UPI IDs</li>
          <li>Real-time bank settlement</li>
          <li>Docs and integration kit</li>
          <li>WhatsApp support</li>
        </ul>
        <a class="btn-cwp btn-cwp--ghost" href="CreateAccount?trial=true">Start free trial</a>
      </article>
      <article class="plan plan--main">
        <span class="flag">Most chosen</span>
        <h3>Standard</h3>
        <p class="price">&#8377;199<small>for 30 days</small></p>
        <ul>
          <li>0% transaction fee</li>
          <li>Unlimited payments and UPI IDs</li>
          <li>Real-time bank settlement</li>
          <li>Docs and integration kit</li>
          <li>WhatsApp support</li>
          <li>Migration help and SMS alerts</li>
        </ul>
        <a class="btn-cwp" href="CreateAccount">Buy Standard</a>
      </article>
    </div>
  </div>
</section>

<section class="section support" id="Support">
  <div class="wrap">
    <div>
      <h2>Stuck? Message us on WhatsApp.</h2>
      <p>Setup help is a message away, every day. Tell us what you are building and we will point you to the right step.</p>
    </div>
    <a class="btn-cwp btn-cwp--light" href="https://wa.me/91<?php echo htmlspecialchars($wa);?>">Chat on WhatsApp</a>
  </div>
</section>
<?php
require('inc/foot.php');
