<?php
include_once('lib/DBEngine.class.php');
include_once('lib/Template2.class.php');
include_once('lib/PHPMailer.class.php');
//if (!Auth::is_logged_in()) 
//    Auth::print_login_msg();
if ($_GET['active'] == 'service')
	$t = new Template('Our Service');
else if ($_GET['active'] == 'mobile')
	$t = new Template('Planettran Mobile');
$d = new DBEngine();
$t->printHTMLHeader();
$t->startMain();

if ($_GET['active'] == 'service')
	showService();	
else if ($_GET['active'] == 'mobile')
	showMobile();


$t->endMain();
$t->printHTMLFooter();

///////////////////////////////////////////////////
function showService() {
	$s = file_get_contents('http://www.planettran.com/service.php');
	$s = str_replace('images/', 'http://www.planettran.com/images/', $s);
	$s = str_replace('a href', 'a target="_blank" href', $s);
	$search = array('bos.php','mht.php','pvd.php','sfo.php','oak.php','sjc.php');
	$replace = array_map('absAddr', $search);
	$s = str_replace($search, $replace, $s);	
	echo $s;
}
function absAddr($s) {
	return 'http://www.planettran.com/'.$s;
}
function showMobile() {
	?>
	A description of mobile
	<?
}
