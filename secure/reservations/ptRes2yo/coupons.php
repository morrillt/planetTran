<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
include_once('lib.php');
$t = new Template('Coupons');
$d = new DBEngine();
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
else if (!isset($_SESSION['role']) || $_SESSION['role']!='m')
    Auth::doLogout();
$t->printHTMLHeader();
if (Auth::isBillingAdmin()) $t->linkbar();
echo '<div style="font-size: 12px;">';

if ($_POST['create']) {
	$err = create_coupon($d);
	if ($err) echo '<div style="text-align: center; font-size: large; color: red;">'.$err.'</div>';
	else echo '<b style="text-align: center; font-size: large;">Coupon created.</b>';
}

coupon_form();
show_coupons();
echo '</div>';

$t->printHTMLFooter();

function show_coupons() {
	$checked = $_GET['showExpired'] == 'on' ? ' checked' : '';
	?>
	<form name="showExp" action="<?=$_SERVER['PHP_SELF']?>" method="get">
	<input type="checkbox" name="showExpired" onClick="document.showExp.submit();"<?=$checked?>> Show expired coupons
	</form>
	<table width="100%" cellspacing=3 cellpadding=3>
	<tr style="font-weight: bold;">
		<td>Code</td>
		<td width="25%">Description</td>
		<td>Amount</td>
		<td>Type</td>
		<td>Begins</td>
		<td>Expires</td>
		<td>Per-customer<br>uses</td>
		<td>Global<br>max uses</td>
		<td>Allowed</td>
	</tr>
	<?
	
	$coupons = get_coupons();
	for ($i=0; $coupons[$i]; $i++) {
		$cur = $coupons[$i];
		$code = $cur['coupon_code'];
		$amount = $cur['amount'];
		$type = $cur['type'];
		$expires = $cur['expires'];
		$recur = $cur['recurrence'];
		$stamp = $cur['tstamp'];
		$bstamp = $cur['bstamp'];
		$begins = $cur['begins'];
		$max = $cur['max_uses'];
		$description = $cur['description'];
		if (!$stamp) $expires = 'Never';
		if (!$bstamp) $begins = ' - ';
		$allowed = $cur['allowed'];

		if ($type == 'p') $amount .= '%';
		else $amount = '$'.$amount;

		$bgcolor = $i % 2 ? "FFFFFF" : "EEEEEE";
		
		echo "<tr style = \"background-color: #$bgcolor\" alt=
\"test\">";
		echo "<td>$code</td>";
		echo "<td>$description</td>";
		echo "<td>$amount</td>";
		echo "<td>$type</td>";
		echo "<td>$begins</td>";
		echo "<td>$expires</td>";
		echo "<td>$recur</td>";
		echo "<td>$max</td>";
		echo "<td>$allowed</td>";
		echo "</tr>\n";
	}

	echo '</table>';
}
function coupon_form() {
	$month = range(1,12);
	$year = range(2009,2020);
	$day = range(1,31);
	?>
	<div style="text-align: center;">
	<h3>Create Coupon</h3>
	<form name="newcoupon" method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<table width="100%" cellspacing=5 cellpadding=0 border=0>
	<tr>
		<td width="50%" align="right">Coupon Code</td>
		<td width="50%" align="left">
		<input type="text" name="coupon">
		</td>
	</tr>
	<tr>
		<td align="right">Amount</td>
		<td align="left">
		<input type="text" name="amount">
		</td>
	</tr>
	<tr>
		<td align="right">Type</td>
		<td align="left">
			<select name="type">
			<option value="p">Percentage</option>
			<option value="d">Dollars</option>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">Begins</td>
		<td align="left">
			<select name="bmonth">
			<option value=""></option>
	<?
		foreach ($month as $v) {
			echo "<option value=\"$v\">$v</option>";
		}

		echo '</select>';
		echo '<select name="bday">';
		echo '<option value=""></option>';
		foreach ($day as $v) {
			echo "<option value=\"$v\">$v</option>";
		}

		echo '</select>';
		echo '<select name="byear">';
		echo '<option value=""></option>';
		foreach ($year as $v) {
			echo "<option value=\"$v\">$v</option>";
		}
		echo '</select>';

	?>
		<input type="checkbox" name="noBegin"> Starts now (no start date restriction)
		</td>
	</tr>
	<tr>
		<td align="right">Expires</td>
		<td align="left">
			<select name="month">
			<option value=""></option>
	<?
		foreach ($month as $v) {
			echo "<option value=\"$v\">$v</option>";
		}

		echo '</select>';
		echo '<select name="day">';
		echo '<option value=""></option>';
		foreach ($day as $v) {
			echo "<option value=\"$v\">$v</option>";
		}

		echo '</select>';
		echo '<select name="year">';
		echo '<option value=""></option>';
		foreach ($year as $v) {
			echo "<option value=\"$v\">$v</option>";
		}
		echo '</select>';

	?>
		<input type="checkbox" name="noExpiration"> Never expires
		</td>
	</tr>
	<tr>
		<td align="right">Number of uses per customer</td>
		<td align="left"><input type="textbox" name="recurrence" size=1 value="1">
		</td>
	</tr>
	<tr>
		<td align="right">
		<b>Global</b> max number of uses<br>
		*the coupon may only be used this many times total	
		</td>
		<td align="left"><input type="textbox" name="global_uses" size=1 value="">
		<input type="checkbox" name="noglobal"> No global usage limit
		</td>
	</tr>
	<tr>
		<td align="right">Allowed uses</td>
		<td align="left">
		<select name="allowed">
		<option value="all">All</option>
		<option value="airport">Airport</option>
		<option value="p2p">Point to point</option>
		<option value="hourly">Hourly</option>
		</select>
		</td>
	</tr>
	<tr>
		<td align="right">Description (for internal use only)</td>
		<td align="left">
		<textarea name="description"></textarea>
		</td>
	</tr>
	<tr>
		<td align="right">Customer-facing description<br>(will appear on customer-visible coupon page)</td>
		<td align="left">
		<textarea name="cust_description"></textarea>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="left"><input type="submit" value="Submit"></td>
	</tr>
	</table>
		<input type="hidden" name="create" value="1">
	</form>
	</div>
	<?
}

