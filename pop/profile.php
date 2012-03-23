<?php

session_start();
$conf= array( 'db' => array('hostSpec'=>'localhost','dbType'=>'mysql','dbUser'=>'planet_schedul','dbPass'=>'schedule','dbName'=>'planet_reservations'));
if($_SESSION['role']=='p')
{
  die('Auth error');
}

ob_start();
echo " <span></span> ";
// include '../secure/reservations/ptRes2/ctrlpnl.php';
// ini_set('display_errors', true);
// error_reporting(E_ALL);

include dirname(__FILE__).'/../secure/reservations/ptRes2/lib/DBEngine.class.php';
include dirname(__FILE__).'/../secure/reservations/ptRes2/lib/Account.class.php';
include dirname(__FILE__).'/../secure/reservations/ptRes2/lib/Template.class.php';
include dirname(__FILE__).'/../secure/reservations/ptRes2/templates/cpanel.template.php';
ob_end_clean();

mysql_connect('localhost','planet_schedul','schedule');
mysql_select_db('planet_reservations');
if($_POST['radio_profile'])
{
  $_SESSION['currentID'] = $_POST['radio_profile'];
  $r = mysql_fetch_assoc(mysql_query("SELECT * FROM login where memberid='".$_SESSION['currentID']."'"));
  $_SESSION['currentName'] = $r['fname'].' '.$r['lname'];
/*
  $_SESSION['old_session'] = array();
  foreach($_SESSION as $k=>$v) {
    if($k == 'old_session') continue;
    $_SESSION['old_session'][$k] = $v;
  }
  
  $auth = new Auth();
  $auth->doLogin(null,null,null,null,null,null,true,$_POST['radio_profile'], false);

  $mimedId = $_SESSION['sessionID'];
  $_SESSION = $_SESSION['old_session'];
  $_SESSION['currentId'] = $mimedId;
*/
  
  ?>
<script type="text/javascript">
//<!--
    var ol = window.opener.location;
    window.opener.location = window.opener.location + ((ol+"").indexOf("?") != -1 ? "&" : "?") + "reload";
    window.close();
//-->
</script>
  <?php
  die();
}

if(isset($_POST['back_to_old'])) {
    if($_POST['back_to_old']) {
    $r = mysql_fetch_assoc(mysql_query("SELECT * FROM login where memberid='".$_SESSION['sessionID']."'"));
    $_SESSION['currentName'] = $r['fname'].' '.$r['lname'];
  
           /*     
        foreach($_SESSION['old_session'] as $k=>$v) {            
            $_SESSION[$k] = $v;
        }*/
        unset($_SESSION['currentID']); //$_SESSION['old_session']);
        
        ?>
<script type="text/javascript">
//<!--
    window.opener.location = window.opener.location;//.reload(true);//href = "https://secure.planettran.com/reservations/ptRes2/ctrlpnl.php";
    window.close();
//-->
</script>
            <?php
            
            die();
        
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="en-us" />
<title>Profile Picker | PlanetTran</title>
	<link href="/css/master.css" media="screen" rel="stylesheet" type="text/css" />
	<!--[if IE]>
		<link href="/css/ie.css" media="screen" rel="stylesheet" type="text/css" />
	<![endif]-->
</head>

<body class="popup">

<div id="container">
	
	<div id="main">

		<h1>Change the current passenger profile</h1>
                
                <form id="profile_picker" method="post">
                    
                    <?php if(isset($_SESSION['old_session'])) : ?>
                        <p>
                             <input type="submit" name="back_to_old" value=" &lt;&lt; Back to Your Profile " />
                        </p>
                    <?php endif; ?>
                    
		<p class="">Search for profiles using any of the following criteria (including partial words, too):</p>

		
			<fieldset>
				<legend>Profile Search</legend>
				<div class="row group">
				    <div class="labelish">
					<label for="picker_first">First Name</label>
				    </div>
				    <div class="inputs">
					<input type="text" class="text" id="picker_first" name="firstName" />
				    </div>
				</div>
				<div class="row group">
				    <div class="labelish">
					<label for="picker_last">Last Name</label>
				    </div>
				    <div class="inputs">
					<input type="text" class="text" id="picker_last" name="lastName" />
				    </div>
				</div>
				<div class="row group">
					<div class="labelish">
						<label for="picker_email">Email</label>
					</div>
					<div class="inputs">
						<input type="text" class="text" name="email" id="picker_email" />
					</div>
				</div>
				<?php if($_SESSION['role'] != 'a'): ?>
					<div class="row group">
						<div class="labelish">
							<label for="picker_group">Billing Group</label>
						</div>
						<div class="inputs">
							<select style="width: 84%" name="group" id="group">
								<option value=""></option>
								<?php foreach(AdminDB::get_grouplist() as $id=>$name): ?>
									<option value="<?php echo $id ?>"><?php echo $name ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
				<?php endif ?>
				<div class="row group">
					<div class="inputs">
						<input type="submit" value="Search" /><br />
					</div>
				</div>
			</fieldset>
		</form>
		<p>Select a person below to assume their profile and be able to edit their reservations or account settings:</p>
		<form method="post">
			<table cellpadding="0" cellspacing="0">
				<caption class="group">
					<h2>Profile Matches</h2>
				</caption>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Last</th>
						<th>First</th>
						<th>Email</th>
						<th>Company</th>
						<th>Phone</th>
					</tr>
				</thead>
				<tbody>
				  <?php /*
				    $where = array();
				    if($_POST['picker_first']) { $where[] = "fname like '%".addslashes($_POST['picker_first'])."%'";   }
				    if($_POST['picker_last'])  { $where[] = "lname like '%".addslashes($_POST['picker_last'])."%'";    }
				    if($_POST['picker_email']) { $where[] = "email like '%".addslashes($_POST['picker_email'])."%'";   }
				    if($_POST['picker_group']) { $where[] = "groupid like '%".addslashes($_POST['picker_group'])."%'"; }
				    if(count($where)) {
				      $where = 'WHERE '.implode(' AND ',$where);
				    } else {
				      $where = 'WHERE 1=0';
				    }*/
				  ?>
				  <?php 
					$db = new DBEngine();
					$db->db_connect();
					if($_SESSION['role']=='a')$_POST['group']=$_SESSION['curGroup'];
					$order = array('date', 'name', 'startTime', 'endTime', 'created', 'modified');
					$res   = $db->get_admin_schedules($_SESSION['sessionID'], array(),array());
					
					// die(print_r($res));
					
					// CmnFns::get_value_order($order), CmnFns::get_vert_order());
					// $q = mysql_query('SELECT * FROM login '.$where) or die(mysql_error());; ?>
				  <?php // while($r = mysql_fetch_assoc($q)): ?>
				  <?php foreach($res as $r): ?>
				    <tr>
					<td><input type="radio" name="radio_profile" value="<?php echo $r['memberid'] ?>" /></td>
					<td><?php echo $r['lname'] ?></td>
					<td><?php echo $r['fname'] ?></td>
					<td><?php echo $r['email'] ?></td>
					<td><?php echo $r['institution'] ?></td>
					<td><?php echo $r['phone'] ?></td>
				    </tr>
				  <?php endforeach ?>
				  <?php // endwhile ?>
				</tbody>
			</table>
			<div style="text-align:center;"><input type="submit" value="Select Profile" class="spacious_bottom" /></div>
		</form>

	</div><!-- /main -->

</div><!-- /container -->

</body>
</html>