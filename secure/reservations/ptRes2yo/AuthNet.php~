<?php

include_once ("XML/vars.php");
include_once ("XML/util.php");
include_once ("lib/DBEngine.class.php");
include_once ("lib/CmnFns.class.php");

$month = $_POST['month'];

$_POST['billExpDate'] = $_POST['year'] . "-" . ($month < 10 ? "0" . $month : $month);

$defaultCard = ("on" == $_POST['isDefault'] ? 1 : 0);
	

if($defaultCard) {
	$dbe = new DBEngine();

	$query = "update paymentProfiles set isDefault = 0 where memberid = '" . $memberid . "'";
	

	$_POST['defaultCard'] = 1;
	$result = $dbe->db->query($query);
	
} else {
	$_POST['defaultCard'] = 0;
}

if($_POST['mode'] == "edit") {
	$response = updatePaymentProfile($_POST, $_POST['memberid']);

//	echo "<html><head></head><body>";
// 	echo '<div style="text-align: center; font-size: large; margin: 20px;">';
  echo '<div class="popover_content" style="text-align: center;">';
	if("Fail" == $response) {
		echo "Failed to edit profile.";
	} else {
		//echo $_POST['mode'] . "ed Payment Profile for " . $_POST['memberid'];
    renderPointerScript();
    if($_GET['js'] == 'select')
    {
      renderSelectScript();
    }
    elseif($_GET['js'] == 'div')
    {
      renderDivScript();
    }
		echo ucwords($_POST['mode']) . "ed Payment Profile.<br>";
	}
//	echo '<script language="JavaScript" type="text/javascript">' . "\n" .
//		'window.opener.document.location.href = window.opener.document.URL;' . "\n</script>";
//	echo '<a href="javascript: window.close();">Close</a>';
	echo '</div>';

//	echo "</body></html>";
} elseif($_POST['mode'] == "add") {
//	echo "<html><head></head><body>";
// 	echo '<div style="text-align: center; font-size: large; margin: 20px;">';
  echo '<div class="popover_content" style="text-align: center;">';
	$response = createPaymentProfile($_POST, $_POST['memberid']);
	if("Fail" == $response) {
		echo "Failed to add profile.<br>";
	} else {
		//echo $_POST['mode'] . "ed Payment Profile for " . $_POST['memberid'];
    renderPointerScript();
    if($_GET['js'] == 'select')
    {
      renderSelectScript();
    }
    elseif($_GET['js'] == 'div')
    {
      renderDivScript();
    }
		echo ucwords($_POST['mode']) . "ed Payment Profile.<br>";
	}
	
//	echo '<script language="JavaScript" type="text/javascript">' . "\n" .
//		'window.opener.document.location.href = window.opener.document.URL;' . "\n</script>";
//	echo '<a href="javascript: window.close();">Close</a>';
	echo '</div>';
//	echo "</body></html>";
	
} elseif($_POST['mode'] == "delete") {
	
	$memberid = $_POST['memberid'];
	$customerProfileId = $_POST['customerProfileId'];
	$customerPaymentProfileId = $_POST['customerPaymentProfileId'];
	
	$dbe = new DBEngine();

	deleteCustomerProfile($memberid, $customerProfileId, $customerPaymentProfileId);

	$query = "update paymentProfiles set status = 'inactive', isDefault = 0 where memberid = '" . $memberid . "' and customerProfileId = '" . $customerProfileId . "' and paymentProfileId = '" . $customerPaymentProfileId . "'";
	$result = $dbe->db->query($query);

  if($_GET['js'] == 'div')
  {
    renderDivScript();
  }
}

