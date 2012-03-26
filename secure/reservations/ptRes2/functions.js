// Last modified: 08-18-04

function checkForm(f) {
	var msg = "Please fix these errors:\n";
	var errors = false;
	
	if (f.fname.value == "") {
		msg+="-First name is required\n";
		errors = true;
	}
	if (f.lname.value == "") {
		msg+="-Last name is required\n";
		errors = true;
	}
	if (f.phone.value == "") {
		msg+="-Phone number is required\n";
		errors = true;
	}
	if (f.institution.value == "") {
		msg+="-Institution is required\n";
		errors = true;
	}
	if ( (f.email.value == "") || ( f.email.value.indexOf('@') == -1) ) {
		msg+="-Valid email is required\n";
		errors = true;
	}
	if (errors) {
		window.alert(msg);
		return false;
	}
		
	return true;
}

function verifyEdit() {
	var msg = "Please fix these errors:\n";
	var errors = false;
	
	if ( (document.register.email.value != "") && ( document.register.email.value.indexOf('@') == -1) ) {
		msg+="-Valid email is required\n";
		errors = true;
	}
	if ( (document.register.password.value != "") && (document.register.password.value.length < 6) ) {
		msg+="-Min 6 character password is required\n";
		errors = true;
	}
	if ( (document.register.password.value != "") && (document.register.password.value != document.register.password2.value) ) {
		msg+=("-Passwords to not match\n");
		errors = true;
	}
	if (errors) {
		window.alert(msg);
		return false;
	}
		
	return true;
}

function checkBrowser() {
	if ( (navigator.appName.indexOf("Netscape") != -1) && ( parseFloat(navigator.appVersion) <= 4.79 ) ) {
		newWin = window.open("","message","height=200,width=300");
		newWin.document.writeln("<center><b>This system is optimized for Netscape version 6.0 or higher.<br>" +
					"Please visit <a href='http://channels.netscape.com/ns/browsers/download.jsp' target='_blank'>Netscape.com</a> to obtain an update.");
		newWin.document.close();
	}
}

function help(file) {    
		window.open("help.php#" + file ,"","width=500,height=500,scrollbars");    
		void(0);    
}      

