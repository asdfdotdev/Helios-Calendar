<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	
	if(!isset($_GET['dID'])){
		$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn($_POST['uID']) : 0;
		$firstname = (isset($_POST['firstname'])) ? cIn($_POST['firstname']) : '';
		$lastname = (isset($_POST['lastname'])) ? cIn($_POST['lastname']) : '';
		$email = (isset($_POST['email'])) ? cIn($_POST['email']) : '';
		$oldEmail = (isset($_POST['oldEmail'])) ? cIn($_POST['oldEmail']) : '';
		$notices = (isset($_POST['notices'])) ? $_POST['notices'] : array();
		
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
			$comments = $_POST['comments'];
		}//end if
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $uID . "'");
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . $email . "'");
			if((hasRows($result)) AND ($email != $oldEmail)){
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . $firstname . "', LastName = '" . $lastname . "'  WHERE PkID = '" . $uID . "'");
				$msgID = 1;
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . $firstname . "', LastName = '" . $lastname . "', Email = '" . $email . "' WHERE PkID = '" . $uID . "'");
				$msgID = 3;
			}//end if

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
							Locations = " . cIn($location) . ",
							Comments = " . cIn($comments) . "
						WHERE AdminID = " . cIn($uID));
			}//end if
			
			doQuery("DELETE FROM " . HC_TblPrefix . "adminnotices WHERE AdminID = '" . cIn($uID) . "'");
			foreach($notices as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "adminnotices(AdminID, TypeID, IsActive)
						VALUES(" . $uID . "," . cIn($val) . ",1)");
			}//end for
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if(hasRows($result)){
				header('Location: ' . CalAdminRoot . '/index.php?com=adminedit&msg=2');
				exit();
			} else {
				$pwKey = md5(date("U"));
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, SuperAdmin, IsActive, PCKey)
						VALUES('" . $firstname . "','" . $lastname . "',NULL,'" . $email . "',0,1,'" . $pwKey . "')");
				$result = doQuery("SELECT LAST_INSERT_ID()");
				$uID = mysql_result($result,0,0);

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

				foreach($notices as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "adminnotices(AdminID, TypeID, IsActive) VALUES(" . cIn($uID) . "," . cIn($val) . ",1)");
				}//end for

				include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/admin.php');
				
				$subject = CalName . " " . $hc_lang_admin['CreateSubject'];
				$message = '<p>' . $hc_lang_admin['CreateLink'] . ' <a href="' . CalAdminRoot . '/index.php?lp=2&k=' . $pwKey . '">' . CalAdminRoot . '/index.php?lp=2&k=' . $pwKey . '</a></p>';
				$message .= '<p>' . $hc_lang_admin['CreateEmail'] . '</p>';
				if(count($notices) > 0){
					$message .= '<p>' . $hc_lang_admin['CreateNotices'] . '<ul>';
					$message .= in_array(0, $notices) ? '<li>' . $hc_lang_admin['NoticeEventE'] . '</li>' : '';
					$message .= in_array(1, $notices) ? '<li>' . $hc_lang_admin['NoticeCommentE'] . '</li>' : '';
					$message .= in_array(2, $notices) ? '<li>' . $hc_lang_admin['NoticeLoginE'] . '</li>' : '';
					$message .= '</ul></p>';
				}//end if
				$message .= '<p>' . $hc_lang_admin['CreateQuestions'] . ' ' . trim($_SESSION['AdminFirstName'] . ' ' . $_SESSION['AdminLastName']) . ' (' . $_SESSION['AdminEmail'] . ')</p>';

				reMail(trim($firstname . ' ' . $lastname), $email, $subject, $message, $hc_cfg79, $hc_cfg78);
				
				$msgID = 4;
			}//end if
		}//end if
		
		header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=' . $msgID . '&uID=' . $uID);
	} else {
		if($_GET['dID'] == $_SESSION['AdminPkID']){
			header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=2');
			exit();
		}//end if
		
		doQuery("DELETE FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = '" . cIn($_GET['dID']) . "'");
		header('Location: ' . CalAdminRoot . '/index.php?com=adminbrowse&msg=1');
	}//end if	?>