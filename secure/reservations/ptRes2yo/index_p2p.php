<?php
/**
* This file is the login page for the system
* It provides a login form and will automatically
* forward any users who have cookies set to ctrlpnl.php
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 06-25-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Include Template class
*/
$biz = (isset($_GET['biz']) ? $_GET['biz'] : 'www') ;
$prefix = (isset($_GET['prefix']) ? $_GET['prefix'] : $biz);

include_once('lib/Template.class.php');

// Auth included in Template.php
$auth = new Auth();
$t = new Template();
$msg = '';

$resume = (isset($_POST['resume'])) ? $_POST['resume'] : '';
	
// Logging user out
if (isset($_GET['logout'])) {
    $auth->doLogout();   
}
else if (isset($_POST['login'])) {
	$msg = $auth->doLogin($_POST['email'], $_POST['password'], (isset($_POST['setCookie']) ? 'y' : null), false, $resume, $_POST['language']);
}
else if (isset($_COOKIE['ID'])) {
    $msg = $auth->doLogin('', '', 'y', $_COOKIE['ID'], $resume);  	// Check if user has cookies set up. If so, log them in automatically 
}

$t->printHTMLHeader();

// Print out logoImage if it exists
echo (!empty($conf['ui']['logoImage']))
		? '<div align="center"><img src="img/reservations_masthead.jpg" alt="reservations" vspace="5"/></div>'
		: '';

$t->startMain();

if (isset($_GET['auth'])) {
	$auth->printLoginForm(translate('You are not logged in!'), $_GET['resume']);
}
else {
	$auth->printLoginForm($msg);
}

?>
<table align="center">
	<tr colspan="3">
        <td height="15" colspan="1" valign="top" align="left"><img src="img/button_quickquote.gif" width="75" height="15">
	<td>Select Metro Area:<select name="market">
				<option value="BOS">Greater Boston, MA</option>
<!--				<option value="SFO">Bay Area, CA</option>-->
	</td>
          </tr>
          <form action="http://<?=$prefix?>.planettran.com/quickquote.php" method="post" name="quickquote" id="quickquote">
	<tr>
          <td valign="top" align="left" class="bodytext">Address:<input name="fromAddress" type="text" maxlength="50" size="20"></td>
	<td align="center">or Airport:<select name="fromAirport">
		<option value="BOS">Logan</option>
		<option value="PVD">TF Green</option>
		<option value="MHT">Manchester</option>
<!--		<option value="SFO">SFO</option>
		<option value="SJC">San Jose</option>
		<option value="OAK">Oakland</option>-->
		</select>
	</td>
	<td>or Hotel:<select name="fromHotel">
		<option value="20 Sidney St., Cambridge, MA">Hotel@MIT</option>
		<option value="25 Land Blvd., Cambridge, MA">Hotel Marlowe</option>
		<option value="2 Cambridge Center, Cambridge, MA">Mariott, Cambridge</option>
		</select>
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="center">to</td>
	<td>&nbsp;</td>
	</tr>
          <tr>
          <td valign="top" align="left" class="bodytext">Address:<input name="toAddress" type="text" maxlength="50" size="20"></td>
	<td align="center">or Airport:<select name="toAirport">
		<option value="BOS">Logan</option>
		<option value="PVD">TF Green</option>
		<option value="MHT">Manchester</option>
<!--		<option value="SFO">SFO</option>
		<option value="SJC">San Jose</option>
		<option value="OAK">Oakland</option>-->
		</select>
	</td>
	<td>or Hotel:<select name="toHotel">
		<option value="20 Sidney St., Cambridge, MA">Hotel@MIT</option>
		<option value="25 Land Blvd., Cambridge, MA">Hotel Marlowe</option>
		<option value="2 Cambridge Center, Cambridge, MA">Mariott, Cambridge</option>
		</select>
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td align="center">
	  <input name="quote" type="submit" value="quote it!">
           </form>             
            </td>
	<td>&nbsp;</td>
          </tr>
	</table>
<?
$t->endMain();
$t->printHTMLFooter();
?>
