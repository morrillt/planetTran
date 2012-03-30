<?php
/**
* Utility functions for tool pages 
*/

@define('BASE_DIR', dirname(__FILE__) . '/../..');

class Tools {

	function Tools() {

	}

	/**
	* Get distance to a zip code from Logan
	*/
	function getDistance($zip, $loc2) {
		$query = "select distance from zips where 
			  (zip='$zip' and loc2='$loc2') or
			  (zip='$loc2' and loc2='$zip')";
		$qresult = mysql_query($query);
		if (!$qresult || !mysql_num_rows($qresult))
			return false;

		$row = mysql_fetch_assoc($qresult);
		return $row['distance'];
	}

	function car_list($area = null, $order = '') {
		$areastr = $area ? "and area='$area'" : '';
		$orderby = $order ? "order by $order" : '';
		$query = "select id, code, area from codes
			  where category='vehicle' and status='active'
			  $areastr 
			  $orderby";
		$qresult = mysql_query($query);
		$return = array();
		while($row = mysql_fetch_assoc($qresult))
			$return[] = $row;
		return $return;
	}

	/**
	* Issue codes for OTI system
	*/
	function get_oti_issues_array() {
		return array(	'drv_late'=>	1,
				'drv_lost'=>	2,
				'dsp_late'=>	4,
				'no_contact'=>	8,
				'res_us'=>	16,
				'already_left'=>32,
				'res_them'=>	64,
				'contact_info'=>128,
				'drv_behav'=>	256,
				'saturn'=>	512,
				//'drv_err'=>	1024,
				//'dsp_err'=>	2048,
				//'cs'=>		4096,
				//'fleet'=>	8192,
				'other'=>	1073741824);
	}
	function get_oti_display_array() {
		return array(	
				//'drv_err'=>	'Driver',
				//'dsp_err'=>	'Dispatcher',
				//'cs'=>		'Customer Service',
				//'fleet'=>	'Fleet',
				'drv_late'=>	'Driver Late',
				'drv_lost'=>	'Driver Lost',
				'dsp_late'=>	'Dispatched Late',
				'no_contact'=>	'Unable to contact',
				'res_us'=>	'Reservation error (Planettran)',
				'contact_info'=>'Missing/wrong confact info',
				'res_them'=>	'Reservation error (customer)',
				'saturn'=>	'Saturn reservation',
				'drv_behav'=>	'Complaint about driver',
				'already_left'=>'Pax had already left',
				'other'=>	'Other');
	}

	/*
	*  Issue categories for customer feedback form
	*/
	function get_cs_issues_array() {
		return array(	'late'=>	1,
				'drv'=>		2,
				'bill'=>	4,
				'other'=>	1073741824);
	}
	function get_cs_display_array() {
		return array(	'late'=>	'Car was late',
				'drv'=>		'Issues with driver',
				'bill'	=>	'Billing issue',
				'other'=>	'Other');
	}

	// General issues form
	function get_feedback_issues_array() {
		return array(	'sales'=>	1,
				'cs'=>		2,
				'billing'=>	4,
				'mobile'=>	8,
				'general'=>	1073741824
				);
	}
	function get_feedback_display_array() {
		return array(	'sales'=>	'Sales',
				'cs'=>		'Customer Service',
				'billing'=>	'Billing',
				'mobile'=>	'Mobile',
				'general'=>	'General Feedback'
				);
	}

	function get_mgeneral_issues_array() {
		return array(	'feature'=>	1,
				'bug'=>		2
				);
	}
	function get_mgeneral_display_array() {
		return array(	'feature'=>	'Feature request',
				'bug'=>		'Bug report'
				);
	}

