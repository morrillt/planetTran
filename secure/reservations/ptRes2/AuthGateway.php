<div class="popover_content">
<?php
//ini_set('display_errors', '0');
//include_once('lib/Template2.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib/CmnFns.class.php');
include_once('lib/Auth.class.php');
include_once('lib/Account.class.php');
include_once('AuthNet.php');
include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
$tool = new Tools();

$paymentProfileId = $_GET['paymentProfileId'];
$memberid = $_GET['memberid'];

//get metadata about card if it exists

$dbe = new DBEngine();

$query = "select customerProfileId, description, isDefault, lastFour from paymentProfiles where memberid = '" . $memberid . "' and paymentProfileId = '" . $paymentProfileId . "'";

$result = $dbe->db->query($query);

$dbe->check_for_error($result);

$rs = $result->fetchRow();

$customerProfileId = $rs['customerProfileId'];
$description = $rs['description'];
$isDefault = $rs['isDefault'];
$lastFour = $rs['lastFour'];

$mode = $_GET['mode'];

if (!Auth::is_logged_in()) 
    Auth::print_login_msg();

//$t = new Template('Payment Profile');
//$t->printHTMLHeader();
//customHead();

$currentVals = array();

if("delete" != $mode) {
	if("edit" == $mode) {
		$vals = getPaymentProfileData($memberid, $customerProfileId, $paymentProfileId);
		$vals['description'] = $description;
		$vals['isDefault'] = $isDefault;
//		echo "<h2 align=center>Modify Payment Profile:<br>$description*$lastFour</h2>";
		$paymentProfile = $dbe->getPaymentProfile($paymentProfileId);
	} else {
//		echo "<h1 align=center>Add Payment Profile</h1>";
		$paymentProfile = array();
	}
	
	$vals['memberid'] = $memberid;
	$vals['customerProfileId'] = $customerProfileId;
	$vals['customerPaymentProfileId'] = $paymentProfileId;
	$vals['mode'] = $mode;
		
	print_form($vals, $paymentProfile);
} else {
	echo "<h2 align=center>Are you sure you want to delete:<br>" . $description . "*" . $lastFour . "?</h2>";
	$action = $conf['app']['weburi'].'/AuthNet.php?js='.$_GET['js'];

	echo '<form name="auth" id="auth" action="'.$action.'" method="post">';
	echo '<input type="hidden" name="mode" value="'.$mode.'">';
	echo '<input type="hidden" name="memberid" value="'.$memberid.'">';
	echo '<input type="hidden" name="customerProfileId" value="'.$customerProfileId.'">';
	echo '<input type="hidden" name="customerPaymentProfileId" value="'.$paymentProfileId.'">';
//	echo '<div align="center"><input type="submit" name="submit" value="Yes"></div>';
	echo '<div align="center"><input type="hidden" name="submit" value="Yes"></div>';
	echo '</form>';

}


//$t->printHTMLFooter();

