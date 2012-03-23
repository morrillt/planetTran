<?php 
/** 
* This file provides output functions
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 08-10-04
* @package phpScheduleIt
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
*/
/**
* Base directory of application
*/
@define('BASE_DIR', dirname(__FILE__) . '/..');
/**
* Include Auth class
*/
include_once('Auth.class.php');

/**
* Provides functions for outputting template HTML
*/
class Template {
	var $title;
	var $link;
	var $dir_path;
	
	/**
	* Set the page's title
	* @param string $title title of page
	* @param int $depth depth of the current page relative to phpScheduleIt root
	*/
	function Template($title = '', $depth = 0) {
		global $conf;
		
		$this->title = (!empty($title)) ? $title : $conf['ui']['welcome'];
		$this->dir_path = str_repeat('../', $depth);
		$this->link = CmnFns::getNewLink();
		//Auth::Auth();	// Starts session
	}
	
	/**
	* Print all XHTML headers
	* This function prints the HTML header code, CSS link, and JavaScript link
	*
	* DOCTYPE is XHTML 1.0 Transitional
	* @param none
	*/
	function printHTMLHeader() {
		global $conf;
		global $languages;
		global $lang;
		global $charset;
		
		$path = $this->dir_path;
		//echo "<?xml version=\"1.0\" encoding=\"$charset\"?" . ">\n";
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<link rel="stylesheet"  type="text/css" href="css.css"  />
	<link rel="stylesheet"  type="text/css" href="css1.css"  />
	<link href="<?=$path?>boxy.css" rel="stylesheet" type="text/css" />

	<script language="JavaScript" type="text/javascript" src="<?=$path?>jquery.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?=$path?>functions.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?=$path?>jquery.boxy.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	</head>
	<title><?=$this->title?></title>
	<body>
	<?
	}
	
	
	/**
	* Print welcome header message
	* This function prints out a table welcoming
	*  the user.  It prints links to My Control Panel,
	*  Log Out, Help, and Email Admin.
	* If the user is the admin, an admin banner will
	*  show up
	* @global $conf
	*/
	function printWelcome($do = false) {
		if (!$do) return;
		global $conf;
		
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="5" class="mainBorder">
	  <tr>
		<td class="mainBkgrdClr">
		  <h4 class="welcomeBack"><?= translate('Welcome Back', array($_SESSION['sessionName'], 1))?></h4>
		  <p>
			<? $this->link->doLink($this->dir_path . 'index.php?logout=true', translate('Log Out')) ?>
			|
			<? $this->link->doLink($this->dir_path . 'ctrlpnl.php', translate('My Control Panel')) ?>
			|
			<a href="ctrlpnl.php?ui=old">Switch to Classic View</a>
		  </p>
		</td>
		<td class="mainBkgrdClr" valign="top" align="right">
		 <!--<div align="right">-->
		    <p>
			<?= translate_date('header', mktime());?>
			</p>
			<p>
			  <? $this->link->doLink('javascript: help();', translate('Help')) ?>
			</p>
		  <!--</div>-->
		</td>
	  </tr>
	</table>
	<?
	}
	
	/*
	* Do subheader
	*/
	function subHeader() {
		return;
		$active = isset($_GET['active']) ? $_GET['active'] : (isset($_POST['active'])?$_POST['active']:false);
		//if (!$active)
		//	return;
		$tabs = array(	
			); 
		//if ($_SESSION['role'] == 'a' || $_SESSION['role'] == 'm') {
		//	$tabs['Schedules'] = 'schedules';
		//}
		$sublinks = '';
		foreach ($tabs as $k => $v) {
			$hilite = ($active == $v) ? ' class="active"' : '';
			$sublinks .= "<a href=\"ctrlpnl.php?active=$v\"$hilite>$k</a>";	
		}

		$subheader = '<tr><td width="100%" height="26px"><div id="nav">'.$sublinks.'</div></td></tr>';
		echo $subheader;
	}
	
