<?php

include_once ("vars.php");
include_once ("util.php");
include_once ("../AuthNet.php");
include_once ("../lib/DBEngine.class.php");

echo "<html><body>";
echo "Created Payment Profile for " . $_POST['memberId'];

	$response = createPaymentProfile($dbe, $_POST, $_POST['memberId']);
	echo "Raw request: " . htmlspecialchars($response) . "<br><br>";

	echo $response;
echo "</body></html>";
?>
