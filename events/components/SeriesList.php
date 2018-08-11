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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
	if(hasRows($result)){
		$cnt = 0;
		$curTitle = "";
		while($row = mysql_fetch_row($result)){
			echo ($cnt == 0) ? '<div id="eventDetailTitle">' . cOut($row[1]) . '</div>' : '';
			echo ($cnt % 2 == 0) ? '<div class="eventListTime">' : '<div class="eventListTimeHL">';
			
			$startTime = "";
			$endTime = "";
			$dateparts = explode("-", $row[9]);
			if($row[10] != ''){
				$timepart = explode(":", $row[10]);
				$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			}//end if
			
			if($row[12] != ''){
				$timepart = explode(":", $row[12]);
				$endTime = '<div class="hc_align">&nbsp;-&nbsp;</div><div class="hc_align">' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971)) . '</div>';
			}//end if
			
			if($row[11] == 0){
				echo '<div class="hc_align">' . $startTime . '</div>' . $endTime;
			} elseif($row[11] == 1) {
				echo '<i>' . $hc_lang_event['AllDay'] . '</i>';
			} elseif($row[11] == 2) {
				echo '<i>' . $hc_lang_event['TBA'] . '</i>';
			}//end if
			echo '</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTitle">' : '<div class="eventListTitleHL">';
			echo '<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" class="eventListTitle" rel="nofollow">' . stampToDate($row[9], $hc_cfg14) . '</a></div>';
				
			++$cnt;
		}//end while
	} else {
		echo "<br />" . $hc_lang_event['NoSeries'] . "<br /><br />";
	}//end if	?>