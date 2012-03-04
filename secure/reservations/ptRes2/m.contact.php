<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

/*
if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}
*/
$t = new Template();
$db = new DBEngine();

$back = $_GET['back'] ? $_GET['back'] : 'm.index.php';

pdaheader('Contact');
pdawelcome('contact', $back);


/*
* Do stuff here
*/

$tel = "18887568876";

?>
<ul>
<!--<li>IM Dispatch</li>-->
<li><a href="m.generalFeedback.php?back=<?=$back?>">Contact Form</a></li>
<li><a href="m.generalFeedback.php?back=<?=$back?>&mobile=1">Submit Feedback on PlanetTran Mobile</a></li>
<li><a href="tel:<?=$tel?>">Call PlanetTran (888) 756-8876</a></li>
</ul>

<?

pdafooter();


?>
