<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$aID = 0;
	if(isset($_POST['aID'])){
		$aID = $_POST['aID'];
	}//end if
	
	$pass1 = '';
	if(isset($_POST['pass1'])){
		$pass1 = $_POST['pass1'];
	}//end if
	
	$pass2 = '';
	if(isset($_POST['pass2'])){
		$pass2 = $_POST['pass2'];
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = " . cIn($aID));
	if(hasRows($result)){
		if($pass1 == $pass2){
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Passwrd = '" . cIn(md5(md5($pass1) . mysql_result($result,0,3))) . "', PCKey = NULL WHERE PkID = " . cIn($aID));
			header('Location: ' . CalAdminRoot . '/?msg=4');
		} else {
			header('Location: ' . CalAdminRoot . '/?msg=5');
		}//end if
	} else {
		header('Location: ' . CalAdminRoot . '/');
	}//end if	?>
