<?php
include_once('lib/Reservation.class.php');
$resid = $_GET['resid'];
$time = $_GET['time'];
$r = new Reservation($resid);
$r->load_by_id();
$r->date = mktime(0,0,0,date("m"),date("d"),date("Y"));
$r->start = $_GET['time'];
echo $r->check_times();
?>
