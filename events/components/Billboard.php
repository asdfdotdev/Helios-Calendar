<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
/*
	$incPrefix = 'events/';												//	Path from where you integrate this file to your /events/ directory
	include_once($incPrefix . 'includes/include_int.php');						//	Include for your int_include.php file (/events/includes/int_include.php)
	include_once($incPrefix . 'includes/lang/' . $hc_cfg28 . '/public/event.php');	//	New Include for your event.php language pack file
*/	
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/event.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	
	$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD
			FROM " . HC_TblPrefix . "events
			WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'";
	if($hc_cfg13 == 0){
		$query .= " AND IsBillboard = 1 ";
	}//end if
	
	$query .= " ORDER BY IsBillboard DESC, StartDate, StartTime, Title LIMIT " . $hc_cfg12 * 3;
	$result = doQuery($query);
	
	if(hasRows($result)){
		$curDate = "";
		$cnt = 0;
		$hideSeries = array();
		$showHeader = 0;
		echo '<ul class="billboard">';
		while($row = mysql_fetch_row($result)){
			if($cnt >= $hc_cfg12){break;}
			
			if($row[4] == 0 && $showHeader == 0){
				$showHeader = 1;
				echo '<li class="billboardDate"><br /><b>' . $hc_lang_event['Upcoming'] . '</b></li>';
			}//end if
			
			if($row[5] == '' || !in_array($row[5], $hideSeries)){
				if($curDate != $row[2]){
					$curDate = cOut($row[2]);
					echo '<li class="billboardDate"><br />' . stampToDate($row[2], $hc_cfg14) . '</li>';
				}//end if
				
				echo '<li class="billboard"><div class="hc_align"><a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" rel="nofollow" class="billboard">' . cOut($row[1]) . '</a></div>';
				if($hc_cfg15 == 1){
					$startTime = '';
					if($row[6] == 0){
						$timeparts = explode(":", $row[3]);
						$startTime = strftime($hc_cfg23, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
					} elseif($row[6] == 1) {
						$startTime = '<i>' . $hc_lang_event['AllDay'] . '</i>';
					} elseif($row[6] == 2) {
						$startTime = '<i>' . $hc_lang_event['TBA'] . '</i>';
					}//end if
					
					echo '<div class="hc_align">&nbsp;-&nbsp;</div>' . $startTime;
				}//end if
				echo '&nbsp;</li>';
				++$cnt;
			}//end if
			
			if($hc_cfg33 == 0 && $row[5] != '' && (!in_array($row[5], $hideSeries))){
				$hideSeries[] = $row[5];
			}//end if
		}//end while
		echo '</ul>';
	} else {
		echo '<br />' . $hc_lang_event['NoBillboard'];
	}//end if	?>