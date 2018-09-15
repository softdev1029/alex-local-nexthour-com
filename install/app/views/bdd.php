<?php
$Install = new Install();

$allValid = true;
?>
<section>
  <h2>MySQL Configuration</h2>
  <table>
    <tr>
      <td>
        <label>HOST</label>
      </td>
      <td style="text-align:right;">
        <input type="text" name="sql_host" required value="localhost">
      </td>
    </tr>
    <tr>
      <td>
        <label>PORT</label>
      </td>
      <td style="text-align:right;">
        <input type="text" name="sql_port" value="3306">
      </td>
    </tr>
    <tr>
      <td>
        <label>USER</label>
      </td>
      <td style="text-align:right;">
        <input type="text" name="sql_user" required value="krypto">
      </td>
    </tr>
    <tr>
      <td>
        <label>PASSWORD</label>
      </td>
      <td style="text-align:right;">
        <input type="text" name="sql_password" placeholder="Your password">
      </td>
    </tr>
    <tr>
      <td>
        <label>DATABASE NAME</label>
      </td>
      <td style="text-align:right;">
        <input type="text" name="sql_database_name" required value="krypto">
      </td>
    </tr>
  </table>
  <section class="kr-msg kr-msg-error"></section>
</section>
<section class="kr-install-loading">
  Loading ...
</section>
<footer>
  <div>
    <?php if($Install->_getBack() != null): ?>
      <a href="<?php echo $Install->_getBack(); ?>" class="btn-shadow btn-grey">BACK</a>
    <?php endif; ?>
  </div>
  <div>
    <?php if($Install->_getRefresh() != null): ?>
      <a class="btn-shadow btn-orange kr-sql-check">TRY SQL</a>
    <?php endif; ?>
    <?php if($Install->_getForward() != null): ?>
      <input type="submit" disabled class="kr-next-f btn-shadow btn-grey" value="NEXT">
    <?php endif; ?>
  </div>
</footer>
