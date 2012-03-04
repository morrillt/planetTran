<?php

include_once ("XML/vars.php");
include_once ("XML/util.php");
include_once ("AuthNet.php");

echo "<html><body>";
echo "Updated Payment Profile for " . $_POST['memberId'];

	$response = updatePaymentProfile($_POST, $_POST['memberId']);
	echo "Raw request: " . htmlspecialchars($response) . "<br><br>";

	echo $response;
echo "</body></html>";
?>
