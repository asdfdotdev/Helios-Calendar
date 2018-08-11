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
/*
	$incPrefix = 'events/';												//	Path from where you integrate this file to your /events/ directory
	include_once($incPrefix . 'includes/include_int.php');						//	Include for your int_include.php file (/events/includes/int_include.php)
	include_once($incPrefix . 'includes/lang/' . $hc_cfg28 . '/public/event.php');	//	New Include for your event.php language pack file
*/
	$incPrefix = '';
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');

	$cacheFile = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))) . '_0.php';
	if(!file_exists(realpath($incPrefix . 'cache/list' . $cacheFile))){
		if(count(glob($incPrefix . 'cache/list*_0.*')) > 0){
			foreach(glob($incPrefix . 'cache/list*_0.*') as $filename){
				unlink($filename);
			}//end foreach
		}//end if

		ob_start();
		$fp = fopen($incPrefix . 'cache/list' . $cacheFile, 'w');
		$hourOffset = date("G") + ($hc_cfg35);
		$query = "SELECT PkID, Title, StartDate, StartTime, IsBillboard, SeriesID, TBD, EndTime
				FROM " . HC_TblPrefix . "events
				WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'";
		$query .= ($hc_cfg13 == 0) ? " AND IsBillboard = 1 " : '';
		$query .= " ORDER BY IsBillboard DESC, StartDate, StartTime, Title LIMIT " . cIn($hc_cfg12 * 3);
		$result = doQuery($query);

		if(hasRows($result)){
			$curDate = '';
			$cnt = $showHeader = 0;
			$hideSeries = array();
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
						$startTime  = $endTime = '';
						if($row[6] == 0){
							$timeparts = explode(":", $row[3]);
							$startTime = strftime($hc_cfg23, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
							//$endTime = ($row[7] != '') ? ' - ' . strftime($hc_cfg23,strtotime($row[7])) : '';
						} elseif($row[6] == 1) {
							$startTime = '<i>' . $hc_lang_event['AllDay'] . '</i>';
						} elseif($row[6] == 2) {
							$startTime = '<i>' . $hc_lang_event['TBA'] . '</i>';
						}//end if

						echo '<div class="hc_align">&nbsp;-&nbsp;</div>' . $startTime;
						//echo $endTime;
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
			echo '<p>' . $hc_lang_event['NoBillboard'] . '</p>';
		}//end if

		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}//end if
	include($incPrefix . 'cache/list' . $cacheFile);
?>