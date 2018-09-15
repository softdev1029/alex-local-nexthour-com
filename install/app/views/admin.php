<?php
$Install = new Install();

$loginData = $Install->_getLoginFields();

?>
<section>
  <h2>Admin login</h2>
  <table>
    <?php
    foreach ($loginData as $loginKey => $loginContent) {
      ?>
      <tr>
        <td>
          <label><?php echo $loginContent['title']; ?></label><br/>
          <input type="text" <?php echo ($loginContent['disabled'] ? 'disabled' : ''); ?> required name="<?php echo $loginKey; ?>" placeholder="<?php echo $loginContent['placeholder']; ?>" value="">
        </td>
      </tr>
      <?php
    }
    ?>
  </table>
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
