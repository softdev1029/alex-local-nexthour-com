<?php
$Install = new Install();

$allValid = true;
?>
<section>
  <table>
    <?php
    foreach ($Install->_getServerRequirement() as $requirement) {
      if($allValid && !$requirement['valid']) $allValid = false;
    ?>
    <tr>
      <td>
        <label><?php echo $requirement['title']; ?></label><br/>
        <span><?php echo $requirement['description']; ?></span>
      </td>
      <td style="text-align:right;">
        <label class="kr-check-status <?php echo ($requirement['valid'] ? '' : 'kr-check-status-nv'); ?>"><?php echo ($requirement['valid'] ? 'Valid' : 'Missing'); ?></label>
      </td>
    </tr>
    <?php } ?>
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
      <input type="submit" class="btn-shadow <?php echo ($allValid ? 'btn-orange' : 'btn-grey'); ?>" value="NEXT">
    <?php endif; ?>
  </div>
</footer>
