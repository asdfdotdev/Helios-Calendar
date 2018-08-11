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
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = " . $uID);
		$row_cnt = mysql_num_rows($result);
		
		if($row_cnt > 0){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . $email . "'");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0 AND ($email != $oldEmail)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=3&uID=' . $uID);
				
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . $firstname . "', LastName = '" . $lastname . "', Email = '" . $email . "', Passwrd= '" . $password . "'  WHERE PkID = '" . $uID . "'");
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
									SET EventEdit = " . $editEvent . ",
										EventPending = " . $eventPending . ",
										EventCategory = " . $eventCategory . ",
										UserEdit = " . $userEdit . ",
										AdminEdit = " . $adminEdit . ",
										Newsletter = " . $newsletter . ",
										Settings = " . $settings . ",
										Reports = " . $reports . "
								WHERE AdminID = " . $uID);
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=1&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE Email = '" . $email . "'");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0  AND ($email != $oldEmail)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=4&firstname=' . urlencode($firstname) . '&lastname=' . urlencode($lastname) . '&email=' . urlencode($email));
				
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, IsActive) VALUES('" . $firstname . "', '" . $lastname . "', '" . $password . "', '" . $email . "', 1)");
				$result = doQuery("SELECT LAST_INSERT_ID()");
				$uID = mysql_result($result,0,0);
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("INSERT INTO " . HC_TblPrefix . "adminpermissions(EventEdit, EventPending, EventCategory, UserEdit, AdminEdit, Newsletter, Settings, Reports, AdminID, IsActive)
							VALUES(	" . $editEvent . ",
									" . $eventPending . ",
									" . $eventCategory . ",
									" . $userEdit . ",
									" . $adminEdit . ",
									" . $newsletter . ",
									" . $settings . ",
									" . $reports . ",
									" . $uID . ",
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
		
		doQuery("DELETE FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $_GET['dID'] . "'");
		doQUery("DELETE FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = '" . $_GET['dID'] . "'");
		header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=1');
	}//end if
?>