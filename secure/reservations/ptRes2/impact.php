<?php
include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
if (!Auth::is_logged_in()) 
    Auth::print_login_msg();
$t = new Template(translate('Impact | Reservations | PlanetTran'));
$d = new DBEngine();
$t->printHTMLHeader('silo_reservations sn2');
$t->printNavReservations();
$t->startMain();
//$t->printWelcome();

$id = isset($_GET['memberid'])?$_GET['memberid']:$_SESSION['sessionID'];
$mFares = get_member_fares($id);

if ($d->has_referrals($id))
	$rFares = $d->get_referred_fares($id);
else
	$rFares = array('total_fare' => 0, 'avg_fare' => 0);

// Convert to CO2
$mFares['total_fare'] = $d->coConvert($mFares['total_fare'], $mFares['avg_fare']);
$total = $mFares['total_fare'] + $rFares['total_fare'];
if ($gFares = get_group_fares()) {
	$gFares['total_fare'] = $d->coConvert($gFares['total_fare'], $gFares['avg_fare']);
	$total += $gFares['total_fare'];
}
$u = new User($_SESSION['sessionID']);
$name = $u->get_name();
?>

<h1>Impact</h1>
<h2>Impact for <?=$name?></h2>
<div class="basicText">
Using PlanetTran instead of regular taxi or car service is one of the
most effective actions you can take to fight global warming.  The
following is a summary of the greenhouse gas reduction (in lbs.) which
results from you and your network using PlanetTran.

</div>
<table>
  <tr>
    <td>Your impact (fewer lbs.):</td>
    <td><?= $mFares['total_fare'] ?></td>
  </tr>
  <tr>
    <td>Referral impact (fewer lbs.):</td>
    <td><?= $rFares['total_fare'] ?></td>
  </tr>
  <?
  if($gFares)
  {
    ?>
    <tr>
      <td>Group impact (fewer lbs.):</td>
      <td><?= $gFares['total_fare'] ?></td>
    </tr>
    <?
  }
  ?>
  <tr>
    <td><b>Total (fewer lbs.):</b></td>
    <td><b><?= $total ?></b></td>
  </tr>
</table>

<?
$t->endMain();
$t->printHTMLFooter();

/*****************************************************/
function get_member_fares($id) {
	$query = "select sum(t.total_fare) as total_fare,
		  avg(t.total_fare) as avg_fare
		 from reservations r left join trip_log t
		 on r.resid=t.resid
		 where r.memberid='$id'
		 and t.pay_status=25
		group by r.memberid";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);

	$return = array('total_fare' => $row['total_fare'],
			'avg_fare' => $row['avg_fare']);
	return $return;
}
function get_group_fares() {
	$gid = $_SESSION['curGroup'];
	if (!$gid) return false;
	$query = "select sum(t.total_fare) as total_fare,
		  avg(t.total_fare) as avg_fare
		 from (reservations r left join trip_log t
		 on r.resid=t.resid)
		 join login l on l.memberid=r.memberid
		 where l.groupid='$gid'
		 and (t.pay_status=25 or t.pay_status=23)
		group by l.groupid";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);

	$return = array('total_fare' => $row['total_fare'],
			'avg_fare' => $row['avg_fare']);
	return $return;
}
?>
