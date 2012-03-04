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

$customerShippingAddressId = NULL;
if (isset($_REQUEST['customerShippingAddressId'])) {
	$customerShippingAddressId = $_REQUEST['customerShippingAddressId'];
}

echo "Create payment profile for customerProfileId <b>"
	. htmlspecialchars($_POST["customerProfileId"])
	. "</b>...<br><br>";

//build xml to post
$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $_POST["customerProfileId"] . "</customerProfileId>".
	"<paymentProfile>".
	"<billTo>".
	 "<firstName>John</firstName>".
	 "<lastName>Doe</lastName>".
	 "<phoneNumber>000-000-0000</phoneNumber>".
	"</billTo>".
	"<payment>".
	 "<creditCard>".
	  "<cardNumber>4111111111111111</cardNumber>".
	  "<expirationDate>2020-11</expirationDate>". // required format for API is YYYY-MM
	 "</creditCard>".
	"</payment>".
	"</paymentProfile>".
	"<validationMode>liveMode</validationMode>". // or testMode
	"</createCustomerPaymentProfileRequest>";

echo "Raw request: " . htmlspecialchars($content) . "<br><br>";
$response = send_xml_request($content);
echo "Raw response: " . htmlspecialchars($response) . "<br><br>";
$parsedresponse = parse_api_response($response);
if ("Ok" == $parsedresponse->messages->resultCode) {
	echo "customerPaymentProfileId <b>"
		. htmlspecialchars($parsedresponse->customerPaymentProfileId)
		. "</b> was successfully created for customerProfileId <b>"
		. htmlspecialchars($_POST["customerProfileId"])
		. "</b>.<br><br>";
}

echo "<br><a href=index.php?customerProfileId=" 
	. urlencode($_POST["customerProfileId"])
	. "&customerPaymentProfileId="
	. urlencode($parsedresponse->customerPaymentProfileId)
	. "&customerShippingAddressId="
	. urlencode($customerShippingAddressId)
	. ">Continue</a><br>";
?>
</body>
</html>
