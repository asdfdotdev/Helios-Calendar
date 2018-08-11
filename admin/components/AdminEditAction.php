<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
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
			$reports = $_POST['reports'];
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
										Reports = " . cIn($reports) . "
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
										Reports = " . cIn($reports) . "
								WHERE AdminID = " . cIn($uID));
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=3&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if(hasRows($result)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=2&firstname=' . urlencode($firstname) . '&lastname=' . urlencode($lastname) . '&email=' . urlencode($email));
				
			} else {
				$pwKey = md5(date("U"));
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, IsActive, PCKey) VALUES('" . cIn($firstname) . "', '" . cIn($lastname) . "', NULL, '" . cIn($email) . "', 1, '" . cIn($pwKey) . "')");
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
				
				// Send Password to New Admin exit($pWord);
				
				
				$headers = "From: " . CalAdminEmail . "\n";
				$headers .= "MIME-Version: 1.0\n";
				$headers .= "Reply-To: " . CalAdminEmail . "\n";
				$headers .= "Content-Type: text/html; charset=iso-8859-1;\n";
				
				$subject = CalName . " Admin Account Created";
				
				$message = "An admin account has been created for you at our event calendar. Please copy and paste the URL below into your browser and you will be able to create your password and login to the admin site.";
				$message .= "<br><br>If you have any questions please contact " . CalAdmin . " at " . CalAdminEmail;
				
				$message .= "<br><br>" . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey;
				
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