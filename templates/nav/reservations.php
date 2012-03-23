<?php require dirname(__FILE__).'/../../config/paths.php'; ?>

<div id="subnav">
  <h2>In this section:</h2>
  <ul>
    <!--li class="sn1"><a href="<?php echo $securePrefix ?>/ctrlpnl.php">Home</a></li-->
    <li class="sn3"><a href="<?php echo $securePrefix ?>/reserve.php?type=r">Reserve</a></li>
    <li class="sn4"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=view">Upcoming</a></li>
    <li class="sn5"><a href="<?php echo $securePrefix ?>/receipts.php">Past Trips/Receipts</a></li>
    <li class="sn7"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=qq">Price Quote</a></li>
    <?php if ($_SESSION['role']=='a' || $_SESSION['role']=='m') { ?>
      <li class="sn9"><a href="<?php echo $securePrefix ?>/ctrlpnl.php?active=schedules">Schedules</a></li>
    <?php } ?>
    <li class="sn6"><a href="<?php echo $securePrefix ?>/referrals.php">Referrals</a></li>
    <li class="sn2"><a href="<?php echo $securePrefix ?>/impact.php">Impact</a></li>
    <?php if ($_SESSION['role']=='a' || $_SESSION['role']=='m') { ?>
      <li class="sn8"><a href="<?php echo $securePrefix ?>/res_search2.php">Search</a></li>
    <?php } ?>
    
    <!-- <li class="sn3"><a href="<?php echo $securePrefix ?>/reserve.php?type=r&machid=&ts=&resid=&scheduleid=&is_blackout=0&read_only=undefined">Reservations</a></li>-->
  </ul>
</div><!-- /subnav -->
