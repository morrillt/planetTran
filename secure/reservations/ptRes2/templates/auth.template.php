<?php
/**
* This file provides output functions for all auth pages
* No data manipulation is done in this file
* @author Nick Korbel <lqqkout13@users.sourceforge.net>
* @version 09-08-04
* @package Templates
*
* Copyright (C) 2003 - 2004 phpScheduleIt
* License: GPL, see LICENSE
/*/
$link = CmnFns::getNewLink();	// Get Link object

/**
* Prints out a form for users can register
*  filling in any values
* @param boolean $edit whether this is an edit or a new register
* @param array $data values to auto fill
* @param string $msg error message to print to user
*/
function print_register_form($edit, $data = array(), $msg = '', $new = 0, $favs = array(), $paymentArray = array()) {
	// $new: whether this register form is for new version of site
	global $conf;
	list($ccnum, $exp, $cvv2) = explode('+', $data['other']);

	// $ccshow and $ccmess now deprecated
	$ccshow = substr($ccnum, -4, 4);
	$ccmess = ($ccnum&&$exp)?"<br>Card on file: ************$ccshow":'';

	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Tools.class.php');
	$t = new Tools();

	$positions    = $conf['ui']['positions'];		// Postions that are availble in the pull down menu
	$institutions = $conf['ui']['institutions'];	// Institutions that are available in the pull down menu

	include_once($conf['app']['include_path'].'reservations/ptRes2/lib/Account.class.php');

	// Print header
//	if (!$new)
//		echo '<h3 align="center">' . (($edit) ? 'Edit your profile' : translate('Please register')) . '</h3>' . "\n";

	if (!empty($msg))
	{
		if(substr($msg,0,3) == 'glb') {

		}
		else CmnFns::do_error_box($msg, '', false);
	}

	$mode = ($edit) ? "e" : "r";

//	$width = $new ? 100 : 50;
  include dirname(__FILE__).'/../../../../config/paths.php';
?>
<h1 id="hdr_account"><span class="imagetext">My Account</span></h1>

<form id="account_profile" name="register" method="post" action="<?= $_SERVER['PHP_SELF'] . '?' . ($edit ? 'edit=' . $edit : '')?>" onSubmit="return checkReg('<?=$mode?>');">

  <fieldset class="hr group">
    <div class="row group">
      <div class="labelish">
        <label for="emailaddress"><?php echo translate('Email') ?></label>
      </div>
      <div class="inputs">
        <input id="emailaddress" type="text" name="emailaddress" class="textbox" value="<?= isset($data['emailaddress']) ? $data['emailaddress'] : (isset($_GET['emailaddress']) ? $_GET['emailaddress'] : '') ?>" maxlength="75" />
        <em>(this will be your login)</em>
      </div>
    </div>

    <div class="row group">
      <div class="labelish">
        <label for="fname"><?php echo translate('First Name') ?></label>
      </div>
      <div class="inputs">
        <input id="fname" type="text" name="fname" class="textbox" value="<?=isset($data['fname']) ? $data['fname'] : ''?>" maxlength="50" />
      </div>
    </div>

    <div class="row group">
      <div class="labelish">
        <label for="lname"><?php echo translate('Last Name') ?></label>
      </div>
      <div class="inputs">
        <input id="lname" type="text" name="lname" class="textbox" value="<?=isset($data['lname']) ? $data['lname'] : ''?>" maxlength="50" />
      </div>
    </div>

    <div class="row group">
      <div class="labelish">
        <label for="phone"><?php echo translate('Phone') ?></label>
      </div>
      <div class="inputs">
        <input id="phone" type="text" name="phone" class="textbox" value="<?=isset($data['phone']) ? $data['phone'] : ''?>" maxlength="20" />
      </div>
    </div>

    <div class="row group">
      <div class="labelish">
        <label for="password"><?php echo translate('Password') ?></label>
      </div>
      <div class="inputs">
        <input id="password" type="password" name="password" class="textbox" />
        <em>(at least 6 characters)</em>
      </div>
    </div>
    <div class="row group">
      <div class="labelish">
        <label for="password2"><?php echo translate('Re-Enter Password') ?></label>
      </div>
      <div class="inputs">
        <input id="password2" type="password" name="password2" class="textbox" />
      </div>
    </div>
  </fieldset>

  <fieldset class="hr group">
    <div class="row group">
      <div class="labelish">
        <label><?php echo translate('Company/Organization') ?></label>
      </div>
      <div class="inputs">
        <input type="text" name="institution" class="textbox" value="<?php echo $data['institution'] ?>" maxlength="255" />
        <?
        /* // seems useless so far
          echo '<input type="hidden" name="billtype" value="'.
          $GLOBALS['billtype'].'"/>';
          echo '<input type="hidden" name="groupid" value="'.
          $GLOBALS['groupid'].'"/>';
          echo '<input type="hidden" name="verify_groupid" value="'.
          $GLOBALS['groupid'].'"/>';

          if(isset($_GET['rid'])) echo '<input type="hidden" name="rid" value="'.$_GET['rid'].'">';
          else if(isset($_COOKIE['rid'])) echo '<input type="hidden" name="rid" value="'.$_COOKIE['rid'].'">';
          if(empty($institutions[0]))
          {
            echo '<input type="text" name="institution" class="textbox" value="'.(isset($data['institution']) ? $data['institution'] : (isset($GLOBALS['biz']) && $GLOBALS['biz'] != 'www' ? $GLOBALS['biz'].'" disabled="true' : '' )).'" maxlength="255" />'."\n";
          }
          else
          {
            ?>
            <select name="institution" class="textbox">
              <?
              // Print out position options
              for($i = 0; $i < count($institutions); $i++)
              {
                echo '<option value="'.$institutions[$i].'"'
                .( (isset($data['institution']) && ($data['institution'] == $institutions[$i])) ? ' selected="selected"' : '' )
                .'>'.$institutions[$i].'</option>'."\n";
              }
              ?>
            </select>
            <?
          }
          */
        ?>
      </div>
    </div> 

    <?php 
    	//if($data['role'] === 'm' || $data['role'] === 'a'): // wrong	 
    	if($_SESSION['role']=='m' && $_SESSION['role'] !== 0):  // only role of M should have access to this - JL 3/26
    ?>
		<div class="row group">
			<div class="labelish">
			  <label for="role">Role</label>
			</div>
			<div class="inputs">
			  <select name="role" id="role">
			    <?php foreach(Account::getRoles() as $key => $name): ?>
			      <option value="<?php echo $key ?>"<?php if($key == $data['role']) echo ' selected="selected"' ?>><?php echo $name ?></option>
			    <?php endforeach; ?>
			  </select>
			</div>
	    </div>
	
	    <div class="row group">
	 		<div class="labelish">
				<label for="position"><?php echo translate('Dept. Code') ?></label>
	    </div>
	
	
      <div class="inputs">
        <?
          if(empty($positions[0]))
          {
            echo '<input id="position" type="text" name="position" class="textbox" value="'.(isset($data['position']) ? $data['position'] : '').'" maxlength="100" />'."\n";
          }
          else
          {
            ?>
            <select id="position" name="position" class="textbox">
              <?
              // Print out position options
              for($i = 0; $i < count($positions); $i++)
              {
                echo '<option value="'.$positions[$i].'"'
                .( (isset($data['position']) && ($data['position'] == $positions[$i])) ? ' selected="selected"' : '' )
                .'>'.$positions[$i].'</option>'."\n";
              }
              ?>
            </select>
          <?
          }
        ?>
      </div>
    <?php endif ?>  

    <?php /*if(!$edit): ?>
      <div class="row group">
        <div class="labelish">
          <label for="role"><?php echo translate('Role') ?></label>
        </div>
        <div class="inputs">
          <select id="role" name="role" class="textbox">
            <option value="p">Passenger</option>
            <option value="a">Schedule Manager</option>
          </select>
        </div>
      </div>
    <?php endif; */
    // die(print_r($_SESSION)); ?>

    <?php 
	// 	if(in_array($_SESSION['role'], array('v','m','a')) && $_SESSION['role'] !== 0): -- Old code.  Removed JL 3/26 
    	if($_SESSION['role']=='m' && $_SESSION['role'] !== 0):  // only role of M should have access to this
    ?>
      <div class="row group">
	<div class="labelish">
	  <label for="billing_group">Billing Group</label>
	</div>
	<div class="inputs"><!-- Only editable by SM and above -->
	  <select name="groupid" id="groupid">
	    <option value="">-- Choose One --</option>
	    <?php foreach(Account::getBillingGroups() as $key => $name): ?>
	      <option value="<?php echo $key ?>"<?php if($key == $data['groupid']) echo ' selected="selected"' ?>><?php echo $name ?></option>
	    <?php endforeach; ?>
	  </select>
	</div>
      </div>
    <!--div class="row group admin">
      <div class="labelish">
        <label for="price_type">Price Type</label>
      </div>
      <div class="inputs">
        <select name="price_type" id="price_type">
          <?php foreach(Account::getPriceTypes() as $key => $name): ?>
            <option value="<?php echo $key ?>"<?php if($key == $data['price_type']) echo ' selected="selected"' ?>><?php echo $name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div-->

    <div class="row group admin">
      <div class="labelish">
        <label for="role">Customer type</label>
      </div>
      <div class="inputs">
        <select name="role" id="role">
          <option value="1">-- Choose One --</option>
          <?php foreach(Account::getCustomerTypes() as $key => $name): ?>
            <option value="<?php echo $key ?>"<?php if($key == $data['role']) echo ' selected="selected"' ?>><?php echo $name ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <?php endif ?>
    
    <?php if(!$new): ?>
      <div class="row group admin">
        <div class="labelish">
          <a class="popover-delete" href="/pop/delete_user.php?memberid=<?php echo $data['memberid'] ?>">Delete Account?</a>
        </div>
      </div>
    <?php endif; ?>
    
    <?php /*
		 <div class="row group admin">
		 <div class="labelish">
		 <label for="profile_notes">Profile Notes</label>
		 </div>
		 <div class="inputs">
		 <textarea name="profile_notes" id="profile_notes"><?php echo $data['profile_notes'] ?></textarea>
		 </div>
		 </div>
		 */
 ?>

  </fieldset>

  <?php if(!$new): ?>
    <fieldset id="credit_cards" class="hr group more_leading">
      <div class="row group">
        <div class="labelish">Credit Cards</div>
        <div class="inputs">
          <div id="credit_cards_div">
            <?php echo Account::getCreditCardsDiv($data['memberid']) ?>
          </div>
          <a href="AuthGateway.php?memberid=<?php echo $data['memberid'] ?>&mode=add" class="popover-add spacious_top" title="Add Credit Card">Add credit card</a>
        </div>
      </div>
    </fieldset>
  <?php endif;?>

  <?php if(!$new): ?>
    <fieldset class="hr more_leading">
      <div class="row group">
        <div class="labelish">
          <label for="favroite_drivers">Favorite Drivers</label>
        </div>
        <div class="inputs">
          <select id="favourite_drivers">
            <option value="">Select favourite driver</option>
            <?php foreach(Account::getAllDrivers() as $driver): ?>
              <?php
				if (!$driver['memberid'])
					continue;
 ?>
              <option value="<?php echo $driver['memberid'] ?>"><?php echo $driver['fname'].' '.$driver['lname'] ?></option>
            <?php endforeach;?>
          </select>
          <input type="hidden" id="driverAddUrl" value="<?php echo $securePrefix ?>/admin_update.php?fn=addFavDriver" />
          <input id="addFavDriver" type="button" value="Add" /><br />
          <?php foreach(Account::getFavouriteDrivers() as $driver): ?>
            <div>
              <?php echo $driver['fname'] ?> <?php echo $driver['lname'] ?> <span class="options"><a href="/pop/remove_driver.php?memberid=<?php echo $driver['memberid'] ?>" class="popover-delete parentDiv" title="Remove Favorite Driver?">Remove</a></span>
            </div>
          <?php endforeach;?>
        </div>
      </div>
    </fieldset>
  <?php endif;?>
<?php /*
	 <fieldset class="hr group">
	 <?
	 // No more $ccmess, it's all in the drop down now
	 if($_GET['groupid'] != 1469 && $edit):

	 // Message for new registers only
	 $newcustmsg = $new ? ' (can be entered later)' : '';
	 ?>

	 <div class="row group">
	 <div class="labelish">
	 <label for="role"><?php echo translate('Payment Info'); echo $newcustmsg ?></label>
	 </div>
	 <div class="inputs">
	 Stored payment options<br/>
	 <?php $t->print_dropdown($paymentArray, null, 'paymentProfileId', null, '', 'paymentProfileId'); ?><br/>
	 <a href="javascript: paymentPopup('<?= $memberid ?>', 'add')">Add Payment Info</a><br>
	 <a href="javascript: paymentPopup('<?= $memberid ?>', 'edit')">Edit Payment Info</a><br>
	 <a href="javascript: paymentPopup('<?= $memberid ?>', 'delete')">Delete Payment Info</a><br>
	 </div>
	 </div>
	 <?php endif; ?>

	 </fieldset>

	 <fieldset class="hr group">
	 <div class="row group">
	 <div class="labelish">
	 <label for="twitter_username"><?php echo translate('Twitter Username') ?></label>
	 </div>
	 <div class="inputs">
	 <input id="twitter_username" name="twitter_username" class="textbox" value="<?=isset($data['twitter_username']) ? $data['twitter_username']:''?>"/>
	 </div>
	 </div>
	 </fieldset>
	 <fieldset class="hr group">
	 <?php if($edit && isset($data['trip_credit']) && $data['trip_credit']): ?>
	 <div class="row group">
	 <div class="labelish">
	 <label><?php echo translate('Trip Credit') ?></label>
	 </div>
	 <div class="inputs">
	 $<?=number_format($data['trip_credit'])?>
	 </div>
	 </div>
	 <?php endif; ?>
	 <?php if($edit): ?>
	 <div class="row group">
	 <div class="labelish">
	 <label><?php echo translate('Favorite Drivers') ?></label>
	 </div>
	 <div class="inputs">
	 <?php
	 if($edit && $favs)
	 {
	 for($i = 0; $favs[$i]; $i++)
	 {
	 $cur = $favs[$i];
	 $lname = strtoupper(substr($cur['lname'], 0, 1));
	 $name = $cur['fname']." ".$lname;
	 echo "$name";
	 echo '<span class="options"><a href="delFav.php?del=1&driverid='.$cur['driverid'].'&name='.urlencode($name).'">Remove</a></span>';
	 }
	 }
	 else if($edit && !$favs)
	 {
	 echo 'You have no favorite drivers!';
	 }
	 ?>
	 </div>
	 </div>
	 <div class="row group">
	 <div class="inputs">* Please note, while we cannot guarantee being able to send a specific driver, we will certainly try!</div>
	 </div>
	 <?php endif; ?>
	 </fieldset>
	 */
 ?>

  <div align="center">
    <?php if($edit): ?>
      <input type="submit" name="update" value="<?= translate('Save') ?>" class="button" />
      <input type="button" name="cancel" value="<?= translate('Cancel') ?>" class="button" onclick="javascript: document.location='ctrlpnl.php';" />
    <?php else:?>
      <input type="submit" name="register" value="<?= translate('Register') ?>" class="button" />
      <input type="button" name="cancel" value="<?= translate('Cancel') ?>" class="button" onclick="javascript: document.location='index.php';" />
    <?php endif;?>
  </div>
</form>

<? }

	/**
	* Prints out a login form and any error messages
	* @param string $msg error messages to display for user
	* @param string $resume page to resume on after login
	*/
	function printLoginForm($msg = '', $resume = '') {
	global $conf;
	$link = CmnFns::getNewLink();

	// Check browser information
	//echo '<script language="JavaScript" type="text/javascript">checkBrowser();</script>';

	if (!empty($msg))
	CmnFns::do_error_box($msg, '', false);
?>

    <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr>
        <td bgcolor="#CCCCCC">
        </td>

        <td colspan=4>
          <div id="main-content">
            <h1 class="page-header">Log In</h1>

            <div class="article">

              <form name="login" method="post" id="res-form" action="<?= $_SERVER['PHP_SELF'] ?>">
                <p STYLE=" position: relative">
                  <label for="res-email">Email address</label>
                  <input type="text" name="email" id="res-email" class="textbox" tabindex="1" />
                  <input type="image" name="login" value="Log In" class="image" src="/images/button-login-long.gif" tabindex="3" />
                  <a style="float: left; clear: both;position:absolute;right: 85px;top:20px;" href="register2.php?billtype=<?= $_GET['billtype'] ?>&amp;biz=<?= $_GET['biz'] ?>&amp;groupid=<?= $_GET['groupid'] ?>" class="" style="float: right; margin: -10px 83px 0 0;" onmouseover="javascript: window.status='Register'; return true;" onmouseout="javascript: window.status=''; return true;">Create an account</a>
                </p>
                <p>
                  <label for="res-pass">Password</label>
                  <input type="password" name="password" class="textbox" id="res-pass" tabindex="2" />
                  <input type="checkbox" name="setCookie" value="true"> Remember me on this computer
                  <input type="hidden" name="resume" value="<?= $resume ?>" />
                  <input type="hidden" name="login" value="1" />
                </p>
              </form>

            </div>
          </div>

      </tr>
    </table>
    <div align="center" style="margin-bottom: 0;">
      <? $link->doLink('forgot_pwd.php', translate('Forgot your password?'), '', '', translate('Retreive lost password')) ?>
      <? //$link->doLink('javascript: help();', translate('Help'), '', '', translate('Get online help'))?>
    </div>
<?
}
?>
