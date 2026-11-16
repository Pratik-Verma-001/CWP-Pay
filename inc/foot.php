</main>
<footer class="footer" id="About">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <a href="index.php"><img src="images/cwp-logo-dark.svg" alt="CWP Pay" width="130" height="36"></a>
        <p style="margin-top:1rem">Accept UPI payments into your own bank account and let <?php echo htmlspecialchars(webdata('company_name'));?> verify them for you.</p>
      </div>
      <div>
        <h4>Links</h4>
        <p><a href="docs/cwp-terms_V2.docx">Terms of service</a></p>
        <p><a href="#Pricing">Pricing</a></p>
        <p><a href="trial/index.php">Try demo</a></p>
        <p><a href="Login">Log in</a></p>
      </div>
      <div>
        <h4>Support</h4>
        <p><a href="tel:+91<?php echo htmlspecialchars(support('mobile'));?>">+91 <?php echo htmlspecialchars(support('mobile'));?></a></p>
        <p><a href="tel:+91<?php echo htmlspecialchars(support('mobile1'));?>">+91 <?php echo htmlspecialchars(support('mobile1'));?></a></p>
        <p><a href="mailto:<?php echo htmlspecialchars(support('email'));?>"><?php echo htmlspecialchars(support('email'));?></a></p>
      </div>
      <div>
        <h4>Address</h4>
        <p><?php echo webdata('company_about');?></p>
      </div>
    </div>
    <p class="footer-legal"><?php echo webdata('web_rights');?></p>
  </div>
</footer>
<script src="js/cwp-site.js"></script>
<?php echo webdata('live_chat');?>
</body>
</html>
