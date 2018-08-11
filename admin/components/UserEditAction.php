<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	if(!isset($_GET['dID'])){
		$uID = $_POST['uID'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$oldEmail = $_POST['oldEmail'];
		$occupation = $_POST['occupation'];
		$zip = $_POST['zip'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE PkID = " . $uID);
		
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE Email = '" . $email . "'");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0 AND ($email != $oldEmail)){
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=3&uID=' . $uID . '&name=' . urlencode($name) . '&email=' . urlencode($email));
				
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "users
							SET FirstName = '" . $firstname . "',
								LastName = '" . $lastname . "',
								Email = '" . $email . "',
								OccupationID = '" . $occupation . "',
								Zip = '" . $zip . "'
							WHERE PkID = " . $uID);
				
				doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . $uID . "'");
				
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
						foreach ($catID as $val){
							doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) Values('" . $uID . "', '" . $val . "')");
						}//end while
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=1&uID=' . $uID);
			}//end if
			
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE Email = '" . $email . "'");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0  AND ($email != $oldEmail)){
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=4&fname=' . urlencode($firstname) . '&lname=' . urlencode($lastname) . '&occ=' . urlencode($occupation ). '&zip=' . urlencode($zip) );
				
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "users(FirstName, LastName, Email, 
						 OccupationID, Zip, IsRegistered, GUID, AddedBy, RegisteredAt, RegisterIP)
							VALUES(	'" . $firstname . "',
									'" . $lastname . "',
									'" . $email . "',
									'" . $occupation . "',
									'" . $zip . "',
									1, 
									MD5(UNIX_TIMESTAMP() + RAND(UNIX_TIMESTAMP()) * (RAND()*1000000) ), 
									'" . $_SESSION['AdminPkID'] . "',
									NOW(),
									'" . $_SERVER["REMOTE_ADDR"] . "')");
				
				$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "users");
				$uID = mysql_result($result,0,0);
				
				doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . $uID . "'");
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
						foreach ($catID as $val){
							doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) Values('" . $uID . "', '" . $val . "')");
						}//end while
				}//end if
				
				header('Location: ' . CalAdminRoot . '/index.php?com=useredit&msg=2&uID=' . $uID);
			}//end if
			
		}//end if
		
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = '" . $_GET['dID'] . "'");
		doQuery("DELETE FROM " . HC_TblPrefix . "users WHERE PkID = '" . $_GET['dID'] . "'");
		
		header('Location: ' . CalAdminRoot . '/index.php?com=userbrowse&msg=1');
		
	}//end if
?>