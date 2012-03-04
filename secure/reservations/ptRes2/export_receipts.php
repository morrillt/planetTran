<?php
//ini_set('display_errors', 0);
include_once('lib/DBEngine.class.php');
$d = new DBEngine();

$res = $d->get_user_receipts($_SESSION['currentID'], 'ALL');
$colnames = "Reservation #\tDate\tFare\tName\tFrom\tTo\tTime";
//for($i=0;$i<$count;$i++){
//	$colnames .= mysql_field_name($qresult, $i) . "\t";
//}
$data = '';
for ($i=0; $res[$i]; $i++) {
	$row = $res[$i];
	$line = '';
	foreach ($row as $k => $v) {
		switch ($k) {
			case 'date':
				$v = CmnFns::formatDate($v);
				break;
			case 'resid':
				$v = strtoupper(substr($v, -6));
				break;
			case 'firstName':
				$v = $row['firstName']." ".$row['lastName'];
				break;
			case 'lastName':
				continue 2;
				break;
			case 'fromLocationName':
				break;
			case 'toLocationName':
				break;
			case 'pickupTime':
				$v = CmnFns::formatTime($v);
				break;
			case 'total_fare':
				$v = '$'.$v;
				break;
			default:
				continue 2;
		}
		if(!isset($v) || $v == "")
			$v = "\t";
		else {
			$v = str_replace('"', '""', $v);
			$v = '"'.$v.'"'."\t";
		}
		$line .= $v;
	}
	
	$data .= trim($line) . "\n";
}
$data = str_replace("\r", "", $data);

////// Stream file //////
header("Pragma: public");
header("Expires: 0");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=planettran_receipts.xls");
print "$colnames\n$data";
?>
