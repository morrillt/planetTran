<?php

include_once ("XML/vars.php");
include_once ("XML/util.php");
include_once ("AuthNet.php");

echo "<html><body>";
echo "Created Payment Profile for " . $_POST['memberId'];

	$response = createPaymentProfile($_POST, $_POST['memberId']);
	echo "Raw request: " . htmlspecialchars($response) . "<br><br>";

	echo $response;
echo "</body></html>";
?>
