<script type="text/javascript" src="functions.js"></script><?php/** Check if an address is valid by testing its GPS coords. Print message for user.*/include_once('lib/Template.class.php');include_once('lib.php');$t = new Template('Check Address');// $t->printHTMLHeader('', true);//$t->startMain();$location = implode(" ",array_filter(array($_REQUEST['address1'], $_REQUEST['city'], $_REQUEST['state'])));$array = getGPS($location);if (!empty($array[1]['zip'])) { // Google	loc_success($array[1]);} else if (!empty($array['zip'])) { // Yahoo	loc_success($array);}else {	loc_fail($array);}//$t->endMain();// $t->printHTMLFooter(false, true);function loc_success($locs) {	$mode = $_GET['mode'];	$locs['zip'] = substr($locs['zip'], 0, 5);	echo '<center><table border=0 cellspacing=0 cellpadding=2 width="100%">';	echo "<tr><td>Address: </td><td><span id=\"streetaddr\">{$locs['streetaddr']}</span></td></tr>";	echo "<tr><td>City: </td><td><span id=\"ccity\">{$locs['city']}</span></td></tr>";	echo "<tr><td>State: </td><td><span id=\"cstate\">{$locs['state']}</span></td></tr>";	echo "<tr><td>Zip: </td><td><span id=\"czip\">{$locs['zip']}</span></td></tr></table><br/>&nbsp;<br/>";	echo "Is this correct?<br/>&nbsp;<br/>";	echo '<a href="javascript: goodZip('.$mode.');">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';	echo '<a href="javascript: window.close()">No, go back</a></center>';}function loc_fail($array = null) {	echo "<center>Could not find {$_GET['address1']}, {$_GET['city']}, {$_GET['state']}.<br/>&nbsp;<br/>";	echo "Please double check that the <b>Address</b>, <b>City</b> and <b>State</b> are correct.<br/>&nbsp;<br/>";	echo "<a href=\"javascript: window.close();\">Close</a></center>";}?>