function get_fields() {
	/* This function returns a hash of key=> value pairs
	 * which are used to display the form for adding or editing a
	 * payment profile. The keys are the parameter names, the values
	 * are the display labels. */

	$return = array();
	
	$return['billFirstName'] = "Cardholder First Name";
	$return['billLastName'] = "Cardholder Last Name";
	$return['billCompany'] = "Company";
	$return['billAddress'] = "Address";
	$return['billCity'] = "City";
	$return['billState'] = "State";
	$return['billZip'] = "Zip Code";
	$return['billCountry'] = "Country";
	$return['billPhoneNumber'] = "Phone Number";
	$return['billCardNumber'] = "Card Number";
	$return['billExpDate'] = "Expiration Date";
	$return['billCardCode'] = "CVV code";
	
	return $return;

}

function createPaymentProfile($values, $memberid, $mode = 'testMode') {
	//select the payment profiles from the db for the customer
	//if none exist, call the function at authorize.net to create th
	//customer

	$dbe = new DBEngine();

	$query = "select * from paymentProfiles where memberid = '" . $memberid . "'";
	$result = $dbe->db->query($query);

	$profilesExist = $result->numRows();
	
	if(!$profilesExist){
	//then create a new paymentProfile at Authorize.net	
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<profile>" .
	"<merchantCustomerId>" . $memberid . "</merchantCustomerId>".
	"<paymentProfiles>".
	"<billTo>".
	 "<firstName>" . $values['billFirstName'] . "</firstName>".
	 "<lastName>" . $values['billLastName'] . "</lastName>".
	 "<company>" . $values['billCompany'] . "</company>".
	 "<address>" . $values['billAddress'] . "</address>".
	 "<city>" . $values['billCity'] . "</city>".
	 "<state>" . $values['billState'] . "</state>".
	 "<zip>" . $values['billZip'] . "</zip>".
	 "<country>" . $values['billCountry'] . "</country>".
	 "<phoneNumber>" . $values['billPhoneNumber'] . "</phoneNumber>".
	"</billTo>".
	"<payment>".
	 "<creditCard>".
	  "<cardNumber>" . $values['billCardNumber'] . "</cardNumber>".
	  "<expirationDate>" . $values['billExpDate'] . "</expirationDate>". 
	 "</creditCard>".
	"</payment>".
	"</paymentProfiles>".
	"</profile>".
	"<validationMode>$mode</validationMode>". // or testMode
	"</createCustomerProfileRequest>";

	$response = send_xml_request($content);
	$parsedresponse = parse_api_response($response);
	if("Ok" != $parsedresponse->messages->resultCode) {
		return "Fail";
	}
	$customerProfileId = $parsedresponse->customerProfileId;
	$customerPaymentProfileId = $parsedresponse->customerPaymentProfileIdList->numericString;	
	
	
	} else {

	$rs = $result->fetchRow();
	$customerProfileId = $rs['customerProfileId'];

	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"<paymentProfile>".
	"<billTo>".
	 "<firstName>" . $values['billFirstName'] . "</firstName>".
	 "<lastName>" . $values['billLastName'] . "</lastName>".
	 "<company>" . $values['billCompany'] . "</company>".
	 "<address>" . $values['billAddress'] . "</address>".
	 "<city>" . $values['billCity'] . "</city>".
	 "<state>" . $values['billState'] . "</state>".
	 "<zip>" . $values['billZip'] . "</zip>".
	 "<country>" . $values['billCountry'] . "</country>".
	 "<phoneNumber>" . $values['billPhoneNumber'] . "</phoneNumber>".
	"</billTo>".
	"<payment>".
	 "<creditCard>".
	  "<cardNumber>" . $values['billCardNumber'] . "</cardNumber>".
	  "<expirationDate>" . $values['billExpDate'] . "</expirationDate>". 
	 "</creditCard>".
	"</payment>".
	"</paymentProfile>".
	"<validationMode>$mode</validationMode>". // or testMode
	"</createCustomerPaymentProfileRequest>";

	$response = send_xml_request($content);
	$parsedresponse = parse_api_response($response);
	if("Ok" != $parsedresponse->messages->resultCode) {
		return "Fail";
	}
	
	$customerPaymentProfileId = $parsedresponse->customerPaymentProfileId;

	}

	$insertquery = "insert into paymentProfiles (memberid, 
							customerProfileId,
							paymentProfileId,
							status,
							description,
							isDefault,
							lastFour,
							expdate)
						values (?,?,?,?,?,?,?,?)";
	$lastFour = substr($values['billCardNumber'],-4);
	
	
	$insertValues = array($memberid, $customerProfileId, 
				$customerPaymentProfileId,
				'active', $values['description'],
				$values['defaultCard'], $lastFour,$values['billExpDate']); 
	$q = $dbe->db->prepare($insertquery);
	
	$newresult = $dbe->db->execute($q,$insertValues);
	$dbe->check_for_error($newresult);
	
	return $customerPaymentProfileId;

}

