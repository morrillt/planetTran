<?php require dirname(__FILE__).'/../../config/paths.php'; ?>

<div id="subnav">
  <h2>In this section:</h2>
  <ul><!-- DEV NOTE: If anyone other than Passenger, remove the word "My" from the following links -->
    <li class="sn1"><a href="<?php echo $securePrefix ?>/register.php?edit=true">My Account</a></li>
    <li class="sn2"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=prefs">My Email/Phone Preferences</a></li>
    <li class="sn3"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=locs">My Locations</a></li>
  </ul>
</div><!-- /subnav -->
