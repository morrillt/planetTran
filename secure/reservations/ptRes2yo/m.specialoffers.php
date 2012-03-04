<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template();
$db = new DBEngine();


pdaheader('Special Offers');
pdawelcome('cpanel');


/*
* Do stuff here
*/
?>

<div class="paragraph">
Check back regularly for new special offers!  Book through the mobile website for instant savings!
</div>

 
<div class="paragraph">
<div class="title">
<?=couplink('MWKND')?>
</div>
25% off any non-airport weekend trips 
</div>
 
<!--
<div class="paragraph">
<div class="title">
<?=couplink('MHOCKEY')?>
</div>
30% off PlanetTran trips to all playoff sharks or bruin games. Tickets must be to or from the stadium.
</div>


<div class="paragraph">
<div class="title">
<?=couplink('MCELTICS')?>
</div>
30% off PlanetTran for all playoff celtic's games. Big green is about to make their annual playoff run.  Lets hope this offer lasts until mid June. Tickets must be to or from the stadium.
</div>


<div class="paragraph">
<div class="title">
<?=couplink('MREDSOX')?>
</div>
30% off PlanetTran for all Red Sox games.  A Green Monstrous offer from PlanetTran!  Grab a PlanetTran car so you can skip the price of parking and hassle of public transport. Tickets must be to or from the stadium.
</div>


<div class="paragraph">
<div class="title">
<?=couplink('MGIANTS')?>
</div>
30% off PlanetTran to SF Giants games.  Grab a hot dog and head down to see Tim Lincecum and Sandoval propel the giants to their best start yet.  Tickets must be to or from the stadium.
</div>
-->

<div class="paragraph">
Promo Coupons cannot be combined with other codes or corporate discounts and are limited in quantity and availability.
</div>

<?
pdafooter();

/*************************************************************/
function couplink($coupon) {
	echo '<a href="m.reserve.php?type=r&mtype=o&coupon='.$coupon.'">'.$coupon.'</a>';
}
?>
