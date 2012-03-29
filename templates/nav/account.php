<?php require dirname(__FILE__).'/../../config/paths.php'; ?>

<div id="subnav">
  <h2>In this section:</h2>
  <ul><!-- DEV NOTE: If anyone other than Passenger, remove the word "My" from the following links -->
  <?php if ($_SESSION['role']=='p'){ ?>
    <li class="sn1"><a href="<?php echo $securePrefix ?>/register.php?edit=true">My Account</a></li>
    <li class="sn2"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=prefs">My Email/Phone Preferences</a></li>
    <li class="sn3"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=locs">My Locations</a></li>
   <?php } else { ?>
    <li class="sn1"><a href="<?php echo $securePrefix ?>/register.php?edit=true">Account</a></li>
    <li class="sn2"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=prefs">Email/Phone Preferences</a></li>
    <li class="sn3"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=locs">Locations</a></li>
   <?php } ?>
  </ul>
</div><!-- /subnav -->
