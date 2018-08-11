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
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	if(!isset($_POST['dID'])){
		$target = '/index.php?com=signup&s=1&msg=2';
		$proof = $challenge = '';
		if($hc_cfg65 == 1){
			$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
			$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
		} elseif($hc_cfg65 == 2){
			$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
			$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
		}//end if
		spamIt($proof,$challenge,4);

		$email = (isset($_POST['hc_fz'])) ? cIn($_POST['hc_fz']) : '';
		$do = (isset($_POST['hc_fy'])) ? cIn($_POST['hc_fy']) : '';
		$stop = (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) == 1) ? 0 : 1;
		$stop = (is_numeric($do)) ? 0 : 1;

		if($stop == 0){
			$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE email = '" . $email . "' && IsConfirm = 1");
			if(hasRows($result)){
				doQuery("UPDATE " . HC_TblPrefix . "subscribers SET GUID = MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')) WHERE email = '" . $email . "'");
				$result = doQuery("SELECT FirstName, GUID FROM " . HC_TblPrefix . "subscribers WHERE email = '" . $email . "'");
				$GUID = (hasRows($result)) ?  mysql_result($result,0,1) : '';
				if($GUID != ''){
					$link = ($do == 0) ? CalRoot . '/index.php?com=signup&u=' . $GUID : CalRoot . '/index.php?com=signup&d=' . $GUID;
					$doMsg = ($do == 0) ? 'Edit' : 'Delete';
					$subject = $hc_lang_register[$doMsg.'Subject'] . ' - ' . CalName;
					$message = '<p>' . $hc_lang_register[$doMsg.'EmailA'] . ' <a href="' . $link . '">' . $link . '</a></p>';
					$message .= '<p>' .  mysql_result($result,0,0) . $hc_lang_register[$doMsg.'EmailB'] . ' ' . $hc_lang_register[$doMsg.'EmailC'] . ' ' . $hc_cfg78 . '</p>';

					reMail(trim($firstname . ' ' . $lastname),$email,$subject,$message,$hc_cfg79,$hc_cfg78);

					$target = '/index.php?com=signup&s=1&msg=1';
				}//end if
			}//end if
		}//end if

		header('Location: ' . CalRoot . $target);
	} else {
		$dID = cIn(strip_tags($_POST['dID']));
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "subscribers WHERE GUID = '" . $dID . "'");
		if(hasRows($result)){
			$dID = mysql_result($result,0,0);
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . $dID . "'");
			doQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = '" . $dID . "'");
		}//end if
		
		header('Location: ' . CalRoot . '/index.php?com=signup&t=4');
	}//end if
?>