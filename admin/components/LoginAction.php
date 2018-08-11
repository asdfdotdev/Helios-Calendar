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
	
	$username = $_POST['username'];
	$password = md5(md5($_POST['password']) . $_POST['username']);
	$com = $_POST['com'];
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($username) ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$found_pwd = mysql_result($result,0,4);
		
		if ($found_pwd == $password){
			if(mysql_result($result,0,5) > 0){
				/* login correct */
				$_SESSION['AdminLoggedIn'] = true;
				$_SESSION['AdminPkID'] = mysql_result($result,0,0);
				$_SESSION['AdminFirstName'] = mysql_result($result,0,1);
				$_SESSION['AdminLastName'] = mysql_result($result,0,2);
				$_SESSION['AdminEmail'] = mysql_result($result,0,3);
				$_SESSION['Instructions'] = mysql_result($result,0,9);
				$_SESSION['LangSet'] = strtolower($_POST['langType']);
				
				doQuery("UPDATE " . HC_TblPrefix . "admin SET LoginCnt = LoginCnt + 1, LastLogin = NOW() WHERE PkID = " . $_SESSION['AdminPkID']);
				doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID, IP, Client, LoginTime) Values('" . $_SESSION['AdminPkID'] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["HTTP_USER_AGENT"] . "', NOW())");
				
				if(mysql_result($result,0,10) != ''){
					doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = NULL WHERE PkID = " . $_SESSION['AdminPkID']);
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=' . $com);
				exit;
			} else {
				$msg = 1;
			}//end if
		} else {
			$msg = 2;
		}//end if
	} else {
		$msg = 1;
	}//end if
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/login.php');
	
	$headers = "From: " . CalAdminEmail . "\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Reply-To: " . CalAdminEmail . "\n";
	$headers .= "Content-Type: text/html; charset=UTF-8;\n";
	
	$subject = $hc_lang_login['FailedSubject'];
	
	$message = $hc_lang_login['FailedMsg'];
	$message .= "<br><br>" . $hc_lang_login['Username'] . " " . $_POST['username'] . "<br>" . $hc_lang_login['Password'] . " " . $_POST['password'] . "<br>" . $hc_lang_login['IP'] . " " . $_SERVER["REMOTE_ADDR"] . "<br>" . $hc_lang_login['Time'] . " " . date("F d, Y \a\\t h:i A");
	
	mail(CalAdminEmail, $subject, $message, $headers);
	header('Location: ' . CalAdminRoot . '/index.php?msg=' . $msg);?>