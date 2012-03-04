<?php
include_once('lib/DBEngine.class.php');
include_once('lib/Receipt.class.php');
$d = new DBEngine();
$res = $d->get_receipt_data($_GET['resid']);
//print_r($res);

build_receipt($res);
function build_receipt($res) {
		$fromAddress = $res['fromAddress'].', '.$res['fromCity'].' '.$res['fromState'].' '.$res['fromZip'];
		$toAddress = $res['toAddress'].', '.$res['toCity'].' '.$res['toState'].' '.$res['toZip'];

	  	$receipt = new Receipt();
		$receipt->AliasNbPages();
		$receipt->AddPage();
		//date
		$data = "Reservation #: " . strtoupper(substr($res['resid'], -6));	
		$receipt->Cell(0,10,$data,0,1);
		//date
		$data = "Date: " . CmnFns::formatDate($res['date']);	
		$receipt->Cell(0,10,$data,0,1);
		//date
		$data = "Time: " . CmnFns::formatTime($res['startTime']);	
		$receipt->Cell(0,10,$data,0,1);
		//name
		$name = $res['paxname'] ? $res['paxname'] : $res['firstName']." ".$res['lastName'];
		$data = "Passenger Name: " . $name;	
		$receipt->Cell(0,10,$data,0,1);
		//from
		$data = "From: " . $fromAddress;	
		$receipt->Cell(0,10,$data,0,1);
		//to
		$data = "To: " . $toAddress;	
		$receipt->Cell(0,10,$data,0,1);
		//fare
		$data = "Fare: $" . $res['total_fare'];	
		$receipt->Cell(0,10,$data,0,1);
		//card
		$data = "Paid by Card: " . "X" . substr($res['cc'], 12, 4);	
		$receipt->Cell(0,10,$data,0,1);
		//card
		//$data = "Authorization: " . $authcode;	
		//$receipt->Cell(0,10,$data,0,1);
		$receipt->Output();

		//************************************
//		$filename = time();	
//		$receipt->Output("/home/planet/tmp/". $filename . "receipt.pdf");	
//		$mailer = new PHPMailer();
  //         	$mailer->AddCC('billing@planettran.com', 'Billing');
    //       	$mailer->AddAddress($res['ccEmail'], $res['ccName']);
//		$mailer->Subject = "PlanetTran Receipt: " . $res['ccName'] . "," . CmnFns::formatDate($res['ts']) . ", " . CmnFns::formatTime($res['startTime']); 
//		$mailer->FromName = "PlanetTran Billing"; 
  //         	$mailer->AddAttachment("/home/planet/tmp/" . $filename . "receipt.pdf");
   //      	$mailer->Send();
//		unlink("/home/planet/tmp/" . $filename . "receipt.pdf");	
//		echo 'Emailed Receipt.';
//		************************************
}
?>
