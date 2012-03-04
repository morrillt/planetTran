<?
$dbc=mysql_connect('localhost','planet_schedul','schedule');
mysql_select_db('planet_reservations');
$newtime=($_POST[oldtime] + $_POST[changeby]);
$newend=($_POST[oldend] + $_POST[endchange]);
$updata=mysql_query("update driver_log set startTime='$newtime', endTime='$newend' where driverid='$_POST[driver]' and startTime='$_POST[oldtime]' and endTime='$_POST[oldend]' ");
header("Location: cale.php?driver=$_POST[driver]");

?>
