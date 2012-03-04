#!/usr/local/bin/php
<?php
include_once ("lib/DBEngine.class.php");
include_once ("lib/CmnFns.class.php");
include_once ("AuthNet.php");

//querey to get memberids for every login row with other not null
//$memberids = array('41b482f4d0115','41b52a95d999c','41b5bb07bd06d');
//$memberids = array('41b482f4d0115');

$dbe = new DBEngine();

//$query = "select * from login where memberid = 'gzm41f91b9fbe6f3'";
$query = "select * from login where other like '%+%' and memberid not in (select memberid from paymentProfiles)";

$result = $dbe->db->query($query);

while($rs = $result->fetchRow()) {
	$mid = $rs['memberid'];
	
	$fields = get_fields();

	$fields['billFirstName'] = $rs['fname'];		
	$fields['billLastName'] = $rs['lname'];		

	$rawCard = preg_replace('/\s/', '', $rs['other']);	
	$cardDets = explode("+", $rawCard);

	$fields['billCardNumber'] = $cardDets[0];

	$dateDets = explode("/", $cardDets[1]);
	$expYear = $dateDets[1];
	$expMonth = $dateDets[0];
	
	if(strlen($expYear) == 2) {
		$expYear = "20" . $dateDets[1];
	} 

	if($expYear < 2010 or ($expYear == 2010 and $expMonth < 12) or preg_match('/\D/',$cardDets[0])) {
	//	echo "Skipping\n";
		continue;
	}

	$fields['billExpDate'] = $expYear . "-" . $dateDets[0];
	$fields['billCardCode'] = $cardDets[2];
	
	$fields['defaultCard'] = 0;
	$fields['billCompany'] = '';
	$fields['billAddress'] = '';
	$fields['billCity'] = '';
	$fields['billState'] = '';
	$fields['billZip'] = '';
	$fields['billCountry'] = '';
	$fields['billPhoneNumber'] = '';
	$fields['description'] = 'ProfileCard';

	//$output = deleteCustomerProfile($mid);
	$output = createPaymentProfile($fields, $mid);
	echo $rs['fname'] . " " . $rs['lname'] . "\n";
	
	echo $output;	
	//CmnFns::Diagnose($fields);
}
?>
