<?php
//ini_set('display_errors', '1');
include_once('lib/Template2.class.php');
include_once('lib/DBEngine.class.php');
include_once('templates/cpanel.template.php');


if (!Auth::is_logged_in()) { 
    Auth::print_login_msg();
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template('Real Time Map');
$db = new DBEngine();
$_GET['mode'] = 'php';
include_once "TC_refresh.php";
$cars = get_cars('php'); /* function from TC_refresh */

printMapHeader($cars);
$t->startMain();

//include('traffic_control.php');
print_map($cars);

printCpanelBr();

$t->endMain();
$t->printHTMLFooter();
/**************************************************************/
function print_map($cars) {
	$is_tracking = isset($cars[0]['lat'], $cars[0]['lon']);
	$num_cars = count($cars);
	$name = $_SESSION['currentName'];
	if (!$is_tracking) $msg = "You do not have any cars tracking.";
	else $msg = "You currently have $num_cars car".($num_cars>1 ? 's' : '')." tracking.";
	?>
	<div align="center">If your trip has been assigned a car, and if we have current tracking data for that car, it will be displayed on the map below.<br>&nbsp;<br>Showing tracking data for <b><?=$name?></b>.<br><?=$msg?><br></div>
<div id="map" class="map" align="center"></div>
<div align="center">Map is centered on <span id="message"></span></div>
<table width="100%" cellspacing=0 cellpadding=0><tr><td>
<span style="color: red;"><span style="background-color: #99ff99; padding: 2px; font-size: 0.7em; font-family: Arial,Verdana,Sans-serif; font-weight: bold; border-style: solid; border-width: 1px;"><nobr>00</nobr></span></span>
 Red numbers/outline = car has not been updated in over 2 minutes
 <span id="count"></span></td>
<td align="right">
</td></tr></table>
<?
}

/*
* Print special header for traffic control page
*/
function printMapHeader($cars) {
include_once('key.php');

/* Center map on their car on loading */
if (isset($cars[0]['lat'], $cars[0]['lon'])) {
	$deflat = $cars[0]['lat'];
	$deflon = $cars[0]['lon'];
} else {
	$deflat = 42.355499;
	$deflon = -71.228485;
}
$zoom = ($_COOKIE['zoom']) ? $_COOKIE['zoom'] : 11;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
	<link rel="stylesheet"  type="text/css" href="css.css"  />
	<link rel="stylesheet"  type="text/css" href="css1.css"  />
<title>Real Time Map</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=$key?>"
      type="text/javascript"></script>
<script src="tlabel.2.05.js" type="text/javascript"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_nbGroup(event, grpName) { //v6.0
  var i,img,nbArr,args=MM_nbGroup.arguments;
  if (event == "init" && args.length > 2) {
    if ((img = MM_findObj(args[2])) != null && !img.MM_init) {
      img.MM_init = true; img.MM_up = args[3]; img.MM_dn = img.src;
      if ((nbArr = document[grpName]) == null) nbArr = document[grpName] = new Array();
      nbArr[nbArr.length] = img;
      for (i=4; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
        if (!img.MM_up) img.MM_up = img.src;
        img.src = img.MM_dn = args[i+1];
        nbArr[nbArr.length] = img;
    } }
  } else if (event == "over") {
    document.MM_nbOver = nbArr = new Array();
    for (i=1; i < args.length-1; i+=3) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = (img.MM_dn && args[i+2]) ? args[i+2] : ((args[i+1])? args[i+1] : img.MM_up);
      nbArr[nbArr.length] = img;
    }
  } else if (event == "out" ) {
    for (i=0; i < document.MM_nbOver.length; i++) {
      img = document.MM_nbOver[i]; img.src = (img.MM_dn) ? img.MM_dn : img.MM_up; }
  } else if (event == "down") {
    nbArr = document[grpName];
    if (nbArr)
      for (i=0; i < nbArr.length; i++) { img=nbArr[i]; img.src = img.MM_up; img.MM_dn = 0; }
    document[grpName] = nbArr = new Array();
    for (i=2; i < args.length-1; i+=2) if ((img = MM_findObj(args[i])) != null) {
      if (!img.MM_up) img.MM_up = img.src;
      img.src = img.MM_dn = (args[i+1])? args[i+1] : img.MM_up;
      nbArr[nbArr.length] = img;
  } }
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//<![CDATA[
    function load() {
      var date = new Date();
      date.setTime(date.getTime()+(30*60*1000));
      var expires = "; expires="+date.toGMTString();
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		GEvent.addListener(map, "moveend", function() {
		  var center = map.getCenter();
		  document.getElementById("message").innerHTML = center.toString();
		  var deflat = center.lat();
		  var deflon = center.lng();
		  var zoom = map.getZoom();
		  document.cookie = 'deflat=' + deflat + '; expires=Thu, 2 Aug 2010 20:47:11 UTC; path=/';
		  document.cookie = 'deflon=' + deflon + '; expires=Thu, 2 Aug 2010 20:47:11 UTC; path=/';
		  document.cookie = 'zoom=' + zoom + '; expires=Thu, 2 Aug 2010 20:47:11 UTC; path=/';
		});

        map.setCenter(new GLatLng(<?=$deflat?>, <?=$deflon?>), <?=$zoom?>);


		// Creates a marker whose info window displays the letter corresponding
		// to the given index.
		/*
		function createMarker(point, text, fulltext) {
		  // Create a lettered icon for this point using our icon class
		  var icon = new GIcon(baseIcon);
		  icon.image = "images/marker" + text + ".png";
		  var marker = new GMarker(point, icon);
		  fulltext = text == "home" ? "Planettran Offices" : (text == "logan" ? "Logan Airport" : fulltext);
		  fulltext = text == "novartis" ? "Novartis" : fulltext;

		  GEvent.addListener(marker, "click", function() {
		    marker.openInfoWindowHtml("<b>" + fulltext + "</b>");
		  });
		  return marker;
		}
		*/
		/*
		for (i=0; i < carArray.length;i++) {
			if (carArray[i][3]!=''&&carArray[i][4]!='') {
				var car = new GLatLng(carArray[i][3], carArray[i][4]);
				map.addOverlay(createMarker(car, carArray[i][2], "Car "+carArray[i][2]+"<br>Last updated at<br>"+carArray[i][5]));
			}
		}
		*/
		// Initialize cars and locations
		GDownloadUrl("TC_refresh.php?x="+Math.random(), function(data, responseCode) {
   	    	var xml = GXml.parse(data);
			var markers = xml.documentElement.getElementsByTagName("marker");
			for (var i = 0; i < markers.length; i++) {
				var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
			                            parseFloat(markers[i].getAttribute("lng")));
				var pointStyle;
				var bgColor;
				var vLabel;
				if (markers[i].getAttribute("label") == "home" || markers[i].getAttribute("label") == "logan" || markers[i].getAttribute("warning") == "location") {
					pointStyle = "point_blue.png";
					bgColor = "#ccffff";
					vLabel = markers[i].getAttribute("label");
				} else {
					pointStyle = "point_green.png";
					bgColor = "#99ff99";
					vLabel = markers[i].getAttribute("label");
					vLabel = vLabel.substr(vLabel.length-2);
				}
				var content = '<div style="padding: 0px 0px 8px 8px;  background: url(images/'+pointStyle+') no-repeat bottom left;"><div style="background-color: '+bgColor+'; padding: 2px; font-size: 0.9em; font-family: Arial,Verdana,Sans-serif; font-weight: bold; border-style: solid; border-width: 1px;"><nobr>'+vLabel+'</nobr></div></div>';
				var curlab = markers[i].getAttribute("id");
				window.curlab = new TLabel();
				window.curlab.id = markers[i].getAttribute("id");
				window.curlab.content = content;
				window.curlab.anchorPoint = 'bottomLeft';
				window.curlab.percentOpacity = 80;
				window.curlab.anchorLatLng = point;
				map.addTLabel(window.curlab);
				//window.curlab.setPosition(point);
			}
		});

		// The main loop
        window.timer = function () {
				GDownloadUrl("TC_refresh.php?x="+Math.random(), function(data, responseCode) {
				  var xml = GXml.parse(data);
				  var markers = xml.documentElement.getElementsByTagName("marker");
				  for (var i = 0; i < markers.length; i++) {
				    var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
			                                parseFloat(markers[i].getAttribute("lng")));
				    var curlab = markers[i].getAttribute("id");

				    	window.curlab.id = curlab;
				//	window.curlab.anchorLatLng = point;
						window.curlab.setPosition(point);
						document.getElementById(curlab).style.color = (markers[i].getAttribute("warning")=="true")?"red":"black";
						document.getElementById(curlab).style.visibility = (markers[i].getAttribute("warning")=="kill")?"hidden":"visible";
				  }
				});
				setTimeout('window.timer();', 5000);
		}
		window.timer();
      }
    }
    //]]>
//-->
</script>
<!--[if !mso]>
<style type="text/css">
    v\:* { behavior:url(#default#VML); }
</style>
<![endif]-->

<meta name="description" content=""revolutionary airport livery for the greater boston area and beyond."">
<meta name="keywords" content="hybrid, car, taxi, cab, limo, livery, shuttle, service, logan, international, airport, environmentally, friendly, eco, cambridge, boston, climate, protection, PlanetTran, planet, tran">
<style type="text/css">
<!--
.style1 {font-size: 14px}
.style2 {font-size: 16px}
html {height: 100%; min-height: 100%;}
body {height: 100%; min-height: 100%;}
div.map {height: 600px; width: 100%; min-height: 90%}
-->
</style>
</head>
<!--<body leftmargin="0" topmargin="10" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('images/navbar_contactroll.gif','images/navbar_hybridroll.gif','images/navbar_aboutroll.gif','images/navbar_partnersroll.gif','images/navbar_specialroll.gif')">-->
<body onload="load()" onunload="GUnload()">
	<?
}
?>
