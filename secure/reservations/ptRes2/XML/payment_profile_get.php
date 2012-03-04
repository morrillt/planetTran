<html>
<body>
<?php

include_once ("vars.php");
include_once ("util.php");
include_once ("AuthNet.php");
include_once ("CmnFns.class.php");

echo "Got payment profile for customerProfileId and customerPaymentProfileId<b>"
	. htmlspecialchars($_POST["customerProfileId"])
	. " and "
	. htmlspecialchars($_POST["customerPaymentProfileId"])
	. "</b>...<br><br>";


	echo "Raw request: " . htmlspecialchars($content) . "<br><br>";
	$response = getPaymentProfileData($_POST["memberId2"], $_POST["customerProfileId"], $_POST["customerPaymentProfileId"]);

CmnFns::diagnose($response);

?>
</body>
</html>
