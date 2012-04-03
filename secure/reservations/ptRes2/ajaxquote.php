<?php
 // ini_set('error_reporting', E_ERROR);
 // ini_set('display_errors', 1);
// spl_autoload_extensions(".php");
// spl_autoload_register();
use classes\BusinessLogic\Estimates as Est;
session_start();

include_once('lib/db/AdminDB.class.php');
include_once('../../../PTAutoLoader.php');
PTAutoLoader::BusinessLogicLoader('Estimates/Estimate');

global $d;

get_estimate($_REQUEST);
function loadMachId($arr, $srch){
    foreach($srch as $v){
        if($arr["$v"]){
	        return $arr["$v"];
        }
    }
    return 0;
}
function get_estimate($col){
    global $d;
    $d = new AdminDB();
    $e = new Est\Estimate();
    $e->perlScript = "estimateA.pl";
    $fromId = loadMachId($col, array('fromID','from_location'));
    $toId = loadMachId($col, array('toID','to_location'));
    $stopId = loadMachId($col, array('stopID','stop_location'));

    $from = null;
    $to = null;
    $stop = null;

    loadResourceById(&$e->fromAddress,$fromId);
    loadResourceById(&$e->toAddress,$toId);
    loadResourceById(&$e->stopAddress,$stopId);
    loadCollectionIn(&$e, $col);
    setEstimateRegion(&$e);

    $estVal = $e->getEstimate();
    $estErrors="";
    if(is_null($estVal)){
        $estErrors =  implode('    ', $e->errors);
    }
    if($estVal->fare > 0 && $estVal->fare < 29)
        $estVal->fare = 29;

	/*
		  *  var $code=0;  no
	 *       }
			 var $address1;  1
			 var $address2;  1
			 var $address3;  1
			 var $res_type;
			 var $group_name;
			 var $vehicle_desc;
			 var $base_fare = 0;
			 var $stop_fee = 0;
			 var $wait_fee = 0;
			 var $vehicle_fee = 0;
			 var $meet_greet_fee = 0;
			 var $convertible_seats_fee;
			 var $booster_seats_fee;
			 var $subtotal_fare;
			 var $s_discount = 0;
			 var $g_discount = 0;
			 var $c_discount = 0;
			 var $min_fare = 29;
			 var $integration_fee;
			 var $airport_fee;
			 var $tolls;
			 var $fare = 0;
			 var $status = "NotOK";
		  */

	$output = array();
	$output['resType'] = $estVal->resType;
	$output['groupName'] = $estVal->groupName;
	$output['vehicleDesc'] = $estVal->vehicleDesc;
	$output['baseFare'] = $estVal->baseFare;
	$output['stopFee'] = $estVal->stopFee;
	$output['waitFee'] = $estVal->waitFee;
	$output['vehicleFee'] = $estVal->vehicleFee;
	$output['meetGreetFee'] = $estVal->meetGreetFee;
	$output['convertibleSeatsFee'] = $estVal->convertibleSeatsFee;
	$output['boosterSeatsFee'] = $estVal->boosterSeatsFee;
	$output['subtotalFare'] = $estVal->subtotalFare;
	$output['sDiscount'] =  $estVal->sDiscount;
	$output['gDiscount'] = $estVal->gDiscount;
	$output['cDiscount'] = $estVal->cDiscount;
	$output['minFare'] = $estVal->minFare;
	$output['integrationFee'] = $estVal->integrationFee;
	$output['airportFee'] = $estVal->airportFee;
	$output['tolls'] = $estVal->tolls;
	$output['fromAddress'] = $e->fromAddress->getOneLineAddress();
	$output['toAddress'] = $e->toAddress->getOneLineAddress();
	$output['stopAddress'] = $e->stopAddress->getOneLineAddress();
	$output['fare'] = $estVal->fare;
	$output['status'] = $estVal->status;
	$output['errors'] =  implode('    ',$estVal->errors) . $estErrors;


	$msg = "";

	foreach($output as $k=>$v){
		$msg .= $v . "|";
	}
	$msg = substr($msg,0,-1); // get rid of last pipe
	echo $msg;

    //echo $estVal->fare . '|' . $e->fromAddress->getOneLineAddress() . '|'
    //    . $e->toAddress->getOneLineAddress() . '|' .  $estVal->couponAmount
    //    . '|' .  $estVal->baseFare .'|' . $estVal->status .'|' . implode('    ',$estVal->errors) . $estErrors ;

}