function updatePaymentProfile($values, $memberid, $mode = 'testMode') {

	$customerProfileId = $values['customerProfileId'];
	
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<updateCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"<paymentProfile>".
	"<billTo>".
	 "<firstName>" . $values['billFirstName'] . "</firstName>".
	 "<lastName>" . $values['billLastName'] . "</lastName>".
	 "<company>" . $values['billCompany'] . "</company>".
	 "<address>" . $values['billAddress'] . "</address>".
	 "<city>" . $values['billCity'] . "</city>".
	 "<state>" . $values['billState'] . "</state>".
	 "<zip>" . $values['billZip'] . "</zip>".
	 "<country>" . $values['billCountry'] . "</country>".
	 "<phoneNumber>" . $values['billPhoneNumber'] . "</phoneNumber>".
	"</billTo>".
	"<payment>".
	 "<creditCard>".
	  "<cardNumber>" . $values['billCardNumber'] . "</cardNumber>".
	  "<expirationDate>" . $values['billExpDate'] . "</expirationDate>". 
	 "</creditCard>".
	"</payment>".
	"<customerPaymentProfileId>".$values['customerPaymentProfileId'] .
	"</customerPaymentProfileId>".
	"</paymentProfile>".
	"<validationMode>$mode</validationMode>". // or testMode
	"</updateCustomerPaymentProfileRequest>";

	$response = send_xml_request($content);
	$parsedresponse = parse_api_response($response);
	
	//return without doing anything to the database if there is an error
	if("Ok" != $parsedresponse->messages->resultCode) {
		return "Fail";
	}
	$customerPaymentProfileId = $parsedresponse->customerPaymentProfileId;
	
	$dbe = new DBEngine();

	$lastFour = substr($values['billCardNumber'],-4);

	
	$expdate = $values['billExpDate'];
	$query = "update paymentProfiles set lastFour = '" . $lastFour . "', expdate = '" . $expdate . "', description = '" . $values['description'] . "', isDefault = '" . $values['defaultCard'] . "' where memberid = '" . $memberid . "' and customerProfileId = '" . $customerProfileId . "' and paymentProfileId = '" . $values['customerPaymentProfileId'] . "'";
	$result = $dbe->db->query($query);
	$dbe->check_for_error($result);	
	
	return $customerPaymentProfileId;

}

function deleteCustomerProfile($memberid, $customerProfileId, $paymentProfileId) {
	$dbe = new DBEngine();

	// FIXED SQL Injection possibility
	$memberid = mysql_real_escape_string($memberid);
	$customerProfileId = mysql_real_escape_string($customerProfileId);
	$paymentProfileId = mysql_real_escape_string($paymentProfileId);

	      $query = sprintf("select * from paymentProfiles where memberid = '%s' and customerProfileId = '%s' and paymentProfileId = '%s'",
	  $memberid,
	  $customerProfileId,
	  $paymentProfileId
	);
	
	$result = $dbe->db->query($query);

	$row = $result->fetchRow();	
	$customerProfileId = $row['customerProfileId'];
	
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<deleteCustomerProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"</deleteCustomerProfileRequest>";

	$response = send_xml_request($content);
	$parsedresponse = parse_api_response($response);
	$customerPaymentProfileId = $parsedresponse->customerPaymentProfileId;
	
	$query = sprintf("delete from paymentProfiles where memberid = '%s' and customerProfileId = '%s' and paymentProfileId = '%s'",
	  $memberid,
	  $customerProfileId,
	  $paymentProfileId
	);
	$dbe->db->query($query);
	
	return $customerPaymentProfileId;
}

