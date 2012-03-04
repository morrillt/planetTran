<?php

include_once ("XML/vars.php");
include_once ("XML/util.php");
include_once ("AuthNet.php");
include_once ("lib/CmnFns.class.php");
?>
<html>
<body>
<?
echo "Edit payment profile for customerProfileId and customerPaymentProfileId<b>"
	. htmlspecialchars($_POST["memberId"])
	. " and "
	. htmlspecialchars($_POST["customerProfileId"])
	. " and "
	. htmlspecialchars($_POST["customerPaymentProfileId"])
	. "</b>...<br><br>";


	$response = getPaymentProfileData($_POST["memberId"], $_POST["customerProfileId"], $_POST["customerPaymentProfileId"]);

echo "<form method=post action=payment_profile_update.php>";
echo "<input type=text name=memberId value=" . $_POST['memberId']."><br>";
echo "<input type=text name=customerProfileId value=".$_POST['customerProfileId']."><br>";
echo "<input type=text name=customerPaymentProfileId value=".$_POST['customerPaymentProfileId']."><br>";
foreach($response as $k=>$v) {
	echo "<input type=text name=$k value=$v><br>";
}
echo "<input type=submit value=submit name=submit>";


?>
</body>
</html>
