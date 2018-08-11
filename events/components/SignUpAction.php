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
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION[$hc_cfg00 . 'hc_cap']) ? $_SESSION[$hc_cfg00 . 'hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,4);
	
	if(isset($_POST['catID'])){
		$firstname = $_POST['hc_f1'];
		$lastname = $_POST['hc_f2'];
		$email = $_POST['hc_f3'];
		$occupation = $_POST['occupation'];
		$zip = $_POST['hc_f4'];
		$catID = $_POST['catID'];
		$birthyear = $_POST['hc_fa'];
		$gender = $_POST['hc_fb'];
		$referral = $_POST['hc_fc'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE email = '" . cIn($email) . "'");
		if(!hasRows($result)){
			$query = "	INSERT INTO " . HC_TblPrefix . "users(FirstName, LastName, Email, OccupationID, Zip, IsRegistered, GUID,
							RegisteredAt, RegisterIP, BirthYear, Gender, Referral)
						VALUES(	'" . cIn($firstname) . "',
								'" . cIn($lastname) . "',
								'" . cIn($email) . "',
								'" . cIn($occupation) . "',
								'" . cIn($zip) . "',
								0, 
								MD5(UNIX_TIMESTAMP() + RAND(UNIX_TIMESTAMP()) * (RAND()*1000000) ), 
								'" . date("Y-m-d") . "',
								'" . $_SERVER["REMOTE_ADDR"] . "',
								'" . cIn($birthyear) . "',
								'" . cIn($gender) . "',
								'" . cIn($referral) . "')";
			doQuery($query);
			
			$result = doQuery("select last_insert_id() from " . HC_TblPrefix . "users");
			$newID = mysql_result($result,0,0);
			
			$result = doQuery("select GUID from " . HC_TblPrefix . "users where pkid = " . cIn($newID));
			$GUID = mysql_result($result,0,0);
			
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "usercategories(UserID, CategoryID) VALUES('" . cIn($newID) . "', '" . cIn($val) . "')");
			}//end while

			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=" . $hc_lang_config['CharSet'] . ";\n";

			$subject = CalName . " " . $hc_lang_register['Subject'];
			$msg = $firstname . ",<br /><br />";
			$msg .= $hc_lang_register['RegEmailA'] . " <a href=\"" . CalRoot . "/a.php?a=" . $GUID . "\">" . CalRoot . "/a.php?a=" . $GUID . "</a><br /><br />";
			$msg .= $hc_lang_register['RegEmailB'] . "<br /><br />" . $hc_lang_register['RegEmailC'] . " ";
			$msg .= CalAdmin . " - " . CalAdminEmail;
					
			mail($email, $subject, $msg, $headers);
			
			header('Location: ' . CalRoot . '/index.php?com=signup&msg=1');
		} else {
			header('Location: ' . CalRoot . '/index.php?com=signup&msg=2&fname=' . urlencode($firstname) . '&lname=' . urlencode($lastname) . '&occ=' . urlencode($occupation) . '&zip=' . urlencode($zip) );
		}//end if
	}//end if	?>