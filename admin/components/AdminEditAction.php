<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	if(!isset($_GET['dID'])){
		if(isset($_POST['uID'])){
			$uID = $_POST['uID'];
		} else {
			$uID = 0;
		}//end if
		
		if(isset($_POST['firstname'])){
			$firstname = $_POST['firstname'];
		} else {
			$firstname = "";
		}//end if
		
		if(isset($_POST['lastname'])){
			$lastname = $_POST['lastname'];
		} else {
			$lastname = "";
		}//end if
		
		if(isset($_POST['password'])){
			$password = $_POST['password'];
		} else {
			$password = "";
		}//end if
		
		if(isset($_POST['email'])){
			$email = $_POST['email'];
		} else {
			$email = "";
		}//end if
		
		if(isset($_POST['oldEmail'])){
			$oldEmail = $_POST['oldEmail'];
		} else {
			$oldEmail = "";
		}//end if
		
		if($_SESSION['AdminPkID'] != $uID){
			$editEvent = $_POST['editEvent'];
			$eventPending = $_POST['eventPending'];
			$eventCategory = $_POST['eventCategory'];
			$userEdit = $_POST['userEdit'];
			$adminEdit = $_POST['adminEdit'];
			$newsletter = $_POST['newsletter'];
			$settings = $_POST['settings'];
			$reports = $_POST['reports'];
		}//end if
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = " . cIn($uID));
		
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if((hasRows($result)) AND ($email != $oldEmail)){
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . cIn($firstname) . "', LastName = '" . cIn($lastname) . "', Passwrd= '" . cIn($password) . "'  WHERE PkID = '" . cIn($uID) . "'");
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
									SET EventEdit = " . cIn($editEvent) . ",
										EventPending = " . cIn($eventPending) . ",
										EventCategory = " . cIn($eventCategory) . ",
										UserEdit = " . cIn($userEdit) . ",
										AdminEdit = " . cIn($adminEdit) . ",
										Newsletter = " . cIn($newsletter) . ",
										Settings = " . cIn($settings) . ",
										Reports = " . cIn($reports) . "
								WHERE AdminID = " . cIn($uID));
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=3&uID=' . $uID);
				
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . cIn($firstname) . "', LastName = '" . cIn($lastname) . "', Email = '" . cIn($email) . "', Passwrd= '" . cIn($password) . "'  WHERE PkID = '" . cIn($uID) . "'");
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
									SET EventEdit = " . cIn($editEvent) . ",
										EventPending = " . cIn($eventPending) . ",
										EventCategory = " . cIn($eventCategory) . ",
										UserEdit = " . cIn($userEdit) . ",
										AdminEdit = " . cIn($adminEdit) . ",
										Newsletter = " . cIn($newsletter) . ",
										Settings = " . cIn($settings) . ",
										Reports = " . cIn($reports) . "
								WHERE AdminID = " . cIn($uID));
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=1&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if(hasRows($result)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=4&firstname=' . urlencode($firstname) . '&lastname=' . urlencode($lastname) . '&email=' . urlencode($email));
				
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, IsActive) VALUES('" . cIn($firstname) . "', '" . cIn($lastname) . "', '" . cIn($password) . "', '" . cIn($email) . "', 1)");
				$result = doQuery("SELECT LAST_INSERT_ID()");
				$uID = mysql_result($result,0,0);
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("INSERT INTO " . HC_TblPrefix . "adminpermissions(EventEdit, EventPending, EventCategory, UserEdit, AdminEdit, Newsletter, Settings, Reports, AdminID, IsActive)
							VALUES(	" . cIn($editEvent) . ",
									" . cIn($eventPending) . ",
									" . cIn($eventCategory) . ",
									" . cIn($userEdit) . ",
									" . cIn($adminEdit) . ",
									" . cIn($newsletter) . ",
									" . cIn($settings) . ",
									" . cIn($reports) . ",
									" . cIn($uID) . ",
									1)");
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=2&uID=' . $uID);
			}//end if
			
		}//end if
		
	} else {
		
		if($_GET['dID'] == $_SESSION['AdminPkID']){
			header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=2');
			exit;
		}//end if
		
		doQuery("DELETE FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = '" . cIn($_GET['dID']) . "'");
		header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=1');
	}//end if
?>