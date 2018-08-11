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
	
	$sID = (isset($_GET['s']) && is_numeric($_GET['s'])) ? cIn($_GET['s']) : 0;
	$maxShow = (isset($_GET['z']) && is_numeric($_GET['z'])) ? cIn($_GET['z']) : 10;
	$showStart = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn($_GET['t']) : 0;
	
	$query = "	SELECT distinct e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				WHERE e.IsActive = 1 AND 
					e.IsApproved = 1 AND 
					e.StartDate >= '" . date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y"))) . "' AND
					c.IsActive = 1 ";
	switch($sID){
		case 1:
			$query .= "	ORDER BY e.PublishDate DESC, e.StartDate
						LIMIT " . $maxShow * 2;
			$feedName = "Newest Events";
			break;
		case 2:
			$query .= "	ORDER BY e.Views DESC, e.StartDate
						LIMIT " . $maxShow * 2;
			$feedName = "Most Popular Events";
			break;
		case 3:
			$query .= "	AND e.IsBillboard = 1
						ORDER BY e.StartDate, e.TBD, e.Title
						LIMIT " . $maxShow * 2;
			$feedName = "Featured Events";
			break;
		default:
			$query .= "	ORDER BY e.StartDate, e.TBD ASC, e.StartTime, e.Title 
						LIMIT " . $maxShow * 2;
			$feedName = "All Events";
		break;
	}//end switch
	
	$result = doQuery($query);
	if(hasRows($result)){
		$hideSeries[] = array();
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){	
			if($cnt >= $maxShow){break;}
			
			if($row[19] == '' || !in_array($row[19], $hideSeries)){
				if($curDate != $row[9]) {
					$curDate = $row[9];
					echo "document.write('<div class=\"eventDate\">" . stampToDate($row[9], $hc_cfg14) . "</div>');";
				}//end if
				
				$startTime = "";
				if($showStart == 1){
					if($row[11] == 0){
						$timeparts = explode(":", $row[10]);
						$startTime = strftime($hc_cfg23, mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971)) . " ";
					} elseif($row[11] == 1) {
						$startTime = "<i>All Day Event</i>";
					} elseif($row[11] == 2) {
						$startTime = "<i>TBA</i>";
					}//end if
				}//end if
				echo "document.write('<a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\" class=\"eventLink\">" . $startTime . " - " . html_entity_decode(strip_tags(cleanXMLChars(cOut($row[1])))) . "</a><br />');";
				$cnt++;
			}//end if
			
			if($hc_cfg33 == 0 && $row[19] != '' && (!in_array($row[19], $hideSeries))){
				$hideSeries[] = $row[19];
			}//end if
		}//end while
	} else {
		echo "document.write('There are no events currently available for this feed');";
	}//end if	?>