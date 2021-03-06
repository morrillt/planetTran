
<div id="secondary">
  <?php if(isset($_SESSION['sessionID'])): ?>
    <div id="sb_impact" class="sidebar_box group">
      <h3>Environmental Impact</h3>
      <p>By using PlanetTran, you have reduced greenhouse gases by:</p>
      <div id="gas_reduction">
	<span>-
     <?php
	 // ini_set('display_errors', 1);
	 //error_reporting(E_ALL);

	  include_once(dirname(__FILE__).'/../secure/reservations/ptRes2/impact_includes.php');

	  $d = new DBEngine();
	  $id = isset($_GET['memberid'])?$_GET['memberid']:$_SESSION['sessionID'];
	  $mFares = get_member_fares($id);

	  if ($d->has_referrals($id))	$rFares = $d->get_referred_fares($id);
	  else				$rFares = array('total_fare' => 0, 'avg_fare' => 0);

	  // Convert to CO2
	  $mFares['total_fare'] = $d->coConvert($mFares['total_fare'], $mFares['avg_fare']);
	  $total = $mFares['total_fare'] + $rFares['total_fare'];
	  echo $total.'';

	  ?></span><strong>LBS</strong>
      </div>
    </div><!-- /sidebar_box -->
  <?php endif ?>
  <div id="sb_email_signup" class="sidebar_box group">
    <h3>Signup for Email Updates</h3>
    <form method="post" id="mailing-form" action="http://oi.vresp.com?fid=7a06db36ae" target="vr_optin_popup" onsubmit="window.open( 'http://www.verticalresponse.com', 'vr_optin_popup', 'scrollbars=yes,width=600,height=450' ); return true;">
      <label for="list_email_address">Email Address</label><input id="list_email_address" name="email_address" type="text" value="" placeholder="email address" size="25" maxlength="255" />
      <input type="submit" value="Submit" class="button">
    </form>

  </div><!-- /sidebar_box -->
  <div id="sb_connect" class="sidebar_box group">
    <h3>Connect With Us</h3>
    <ul id="connect">
      <li id="connect_twitter"><a href="" onclick="window.open('http://twitter.com/planettran');">Twitter</a></li>
      <li id="connect_facebook"><a href="#" onclick="window.open('http://www.facebook.com/planettran');">Facebook</a></li>
      <!--li id="connect_rss"><a href="#">RSS</a></li-->
      <li id="connect_email"><a href="<?php echo $siteprefix ?>/Boston-Limo-Services.php">Email</a></li>
    </ul>
  </div><!-- /sidebar_box -->
</div><!-- /secondary -->
