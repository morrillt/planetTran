<?php

session_start();

include_once('lib/db/AdminDB.class.php');
include_once('../../../../BusinessLogic/Estimates/Estimate.php');

global $d;

get_estimate($_REQUEST);

function get_estimate($col){
    global $d;
    $d = new AdminDB();
    $e = new Estimate();

    $fromId = ($col['fromID']?$_GET['fromID']:0);
    $toId = ($col['toID']?$_GET['toID']:0);
    $stopId = ($col['stopID']?$_GET['stopID']:0);
    $from = null;
    $to = null;
    $stop = null;

    loadResourceById(&$e,$fromId);

    loadResourceById(&$e,$toId);

    loadResourceById(&$e,$stopId);

    loadCollectionIn(&$e, $col);

    setEstimateRegion(&$e);



    $estVal = $e->getEstimate();

    if($estVal->fare > 0 && $estVal->fare < 29)
        $estVal->fare = 29;

    echo $estVal->fare . '|' . $e->fromAddress->getOneLineAddress() . '|' . $e->toAddress->getOneLineAddress() . '|' .  $estVal->couponAmount . '|' .  $estVal->baseFare;

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
function loadResourceById(Estimate &$e, $machId){
    global $d;
    if($machId){
        $e->fromAddress->machid = $machId;
        $res = $d->get_resource_data($machId);
        EstimateConverter::getAddressFromResource(&$e->fromAddress, $res);
    }
}

function loadCollectionIn(Estimate &$e, $col){
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
