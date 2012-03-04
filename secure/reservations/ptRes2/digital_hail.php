<?php

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] .' '. $_GET['lname'];
	}
}
$t = new Template();
$db = new DBEngine();
$memberid= $_SESSION['sessionID'];
$scheduleid = get_scheduleid($memberid);
$ts = time();
$rand2 = $ts / 2;

$override =  isset($_GET['force_gps']) ? '1' : '';


$js = <<<HERE
	<script src="http://code.google.com/apis/gears/gears_init.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/geo.js" type="text/javascript" charset="utf-8"></script>
HERE;

pdaheader('Digital Hailing', $js, false);
pdawelcome('hailing');

?>
	<div style="text-align: center;">
	<p id="info">
	Please wait...<br>
	<img border=0 src="images/loading.gif">
	</p>
	<div id="formDiv">
	</div>
	</div>
	<script>
		var req = null; // must be declared outside the functions
		var display = document.getElementById('info');
		var address;
		var lat;
		var lon;
		function getAddress(lat, lon) {
			var response;
			var url = 'hail.ajax.php?lat='+lat+'&lon='+lon+'&scheduleid=<?=$scheduleid?>&override=<?=$override?>&junk=<?=$ts?>';
	
			if(window.XMLHttpRequest) {
				req = new XMLHttpRequest();
				req.onreadystatechange=stateChange;
				req.open('GET', url, true);
				req.send(null);
			} else if (window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHTTP");
				req.onreadystatechange=stateChange;
				req.open('GET', url, true);
				// Can't send null to ActiveX object
				req.send();
			}
	
			//alert(req.responseText);
		
		}
		function parseXML(xmlDoc) {
			var xmlDoc = req.responseXML;
			var x = xmlDoc.documentElement.getElementsByTagName("address_parts");
			var mainNode = xmlDoc.childNodes; // address_parts
			var innerNodes = mainNode[0].childNodes;

			makeForm(innerNodes);
		}

		function stateChange() {
			if (req.readyState == 4) {
				if (req.status == 200) {
					parseXML(req.responseXML);
		    		} else {
					alert("There was a problem in the returned data");
		    		}
			} else if (req.readyState >= 0 && req.readyState <= 3) {
				// run a "waiting" animation 
				//display.innerHTML = wait.innerHTML;
			}
		}	
		function makeForm(innerNodes) {
			mydiv = document.getElementById("formDiv");
			myinfo = document.getElementById("info");

			myform = document.createElement("form");
			myform.setAttribute("name", "theform");
			myform.setAttribute("method", "POST");
			myform.setAttribute("action", "m.addHailLoc.php");	

			/*
			mysubmit = document.createElement("input");
			mysubmit.setAttribute("type", "submit");
			mysubmit.setAttribute("name", "mysubmit");
			mysubmit.setAttribute("visibility", "hidden");
			mysubmit.setAttribute("value", "");
			mysubmit.style.height=0;
			mysubmit.style.width=0;
			*/

			mylat = document.createElement("input");
			mylat.setAttribute("type", "hidden");
			mylat.setAttribute("name", "mylat");
			mylat.setAttribute("value", lat);

			mylon = document.createElement("input");
			mylon.setAttribute("type", "hidden");
			mylon.setAttribute("name", "mylon");
			mylon.setAttribute("value", lon);

			rand2 = document.createElement("input");
			rand2.setAttribute("type", "hidden");
			rand2.setAttribute("name", "rand2");
			rand2.setAttribute("value", "<?=$rand2?>");

			var element;
			var output = '';
			var num;
			var street;
			var add1;
			var city;
			var state;
			var zip;
			var foundAddr = '';
			var foundAddrReload = '';
			var machid = null;
			var foundName = null;
			var foundlat;
			var foundlon;

			var curname;
			var curval;

			for (i=0; i < innerNodes.length; i++) {
				if (innerNodes[i].nodeType == 1) {
					element = document.createElement("input");
					element.setAttribute("type", "hidden");
					element.setAttribute("name", innerNodes[i].nodeName);	
					element.setAttribute("value", innerNodes[i].childNodes[0].nodeValue);
					myform.appendChild(element);

					curname = innerNodes[i].nodeName;
					curval = innerNodes[i].childNodes[0].nodeValue;

					if (curname == "street_number")
						num = curval;
					else if (curname == "route")
						street = curval;
					else if (curname == "locality")
						city = curval;
					else if (curname == "state")
						state = curval;
					else if (curname == "postal_code")
						zip = curval;
					else if (curname == "machid")
						machid = curval;
					else if (curname == "name")
						foundName = curval;
					else if (curname == "lat")
						foundlat = curval;
					else if (curname == "lon")
						foundlon = curval;

				}
			}


			//if (machid != null && foundName != null) {
			if(0) {

				mymachid = document.createElement("input");
				mymachid.setAttribute("type", "hidden");
				mymachid.setAttribute("name", "machid");
				mymachid.setAttribute("value", machid);

				foundAddr = 'your profile location <b>'+foundName+', ';
				foundAddrReload = '<li>If you would rather generate a new address based on your exact coordinates, <a href="digital_hail.php?force_gps=1">click here</a>.</li>';

			} else {
				foundName = '<b>';
			}

			var link = '<a href="m.newres.php">clicking here</a>';
			
			output = '<div class="paragraph" style="text-align: left;">'+"Congratulations! Based on your coordinates, we have detected your current location as at or near "+foundAddr+num+" "+street+", "+city+" "+state+", "+zip+"</b>. <ul><li>If this is correct (or close enough), press Continue to make a reservation.</li>"+foundAddrReload+"<li>Otherwise, you can book a reservation normally by "+link+".</li></ul></div>";

			//output += foundlat+" "+foundlon;
			//display.innerHTML = output;

			myform.appendChild(mylat);
			myform.appendChild(mylon);
			myform.appendChild(rand2);
			//myform.appendChild(mysubmit);
	
			mydiv.appendChild(myform);

			myform.submit();

		}

		if(geo_position_js.init()){
			geo_position_js.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true,options:5000});
		}
		else{
			alert('We apologize, but your phone does not support this type of GPS function, or you selected "No" when asked for permission to access your coordinates. If the latter, refresh the page and select "Yes".');
		}

		function success_callback(p) {
			//makeForm();	
			getAddress(p.coords.latitude.toFixed(6), p.coords.longitude.toFixed(6));
			lat = p.coords.latitude.toFixed(6);
			lon = p.coords.longitude.toFixed(6);
			//alert('lat='+p.coords.latitude.toFixed(6)+';lon='+p.coords.longitude.toFixed(6));
		}
		
		function error_callback(p)
		{
			alert('error='+p.message);
		}		
	</script>
	</body>
</html>
<?

function get_scheduleid($memberid) {
	global $db;
	$vals = array($memberid);
	$query = "select scheduleid from schedules where scheduleTitle=?";
	$result = $db->db->getOne($query, $vals);
	return $result;
}
