<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
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
				
				doQuery("UPDATE " . HC_TblPrefix . "admin SET LoginCnt = LoginCnt + 1, LastLogin = now() WHERE PkID = " . $_SESSION['AdminPkID']);
				doQuery("INSERT INTO " . HC_TblPrefix . "adminloginhistory(AdminID, IP, Client, LoginTime) Values('" . $_SESSION['AdminPkID'] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["HTTP_USER_AGENT"] . "', now())");
				
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
	
	$headers = "From: " . CalAdminEmail . "\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Reply-To: " . CalAdminEmail . "\n";
	$headers .= "Content-Type: text/html; charset=UTF-8;\n";
	
	$subject = "Admin Login Failure";
	
	$message = "A failed login attempt has been made at your Helios Calendar, the details of this attempt are below.";
	$message .= "<br><br>Username: " . $_POST['username'] . "<br>Password: " . $_POST['password'] . "<br>IP: " . $_SERVER["REMOTE_ADDR"] . "<br>Time :" . date("F d, Y \a\\t h:i A");
	
	mail(CalAdminEmail, "$subject", "$message", "$headers");
	
	header('Location: ' . CalAdminRoot . '/index.php?msg=' . $msg . '&username='. urlencode($username));?>