// return array of coupons
function get_coupons() {
	$where = "where expires >= CURDATE() or expires='0000-00-00'";
	if ($_GET['showExpired'] == "on") $where = '';

	$query = "select *, unix_timestamp(expires) as tstamp,
		  unix_timestamp(begins) as bstamp
		  from coupon_codes
		  $where
		  order by coupon_code";
	$qresult = mysql_query($query);
	$return = array();
	while ($row = mysql_fetch_assoc($qresult))
		$return[] = $row;
	return $return;
}
function create_coupon(&$d) {
	$coupon_code 	= $_POST['coupon'];
	$amount 	= $_POST['amount'];
	$type 		= $_POST['type'];
	$noexpire 	= isset($_POST['noExpiration']);
	$recur		= $_POST['recurrence'];
	$nobegin	= isset($_POST['noBegin']);
	$allowed	= $_POST['allowed'];
	$desc		= $_POST['description'] ? $_POST['description'] : null;
	$cust_desc	= $_POST['cust_description'] ? $_POST['cust_description'] : null;
	if (!$_POST['global_uses'] || $_POST['noglobal']) $global = null;
	else $global = $_POST['global_uses'];

	preg_match("/[^-_a-zA-Z0-9]/", $coupon_code, $matches);
	if ($matches[0]) return "Coupon codes may contain only letters, numbers, underscores, or hyphens (-).";

	if ($noexpire) {
		$month = $day = '00';
		$year = '0000';
	} else {
		$month = $_POST['month'];
		$day = $_POST['day'];
		$year = $_POST['year'];
	}
	$date = "$year-$month-$day";

	if ($nobegin) {
		$bmonth = $bday = '00';
		$byear = '0000';
	} else {
		$bmonth = $_POST['bmonth'];
		$bday = $_POST['bday'];
		$byear = $_POST['byear'];
	}
	$bdate = "$byear-$bmonth-$bday";

	$dstamp = mktime(0,0,0,$month, $day, $year);
	$bstamp = mktime(0,0,0,$bmonth, $bday, $byear);

	if (!$coupon_code || !$recur || !$month || !$day || !$year || !$bday || !$bmonth || !$byear || !$allowed) {
		return 'All fields are required. If no expiration, select "Never expires". If no begin date, select "Starts now (no begin date)".'; 
	} else if (!is_numeric($amount)) {
		return 'The amount must be an integer.';
	} else if ($date < $bdate) {
		return "That coupon would expire before it begins (begins $bdate, expires $date).";
	}

	// Check for duplicates
	$query = "select coupon_code from coupon_codes where coupon_code=?";
	$vals = array($coupon_code);
	$res = $d->db->getOne($query, $vals);
	if ($res) {
		return "That coupon code, $res, already exists.";
	}

	echo $global;
	$query = "insert into coupon_codes (coupon_code, amount, type, expires, recurrence, begins, description, allowed, Coupon_Desc, max_uses)
		  values (?,?,?,?,?,?,?,?,?,?)";
	$vals = array($coupon_code, $amount, $type, $date, $recur, $bdate, $desc, $allowed, $cust_desc, $global);
	$q = $d->db->prepare($query);
	$result = $d->db->execute($q, $vals);
	$d->check_for_error($result);

	return 0;
}
?>
