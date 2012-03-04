<html>
<body>
<?php

/*
D I S C L A I M E R  
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK. 
Authorize.Net provphpides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.
Authorize.Net owns and retains all right, title and interest in and to the Automated Recurring Billing intellectual property.
*/

include_once ("vars.php");
include_once ("util.php");

echo "Delete customerProfileId <b>"
	. htmlspecialchars($_POST["customerProfileId"])
	. "</b>...<br><br>";

//build xml to post
$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<deleteCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $_POST["customerProfileId"] . "</customerProfileId>".
	"</deleteCustomerProfileRequest>";

echo "Raw request: " . htmlspecialchars($content) . "<br><br>";
$response = send_xml_request($content);
echo "Raw response: " . htmlspecialchars($response) . "<br><br>";
$parsedresponse = parse_api_response($response);
if ("Ok" == $parsedresponse->messages->resultCode) {
	echo "customerProfileId <b>"
		. htmlspecialchars($_POST["customerProfileId"])
		. "</b> was successfully deleted.<br><br>";
}

echo "<br><a href=index.php?customerProfileId="
	. urlencode($_POST["customerProfileId"])
	. ">Continue</a><br>";
?>
</body>
</html>
