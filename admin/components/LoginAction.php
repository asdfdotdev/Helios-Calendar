<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$com = $_POST['com'];
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE email = '" . $username ."' AND IsActive = 1");
	
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
				
				header('Location: ' . CalAdminRoot . '/index.php?com=' . $com);
			} else {
				/* account not active */
				header('Location: ' . CalAdminRoot . '/index.php?msg=1&username='. urlencode($username));
			}//end if
			
		} else {
			/* wrong password */
			header('Location: ' . CalAdminRoot . '/index.php?msg=2&username='. urlencode($username));
			
		}//end if
		
	} else {
		/* username not found */
		header('Location: ' . CalAdminRoot . '/index.php?msg=1&username='. urlencode($username));
		
	}//end if
?>
