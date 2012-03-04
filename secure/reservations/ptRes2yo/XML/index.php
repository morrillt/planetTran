<html>
<body>
<?php
/*
D I S C L A I M E R                                                                                          
WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.                                                                                   
Authorize.Net provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.   
Authorize.Net owns and retains all right, title and interest in and to the Automated Recurring Billing intellectual property.
*/

$customerProfileId = NULL;
if (isset($_REQUEST['customerProfileId'])) {
	$customerProfileId = $_REQUEST['customerProfileId'];
}

$customerPaymentProfileId = NULL;
if (isset($_REQUEST['customerPaymentProfileId'])) {
	$customerPaymentProfileId = $_REQUEST['customerPaymentProfileId'];
}

$customerShippingAddressId = NULL;
if (isset($_REQUEST['customerShippingAddressId'])) {
	$customerShippingAddressId = $_REQUEST['customerShippingAddressId'];
}
?>

<form method=post action=profile_create.php>
<b>Create Customer Profile</b><br>
email <input type=text name=email value='test@example.com'><br>
<input type=submit name=submit value=submit>
</form>
<hr>

<form method=post action=payment_profile_create.php>
<b>Create Customer Payment Profile</b><br>
customerProfileId <input type=text name=customerProfileId value='<?php echo $customerProfileId; ?>'><br>
<input type=hidden name=customerShippingAddressId value='<?php echo $customerShippingAddressId; ?>'>
<input type=submit name=submit value=submit>
</form>
<hr>

<form method=post action=shipping_address_create.php>
<b>Create Shipping Address</b><br>
customerProfileId <input type=text name=customerProfileId value='<?php echo $customerProfileId; ?>'><br>
<input type=hidden name=customerPaymentProfileId value='<?php echo $customerPaymentProfileId; ?>'>
<input type=submit name=submit value=submit>
</form>
<hr>

<form method=post action=transaction_create.php>
<b>Create Transaction</b><br>
customerProfileId <input type=text name=customerProfileId value='<?php echo $customerProfileId; ?>'><br>
customerPaymentProfileId <input type=text name=customerPaymentProfileId value='<?php echo $customerPaymentProfileId; ?>'><br>
customerShippingAddressId <input type=text name=customerShippingAddressId value='<?php echo $customerShippingAddressId; ?>'><br>
amount <input type=text name=amount value='10.95'><br>
<input type=submit name=submit value=submit>
</form>
<hr>

<form method=post action=profile_delete.php>
<b>Delete Customer Profile</b><br>
customerProfileId <input type=text name=customerProfileId value='<?php echo $customerProfileId; ?>'><br>
<input type=submit name=submit value=submit>
</form>
<hr>

</body>
</html>