	function get_email_issues_array() {
		return array(	'res'=>		1,
				'receipt'=>	2,
				'bill'=>	4,
				'general'=>	8,
				'res_comp'=>	16,
				'bill_comp'=>	32,
				'other'=>	1073741824);
	}
	function get_email_display_array() {
		return array(	'res'=>		'Reservation request',
				'receipt'=>	'Receipt request',
				'bill'=>	'Billing inquiry',
				'general'=>	'General inquiry',
				'res_comp'=>	'Reservation complaint',
				'bill_comp'=>	'Billing complaint',
				'other'=>	'Other');
	}


	/*
	* Return the correct binary and display arrays for OTI
	*/
	function get_binary_array($fromsystem) {
		if ($fromsystem == 'dispatch')
			return $this->get_oti_issues_array();
		else if ($fromsystem == 'feedback')
			return $this->get_cs_issues_array();
		else if ($fromsystem == 'general')
			return $this->get_feedback_issues_array();
		else if ($fromsystem == 'm_general')
			return $this->get_mgeneral_issues_array();
		else if ($fromsystem == 'survey')
			return array();
		else if ($fromsystem == 'email')
			return $this->get_email_issues_array();
	}
	function get_display_array($fromsystem) {
		if ($fromsystem == 'dispatch')
			return $this->get_oti_display_array();
		else if ($fromsystem == 'feedback')
			return $this->get_cs_display_array();
		else if ($fromsystem == 'general')
			return $this->get_feedback_display_array();
		else if ($fromsystem == 'm_general')
			return $this->get_mgeneral_display_array();
		else if ($fromsystem == 'survey')
			return array();
		else if ($fromsystem == 'email')
			return $this->get_email_display_array();
	}
	

	/*
	* Categories of OTIs
	*/
	function get_category_array() {
		return array(	'cs'=>		'Customer Service',
				'dispatch'=>	'Dispatch',
				'driver'=>	'Driver',
				'billing'=>	'Billing',
				'fleet'=>	'Fleet',
				'feedback'=>	'Feedback',
				'system'=>	'Website'
				);
	}

	function survey_array() {
		return array(	'overall'=>	'Overall Satisfaction',
				'ease'=>	'Ease of making a reservation',
				'professionalism'=>	'Professionalism of driver',
				'punctuality'=>	'Punctuality of driver',
				'knowledge'=>	'Knowledge of driver (as related to street routes)',
				'condition1'=>	'Condition of car',
				'comfort'=>	'Comfort of ride',
				'use_again'=>	'Would use again',
				'would_recommend'=>	'Would recommend',
				'comments'=>	'Comments',
				'name'=>	'Name',
				'email'=>	'Email',
				'phone'=>	'Phone'
			);
	}

	function status_array() {
		return array(	'new'=>		'New',
				'inprogress'=>	'In Progress',
				'resolved'=>	'Resolved',
				'nofix'=>	'Will not be fixed'
				);
	}

	/*
	* Print an array as a select menu with one item already selected
	* if name is passed in print the opening and closing select tags
	*/
	function print_dropdown($array, $match = null, $name = null, $class = null, $function = '', $id = null, $disabled = false) {

		$class = $class ? ' class="'.$class.'"' : '';
		$id = $id ? ' id="'.$id.'"' : '';
		if ($disabled) {
			$disabled = " disabled";
		} else {
			$disabled = "";
		}
		if ($name) echo "<select name=\"$name\"$id$class $function$disabled>\n";
		foreach ($array as $k => $v) {
			$selected = $k == $match ? ' selected' : '';
			echo "<option value=\"$k\"$selected>$v</option>\n";
		}
		
		if ($name) echo "</select>";
	}

