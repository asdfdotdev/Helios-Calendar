<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	include(HCLANG.'/admin/admin.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	if(!check_form_token($token))
		go_home();
	
	if(!isset($_GET['dID'])){
		post_only();
		
		$aID = (isset($_POST['aID']) && is_numeric($_POST['aID'])) ? cIn(strip_tags($_POST['aID'])) : 0;
		$firstname = (isset($_POST['firstname'])) ? cIn($_POST['firstname']) : '';
		$lastname = (isset($_POST['lastname'])) ? cIn($_POST['lastname']) : '';
		$email = (isset($_POST['email'])) ? cIn($_POST['email']) : '';
		$oldEmail = (isset($_POST['oldEmail'])) ? cIn($_POST['oldEmail']) : '';
		$notices = (isset($_POST['notices'])) ? array_filter($_POST['notices'],'is_numeric') : array();
		
		if($_SESSION['AdminPkID'] != $aID){
			$editEvent = (isset($_POST['editEvent']) && is_numeric($_POST['editEvent'])) ? cIn(strip_tags($_POST['editEvent'])) : 0;
			$eventPending = (isset($_POST['eventPending']) && is_numeric($_POST['eventPending'])) ? cIn(strip_tags($_POST['eventPending'])) : 0;
			$eventCategory = (isset($_POST['eventCategory']) && is_numeric($_POST['eventCategory'])) ? cIn(strip_tags($_POST['eventCategory'])) : 0;
			$userEdit = (isset($_POST['userEdit']) && is_numeric($_POST['userEdit'])) ? cIn(strip_tags($_POST['userEdit'])) : 0;
			$adminEdit = (isset($_POST['adminEdit']) && is_numeric($_POST['adminEdit'])) ? cIn(strip_tags($_POST['adminEdit'])) : 0;
			$newsletter = (isset($_POST['newsletter']) && is_numeric($_POST['newsletter'])) ? cIn(strip_tags($_POST['newsletter'])) : 0;
			$settings = (isset($_POST['settings']) && is_numeric($_POST['settings'])) ? cIn(strip_tags($_POST['settings'])) : 0;
			$tools = (isset($_POST['tools']) && is_numeric($_POST['tools'])) ? cIn(strip_tags($_POST['tools'])) : 0;
			$reports = (isset($_POST['reports']) && is_numeric($_POST['reports'])) ? cIn(strip_tags($_POST['reports'])) : 0;
			$location = (isset($_POST['editLoc']) && is_numeric($_POST['editLoc'])) ? cIn(strip_tags($_POST['editLoc'])) : 0;
			$pages = (isset($_POST['pages']) && is_numeric($_POST['pages'])) ? cIn(strip_tags($_POST['pages'])) : 0;
		}
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $aID . "'");
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . $email . "'");
			if((hasRows($result)) AND ($email != $oldEmail)){
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . $firstname . "', LastName = '" . $lastname . "'  WHERE PkID = '" . $aID . "'");
				$msgID = 1;
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "admin SET FirstName = '" . $firstname . "', LastName = '" . $lastname . "', Email = '" . $email . "' WHERE PkID = '" . $aID . "'");
				$msgID = 3;
			}

			if($_SESSION['AdminPkID'] != $aID)
				doQuery("UPDATE " . HC_TblPrefix . "adminpermissions
						SET EventEdit = '" . $editEvent . "',
							EventPending = '" . $eventPending . "',
							EventCategory = '" . $eventCategory . "',
							UserEdit = '" . $userEdit . "',
							AdminEdit = '" . $adminEdit . "',
							Newsletter = '" . $newsletter . "',
							Settings = '" . $settings . "',
							Tools = '" . $tools . "',
							Reports = '" . $reports . "',
							Locations = '" . $location . "',
							Pages = '" . $pages . "'
						WHERE AdminID = '" . $aID . "'");
			
			doQuery("DELETE FROM " . HC_TblPrefix . "adminnotices WHERE AdminID = '" . cIn($aID) . "'");
			foreach($notices as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "adminnotices(AdminID, TypeID, IsActive) VALUES('" . $aID . "','" . cIn($val) . "',1)");
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE Email = '" . cIn($email) . "'");
			
			if(hasRows($result)){
				header('Location: ' . AdminRoot . '/index.php?com=adminedit&msg=2');
				exit();
			} else {
				$pwKey = md5(date("U"));
				doQuery("INSERT INTO " . HC_TblPrefix . "admin(FirstName, LastName, Passwrd, Email, SuperAdmin, IsActive, PCKey)
						VALUES('" . $firstname . "','" . $lastname . "',NULL,'" . $email . "',0,1,'" . $pwKey . "')");
				$result = doQuery("SELECT LAST_INSERT_ID()");
				$aID = mysql_result($result,0,0);

				doQuery("INSERT INTO " . HC_TblPrefix . "adminpermissions(EventEdit, EventPending, EventCategory, UserEdit, AdminEdit, Newsletter, Settings, Tools, Reports, Locations, Pages, AdminID, IsActive)
						VALUES(	'" . $editEvent . "',
								'" . $eventPending . "',
								'" . $eventCategory . "',
								'" . $userEdit . "',
								'" . $adminEdit . "',
								'" . $newsletter . "',
								'" . $settings . "',
								'" . $tools . "',
								'" . $reports . "',
								'" . $location . "',
								'" . $pages . "',
								'" . $aID . "',1)");

				foreach($notices as $val)
					doQuery("INSERT INTO " . HC_TblPrefix . "adminnotices(AdminID, TypeID, IsActive) VALUES('" . $aID . "','" . cIn($val) . "',1)");
				
				$subject = CalName . " " . $hc_lang_admin['CreateSubject'];
				$message = '<p>' . $hc_lang_admin['CreateLink'] . ' <a href="' . AdminRoot . '/index.php?lp=2&k=' . $pwKey . '">' . AdminRoot . '/index.php?lp=2&k=' . $pwKey . '</a></p>';
				$message .= '<p>' . $hc_lang_admin['CreateEmail'] . '</p>';
				if(count($notices) > 0){
					$message .= '<p>' . $hc_lang_admin['CreateNotices'] . '<ul>';
					$message .= in_array(0, $notices) ? '<li>' . $hc_lang_admin['NoticeEventE'] . '</li>' : '';
					$message .= in_array(1, $notices) ? '<li>' . $hc_lang_admin['NoticeLoginE'] . '</li>' : '';
					$message .= '</ul></p>';
				}
				$message .= '<p>' . $hc_lang_admin['CreateQuestions'] . ' ' . trim($_SESSION['AdminFirstName'] . ' ' . $_SESSION['AdminLastName']) . ' (' . $_SESSION['AdminEmail'] . ')</p>';

				reMail(trim($firstname.' '.$lastname), $email, $subject, $message, $hc_cfg[79], $hc_cfg[78]);
				
				$msgID = 4;
			}
		}
		
		header('Location: ' . AdminRoot . '/index.php?com=adminbrowse&aID=' . $aID . '&msg=' . $msgID);
	} else {
		$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(strip_tags($_GET['dID'])) : 0;
		
		if($dID == $_SESSION['AdminPkID']){
			header('Location: ' . AdminRoot . '/index.php?com=adminbrowse&msg=2');
			exit();}
		
		doQuery("DELETE FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $dID . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "adminpermissions WHERE AdminID = '" . $dID . "'");
		header('Location: ' . AdminRoot . '/index.php?com=adminbrowse&msg=1');
	}
?>