function getPaymentProfileData($memberid, $customerProfileId, $customerPaymentProfileId) {

  //build xml to post
	$content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<getCustomerPaymentProfileRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"<customerPaymentProfileId>" . $customerPaymentProfileId . "</customerPaymentProfileId>".
	"</getCustomerPaymentProfileRequest>";

	$response = send_xml_request($content);
	
	$parsedresponse = parse_api_response($response);

	
	$return = array();
	
	$return['billFirstName']
		= (string)$parsedresponse->paymentProfile->billTo->firstName;
	$return['billLastName'] 
		= (string)$parsedresponse->paymentProfile->billTo->lastName;
	$return['billCompany'] 
		= (string)$parsedresponse->paymentProfile->billTo->company;
	$return['billAddress'] 
		= (string)$parsedresponse->paymentProfile->billTo->address;
	$return['billCity'] 
		= (string)$parsedresponse->paymentProfile->billTo->city;
	$return['billState'] 
		= (string)$parsedresponse->paymentProfile->billTo->state;
	$return['billZip'] 
		= (string)$parsedresponse->paymentProfile->billTo->zip;
	$return['billCountry'] 
		= (string)$parsedresponse->paymentProfile->billTo->country;
	$return['billPhoneNumber'] 
		= (string)$parsedresponse->paymentProfile->billTo->phoneNumber;
	$return['billCardNumber'] 
		= (string)$parsedresponse->paymentProfile->payment->creditCard->cardNumber;
	$return['billExpDate'] 
		= (string)$parsedresponse->paymentProfile->payment->creditCard->expirationDate;
	$return['billCardCode'] 
		= (string)$parsedresponse->paymentProfile->payment->creditCard->cardCode;
	
	return $return;
}

function submitAuthNetTransaction($paymentProfileId, $customerProfileId, $amount, $type) {

  $content =
	"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
	"<createCustomerProfileTransactionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
	MerchantAuthenticationBlock().
	"<transaction>" .
	"<" . $type . ">" .	
	"<amount>" . $amount . "</amount>" .
	"<customerProfileId>" . $customerProfileId . "</customerProfileId>".
	"<customerPaymentProfileId>". $paymentProfileId . "</customerPaymentProfileId>".
	"</" . $type . ">" .	
	"</transaction>".
	"</createCustomerProfileTransactionRequest>";

	$response = send_xml_request($content);
	
	$parsedresponse = parse_api_response($response);
	return $parsedresponse->directResponse;	
}

function renderPointerScript(){
    $pointerId = uniqid();
?>
<div id="pointer_id_<?php echo $pointerId ?>"></div>
<script type="text/javascript">
  $buttons = $("#pointer_id_<?php echo $pointerId ?>")
    .parents(".ui-dialog")
    .find(".ui-dialog-buttonpane button");
  $buttons.first().remove();
  $buttons.eq(1).text("Close");
</script>
<?php
}
function renderSelectScript(){
  require_once 'lib/Account.class.php';
?>
<script type="text/javascript">
  var selected = $("#payment_method").val();
  $("#payment_method").html('<?php echo Account::getCreditCardsOptions() ?>');
  $("#payment_method").val(selected);
</script>
<?php
}
function renderDivScript(){
  require_once 'lib/Account.class.php';
?>
<script type="text/javascript">
  $("#credit_cards_div").html('<?php echo Account::getCreditCardsDiv() ?>');
</script>
<?php
}
