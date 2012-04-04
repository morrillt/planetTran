<?php

if(!class_exists('UserDB'))
{
  require_once dirname(__FILE__).'/db/UserDB.class.php';
}

class Account
{
  static $ROLES = array (
    'p' => 'Passenger',
    'a' => 'Manager'
  );
  static $BILLING_GROUPS = array(
    1 => 'First billing group',
    2 => 'Second billing group',
  );
  static $CUSTOMER_TYPES = array(
    1 => 'None',
    2 => 'VIP',
  );
  static $PRICE_TYPES = array(
    1 => 'First price type',
    2 => 'Second price type',
  );

  // FIXME: this should not be here
  static $AIRLINES = array(
    'TZ' => "ATA Airlines - TZ",
    'EI' => "Aer Lingus - EI",
    'AM' => "Aeromexico - AM",
    '9A' => "Air Atlantic - 9A",
    'AC' => "Air Canada - AC",
    'CA' => "Air China - CA",
    'AF' => "Air France - AF",
    'IJ' => "Air Liberte - IJ",
    'NZ' => "Air New Zealand - NZ",
    'FL' => "Air Tran - FL",
    'TS' => "Air Transat (Canada) - TS",
    'GB' => "Airborne Express - GB",
    'AS' => "Alaska Airlines - AS",
    'AZ' => "Alitalia - AZ",
    'NH' => "All Nippon Airways - NH",
    'G4' => "Allegiant Air - G4",
    'AQ' => "Aloha Airlines - AQ",
    'HP' => "America West Airlines - HP",
    'AA' => "American Airlines - AA",
    'AN' => "Ansett Australia - AN",
    'AV' => "Avianca - AV",
    'UP' => "Bahamasair - UP",
    'JV' => "Bearskin Airlines - JV",
    'GQ' => "Big Sky Airways - GQ",
    'BU' => "Braathens - BU",
    'BA' => "British Airways - BA",
    'BD' => "British Midland - BD",
    'ED' => "CCAir - ED",
    'C6' => "CanJet - C6",
    'CX' => "Cathay Pacific - CX",
    'MU' => "China Eastern Airlines - MU",
    'CZ' => "China Southern Airlines - CZ",
    'CO' => "Continental Airlines - CO",
    'DL' => "Delta Air Lines - DL",
    'BR' => "EVA Airways - BR",
    'U2' => "Easyjet - U2",
    'LY' => "El Al Israel Airlines - LY",
    'AY' => "Finnair - AY",
    '7F' => "First Air - 7F",
    'RF' => "Florida West Airlines - RF",
    'F9' => "Frontier Airlines - F9",
    'GA' => "Garuda - GA",
    'HQ' => "Harmony Airways - HQ",
    'HA' => "Hawaiian Airlines - HA",
    'IB' => "Iberia - IB",
    'FI' => "Icelandair - FI",
    'IC' => "Indian Airlines - IC",
    'IR' => "Iran Air - IR",
    'JD' => "Japan Air System - JD",
    'JL' => "Japan Airlines - JL",
    'QJ' => "Jet Airways - QJ",
    'B6' => "JetBlue Airways - B6",
    'KL' => "KLM Royal Dutch Airlines - KL",
    'KE' => "Korean Air Lines - KE",
    'WJ' => "Labrador Airways LTD - WJ",
    'LH' => "Lufthansa - LH",
    'MY' => "MAXjet - MY",
    'MH' => "Malaysian Airline - MH",
    'YV' => "Mesa Airlines - YV",
    'MX' => "Mexicana - MX",
    'GL' => "Miami Air Intl. - GL",
    'YX' => "Midwest Airlines - YX",
    'NW' => "Northwest Airlines - NW",
    'OA' => "Olympic Airways - OA",
    'PR' => "Philippine Airlines - PR",
    'PO' => "Polar Air - PO",
    'PD' => "Porter Airlines - PD",
    'QF' => "Qantas Airways - QF",
    'SN' => "Sabena - SN",
    'S6' => "Salmon Air - S6",
    'SV' => "Saudi Arabian Airlines - SV",
    'SK' => "Scandinavian Air (SAS) - SK",
    'YR' => "Scenic Airlines - YR",
    'S5' => "Shuttle America - S5",
    'SQ' => "Singapore Airlines - SQ",
    '5G' => "Skyservice - 5G",
    'SA' => "South African Airways - SA",
    'WN' => "Southwest Airlines - WN",
    'JK' => "Spanair - JK",
    'NK' => "Spirit Airlines - NK",
    'SY' => "Sun Country Airlines - SY",
    'LX' => "Swiss Int'l Airllines - LX",
    'TG' => "Thai Airways - TG",
    'TK' => "Turkish Airlines - TK",
    'US' => "US Airways - US",
    'U5' => "USA3000 - U5",
    'UA' => "United Airlines - UA",
    'VP' => "VASP - VP",
    'RG' => "Varig - RG",
    'VX' => "Virgin America - VX",
    'VS' => "Virgin Atlantic - VS",
    'WS' => "WestJet Airlines - WS",
    'MF' => "Xiamen Airlines - MF",
    'Z4' => "Zoom Airlines - Z4",
  );

