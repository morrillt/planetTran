#!/usr/local/bin/php
<?php
include_once ("lib/DBEngine.class.php");
include_once ("lib/CmnFns.class.php");
include_once ("AuthNet.php");

CmnFns::Diagnose($args);
	
$ppid = $argv[1];
$amount = $argv[2];
$type = $argv[3];


$dbe = new DBEngine();

$query = "select * from paymentProfiles where paymentProfileId ='" . $ppid . "'";

echo "Query is:  " . $query . "\n";
$result = $dbe->db->query($query);

$rs = $result->fetchRow();
$mid = $rs['memberid'];
$cpid = $rs['customerProfileId'];
$ppid = $rs['paymentProfileId'];	

$output = submitAuthNetTransaction($ppid, $cpid, $amount, $type);
CmnFns::Diagnose($output);	

?>
