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
	checkIt();
	
	$proof = "";
	if(isset($_POST['proof'])){$proof = $_POST['proof'];}
	spamIt($proof, 4);
	
	if(isset($_POST['catID'])){
		$firstname = $_POST['hc_f1'];
		$lastname = $_POST['hc_f2'];
		$occupation = $_POST['occupation'];
		$zip = $_POST['hc_f4'];
		$catID = $_POST['catID'];
		$guid = $_POST['guid'];
		$birthyear = $_POST['hc_fa'];
		$gender = $_POST['hc_fb'];
		$referral = $_POST['hc_fc'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($guid) . "'");
		
		if(hasRows($result)){
		$uID = mysql_result($result,0,0);
			$query = "	UPDATE " . HC_TblPrefix . "users
						SET FirstName = '" . cIn($firstname) . "',
							LastName = '" . cIn($lastname) . "',
							OccupationID = '" . cIn($occupation) . "',
							Zip = '" . cIn($zip) . "',
							BirthYear = '" . cIn($birthyear) . "',
							Gender = '" . cIn($gender) . "',
							Referral = '" . cIn($referral) . "'						
						WHERE GUID = '" . cIn($guid) . "'";
			doQuery($query);
			doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = " . cIn($uID));
			
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) VALUES('" . cIn($uID) . "', '" . cIn($val) . "')");
			}//end while
			header('Location: ' . CalRoot . '/index.php?com=editreg&guid=' . cIn($guid) . '&msg=1');
		} else {
			header('Location: ' . CalRoot . '/index.php?com=signup');
		}//end if
	}//end if?>