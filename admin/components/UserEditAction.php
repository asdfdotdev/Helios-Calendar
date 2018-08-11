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
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$uID = $_POST['uID'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$oldEmail = $_POST['oldEmail'];
		$occupation = $_POST['occupation'];
		$birthyear = $_POST['birthyear'];
		$gender = $_POST['gender'];
		$referral = $_POST['referral'];
		$zip = $_POST['zip'];
		
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE PkID = " . cIn($uID));
		
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE Email = '" . cIn($email) . "'");
			
			if((hasRows($result)) && ($email != $oldEmail)){
				doQuery("UPDATE " . HC_TblPrefix . "users
							SET FirstName = '" . cIn($firstname) . "',
								LastName = '" . cIn($lastname) . "',
								OccupationID = '" . cIn($occupation) . "',
								Zip = '" . cIn($zip) . "',
								BirthYear = '" . cIn($birthyear) . "',
								Gender = '" . cIn($gender) . "',
								Referral = '" . cIn($referral) . "'
							WHERE PkID = " . cIn($uID));
				
				doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . cIn($uID) . "'");
				
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
						foreach ($catID as $val){
							doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
						}//end while
				}//end if
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=3&uID=' . $uID);
				
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "users
							SET FirstName = '" . cIn($firstname) . "',
								LastName = '" . cIn($lastname) . "',
								Email = '" . cIn($email) . "',
								OccupationID = '" . cIn($occupation) . "',
								Zip = '" . cIn($zip) . "',
								BirthYear = '" . cIn($birthyear) . "',
								Gender = '" . cIn($gender) . "',
								Referral = '" . cIn($referral) . "'
							WHERE PkID = " . cIn($uID));
				
				doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . cIn($uID) . "'");
				
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
						foreach ($catID as $val){
							doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
						}//end while
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=1&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE Email = '" . cIn($email) . "'");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0 AND ($email != $oldEmail)){
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=4&fname=' . urlencode($firstname) . '&lname=' . urlencode($lastname));
				
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "users(FirstName, LastName, Email, 
						 OccupationID, Zip, BirthYear, Gender, Referral, IsRegistered, GUID, AddedBy, RegisteredAt, RegisterIP)
							VALUES(	'" . cIn($firstname) . "',
									'" . cIn($lastname) . "',
									'" . cIn($email) . "',
									'" . cIn($occupation) . "',
									'" . cIn($zip) . "',
									'" . cIn($birthyear) . "',
									'" . cIn($gender) . "',
									'" . cIn($referral) . "',
									1, 
									MD5(UNIX_TIMESTAMP() + RAND(UNIX_TIMESTAMP()) * (RAND()*1000000) ), 
									'" . $_SESSION['AdminPkID'] . "',
									NOW(),
									'" . $_SERVER["REMOTE_ADDR"] . "')");
				
				$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "users");
				$uID = mysql_result($result,0,0);
				
				doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . cIn($uID) . "'");
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
						foreach ($catID as $val){
							doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
						}//end while
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=2&uID=' . $uID);
			}//end if
			
		}//end if
		
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . cIn($_GET['dID']) . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "users WHERE PkID = '" . cIn($_GET['dID']) . "'");
		
		header('Location: ' . CalAdminRoot . '/index.php?com=userbrowse&msg=1');
		
	}//end if
?>