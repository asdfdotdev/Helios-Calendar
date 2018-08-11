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
		$format = $_POST['format'];
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . cIn($uID) . "'");
		if(hasRows($result)){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE Email = '" . cIn($email) . "'");
			
			$queryE = '';
			if($email != $oldEmail){
				if(hasRows($result)){
					$msgID = '3';
				} else {
					$msgID = '1';
					$queryE = "Email = '" . cIn($email) . "',";
				}//end if
			}//end if

			doQuery("UPDATE " . HC_TblPrefix . "subscribers
					SET FirstName = '" . cIn($firstname) . "',
						LastName = '" . cIn($lastname) . "',
						OccupationID = '" . cIn($occupation) . "',
						" . $queryE . "
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
			header('Location: ' . CalAdminRoot . '/index.php?com=subedit&msg=' . $msgID . '&uID=' . $uID);
			
		} else {
			$optin = $_POST['sendOIE'];
			$status = ($optin == 1) ? 0 : 1;
		
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE Email = '" . cIn(trim($email)) . "'");

			if(hasRows($result)){
				header('Location: ' . CalAdminRoot . '/index.php?com=subedit&msg=4');
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "subscribers(FirstName, LastName, Email,
						 OccupationID, Zip, BirthYear, Gender, Referral, IsConfirm, GUID, AddedBy, RegisteredAt, RegisterIP, Format)
							VALUES(	'" . cIn($firstname) . "',
									'" . cIn($lastname) . "',
									'" . cIn($email) . "',
									'" . cIn($occupation) . "',
									'" . cIn($zip) . "',
									'" . cIn($birthyear) . "',
									'" . cIn($gender) . "',
									'" . cIn($referral) . "',
									'" . cIn($status) . "',
									MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')),
									'" . $_SESSION['AdminPkID'] . "',
									NOW(),
									'" . $_SERVER["REMOTE_ADDR"] . "',
									'" . cIn($format) . "')");
				
				$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "subscribers");
				$uID = mysql_result($result,0,0);
				
				doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . cIn($uID) . "'");
				if(isset($_POST['grpID'])){
					$grpID = $_POST['grpID'];
					foreach ($grpID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
					}//end while
				}//end if
				doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . cIn($uID) . "'");
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID, CategoryID) Values('" . cIn($uID) . "', '" . cIn($val) . "')");
					}//end while
				}//end if
				
				include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
				$result = doQuery("SELECT FirstName, GUID FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . cIn($uID) . "'");
				if(hasRows($result) && $optin == 1){
					$subject = $hc_lang_register['Subject'] . ' - ' . CalName;
					$message = '<p>' . $hc_lang_register['RegEmailA'] . ' <a href="' . CalRoot . '/a.php?a=' . mysql_result($result,0,1) . '">' . CalRoot . '/a.php?a=' . mysql_result($result,0,1) . '</a></p>';
					$message .= '<p>' .  mysql_result($result,0,0) . $hc_lang_register['RegEmailB'] . '</p>';
					$message .= '<p>' . $hc_lang_register['RegEmailC'] . ' ' . $hc_cfg78 . '</p>';

					reMail(trim($firstname . ' ' . $lastname), $email, $subject, $message, $hc_cfg79, $hc_cfg78);
				}//end if

				$target = ($optin == 1) ? 'submngt&msg=2' : 'subedit&uID=' . $uID . '&msg=2';
				
				header('Location: ' . CalAdminRoot . '/index.php?com=' . $target);
			}//end if
		}//end if
	} else {
		if(isset($_GET['a']) && $_GET['a'] = 1){
			doQuery("DELETE sg FROM " . HC_TblPrefix . "subscribersgroups sg LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sg.UserID) WHERE s.IsConfirm = 0");
			doQuery("DELETE sc FROM " . HC_TblPrefix . "subscriberscategories sc LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sc.UserID) WHERE s.IsConfirm = 0");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 0");
		} else {
			$dID = cIn($_GET['dID']);
			$result = doQuery("SELECT NewsletterID FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = '" . $dID . "'");
			if(hasRows($result)){
				while($row = mysql_fetch_row($result)){
					doQuery("UPDATE " . HC_TblPrefix . "newsletters SET SendCount = (SendCount - 1) WHERE PkID = '" . $row[0] . "'");
				}//end while
			}//end if
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = '" . $dID . "'");
		}//end if
		header('Location: ' . CalAdminRoot . '/index.php?com=submngt&msg=1');
	}//end if?>