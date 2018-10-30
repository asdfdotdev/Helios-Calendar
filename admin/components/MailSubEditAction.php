<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	
	if(!check_form_token($token))
		go_home();
	
	if(!isset($_GET['dID'])){
		$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn(strip_tags($_POST['uID'])) : 0;
		$firstname = isset($_POST['firstname']) ? cIn($_POST['firstname']) : '';
		$lastname = isset($_POST['lastname']) ? cIn($_POST['lastname']) : '';
		$email = isset($_POST['email']) ? cIn($_POST['email']) : '';
		$oldEmail = isset($_POST['oldEmail']) ? cIn($_POST['oldEmail']) : '';
		$occupation = isset($_POST['occupation']) ? cIn($_POST['occupation']) : '';
		$birthyear = isset($_POST['birthyear']) ? cIn($_POST['birthyear']) : '';
		$gender = isset($_POST['gender']) ? cIn($_POST['gender']) : '';
		$referral = isset($_POST['referral']) ? cIn($_POST['referral']) : '';
		$zip = isset($_POST['zip']) ? cIn($_POST['zip']) : '';
		$format = isset($_POST['format']) ? cIn($_POST['format']) : '';
		$grpID = (isset($_POST['grpID'])) ? array_filter($_POST['grpID'],'is_numeric') : array();
		$catID = (isset($_POST['catID'])) ? array_filter($_POST['catID'],'is_numeric') : array();
		
		$result = DoQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE PkID = ?", array($uID));
		if(hasRows($result)){
			$queryE = '';
			$msgID = '1';
			$params = array();
			if($email != $oldEmail){
				$result = DoQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE Email = ?", array($email));
				if(hasRows($result))
					$msgID = '3';
				else {
					$queryE = "Email = ?,";
					$params[] = $email;
				}
			}
			$params[] = $firstname;
			$params[] = $lastname;
			$params[] = $occupation;
			$params[] = $zip;
			$params[] = $birthyear;
			$params[] = $gender;
			$params[] = $referral;
			$params[] = $format;
			$params[] = $uID;
			DoQuery("UPDATE " . HC_TblPrefix . "subscribers
					SET 
						" . $queryE . "
						FirstName = ?,
						LastName = ?,
						OccupationID = ?,
						Zip = ?,
						BirthYear = ?,
						Gender = ?,
						Referral = ?,
						Format = ?
					WHERE PkID = ?", $params);
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = ?", array($uID));
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = ?", array($uID));
			
			foreach ($grpID as $val){
				DoQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) VALUES(?,?)", array($uID, $val));
			}
			foreach ($catID as $val){
				DoQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID,CategoryID) VALUES(?,?)", array($uID, $val));
			}
			
			header('Location: ' . AdminRoot . '/index.php?com=subedit&msg=' . $msgID . '&uID=' . $uID);
		} else {
			$optin = isset($_POST['sendOIE']) ? cIn($_POST['sendOIE']) : '';;
			$status = ($optin == 1) ? 0 : 1;
		
			$result = DoQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE Email = ?", array(trim($email)));

			if(hasRows($result)){
				header('Location: ' . AdminRoot . '/index.php?com=subedit&msg=4');
			} else {
				DoQuery("INSERT INTO " . HC_TblPrefix . "subscribers(FirstName, LastName, Email,
						 OccupationID, Zip, BirthYear, Gender, Referral, IsConfirm, GUID, AddedBy, RegisteredAt, RegisterIP, Format)
							VALUES(?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?)",
							array(
									$firstname,
									$lastname,
									$email,
									$occupation,
									$zip,
									$birthyear,
									$gender,
									$referral,
									$status,
									MD5(CONCAT(rand(UNIX_TIMESTAMP()) * (RAND()*1000000),'" . $email . "')),
									$_SESSION['AdminPkID'],
									cIn(strip_tags($_SERVER["REMOTE_ADDR"])),
									$format
							));
				
				$result = DoQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "subscribers");
				$uID = hc_mysql_result($result,0,0);
				
				DoQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = ?", array($uID));
				DoQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = ?", array($uID));
				
				foreach ($grpID as $val){
					DoQuery("INSERT INTO " . HC_TblPrefix . "subscribersgroups(UserID,GroupID) VALUES(?,?)", array($uID, $val));
				}
				foreach ($catID as $val){
					DoQuery("INSERT INTO " . HC_TblPrefix . "subscriberscategories(UserID,CategoryID) VALUES(?,?)", array($uID, $val));
				}
				
				$result = DoQuery("SELECT FirstName, GUID FROM " . HC_TblPrefix . "subscribers WHERE PkID = ?", array($uID));
				if(hasRows($result) && $optin == 1){
					include(HCLANG.'/public/news.php');
					
					$subject = $hc_lang_news['Subject'] . ' - ' . CalName;
					$message = '<p>' . $hc_lang_news['RegEmailA'] . ' <a href="' . CalRoot . '/a.php?a=' . hc_mysql_result($result,0,1) . '">' . CalRoot . '/a.php?a=' . hc_mysql_result($result,0,1) . '</a></p>';
					$message .= '<p>' .  hc_mysql_result($result,0,0) . $hc_lang_news['RegEmailB'] . '</p>';
					$message .= '<p>' . $hc_lang_news['RegEmailC'] . ' ' . $hc_cfg[78] . '</p>';

					reMail(trim($firstname.' '.$lastname), $email, $subject, $message, $hc_cfg[79], $hc_cfg[78]);
				}
				
				$target = ($optin == 1) ? 'submngt&msg=2' : 'subedit&uID=' . $uID . '&msg=2';
				
				header('Location: ' . AdminRoot . '/index.php?com=' . $target);
			}
		}
	} else {
		if(isset($_GET['a']) && $_GET['a'] = 1){
			DoQuery("DELETE sg FROM " . HC_TblPrefix . "subscribersgroups sg LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sg.UserID) WHERE s.IsConfirm = 0");
			DoQuery("DELETE sc FROM " . HC_TblPrefix . "subscriberscategories sc LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sc.UserID) WHERE s.IsConfirm = 0");
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 0");
		} elseif(isset($_GET['dID'])) {
			$dID = cIn(strip_tags($_GET['dID']));
			$result = DoQuery("SELECT NewsletterID FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = ?", array($dID));
			if(hasRows($result)){
				while($row = hc_mysql_fetch_row($result)){
					DoQuery("UPDATE " . HC_TblPrefix . "newsletters SET SendCount = (SendCount - 1) WHERE PkID = ?", array($row[0]));
				}
			}
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscribersgroups WHERE UserID = ?", array($dID));
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscriberscategories WHERE UserID = ?", array($dID));
			DoQuery("DELETE FROM " . HC_TblPrefix . "subscribers WHERE PkID = ?", array($dID));
			DoQuery("DELETE FROM " . HC_TblPrefix . "newssubscribers WHERE SubscriberID = ?", array($dID));
		}
		header('Location: ' . AdminRoot . '/index.php?com=submngt&msg=1');
	}
?>