	/*
	* Return an array of month values counting from the current month
	* $months the number of months to return
	* $blankfirstrow insert a blank element at beginningof array
	*/
	function get_month_array($months, $blankFirstRow = true) {
		$return = array();
		if ($blankFirstRow) $return[''] = '';
		for ($i=0; $i<$months; $i++) {
			$index = date('n')+$i;
			$val = date("M", mktime(0,0,0, date('n')+$i, date("j"), date("Y")));
			$return[$index] = $val;
		}
		return $return;
	}
	function get_day_array($days = 32, $blankFirstRow = true) {
		$return = array();
		if ($blankFirstRow) $return[''] = '';
		for ($i=1; $i<$days; $i++) {
			$return[$i] = $i;
		}
		return $return;
	}
	function get_year_array($years, $blankFirstRow = false) {
		$return = array();
		if ($blankFirstRow) $return[''] = '';
		for ($i=0; $i<$years; $i++) {
			$index = date('Y')+$i;
			$val = date("Y", mktime(0,0,0, date('n'), date("j"), date("Y")+$i));
			$return[$index] = $val;
		}
		return $return;
	}

	/*
	* Return memberid for that category if there's a match
	* -the return value can be directly inserted into the db
	*/
	function get_auto_assign($category) {
		if ($category == 'billing') {
			return 'glb461aff8158a7c'; // Marilyn
		} else if ($category == 'cs') {
			return 'glb4716506e587ee'; // Nancy
		} else if ($category == 'dispatch') {
			return 'ssk425bec50e30e9'; // Seth
		} else if ($category == 'driver') {
			return 'glb4cffa46631103'; // jknight
		} else if ($category == 'feedback') {
			return 'glb4d026425ca3e4'; // lvandam
			//return 'ssk425bec50e30e9'; // Seth
			//return 'glb4ac35a7de947e'; // Sarah
		} else if ($category == 'fleet') {
			return 'glb4b42064417a1f'; // Scott
		} else if ($category == 'system') {
			return 'glb449994bd9c421'; // Matt
		}

		return null;
	}

	/**
	* Return name by memberid
	*/
	function get_name($memberid) {
		$query = "select fname, lname from login where memberid='$memberid'";
		$qresult = mysql_query($query);
		$row = mysql_fetch_assoc($qresult);
		return $row['fname']." ".$row['lname'];
	}
	/**
	* Return a simple "Back" link
	*/
	function backlink($page, $text = '') {
		if (!$text) $text = 'Back';
		return "<a href=\"$page\">$text</a>";	
	}

	/**
	* Return array of drivers. Key is their memberid
	*/
	function get_drivers($key = 'memberid') {
		$query = "select c.id, c.code, l.memberid
			  from codes c left join dinfo d on d.id=c.id
			  join login l on l.memberid=d.memberid
			  where c.category='driver' and c.status='active' and c.area<>'NA'
			  order by c.code";
		$qresult = mysql_query($query);
		$return = array();
		while ($row = mysql_fetch_assoc($qresult))
			$return[$row[$key]] = stripslashes($row['code']);
		return $return;
	}
	function get_employees() {
		$query = "select fname, lname, memberid from login where role='m'
			  order by fname";
		$qresult = mysql_query($query);
		$return = array();
		while ($row = mysql_fetch_assoc($qresult))
			$return[$row['memberid']] = $row['fname'].' '.$row['lname'];
		return $return;
	}
	function get_oti_reporters() {
		$query = "select fname, lname, memberid from login where role='o'
			  order by fname";
		$qresult = mysql_query($query);
		$return = array();
		while ($row = mysql_fetch_assoc($qresult))
			$return[$row['memberid']] = $row['fname'].' '.$row['lname'];
		return $return;
	}

	/*
	* Insert an OTI that will only have a single issue flag
	* (from a drop-down manu, not checkboxes). Must not echo anything
	* $d DBEngine link
	*/
	function insert_single_oti($d, $data) {
		$resid 	= 	$data['resid'];
		$issues = 	$data['issues'];
		$notes = 	$data['notes'];
		$managerid = 	$data['managerid'];
		$category = 	$data['category'];	
		$fromsystem = 	$data['fromsystem'];
		$assignTo = 	$this->get_auto_assign($category);
		$status = 'new';
		$vals = array ($resid,$managerid,$issues,$notes,$category,$fromsystem,$assignTo,$status);
		$query = "insert into oti (resid,managerid,driver,issues,notes,time,open,closedby,category,fromsystem,assignedTo,status)
			  values (?,?,NULL,?,?,NOW(),1,NULL,?,?,?,?)";
		$q = $d->db->prepare($query);
		$result = $d->db->execute($q, $vals);
		$d->check_for_error($result);

		$id = mysql_insert_id();
		return $id;
	}

