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
  protected $bodyOnly;
  protected $secondary;
	
	/**
	* Set the page's title
	* @param string $title title of page
	* @param int $depth depth of the current page relative to phpScheduleIt root
	*/
//	function Template($title = '', $depth = 0) {
	function Template($title = '', $secondary = true, $bodyOnly = false)
  {
		global $conf;
    $this->bodyOnly = $bodyOnly;
    $this->secondary = $secondary;
		
		$this->title = (!empty($title)) ? $title : $conf['ui']['welcome'];
//		$this->dir_path = str_repeat('../', $depth);
		$this->link = CmnFns::getNewLink();
		//Auth::Auth();	// Starts session
	}
	
	/**
	* Print all XHTML headers
	* This function prints the HTML header code, CSS link, and JavaScript link
	*
	* DOCTYPE is XHTML 1.0 Transitional
	* isIndex: print a different body tag for the index page, so it 
	* matches the rest of the front pages of the site
	*/
//	function printHTMLHeader($isIndex = false) {
	function printHTMLHeader($bodyClass = ''){
		global $conf;
		global $languages;
		global $lang;
		global $charset;

//		$path = $this->dir_path;
//		echo "<?xml version=\"1.0\" encoding=\"$charset\"?" . ">\n";
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php include $conf['app']['include_path'].'../templates/head.php' ?>
<title><?php echo $this->title ?></title>
<script type="text/javascript" src="functions.js"></script>
<script type="text/javascript" src="jquery.boxy.js"></script>
</head>

<body class="<?php echo $bodyClass ?>">
<?php if(!$this->bodyOnly): ?>
<div id="container">

<?php include $conf['app']['include_path'].'../templates/top.php' ?>

	<div id="colwrap" class="group constrainer">

<?php endif;
	}

	function printNavReservations(){
    global $conf;
//    include dirname(__FILE__).'/../../../../templates/nav/reservations.php';
    include $conf['app']['include_path'].'../templates/nav/reservations.php';
  }

	function printNavAccount(){
    global $conf;
//    include dirname(__FILE__).'/../../../../templates/nav/account.php';
    include $conf['app']['include_path'].'../templates/nav/account.php';
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
	function printWelcome() {
    /*
		global $conf;
		
		// Print out notice for administrator
		//echo Auth::isAdmin() ? '<h3 align="center">' . translate('Administrator') . '</h3>' : '';
		
		// Print out logoImage if it exists
		if (isset($_SESSION['curGroup']) && $_SESSION['curGroup']==63) {
		// show Millipore logo
		?>
		<table width=100% border=0>
		<tr>
			<td width=7%>&nbsp;</td>
			<td align="left"><img src="img/millipore.gif" alt="Millipore"/></td>
			<td align="right"><img src="img/reservations_masthead.jpg" alt="reservations"/></td>
			<td width=7%>&nbsp;</td>
		</tr>
		</table>
		<?
		} else {
		?>

<!-- updated align and logo img -->
	<div align="left">
	<img src="images/planettran_logo_new.jpg" alt="reservations" vspace="5" />
	</div>
		<?
		}
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
			<a href="ctrlpnl.php?ui=new"><h2>Try the new features of our latest release, Reservations 2.0!</h2></a>
		  </p>
		</td>
		<td class="mainBkgrdClr" valign="top">
		  <div align="right">
		    <p>
			<?= translate_date('header', mktime());?>
			</p>
			<p>
			  <? $this->link->doLink('javascript: help();', translate('Help')) ?>
			</p>
		  </div>
		</td>
	  </tr>
	</table>
	<?
  */
	}
	
	
	/**
	* Start main HTML table
	* @param none
	*/
	function startMain($mainClass = ''){
    global $auth;
    if(empty($mainClass) && !$this->secondary) $mainClass = 'wide';
    if(Auth::is_logged_in()){
	?>
    <? } ?>
    <div id="main" class="<?php echo $mainClass ?>">
    <div class="content_box">
      <div class="content_box_inner">
<?php
	}
	
	
	/**
	* End main HTML table
	* @param none
	*/
	function endMain() {
    echo '</div></div></div>';
	}
	
	
	/**
	* Print HTML footer
	* This function prints out a tech email
	* link and closes off HTML page
	* @global $conf
	*/
	function printHTMLFooter(){
		global $conf;
    if(!$this->bodyOnly): ?>
        <?php if($this->secondary) include $conf['app']['include_path'].'../templates/secondary.php'; ?>
      </div><!-- /colwrap -->
      <?php include $conf['app']['include_path'].'../templates/foot.php'; ?>
    </div><!-- /container -->
    <?php endif; ?>
  </body>
</html>
	<?
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


	/**
	* Print out a row of links to tool pages
	* $help the address of the optional help file
	*/
	function linkbar($help = '') {
		$respath = "https://secure.planettran.com/reservations/ptRes2/";
		$dispath = "https://secure.planettran.com/dispatch/ptRes/";
		$curpath = "https://secure.planettran.com".$_SERVER['SCRIPT_NAME'];
		$billpath = "https://secure.planettran.com/billing/ptRes/";

		$respages = array(	
			'billgroups.php'=>	'Billing Groups',
			'coupons.php'=>		'Coupons',
			'faredistance.php'=>	'Calculate Fare by Distance'
						);

		$i = 0;
		$out = '';

		echo '<table width="100%" cellspacing=0 cellpadding=0>';
		echo '<tr><td width="90%" align="left">';
		foreach ($respages as $k => $v) {
			$i++;
			if ($curpath == $respath.$k)
				$out .= "<b>$v</b>";
			else  
				$out .= "<a href=\"$respath$k\">$v</a>";
			if ($i != count($respages)) $out .= " | ";
		}
		
		$out .= ' | <a target="_blank" href="'.$billpath.'newdispatch.php">Billing Dispatch Screen</a>';

		echo "<div>$out</div>";
		echo '</td><td width="10%" align="right">';
		if ($help) {
			echo "<a href=\"$help\">Help</a>";
		} else
			echo "&nbsp;";
		echo "</td></tr></table>";
	}
}
?>
