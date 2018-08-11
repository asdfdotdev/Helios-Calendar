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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	$date = (isset($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : strftime($hc_cfg24);
	$hourOffset = date("G") + ($hc_cfg35);
	
	$dateParts = explode('-', $date);
	$start = (isset($dateParts[2]) && is_numeric($dateParts[0]) && is_numeric($dateParts[1])) ? date("Y-m-d",mktime($hourOffset,0,0,$dateParts[1],1,$dateParts[0])) : date("Y-m-d",mktime($hourOffset,0,0,date("m"),1,date("Y")));
	$end = (isset($dateParts[2]) && is_numeric($dateParts[0]) && is_numeric($dateParts[1])) ? date("Y-m-d",mktime($hourOffset,0,0,$dateParts[1]+1,0,$dateParts[0])) : date("Y-m-d",mktime($hourOffset,0,0,date("m")+1,0,date("Y")));
	$next = (isset($dateParts[2]) && is_numeric($dateParts[0]) && is_numeric($dateParts[1])) ? date("Y-m-d",mktime($hourOffset,0,0,$dateParts[1]+1,1,$dateParts[0])) : date("Y-m-d",mktime($hourOffset,0,0,date("m")+1,1,date("Y")));
	$last = (isset($dateParts[2]) && is_numeric($dateParts[0]) && is_numeric($dateParts[1])) ? date("Y-m-d",mktime($hourOffset,0,0,$dateParts[1]-1,1,$dateParts[0])) : date("Y-m-d",mktime($hourOffset,0,0,date("m")-1,1,date("Y")));
	echo '<div id="nav-news">';
	echo '<a href="' . CalRoot . '/index.php?com=archive" title="' . $hc_lang_register['Home'] . '" class="nav"><img src="' . CalRoot . '/images/nav/home.png" alt="' . $hc_lang_register['Home'] . '" /></a>';
	echo '<a href="' . CalRoot . '/index.php?com=archive&n=' . $last . '" title="' . $hc_lang_register['Back'] . '" class="nav"><img src="' . CalRoot . '/images/nav/left.png" alt="' . $hc_lang_register['Back'] . '" /></a>';
	echo ($next <= date("Y-m-d",mktime($hourOffset,0,0,date("m")+1,0,date("Y")))) ? '<a href="' . CalRoot . '/index.php?com=archive&n=' . $next . '" title="' . $hc_lang_register['Forward'] . '" class="nav"><img src="' . CalRoot . '/images/nav/right.png" alt="' . $hc_lang_register['Forward'] . '" /></a>' : '<a href="#" class="nav"><img src="' . CalRoot . '/images/nav/rightb.png" alt="' . $hc_lang_register['Forward'] . '" /></a>';
	echo '</div>';

	echo '<div class="newsMonth">' . stampToDate($start, '%B %Y ' . $hc_lang_register['NewsletterArchive']) . '</div>';

	$result = doQuery("SELECT PkID, Subject, SentDate FROM " . HC_TblPrefix . "newsletters WHERE Status > 0 AND IsArchive = 1 AND IsActive = 1 AND ArchiveContents != '' AND SentDate Between '" . $start . "' AND '" . $end . "' ORDER BY SentDate");
	if(hasRows($result)){
		$cnt = 0;

		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 0) ? '<div class="newsDate">' : '<div class="newsDateHL">';
			$date = stampToDate($row[2],'%A, %b %d');
			echo addOrdinal($date) . '</div>';

			echo ($cnt % 2 == 0) ? '<div class="newsSubject">' : '<div class="newsSubjectHL">';
			echo '<a href="' . CalRoot . '/newsletter/index.php?n=' . md5($row[0]) . '" class="eventMain" target="_blank">' . $row[1] . '</a></div>';
			++$cnt;
		}//end while
	} else {
		echo '<br /><p>' . $hc_lang_register['NoNewsletter'] . '</p>';
	}//end if
?>