function setEstimateRegion(&$e){
    if($e->stopAddress->isValid()){
        if($e->fromAddress->region == $e->toAddress->region && $e->fromAddress->region  == $e->stopAddress->region )
        {
            $e->regionCode = $e->fromAddress->region;
        }
        else
        {
            $fare = 0;
            echo $fare . '|' . $e->fromAddress->getOneLineAddress() . '|' . $e->toAddress->getOneLineAddress();
            return;
        }
    }
    elseif($e->fromAddress->region  == $e->toAddress->region )
    {
        $e->regionCode = $e->fromAddress->region;
    }
    else
    {
        $fare = 0;
        echo $fare . '|' . $e->fromAddress->getOneLineAddress() . '|' . $e->toAddress->getOneLineAddress();
        return;
    }

}
function loadResourceById(Est\EstimateAddress &$e, $machId){
    global $d;
    if(is_null($machId)){
        $e  = new Est\EstimateAddress();
    } else if($machId && $machId=="asDirectedLoc"){
        $e->machid = "asDirectedLoc";
    } else if($machId){
        $e->machid = $machId;
        $res = $d->get_resource_data($machId);
        Est\EstimateConverter::getAddressFromResource(&$e, $res);
    }
}

function loadCollectionIn(Est\Estimate &$e, $col){
    $e->groupid = $groupId = isset($col['groupid']) ? $col['groupid'] : 0;

    $e->fromAddress->street = $col['from_address'];
    $e->fromAddress->city = $col['from_city'];
    $e->fromAddress->state = $col['from_state'];
    $e->fromAddress->zip = $col['from_zip'];
    $e->toAddress->street = $col['to_address'];
    $e->toAddress->city = $col['to_city'];
    $e->toAddress->state = $col['to_state'];
    $e->toAddress->zip = $col['to_zip'];
    $e->stopAddress->street = $col['stop_address'];
    $e->stopAddress->city = $col['stop_city'];
    $e->stopAddress->state = $col['stop_state'];
    $e->stopAddress->zip = $col['stop_zip'];

    $e->memberid = $_SESSION['currentID'];
    $e->groupid = $_SESSION['curGroup'];

    // if(isset($col['memberid'])&& ! empty($col['memberid'])){
    //     $e->memberid = $col['memberid'];
    // }
    if(isset($col['convertible_seats'])&& ! empty($col['convertible_seats'])){
        $e->convertibleSeats = $col['convertible_seats'];
    }
    if(isset($col['booster_seats'])&& ! empty($col['booster_seats'])){
        $e->boosterSeats=$col['booster_seats'];
    }
    if(isset($col['meet_greet '])&& ! empty($col['meet_greet '])){
        $e->meetGreet =$col['meet_greet'];
    }
    //if(isset($col['groupid'])&& ! empty($col['groupid'])){
    //    $e->groupid = $col['groupid'];
    //}
    if(isset($col['coupon'])&& ! empty($col['coupon'])){
        $e->coupon = $col['coupon'];
    }
    if(isset($col['vehicle_type'])&& ! empty($col['vehicle_type'])){
        $e->vehicleType = $col['vehicle_type'];
    }
    if(isset($col['trip_type'])&& ! empty($col['trip_type'])){
        $e->tripType = $col['trip_type'];
    }

    if($e->tripType=='H' && isset($col['wait_time'])&& ! empty($col['wait_time'])){
        $e->waitTime = $col['wait_time'];
    }
}

function apt_or_zip($machid, $zip) {
    if (strpos($machid, 'airport') !== false)
        $return = substr($machid, -3);
    else if (stripos($machid, 'logan') !== false)
        $return = 'BOS';
    else if ($machid == '41b40be9091cb')
        $return = 'BOS';
    else
        $return = $zip;

    return escapeshellarg($return);
}


function get_service_region($from_zip, $to_zip, $airport_code, $from_state, $to_state){
    if($from_state=="MA" || $to_state=="MA"){
        return 1;
    }
    $from_zip = CmnFns::get_state_from_zip($from_zip);
    if($from_zip==''){
        $from_zip =$from_state;
    }
    $to_zip = CmnFns::get_state_from_zip($to_zip);
    if($to_zip==''){
        $to_zip =$to_state;
    }
    $region = 1;
    if ($from_zip == "CA" || $to_zip== "CA" ||
        $from_zip == "NV" || $to_zip== "NV" ||
        $from_zip == "AZ" || $to_zip== "AZ" ||
        $from_zip == "OR" || $to_zip == "OR" ||
        $airport_code == "SFO" || $airport_code=="SJC" || $airport_code == "OAK"){
        $region=2;
    }
    return $region;
}
function get_service_region2($res){
    $state = CmnFns::get_state_from_zip($res['zip']);
    if($state==''){
        $state = $res['state'];
    }
    $airport = apt_or_zip($res['machid'],'');
    $region = 1;
    if ($state == "CA" ||
        $state == "NV" ||
        $state == "AZ" ||
        $state == "OR" ||
        $airport == "SFO" || $airport=="SJC" || $airport == "OAK"){
        $region=2;
    }
    return $region;
}

function get_service_region3 ($state,$zip)
{
    $airport = $zip;
    $region = 1;
    if ($state == "CA" ||
        $state == "NV" ||
        $state == "AZ" ||
        $state == "OR" ||
        $airport == "SFO" || $airport=="SJC" || $airport == "OAK"){
        $region=2;
    }
    return $region;
}
