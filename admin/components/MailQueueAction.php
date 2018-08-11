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
		$allSub = '';
		$mID = (isset($_POST['mID']) && is_numeric($_POST['mID'])) ? cIn($_POST['mID']) : 0;
		$next = (isset($_POST['next']) && is_numeric($_POST['next'])) ? cIn($_POST['next']) : 0;

		$resultG = doQuery("SELECT mg.PkID, mg.Name, m.PkID as Selected
						 FROM " . HC_TblPrefix . "mailgroups mg
							 LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = mg.PkID AND mgs.MailerID = '" . $mID . "')
							 LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID and m.IsActive = 1)
						 WHERE mg.IsActive = 1
						 Group By mg.PkID, mg.Name, m.PkID
						 ORDER BY mg.Name");
		while($row = mysql_fetch_row($resultG)){
			$allSub += ($row[2] != '' && $row[0] == 1) ? 1 : 0;
		}
		
		$queryCnt = ($allSub > 0) ? "SELECT COUNT(PkID) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1" :
							"SELECT COUNT(DISTINCT sgs.UserID)
							FROM " . HC_TblPrefix . "subscribersgroups sgs
								LEFT JOIN " . HC_TblPrefix . "mailgroups mg ON (sgs.GroupID = mg.PkID AND mg.IsActive = 1)
								LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = sgs.GroupID)
								LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID AND m.IsActive = 1)
								LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sgs.UserID)
							WHERE m.PkID = '" . $mID . "' AND s.IsConfirm = 1";
		$resultS = doQuery($queryCnt);
		$subCnt = mysql_result($resultS,0,0);
		
		doQuery("INSERT INTO " . HC_TblPrefix . "newsletters(Subject,StartDate,EndDate,TemplateID,Message,SentDate,SendCount,`Status`,SendingAdminID,MailerID,IsArchive,IsActive)
				SELECT Subject, StartDate, EndDate, TemplateID, Message, NOW(), " . $subCnt . " as SendCount,
					0, " . $_SESSION['AdminPkID'] . ", PkID, IsArchive, 1
				FROM " . HC_TblPrefix . "mailers m
				WHERE m.PkID = '" . $mID . "'");

		$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "newsletters");
		$newPkID = mysql_result($result,0,0);
		$queryList = ($allSub > 0) ? "INSERT INTO " . HC_TblPrefix . "newssubscribers(NewsletterID,SubscriberID) SELECT '" . $newPkID . "', PkID FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1" :
							"INSERT INTO " . HC_TblPrefix . "newssubscribers(NewsletterID,SubscriberID)
							SELECT DISTINCT '" . $newPkID . "', sgs.UserID
							FROM " . HC_TblPrefix . "subscribersgroups sgs
								LEFT JOIN " . HC_TblPrefix . "mailgroups mg ON (sgs.GroupID = mg.PkID AND mg.IsActive = 1)
								LEFT JOIN " . HC_TblPrefix . "mailersgroups mgs ON (mgs.GroupID = sgs.GroupID)
								LEFT JOIN " . HC_TblPrefix . "mailers m ON (mgs.MailerID = m.PkID AND m.IsActive = 1)
								LEFT JOIN " . HC_TblPrefix . "subscribers s ON (s.PkID = sgs.UserID)
							WHERE m.PkID = '" . $mID . "' AND s.IsConfirm = 1";
		doQuery($queryList);
		
		$target = ($next > 0) ? 'newssend&nID=' . $newPkID : 'newsqueue&msg=1';
	} else {
		$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn($_GET['tID']) : 0;
		$target = 'newsqueue&t=' . $tID . '&msg=2';
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
	}
	
	header("Location: " . AdminRoot . "/index.php?com=$target");
?>