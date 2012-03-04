<?php
/*
*	Use this file as a template for new mobile pages
*/

set_include_path("../:lib/pear/:/usr/local/php5");

include_once('lib/Template.class.php');
include_once('templates/cpanel.template.php');
include_once('templates/mobile.template.php');

if (!Auth::is_logged_in()) {
	header('Location: m.index.php?resume=m.cpanel.php');
} else {
	if(!empty($_GET['currentId'])) {
		$_SESSION['currentID'] = $_GET['currentId'];
		$_SESSION['currentName'] = $_GET['fname'] . ' ' . $_GET['lname'];
	}
}

$t = new Template(translate('My Control Panel'));
$db = new DBEngine();


pdaheader('Reservations');
pdawelcome('cpanel');


/*
* Do stuff here
*/
?>

<div style="margin:1ex">





<div>

<p style="text-align: left; color: #008000; font-size: x-large; font-face: Helvetica; font-weight: bold;">
Frequently Asked Questions
</p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>What 
is PlanetTran?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">PlanetTran is the 
nation&#39;s first all-hybrid, chauffeured car service.  We are defining 
a new mode of car service that we call SmartTransport:</font></p>
<p align="justify"><font size="3" face="Helvetica"><i>Green:  </i>The 
most fuel-efficient, all hybrid car fleet available</font></p>
<p align="justify"><font size="3" face="Helvetica"><i>Best Value:</i> 
 Significantly less expensive than traditional livery services</font></p>
<p align="justify"><font size="3" face="Helvetica"><i>Technology:</i> Online 
booking, e-billing, and free in-car wireless Internet</font></p>

<p align="justify"><font size="3" face="Helvetica"><i>Service:</i>  Professional 
car service standards</font></p>
<p align="justify"><a name="0.1_graphic06"></a><font color="#008000" size="6" face="Helvetica"><!--<img src="">-->
</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>How 
do I use the free in-car wireless Internet?</b></font></p>
<p><font size="3" face="Helvetica">Broadband wireless Internet access 
is complimentary in our cars.  The wireless network name is PlanetTran-xx.  
Please ask your driver for assistance if you have difficulty connecting.</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>Do 
the fares include tips?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">Tips are neither 
included nor expected.  We are committed to fair wages for all employees.  
Our drivers are compensated by hourly time, not based on number of trips 
or tips.  We feel that this results in the most convenient customer 
experience.</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>But, 
may I tip for outstanding service?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">Yes. While we don&#39;t 
encourage or discourage cash tips, drivers may accept them as a token 
of appreciation for superior service. That said, drivers should never 
solicit tips and you should not feel obligated to tip. We expect excellence 
as part of our job. Please be aware that we cannot provide a receipt 
for cash tips, nor can we add a tip to a credit card or invoiced transaction.</font></p>

<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>What 
is your cancellation policy?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">Cancellation of reservations 
must be made within one hour of the scheduled pick-up.  A no-show fee 
for the full fare will be charged when the passenger fails to arrive 
at the designated location, without contacting PlanetTran, within 1 
hour of the reservation time.  (The minimum charge for time-based 
trips is 1.5 hours). To avoid a no show fee or if you cannot locate 
your PlanetTran vehicle, please call.</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>What 
is your waiting policy?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">PlanetTran bills 
wait time at a flat $60 an hour in increments of 10 minutes.  Each reservation 
has a 10 minute grace period for which no wait time charges are assessed.  
PlanetTran tracks all commercial flights and wait time is not charged 
for any passenger arriving on a commercial airline.  We wait for one 
hour after your flight arrives unless we hear from you.</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>How 
many passengers/bags can the cars carry?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">The Prius can seat 
up to 4 people in addition to the driver. Typically, 3 adults with bags 
for a week is a very efficient arrangement.  The Prius can carry skis, 
golf clubs, etc. by folding down one half of the back seat, but this 
reduces the person capacity to 2.  PlanetTran also has sedans, 
SUVs, and busses with greater capacity.</font></p>
<p align="justify"><font color="#008000" size="3" face="Helvetica"><b>How 
do I give feedback?</b></font></p>
<p align="justify"><font size="3" face="Helvetica">We appreciate any 
and all feedback.  Use the survey available on your emailed electronic 
receipt, at <a href="http://www.planettran.com/feedback" target="_blank">beta.planettran.com/feedback</a>, or email <a href="mailto:feedback@planettran.com" target="_blank">feedback@planettran.com</a>.</font></p>



</div>

</div>

<?
pdafooter();


?>
