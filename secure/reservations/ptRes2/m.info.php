<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');


$t = new Template();
$db = new DBEngine();
$m = $_GET['mode'];
$back = '<br>&nbsp;<br><a href="m.index.php">Back</a>';
$link = '';
if ($m == 'bb')
	$link = '<br>&nbsp;<br><a href="PT_Webicon.jad">Download PlanetTran Mobile for Blackberry Version 1.0</a>';
else if ($m == 'android')
	$link = '<br>&nbsp;<br><a href="market://search?q=pname:com.planettran">Download PlanetTran Mobile for Android Version 1.0</a>';

pdaheader('PlanetTran Mobile App Instructions');
pdawelcome('iphone', 'm.index.php');


/*
* Do stuff here
*/
if ($m == 'iphone') echo '<link rel="apple-touch-icon" href="apple-touch-icon.png">';
echo '<div style="text-align: center;">';

if ($m == 'iphone') $msg = 'To bookmark the PlanetTran mobile site on your iPhone, click back, tap the "+" symbol on your browser, then select "Add to Home Screen".';
else if ($m == 'bb') $msg = 'Please press the link below to download a Blackberry application that will provide a shortcut to PlanetTran Mobile.';
else if ($m == 'android') $msg = 'Please press the link below to download an Android application that will provide a shortcut to PlanetTran Mobile.';

echo $msg . $link . $back;
echo '</div>';

pdafooter();

?>
