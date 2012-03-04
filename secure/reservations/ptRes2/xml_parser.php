<?php
$xml_file = $url;

$xml_addNum_key		= "*GEOCODERESPONSE*RESULT*ADDRESS_COMPONENT*LONG_NAME";
$xml_addState_key	= "*GEOCODERESPONSE*RESULT*ADDRESS_COMPONENT*SHORT_NAME";
$xml_type_key	 	= "*GEOCODERESPONSE*RESULT*ADDRESS_COMPONENT*TYPE";

//$xml_lat_key		= "*GEOCODERESPONSE*RESULT*GEOMETRY*LOCATION*LAT";
//$xml_lon_key		= "*GEOCODERESPONSE*RESULT*GEOMETRY*LOCATION*LNG";

$story_array = array();
$address_array = array();

$counter = 0;
class xml_story{
    var $addNum, $addState, $type;
}

function startTag($parser, $data){
    global $current_tag;
    $current_tag .= "*$data";
}

function endTag($parser, $data){
    global $current_tag;
    $tag_key = strrpos($current_tag, '*');
    $current_tag = substr($current_tag, 0, $tag_key);
}

function contents($parser, $data){
    global $current_tag, $xml_addNum_key, $xml_addState_key, $xml_type_key, $counter, $story_array, $address_array;


	// if our object has all the pieces of data we need, add it to the
	// simpler address_array

    switch($current_tag){
        case $xml_addNum_key:
            $story_array[$counter] = new xml_story();
            $story_array[$counter]->addNum = $data;
            break;
        case $xml_addState_key:
            $story_array[$counter]->addState = $data;
            break;
        case $xml_type_key:
            $story_array[$counter]->type = $data;
 		if ($data == 'administrative_area_level_1')
			$address_array['state'] = $story_array[$counter]->addState;
		else
			$address_array[$data] = $story_array[$counter]->addNum;

            $counter++;
            break;
    }
}

$xml_parser = xml_parser_create();

xml_set_element_handler($xml_parser, "startTag", "endTag");

xml_set_character_data_handler($xml_parser, "contents");

$fp = fopen($xml_file, "r");
//echo filesize($xml_file);

$data = fread($fp, 80000);

if(!(xml_parse($xml_parser, $data, feof($fp)))){
    die("Error on line " . xml_get_current_line_number($xml_parser));
}

xml_parser_free($xml_parser);

fclose($fp);

//CmnFns::diagnose($story_array);
//CmnFns::diagnose($address_array);

?>
