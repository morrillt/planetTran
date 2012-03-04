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
//if (isset($_GET['rid'])) setcookie("rid", $_GET['rid'], time()+3600*12*30);
set_include_path("../:lib/pear/:/usr/local/php5");
include_once('../../reservations/ptRes2/lib/Template.class.php');
include_once('templates/mobile.template.php');

$auth = new Auth();
$t = new Template();
$msg = '';
$resume = (isset($_POST['resume'])) ? $_POST['resume'] : (isset($_GET['resume'])?$_GET['resume']:'');
$resume = 'm.main.php?showalert=1';
$mobile = true;
	
// Logging user out
if (isset($_GET['logout'])) {
    $auth->doLogout();   
} else if (isset($_POST['login'])) {
	$msg = $auth->doLogin($_POST['email'], $_POST['password'], (isset($_POST['setCookie']) ? 'y' : null), false, $resume);
} else if (isset($_COOKIE['ID'])) {
    $msg = $auth->doLogin('', '', 'y', $_COOKIE['ID'], $resume);
}

pdaheader('PlanetTran');
echo '<link rel="apple-touch-icon" href="apple-touch-icon.png">';

if (isset($_GET['auth'])) {
	pdaLoginForm('You are not logged in!', $_GET['resume']);
}
else {
	pdaLoginForm($msg);
}

pdafooter();
?>
