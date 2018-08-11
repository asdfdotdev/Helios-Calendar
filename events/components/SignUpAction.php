<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	checkIt();
	
	$proof = "";
	if(isset($_POST['proof'])){$proof = $_POST['proof'];}
	spamIt($proof, 4);
	
	if(isset($_POST['catID'])){
		$firstname = $_POST['hc_f1'];
		$lastname = $_POST['hc_f2'];
		$email = $_POST['hc_f3'];
		$occupation = $_POST['occupation'];
		$zip = $_POST['hc_f4'];
		$catID = $_POST['catID'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE email = '" . cIn($email) . "'");
		$row_cnt = mysql_num_rows($result);
		
		
		if($row_cnt < 1){
			$query = "	INSERT INTO " . HC_TblPrefix . "users(FirstName, LastName, Email, OccupationID, Zip, IsRegistered, GUID, RegisteredAt, RegisterIP)
						VALUES(	'" . cIn($firstname) . "',
								'" . cIn($lastname) . "',
								'" . cIn($email) . "',
								'" . cIn($occupation) . "',
								'" . cIn($zip) . "',
								0, 
								MD5(UNIX_TIMESTAMP() + RAND(UNIX_TIMESTAMP()) * (RAND()*1000000) ), 
								NOW(),
								'" . $_SERVER["REMOTE_ADDR"] . "')";
			doQuery($query);
			
			$result = doQuery("select last_insert_id() from " . HC_TblPrefix . "users");
			$newID = mysql_result($result,0,0);
			
			$result = doQuery("select GUID from " . HC_TblPrefix . "users where pkid = " . cIn($newID));
			$GUID = mysql_result($result,0,0);
			
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) VALUES('" . cIn($newID) . "', '" . cIn($val) . "')");
			}//end while
			
			$subject = CalName . " Account Activation";
			$from = CalAdminEmail;
			$msg = $firstname . ",";
			$msg .= "\n\nYour Account Activation Link:\n" . CalRoot . "/a.php?a=" . $GUID . "\n"
					. "\n\nThank you for signing up for the " . CalName . " Event Newsletter. You are just one step away from receiving all of our newest event information directly in your inbox."
					. "\n\nTo complete the registration process please click on the link above. If your email client does not support HTML you can simply copy and paste the entire web address below into your web browser."
					. "\n\nWhy do we require you to follow the link below? To safeguard your privacy. We want to make sure it was actually you who signed up your email address. So if you didn't signup, or have changed your mind, simply delete this email and you will hear nothing further from us in regards to our Event Alert Newsletter."
					. "\n\nIf you have any questions please contact " . CalAdmin . " via email at " . CalAdminEmail
					. "\n\nThe " . CalName . " Staff";
					
			mail("$email", "$subject", "$msg", "From: $from");
			
			header('Location: ' . CalRoot . '/index.php?com=signup&msg=1');
			
		} else {
		
		header('Location: ' . CalRoot . '/index.php?com=signup&msg=2&fname=' . urlencode($firstname) . '&lname=' . urlencode($lastname) . '&occ=' . urlencode($occupation) . '&zip=' . urlencode($zip) );
		
		}//end if
		
	}//end if
?>