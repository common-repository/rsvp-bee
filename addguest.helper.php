<?php

$inc0 = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
include_once $inc0;
$inc1 = 'guest.class.php';
include_once $inc1;

if (isset($_POST['firstname']))	{
	$guest = new Guest(NULL, $_POST['firstname'], $_POST['lastname'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['phone'], $_POST['email'], $POST['rsvp'], $_POST['relation'], $_POST['plusone']);
$goto = 'Location: ' . $_POST['site'] . '/wp-admin/admin.php?page=rsvpbee-viewguest&guest=' . $guest->getItem('id');
header($goto);
}

?>
