<?php

$file = "test.txt";

//touch("test.txt");

if (file_exists($file)) {
	echo "$file exists, deleting\n";
	unlink($file);
}




?>
