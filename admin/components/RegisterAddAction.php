<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$rID = $_POST['rID'];
		$eID = $_POST['eventID'];
		$name = $_POST['name'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$address = $_POST['address'];
		$address2 = $_POST['address2']; 
		$city = $_POST['city'];
		$state = $_POST['locState'];
		$zip = $_POST['zip'];
		$oldemail = $_POST['oldemail'];
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE PkID = " . $rID);
		
		if(hasRows($result)){
			$query = "UPDATE " . HC_TblPrefix . "registrants
						SET Name = '" . cIn($name) . "',
							Email = '" . cIn($email) . "',
							Phone = '" . cIn($phone) . "',
							Address = '" . cIn($address) . "',
							Address2 = '" . cIn($address2) . "',
							City = '" . cIn($city) . "',
							State = '" . cIn($state) . "',
							Zip = '" . cIn($zip) . "'
						WHERE PkID = " . cIn($rID);
			doQuery($query);
			header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID . "&r=1&msg=4");
		} else {			
			$query = "	INSERT into " . HC_TblPrefix . "registrants(Name, Email, Phone, Address, Address2, City, State, Zip, EventID, IsActive, RegisteredAt)
						Values('" . cIn($name) . "',
								'" . cIn($email) . "',
								'" . cIn($phone) . "',
								'" . cIn($address) . "',
								'" . cIn($address2) . "',
								'" . cIn($city) . "',
								'" . cIn($state) . "',
								'" . cIn($zip) . "',
								" . cIn($eID) . ",
								1,
								NOW() )";
			
			doQuery($query);
			header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID . "&r=1&msg=3");
		}//end if
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "registrants WHERE PkID = " . $_GET['dID']);
		header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=5");
	}//end if	?>
