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

echo "Create transaction for customerProfileId <b>"
	. htmlspecialchars($_POST["customerProfileId"])
	. "</b>, customerPaymentProfileId <b>"
	. htmlspecialchars($_POST["customerPaymentProfileId"])
	. "</b>, customerShippingAddressId <b>"
	. htmlspecialchars($_POST["customerShippingAddressId"])
	. "</b>...<br><br>";

//build xml to post
$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<transaction>".
	"<profileTransAuthOnly>".
	"<amount>" . $_POST["amount"] . "</amount>". // should include tax, shipping, and everything.
	"<shipping>".
	"<amount>0.00</amount>".
	"<name>Free Shipping</name>".
	"<description>Free UPS Ground shipping. Ships in 5-10 days.</description>".
	"</shipping>".
	"<lineItems>".
	"<itemId>123456</itemId>".
	"<name>name of item sold</name>".
	"<description>Description of item sold</description>".
	"<quantity>1</quantity>".
	"<unitPrice>" . ($_POST["amount"] - 1.00) . "</unitPrice>".
	"<taxable>false</taxable>".
	"</lineItems>".
	"<lineItems>".
	"<itemId>456789</itemId>".
	"<name>name of item sold</name>".
	"<description>Description of item sold</description>".
	"<quantity>1</quantity>".
	"<unitPrice>1.00</unitPrice>".
	"<taxable>false</taxable>".
	"</lineItems>".
	"<customerProfileId>" . $_POST["customerProfileId"] . "</customerProfileId>".
	"<customerPaymentProfileId>" . $_POST["customerPaymentProfileId"] . "</customerPaymentProfileId>".
	"<customerShippingAddressId>" . $_POST["customerShippingAddressId"] . "</customerShippingAddressId>".
	"<order>".
	"<invoiceNumber>INV12345</invoiceNumber>".
	"</order>".
	"</profileTransAuthOnly>".
	"</transaction>".
	"</createCustomerProfileTransactionRequest>";

echo "Raw request: " . htmlspecialchars($content) . "<br><br>";
$response = send_xml_request($content);
echo "Raw response: " . htmlspecialchars($response) . "<br><br>";
$parsedresponse = parse_api_response($response);
if ("Ok" == $parsedresponse->messages->resultCode) {
	echo "A transaction was successfully created for customerProfileId <b>"
		. htmlspecialchars($_POST["customerProfileId"])
		. "</b>.<br><br>";
}
if (isset($parsedresponse->directResponse)) {
	echo "direct response: <br>"
		. htmlspecialchars($parsedresponse->directResponse)
		. "<br><br>";
		
	$directResponseFields = explode(",", $parsedresponse->directResponse);
	$responseCode = $directResponseFields[0]; // 1 = Approved 2 = Declined 3 = Error
	$responseReasonCode = $directResponseFields[2]; // See http://www.authorize.net/support/AIM_guide.pdf
	$responseReasonText = $directResponseFields[3];
	$approvalCode = $directResponseFields[4]; // Authorization code
	$transId = $directResponseFields[6];
	
	if ("1" == $responseCode) echo "The transaction was successful.<br>";
	else if ("2" == $responseCode) echo "The transaction was declined.<br>";
	else echo "The transaction resulted in an error.<br>";
	
	echo "responseReasonCode = " . htmlspecialchars($responseReasonCode) . "<br>";
	echo "responseReasonText = " . htmlspecialchars($responseReasonText) . "<br>";
	echo "approvalCode = " . htmlspecialchars($approvalCode) . "<br>";
	echo "transId = " . htmlspecialchars($transId) . "<br>";
}

echo "<br><a href=index.php?customerProfileId=" 
	. urlencode($_POST["customerProfileId"])
	. "&customerPaymentProfileId="
	. urlencode($_POST["customerPaymentProfileId"])
	. "&customerShippingAddressId="
	. urlencode($_POST["customerShippingAddressId"])
	. ">Continue</a><br>";
?>
</body>
</html>
