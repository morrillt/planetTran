<?php
 
$mobile_browser = 0;
 
if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}
 
if((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml')>0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
    $mobile_browser++;
}    
 
$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));
$mobile_agents = array(
    'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
    'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
    'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
    'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
    'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
    'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
    'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
    'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
    'wapr','webc','winw','winw','xda','xda-');
 
if(in_array($mobile_ua,$mobile_agents)) {
    $mobile_browser++;
}
 
if (strpos(strtolower($_SERVER['ALL_HTTP']),'OperaMini')>0) {
    $mobile_browser++;
}
 
if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows')>0) {
    $mobile_browser=0;
}
 
if($mobile_browser>0) {
//if(1) {
?><html><head><title>Mobile Browser Detected</title>
<META HTTP-EQUIV="Refresh"
      CONTENT="16; URL=/Home-page.php">
</head>
<body>
<div style="text-align: center;">
<img src="images/planettran_logo_new.jpg" border=0>
</div>
We have detected a mobile browser. To visit our mobile website, click here: <a href="http://m.planettran.com">PlanetTran Mobile</a>.
<br>&nbsp;<br>
To continue to the regular website, <a href="/Home-page.php">click here</a> or wait 15 seconds and you will be automatically redirected.
</body>
</html>
	<?
} else {
	// go to main page
?><HTML>
<META HTTP-EQUIV="Refresh"
      CONTENT="0; URL=/Home-page.php">
</HTML><?
}   
 
?>
