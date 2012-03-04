<?php

$vals = array(	'twitterUserName'=>	'mattTest',
		'fromType'=>		1,
		'toName'=>		'Home',
		'timestamp'=>		time() + 15,
		'fromLat'=>		'42.615685',
		'fromLon'=>		'-71.166843'

	);



?>
<form name="ttest" action="twitter.php" method="post">
<input type="submit" value="Test"><br>
<?

foreach ($vals as $k=>$v) {
	echo $k.' = '.$v.'<input type="hidden" name="'.$k.'" value="'.$v.'">';
	echo "<br>";
}

echo '</form>';
