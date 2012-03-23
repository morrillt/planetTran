<div class="popover_content">
  <p>Are you sure you want to delete this reservation?  This action cannot be undone.</p>
  <form method="post" action="admin_update.php?fn=delReservation">
    <input type="hidden" name="resid" value="<?php echo $_GET['resid'] ?>" />
  </form>
</div>