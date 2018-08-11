<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	if(!isset($_POST['username'])){header('Location: ' . CalAdminRoot);exit();}
	
	$username = $_POST['username'];
	$password = md5(md5($_POST['password']) . $_POST['username']);
	$com = cIn(htmlspecialchars(strip_tags($_POST['com'])));
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($username) ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$found_pwd = mysql_result($result,0,4);
		
		if($found_pwd == $password){
			if(mysql_result($result,0,5) > 0){
				startNewSession();
				$_SESSION[$hc_cfg00 . 'hc_whoami'] = md5(md5($_SERVER['HTTP_USER_AGENT']) . session_id());
				$_SESSION[$hc_cfg00 . 'AdminLoggedIn'] = true;
				$_SESSION[$hc_cfg00 . 'AdminPkID'] = mysql_result($result,0,0);
				$_SESSION[$hc_cfg00 . 'AdminFirstName'] = mysql_result($result,0,1);
				$_SESSION[$hc_cfg00 . 'AdminLastName'] = mysql_result($result,0,2);
				$_SESSION[$hc_cfg00 . 'AdminEmail'] = mysql_result($result,0,3);
				$_SESSION[$hc_cfg00 . 'Instructions'] = mysql_result($result,0,9);
				$_SESSION[$hc_cfg00 . 'LangSet'] = strtolower($_POST['langType']);
				
				//	MCImageManager Session Variables (Overrides Config File)
				$_SESSION['isLoggedIn'] = $_SESSION[$hc_cfg00 . 'AdminLoggedIn']; 
				$_SESSION['imagemanager.preview.wwwroot'] = "../../../../uploads";
				$_SESSION['imagemanager.preview.urlprefix'] = CalRoot . "/uploads";
				$_SESSION['imagemanager.filesystem.rootpath'] = "../../../../uploads";
				
				doQuery("UPDATE " . HC_TblPrefix . "admin SET LoginCnt = LoginCnt + 1, LastLogin = NOW() WHERE PkID = " . $_SESSION[$hc_cfg00 . 'AdminPkID']);
				doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID, IP, Client, LoginTime) Values('" . $_SESSION[$hc_cfg00 . 'AdminPkID'] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["HTTP_USER_AGENT"] . "', NOW())");
				
				if(mysql_result($result,0,10) != ''){
					doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = NULL WHERE PkID = " . $_SESSION[$hc_cfg00 . 'AdminPkID']);
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=' . $com);
				exit();
			} else {
				$msg = 1;
			}//end if
		} else {
			$msg = 2;
		}//end if
	} else {
		$msg = 1;
	}//end if
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/login.php');
	
	$headers = "From: " . CalAdminEmail . "\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Reply-To: " . CalAdminEmail . "\n";
	$headers .= "Content-Type: text/html; charset=UTF-8;\n";
	
	$subject = $hc_lang_login['FailedSubject'];
	
	$message = $hc_lang_login['FailedMsg'];
	$message .= "<br><br>" . $hc_lang_login['Username'] . " " . $_POST['username'] . "<br>" . $hc_lang_login['IP'] . " " . $_SERVER["REMOTE_ADDR"] . "<br>" . $hc_lang_login['Time'] . " " . date("F d, Y \a\\t h:i A");
	
	mail(CalAdminEmail, $subject, $message, $headers);
	header('Location: ' . CalAdminRoot . '/index.php?msg=' . $msg);?>