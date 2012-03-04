<?php

include_once('lib/Template.class.php');
include_once('templates/mobile.template.php');


$t = new Template();
pdaheader('About');

$back = $_GET['back'] ? $_GET['back'] : 'm.index.php';

pdawelcome(null, $back);
?>
<div class="paragraph">
PlanetTran is a compelling alternative to traditional taxi and livery services that currently serves the Bay Area and New England.  We believe there is a better way to travel than in gas-guzzling hummer limos or in unreliable taxis who don't provide any benefits.  Our mission is to completely redefine the livery industry through technological innovation.  Living green has traditionally involved sacrificing luxuries in your life for the good of the planet.  At PlanetTran, our goal is to save the world, but not by asking you sacrifice or by living a simple life, instead we provide the best service, at the best price, with the lease environmental impact.
</div>

<div class="paragraph">
	<div class="title">
	Corporate Mailing Address
	</div>
	PlanetTran<br>
	1 Broadway, 14th Floor<br>
	Cambridge, MA 02142
</div>

<div class="paragraph">
	<div class="title">
	Questions?
	</div>
	Visit our <a href="m.faq.php">FAQ</a>, <a href="http://www.planettran.com">http://www.planettran.com</a>, or call 888 756 8876.
</div>
 

<div class="paragraph">
PlanetTran Terms of Use apply.<br>
© 2010 Planet Transportation Systems, Inc.
</div>

<?
pdafooter();
?>
