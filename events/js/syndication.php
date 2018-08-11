<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	
	
	$isAction = 1;
	include('../includes/include.php');
	
	$sID = 0;
	if(isset($_GET['s']) && is_numeric($_GET['s'])){
		$sID = cIn($_GET['s']);
	}//end if
	
	$maxShow = 10;
	if(isset($_GET['z']) && is_numeric($_GET['z'])){
		$maxShow = cIn($_GET['z']);
	}//end if
	
	$showStart = 0;
	if(isset($_GET['t']) && is_numeric($_GET['t'])){
		$showStart = cIn($_GET['t']);
	}//end if
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (14,23,33)");
	$dateFormat = cOut(mysql_result($result,0,0));
	$timeFormat = cOut(mysql_result($result,1,0));
	$series = cOut(mysql_result($result,2,0));
	
	$query = "	SELECT distinct e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				WHERE e.IsActive = 1 AND 
					e.IsApproved = 1 AND 
					e.StartDate >= NOW() AND
					c.IsActive = 1 ";
	
	switch($sID){
		case 1:
			//	Newest Events
			$query .= "	ORDER BY e.PublishDate DESC, e.StartDate
						LIMIT " . $maxShow * 2;
			$feedName = "Newest Events";
		break;
		case 2:
			// Most Popular Events
			$query .= "	ORDER BY e.Views DESC, e.StartDate
						LIMIT " . $maxShow * 2;
			$feedName = "Most Popular Events";
		break;
		case 3:
			// Billboard Events
			$query .= "		e.IsBillboard = 1
						ORDER BY e.StartDate, e.TBD, e.Title
						LIMIT " . $maxShow * 2;
			$feedName = "Featured Events";
		break;
		default:
			$query .= "	ORDER BY e.StartDate, e.TBD, e.Title 
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
			if($cnt >= $maxShow){break;}//end if
			
			if($row[19] == '' || !in_array($row[19], $hideSeries)){
				if($curDate != $row[9]) {
					$curDate = $row[9];
					echo "document.write('<div class=\"eventDate\">" . stampToDate($row[9], $dateFormat) . "</div>');";
				}//end if
				
				$startTime = "";
				if($showStart == 1){
					$timeparts = explode(":", $row[10]);
					$startTime = date($timeFormat , mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971)) . " ";
				}//end if
				echo "document.write('<a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\" class=\"eventLink\">" . $startTime . strip_tags(cleanWMLChars(cOut($row[1]))) . "</a><br />');";
				$cnt++;
			}//end if
			
			if($series == 0 && $row[19] != '' && (!in_array($row[19], $hideSeries))){
				$hideSeries[] = $row[19];
			}//end if
		}//end while
		
	} else {	?>
		<item>
	      <title>There Are No Upcoming Events Available For This Feed</title>
	      <link><?php echo CalRoot;?>/</link>
	      <description>Visit the <?php echo CalName;?> for more information.</description>
	    </item>
<?php 	
	}//end if	?>