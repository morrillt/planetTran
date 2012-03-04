<?php  
if (!isset($_GET['active'])) {
	include_once('lib/Template.class.php');
	$old = true;
} else  $old = false;
include_once('lib/DBEngine.class.php');
include('special.php');

$apts = array (
		"BOS"=>"Logan Int'l (Boston, MA)",
		"PVD"=>"T.F.Green Int'l (Providence, RI)",
		"MHT"=>"Manchester Int'l (Manchester, NH)",
		"SFO"=>"San Francisco Int'l (SF, CA)",
		"SJC"=>"San Jose Int'l (San Jose, CA)",
		"OAK"=>"Oakland Int'l (Oakland, CA)"
	);

if ($old) $t = new Template('Get a Quote');
$d = new DBEngine();
if ($old) {
	$t->printHTMLHeader();
	$t->startMain();
}
show_form();

if ($old) {
	$t->endMain();
	$t->printHTMLFooter();
}
/* Functions *************************************************************/
function show_form() {
	global $conf;
	//CmnFns::diagnose($conf);
	$manualLink = Auth::isAdmin() ? '<div style="text-align: center; font-weight: bold;"><a href="faredistance.php">To get an estimate by entering the distance manually, click here.</a></div>' : '';
	?>
<script language="javascript">
<!--
function checkAddress1() {
	var addr = document.quickquote.fromAddr.value;
	var city = document.quickquote.fromCity.value;
	
	var url = 'checkaddr.php?mode=1&address1=' + addr + '&city=' + city;
	var w = 400;
	var h = 250;
  
	window.open(url,"checkaddr","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
	//void(0); 
}

function checkAddress2() {
	var addr = document.quickquote.toAddr.value;
	var city = document.quickquote.toCity.value;
	
	var url = 'checkaddr.php?mode=2&address1=' + addr + '&city=' + city;
	var w = 400;
	var h = 250;
  
	window.open(url,"checkaddr","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
	//void(0); 
}
function checkZip() {
	if (document.quickquote.fromZip.value == "") {
		alert("Please fill in the origin address zip code.");
		return false;
	} else if (document.quickquote.airport.selectedIndex == 0 && document.quickquote.toZip.value == "") {
		alert("If not traveling to an airport, please fill in a destination zip code.");
		return false;
	}
	return true;
}
function get_region()
{
  var from_zip = get_state_from_zip(document.quickquote.fromZip.value.replace(/^\s*/, "").replace(/\s*$/, ""));
    var to_zip = get_state_from_zip(document.quickquote.toZip.value.replace(/^\s*/, "").replace(/\s*$/, ""));
    var airport = document.quickquote.airport.value;

document.quickquote.quoteregion.value =
	    get_service_region(from_zip, to_zip, airport);
}
function get_service_region(from_zip, to_zip, airport_code){

    var region = 1;
    if(from_zip =="CA" || to_zip=="CA" ||
            from_zip =="NV" || to_zip=="NV" ||
            from_zip =="AZ" || to_zip=="AZ" ||
            from_zip =="OR" || to_zip=="OR" ||
            airport_code=="SFO" || airport_code=="SJC" || airport_code=="OAK"){
        region=2;
    }
    return region;
}
function get_state_from_zip(zip){
	if(zip >= '99501' &&  zip<= '99950'){
		 return 'AK' ;
	} else if(zip >= '35004' &&  zip<= '36925'){
		 return 'AL' ;
	} else if(zip >= '71601' &&  zip<= '72959'){
		 return 'AR' ;
	} else if(zip >= '75502' &&  zip<= '75502'){
		 return 'AR' ;
	} else if(zip >= '85001' &&  zip<= '86556'){
		 return 'AZ' ;
	} else if(zip >= '90001' &&  zip<= '96162'){
		 return 'CA' ;
	} else if(zip >= '80001' &&  zip<= '81658'){
		 return 'CO' ;
	} else if(zip >= '06001' &&  zip<= '06389'){
		 return 'CT' ;
	} else if(zip >= '06401' &&  zip<= '06928'){
		 return 'CT' ;
	} else if(zip >= '20001' &&  zip<= '20039'){
		 return 'DC' ;
	} else if(zip >= '20042' &&  zip<= '20599'){
		 return 'DC' ;
	} else if(zip >= '20799' &&  zip<= '20799'){
		 return 'DC' ;
	} else if(zip >= '19701' &&  zip<= '19980'){
		 return 'DE' ;
	} else if(zip >= '32004' &&  zip<= '34997'){
		 return 'FL' ;
	} else if(zip >= '30001' &&  zip<= '31999'){
		 return 'GA' ;
	} else if(zip >= '39901' &&  zip<= '39901'){
		 return 'GA' ;
	} else if(zip >= '96701' &&  zip<= '96898'){
		 return 'HI' ;
	} else if(zip >= '50001' &&  zip<= '52809'){
		 return 'IA' ;
	} else if(zip >= '68119' &&  zip<= '68120'){
		 return 'IA' ;
	} else if(zip >= '83201' &&  zip<= '83876'){
		 return 'ID' ;
	} else if(zip >= '60001' &&  zip<= '62999'){
		 return 'IL' ;
	} else if(zip >= '46001' &&  zip<= '47997'){
		 return 'IN' ;
	} else if(zip >= '66002' &&  zip<= '67954'){
		 return 'KS' ;
	} else if(zip >= '40003' &&  zip<= '42788'){
		 return 'KY' ;
	} else if(zip >= '70001' &&  zip<= '71232'){
		 return 'LA' ;
	} else if(zip >= '71234' &&  zip<= '71497'){
		 return 'LA' ;
	} else if(zip >= '01001' &&  zip<= '02791'){
		 return 'MA' ;
	} else if(zip >= '05501' &&  zip<= '05544'){
		 return 'MA' ;
	} else if(zip >= '20331' &&  zip<= '20331'){
		 return 'MD' ;
	} else if(zip >= '20335' &&  zip<= '20797'){
		 return 'MD' ;
	} else if(zip >= '20812' &&  zip<= '21930'){
		 return 'MD' ;
	} else if(zip >= '03901' &&  zip<= '04992'){
		 return 'ME' ;
	} else if(zip >= '48001' &&  zip<= '49971'){
		 return 'MI' ;
	} else if(zip >= '55001' &&  zip<= '56763'){
		 return 'MN' ;
	} else if(zip >= '63001' &&  zip<= '65899'){
		 return 'MO' ;
	} else if(zip >= '38601' &&  zip<= '39776'){
		 return 'MS' ;
	} else if(zip >= '71233' &&  zip<= '71233'){
		 return 'MS' ;
	} else if(zip >= '59001' &&  zip<= '59937'){
		 return 'MT' ;
	} else if(zip >= '27006' &&  zip<= '28909'){
		 return 'NC' ;
	} else if(zip >= '58001' &&  zip<= '58856'){
		 return 'ND' ;
	} else if(zip >= '68001' &&  zip<= '68118'){
		 return 'NE' ;
	} else if(zip >= '68122' &&  zip<= '69367'){
		 return 'NE' ;
	} else if(zip >= '03031' &&  zip<= '03897'){
		 return 'NH' ;
	} else if(zip >= '07001' &&  zip<= '08989'){
		 return 'NJ' ;
	} else if(zip >= '87001' &&  zip<= '88441'){
		 return 'NM' ;
	} else if(zip >= '88901' &&  zip<= '89883'){
		 return 'NV' ;
	} else if(zip >= '06390' &&  zip<= '06390'){
		 return 'NY' ;
	} else if(zip >= '10001' &&  zip<= '14975'){
		 return 'NY' ;
	} else if(zip >= '43001' &&  zip<= '45999'){
		 return 'OH' ;
	} else if(zip >= '73001' &&  zip<= '73199'){
		 return 'OK' ;
	} else if(zip >= '73401' &&  zip<= '74966'){
		 return 'OK' ;
	} else if(zip >= '97001' &&  zip<= '97920'){
		 return 'OR' ;
	} else if(zip >= '15001' &&  zip<= '19640'){
		 return 'PA' ;
	} else if(zip >= '02801' &&  zip<= '02940'){
		 return 'RI' ;
	} else if(zip >= '29001' &&  zip<= '29948'){
		 return 'SC' ;
	} else if(zip >= '57001' &&  zip<= '57799'){
		 return 'SD' ;
	} else if(zip >= '37010' &&  zip<= '38589'){
		 return 'TN' ;
	} else if(zip >= '73301' &&  zip<= '73301'){
		 return 'TX' ;
	} else if(zip >= '75001' &&  zip<= '75501'){
		 return 'TX' ;
	} else if(zip >= '75503' &&  zip<= '79999'){
		 return 'TX' ;
	} else if(zip >= '88510' &&  zip<= '88589'){
		 return 'TX' ;
	} else if(zip >= '84001' &&  zip<= '84784'){
		 return 'UT' ;
	} else if(zip >= '20040' &&  zip<= '20041'){
		 return 'VA' ;
	} else if(zip >= '20040' &&  zip<= '20167'){
		 return 'VA' ;
	} else if(zip >= '20042' &&  zip<= '20042'){
		 return 'VA' ;
	} else if(zip >= '22001' &&  zip<= '24658'){
		 return 'VA' ;
	} else if(zip >= '05001' &&  zip<= '05495'){
		 return 'VT' ;
	} else if(zip >= '05601' &&  zip<= '05907'){
		 return 'VT' ;
	} else if(zip >= '98001' &&  zip<= '99403'){
		 return 'WA' ;
	} else if(zip >= '53001' &&  zip<= '54990'){
		 return 'WI' ;
	} else if(zip >= '24701' &&  zip<= '26886'){
		 return 'WV' ;
	} else if(zip >= '82001' &&  zip<= '83128'){
		 return 'WY' ;
	}
		return '';
}
-->
</script>	
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    	<tr> 
      		<td colspan="4" valign="top" align="center">
		<?
		print_specials();
      		   // echo '<img border="0" src="img/test_main_hdr.jpg" width="600" height="150">';
		?>
		</td>
	</tr>
    	<tr height="80"> 

      	<td colspan="4" valign="top" align="center">
	  <table border="2" cellpadding="4" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="600" bordercolorlight="#999999" bordercolordark="#333333">
        <tr>
            <td width="600" height="15" colspan="4" valign="top" align="left">
            <img src="img/button_quickquote.gif" width="75" height="15">
	</td>
        </tr>
       <tr>
        <td valign="top" class="bodytext">
	<?=$manualLink?>
        <form action="<?=$conf['app']['domain']?>/quickquote.php?script=<?=get_script()?>&groupid=<?=get_groupid()?>" method="post" name="quickquote" id="quickquote" onsubmit="return checkZip();">
	<br><p align="center" class="bodytext">Street Address:    <input name="fromAddr" type="text" maxlength="30" size="15"> City/State:    <input name="fromCity" type="text" maxlength="50" size="12"> Zip: <input name="fromZip" type="text" maxlength="5" size="3"> <a href="javascript: checkAddress1();">Don't know zip code?</a>
        <p align="center"> to/from: 
	<select name="airport">
		<option value="P2P" selected="selected">choose an airport or enter a destination below</option>
		<option value="BOS">Logan Int'l (Boston, MA)</option>
		<option value="PVD">T.F.Green Int'l (Providence, RI)</option>
		<option value="MHT">Manchester Int'l (Manchester, NH)</option>
		<option value="SFO">San Francisco Int'l (SF, CA)</option>
		<option value="SJC">San Jose Int'l (San Jose, CA)</option>
		<option value="OAK">Oakland Int'l (Oakland, CA)</option>
	</select>
	<br><p align="center" class="bodytext">or<br>Street Address:    <input name="toAddr" type="text" maxlength="30" size="15"> City/State:    <input name="toCity" type="text" maxlength="50" size="12"> Zip: <input name="toZip" type="text" maxlength="5" size="3"> <a href="javascript: checkAddress2();">Don't know zip code?</a>
<input type="hidden" id="quoteregion" name="quoteregion" value="1" />
<p align = "center"><input name="quote" type="submit" value="quote it!" onclick="get_region();">
        </form>
        </td>
        </tr>
        </table>
	<br>
	</td>
        </tr>
	<?
}
function get_groupid() {
     $id=(isset($_SESSION['currentID']) ? $_SESSION['currentID'] : $_SESSION['sessionID']);
	$query = "select groupid from login where memberid='".$id."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row['groupid'];
}
function get_script() {
    $id=(isset($_SESSION['currentID']) ? $_SESSION['currentID'] : $_SESSION['sessionID']);
	$query = "select script from login where memberid='".$id."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row['script'];
}
?>
