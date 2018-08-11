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
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');
	
	if(!isset($_GET['dID'])){
		$mID = (isset($_POST['mID']) && is_numeric($_POST['mID'])) ? cIn($_POST['mID']) : 0;
		$title = (isset($_POST['mailTitle'])) ? cIn($_POST['mailTitle']) : '';
		$subject = (isset($_POST['mailSubj'])) ? cIn($_POST['mailSubj']) : '';
		$startDate = (isset($_POST['startDate'])) ? dateToMySQL(cIn($_POST['startDate']), $hc_cfg24) : '';
		$endDate = (isset($_POST['endDate'])) ? dateToMySQL(cIn($_POST['endDate']), $hc_cfg24) : '';
		$template = (isset($_POST['templateID']) && is_numeric($_POST['templateID'])) ? cIn($_POST['templateID']) : 0;
		$archive = (isset($_POST['archStatus']) && is_numeric($_POST['archStatus'])) ? cIn($_POST['archStatus']) : 0;
		$message = (isset($_POST['mailMsg'])) ? cIn(cleanQuotes($_POST['mailMsg'],0),0) : '';

		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "mailers WHERE PkID = '" . $mID . "'");
		if(hasRows($result)){
			$msg = 1;
			doQuery("UPDATE " . HC_TblPrefix . "mailers
					SET Title = '" . $title . "',
						Subject = '" . $subject . "',
						StartDate = '" . $startDate . "',
						EndDate = '" . $endDate . "',
						TemplateID = '" . $template . "',
						Message = '" . $message . "',
						LastModDate = '" . date("Y-m-d") . "',
						IsArchive = '" . $archive . "'
					WHERE PkID = '" . $mID . "'");
		} else {
			$msg = 2;
			doQuery("INSERT INTO " . HC_TblPrefix . "mailers(Title,Subject,StartDate,EndDate,TemplateID,Message,CreatedDate,LastModDate,IsArchive,IsActive)
					VALUES('" . $title . "',
						'" . $subject . "',
						'" . $startDate . "',
						'" . $endDate . "',
						'" . $template . "',
						'" . $message . "',
						'" . date("Y-m-d") . "',
						'" . date("Y-m-d") . "',
						'" . $archive . "',
						1)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$mID = mysql_result($result,0,0);
		}//end if

		if(isset($_POST['grpID'])){
			doQuery("DELETE FROM " . HC_TblPrefix . "mailersgroups WHERE MailerID = '" . $mID . "'");
			foreach($_POST['grpID'] as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "mailersgroups(MailerID,GroupID) VALUES('" . $mID . "','" . $val . "')");
			}//end if
		}//end if
	} else {
		$msg = 3;
		doQuery("UPDATE " . HC_TblPrefix . "mailers SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
	}//end if
	header("Location: " . CalAdminRoot . "/index.php?com=newscreate&msg=" . $msg);
?>