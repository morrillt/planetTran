<?php
//include_once('lib/Template.class.php');
include_once('lib/DBEngine.class.php');
//$t = new Template('Get a Quote');
$d = new DBEngine($conf['db']['dbName']);
//$t->printHTMLHeader();
//$t->startMain();
//print_specials();

//$t->endMain();
//$t->printHTMLFooter();
/* Functions *************************************************************/
function print_specials() {
	$query = "select bg.group_name as groupName, fl.name as fromName, fl.address1 as fromAddress, fl.city as fromCity, fl.state as fromState, tl.name as toName, tl.address1 as toAddress, tl.city as toCity, tl.state as toState, bs.fare as fare from resources fl, resources tl, billing_special bs, billing_groups bg, login l where bs.fromLoc = fl.machid and bs.toLoc = tl.machid and bs.groupid = l.groupid and bg.groupid = l.groupid and l.memberid='".$_SESSION['sessionID']."'";
	$qresult = mysql_query($query);
	if (!mysql_num_rows($qresult))
		return false;
	echo mysql_error();
	$i = 0;
	while($row = mysql_fetch_assoc($qresult)) {
		if(!$i) {
			echo "<h1><div align='center'>Special Rates for " . $row['groupName'] . "</div></h1>";
			echo "<table align='center' width='95%'>";
			echo "<tr class='bold'><td width='35%'>&nbsp;</td>";//<td>Address</td><td>City</td><td>State</td>";
			echo "<td width='20%'>&nbsp</td>";
			echo "<td width='35%'>&nbsp;</td>";//<td>Address</td><td>City</td><td>State</td>";
			echo "<td width='10%'>&nbsp;</td></tr>";
		}
		echo "<tr>";
		echo "<td>" . $row['fromName'] . "</td>";
		echo "<td class='bold'>From/To</td>";
		//echo "<td>" . $row['fromAddress'] . "</td>";
		//echo "<td>" . $row['fromCity'] . "</td>";
	//	echo "<td>" . $row['fromState'] . "</td>";
		echo "<td>" . $row['toName'] . "</td>";
	//	echo "<td>" . $row['toAddress'] . "</td>";
	//	echo "<td>" . $row['toCity'] . "</td>";
	//	echo "<td>" . $row['toState'] . "</td>";
		echo "<td>$" . $row['fare'] . "</td>";
		echo "</tr>";
		$i++;
	}
	
	if(!$i) {
		//echo "<h1><div align='center'>Your organization does not have any special rates.  Try the Custom Quote feature</div></h1>";
		return false;
	} else
		echo "</table><br><div align='center'>These rates apply to our Toyota Prius Hybrid Sedan.</div>";
	return true;	
}
?>
