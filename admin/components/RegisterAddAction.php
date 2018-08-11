<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	if(!isset($_GET['dID'])){
		post_only();
		
		$rID = (isset($_POST['rID']) && is_numeric($_POST['rID'])) ? cIn(strip_tags($_POST['rID'])) : 0;
		$eID = isset($_POST['eventID']) ? cIn($_POST['eventID']) : 0;
		$name = isset($_POST['name']) ? cIn($_POST['name']) : '';
		$email = isset($_POST['email']) ? cIn($_POST['email']) : '';
		$phone = isset($_POST['phone']) ? cIn($_POST['phone']) : '';
		$address = isset($_POST['address']) ? cIn($_POST['address']) : '';
		$address2 = isset($_POST['address2']) ? cIn($_POST['address2']) : '';
		$city = isset($_POST['city']) ? cIn($_POST['city']) : '';
		$state = isset($_POST['locState']) ? cIn($_POST['locState']) : '';
		$zip = isset($_POST['zip']) ? cIn($_POST['zip']) : '';
		$oldemail = isset($_POST['oldemail']) ? cIn($_POST['oldemail']) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE PkID = '" . $rID . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "registrants
					SET Name = '" . $name . "',
						Email = '" . $email . "',
						Phone = '" . $phone . "',
						Address = '" . $address . "',
						Address2 = '" . $address2 . "',
						City = '" . $city . "',
						State = '" . $state . "',
						Zip = '" . $zip . "'
					WHERE PkID = '" . $rID . "'");
			header("Location: " . AdminRoot . "/index.php?com=eventedit&eID=" . $eID . "&msg=4");
		} else {			
			doQuery("INSERT INTO " . HC_TblPrefix . "registrants(Name,Email,Phone,Address,Address2,City,State,Zip,EventID,IsActive,RegisteredAt)
					Values('" . $name . "',
						'" . $email . "',
						'" . $phone . "',
						'" . $address . "',
						'" . $address2 . "',
						'" . $city . "',
						'" . $state . "',
						'" . $zip . "',
						'" . $eID . "',
						1,NOW())");
			header("Location: " . AdminRoot . "/index.php?com=eventedit&eID=" . $eID . "&msg=3");
		}
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "registrants WHERE PkID = '" . cIn(strip_tags($_GET['dID'])) . "'");
		header("Location: " . AdminRoot . "/index.php?com=eventedit&eID=" . cIn(strip_tags($_GET['eID'])) . "&msg=5");
	}
?>