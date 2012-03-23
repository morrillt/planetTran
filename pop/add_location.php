<?php
$includePath = dirname(__FILE__).'/../secure/reservations/ptRes2/';

include_once($includePath.'templates/admin.template.php');
print_resource_edit($_GET['machid']);

