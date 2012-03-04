<?php
include_once ("../lib/DBEngine.class.php");

echo "<html>Making db connection...<br>";
$dbe = new DBEngine();
$query = "select * from paymentProfiles";

$result = $dbe->db->query($query);

echo "number of rows: " . $result->numRows() . "<br>";

echo "Got it!</html>";

?>
