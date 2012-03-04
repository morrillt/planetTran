<?php

include_once('lib/Template.class.php');
include_once('lib/Reservation.class.php');
//include_once('lib.php');

$t = new Template();

//delete
if(!empty($_GET['action']) && $_GET['action'] == 'delete' && !empty($_GET['id']))
{
  $reservation = new Reservation($_GET['id']);
  $reservation->delete($_GET['id']);
}
//edit
elseif(!empty($_GET['id']))
{
  $t->set_title('Edit Reservation');
  $t->printHTMLHeader('silo_reservations sn3 mn1');
  $t->printNavReservations();
  $t->startMain();

  $reservation = new Reservation($_GET['id']);
  $reservation->processValues();
  $reservation->displayForm();

  $t->endMain();
  $t->printHTMLFooter();
}
//add
else
{
  $t->set_title('New Reservation');
  $t->printHTMLHeader('silo_reservations sn3 mn1');
  $t->printNavReservations();
  $t->startMain();

  $reservation = new Reservation();
  $reservation->processValues();
  $reservation->displayForm();

  $t->endMain();
  $t->printHTMLFooter();
}
