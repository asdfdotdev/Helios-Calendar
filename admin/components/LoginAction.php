<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	action_headers();
	post_only();
	
	include(HCLANG.'/admin/login.php');
	
	$username = (isset($_POST['username'])) ? cIn(strip_tags($_POST['username'])) : '';
	$password = (isset($_POST['password'])) ? md5(md5(cIn(strip_tags($_POST['password']))) . $username) : '';
	$com = (isset($_POST['com'])) ? cIn(htmlspecialchars(strip_tags($_POST['com']))) : '';
	$msg = $unlocked = 1;

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE email = '" . $username ."' AND IsActive = 1");

	if(hasRows($result)){
		$resultF = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "adminloginhistory WHERE AdminID = '" . cIn(mysql_result($result,0,0)) . "' AND LoginTime > subdate(NOW(), INTERVAL 24 HOUR) AND IsFail = 1");
		if(mysql_result($resultF,0,0) >= $hc_cfg[80]){
			header('Location: ' . AdminRoot . '/index.php?lmsg=2');
			exit();
		} else {
			if(mysql_result($result,0,4) == $password){
				if(mysql_result($result,0,5) > 0){
					$_SESSION['AdminLoggedIn'] = true;
					$_SESSION['AdminPkID'] = mysql_result($result,0,0);
					$_SESSION['AdminFirstName'] = mysql_result($result,0,1);
					$_SESSION['AdminLastName'] = mysql_result($result,0,2);
					$_SESSION['AdminEmail'] = mysql_result($result,0,3);
					$_SESSION['Instructions'] = mysql_result($result,0,9);
					$_SESSION['LangSet'] = strtolower($_POST['langType']);
					$_SESSION['PasswordWarn'] = (mysql_result($result,0,12) == '' || ($hc_cfg[26] > 0 && daysDiff(mysql_result($result,0,12), date("Y-m-d")) >= $hc_cfg[26])) ? 1 : 0;

					//	MCImageManager Session Variables (Overrides Config File)
					$_SESSION['isLoggedIn'] = $_SESSION['AdminLoggedIn'];
					$_SESSION['imagemanager.preview.wwwroot'] = "../../../../uploads";
					$_SESSION['imagemanager.preview.urlprefix'] = CalRoot . "/uploads";
					$_SESSION['imagemanager.filesystem.rootpath'] = "../../../../uploads";

					doQuery("UPDATE " . HC_TblPrefix . "admin SET LoginCnt = LoginCnt + 1, PCKey = NULL, Access = '" . cIn(md5(session_id())) . "', LastLogin = NOW() WHERE PkID = '" . cIn($_SESSION['AdminPkID']) . "'");
					doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID, IP, Client, LoginTime) Values('" . $_SESSION['AdminPkID'] . "', '" . cIn(strip_tags($_SERVER["REMOTE_ADDR"])) . "', '" . cIn(strip_tags($_SERVER["HTTP_USER_AGENT"])) . "', NOW())");

					startNewSession();

					header('Location: ' . AdminRoot . '/index.php?com=' . $com);
					exit();
				}
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID,IP,Client,LoginTime,IsFail) Values('" . cIn(mysql_result($result,0,0)) . "','" . cIn(strip_tags($_SERVER["REMOTE_ADDR"])) . "','" . cIn(strip_tags($_SERVER["HTTP_USER_AGENT"])) . "',NOW(),1)");
			}
			
			$resultE = doQuery("SELECT a.FirstName, a.LastName, a.Email
							FROM " . HC_TblPrefix . "adminnotices n
								LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
							WHERE a.IsActive = 1 AND n.IsActive = 1 AND n.TypeID = 2");
			if(hasRows($resultE)){
				$toNotice = array();
				while($row = mysql_fetch_row($resultE)){
					$toNotice[trim($row[0] . ' ' .$row[1])] = $row[2];
				}

				$subject = $hc_lang_login['FailedSubject'];
				$message = '<p>' . $hc_lang_login['FailedMsg'] . '</p>';
				$message .= '<p><b>' . $hc_lang_login['Username'] . '</b> ' . $_POST['username'] . '<br /><b>' . $hc_lang_login['IP'] . '</b> ' . $_SERVER["REMOTE_ADDR"] . '<br /><b>' . $hc_lang_login['Time'] . '</b> ' . date("Y-m-d H:i:s ") . '<br /><b>' . $hc_lang_login['UserAgent'] . '</b> ' . $_SERVER["HTTP_USER_AGENT"];
				$message .= ($unlocked == 0) ? '<p>' . $hc_lang_login['LockNotice'] . '</p>' : '';
				$message .= '<p><a href="' . AdminRoot . '/">' . AdminRoot . '/</a></p>';

				reMail('', $toNotice, $subject, $message);
			}
		}
	}

	header('Location: ' . AdminRoot . '/index.php?lmsg=' . $msg);?>