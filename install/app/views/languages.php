<?php
$Install = new Install();

$allValid = true;
?>
<section>
  <h2>Select your language</h2>
  <select class="multiple_select" name="language_select" size="7">
    <?php foreach (Lang::getListLanguage() as $keyLang => $language) {
      ?>
      <option <?php if($keyLang == "en") echo 'selected'; ?> value="<?php echo $keyLang; ?>"><?php echo $language; ?></option>
      <?php
    } ?>
  </select>
</section>
<footer>
  <div>
    <?php if($Install->_getBack() != null): ?>
      <a href="<?php echo $Install->_getBack(); ?>" class="btn-shadow btn-grey">BACK</a>
    <?php endif; ?>
  </div>
  <div>
    <?php if($Install->_getRefresh() != null): ?>
      <a href="<?php echo $Install->_getRefresh(); ?>" class="btn-shadow btn-grey">REFRESH</a>
    <?php endif; ?>
    <?php if($Install->_getForward() != null): ?>
      <input type="submit" class="btn-shadow <?php echo ($allValid ? 'btn-orange' : 'btn-grey'); ?>" value="NEXT">
    <?php endif; ?>
  </div>
</footer>
