<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
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
			$tools = $_POST['tools'];
			$reports = $_POST['reports'];
			$location = $_POST['editLoc'];
		}//end if
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = " . cIn($uID));
		
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if((hasRows($result)) AND ($email != $oldEmail)){
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . cIn($firstname) . "', LastName = '" . cIn($lastname) . "'  WHERE PkID = '" . cIn($uID) . "'");
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
									SET EventEdit = " . cIn($editEvent) . ",
										EventPending = " . cIn($eventPending) . ",
										EventCategory = " . cIn($eventCategory) . ",
										UserEdit = " . cIn($userEdit) . ",
										AdminEdit = " . cIn($adminEdit) . ",
										Newsletter = " . cIn($newsletter) . ",
										Settings = " . cIn($settings) . ",
										Tools = " . cIn($tools) . ",
										Reports = " . cIn($reports) . ",
										Locations = " . cIn($location) . "
								WHERE AdminID = " . cIn($uID));
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=1&uID=' . $uID);
				
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . cIn($firstname) . "', LastName = '" . cIn($lastname) . "', Email = '" . cIn($email) . "' WHERE PkID = '" . cIn($uID) . "'");
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
									SET EventEdit = " . cIn($editEvent) . ",
										EventPending = " . cIn($eventPending) . ",
										EventCategory = " . cIn($eventCategory) . ",
										UserEdit = " . cIn($userEdit) . ",
										AdminEdit = " . cIn($adminEdit) . ",
										Newsletter = " . cIn($newsletter) . ",
										Settings = " . cIn($settings) . ",
										Tools = " . cIn($tools) . ",
										Reports = " . cIn($reports) . ",
										Locations = " . cIn($location) . "
								WHERE AdminID = " . cIn($uID));
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=3&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if(1 == 2){
			//if(hasRows($result)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=2&firstname=' . urlencode($firstname) . '&lastname=' . urlencode($lastname) . '&email=' . urlencode($email));
				
			} else {
				$pwKey = md5(date("U"));
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, IsActive, PCKey) VALUES('" . cIn($firstname) . "', '" . cIn($lastname) . "', NULL, '" . cIn($email) . "', 1, '" . cIn($pwKey) . "')");
				$result = doQuery("SELECT LAST_INSERT_ID()");
				$uID = mysql_result($result,0,0);
				
				if($_SESSION['AdminPkID'] != $uID){
					doQuery("INSERT INTO " . HC_TblPrefix . "adminpermissions(EventEdit, EventPending, EventCategory, UserEdit, AdminEdit, Newsletter, Settings, Tools, Reports, Locations, AdminID, IsActive)
							VALUES(	" . cIn($editEvent) . ",
									" . cIn($eventPending) . ",
									" . cIn($eventCategory) . ",
									" . cIn($userEdit) . ",
									" . cIn($adminEdit) . ",
									" . cIn($newsletter) . ",
									" . cIn($settings) . ",
									" . cIn($tools) . ",
									" . cIn($reports) . ",
									" . cIn($location) . ",
									" . cIn($uID) . ",
									1)");
				}//end if
				
				include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/admin.php');
				
				$headers = "From: " . CalAdminEmail . "\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Reply-To: " . CalAdminEmail . "\n";
				$headers .= "Content-Type: text/html; charset=UTF-8;\n";
				
				$subject = CalName . " " . $hc_lang_admin['CreateSubject'];
				
				$message = $hc_lang_admin['CreateLink'] . " <a href=\"" . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey . "\">" . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey . "</a>";
				$message .= "<br><br>" . $hc_lang_admin['CreateEmail'];
				$message .= "<br><br>" . $hc_lang_admin['CreateQuestions'] . " " . CalAdmin . " - " . CalAdminEmail;
				
				mail($email, "$subject", "$message", "$headers");
				
				header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=4&uID=' . $uID);
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
	}//end if	?>