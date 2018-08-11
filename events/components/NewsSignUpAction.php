<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,4);

	$stop = 0;
	$firstname = $_POST['hc_f1'];
	$lastname = $_POST['hc_f2'];
	$email = $_POST['hc_f3'];
	$catID = $_POST['catID'];
	$uID = (isset($_POST['uID'])) ? $_POST['uID'] : 0;
	$occupation = (isset($_POST['occupation'])) ? $_POST['occupation'] : '';
	$zip = (isset($_POST['hc_f4'])) ? $_POST['hc_f4'] : '';
	$birthyear = (isset($_POST['hc_fa'])) ? $_POST['hc_fa'] : '';
	$gender = (isset($_POST['hc_fb'])) ? $_POST['hc_fb'] : '';
	$referral = (isset($_POST['hc_fc'])) ? $_POST['hc_fc'] : '';
	$format = (isset($_POST['format'])) ? $_POST['format'] : '';;

	$stop += ($firstname != '') ? 0 : 1;
	$stop += ($lastname != '') ? 0 : 1;
	$stop += (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) == 1) ? 0 : 1;
	$stop += (is_array($_POST['catID'])) ? 0 : 1;
	$stop += ($birthyear <= (date("Y") - 13)) ? 0 : 1;

	if($stop == 0){
		if($uID > 0){
			doQuery("UPDATE " . HC_TblPrefix . "subscribers
					SET FirstName = '" . cIn($firstname) . "',
						LastName = '" . cIn($lastname) . "',
						OccupationID = '" . cIn($occupation) . "',
						Email = '" . cIn($email) . "',
						Zip = '" . cIn($zip) . "',
						BirthYear = '" . cIn($birthyear) . "',
						Gender = '" . cIn($gender) . "',
						Referral = '" . cIn($referral) . "',
						Format = '" . cIn($format) . "'
					WHERE PkID = " . cIn($uID));
			doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . cIn($uID) . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . cIn($uID) . "'");

			if(isset($_POST['grpID'])){
				$grpID = $_POST['grpID'];
				foreach ($grpID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
				}//end while
			}//end if
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID,CategoryID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
				}//end while
			}//end if

			header('Location: ' . CalRoot . '/index.php?com=signup&t=5');
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE email = '" . cIn($email) . "'");
			if(!hasRows($result)){
				doQuery("INSERT INTO " . HC_TblPrefix . "subscribers(FirstName,LastName,Email,OccupationID,Zip,IsConfirm,GUID,RegisteredAt,RegisterIP,BirthYear,Gender,Referral)
						VALUES(	'" . cIn($firstname) . "',
								'" . cIn($lastname) . "',
								'" . cIn($email) . "',
								'" . cIn($occupation) . "',
								'" . cIn($zip) . "',
								0,
								MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')),
								'" . date("Y-m-d") . "',
								'" . $_SERVER["REMOTE_ADDR"] . "',
								'" . cIn($birthyear) . "',
								'" . cIn($gender) . "',
								'" . cIn($referral) . "')");
				$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "subscribers");
				$newID = mysql_result($result,0,0);
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID, CategoryID) Values('" . cIn($newID) . "', '" . cIn($val) . "')");
					}//end while
				}//end if
				if(isset($_POST['grpID'])){
					$grpID = $_POST['grpID'];
					foreach ($grpID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) Values('" . cIn($newID) . "', '" . cIn($val) . "')");
					}//end while
				}//end if

				$result = doQuery("SELECT GUID FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . cIn($newID) . "'");
				$GUID = (hasRows($result)) ?  mysql_result($result,0,0) : '';
				$subject = $hc_lang_register['Subject'] . ' - ' . CalName;
				$message = '<p>' . $hc_lang_register['RegEmailA'] . ' <a href="' . CalRoot . '/a.php?a=' . $GUID . '">' . CalRoot . '/a.php?a=' . $GUID . '</a></p>';
				$message .= '<p>' .  $firstname . $hc_lang_register['RegEmailB'] . '</p>';
				$message .= '<p>' . $hc_lang_register['RegEmailC'] . ' ' . $hc_cfg78 . '</p>';

				reMail(trim($firstname . ' ' . $lastname),$email,$subject,$message,$hc_cfg79,$hc_cfg78);

				header('Location: ' . CalRoot . '/index.php?com=signup&t=1');
			} else {
				header('Location: ' . CalRoot . '/index.php?com=signup&t=2');
			}//end if
		}//end if
	} else {
		header('Location: ' . CalRoot . '/index.php?com=signup&t=2');
	}//end if	?>