<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	hookDB();
	
	if(isset($_POST['catID'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$occupation = $_POST['occupation'];
		$zip = $_POST['zip'];
		$catID = $_POST['catID'];
		$guid = $_POST['guid'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . $guid . "'");
		
		if(hasRows($result)){
		$uID = mysql_result($result,0,0);
		
			$query = "	UPDATE " . HC_TblPrefix . "users
						SET FirstName = '" . $firstname . "',
							LastName = '" . $lastname . "',
							OccupationID = '" . $occupation . "',
							Zip = '" . $zip . "'
						WHERE GUID = '" . $guid . "'";
			doQuery($query);
			
			doQuery("DELETE FROM " . HC_TblPrefix . "usercategories WHERE UserID = " . $uID);
			
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) VALUES('" . $uID . "', '" . $val . "')");
			}//end while
			
			header('Location: ' . CalRoot . '/index.php?com=editreg&guid=' . $guid . '&msg=1');
			
		} else {
		
			header('Location: ' . CalRoot . '/index.php?com=signup');
		
		}//end if
		
	}//end if
?>