	/**
	* Start main HTML table
	* @param none
	*/
	function startMain() {
		global $conf;
		$dispath = $conf['app']['domain'].'/dispatch/ptRes/';
		$tabs = array(	'Home' => 'ctrlpnl.php?active=home',
			'Impact' => 'impact.php',
			'Reservations' => 'ctrlpnl.php?active=view',
			'My Addresses'	=> 'ctrlpnl.php?active=locs',
			'History/Receipts' => 'receipts.php',
			'Referrals' => 'referrals.php',
			'My Account' => 'register.php',
			'Our Service' => 'info.php?active=service',
			'Price Quote'	=> 'ctrlpnl.php?active=qq',
			'Search'	=> $dispath.'res_search2.php" target="_blank'
			);
		if (Auth::isSuperAdmin()) $tabs['Map'] = 'map.php';
		$split = 1;
		if ($_SESSION['role'] == 'a' || $_SESSION['role'] == 'm') {
			$arr1 = array_slice($tabs, 0, 2);
			$arr1['Schedules'] = 'ctrlpnl.php?active=schedules';
			$arr1 = array_merge($arr1, array_slice($tabs, 2));
			$tabs = $arr1;
		}
		$visitorLogo = "img/pixel.gif";	
		if ($_SESSION['curGroup']) {
			$g = $_SESSION['curGroup'];
			if (file_exists("img/$g.gif"))
				$visitorLogo = "img/$g.gif";
			else if (file_exists("img/$g.jpg"))
				$visitorLogo = "img/$g.jpg";
			else if (file_exists("img/$g.png"))
				$visitorLogo = "img/$g.png";
		}
	?>

<table border=0 id="main" height="100%" width="900px" align="center" cellspacing="0" cellpadding="0" >
  <tr width="100%">
    <td valign="bottom">


<!-- updated with new logo -->
<a href="http://www.planettran.com" border="0"><img align="left" src="images/planettran_logo_new.jpg" border="0" /></a>
<br clear="all" />
<!-- updated with new phone image -->
<div id="reservation-info" style="text-align: right;">
	<img src="images/phone-number.gif" alt="1 888 PLNT TRN (1 888 756 8876)" border="0" width="166" height="9" id="phone-number" style="float: none;margin-bottom: 9px;" />
</div>

     </td>
  </tr>
  <tr>
    <td width="100%" height="1px"><div id="line"></div></td>
  </tr>
<tr>
<td><?=$this->printWelcome(true);?></td>
</tr>
  <tr>
	<?	
	//print_r($_SESSION);
	$tabstring = '';
	foreach ($tabs as $k=>$v) {
		$c = $_SERVER['PHP_SELF'];
		if (!empty($_SERVER['QUERY_STRING'])) 
			$c.= '?'.$_SERVER['QUERY_STRING'];
		$active = (strpos($c, $v) !== false) ? ' class="active"' : '';
		if ($v == 'register.php') $v .= '?edit=true';
	$tabstring .= "<a href=\"$v\"$active>$k</a>";
	}
	?>
    <td width="100%" height="26px"><div id="nav"><?=$tabstring?></div></td>
  </tr>
	<?	
	$this->subHeader(); 
	?>
  <tr>
  <td height="200px" valign="top">
	<?
	}
	
	
	/**
	* End main HTML table
	* @param none
	*/
	function endMain() {
		//$this->quickLinks();
	?>
		</td>
	  </tr>
	</table>

	<?
	}
	
	
	/**
	* Print HTML footer
	* This function prints out a tech email
	* link and closes off HTML page
	* @global $conf
	*/
	function printHTMLFooter() {
		global $conf;
	?>
	</body>
	</html>
	<?
	}

	/*
	* Quick Links
	*/
	function quickLinks() {
		global $conf;
		global $link;
	?>
<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
  <tr>
    <td class="tableBorder">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr>
          <td class="tableTitle">
		    <a href="javascript: void(0);" onclick="showHideCpanelTable('quicklinks');">&#8250; <?=translate('My Quick Links')?></a>
		  </td>
        </tr>
      </table>
      <div id="quicklinks" style="display: <?=$this->getShowHide('quicklinks')?>;">
      <table width="100%" border="0" cellspacing="1" cellpadding="0">
        <tr style="padding: 5px" class="cellColor">
          <td colspan="2">
            <p><b>&raquo;</b>
              <a href="http://www.planettran.com">PlanetTran Home</a>
            </p>
		<p><b>&raquo;</b>
               <a href="http://www.planettran.com/service.php">Details about Our Service</a>
             </p>
            <!--<p><b>&raquo;</b>
              <? $link->doLink('schedule.php', translate('Go to the Online Scheduler')) ?>
            </p>-->
            <p><b>&raquo;</b>
              <? $link->doLink('register.php?edit=true', translate('Change My Profile Information/Password')) ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('my_email.php', translate('Manage My Email Preferences')) ?>
            </p>
            <p><b>&raquo;</b>
             <a href="javascript: window.open('feedback.php', 'feedbackForm', 'width=700, height=425'); void(0);">Send Feedback</a> 
            </p>
            <p><b>&raquo;</b>
             <a href="javascript: window.open('special.php', 'qq', 'width=630, height=350'); void(0);">See special rates for your organization.</a> 
            </p>
            <p><b>&raquo;</b>
             <a href="javascript: window.open('qq.php', 'qq', 'width=630, height=400'); void(0);">Get a Custom Quote w/ your organization's discount.</a> 
            </p>
            <?
		// If it's the admin, print out admin links
		if (Auth::isAdmin()) {
			echo
				  '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=schedules', translate('Manage Schedules')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=resources', translate('Manage Resources')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=users', translate('Manage Users')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=reservations', translate('Manage Reservations')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('blackouts.php', translate('Manage Blackout Times')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=email', translate('Mass Email Users')) . "</p>\n"
                . '<p><b>&raquo;</b> ' .  $link->getLink('usage.php', translate('Search Scheduled Resource Usage')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('admin.php?tool=export', translate('Export Database Content')) . "</p>\n"
				. '<p><b>&raquo;</b> ' .  $link->getLink('stats.php', translate('View System Stats')) . "</p>\n";
		}
		?>
            <p><b>&raquo;</b>
              <? $link->doLink('mailto:' . $conf['app']['adminEmail'].'?cc=' . $conf['app']['ccEmail'], 'Send Email Regarding Reservations', '', '', 'Send a non-technical email to the administrator') ?>
            </p>
            <p><b>&raquo;</b>
              <? $link->doLink('index.php?logout=true', translate('Log Out')) ?>
            </p>
          </td>
        </tr>
      </table>
	  </div>
    </td>
  </tr>
</table>
	<?
	}
	
	/*
	* getShowHide, for use with quick links
	*/
	function getShowHide($table) {
		if (isset($_COOKIE[$table]) && $_COOKIE[$table] == 'hide') {
			return 'none';
		}
		else
			return 'block';
	}
	
	/**
	* Sets the link class variable to reference a new Link object
	* @param none
	*/
	function set_link() {
		$this->link = CmnFns::getNewLink();
	}
	
	/**
	* Returns the link object
	* @param none
	* @return link object for this class 
	*/
	function get_link() {
		return $this->link;
	}
	
	/**
	* Sets a new title for the template page
	* @param string $title title of page
	*/
	function set_title($title) {
		$this->title = $title;
	}
}
?>
