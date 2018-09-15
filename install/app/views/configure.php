<?php
$Install = new Install();

$configureData = $Install->_getConfigureContent();

?>
<section>
  <table>
    <?php
    foreach ($configureData as $configureKey => $configureContent) {
      ?>
      <tr>
        <td>
          <label><?php echo $configureContent['title']; ?></label><br/>
          <input type="text" <?php echo ($configureContent['disabled'] ? 'disabled' : ''); ?> <?php echo ($configureContent['require'] ? 'required' : ''); ?> name="<?php echo $configureKey; ?>" value="<?php echo $configureContent['precontent']; ?>">
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
    <?php if($Install->_getRefresh() != null): ?>
      <a href="<?php echo $Install->_getRefresh(); ?>" class="btn-shadow btn-grey">REFRESH</a>
    <?php endif; ?>
    <?php if($Install->_getForward() != null): ?>
      <input type="submit" class="btn-shadow btn-orange" value="NEXT">
    <?php endif; ?>
  </div>
</footer>
