<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();

	include(HCLANG.'/admin/register.php');
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactName, ContactEmail, SpacesAvailable FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");

	if(hasRows($result)){
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg[14]);
		if(mysql_result($result,0,3) == 0){
			$eventTime = stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg[23]);
		} elseif(mysql_result($result,0,3) == 1){
			$eventTime = $hc_lang_register['AllDay'];
		} elseif(mysql_result($result,0,3) == 2){
			$eventTime = $hc_lang_register['TBA'];
		}
		$contName = mysql_result($result,0,4);
		$contEmail = mysql_result($result,0,5);
		$space = mysql_result($result,0,6);

		$result = doQuery("SELECT *, 1 AS Cnt
						FROM " . HC_TblPrefix . "registrants
						WHERE EventID = '" . $eID . "' AND (GroupID IS NULL OR GroupID = '') UNION
						SELECT *, COUNT(groupID) AS Cnt
						FROM " . HC_TblPrefix . "registrants
						WHERE EventID = '" . $eID . "' AND (GroupID IS NOT NULL AND GroupID != '')
						GROUP BY GroupID
						ORDER BY RegisteredAt");
		if(hasRows($result)){
			$subject = CalName . ' ' . $hc_lang_register['RosterSubject'];

			$hourOffset = date("G") + ($hc_cfg[35]);
			$sysDate = date("Y-m-d H:i:s", mktime($hourOffset,date("i"),0,date("m"),date("d"),date("Y")));
			$cnt = 0;
			$roster = '';
			while($row = mysql_fetch_row($result)){
				$cnt += $row[13];
				$name = explode('-',$row[1]);
				$roster .= '<p>' . $name[0]  . ' (' . $row[2] . ') ';
				$roster .= ($row[3] != '') ? '<br><b>' . $hc_lang_register['Phone'] . '</b> ' . $row[3] : '';
				$roster .= ($row[4] != '') ?  '<br /><b>' . $hc_lang_register['Address']. '</b> ' . str_replace('<br />',' ',strip_tags(buildAddress($row[4],$row[5],$row[6],$row[7],$row[8],'',$hc_lang_config['AddressType']),'<br>')) : '';
				$roster .= '<br><b>' . $hc_lang_register['PartySize'] . '</b> ' . $row[13];
				$roster .= '<br><b>' . $hc_lang_register['RegAt'] . '</b> ' . stampToDate($row[11], $hc_cfg[14] . ' @ ' . $hc_cfg[23]) . '</p>';
			}

			$message = '<p>' . $hc_lang_register['RosterEmailA'] . ' ' . stampToDate($sysDate, $hc_cfg[14] . ' @ ' . $hc_cfg[23],$sysDate) . '</p>';
			$message .= '<p><b>' . $eventTitle . '</b><br />' . $eventDate . ' - ' . $eventTime;
			$message .= '<br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . CalRoot . '/index.php?eID=' . $eID . '</a></p>';
			$message .= '<p><b>' . $hc_lang_register['SpacesRequested'] . '</b> ' . $cnt . '/' . $space . '</p>';
			$message .= $roster;
			
			reMail($contName, $contEmail, $subject, $message, $hc_cfg[79], $hc_cfg[78]);

			header("Location: " . AdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&msg=6");
		}
	}

	header("Location: " . AdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID']);
?>