  protected static function getUserId(&$memberid)
  {
    if(null === $memberid){
      $memberid = $_SESSION['currentID'];
    }
  }

  public static function getRoles()
  {
    return self::$ROLES;
  }

  public static function getBillingGroups()
  {
    // if(!$_SESSION['sessionID']) return;
    /*
    $domain = mysql_fetch_assoc(mysql_query('select * from login where id=\''.$_SESSION['sessionID'].'\';'));
    $domain = mysql_fetch_assoc(mysql_query('select * from billing_groups where groupid=\''.$domain['groupid'].'\';'));
    $domain = $domain['domain'];

    if(!$domain) return array();
    */
    $results = array();
    $q = mysql_query('select * from billing_groups order by name asc');// WHERE domain=\''.addslashes($domain).'\' order by group_name');

    while($r=mysql_fetch_assoc($q)) {
      $results[$r['groupid']] = $r['group_name'];
    }
    return $results;
    //*/ return self::$BILLING_GROUPS;
  }

  public static function getCustomerTypes()
  {
    return self::$CUSTOMER_TYPES;
  }

  public static function getPriceTypes()
  {
    return self::$PRICE_TYPES;
  }

  // FIXME: this should not be here
  public static function getAirlines()
  {
    return self::$AIRLINES;
  }

  // FIXME: this should not be here
  public static function getAllDrivers()
  {
    $userDB = new UserDB();
    $drivers = $userDB->get_all_drivers();

    return $drivers;
  }

  public static function getSavedLocations($memberid = null)
  {
    self::getUserId($memberid);

    $userDB = new UserDB();
    $scheduleid = $userDB->get_user_scheduleid($_SESSION['currentID']);
    $result = $userDB->get_user_permissions($scheduleid);

    return $result;
  }

  public static function getfavoriteDrivers($memberid = null)
  {
    self::getUserId($memberid);

    $userDB = new UserDB();
    $result = $userDB->get_user_favorite_drivers($memberid);

    return $result;
  }

  public static function getData($memberid = null)
  {
    self::getUserId($memberid);
//    var_dump($memberid);

    $userDB = new UserDB();

    return $userDB->get_user_data($memberid);
  }

  public static function getCreditCards($memberid = null)
  {
    self::getUserId($memberid);

    $userDB = new UserDB();
    $options = $userDB->getPaymentOptions($memberid);

    return $options ? $options : array();
  }

  // FIXME: this should not be here
  public static function getCreditCardsOptions($memberid = null, $default)
  {
    self::getUserId($memberid);

    $output = '<option value="">Select credit card</option>';

    foreach(self::getCreditCards($memberid) as $paymentId => $description)
    {
      $output .= sprintf('<option value="%s"'.($default == $paymentId ? ' selected="true"':'').'>%s</option>', $paymentId, $description);
    }

    return $output;
  }

  // FIXME: this should not be here
  public static function getCreditCardsDiv($memberid = null)
  {
    self::getUserId($memberid);

    $output = '';

    foreach(self::getCreditCards($memberid) as $paymentId => $description)
    {
      $output .= sprintf('%1$s<span class="options"><a href="AuthGateway.php?js=div&memberid=%2$s&mode=edit&hidesubmit=true&paymentProfileId=%3$s" class="popover-edit" title="Edit Credit Card">Edit</a> | <a href="AuthGateway.php?js=div&hidesubmit=true&memberid=%2$s&mode=delete&paymentProfileId=%3$s" class="popover-delete" title="Delete Credit Card?">Delete</a></span><br />',
        $description,
        $memberid,
        $paymentId
      );
    }

    return $output;
  }
}
