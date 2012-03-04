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


pdaheader('iPhone Instructions');
pdawelcome('iphone', 'm.index.php');

/*
* Do stuff here
*/

?>
To bookmark the PlanetTran mobile site on your iPhone, click back, tap the "+" symbol, then select "Add to Home Screen".
<br>&nbsp;<br>
<a href="m.index.php">Back</a>
<?

pdafooter();

?>
