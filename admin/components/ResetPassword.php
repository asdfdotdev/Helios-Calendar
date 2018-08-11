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