	/*
	* Insert an OTI that can have multiple issue flags
	* issues is binary issues array
	* requires $data: resid, managerid, category, fromsystem
	* 	-driver, notes optional
	*/
	function insert_multiple_oti($d, $data, $issues_list) {
		$issues = 0;
		foreach ($_POST as $k => $v) {
			if (isset($issues_list[$k])) $issues += $issues_list[$k];
		}

		$resid 	= 	$data['resid'];
		$managerid = 	$data['managerid'];
		$driver = 	$data['driver'] ? $data['driver'] : null;
		// issues set above
		$notes = 	$data['notes'] ? $data['notes'] : null;
		// time set manually
		// open is 1 since this is a new issue
		// closedby is null, it's open
		$category = 	$data['category'];	
		$fromsystem = 	$data['fromsystem'];
		$assignTo = 	$this->get_auto_assign($category);
		$status = 'new';

		$vals = array ($resid,$managerid,$driver,$issues,$notes,$category,$fromsystem,$assignTo,$status);
		$query = "insert into oti (resid,managerid,driver,issues,notes,time,open,closedby,category,fromsystem,assignedTo,status)
			  values (?,?,?,?,?,NOW(),1,NULL,?,?,?,?)";

		$q = $d->db->prepare($query);
		$result = $d->db->execute($q, $vals);
		$d->check_for_error($result);

	}
	
	// Put this near the top of the page where calendars will be appearing,
	// above the calendar links
	function doCalendarHeader() {
	   ?>
	<script language="JavaScript" src="calendar2.js"></script>
	   <?

	}
	
	/*
	* $element in this format: 
	*	document.forms['formname'].elements['fieldname']
	*/
	function doCalendarCode($form, $field, $calname) {
		$calname = 'cal'.$calname;
		?>
	   <a href="javascript:<?=$calname?>.popup();"><img src="img/cal.gif" width="16" height="16" border="0" alt="Click Here to Pick the Date"></a>
	<script language="JavaScript">
		<!-- 
			var <?=$calname?> = new calendar2(document.forms['<?=$form?>'].elements['<?=$field?>']);
			<?=$calname?>.year_scroll = true;
			<?=$calname?>.time_comp = false;
			//-->
		</script>

		<?
	}

	/*
	* Return array of car types
	*/
	function car_select_array() {
		return array(	'P'=>	'Standard (Prius)',
				'V'=>	'Prius V (+$15)',
				'C'=>	'Camry (+$15)',
				'L'=>	'Luxury (Lexus HS Sedan [+$30])',
				'S'=>	'SUV (Highlander or Lexus [+$30])',
				//'N'=>	'9-Passenger van ($75/hr, 2 hr minimum)'
			);
	}


