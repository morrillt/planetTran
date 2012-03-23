<?php

ini_set('display_errors', '0');

ini_set('track_errors', 1);

//require_once('dompdf-0.5.1/dompdf_config.inc.php');

include_once('lib/DBEngine.class.php');

$d = new DBEngine();


$resid = isset($_GET['resid']) ? $_GET['resid'] : '';

if (!$resid) die;

$res = $d->get_receipt_data($resid);

//CmnFns::diagnose($res);

// override info from trip_log

if ($_GET['cc']) $res['cc'] = $_GET['cc'];

if ($_GET['authcode']) $res['authorization'] = $_GET['authcode'];

if ($_GET['fare']) $res['total_fare'] = $_GET['fare'];

$fromLoc = $res['fromCity'].' '.$res['fromState'].' '.$res['fromZip'];

$toLoc = $res['toCity'].' '.$res['toState'].' '.$res['toZip'];



if (stripos($res['fromName'], 'logan') !== false)

	$res['fromAddress'] = 'BOS';

else if (stripos($res['toName'], 'logan') !== false)

	$res['toAddress'] = 'BOS';



if (strrchr($res['cc'], "+") !== false) {

	$ccinfo = explode("+", $res['cc']);

	$res['cc'] = $ccinfo[0];

}

$cardType = strlen($res['cc']) > 13 ? substr($res['cc'], 0, 2) : 0;

if ($cardType >= 34 && $cardType <= 37)

	$cardType = 'AMEX ';

else if ($cardType >= 40 && $cardType <= 49)

	$cardType = 'VISA ';

else if ($cardType >= 51 && $cardType <= 55)

	$cardType = 'MAST ';

else

	$cardType = '';

$last3 = 'X' . substr($res['cc'], -3);



if (stripos($res['email'], '@planettran.com') !== false)

	$res['email'] = '';

if ($res['authorization'])

	$res['authorization'] = str_replace('"', '', $res['authorization']);



$ptcheck = $res['group_name'] ? strtolower(substr($res['group_name'],0,2)) : '';

if ($ptcheck == 'pt' || !$ptcheck) 

	$groupname = '';

else 

	$groupname = $res['group_name'];





ob_start();

/* capture output starting here */

?>

<html><head>

<style type="text/css">

<!--

div.address {

	font-family: Helvetica;

	font-size: 10pt;

	text-align: center;

	color: gray;

	word-spacing: 0.5pt;

}

table {

	border: 1px solid black;

}

p.tableTitle {

	font-family: Helvetica;

	font-size: 11pt;

	font-weight: bold;

	color: black;

	text-align: left;

	word-spacing: 0.5pt;

	margin-top: 10px;

	margin-bottom: 0;

}

td.col1 {

	font-family: Helvetica;

	font-size: 10pt;

	font-weight: bold;

	border-color: gray;

	border-width: 0 1px 1px 0;

	border-style: solid;

    margin: 0;

}

td.lastcol1 {

	font-family: Helvetica;

	font-size: 10pt;

	font-weight: bold;

	border-color: gray;

	border-width: 0 1px 0 0;

	border-style: solid;

    margin: 0;

}

td.col2 {

	font-family: Helvetica;

	font-size: 10pt;

	border-color: gray;

	border-width: 0 0 1px 0;

	border-style: solid;

    margin: 0;

}

td.lastcol2 {

	font-family: Helvetica;

	font-size: 10pt;

	border: 0;

    margin: 0;

}

div.footer {

	text-align: right;

	font-family: Times New Roman;

	font-size: 10pt;

	color: gray;

	margin-top: 130px;

}



-->

</style>

</head>

<body>

<div class="address">

<img src="images/ptnewlogo.png"><br />

PlanetTran, LLC<br />

One Broadway, 14th Floor<br />

Cambridge, MA 02142<br />

1-888-PLNT-TRN<br />

billing@planettran.com<br />

beta.planettran.com<br />

</div>

<p class="tableTitle">CLIENT INFORMATION</p>

<table cellspacing=0 cellpadding=2 width="100%">

<tr>

	<td class="col1" width="30%">CLIENT NAME:</td>

	<td class="col2" width="70%"><?=$res['accountName']?></td>

</tr>

<tr>

	<td class="col1">COMPANY:</td>

	<td class="col2"><?=$groupname?></td>

</tr>

<tr>

	<td class="col1">EMAIL:</td>

	<td class="col2"><?=$res['email']?></td>

</tr>

<tr>

	<td class="lastcol1">PHONE:</td>

	<td class="lastcol2"><?=$res['phone']?></td>

</tr>

</table>

<p>&nbsp;</p>

<p class="tableTitle">TRIP DETAIL</p>

<table cellspacing=0 cellpadding=2 width="100%">

<tr>

	<td class="col1" width="30%">RESERVATION ID:</td>

	<td class="col2" width="70%"><?=strtoupper(substr($res['resid'], -6))?></td>

</tr>

<tr>

	<td class="col1">PASSENGER:</td>

	<td class="col2"><?=$res['paxname']?></td>

</tr>

<tr>

	<td class="lastcol1">TIME:</td>

	<td class="lastcol2"><?=CmnFns::formatTime($res['startTime'])?></td>

</tr>

<tr>

	<td class="lastcol1">DATE:</td>

	<td class="lastcol2"><?=CmnFns::formatDate($res['date'])?></td>

</tr>

<tr>

	<td class="lastcol1">PICK UP:</td>

	<td class="lastcol2"><?=$res['fromAddress']?></td>

</tr>

<tr>

	<td class="lastcol1">&nbsp;</td>

	<td class="lastcol2"><?=$fromLoc?></td>

</tr>

<tr>

	<td class="lastcol1">DROP OFF:</td>

	<td class="lastcol2"><?=$res['toAddress']?></td>

</tr>

<tr>

	<td class="lastcol1">&nbsp;</td>

	<td class="lastcol2"><?=$toLoc?></td>

</tr>

</table>



<p>&nbsp;</p>

<p class="tableTitle">RECORD OF PAYMENT</p>

<table cellspacing=0 cellpadding=2 width="100%">

<tr>

	<td class="col1" width="30%">FARE:</td>

	<td class="col2" width="70%">$<?=$res['total_fare']?>.00</td>

</tr>

<tr>

	<td class="col1">PAYMENT:</td>

	<td class="col2"><?=$cardType." ".$last3?></td>

</tr>

<tr>

	<td class="lastcol1">AUTHORIZATION</td>

	<td class="lastcol2"><?=$res['authorization']?></td>

</tr>

</table>

<div class="footer">

<img src="images/ptcar.png" border=0><br />

Your carbon footprint just got smaller.

</div>

</body>

</html>

<?



$html = ob_get_clean();


//if (Auth::isSuperAdmin()) {

//	CmnFns::diagnose($res);

//	die;

//}



require_once('dompdf-0.5.1/dompdf_config.inc.php');

$dompdf = new DOMPDF();

$dompdf->load_html($html);

$dompdf->render();



if (isset($_GET['output'])) {

	$outfile = $_GET['output'];

	$thepdf = $dompdf->output();

	file_put_contents($outfile, $thepdf);

} else

	$dompdf->stream($res['resid'].".pdf");

//if ($php_errormsg) {

if(false) {

	include('lib/PHPMailer.class.php');

	//$msg = print_r(error_get_last(), 1);

	//$msg = "$str\nLine $line in $file\nError number $num";



	$msg = $php_errormsg;	



	$p = new PHPMailer();

	$p->AddAddress('msobecky@gmail.com');

	$p->FromName = 'Receipt error';

	$p->Body = $msg;

	$p->Send();

	

}



