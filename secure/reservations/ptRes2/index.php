<?php
/**
* This file is the login page for the system
* It provides a login form and will automatically
* forward any users who have cookies set to ctrlpnl.php
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 06-25-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Template class
*/
$biz = (isset($_GET['biz']) ? $_GET['biz'] : 'www') ;
$prefix = (isset($_GET['prefix']) ? $_GET['prefix'] : $biz);
$groupid = (isset($_GET['groupid']) ? $_GET['groupid'] : 0);
$billtype = (isset($_GET['billtype']) ? $_GET['billtype'] : 'c');
if (isset($_GET['rid'])) setcookie("rid", $_GET['rid'], time()+3600*12*30);
set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
// Auth included in Template.php

$auth = new Auth();
$t = new Template();
$msg = '';
$resume = (isset($_POST['resume'])) ? $_POST['resume'] : (isset($_GET['resume'])?$_GET['resume']:'');

// Logging user out
if (isset($_GET['logout'])) {
    $auth->doLogout();   
} else if(isset($_POST['login'])) {
	$msg = $auth->doLogin($_POST['email'], $_POST['password'], (isset($_POST['setCookie']) ? 'y' : null), false, $resume);
} else if(isset($_COOKIE['ID'])) {
    //$msg = $auth->doLogin('', '', 'y', $_COOKIE['ID'], $resume);
    unset($_COOKIE['ID']);
} else if (Auth::is_logged_in()) {
  header('Location: reserve.php?type=r');
  die();
}

//$t->printHTMLHeader(true);
//echo '<table width="600" align="center" cellspacing=0 cellpadding=0>';
$t->printHTMLHeader();

//set_include_path("../../;../;lib/pear/;C:/AppServ/php5");
//include("header_nav.shtml");

$t->startMain();

if (isset($_GET['auth'])) {
	$auth->printLoginForm(translate('You are not logged in!'), $_GET['resume']);
}
else {
	$auth->printLoginForm($msg);
}

$t->endMain();
/*
include("footer.shtml");
	<div align="center">
	<table>
	<tr>
      	<td width="600" colspan="3">
      	
	
<div id="footer">
	<form method="post" id="mailing-form" action="http://oi.vresp.com?fid=7a06db36ae" target="vr_optin_popup" onsubmit="window.open( 'http://www.verticalresponse.com', 'vr_optin_popup', 'scrollbars=yes,width=600,height=450' ); return true;" >
		<div class="inputs">
			<p>Join our mailing list. <label for="mailing-email">Email</label> <input name="email_address" id="mailing-email" class="textbox" /></p>
			<a href="http://www.verticalresponse.com/?ref=oif" title="Email Marketing by VerticalResponse"  id="powered-by">Powered by VerticalResponse</a>
		</div>
		<div class="submit">
			<input type="image" src="images/button-joinnow.gif" class="image" value="Join Now" />
		</div>
	</form>
	<p class="copyright-info" align="center">Copyright 2009, PlanetTran. All Rights Reserved.</p>
</div>

		</td>
	</tr>	
<?
//echo '</div></table>';
 */
$t->printHTMLFooter();
?>
