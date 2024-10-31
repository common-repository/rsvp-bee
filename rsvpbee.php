<?php
/*
Plugin Name: RSVP Bee
Plugin URI: http://www.noahbeach.com/projects/rsvp-bee
Description: Manage guest lists
Version: 0.2.3
Author: Noah Beach
Author URI: http://www.noahbeach.com
License: GPL2
*/

/*  Copyright 2011  Noah Beach  (email : me@noahbeach.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once 'guest.class.php';
include_once 'glist.class.php';

register_activation_hook( __FILE__, 'rsvpbee_activate' );

register_deactivation_hook( __FILE__, 'rsvpbee_deactivate' );

function rsvpbee_activate() {
	global $wpdb;
	$table_name0 = $wpdb->prefix . "rb_guestlist";
	$table_name1 = $wpdb->prefix . "rb_links";
	if ($wpdb->get_var("show tables like '$table_name0'") != $table_name0) {
		$sql = "CREATE TABLE " . $table_name . " (
			`id` int(11) not null auto_increment,
  			`firstname` varchar(20),
			`lastname` varchar(20),
			`address` varchar(200),
			`city` varchar(30),
			`state` varchar(30),
			`zip` int(11),
			`phone` varchar(20),
			`email` varchar(100),
			`rsvp` tinyint(1) unsigned not null default '0',
   			`relation` tinyint(1) unsigned not null default '0',
			`plusone` tinyint(1) unsigned not null default '0',
			PRIMARY KEY (`id`)
			);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$sql = NULL;
	} elseif ($wpdb->get_var("show tables like '$table_name1'") != $table_name1) {
		$sql = "CREATE TABLE " . $table_name1 . " (
			`guestid` tinyint(1) unsigned not null,
			`linkid` tinyint(1) unsigned not null,
			PRIMARY KEY (`guestid`),
			UNIQUE KEY (`linkid`)
			);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$sql = NULL;
	}
}

function rsvpbee_deactivate() {
	global $wpdb;
	$table_name = $wpdb->prefix . "rb_guestlist";	
	$table = $wbdb->prefix . "rb_guestlist";
	$wpdb->query("DROP TABLE IF EXISTS $table");
	$table = NULL;
	$table = $wbdb->prefix . "rb_linkedguest";
	$wpdb->query("DROP TABLE IF EXISTS $table");
}

add_action('admin_menu', 'rsvpbee_menu');

function rsvpbee_menu() {
	$icon = plugin_dir_url(__FILE__) . 'rsvpbee-icon.png';
	add_menu_page('RSVP Bee', 'RSVP Bee', 'manage_options', 'rsvpbee-admin', 'rsvpbee_admin', $icon);
	add_submenu_page('rsvpbee-admin', 'View Guest List', 'View Guest List', 'manage_options', 'rsvpbee-viewguestlist', 'rsvpbee_viewguestlist');
	add_submenu_page('rsvpbee-admin', 'Add Guest', 'Add Guest', 'manage_options', 'rsvpbee-addguest', 'rsvpbee_addguest');
	add_submenu_page('rsvpbee-admin', 'View Guest', 'View Guest', 'manage_options', 'rsvpbee-viewguest', 'rsvpbee_viewguest');
	add_submenu_page('rsvpbee-admin', 'Edit Guest', 'Edit Guest', 'manage_options', 'rsvpbee-editguest', 'rsvpbee_editguest');
	add_submenu_page('rsvpbee-admin', 'Settings', 'Settings', 'manage_options', 'rsvpbee-settings', 'rsvpbee_settings');
}

function rsvpbee_admin() {
    global $title;
    ?>
    <div class="wrap">
    <h2><?php echo $title;?></h2>
    ... Functionality for using RSVP Bee will go here ...
</div><?php
}
 
function rsvpbee_viewguestlist() {
    global $title;
    echo '<div class="wrap"><h2>' . $title . '</h2></div>';
	$guestlist = new GList("FULL");
	$guestlist->displayGListAsHTMLTable();
}
 
function rsvpbee_addguest() {
    global $title;
    echo '<div class="wrap"><h2>' . $title . '</h2>';
	?>
	<form action="<?php echo plugins_url() . '/rsvp-bee/addguest.helper.php'; ?>" method="post">
	<table>
	<tr><td>Firstname:</td><td><input type="text" size="15" maxlength="100" name="firstname" ></td></tr>
	<tr><td>Lastname:</td><td><input type="text" size="15" maxlength="100" name="lastname" ></td></tr>
	<tr><td>Address:</td><td><input type="text" size="50" maxlength="200" name="address" ></td></tr>
	<tr><td>City:</td><td><input type="text" size="50" maxlength="100" name="city" ></td></tr>
	<tr><td>State:</td><td><input type="text" size="50" maxlength="100" name="state" ></td></tr>
	<tr><td>Zip:</td><td><input type="text" size="5" maxlength="5" name="zip" ></td></tr>
	<tr><td>Phone:</td><td><input type="text" size="12" maxlength="13" name="phone" ></td></tr>
	<tr><td>Email:</td><td><input type="text" size="50" maxlength="100" name="email" ></td></tr>
	<tr><td>Plus One:</td><td><input type="radio" name="plusone" value="1" />
	Yes<input type="radio" name="plusone" value="0" /> No</td></tr>
	<tr><td>Relation: </td><td><select name="relation">
	<option value="0" >Bride</option>
	<option value="1" >Groom</option></td>
	</select></tr>
	<tr><td>Link with other guests (RSVP together):</td></tr><tr><td>
	<?php
	$glist = new GList("FULL");
	if ($glist != NULL) {
		$glist->displayGListAsHTMLCheckList();
	}
	?>
	</td></tr>
	<tr><td><input type="submit" value="Submit" alt="Submit" style="background:green; color:white;"></td></tr>
	</form></table>
	</div>
	<?php
}

function rsvpbee_viewguest() {
	global $title;
    echo '<div class="wrap"><h2>' . $title . '</h2>';
	if (isset($_GET['guest'])) {	
		$guest = new Guest($_GET['guest']);
		echo $guest->getGuestName();
	}
	echo '</div>';
}

function rsvpbee_editguest() {
	global $title;
    echo '<div class="wrap"><h2>' . $title . '</h2>';
	if (isset($_GET['guest']) && is_numeric($_GET['guest']) && isset($_GET['action'])) {	
		$guest = new Guest($_GET['guest']);
		if (isset($_GET['action']) && $_GET['action'] == 'delete') {
			$guest->deleteGuest();
		} elseif (isset($_GET['action']) && $_GET['action'] == 'edit') {
			?>
			<form action="<?php echo plugins_url() . '/rsvp-bee/editguest.helper.php'; ?>" method="post">
			<table>
			<tr><td>Firstname:</td><td><input type="text" size="15" maxlength="100" name="firstname" value="<?php echo $guest->getItem('firstname'); ?>"></td></tr>
			<tr><td>Lastname:</td><td><input type="text" size="15" maxlength="100" name="lastname" value="<?php echo $guest->getItem('lastname'); ?>"></td></tr>
			<tr><td>Address:</td><td><input type="text" size="50" maxlength="200" name="address" value="<?php echo $guest->getItem('address'); ?>"></td></tr>
			<tr><td>City:</td><td><input type="text" size="50" maxlength="100" name="city" value="<?php echo $guest->getItem('city'); ?>"></td></tr>
			<tr><td>State:</td><td><input type="text" size="50" maxlength="100" name="state" value="<?php echo $guest->getItem('state'); ?>"></td></tr>
			<tr><td>Zip:</td><td><input type="text" size="5" maxlength="5" name="zip" value="<?php echo $guest->getItem('zip'); ?>"></td></tr>
			<tr><td>Phone:</td><td><input type="text" size="12" maxlength="13" name="phone" value="<?php echo $guest->getItem('phone'); ?>"></td></tr>
			<tr><td>Email:</td><td><input type="text" size="50" maxlength="100" name="email" value="<?php echo $guest->getItem('email'); ?>"></td></tr>
			<tr><td>Plus One:</td><td><input type="radio" name="plusone" value="1" <?php if ($guest->getItem('plusone') == 1) { echo "checked"; } ?> />
			Yes<input type="radio" name="plusone" value="0" <?php if ($guest->getItem('plusone') == 0) { echo "checked"; } ?> /> No</td></tr>
			<tr><td>Relation: </td><td><select name="relation">
			<option value="0" <?php if ($guest->getItem('relation') == 0) { echo 'selected="selected"'; } ?>>Bride</option>
			<option value="1" <?php if ($guest->getItem('relation') == 1) { echo 'selected="selected"'; } ?>>Groom</option></td>
			</select></tr>
			<tr><td>Link with other guests (RSVP together):</td></tr><tr><td>
			<?php
				$glist = new GList("FULL");
				if ($glist != NULL) {
					$glist->displayGListAsHTMLCheckList($guest);
				}
			?>
			</td></tr>
			<input type="hidden" name="update" value="TRUE">
			<input type="hidden" name="id" value="<?php echo $guest->getItem('id'); ?>">
			<tr><td><input type="submit" value="Submit" alt="Submit" style="background:green; color:white;"></td></tr>
			</form></table>
			<?php
		}
	} else {
		echo 'Please select a guest to edit from the <a href="' . site_url() . '/wp-admin/admin.php?page=rsvpbee-viewguestlist">View Guest List</a> page.';
	}
	echo '</div>';
}

function rsvpbee_settings() {
	global $title;
    echo '<div class="wrap"><h2>' . $title . '</h2>';
	echo '</div>';
}

function rsvpbee_widget() {
	echo '<form action="'. plugins_url() . '/rsvpbee.script.php" method="post">';
	echo '<center><table><tr>';
	echo '<td><input type="text" size="1" maxlength="1" style="font-family:monospace; font-size:150%;" name="rsvp0" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="text" size="1" maxlength="1" style="font-family:monospace; font-size:150%;" name="rsvp1" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="text" size="1" maxlength="1" style="font-family:monospace; font-size:150%;" name="rsvp2" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="text" size="1" maxlength="1" style="font-family:monospace; font-size:150%;" name="rsvp3" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="radio" name="rsvp" value="Yes" /></td>';
	echo '<td><img src="http://www.sarahandnoah.com/wp-content/uploads/2011/02/yes.png" /></td>';
	echo '<td><input type="radio" name="rsvp" value="No"/></td>';
	echo '<td><img src="http://www.sarahandnoah.com/wp-content/uploads/2011/02/no.png" /></td>';
	echo '<td><input type="image" src="http://www.sarahandnoah.com/wp-content/uploads/2011/02/submit.png" value="Submit" alt="Submit"></td>';
	echo '</tr></table></center></form>';
}

function rsvpbee_namewidget() {
	echo '<form action="'. plugins_url() . '/rsvpbee.script.php" method="post">';
	echo '<center><table><tr>';
	echo '<td><input type="text" size="10" maxlength="100" style="font-family:monospace; font-size:100%;" name="firstname" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="text" size="10" maxlength="100" style="font-family:monospace; font-size:100%;" name="lastname" /></td>';
	echo '<td>&nbsp;</td>';
	echo '<td><input type="image" src="'. plugins_url() . 'rsvp-bee/images/submit.png" value="Submit" alt="Submit"></td>';
	echo '</tr></table></center></form>';
}
?>
