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
	
	include(HCLANG.'/admin/newsletter.php');
	
	if(!isset($_GET['dID'])){
		$mID = (isset($_POST['mID']) && is_numeric($_POST['mID'])) ? cIn($_POST['mID']) : 0;
		$title = (isset($_POST['mailTitle'])) ? cIn($_POST['mailTitle']) : '';
		$subject = (isset($_POST['mailSubj'])) ? cIn($_POST['mailSubj']) : '';
		$startDate = (isset($_POST['startDate'])) ? dateToMySQL(cIn($_POST['startDate']), $hc_cfg[24]) : '';
		$endDate = (isset($_POST['endDate'])) ? dateToMySQL(cIn($_POST['endDate']), $hc_cfg[24]) : '';
		$template = (isset($_POST['templateID']) && is_numeric($_POST['templateID'])) ? cIn($_POST['templateID']) : 0;
		$archive = (isset($_POST['archStatus']) && is_numeric($_POST['archStatus'])) ? cIn($_POST['archStatus']) : 0;
		$message = (isset($_POST['mailMsg'])) ? cIn(cleanQuotes($_POST['mailMsg'],0),0) : '';

		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "mailers WHERE PkID = ?", array($mID));
		if(hasRows($result)){
			$msg = 1;
			doQuery("UPDATE " . HC_TblPrefix . "mailers
					SET Title = ?,
						Subject = ?,
						StartDate = ?,
						EndDate = ?,
						TemplateID = ?,
						Message = ?,
						LastModDate = ?,
						IsArchive = ?
					WHERE PkID = ?", array(
						$title,
						$subject,
						$startDate,
						$endDate,
						$template,
						$message,
						date("Y-m-d"),
						$archive,
					    $mID
					));
		} else {
			$msg = 2;
			doQuery("INSERT INTO " . HC_TblPrefix . "mailers(Title,Subject,StartDate,EndDate,TemplateID,Message,CreatedDate,LastModDate,IsArchive,IsActive)
					VALUES(?,?,?,?,?,?,?,?,?,1)", array($title, $subject, $startDate, $endDate, $template, $message, date("Y-m-d"), date("Y-m-d"), $archive));

			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$mID = hc_mysql_result($result,0,0);
		}

		if(isset($_POST['grpID'])){
			doQuery("DELETE FROM " . HC_TblPrefix . "mailersgroups WHERE MailerID = ?", array($mID));
			foreach($_POST['grpID'] as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "mailersgroups(MailerID,GroupID) VALUES(?,?)", array($mID, $val));
			}
		}
	} else {
		$msg = 3;
		doQuery("UPDATE " . HC_TblPrefix . "mailers SET IsActive = 0 WHERE PkID = ?", array(cIn(strip_tags($_GET['dID']))));
	}
	header("Location: " . AdminRoot . "/index.php?com=newscreate&msg=" . $msg);
?>