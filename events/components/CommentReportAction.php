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
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/comment.php');

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,7);
	
	$name = (isset($_POST['reportName'])) ? $_POST['reportName'] : '';
	$email = (isset($_POST['reportEmail'])) ? $_POST['reportEmail'] : '';
	$report = (isset($_POST['reportDetails'])) ? htmlspecialchars(strip_tags($_POST['reportDetails'])) : '';
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? $_POST['eID'] : 0;
	$cID = (isset($_POST['cID']) && is_numeric($_POST['cID'])) ? $_POST['cID'] : 0;
	$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? $_POST['tID'] : 0;
	$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? $_POST['uID'] : 0;
	$returnURL = CalRoot;
	
	if($eID > 0 && $cID > 0 && $tID > 0 && $report != ''){
		$query = "SELECT * FROM " . HC_TblPrefix . "commentsreportlog crl
					WHERE crl.CommentID = '" . $cID . "' AND (ReportedIP = '" . $_SERVER["REMOTE_ADDR"] . "'";
		if($uID > 0){$query .= " OR crl.UserID = '" . $uID . "'";}
		$query .= ')';
		$result = doQuery($query);
		
		if(!hasRows($result)){
			doQuery("INSERT INTO " . HC_TblPrefix . "commentsreportlog(CommentID, TypeID, UserID, ReportedName, ReportedEmail, ReportedMsg, ReportedIP, IsActive)
						VALUES('" . cIn($cID) . "','" . cIn($tID) . "','" . cIn($uID) . "','" . cIn($name) . "','" . cIn($email) . "','" . cIn($report) . "','" . $_SERVER["REMOTE_ADDR"] . "',1)");
			
			$resultE = doQuery("SELECT a.FirstName, a.LastName, a.Email
							FROM " . HC_TblPrefix . "adminnotices n
								LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
							WHERE a.IsActive = 1 AND
								n.IsActive = 1 AND
								n.TypeID = 1");

			if(hasRows($resultE)){
				$toNotice = array();
				while($row = mysql_fetch_row($resultE)){
					$toNotice[trim($row[0] . ' ' .$row[1])] = $row[2];
				}//end while

				$result = doQuery("SELECT oid.ShortName, c.Comment
									FROM " . HC_TblPrefix . "comments c
										LEFT JOIN " . HC_TblPrefix . "oidusers oid ON (c.PosterID = oid.PkID)
									WHERE c.PkID = '" . cIn($cID) . "' AND c.IsActive = 1 AND c.TypeID = '" . $tID . "'");
				
				$subject = CalName . ' -- ' . $hc_lang_comment['NoticeSubject'];
				
				$message = '<p>' . $hc_lang_comment['NoticeEmail1'] . '</p>';
				$message .= '<p><b>' . $hc_lang_comment['NoticeReportBy'] . '</b> ' . $name . ' (' . $email . ')<br />';
				$message .= '<b>' . $hc_lang_comment['NoticeReportFrom'] . '</b> ' . $_SERVER['REMOTE_ADDR'] . '<br />' . $report . '<br /><br />';
				$message .= '<b>' . $hc_lang_comment['NoticeCommentBy'] . '</b> ' . mysql_result($result,0,0) . '<br />';
				$message .= '<b>' . $hc_lang_comment['NoticeComment'] . '</b> ' . mysql_result($result,0,1) . '</p>';
				$message .= '<p><a href="' . CalAdminRoot . '">' . CalAdminRoot . '</a></p>';

				reMail('', $toNotice, $subject, $message);
			}//end if
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=6#cmnts';
		} else {
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=5#cmnts';
		}//end if
	}//end if

	header('Location: ' . $returnURL);?>