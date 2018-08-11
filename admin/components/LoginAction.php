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
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	
	if(!isset($_POST['username'])){header('Location: ' . CalAdminRoot);exit();}
	
	$username = $_POST['username'];
	$password = md5(md5($_POST['password']) . $_POST['username']);
	$com = cIn(htmlspecialchars(strip_tags($_POST['com'])));
	$msg = $unlocked = 1;

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($username) ."' AND IsActive = 1");

	if(hasRows($result)){
		$resultF = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "adminloginhistory WHERE AdminID = '" . mysql_result($result,0,0) . "' AND LoginTime > subdate(NOW(), INTERVAL 24 HOUR) AND IsFail = 1");
		if(mysql_result($resultF,0,0) >= $hc_cfg80){
			header('Location: ' . CalAdminRoot . '/index.php?lmsg=2');
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

					//	MCImageManager Session Variables (Overrides Config File)
					$_SESSION['isLoggedIn'] = $_SESSION['AdminLoggedIn'];
					$_SESSION['imagemanager.preview.wwwroot'] = "../../../../uploads";
					$_SESSION['imagemanager.preview.urlprefix'] = CalRoot . "/uploads";
					$_SESSION['imagemanager.filesystem.rootpath'] = "../../../../uploads";

					doQuery("UPDATE " . HC_TblPrefix . "admin SET LoginCnt = LoginCnt + 1, PCKey = NULL, Access = '" . cIn(md5(session_id())) . "', LastLogin = NOW() WHERE PkID = " . $_SESSION['AdminPkID']);
					doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID, IP, Client, LoginTime) Values('" . $_SESSION['AdminPkID'] . "', '" . cIn(strip_tags($_SERVER["REMOTE_ADDR"])) . "', '" . cIn(strip_tags($_SERVER["HTTP_USER_AGENT"])) . "', NOW())");

					startNewSession();

					header('Location: ' . CalAdminRoot . '/index.php?com=' . $com);
					exit();
				}//end if
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID,IP,Client,LoginTime,IsFail) Values('" . mysql_result($result,0,0) . "','" . $_SERVER["REMOTE_ADDR"] . "','" . $_SERVER["HTTP_USER_AGENT"] . "',NOW(),1)");
			}//end if
			
			$resultE = doQuery("SELECT a.FirstName, a.LastName, a.Email
							FROM " . HC_TblPrefix . "adminnotices n
								LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
							WHERE a.IsActive = 1 AND n.IsActive = 1 AND n.TypeID = 2");
			if(hasRows($resultE)){
				include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/login.php');

				$toNotice = array();
				while($row = mysql_fetch_row($resultE)){
					$toNotice[trim($row[0] . ' ' .$row[1])] = $row[2];
				}//end while

				$subject = $hc_lang_login['FailedSubject'];
				$message = '<p>' . $hc_lang_login['FailedMsg'] . '</p>';
				$message .= '<p><b>' . $hc_lang_login['Username'] . '</b> ' . $_POST['username'] . '<br /><b>' . $hc_lang_login['IP'] . '</b> ' . $_SERVER["REMOTE_ADDR"] . '<br /><b>' . $hc_lang_login['Time'] . '</b> ' . date("Y-m-d H:i:s ") . '<br /><b>' . $hc_lang_login['UserAgent'] . '</b> ' . $_SERVER["HTTP_USER_AGENT"];
				$message .= ($unlocked == 0) ? '<p>' . $hc_lang_login['LockNotice'] . '</p>' : '';
				$message .= '<p><a href="' . CalAdminRoot . '/">' . CalAdminRoot . '/</a></p>';

				reMail('', $toNotice, $subject, $message);
			}//end if
		}//end if
	}//end if

	header('Location: ' . CalAdminRoot . '/index.php?lmsg=' . $msg);?>