<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
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