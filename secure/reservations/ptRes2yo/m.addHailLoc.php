<?php
/*
*	Add a GPS loc to the user's permissions then forward them
*	to the reservation form
*/
include_once('lib/Template.class.php');
include_once('lib/Twitter.class.php');
$tw = new Twitter();
$loc = $tw->get_gps_loc_values($_POST, 'hail');
$name = $loc['name'];
$name = urlencode("(GPS) ".$name);

$forwardstr .= "m.hail.quote.php?type=$type&mtype=$mtype&machid=$machid&start=$time&date=$date&lat=".$_POST['mylat']."&lon=".$_POST['mylon']."&gpsname=$name";
header('Location: '.$forwardstr);
die;

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('lib/Twitter.class.php');
//include_once('templates/cpanel.template.php');
//include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}

//$t = new Template();
$db = new DBEngine();

$memberid = $_SESSION['sessionID'];

/*
* create location and add to their permissions
*/

// get scheduleid
$vals = array($memberid);
$query = "select scheduleid from schedules where scheduleTitle=?";
$scheduleid = $db->db->getOne($query, $vals);

$tw->scheduleid = $scheduleid;

// Existing location
if ($_POST['machid'])
	$machid = $_POST['machid'];
else {
	// Make new location
	$tw->fromLat = $_POST['mylat'];
	$tw->fromLon = $_POST['mylon'];
	$loc = $tw->get_gps_loc_values($_POST, 'hail');
	//if ($loc['name'])
	//	$loc['name'] = "(GPS) ".$loc['name'];
	$machid = $loc['machid'];

}


$queryFields = array();
$vals = array();

foreach($loc as $k=>$v) {
	$queryFields[] = "$k=?";
	$vals[] = $v;
}
		
$query = "insert into resources set ".implode(", ", $queryFields);
$query .= " on duplicate key update machid=machid";
echo $query;
$q = $db->db->prepare($query);
$result = $db->db->execute($q, $vals);

$vals = array($memberid, $machid);
$query = "insert into permission (memberid, machid) values (?, ?)";
$result = $db->db->query($query, $vals);
//DBEngine::check_for_error($result);

$time = date("G")*60 + date("i");
$time -= ($time % 15);
$time += 30;

$date = date("n-j-Y");

$type = 'r';
$mtype = 'o';

// The math of the nearest location will now happen in m.hail.quote.php
$forwardstr .= "m.hail.quote.php?type=$type&mtype=$mtype&machid=$machid&start=$time&date=$date&lat=".$_POST['mylat']."&lon=".$_POST['mylon'];

header('Location: '.$forwardstr);


?>
