<?phpinclude_once('lib/Template2.class.php');global $conf;$path = $conf['app']['include_path'];include_once($path . 'reservations/ptRes2/lib/Tools.class.php');$t = new Template('Survey');$d = new DBEngine();$tool = new Tools();$t->printHTMLHeader();sheader();if ($_POST['submitSurvey']) {	$data['resid'] = $_POST['resid'];	$data['issues'] = 0;	$data['notes'] = $_POST['comments']." ".$_POST['name'];	$data['managerid'] = 'glb4b6791c79ae72';	$data['category'] = 'feedback';	$data['fromsystem'] = 'survey';	$issueid = $tool->insert_single_oti($d, $data);	insert_survey($d, $issueid);	if (isset($_POST['favoriteDriver']) && $_POST['favoriteDriver']=='yes'){		$tool->add_favorite_driver($d, $_POST['memberid'], $_POST['driver']);	}	ThankYou($path);} else	surveyForm($d);sfooter();$t->printHTMLFooter();//*******************************************/*#main-content .article {	width: 365px;	float: left;	margin-left: 9px;	line-height: 1.5;	display: inline;}*/function ThankYou($path){	$link = '';	$fullpath = $path . "reservations/ptRes2/referrals.php";	if ($_POST['would_recommend'] == 'yes')		$link = 'If you would like to visit our referrals page, <a href="'.$fullpath.'">click here</a>.<br>&nbsp;<br>';	?>	<div class="inner" style="text-align: center; color: #666666; font-weight: bold;">	Thank you for completing our survey!<br>&nbsp;<br>	<?=$link?>	<a href="<?=$_POST['referer']?>">Return</a>	</div>	<?}function sheader() {	?><style type="text/css">div.inner {	margin-left: 1.4em;	margin-top: 8px;	font-weight: normal;	font-size: 12px;	color: #939598;}div {	color: #00652e;	font-size: 12px;	margin-bottom: 20px;	font-weight: bold;}</style><div style="width: 600px; margin-left: auto; margin-right: auto;">	<img alt="" border=0 src="images/planettran_logo_new.jpg" id="planettran-logo">	<div id="reservation-info" style="height: 28px; text-align: right;">	<img alt="" border=0 src="images/phone-number.gif" style="margin: 9px;">	</div>	<?}function sfooter() {	?>	<div id="mailing-form">&nbsp;</div>	</div>	<?}function surveyForm($d) {	//$referer = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : 'http://www.planettran.com';	$referer = 'http://www.planettran.com';	$resid = $_GET['resid'] ? $_GET['resid'] : null;	if ($resid) {		$trip = $d->get_trip_data($resid);		$memberid = $trip['memberid'];		$last5 = $d->get_last_5($memberid, $resid, $trip['fname']);			$date = date("m/d/Y", $trip['date']);		$time = $trip['date'] + $trip['startTime'] * 60;		$time = date("g:ia", $time);		list($fname, $lname) = explode(" ", $trip['driver'], 2);		$driver = $fname." ".substr($lname, 0, 1);		$showid = CmnFns::showid($resid);		?>		<div style="color: black; font-weight: normal; color: #666; margin-left: 10px;">		<b>Reservation#<?=$showid?></b><br>		<?=$date?> <?=$time?><br>		<?=$trip['fromname']?> to <?=$trip['toname']?><br>		Driver <?=$driver?>		</div>		<?		if ($last5) {		?>			<div style="color: #666; font-weight: normal; margin-left: 10px;">			<script type="text/javascript">			function changePage(e) {				var index = e.selectedIndex;				var group = e.options[index].value;				var ahref = "survey.php?menu=1&resid=" + group;				document.location.href = ahref;			}			</script>			To give feedback on another recent reservation, select from the following list:<br>			<select name="surveyres" style="margin-top: 5px;" onChange="changePage(this)">			<option value="">Recent reservations</option>			<?			for ($i=0; $last5[$i]; $i++) {				$cur = $last5[$i];				echo '<option value="'.$cur['resid'].'">';				echo CmnFns::showid($cur['resid'])." on ".date("m/d/Y", $cur['date']).", ".$cur['fromname']." to ".$cur['toname'];				echo "</option>\n";			}			?>			</select>			</div>		<?		}	}	?>	<form name="feedback" action="<?=$_SERVER['PHP_SELF']?>" method="post">		<div>	1) How would you rate your overall satisfaction with PlanetTran?<div class="inner"><?	radioButtons('overall');	?></div></div>	<div>	2) Please rate your satisfaction across the following criteria.<div class="inner"><?	multiRadioButtons();		?>	</div>	</div>	<div>	3) Do you plan on traveling with PlanetTran again?<div class="inner"><?		yesNoSelect('use_again');	?>	</div>	</div>	<div>	4) Would you recommend PlanetTran to a friend or colleague?<div class="inner"><?	yesNoSelect('would_recommend');	?>	</div>	</div>	<div>	5) Would you like to add your driver <b style="color: black;"><?=$driver?></b> to your Favorite Driver list? (Please note, while we cannot guarantee being able to send a specific driver, we will certainly try!)<div class="inner"><?	yesNoSelect('favoriteDriver');	?>	</div>	</div>	<div>	6) Do you have any other comments or suggestions for PlanetTran? We are eager to hear your thoughts.<div class="inner">	<textarea name="comments" style="width: 90%; height: 65px;"></textarea>	</div>	</div>	<div>	7) Contact Information (Optional)	<div class="inner">	<table width="100%" cellspacing=5 cellpadding=0>	<tr>		<td align="left" width="15%">Name</td>		<td align="left" width="85%"><input type="text" name="name">	</tr>	<tr>		<td align="left">Email Address</td>		<td align="left"><input type="text" name="email">	</tr>	<tr>		<td align="left">Phone Number</td>		<td align="left"><input type="text" name="phone">	</tr>	</table>	</div>	</div>	<div>	Your feedback is invaluable to us. Thank you for taking the time to comment on your experience.	</div>	<div style="text-align: center;">	<input type="hidden" name="submitSurvey" value="1">	<input type="hidden" name="referer" value="<?=$referer?>">	<input type="hidden" name="resid" value="<?=$resid?>">	<input type="hidden" name="driver" value="<?=$trip['driverid']?>">	<input type="hidden" name="memberid" value="<?=$memberid?>">	<input type="submit" value="Done">	</div>	</form>	<?}/**	$name name of radio buttons*/function radioButtons($name) {	if (!$name) return;	//$count = range(1,5);	$count = fields();	?><table width="100%" cellspacing=0 cellpadding=0><tr><?	foreach ($count as $k => $v) {		?><td width="20%" align="left"><?=$v?> <input type="radio" name="<?=$name?>" value="<?=$k?>"></td><?	}	echo '</tr></table>';}function fields() {	return array(	5=> 'Excellent',			4=> 'Very Good',			3=> 'Good',			2=> 'Fair',			1=> 'Poor');}function MultiRadioButtons() {	//$count = range(1,5);	$count = fields();	// header row, first cell blank		?><table width="100%" cellspacing=3 cellpadding=2 style="border: 1px solid #AAA;"><tr>	<td>&nbsp;</td><?	// header row, numbers		foreach ($count as $k => $v) {		echo "<td align=\"center\">$v</td>";	}	echo "</tr>\n";	$i = 0;		$headers = headers();	foreach ($headers as $name => $header) {		$bg = $i % 2 ? '#FFFFFF' : '#EEEEEE';		?><tr style="background-color: <?=$bg?>;"><td align="left" width="25%"><?=$header?></td><?				// punctuality = 3, etc		foreach ($count as $k => $v) {			?><td width="15%" align="center"><input type="radio" name="<?=$name?>" value="<?=$k?>"></td><?		}		echo "</tr>\n";		$i++;	}	echo '</table>';}// Move this function to Tools.classfunction headers() {	return array(	'ease'=>	'Ease of making a reservation',			'professionalism'=>	'Professionalism of driver',			'punctuality'=>	'Punctuality of driver',			'knowledge'=>	'Knowledge of driver (as related to street routes)',			'condition1'=>	'Condition of car',			'comfort'=>	'Comfort of ride');}/**	$name name of select box. First row blank*/function yesNoSelect($name) {	if (!$name) return;	?><select name="<?=$name?>">	<option value=""></option>	<option value="yes">Yes</option>	<option value="no">No</option>	</select><?}/* $d DBEngine link */function insert_survey($d, $issueid) {	$overall = 	$_POST['overall'] ? $_POST['overall'] : null;	$ease = 	$_POST['ease'] ? $_POST['ease'] : null;	$prof = 	$_POST['professionalism'] ? $_POST['professionalism']:null;	$punct = 	$_POST['punctuality'] ? $_POST['punctuality'] : null;	$knowledge = 	$_POST['knowledge'] ? $_POST['knowledge'] : null;	$condition =	$_POST['condition1'] ? $_POST['condition1'] : null;	$comfort = 	$_POST['comfort'] ? $_POST['comfort'] : null;	$vals = array(	$issueid,			$overall,			$ease,			$prof,			$punct,			$knowledge,			$condition,			$comfort,			$_POST['use_again'],			$_POST['would_recommend'],			$_POST['comments'],			$_POST['name'],			$_POST['email'],			$_POST['phone']		);	$query = "insert into survey (issueid,overall,ease,professionalism,punctuality,knowledge,condition1,comfort,use_again,would_recommend,comments,name,email,phone)		  values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";	$q = $d->db->prepare($query);	$result = $d->db->execute($q, $vals);	$d->check_for_error($result);}?>