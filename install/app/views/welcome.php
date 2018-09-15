<?php
$Install = new Install();
?>
<section>
  <p>Hi! Thank you for buying <b>Krypto</b>, the new software made by OVRLEY<br/>
  We hope that it will meet your desires and your needs, and if you have any questions, ideas for improvements, we are here for you!</p>
  <div class="signature">
    <span>Léo DUMONTIER</span>
    <b>CEO, OVRLEY</b>
    <img src="assets/img/signature.png" alt="Léo Dumontier Signature">
  </div>
</section>
<footer>
  <div>
    <?php if($Install->_getBack() != null): ?>
      <a href="<?php echo $Install->_getBack(); ?>" class="btn-shadow btn-grey">BACK</a>
    <?php endif; ?>
  </div>
  <div>
    <?php if($Install->_getForward() != null): ?>
      <input type="submit" class="btn-shadow btn-orange" value="NEXT">
    <?php endif; ?>
  </div>
</footer>
