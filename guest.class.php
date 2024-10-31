<?php

class Guest {

	private $id;	
	private $firstname;
	private $lastname;
	private	$address;
	private	$city;
	private	$state;
	private	$zip;
	private	$phone;
	private	$email;
	private	$rsvp;
	private	$relation;
	private	$plusone;

	function __construct($id = NULL, $firstname = NULL, $lastname = NULL, $address = NULL, $city = NULL, $state = NULL, $zip = NULL, $phone = NULL, $email = NULL, $rsvp = NULL, $relation = NULL, $plusone = NULL) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rb_guestlist';
		if (isset($id) && is_numeric($id) && $firstname == NULL) {
			$result = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $id");
			if ($result == NULL) {
				$message = 'There is no guest with an id of ' . $id . ' in the guestlist.';
				die($message);
			}
			$this->id = $id;
			$this->firstname = $result->firstname;
			$this->lastname = $result->lastname;
			$this->address = $result->address;
			$this->city = $result->city;
			$this->state = $result->state;
			$this->zip = $result->zip;
			$this->phone = $result->phone;
			$this->email = $result->email;
			$this->rsvp = $result->rsvp;
			$this->relation = $result->relation;
			$this->plusone = $result->plusone;
		} elseif (isset($id) && is_numeric($id) && isset($firstname) && isset($lastname)) {
			$this->id = $id;			
			$this->firstname = $firstname;
			$this->lastname = $lastname;
			$this->address = $address;
			$this->city = $city;
			$this->state = $state;
			$this->zip = $zip;
			$this->phone = $phone;
			$this->email = $email;
			$this->rsvp = $rsvp;
			$this->relation = $relation;
			$this->plusone = $plusone;
		} else {
			$this->firstname = $firstname;
			$this->lastname = $lastname;
			$this->address = $address;
			$this->city = $city;
			$this->state = $state;
			$this->zip = $zip;
			$this->phone = $phone;
			$this->email = $email;
			$this->rsvp = $rsvp;
			$this->relation = $relation;
			$this->plusone = $plusone;
			$wpdb->insert(
					$table_name,
					array(
							'firstname' => $firstname,
							'lastname'	=> $lastname,
							'address'	=> $address,
							'city'		=> $city,
							'state'		=> $state,
							'zip'		=> $zip,
							'phone'		=> $phone,
							'email'		=> $email,
							'rsvp'	=> $rsvp,
							'plusone'	=> $plusone
					)
			);
			$this->id = $wpdb->insert_id;
		}
	}
	
	public function getItem($item) {
		switch ($item) {
			case 'firstname':
				return $this->firstname;
			case 'lastname':
				return $this->lastname;
			case 'address':
				return $this->address;
			case 'city':
				return $this->city;
			case 'state':
				return $this->state;
			case 'zip':
				return $this->zip;
			case 'phone':
				return $this->phone;
			case 'email':
				return $this->email;
			case 'rsvp':
				return $this->rsvp;
			case 'relation':
				return $this->relation;
			case 'plusone':
				return $this->plusone;
			case 'id':
				return $this->id;
			default:
				return NULL;
		}
	}

	public function getGuestName() {
		$name = $this->firstname . ' ' . $this->lastname;
		return $name;
	}

	public function updateGuest($id = NULL, $firstname = NULL, $lastname = NULL, $address = NULL, $city = NULL, $state = NULL, $zip = NULL, $phone = NULL, $email = NULL, $rsvp = NULL, $plusone = NULL) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rb_guestlist';
		$wpdb->update(
					$table_name,
					array(
							'firstname' => $firstname,
							'lastname'	=> $lastname,
							'address'	=> $address,
							'city'		=> $city,
							'state'		=> $state,
							'zip'		=> $zip,
							'phone'		=> $phone,
							'email'		=> $email,
							'rsvp'	=> $rsvp,
							'plusone'	=> $plusone
					),
					array( 'id' => $id )					
			);
	}

	public function deleteGuest() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rb_guestlist';
		$sql = 'DELETE FROM ' . $table_name . ' WHERE id = ' . $this->id;
		$wpdb->query($sql);
	}

	/* Returns an array of id's that are linked to the parent Guest object. */
	public function getLinks() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rb_links';
		$sql = 'SELECT linkid FROM ' . $table_name . ' WHERE guestid = ' . $this->id;
		$result = $wpdb->get_results($sql);
		if ($result == NULL) {
			return NULL;
		}
		$linklist = array();
		foreach ($result as $linked) {
			$linklist[] = $linked->linkid;
		}
		return $linklist;
	}

	public function updateLinks($links) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rb_links';
		$sql = 'DELETE FROM ' . $table_name . ' WHERE guestid = ' . $this->id;
		$wpdb->query($sql);
		foreach ($links as $link) {
			$wpdb->insert(
					$table_name,
					array(
							'guestid' => $this->id,
							'linkid'	=> $link
					)
			);
		}
	}
}

?>
