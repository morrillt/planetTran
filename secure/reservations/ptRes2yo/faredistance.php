<?php

include_once('lib/Template.class.php');
$t = new Template('Get Quote by distance');
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
else if (!isset($_SESSION['role']) || $_SESSION['role']!='m')
    Auth::doLogout();
$t->printHTMLHeader();
if (Auth::isBillingAdmin()) $t->linkbar();

echo '<div style="font-size: 12px; padding: 1em;">';
echo '<h3>Get Quote by distance</h3>';
printform();

$dis = CmnFns::getOrPost('dis');
$dis = escapeshellarg($dis);
$market = $_GET['coast'] == 'SFO' ? 'SFO' : 'BOS';
$apt = isset($_GET['apt']);
$discount = $_GET['discount'] ? $_GET['discount'] : 0;
$discount = $discount / 100;

exec("perl wrapper2.pl $dis $market", $a, $b);
$result = $a[0];
$result += $apt ? 3 : 0;
$result -= $result * $discount;
$fare = intval($result);


if ($result) $msg = "The estimated fare for that distance is \$$fare.";
else if (!$dis) $msg = '';
else $msg = "Something went wrong; unable to calculate fare.";


?>
<div align="center">
	<?=$msg?>	
</div>
<?

echo '</div>';
$t->printHTMLFooter();

/***********************************************************/
function printform() {
?>
	<form name="farebydistance" action="<?=$_SERVER['PHP_SELF']?>" method="get">
	<table width="100%" cellspacing=0 cellpadding=0 border=0>
	<tr>
		<td width="25%">Enter distance:</td>
		<td width="75%"><input type="text" name="dis" value=""></td>
	</tr>
	<tr>
		<td>Area</td>
		<td>
		<input type="radio" name="coast" value="BOS" checked>Boston
		<input type="radio" name="coast" value="SFO">San Francisco<br>
		</td>
	</tr>
	<tr>
		<td>To/from an airport</td>
		<td><input type="checkbox" name="apt"></td>
	</tr>
	<tr>
		<td>Discount (optional)</td>
		<td><input type="text" name="discount" value=""></td>
	</table>
	<input type="submit" value="Submit">
	</form>
	
<?
}
?>
