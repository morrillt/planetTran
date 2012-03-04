<?php
/*
* 	Mobile rec locations page	
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


pdaheader('Recommended Locations');
pdawelcome('location');



/*
* Do stuff here
*/

$coast_display = array(	'ma'=>	'Boston',
			'ca'=>	'California');

$cat = $_GET['cat'] ? $_GET['cat'] : 'all';

if ($_GET['loc_coast']) {
	$coast = $_SESSION['loc_coast'] = $_GET['loc_coast'];
} else if ($_SESSION['loc_coast']) {
	$coast = $_SESSION['loc_coast'];
} else {
	$coast = $_SESSION['loc_coast'] = 'ma';
}

	//'t'=>'Travel',
$cats = array(
	'r'=>'Restaurants',
	'e'=>'Entertainment',
	'h'=>'Hotels',
	's'=>'Sporting',
	'o'=>'Office Buildings',
	'p'=>'Shopping');



//$locs = get_cat_locs($cat, $coast);
//CmnFns::diagnose($locs);


// No GET argument or GET argument is "all"; display main category menu
if (!$cat || $cat == 'all') {
	echo '<ul>';
	foreach($cats as $k=>$v) 
		echo '<li><a href="m.recLocs.php?cat='.$k.'">'.$v.'</a></li>';
	echo '</ul>';

	?>
	<div class="smallparagraph">
	<form name="loc_coast" action="<?=$_SERVER['PHP_SELF']?>" method="get">
	Select alternate location: 
	<select name="loc_coast" class="smallselect">
	<?
	$selected = '';
	foreach ($coast_display as $k=>$v) {
		if ($k == $coast) $selected = ' selected';
		else $selected = '';
		echo "<option value=\"$k\"$selected>$v</option>\n";
	}
	
	echo '</select>';
	echo '<input type="submit" value="Select" class="button">';
	?>
	</form></div>
	<?

} else {
	// we have GET, get the locs
	$locs = get_cat_locs($cat, $coast);
	//CmnFns::diagnose($locs);
	echo '<ul>';
	for ($i=0; $locs[$i]; $i++) {
		$cur = $locs[$i];
		echo '<li><a href="m.location.php?type=v&machid='.$cur['machid'].'">'.$cur['name'].'</a></li>';
	}

	echo '</ul>';
}


pdafooter();

/***************************************************************/

function get_cat_locs($cat, $coast) {
	$coasts = array ('ma'=>	-10,
			 'ca'=>	-11);
	$loc_coast = $coasts[$coast];
	//echo $cat, $loc_coast;
	global $db;

	$query = "select * from resources where status=? and groupid=?";
	$result = $db->db->query($query, array($cat, $loc_coast));

	$return = array();
	while ($row = $result->fetchRow()) {
		$return[] = $row;
	}
	return $return;
}

?>
