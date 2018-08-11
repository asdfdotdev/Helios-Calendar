<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	error_reporting(0);
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include(HCLANG.'/admin/home.php');
	
	appInstructions(0, " ", $hc_lang_home['Title'], $hc_lang_home['Instructions']);
	
	$result = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(AdminRoot . '/index.php?com=eventpending','edit.png',$hc_lang_home['PendingNotice'] . ' <b>' . $num . '</b>',0);
	
	$result = doQuery("SELECT COUNT(DISTINCT e.PkID)
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
					WHERE (e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NULL OR c.IsActive = 0)) OR
						(e.IsActive = 1 AND e.IsApproved = 1 AND e.LocID = 0 AND (e.LocationName = '' OR e.LocationName IS NULL OR e.LocationName = 'Unknown'))");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(AdminRoot . '/index.php?com=eventorphan','category.png',$hc_lang_home['OrphanNotice'] . ' <b>' . $num . '</b>',0);
	
	if($_SESSION['PasswordWarn'] == 1)
		feedback(3,$hc_lang_core['Password']);
	
	$ip = gethostbyname("www.refreshmy.com");
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = "";
		$request = "GET /_update/h/feed.php HTTP/1.1\r\nHost: www.refreshmy.com\r\nConnection: Close\r\n\r\n";
		fwrite($fp, $request);
		while(!feof($fp)){
			$read .= fread($fp,1024);
		}
		fclose($fp);
		
		$output = explode("/bof", $read);
		if(isset($output[1])){
			$message = explode("/eof", $output[1]);
			echo '
		<fieldset>
			<legend>' . $hc_lang_home['Update'] . '&nbsp;&nbsp;(<a href="http://www.refreshmy.com/forum/forumdisplay.php?f=13" target="_blank">' . $hc_lang_home['MoreNews'] . '</a>)</legend>
			<p>'.cleanBreaks($message[0]).'</p>
		</fieldset>';
		}
	}
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "'");
	$eventA = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . SYSDATE . "'");
	$eventB = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . SYSDATE . "'");
	$eventT = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE StartDate BETWEEN '" . SYSDATE . "' AND AddDate('" . SYSDATE . "', INTERVAL 7 DAY)");
	$event7 = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE en.NetworkType = 1 AND en.IsActive = 1");
	$eventEv = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE en.NetworkType = 2 AND en.IsActive = 1");
	$eventEb = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 1");
	$locP = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 0");
	$locA = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1");
	$subC = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 0");
	$subN = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "newsletters WHERE Status = 3 AND IsActive = 1");
	$newsSent = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "newsletters WHERE Status = 3 AND IsActive = 1 AND IsArchive = 1");
	$newsArch = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "newsletters WHERE Status = 3 AND IsActive = 1 AND SentDate Between '" . date("Y-m-d",  strtotime('-7 day')) . "' AND '" . date("Y-m-d") . "'");
	$news7 = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "newsletters WHERE Status = 3 AND IsActive = 1 AND SentDate Between '" . date("Y-m-d",  strtotime('-30 day')) . "' AND '" . date("Y-m-d") . "'");
	$news30 = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND SubmittedAt IS NOT NULL");
	$eventS = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	$result = doQuery("SELECT COUNT(en.NetworkID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.StartDate >= '" . SYSDATE . "' AND en.NetworkType = 3 AND en.IsActive = 1 GROUP BY en.NetworkType");
	$tweet = (hasRows($result)) ? mysql_result($result,0,0) : 0;
	echo '
		<fieldset>
			<legend>'.$hc_lang_home['QuickStat'].'</legend>
			<table class="stats_tbl">
				<tr>
					<td class="statsHeader1" colspan="2">'.$hc_lang_home['Events'].'</td>
				</tr>
				<tr>
					<td class="stats_tbl_label">'.$hc_lang_home['Active'].'</td>
					<td class="status_tbl_data">'.$eventA.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['Billboard'].'</td>
					<td class="status_tbl_data">'.$eventB.'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['Today'].'</td>
					<td class="status_tbl_data">'.$eventT.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['Next7'].'</td>
					<td class="status_tbl_data">'.$event7.'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['Eventful'].'</td>
					<td class="status_tbl_data">'.$eventEv.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['Eventbrite'].'</td>
					<td class="status_tbl_data">'.$eventEb.'</td>
				</tr>
				<tr>
					<td class="statsHeader2" colspan="2">'.$hc_lang_home['Locations'].'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['Public'].'</td>
					<td class="status_tbl_data">'.$locP.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['Admin'].'</td>
					<td class="status_tbl_data">'.$locA.'</td>
				</tr>
			</table>
			<table class="stats_tbl">
				<tr>
					<td class="statsHeader1" colspan="2">'.$hc_lang_home['Newsletters'].'</td>
				</tr>
				<tr>
					<td class="stats_tbl_label">'.$hc_lang_home['Subscribers'].'</td>
					<td class="status_tbl_data">'.$subC.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['Unconfirmed'].'</td>
					<td class="status_tbl_data">'.$subN.'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['NewsSent'].'</td>
					<td class="status_tbl_data">'.$newsSent.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['NewsArchive'].'</td>
					<td class="status_tbl_data">'.$newsArch.'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['NewsSent7'].'</td>
					<td class="status_tbl_data">'.$news7.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['NewsSent30'].'</td>
					<td class="status_tbl_data">'.$news30.'</td>
				</tr>
				<tr>
					<td class="statsHeader2" colspan="2">'.$hc_lang_home['Misc'].'</td>
				</tr>
				<tr>
					<td>'.$hc_lang_home['Submitted'].'</td>
					<td class="status_tbl_data">'.$eventS.'</td>
				</tr>
				<tr class="status_tbl_hl">
					<td>'.$hc_lang_home['AdminTweets'].'</td>
					<td class="status_tbl_data">'.$tweet.'</td>
				</tr>
			</table>
		</fieldset>';
?>