	function car_select_details() {
		return array(	'P'=>	array('name'=>'Standard (Prius)','select_name'=>'Standard (Prius)','seats'=>4,'suitcases'=>'3 large','price'=>0,'img'=>'vehicle_preview_prius.jpg', 'vehicle_type'=>'P', 'price_hr'=> 0),
				'V'=>	array('name'=>'Prius V','select_name'=>'Prius V (+$15)','seats'=>4,'suitcases'=>'4 large','price'=>15,'img'=>'vehicle_preview_priusV.jpg', 'vehicle_type'=>'W', 'price_hr'=> 5),
				'C'=>	array('name'=>'Camry','select_name'=>'Camry (+$15)','seats'=>4,'suitcases'=>'3 medium','price'=>15,'img'=>'vehicle_preview_camry.jpg', 'vehicle_type'=>'Y', 'price_hr'=> 5),
				'S'=>	array('name'=>'SUV (Highlander or Lexus)','select_name'=>'SUV (Highlander or Lexus [+$30])','seats'=>4,'suitcases'=>'4 large','price'=>30,'img'=>'vehicle_preview_highlander.jpg', 'vehicle_type'=>'S', 'price_hr'=> 10),
				'L'=>	array('name'=>'Luxury (Lexus HS Sedan)','select_name'=>'Luxury (Lexus HS Sedan [+$30])','seats'=>3,'suitcases'=>'3 medium' ,'price'=>30,'img'=>'vehicle_preview_luxorylexus.jpg', 'vehicle_type'=>'L', 'price_hr'=> 10,
					      'extra'=>'<strong>(Massachusetts only)</strong>'),
				//'N'=>	'9-Passenger van ($75/hr, 2 hr minimum)'
			);
	}
	/*
	* Return array of car types
	*/
	function car_select_array_prices() {
	    $a = array();
	    foreach($this->car_select_array() as $k=>$v) {
	      $pieces = explode('(', $v);
	      $price = trim(end(explode('+', $pieces[1])), ' ])+$');
	      
	      if(!is_numeric($price)) $price = '0';
	      
	      $a[$k] = array('name' => trim($pieces[0]), 'price' => $price);
	    }
	    
	    return $a;
	}
	/*
	* child seat types
	*/
	function seat_select_array() {
		return  array(	''=>	'None',
				'I'=>	'Infant Seat [+$5]',
				'T'=>	'Toddler Seat [+$5]',
				'O'=>	'Booster Seat [+$5]'
			);
	}

	/*
	* Car delay
	*/
	function delay_array() {
		return array(	0 =>	'On time',
				5 =>	'5 minute delay',
				10 =>	'10 minute delay',
				15 =>	'15 minute delay',
				20 =>	'20 minute delay',
				30 =>	'Please call dispatch');
	}

	function add_favorite_driver($d, $memberid, $driver) {
		$vals = array($memberid, $driver);
		$query = "insert into favorite_drivers (memberid, driver)
			  values (?, ?)";

		$q = $d->db->prepare($query);
		$result = $d->db->execute($q, $vals);
		$d->check_for_error($result);
	}

	/* Return array of shift types for driver login */
	function shift_types() {
		return array(	''=>	"Select shift type",
				1=>	"Start Shift",
				2=>	"End Shift",
				4=>	"Start Break",
				8=>	"End Break");
	}

	function existing_shift_types() {
		return array(	
				1=>	"Open shift",
				2=>	"No open shift",
				4=>	"Open break",
				8=>	"No open break");
	}

	/* get array of cars */
	function get_car_list($area=null, $checkedin=true, $type='road') {
		$areastr = $area ? "and area='$area'" : '';
	
		if ($checkedin) {
			$checkedinstr = "and id not in (select car from driver_log where clockout is null)
			  and id not in (select break_car from driver_log where break_car is not null and clockout is null)";
		} else
			$checkedinstr = '';
		$query = "select id, code code from codes
			  where category='vehicle' and status='active'
			  $checkedinstr
			  $areastr
			  order by code";
		$qresult = mysql_query($query);
		$return = array();
		while($row = mysql_fetch_assoc($qresult))
			$return[] = $row;
		return $return;
	}

	function rowcolor($counter) {
		if ($counter % 2) return "#FFFFFF";
		else return "#EEFEFE";
	}

	function get_employee_types() {
		return array(	'Driver'=>'Driver',
				'Dispatch'=>'Dispatch',
				'Shuttle'=>'Shuttle',
				'CstSvc'=>'CstSvc');
	}

	function get_authWaitTimes() {
		return array(
				'90'=>	'1.5 hours',
				'120'=>	'2 hours',
				'180'=> '3 hours',
				'240'=>	'4 hours',
				'300'=> '5+ hours');
	}
}
