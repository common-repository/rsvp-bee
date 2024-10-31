<?php

$inc0 = $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';
include_once $inc0;
$inc1 = 'guest.class.php';
include_once $inc1;

class GList {

	private $guestlist = array();

	//Construct has option to be sent a prebuilt array of Guest objects or "FULL" for entire guest list
	function __construct($guestlist = NULL) {
		if ($guestlist != NULL && is_array($guestlist)) {
			$this->guestlist = $guestlist;
		} elseif ($guestlist != NULL && $guestlist == "FULL") {
			global $wpdb;
			$table = $wpdb->prefix . "rb_guestlist";
			$sql = "SELECT * FROM $table";
			$result = $wpdb->get_results($sql);
			foreach ($result as $guest) {
				$guest = new Guest($guest->id, $guest->firstname, $guest->lastname, $guest->address, $guest->city, $guest->state, $guest->zip, $guest->phone, $guest->email, $guest->rsvp, $guest->relation, $guest->plusone);
				$this->guestlist[] = $guest;
				$guest = NULL;	
			}
		}
	}

	public function addGuestToArray($guestid) {
		$guest = new Guest($guestid);
		$this->guestlist[$guest->getGuestID()] = $guest;
	}

	public function getList() {
		return $this->guestlist;
	}

	public function displayGListAsHTMLTable() {
		$style =  ' style="text-align:left; padding:2px; border:1px solid #000000;"';
		$style_th = ' style="background-color:green; color:white; padding:2px; border:1px solid #000000;"';
		echo '<form action="' . plugins_url() . '/rsvp-bee/editguest.helper.php" method="post"><table style="border-collapse:collapse;"><tr><th' . $style_th . '</th><th' . $style_th . '><b>Name</b></th><th' . $style_th . '><b>Address</b></th><th' . $style_th . '><b>City</b></th><th' . $style_th . '><b>State</b></th><th' . $style_th . '><b>Zip</b></th><th' . $style_th . '><b>Phone</b></th><th' . $style_th . '><b>Email</b></th><th' . $style_th . '><b>RSVP\'d</b></th><th' . $style_th . '><b>Relation</b></th><th' . $style_th . '><b>Plus One</b></th></tr>'; //<th' . $style_th . '><b>Edit</b></th><th' . $style_th . '><b>Del</b></th></tr>';
		foreach ($this->guestlist as $guest) {
			echo '<tr' . $style . '>';
			echo '<td' . $style . '><input type="radio" name="id" value="' . $guest->getItem('id') . '"></td>'; 
			echo '<td' . $style . '>' . $guest->getGuestName() . '</td>';
			echo '<td' . $style . '>' . $guest->getItem('address') . '</td>';
			echo '<td' . $style . '>' . $guest->getItem('city') . '</td>';
			echo '<td' . $style . '>' . $guest->getItem('state') . '</td>';
			echo '<td' . $style . '>' . $guest->getItem('zip') . '</td>';	
			echo '<td' . $style . '>' . $guest->getItem('phone') . '</td>';
			echo '<td' . $style . '>' . $guest->getItem('email') . '</td>';
			if ($guest->getItem('rsvp') == 0) { echo '<td' . $style . '>' . 'No</td>'; } else { echo '<td' . $style . '>' . 'Yes</td>'; }
			echo '<td' . $style . '>';
			if ($guest->getItem('relation') == 0) { echo 'Bride'; } else { echo 'Groom'; }
			echo '</td>';
			echo '<td' . $style . '>' . $guest->getItem('plusone') . '</td>';
			//echo '<td' . $style . '><a href="' . site_url() . '/wp-admin/admin.php?page=rsvpbee-editguest&guest=' . $guest->getItem('id') . '">Edit</a></td>';
			//echo '<td' . $style . '><a href="' . site_url() . '/wp-admin/admin.php?page=rsvpbee-editguest&guest=' . $guest->getItem('id') . '&action=del">X</a></td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '<select name="action"><option value="edit">Edit</option><option value="delete">Delete</option></select>   ';
		echo '<input type="hidden" name="update" value="FALSE">';
		echo '<input type="submit" value="Submit" alt="Submit" style="background:green; color:white;"></form>';
	}

	public function displayGlistAsHTMLCheckList($guest = NULL) {
		echo '<div style="height: 100px; width:auto; padding: 5px; overflow: auto; border: 1px solid #ccc">';
		if (isset($guest) && is_a($guest, 'Guest')) { $linked = $guest->getLinks(); }
		foreach ($this->guestlist as $listitem) {
			if (isset($guest) && is_a($guest, 'Guest')) {
				if ($linked != NULL && in_array($listitem->getItem('id'), $linked)) {
					echo '<label><input type="checkbox" name="links[]" value="'. $listitem->getItem('id') . '" checked /> ' . $listitem->getGuestName() . '</label><br />';
				} elseif ($listitem->getItem('id') != $guest->getItem('id')) {
					echo '<label><input type="checkbox" name="links[]" value="'. $listitem->getItem('id') . '" /> ' . $listitem->getGuestName() . '</label><br />';				
				}	
			} else {
				echo '<label><input type="checkbox" name="links[]" value="'. $listitem->getItem('id') . '" /> ' . $listitem->getGuestName() . '</label><br />';
			}
		}
		echo '</div>';
	}
}
?>
