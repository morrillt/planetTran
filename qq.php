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
        <form action="<?=$conf['app']['domain']?>/secure/quickquote.php?groupid=<?=get_groupid()?>" method="post" name="quickquote" id="quickquote" onsubmit="return checkZip();">
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

<p align = "center"><input name="quote" type="submit" value="quote it!">
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
	$query = "select groupid from login where memberid='".$_SESSION['sessionID']."'";
	$qresult = mysql_query($query);
	$row = mysql_fetch_assoc($qresult);
	return $row['groupid'];
}
?>