function schedule(type, machid, ts, resid, scheduleid, read_only) {  
		w = (type == 'r') ? 425 : 425;
		h = (type == 'm') ? 600 : 500;

		nurl = "newschedule.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=0&read_only=" + read_only;    
		window.open(nurl,"schedule","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
		void(0);    
}

function reserve(type, machid, ts, resid, scheduleid, read_only) {  
		w = 450;
		h = 700;
		
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		
		nurl = "view_reservation.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=0&read_only=" + read_only;    
		window.open(nurl,"reserve","width=" + w + ",height=" + h + ",top=" + top + ",left=" + left + ", scrollbars,resizable=no,status=no");     
		void(0);    
}
function paymentPopup(memberid, mode) {  
		w = 450;
		h = 600;

		p = document.getElementById("paymentProfileId");
		if(mode != 'add')
			pval = p.options[p.selectedIndex].value;

		if (mode == 'edit' || mode == 'delete') {

			if (pval == "00") {
				alert("Corporate billing options cannot be edited by users. Please contact your administrator for questions regarding your corporate payment options.");
				void(0);
				return;
			} else if (pval == "") {
				alert("Please select a payment option to edit or delete.");
				void(0);
				return;
			}

		}

		if(mode != 'add'){
			nurl = "AuthGateway.php?memberid="+memberid+"&mode="+mode+"&paymentProfileId="+p.options[p.selectedIndex].value;    
		}
		else
		{
			nurl = "AuthGateway.php?memberid="+memberid+"&mode="+mode;
		}
		
		window.open(nurl,"paymentInfo","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
		void(0);    
}

function locate(type, machid, ts, resid, scheduleid, read_only) {  
		w = (type == 'r') ? 600 : 425;
		h = (type == 'm') ? 600 : 500;
		
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);

		nurl = "location.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=0&read_only=" + read_only;    
		window.open(nurl,"location","width=" + w + ",height=" + h + ",top=" + top + ", left=" + left +",scrollbars,resizable=no,status=no");     
		void(0);    
}

function blackout(type, machid, ts, resid, scheduleid) {  
		w = (type == 'r') ? 600 : 425;
		h = (type == 'm') ? 450 : 370;

		nurl = "reserve.php?type=" + type + "&machid=" + machid + "&ts=" + ts + "&resid=" + resid + '&scheduleid=' + scheduleid + "&is_blackout=1";    
		window.open(nurl,"reserve","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
		void(0);    
}

function checkDate() {
	var formStr = document.jumpWeek;
	var dayNum = new Array();
	dayNum = [31,28,31,30,31,30,31,31,30,31,30,31];
	
	if ( (formStr.jumpMonth.value > 12) || (formStr.jumpDay.value > dayNum[formStr.jumpMonth.value-1]) ) {
		alert("Please enter valid date value");
		return false;
	}
	
	for (var i=0; i< formStr.elements.length-1; i++) {
		if (formStr.elements[i].type == "text" || formStr.elements[i].type == "textbox" ) {			
			if ( (formStr.elements[i].value <= 0) || (formStr.elements[i].value.match(/\D+/) != null) ) {
					alert("Please enter valid date value");
					formStr.elements[i].focus();
					return false;
			}
		}
	}
}

function verifyTimes(f) {
	if (f.del && f.del.checked) {
		return confirm("Delete this reservation?");
	}
	if (parseFloat(f.startTime.value) < parseFloat(f.endTime.value)) {
		return true;
	}
	else {
		window.alert("End time must be later than start time\nCurrent start time: " + f.startTime.value + " Current end time: " + f.endTime.value);
		return false;
	}
}

function checkAdminForm() {
	var f = document.forms[0];
	for (var i=0; i< f.elements.length; i++) {
		if ( (f.elements[i].type == "checkbox") && (f.elements[i].checked == true) )
			return confirm('This will delete all reservations and permission information for the checked items!\nContinue?');
	}
	alert("No boxes have been checked!");	
	return false;
}

function checkBoxes() {
	var f = document.train;
	for (var i=0; i< f.elements.length; i++) {
		if (f.elements[i].type == "checkbox")
			f.elements[i].checked = true;
	}
	void(0);
}

function viewUser(user) {
	window.open("userinfo.php?user="+user,"UserInfo","width=400,height=400,scrollbars,resizable=no,status=no");     
		void(0);    
}

function checkAddResource(f) {
	var msg = "";
	minRes = (parseInt(f.minH.value) * 60) + parseInt(f.minM.value);
	maxRes = (parseInt(f.maxH.value) * 60) + parseInt(f.maxM.value);
	
	if (f.name.value=="")
		msg+="-Resource name is required.\n";
	if (parseInt(minRes) > parseInt(maxRes))
		msg+="-Minimum reservaion time must be less than or equal to maximum";
	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAddSchedule() {
	var f = document.addSchedule;
	var msg = "";
	
	if (f.scheduleTitle.value=="")
		msg+="-Schedule title is required.\n";
	if (parseInt(f.dayStart.value) > parseInt(f.dayEnd.value))
		msg+="-Invalid start/end times.\n";
	if (f.viewDays.value == "" || parseInt(f.viewDays.value) <= 0)
		msg+="Invalid view days.\n";
	if (f.dayOffset.value == "" || parseInt(f.dayOffset.value) < 0)
		msg+="Invalid day offset.\n";
	if (f.adminEmail.value == "")
		msg+="Admin email is required.\n";

	if (msg!="") {
		alert("You have the following errors:\n\n"+msg);
		return false;
	}
	
	return true;
}

function checkAllBoxes(box) {
    var f = document.forms[0];
	
	for (var i = 0; i < f.elements.length; i++) {
		if (f.elements[i].type == "checkbox" && f.elements[i].name != "notify_user")
			f.elements[i].checked = box.checked;
	}

	void(0);
}

function check_reservation_form(f) {
	
	var recur_ok = false;
	var days_ok = false;
	var is_repeat = false;
	var msg = "";
	
	if (f.interval.value != "none") {
		is_repeat = true;
		if (f.interval.value == "week" || f.interval.value == "month_day") {
			for (var i=0; i < f.elements["repeat_day[]"].length; i++) {
				if (f.elements["repeat_day[]"][i].checked == true)
					days_ok = true;
			}
		}
		else {
			days_ok = true;
		}
		
		if (f.repeat_until.value == "") {
			msg += "- Please choose an ending date\n";
			recur_ok = false;
		}
	}
	else {
		recur_ok = true;
		days_ok = true;
	}
	
	if (days_ok == false) {
		recur_ok = false;
		msg += "- Please select days to repeat on";
	}
	
	if (msg != "")
		alert(msg);

	//alert("test");
		
	return (msg == "");

}

function check_for_delete(f) {
	if (f.del && f.del.checked == true)
		return confirm('Delete this reservation?');
}

function toggle_fields(box) {
	document.forms[0].elements["table," + box.value + "[]"].disabled = (box.checked == true) ? false : "disabled";
}

function search_user_lname(letter) {
	var frm = isIE() ? document.name_search : document.forms['name_search'];
	frm.firstName.value = "";
	frm.lastName.value=letter;
	frm.submit();
}

function isIE() {
	return document.all;
}

function changeDate(month, year) {
	var frm = isIE() ? document.changeMonth : document.forms['changeMonth'];
	frm.month.value = month;
	frm.year.value = year;
	frm.submit();
}

// Function to change the Scheduler on selected date click
function changeScheduler(m, d, y, isPopup, scheduleid) {
	newDate = m + '-' + d + '-' + y;
	keys = new Array();
	vals = new Array();

	// Get everything up to the "?" (if it even exists)
	var queryString = (isPopup) ? window.opener.document.location.search.substring(0): document.location.search.substring(0);
	var pairs = queryString.split('&');
	var url = (isPopup) ? window.opener.document.URL.split('?')[0] : document.URL.split('?')[0];
	
	if (scheduleid == "") {
	
		for (var i=0;i<pairs.length;i++)
		{
			var pos = pairs[i].indexOf('=');
			if (pos >= 0)
			{
				var argname = pairs[i].substring(0,pos);
				var value = pairs[i].substring(pos+1);
				keys[keys.length] = argname;
				vals[vals.length] = value;		
			}
		}
		
		for (i = 0; i < keys.length; i++) {
			if (keys[i] == "scheduleid")
				scheduleid = vals[i];
		}
	}
	
	if (isPopup)
		window.opener.location = url + "?date=" + newDate + "&scheduleid=" + scheduleid;
	else
		document.location.href = url + "?date=" + newDate + "&scheduleid=" + scheduleid;
	
}

function showSummary(object, e, text) {
	myLayer = document.getElementById(object);
	myLayer.innerHTML = text;
	
	w = parseInt(myLayer.style.width);
	h = parseInt(myLayer.style.height);

    if (e != '') {
        if (isIE()) {
            x = e.clientX;
            y = e.clientY;
            browserX = document.body.offsetWidth - 25;
			x += document.body.scrollLeft;			// Adjust for scrolling on IE
    		y += document.body.scrollTop;
        }
        if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
            browserX = window.innerWidth - 35;
        }
    }
	
	x1 = x;		// Move out of mouse pointer
	y1 = y + 20;
	
	// Keep box from going off screen
	if (x1 + w > browserX)
		x1 = browserX - w;
    
    myLayer.style.left = parseInt(x1)+ "px";
    myLayer.style.top = parseInt(y1) + "px";
	myLayer.style.visibility = "visible";
}

function moveSummary(object, e) {

	myLayer = document.getElementById(object);
	w = parseInt(myLayer.style.width);
	h = parseInt(myLayer.style.height);

    if (e != '') {
        if (isIE()) {
            x = e.clientX;
            y = e.clientY;
			browserX = document.body.offsetWidth -25;
			x += document.body.scrollLeft;
			y += document.body.scrollTop;
        }
        if (!isIE()) {
            x = e.pageX;
            y = e.pageY;
			browserX = window.innerWidth - 30;
        }
    }

	x1 = x;	// Move out of mouse pointer	
	y1 = y + 20;
	
	// Keep box from going off screen
	if (x1 + w > browserX)
		x1 = browserX - w;

    myLayer.style.left = parseInt(x1) + "px";
    myLayer.style.top = parseInt(y1) + "px";
}

function hideSummary(object) {
	myLayer = document.getElementById(object);
	myLayer.style.visibility = 'hidden';
}

function resOver(cell, color) {
	cell.style.backgroundColor=color;
	cell.style.cursor='hand'
}

function resOut(cell, color) {
	cell.style.backgroundColor = color;
}

function showHideDays(opt) {
	e = document.getElementById("days");
	
	if (opt.options[2].selected == true || opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
	
	e = document.getElementById("week_num")
	if (opt.options[4].selected == true) {
		e.style.visibility = "visible";
		e.style.display = isIE() ? "inline" : "table";
	}
	else {
		e.style.visibility = "hidden";
		e.style.display = "none";
	}
}

function chooseDate(input_box, m, y) {
	var file = "recurCalendar.php?m=" + m + "&y="+ y;
	if (isIE()) {
		yVal = "top=" + 200;
		xVal = "left=" + 500;
	}
	if (!isIE()) {
		yVal = "screenY=" + 200;
		xVal = "screenX=" + 500
	}
	window.open(file, "calendar",yVal + "," + xVal + ",height=270,width=220,resizable=no,status=no,menubar=no");
	void(0);
}

function selectRecurDate(m, d, y, isPopup) {
	f = window.opener.document.forms[0];
	f._repeat_until.value = m + "/" + d + "/" + y;
	f.repeat_until.value = f._repeat_until.value;
	window.close();
}

function setSchedule(sid) {
	f = document.getElementById("setDefaultSchedule");
	f.scheduleid.value = sid;
	f.submit();
}

function changeSchedule(sel) {
	var url = document.URL.split('?')[0];
	document.location.href = url + "?scheduleid=" + sel.options[sel.selectedIndex].value;
}

function showHideCpanelTable(element) {
	var expires = new Date();
	var time = expires.getTime() - 2592000000;
	expires.setTime(time);
	var showHide = "";
	if (document.getElementById(element).style.display == "none") {
		document.getElementById(element).style.display='block';
		showHide = "show";
	} else {
		document.getElementById(element).style.display='none';
		showHide = "hide";
	}
	
	document.cookie = element + "=" + showHide + ";expires=" + expires.toGMTString();
}

function changeLanguage(opt) {
	var expires = new Date();
	var time = expires.getTime() + 2592000000;
	expires.setTime(time);
	document.cookie = "lang=" + opt.options[opt.selectedIndex].value + ";expires=" + expires.toGMTString() + ";path=/";
	document.location.href = document.URL;
}

function confirmEstimate(){

	var backgroundDiv = "<div id='loading_div' style='background: #FFFFFF;position: absolute; top: 0; left: 0; z-index: 1; width: 100%; vertical-align:middle;'><img style='position:absolute;left:50px; top:400px;' src='loading.gif'></img></div>";
		$('body').append(backgroundDiv);
	$('#loading_div').css('height',$(document).height());

	
	var fromID = document.reserve.fromLoc.value;
	var fromLabel = document.reserve.fromLoc[document.reserve.fromLoc.selectedIndex].innerHTML;
	var toID = document.reserve.toLoc.value;
	var toLabel = document.reserve.toLoc[document.reserve.toLoc.selectedIndex].innerHTML;
	var stopChecked = document.reserve.stop.checked;
	var stopID = document.reserve.stopLoc.value;
	var groupid = document.reserve.userGroup.value;
    var hrly=  document.reserve.wait.checked;
    var convertible_seats = document.reserve.convertible_seats.value;
    var booster_seats = document.reserve.booster_seats.value;
    var meet_greet = document.reserve.greet.checked;

    var coupon = document.reserve.coupon ?document.reserve.coupon.value:'';
    var vehicle_type='P';
    
    if(document.reserve.carTypeSelect.value != ""){
        vehicle_type = document.reserve.carTypeSelect.value;
    }
    var trip_type='P';

    if(stopID !=""){
        trip_type = 'I';
    }else if(hrly){
        trip_type = 'H';
    }

	if(document.reserve.stop.checked == false)
	{
			stopID = "";
	}	
   
    if(fromID == '' || toID == '')
    {
    	return;
    }

	sfp = "fromID=" + fromID +
        "&toID=" + toID +
        "&stopID=" + stopID +
        "&groupid=" + groupid +
        "&trip_type=" + trip_type +
        "&vehicle_type=" + vehicle_type +
        "&convertible_seats=" + convertible_seats +
        "&booster_seats=" + booster_seats +
        "&meet_greet=" + meet_greet +
        "&coupon=" + coupon;

    $.ajax({
      url: "ajaxquote.php",
      cache: false,
      data: sfp,
      type: "GET",
      dataType: "text",
      error: function(XMLHttpRequest, textStatus, errorThrown){
            
    	  	$('#loading_div').remove();
    	    alert(textStatus);
            return false;
            
        },

      success: function(data, textStatus, xhr ){
              var data_parts = data.split("|");
              
              $('#loading_div').remove();
              
            if(data_parts[0] > 0){
            	
            	$('#estimate_value').val(data_parts[0]); 
		    	Boxy.save_confirm(" <div class='bodytext' align='center'><b style='font-size: large;'>Estimated flat-rate fare: $" + data_parts[0] + "</b>	</div>	<div class='bodytext' align='left'> <br> <b>Details:</b> <ul> <li>Pick-Up Location: " + fromLabel + ", " + data_parts[1] + "</li> <li>Drop-Off Location: " + toLabel + ", " + data_parts[2] + "</li> </ul> Additional Information: 	<ul> <li>The quote is based on distance and does not include applicable wait time.</li> <li>The quote does not include vehicle upgrade charges or charges for infant or booster seats. Fares include tolls. Airport fees are NOT included.</li> <li>PlanetTran does not charge fuel surcharges.</li> <li>Tips are neither expected nor included in our flat-rate pricing.</li></ul>", function() {$('#real_submit').trigger('click'); }, {title: 'Message'});
    			return false; //$('#reserve').submit();
		    }
		    else
		    {
		    	Boxy.confirm("<div class='bodytext' align='center'>We were unable to automatically generate a quote for your locations. Email us at <a href='mailto:customerservice@planettran.com'>customerservice@planettran.com</a>, or call 888-756-8876 (press option 2).  Thanks for your patience and cooperation. </div> ", function() {$('#real_submit').trigger('click'); }, {title: 'Message'});
    			return false; //$('#reserve').submit();		    	
		    }

            
         }
    });

//    return true;
}



function checkFields() {
	missinginfo = "";
	confirmMessage = "";
	var radioChecked = false;
	var greet = document.getElementById('greet');
	
	for (i=0; i < document.reserve.ampm.length; i++) {
		if (document.reserve.ampm[i].checked)
			radioChecked = true;
	}
	if (!radioChecked) {
		missinginfo = "Please specify AM or PM.";
	}
	if (document.reserve.startTime.value == "") {
		missinginfo = "Please enter a time.";
	}
	if (document.reserve.date.value == "") {
		missinginfo = "Please enter a date.";
	}


	p = document.reserve.paymentProfileId;
	pval = p.options[p.selectedIndex].value;

	// Check for cost center on direct bill reservations
	if (document.reserve.billcheck.value == "1" && document.reserve.cccode.value == "") {
		if (pval == '00') {
			missinginfo = "Please enter cost or project code.";
		}
	}

	// Check that some sort of payment was entered
	if (pval == '') {
		missinginfo = "Please select or add a payment option.";
	}
	

 	re = /^\d{1,2}\/\d{1,2}\/\d{4}$/;
	if (document.reserve.date.value != '' && !document.reserve.date.value.match(re)) {
		missinginfo = "Please enter a valid date in the format mm/dd/yyyy.";
	}
	if (document.reserve.toLoc.value == document.reserve.fromLoc.value) {
		s = document.reserve.stopLoc;
		o = document.reserve.wait;
		if (s.selectedIndex == 0 && !o.checked) {
			missinginfo = "Your From and To locations are the same; please modify your selection or add an intermediate stop.\n\n(If your destination is not listed, add it by clicking Create New Location)";
		}
	}
	if (document.reserve.toLoc.value == "") {
		missinginfo = "Please enter a destination (to) location.";
	}
	if (document.reserve.fromLoc.value == "") {
		missinginfo = "Please enter a pickup (from) location.";
	}

	/*
	if (document.reserve.billtype.value == "g") {
		if (document.reserve.ccnum.value == "" || document.reserve.expdate.value == "" || document.reserve.pname.value == "") {
			missinginfo = "Please enter a name, credit card number and expiration date.";
		}
	}
	*/

	pax = document.reserve.pax;
	ctype = document.reserve.carTypeSelect;
	cval = ctype.options[ctype.selectedIndex].value;

	cseats = document.reserve.convertible_seats.selectedIndex;
	bseats = document.reserve.booster_seats.selectedIndex;

	/* check for more than 4 passengers in non-van trips */
	if (pax.selectedIndex > 3 && cval != "N") {
		//pax.selectedIndex = 3;
		//missinginfo = "All vehicles except for Vans have a maximum capacity of 4 passengers. Please book additional vehicles or a van (MA only) to accomodate more passengers.";
		missinginfo = "All vehicles have a maximum capacity of 4 passengers. Please book additional vehicles or a van (MA only) to accomodate more passengers.";
	}

	/* Check for more than 3 child seats total */

	if (cseats + bseats > 3 && cval != "N") {
		//missinginfo = "You cannot fit more than 3 child seats in this type of vehicle. Please complete this reservation then book another to accomodate your party in multiple vehicles, or upgrade to our 9-passenger van.";
		missinginfo = "You cannot fit more than 3 child seats in this type of vehicle. Please complete this reservation then book another to accomodate your party in multiple vehicles.";
	}

	/* Check for any child seats at all */
	if (cseats + bseats > 0) {
		alert("You have included child seats in this reservation. PlanetTran will bring the child seats and instructions for installation, but the customer is responsible for all child seat installations.");
		//confirmMessage += "You have included child seats in this reservation. PlanetTran will bring the child seats and instructions for installation, but the customer is responsible for all child seat installations.";
	}

	var acheck = document.reserve.fromLoc.value.substring(0,7);
	var airport = false;
	if (acheck=='airport')	{
		airport = document.reserve.fromLoc.value;
	} else if (document.reserve.fromLoc.value=='41b40be9091cb'||document.reserve.fromLoc.value=='gzm41b48f6ae8d87') {
		airport = 'airport-BOS';	
	}

	if ((document.reserve.acode.value == "" || document.reserve.fnum.value == "") && airport!==false) {
		missinginfo = "Please enter an airline and flight number.";
	}
	//if (nameCheck())
	//	missinginfo = nameCheck();
	if (phoneCheck())
		missinginfo = phoneCheck();
	if (missinginfo != "") { //die
		alert(missinginfo);
		return false;
	}
	
	/*
	var pax = document.reserve.pax.value;
	if (pax > 4 && !document.reserve.van.checked) {
		confirmMessage += "Our cars and SUVs seat four passengers maximum. For more than four passengers, multiple cars will be sent.";
	}
	*/

	if ((document.reserve.startTime.value >= 1380 && document.reserve.ampm.value == 'pm') || (document.reserve.startTime.value <= 180 && document.reserve.ampm.value == 'am')) {
		if (confirmMessage != "")
			confirmMessage += "\n\n";
		confirmMessage += "You have chosen a time that is either just before, or just after midnight.\nOften, this means that the flight is departing and landing on different dates.\n(Example: a flight departs at 11pm and lands at 2am the next day).\nPlease double check that the date is correct for the selected time.";
	}
	/*
	if (document.reserve.suv.checked || document.reserve.van.checked) {
		if (confirmMessage != "")
			confirmMessage += "\n\n";
		confirmMessage += "Rates for SUVs and vans are higher than for the standard hybrid sedan. Please call for an exact rate quote.\nSUVs and vans are not yet available in the California area.";
	}
	if (document.reserve.toddler.checked || document.reserve.infant.checked) {
		if (confirmMessage != "")
			confirmMessage += "\n\n";
		confirmMessage += "There will be a $5.00 surcharge for infant or toddler seat use.";
	}
	if (document.reserve.multiple.checked) {
		if (confirmMessage != "")
			confirmMessage += "\n\n";
		confirmMessage += "Distance and/or hourly rates apply to multiple stops.";
	}
	if (greet && greet.checked) {
		if (airport == 'airport-BOS') {
			if (confirmMessage != "")
				confirmMessage += "\n\n";
			confirmMessage += "There is an additional $30.00 charge for meet-and-greets at Boston Logan Airport.";
		}
	}
	*/
	
	//confirmMessage = "test";
	if (confirmMessage != "") {
		//var lateCheck = confirm(confirmMessage);
		return confirm(confirmMessage);
	}
	
	if(confirmEstimate() == false)
		return false;
	
	return true;
}
function isAirport(machid) {
	if (!machid) return false;
	var acheck = machid.substring(0,7);
	if (acheck=='airport')	{
		return machid;	
	} else if (machid=='41b40be9091cb' || machid=='gzm41b48f6ae8d87') {
		return 'airport-BOS';	
	}
	return false;
}
function phoneCheck() {
	var err = "Please enter a valid telephone number (at least 10 digits).";
	if (!document.reserve.cphone || !document.reserve.pname)
		return false;
	var name  = document.reserve.pname.value;
	if (name.length==0)  // If no name was entered, move along
		return false;
	var phone = document.reserve.cphone.value;
	if (phone.length==0) // If nothing was entered, move along
		return err;
	var valid = "0123456789";
	var temp;
	var count = 0;
	for (i=0;i<phone.length;i++) {
		temp = "" + phone.substring(i, i+1);
		if (valid.indexOf(temp) != "-1")
			count++;
	}
	if (count>=10) // if anything was entered, it needs 10 digits or more
		return false;
	else
		return err;
}
function nameCheck() {
	var name = document.reserve.pname.value;
	var r = 0;
	cleanName = name.replace(/\s/g, ' ');
	cleanName = cleanName.split(' ');
	for (i=0; i<cleanName.length; i++) {
		if (cleanName[i].length > 0)
			r++;
	}
	if (r>5)
		return "Please enter only the passenger's name into the Passenger Name field."; 
	else
		return false;
}

function meetGreetCheck(where) {
	return;
	if (!where || where != 'from') return;

	var from = document.reserve.fromLoc.value;
	var airport = isAirport(from);
	var greetDiv = document.getElementById('greetDiv');
	var greetMsg = document.getElementById('greetMsg');
	if (!airport) { 
		greetMsg.innerHTML = '';
		greetDiv.style.display = 'none';
		return;
	}

	if (airport == 'airport-BOS') {
		greetDiv.style.display = 'block';
		greetMsg.innerHTML = "Curbside pickup is the default for Logan Airport. Meet and Greet at baggage claim is available for an additional $30.";
	} else if (airport == 'airport-SFO') {
		greetDiv.style.display = 'block';
		greetMsg.innerHTML = "Curbside pickup is the default for SFO. Meet and Greet at baggage claim is available, and for a limited time is free.";
	} else {
		greetDiv.style.display = 'none';
	}
}

// Wrapper function for when authWaitCheck is called by selecting the van
function vanCheck() {
		c = document.getElementById("carTypeSelect");
		cval = c.options[c.selectedIndex].value;
		list = document.getElementById("authWait");

		if (cval == 'N') {
		document.reserve.wait.checked = true;
		document.reserve.wait.disabled = true;
		list.options[1].selected = true;
		authWaitCheck();
	} else {
		document.reserve.wait.disabled = false;
		document.reserve.wait.checked = false;
		authWaitCheck();
	}

}

// Wrapper function for when authWaitCheck is called from a menu selection
function authWaitWrapper(where) {
	if (!where || where != 'to') return;
	var toMachid = document.reserve.toLoc.value;
	if (toMachid == 'asDirectedLoc') {
		document.reserve.wait.checked = true;
		document.reserve.wait.disabled = true;
		authWaitCheck();
	} else {
		document.reserve.wait.disabled = false;
		document.reserve.wait.checked = false;
		authWaitCheck();
	}
}
function authWaitCheck() {
	var authDiv = document.getElementById('waitDiv');

	if (document.reserve.wait.checked == true) {
		//alert("Reserve by the hour to direct your driver for a period of time or to many intermediate stops. These trips must be a minimum of 90 minutes in length and are billed at the following rates:\n\nPrius $60 per hour\nSUV $70 per hour\nLexus Sedan $75 per hour (Mass only)\n9-passenger van $75 per hour (Mass only)\n\nIf the passenger is running late, PlanetTran will wait the entire trip time before creating a no-show billing for the reserved trip.");
		alert("Reserve by the hour to direct your driver for a period of time or to many intermediate stops. These trips must be a minimum of 90 minutes in length and are billed at the following rates:\n\nPrius $60 per hour\nSUV $70 per hour\nLexus Sedan $75 per hour (Mass only)\n\nIf the passenger is running late, PlanetTran will wait the entire trip time before creating a no-show billing for the reserved trip.");
		authDiv.style.display = 'inline';
	} else {
		authDiv.style.display = 'none';
	}
}
function multStopCheck() {
	var waitDiv = document.getElementById('stopDiv');

	if (document.reserve.stop.checked == true) {
		alert("Intermediate Stop trips add $15 plus wait time over 10 minutes at your intermediate stop to the cost of the trip. Reserve by the Hour to make more than one Intermediate Stop.");
		waitDiv.style.display = 'inline';
	} else {
		waitDiv.style.display = 'none';
	}
}
function stopCheck(box, nextid) {
	//var box  = document.getElementById(id);
	var nextDiv = document.getElementById(nextid);
	if (nextDiv == null) return;
	//an error

	if (box.checked == true) {
		nextDiv.style.display = 'block';
	} 
	// no else, leave it visible so that previous stops can be removed
	//else {
	//	nextDiv.style.display = 'none';
	//}
}
function suvCheck (where) {
	var suv = false;
	var van = false;
	var suvArr = new Array('SFO', 'SJC', 'OAK');
	var frCheck = document.reserve.fromLoc.value.substring(8,11);
	var toCheck = document.reserve.toLoc.value.substring(8,11);

	// SUV and van checkboxes no longer exist
	/*

	var suvCheck = document.reserve.suv.checked;
	var vanCheck = document.reserve.van.checked;

	document.reserve.suv.disabled = false;
	document.reserve.van.disabled = false;

	for (var i=0; i<suvArr.length; i++) {
		if ((frCheck==suvArr[i]||toCheck==suvArr[i])&&(suvCheck||vanCheck)) {
			suv = true;
			van = true;
			document.reserve.suv.checked = false;
			document.reserve.suv.disabled = true;
			document.reserve.van.checked = false;
			document.reserve.van.disabled = true;
			break;
		} else if (frCheck==suvArr[i]||toCheck==suvArr[i]) {
			document.reserve.suv.disabled = true;
			document.reserve.van.disabled = true;
			break;
		} 
	}
	*/	

	if(suv || van)
		alert('SUVs and vans are not yet available in the California area.');
	
	meetGreetCheck(where);
	authWaitWrapper(where);
}

function validateLocForm(admin) {
	var valid = "0123456789";
	var validState = new Array('ma','nh','ri','vt','me','ca','ct');
	var missinginfo = "";

	if (document.addResource.name.value == "")
		missinginfo += "Please enter a location name.\n";
	if (document.addResource.address1.value == "")
		missinginfo += "Please enter an address.\n";
	if (document.addResource.city.value == "")
		missinginfo += "Please enter a city.\n";
	if (document.addResource.state.value.length!=2) {
		missinginfo += "Please enter a valid state.\n";
	} else {
		var okState = false;
		if (admin == 'a')
			okState = true;
		for (var x=0; x < validState.length; x++) {
			if (document.addResource.state.value.toLowerCase() == validState[x]) {
				okState = true;
			}
		} 
		if (!okState)
			missinginfo += "Planettran is not currently serving the state of " + document.addResource.state.value + ".\n";
	}	

	if (document.addResource.zip.value.length!=5) {
		missinginfo += "Please enter a valid 5 digit zip code.\n";
	} else {
		for (var i=0; i < document.addResource.zip.value.length; i++) {
			temp = "" + document.addResource.zip.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				missinginfo += "Please enter a valid 5 digit zip code.\n";
				break;
			}
		}
	}
	if (missinginfo != "") { //die
			alert(missinginfo);
			return false;
	} else return true;
}
function checkAddress() {
	var addr = document.addResource.address1.value;
	var city = document.addResource.city.value;
	var state = document.addResource.state.value;
	
	var url = 'checkaddr.php?mode=3&address1=' + addr + '&city=' + city + '&state=' + state;
	var w = 400;
	var h = 500;
  
	window.open(url,"checkaddr","width=" + w + ",height=" + h + ",scrollbars,resizable=no,status=no");     
	//void(0); 
}
function goodZip(mode) {
	var gaddr = document.getElementById('streetaddr').innerHTML;
	var gcity = document.getElementById('ccity').innerHTML;
	var gstate = document.getElementById('cstate').innerHTML;
	var gzip = document.getElementById('czip').innerHTML;
		
	if (mode == 3) {
		window.opener.document.addResource.address1.value = gaddr;
		window.opener.document.addResource.city.value = gcity;
		window.opener.document.addResource.state.value = gstate;
		window.opener.document.addResource.zip.value = gzip;
	} else if (mode == 1) {
		window.opener.document.quickquote.fromAddr.value = gaddr;
		window.opener.document.quickquote.fromCity.value = gcity + " " + gstate;
		window.opener.document.quickquote.fromZip.value = gzip;
	} else if (mode == 2) {
		window.opener.document.quickquote.toAddr.value = gaddr;
		window.opener.document.quickquote.toCity.value = gcity + " " + gstate;
		window.opener.document.quickquote.toZip.value = gzip;
	}
	
	window.close();
}
function ftSynchronize(oChangedElement) {

	var idSource = oChangedElement.id;
	var idTarget;
	idTarget = 'airline-fill';			
	var oSource = document.getElementById(idSource);
	var oTarget = document.getElementById(idTarget);
	
	if (oSource == null) {
		alert('Cannot find source object: ' + idSource);
		return;
	}
	
	if (oTarget == null) {
		alert('Cannot find target object: ' + idTarget);
		return;
	}
	
	ftSynchronizeSelTxt(oSource, oTarget);
}
function ftSynchronizeSelTxt(oElementSource, oElementTarget) {
	if (oElementSource.id.substring(0,3) == 'sel'
			|| oElementSource.id == 'airline-pull') {
		// Source is a select element, target is a text input.
		var selectedOption = oElementSource.options[oElementSource.selectedIndex];
		oElementTarget.value = selectedOption.value;
	}
}
function addApt() {
	var apt = document.reserve.apts.value;
	var response;
	var req = null;

	if(window.XMLHttpRequest) 
		req = new XMLHttpRequest();
	else if (window.ActiveXObject)
		req = new ActiveXObject("Microsoft.XMLHTTP");

	req.open('POST', 'ctrlpnl.php', false);
	req.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	req.send('apts='+apt);
	
	window.location.reload();	
	window.opener.location.reload();	
}

function checkReg(mode) {
	var valid = "0123456789";
	var validexp = "0123456789/";	
	var missinginfo = "";
	var goodcc = document.register.ccnum.value;
	goodcc = goodcc.replace(/[\s-]/g, '');
	var a = 0;
	var admin = false;

	if (document.register.fname.value == "")
		missinginfo += "First name is required.\n";
	if (document.register.lname.value == "")
		missinginfo += "Last name is required.\n";
	if (document.register.phone.value == "")
		missinginfo += "Phone number is required.\n";
	if (document.register.password.value == "" && mode == 'r')
		missinginfo += "Password is required.\n";
	/*
	* Register checks
	*/
	if (document.register.admingroupid) {
		a = document.register.admingroupid.selectedIndex;
		admin = true;
	}
if (mode=='r' && ((!admin && (document.register.groupid.value=='' || document.register.groupid.value==0 || document.register.billtype.value=='c')) || (admin && a<1)) ){ 
	/*
	if (goodcc.length<15||goodcc.length>16) {
		missinginfo += "Please enter a valid credit card.\n";
	} else {
		for (var i=0; i < goodcc.length; i++) {
			temp = "" + goodcc.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				missinginfo += "Please enter a valid credit card.\n";
				break;
			}
		}
	}

	if (document.register.expdate.value.length!=7) {
		missinginfo += "Please enter a valid expiration date, in the format MM/YYYY.\n";
	} else {
		for (var i=0; i < document.register.expdate.value.length; i++) {
			temp = "" + document.register.expdate.value.substring(i, i+1);
			if (validexp.indexOf(temp) == "-1") {
				missinginfo += "Please enter a valid expiration date, in the format MM/YYYY.\n";
				break;
			}
		}
	}
	*/
}
else if (mode=='r' && ((!admin && document.register.billtype.value=='d' && document.register.position.value=='') || (admin && a>0 && document.register.position.value=='')) ) 
	missinginfo += "Please enter a department code.\n";

	/*
	* Edit checks
	* Since the CC field will be blank, only check for a
	* properly formatted CC
	*/
if (document.register.ccnum.value != '' || document.register.expdate.value != '') {
	if (document.register.ccnum.value.length<15||document.register.ccnum.value.length>16) {
		missinginfo += "Please enter a valid credit card.\n";
	} else {
		for (var i=0; i < document.register.ccnum.value.length; i++) {
			temp = "" + document.register.ccnum.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				missinginfo += "Please enter a valid credit card.\n";
				break;
			}
		}
	}
	if (document.register.expdate.value.length!=7) {
		missinginfo += "Please enter a valid expiration date, in the format MM/YYYY.\n";
	} else {
		for (var i=0; i < document.register.expdate.value.length; i++) {
			temp = "" + document.register.expdate.value.substring(i, i+1);
			if (validexp.indexOf(temp) == "-1") {
				missinginfo += "Please enter a valid expiration date, in the format MM/YYYY.\n";
				break;
			}
		}
	}
}
	
	if (missinginfo != "") {
		alert(missinginfo);
		return false;
	}
}

function get_service_region(from_zip, to_zip, airport_code) {

    var region = 1;
    if (from_zip == "CA" || to_zip== "CA" ||
            from_zip == "NV" || to_zip== "NV" ||
            from_zip == "AZ" || to_zip== "AZ" ||
            from_zip == "OR" || to_zip == "OR" ||
            airport_code == "SFO" || airport_code=="SJC" || airport_code == "OAK"){
        region=2;
    }
    return region;
}
function get_state_from_zip(zip) {
	if (zip >= '99501' &&  zip <= '99950'){
		 return 'AK' ;
	} else if (zip >= '35004' &&  zip <= '36925'){
		 return 'AL' ;
	} else if (zip >= '71601' &&  zip <= '72959'){
		 return 'AR' ;
	} else if (zip >= '75502' &&  zip <= '75502'){
		 return 'AR' ;
	} else if (zip >= '85001' &&  zip <= '86556'){
		 return 'AZ' ;
	} else if (zip >= '90001' &&  zip <= '96162'){
		 return 'CA' ;
	} else if (zip >= '80001' &&  zip <= '81658'){
		 return 'CO' ;
	} else if (zip >= '06001' &&  zip <= '06389'){
		 return 'CT' ;
	} else if (zip >= '06401' &&  zip <= '06928'){
		 return 'CT' ;
	} else if (zip >= '20001' &&  zip <= '20039'){
		 return 'DC' ;
	} else if (zip >= '20042' &&  zip <= '20599'){
		 return 'DC' ;
	} else if (zip >= '20799' &&  zip <= '20799'){
		 return 'DC' ;
	} else if (zip >= '19701' &&  zip <= '19980'){
		 return 'DE' ;
	} else if (zip >= '32004' &&  zip <= '34997'){
		 return 'FL' ;
	} else if (zip >= '30001' &&  zip <= '31999'){
		 return 'GA' ;
	} else if (zip >= '39901' &&  zip <= '39901'){
		 return 'GA' ;
	} else if (zip >= '96701' &&  zip <= '96898'){
		 return 'HI' ;
	} else if (zip >= '50001' &&  zip <= '52809'){
		 return 'IA' ;
	} else if (zip >= '68119' &&  zip <= '68120'){
		 return 'IA' ;
	} else if (zip >= '83201' &&  zip <= '83876'){
		 return 'ID' ;
	} else if (zip >= '60001' &&  zip <= '62999'){
		 return 'IL' ;
	} else if (zip >= '46001' &&  zip <= '47997'){
		 return 'IN' ;
	} else if (zip >= '66002' &&  zip <= '67954'){
		 return 'KS' ;
	} else if (zip >= '40003' &&  zip <= '42788'){
		 return 'KY' ;
	} else if (zip >= '70001' &&  zip <= '71232'){
		 return 'LA' ;
	} else if (zip >= '71234' &&  zip <= '71497'){
		 return 'LA' ;
	} else if (zip >= '01001' &&  zip <= '02791'){
		 return 'MA' ;
	} else if (zip >= '05501' &&  zip <= '05544'){
		 return 'MA' ;
	} else if (zip >= '20331' &&  zip <= '20331'){
		 return 'MD' ;
	} else if (zip >= '20335' &&  zip <= '20797'){
		 return 'MD' ;
	} else if (zip >= '20812' &&  zip <= '21930'){
		 return 'MD' ;
	} else if (zip >= '03901' &&  zip <= '04992'){
		 return 'ME' ;
	} else if (zip >= '48001' &&  zip <= '49971'){
		 return 'MI' ;
	} else if (zip >= '55001' &&  zip <= '56763'){
		 return 'MN' ;
	} else if (zip >= '63001' &&  zip <= '65899'){
		 return 'MO' ;
	} else if (zip >= '38601' &&  zip <= '39776'){
		 return 'MS' ;
	} else if (zip >= '71233' &&  zip <= '71233'){
		 return 'MS' ;
	} else if (zip >= '59001' &&  zip <= '59937'){
		 return 'MT' ;
	} else if (zip >= '27006' &&  zip <= '28909'){
		 return 'NC' ;
	} else if (zip >= '58001' &&  zip <= '58856'){
		 return 'ND' ;
	} else if (zip >= '68001' &&  zip <= '68118'){
		 return 'NE' ;
	} else if (zip >= '68122' &&  zip <= '69367'){
		 return 'NE' ;
	} else if (zip >= '03031' &&  zip <= '03897'){
		 return 'NH' ;
	} else if (zip >= '07001' &&  zip <= '08989'){
		 return 'NJ' ;
	} else if (zip >= '87001' &&  zip <= '88441'){
		 return 'NM' ;
	} else if (zip >= '88901' &&  zip <= '89883'){
		 return 'NV' ;
	} else if (zip >= '06390' &&  zip <= '06390'){
		 return 'NY' ;
	} else if (zip >= '10001' &&  zip <= '14975'){
		 return 'NY' ;
	} else if (zip >= '43001' &&  zip <= '45999'){
		 return 'OH' ;
	} else if (zip >= '73001' &&  zip <= '73199'){
		 return 'OK' ;
	} else if (zip >= '73401' &&  zip <= '74966'){
		 return 'OK' ;
	} else if (zip >= '97001' &&  zip <= '97920'){
		 return 'OR' ;
	} else if (zip >= '15001' &&  zip <= '19640'){
		 return 'PA' ;
	} else if (zip >= '02801' &&  zip <= '02940'){
		 return 'RI' ;
	} else if (zip >= '29001' &&  zip <= '29948'){
		 return 'SC' ;
	} else if (zip >= '57001' &&  zip <= '57799'){
		 return 'SD' ;
	} else if (zip >= '37010' &&  zip <= '38589'){
		 return 'TN' ;
	} else if (zip >= '73301' &&  zip <= '73301'){
		 return 'TX' ;
	} else if (zip >= '75001' &&  zip <= '75501'){
		 return 'TX' ;
	} else if (zip >= '75503' &&  zip <= '79999'){
		 return 'TX' ;
	} else if (zip >= '88510' &&  zip <= '88589'){
		 return 'TX' ;
	} else if (zip >= '84001' &&  zip <= '84784'){
		 return 'UT' ;
	} else if (zip >= '20040' &&  zip <= '20041'){
		 return 'VA' ;
	} else if (zip >= '20040' &&  zip <= '20167'){
		 return 'VA' ;
	} else if (zip >= '20042' &&  zip <= '20042'){
		 return 'VA' ;
	} else if (zip >= '22001' &&  zip <= '24658'){
		 return 'VA' ;
	} else if (zip >= '05001' &&  zip <= '05495'){
		 return 'VT' ;
	} else if (zip >= '05601' &&  zip <= '05907'){
		 return 'VT' ;
	} else if (zip >= '98001' &&  zip <= '99403'){
		 return 'WA' ;
	} else if (zip >= '53001' &&  zip <= '54990'){
		 return 'WI' ;
	} else if (zip >= '24701' &&  zip <= '26886'){
		 return 'WV' ;
	} else if (zip >= '82001' &&  zip <= '83128'){
		 return 'WY' ;
	}
		return '';
}

function pop_email(resid, email) {  
		w = 300;
		h = 300;
		
		var left = (screen.width/2)-(w/2);
		var top = (screen.height/2)-(h/2);
		
		nurl = "emailPopup.php?resid=" + resid + "&email=" + email;    
		window.open(nurl,"pop_email","width=" + w + ",height=" + h + ",top=" + top + ",left=" + left + ", scrollbars=no,resizable=no,status=no");     
		void(0);    
}
