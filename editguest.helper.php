<?php

$inc0 = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
include_once $inc0;
$inc1 = 'guest.class.php';
include_once $inc1;
if (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['action']) && $_POST['update'] == "FALSE")	{
	if ($_POST['action'] == "edit") {
		$goto = 'Location: ' . site_url() . '/wp-admin/admin.php?page=rsvpbee-editguest&guest=' . $_POST['id'] . '&action=' . $_POST['action'];
		header($goto);
	} elseif ($_POST['action'] == "delete") {
		$goto = 'Location: ' . site_url() . '/wp-admin/admin.php?page=rsvpbee-viewguestlist';
		header($goto);
	} else {
		$goto = 'Location: ' . site_url() . '/wp-admin/admin.php?page=rsvpbee-viewguestlist';
		header($goto);
	}
} elseif (isset($_POST['id']) && is_numeric($_POST['id']) && isset($_POST['update']) && $_POST['update'] == "TRUE") {
	$guest = new Guest($_POST['id']);
	$guest->updateGuest($_POST['id'], $_POST['firstname'], $_POST['lastname'], $_POST['address'], $_POST['city'], $_POST['state'], $_POST['zip'], $_POST['phone'], $_POST['email'], $_POST['rsvp'], $_POST['plusone']);
	if (count($guest->getLinks()) != count($_POST['links'])) {
		$guest->updateLinks($_POST['links']);
	}
	$goto = 'Location: ' . site_url() . '/wp-admin/admin.php?page=rsvpbee-viewguestlist';
	header($goto);
} else {
	//echo 'Its failing everything <br />';
	$goto = 'Location: ' . site_url() . '/wp-admin/admin.php?page=rsvpbee-viewguestlist';
	header($goto);
}

?>
