#!/usr/local/bin/php
<?php
include_once ("lib/CmnFns.class.php");
include_once ("AuthNet.php");
include_once ("lib/Transponet.class.php");

$t = new Transponet();

$data = $t->get_test_post_info();
$defaultuser['groupid'] = 2;

$userdata['fname'] = $data['Passenger_FirstName'];
$userdata['lname'] = $data['Passenger_LastName'];
$userdata['phone'] = $data['Passenger_CellPhone'];
$userdata['emailaddress'] = $data['Passenger_EmailAddress'];
$userdata['pnr'] = $data['Passenger_PNR'];

$pid = 'PONBSB';

//$output = $t->insert_individual_user($pid, 'glb46560d90b1e46', 2);
//$output = $t->get_user_from_pid($pid, 2);
$output = $t->get_individual_user($pid, 2, $userdata);

CmnFns::Diagnose($output);

?>
