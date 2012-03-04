<?php
//echo date("m/d/Y");
$go = "https://secure.planettran.com/reservations/ptRes2/submitTransaction.php";
$a = array(
    'transactionId' => '5198',
    'txnDateTime' => 'Tue Aug 14 00:00:00 EDT 2007',
    'txnSentBy' => 'SATU8000',
    'txnSentTo' => 'MEEA0103',
    'txnTypeCode' => 'NR',
    'tNResNo' => time(),
    'bookerMemberCode' => 'SATU8000',
    'bookerResNo' => '44500',
    'providerMemberCode' => 'MEEA0103',
    'providerResNo' => 'foo',
    'Accounting_CorporateID' => 'REARDENSMB',
    'Accounting_UDF_Desc_3' => 'CostCenter',
    'Accounting_UDF_Value_3' => '1492',
    'Accounting_UDF_Value' => '|||||',
    'Pickup_Address_City' => 'Boston',
    'Pickup_Address_State' => 'MA',
    'Pickup_AsDirected' => 'N',
    'Pickup_Flight_AirlineCode' => 'AA',
    'Pickup_Flight_AirportCode' => 'BOS',
    'Pickup_Flight_ArriveAheadMins' => '0',
    'Pickup_Flight_DepartureTime' => '17:33',
    'Pickup_Flight_DestAirport' => 'HNL',
    'Pickup_Flight_FlightNumber' => '222',
    'Pickup_Flight_TypeCode' => 'D',
    'Pickup_LocationTypeCode' => 'A',
    'Pickup_Train_CarrierCode' => 'AA',
    'Pickup_Train_DestStation' => 'HNL',
    'Pickup_Train_StationCode' => 'EWR',
    'Pickup_Train_StopDateTime' => '17:33',
    'Pickup_Train_TrainNumber' => '222',
    'Passenger_DayPhone' => '860-873-8348',
    'Passenger_EmailAddress' => 'msobecky@gmail.com',
    'Passenger_EveningPhone' => '860-873-8348',
    'Passenger_FirstName' => 'Reservation',
    'Passenger_LastName' => 'TestQuote',
    'Payment_CC_ExpDate' => '01/31/2010',
    'Payment_CC_Number' => '5405540554055405',
    'Payment_Method' => 'MC',
    'Dropoff_Address_City' => 'Cambridge',
    'Dropoff_Address_CountryCode' => 'US',
    'Dropoff_Address_PhoneNo' => '914-944-9292',
    'Dropoff_Address_PostalCode' => '02139',
    'Dropoff_Address_State' => 'MA',
    'Dropoff_Address_StreetName' => 'Broadway',
    'Dropoff_Address_StreetNo' => '1',

    'Pickup_DateTime' => '9/20/2009 20:45',

    'Dropoff_LocationName' => 'MattTest',
    'Dropoff_LocationTypeCode' => 'O',
    'Ride_EstimatedMileage' => '42.51',
    'Ride_MileageType' => 'M',
    'Ride_NumOfPassengers' => '2',
    'Ride_RequestedDuration' => '0',
    'Ride_VehicleType' => 'SEDN',
    'Stop_Address_City' => 'Hackensack',
    'Stop_Address_Line2' => '/201-498-033',
    'Stop_Address_PhoneNo' => '201-498-033',
    'Stop_Address_PostalCode' => '07601',
    'Stop_Address_State' => 'MA',
    'Stop_Address_StreetName' => '401 Hackensack Ave',
    'Ride_HourlyRatesOnly' => 'False'
);



$b = array(
    'transactionId' => '15347575',
    'txnDateTime' => 'Thu Jan 17 16:20:01 EST 2008',
    'txnSentBy' => 'SATU8000',
    'txnSentTo' => 'PLAN1665',
    'txnTypeCode' => 'NR',
    'tNResNo' => time(),
    'bookerMemberCode' => 'SATU8000',
    'bookerResNo' => '4118692',
    'providerMemberCode' => 'PLAN1665',
    'providerResNo' => '',
    'Accounting_CorporateID' => '2',
    'Accounting_UDF_Desc_1' => 'CostCenter',
    'Accounting_UDF_Desc_2' => 'DeptCode',
    'Accounting_UDF_Desc_3' => 'CustomerAcct',
    'Accounting_UDF_Desc_4' => 'EmployeeID',
    'Accounting_UDF_Desc_5' => 'CostCenter',
    'Accounting_UDF_Value_1' => '1070',
    'Accounting_UDF_Value_2' => '000-0-00',
    'Accounting_UDF_Value_3' => '2',
    'Accounting_UDF_Value_4' => 'GENBUS',
    'Accounting_UDF_Value_5' => '|CC|DC|CA|ID|',
    'Booker_IATA' => '22518952',
    'Caller_Name' => 'JUANA GUERRA SIERRA',
    'Caller_PhoneNo' => '617-444-6225',
    'Dropoff_Address_City' => 'CAMBRIDGE',
    'Dropoff_Address_CountryCode' => 'US',
    'Dropoff_Address_PhoneNo' => '862-371-7067',
    'Dropoff_Address_PostalCode' => '02142',
    'Dropoff_Address_State' => 'MA',
    'Dropoff_Address_StreetName' => '3RD ST APT2014',
    'Dropoff_Address_StreetNo' => '250',
    'Dropoff_AsDirected' => 'N',
    'Dropoff_LocationName' => 'RESIDENCE',
    'Dropoff_LocationTypeCode' => 'O',
    'Passenger_CellPhone' => '862-371-7067-CELL',
    'Passenger_DayPhone' => '617-444-6914-B KAREN',
    'Passenger_EmailAddress' => 'msobecky@gmail.com',
    'Passenger_EveningPhone' => '862-371-7067',
    'Passenger_FirstName' => 'GEORGE T',
    'Passenger_LastName' => 'SPENCERGREEN',
    'Passenger_PNR' => 'IQSLCJ',
    'Payment_Method' => 'VO',
    'Pickup_Address_City' => 'BOSTON',
    'Pickup_Address_State' => 'MA',
    'Pickup_DateTime' => '02/05/2008 11:36',
    'Pickup_LocationTypeCode' => 'T',
    'Pickup_MeetAndGreet' => 'N',
    'Pickup_Train_CarrierCode' => '2V',
    'Pickup_Train_OriginStation' => 'NWK',
    'Pickup_Train_StationCode' => 'BOS',
    'Pickup_Train_StopDateTime' => '11:36',
    'Pickup_Train_TrainNumber' => '2150',
    'Pricing_RateType' => 'H',
    'Ride_EstimatedMileage' => '2.98',
    'Ride_MileageType' => 'M',
    'Ride_NumOfPassengers' => '1',
    'Ride_RequestedDuration' => '0',
    'Ride_VehicleType' => 'SEDN',
);

//<form name="test" action="https://secure.planettran.com/reservations/ptRes2/submitTransaction.php" method="post">
?>
<form name="test" action="<?=$go?>" method="post">
<input type="submit" value="Submit">
<?
foreach ($a as $k => $v) {
	echo '<input type="hidden" name="'.$k.'" value="'.$v.'">';
}



/*
echo '</form>';
echo "sending:<br><pre>";
print_r($a);
echo "</pre>";
$a = array("   d    ");
array_walk($a, 'strtoupper');

echo md5('planettran');
echo "<br>";
$s = "41-41dni@%&^&dfo868";
echo "$s<br>";
$pat = '/\D/';
$s = preg_replace('/\D/', '', $s);
echo "$s<br>";
*/


?>
