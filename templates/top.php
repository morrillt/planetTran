<?php
if(!class_exists('Auth')) require_once dirname(__FILE__).'/../secure/reservations/ptRes2/lib/Auth.class.php';
require dirname(__FILE__).'/../config/paths.php';
?>
<div id="top"><div id="skip"><a href="#main" title="skip navigation" accesskey="2">Skip to main content</a></div></div>

<div id="header" class="group">
  <div id="utility">
    <div class="group constrainer">
      <div id="utility_contact">
        <!--a href="<?php echo $sitePrefix ?>/pop/help.php" class="popupwindow" rel="windowPicker">Help</a> <span class="pipe">|</span--> 888.756.8876
      </div>
      <div id="utility_user_links">
        <?php if(Auth::is_logged_in()): ?>
          <!-- If regular Passenger, show the following text: --> <!-- Welcome, John Doe -->
          <!-- If SM and above, show this profile picker link instead: -->
          <a href="<?php echo $sitePrefix ?>/pop/profile.php" id="current_profile" class="popupwindow" rel="windowPicker"><?php echo $_SESSION['currentName'] ?> <img src="/img/icon_arrow_down_fff.png" alt="down arrow" /></a>
          <span class="pipe">|</span>
          <a href="<?php echo $securePrefix ?>/register.php?edit=true">Account Settings</a>
          <span class="pipe">|</span>
          <a href="<?php echo $securePrefix ?>/index.php?logout=true">Logout</a>
        <?php else: ?>
          <a href="<?php echo $securePrefix ?>/index.php">Login</a>
        <?php endif; ?>
      </div><!-- /utility_user_links -->
    </div>
  </div><!-- /utility -->
  <div class="group constrainer">
    <div id="logo">
      <a href="<?php echo $sitePrefix ?>/" accesskey="1"><img src="/img/planettran_logo22560.png" width="225" height="60" alt="PlanetTran" /></a>
    </div>
    <ul id="nav">
      <?php if(!Auth::is_logged_in()): ?>
	<li class="nav_home"><a href="<?php echo $sitePrefix ?>/"><span class="imagetext">Home</span></a></li>
      <?php else: ?>
	<li class="nav_home"><a href="<?php echo $securePrefix ?>/ctrlpnl.php"><span class="imagetext">Home</span></a></li>
      <?php endif ?>
      <li class="nav_reservations"><a href="<?php echo $securePrefix ?>/reserve.php?type=r&amp;step=1"><span class="imagetext">Reservations</span></a></li>
      <li class="nav_service"><a href="<?php echo $sitePrefix ?>/service/"><span class="imagetext">Service</span></a></li>
      <li class="nav_news"><a href="<?php echo $sitePrefix ?>/news/"><span class="imagetext">News</span></a></li>
      <li class="nav_about"><a href="<?php echo $sitePrefix ?>/about/"><span class="imagetext">About</span></a></li>
      <li class="nav_contact"><a href="<?php echo $sitePrefix ?>/Boston-Limo-Services.php"><span class="imagetext">Contact</span></a></li>
    </ul>
  </div>
</div>

<noscript>
  <div class="alert">Your browser needs JavaScript to use this website. Please enable it to continue.</div>
</noscript>