/**************************************************************/
function print_form($vals = array(), $paymentProfile = array()) {
	global $conf;
	$fields = get_fields();
	$action = $conf['app']['weburi'].'/AuthNet.php?js='.$_GET['js'];
//	printJS($fields);
	?>
	<style type="text/css">
	  td { width: 50%; }
	</style>
	<?php /* <form name="auth" id="auth" action="<?=$action?>" method="post" onSubmit="return checkVals();">*/ ?>

	<form name="auth" id="auth" action="<?=$action?>" method="post" >
	<input type="hidden" name="memberid" value="<?=$vals['memberid']?>">
	<input type="hidden" name="customerProfileId" value="<?=$vals['customerProfileId']?>">
	<input type="hidden" name="customerPaymentProfileId" value="<?=$vals['customerPaymentProfileId']?>">
	<input type="hidden" name="mode" value="<?=$vals['mode']?>">

	<table width="100%" cellspacing=0 cellpadding=3>
	<tr>
	  
	  <td rowspan="1000" valign="top">
	    <?php if(!$_GET['noedit']): ?>
		<h5>Existing cards</h5>
		<a class="popover-add" href="AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=add"> Add Card </a>
		<?php foreach(Account::getCreditCards() as $paymentId => $description): ?>
		  <p>
		    <?php echo $description ?>
		    <br>
		    <span class="options">
		      <a class="popover-edit" title="Edit Credit Card" href="AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=edit&paymentProfileId=<?php echo $paymentId ?>">Edit</a>
		      |
		      <a class="popover-delete" title="Delete Credit Card?" href="AuthGateway.php?js=select&memberid=<?php echo $_SESSION['currentID'] ?>&mode=delete&paymentProfileId=<?php echo $paymentId ?>">Delete</a>
		    </span>
		  </p>
		<?php endforeach; ?>
	    <?php endif ?>
	  </td>
	  <td colspan="2"><h5>Card data</h5></td>
	</tr>
	<?
	foreach ($fields as $k=>$v) {
		$required =  ($k != 'billCompany') ? " *" : '';
		echo "<tr><td>$v$required</td><td>";
		if ($k == 'billExpDate') {
			expDropdowns($paymentProfile);
		} else {
			echo "<input type=\"text\" name=\"$k\" value=". $vals[$k] . ">";
		}
		echo "</td></tr>\n";
	}
	?>
	<tr><td>Description</td><td><textarea name="description"><?=$vals['description']?></textarea></td></tr>
	<tr><td>&nbsp;</td><td>
  <input type="submit" value="Submit">
  </td></tr>
	</table></form>
	<?
}
function expDropdowns($p) {
	//CmnFns::Diagnose($p);
	global $tool;
	$months = range(1,12);
	$months = CmnFns::copy_vals_to_keys($months);
	$years = range(date("Y")-0, date("Y")+10);
	$years = CmnFns::copy_vals_to_keys($years);
	
	list($year, $month) = explode("-", $p['expdate']);
	//echo " " . $year . " " . $month;
	$tool->print_dropdown($months, $month, 'month', null, '', 'month');
	echo " ";
	$tool->print_dropdown($years, $year, 'year', null, '', 'year');
}
/*
function printJS($fields) {
	unset($fields['billCompany'], $fields['billExpDate']);
	//unset($fields['billAddress'], $fields['billState'], $fields['billCity'], $fields['billZip'], $fields['billCountry']);
	?>
	<script type="text/javascript">
	function checkVals() {
		var missinginfo = "";
		var fields = [
		<?

		$a = array();
		$i = 1;
		foreach ($fields as $k => $v) {
			echo "'$k'";

			if ($i < count($fields)) echo ',';
			echo "\n";
			
			$i++;
		}
		?>
		];


		for (i=0; i<fields.length; i++) {
			var cur = document.auth[fields[i]];
			if (cur.value == "")
				missinginfo = "Please fill in all fields marked with an asterisk (*).";
				//missinginfo += fields[i] + " is required.\n";

		}	

		if (missinginfo) {
			alert(missinginfo);
			return false;
		}

		var valid = "0123456789";
		var validexp = "0123456789/";	
		var cc = document.auth.billCardNumber.value;
		var m = document.auth.month;
		var month = m.options[m.selectedIndex].value;
		var y = document.auth.year;
		var year = y.options[y.selectedIndex].value;

		var ccmatch = /^XXXX\d{4}$/;

		if (cc.match(ccmatch))
		{
		} else {
			if (cc.length<15 || cc.length>16) 
			{
				missinginfo += "Credit card number needs to be 15 or 16 digits.\n";
			} else {
				for (var i=0; i < cc.length; i++) 
				{
					temp = "" + cc.substring(i, i+1);
					if (valid.indexOf(temp) == "-1") 
					{
						missinginfo += "Please enter a valid credit card.\n";
						break;
					}
				}
			}
		}

		var curDate = new Date();
		var curYear = curDate.getFullYear();
		var curMonth = curDate.getMonth() + 1;

		if (year < curYear || (year == curYear && month < curMonth)) {
			missinginfo += "Please enter a valid expiration date.\n";
		}
		
		if (missinginfo != "") {
			alert(missinginfo);
			return false;
		}
		return true;
	}

	</script>
	<?
}
function customHead() {
	global $conf;
	global $languages;
	global $lang;
	global $charset;
		
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<title>Payment Profile</title>
	<link rel="stylesheet"  type="text/css" href="css.css"  />
	<link rel="stylesheet"  type="text/css" href="css1.css"  />
	<script language="JavaScript" type="text/javascript" src="functions.js">
	</script>
	<script type="text/javascript">
	function refreshParent() { 
		//alert("window closing");
		window.opener.location.reload(); 
	}
	</script>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>
	<body onUnload="refreshParent();">
	<?
	}
*/
?>
</div>
