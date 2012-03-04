<div class="popover_content">
  <p>Are you sure you want to delete your account?  This action cannot be undone.</p>
  <form method="post" action="admin_update.php?fn=deleteUsers">
    <input type="hidden" name="memberid" value="<?php echo $_GET['memberid'] ?>" />
  </form>
</div>
