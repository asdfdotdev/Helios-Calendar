<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
/*
	$incPrefix = 'events/';															//	Path from where you integrate this file to your /events/ directory
	include_once($incPrefix . 'includes/include_int.php');							//	Include for your int_include.php file (/events/includes/int_include.php)
	include_once($incPrefix . 'includes/lang/' . $hc_cfg28 . '/public/event.php');	//	New Include for your event.php language pack file
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/event.php');

	$hourOffset = date("G") + ($hc_cfg35);
	$result = doQuery("	SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD
						FROM " . HC_TblPrefix . "events
						WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'
                              ORDER BY PublishDate DESC, StartDate LIMIT " . cIn($hc_cfg10));
	if(hasRows($result)){
		$curDate = '';
		$cnt = 0;
		echo '<ul class="popular">';

		while($row = mysql_fetch_row($result)){
			if($curDate != $row[2]){
				$curDate = $row[2];
				$dateParts = explode("-", $row[2]);
				echo '<li class="popularDate"><br />' . stampToDate($row[2], $hc_cfg14) . '</li>';
			}//end if

			if($hc_cfg15 == 1){
				if($row[6] == 0){
					$timeparts = explode(":", $row[3]);
					$startTime = strftime($hc_cfg23, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
				} elseif($row[6] == 1) {
					$startTime = '<i>' . $hc_lang_event['AllDay'] . '</i>';
				} elseif($row[6] == 2) {
					$startTime = '<i>' . $hc_lang_event['TBA'] . '</i>';
				}//end if

				echo '<li class="popular"><a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" rel="nofollow" class="popular">' . cOut($row[1]) . '</a> - ' . $startTime . '</li>';
			} else {
				echo '<li class="popular"><a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" rel="nofollow" class="popular">' . cOut($row[1]) . '</a></li>';
			}//end if
			$cnt = $cnt + 1;
		}//end while
		echo '</ul>';
	} else {
		echo '<br />' . $hc_lang_event['NoPopular'];
	}//end if?>