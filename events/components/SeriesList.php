<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['sID'])){
		$sID = $_GET['sID'];
	} else {
		$sID = 0;
	}//end if
	
	$result = doQuery("	SELECT *
						FROM " . HC_TblPrefix . "events
						WHERE SeriesID = '" . cIn($sID) . "'
							  AND IsActive = 1 AND IsApproved = 1
							  AND StartDate >= NOW()
						ORDER BY Title, StartDate, TBD, StartTime");
	if(hasRows($result)){
		$cnt = 0;
		$curTitle = "";
		while($row = mysql_fetch_row($result)){
			if($cnt == 0){?><div class="eventDetailTitle"><?echo cOut($row[1]);?></div><?}?>
			<div class="<?if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}//end if?>">
			<?	$startTime = "";
				$endTime = "";
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '&nbsp;-&nbsp;' . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[11] == 0){
					echo $startTime . $endTime;
				} elseif($row[11] == 1) {
					echo "<i>All Day Event</i>";
				} elseif($row[11] == 2) {
					echo "<i>TBA</i>";
				}//end if?>
			</div>
			<div class="<?if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}//end if?>"><a href="<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $row[0];?>" class="eventListTitle"><?echo stampToDate($row[9], $hc_dateFormat);?></a></div>
	<?	$cnt++;
		}//end while
	} else {	?>
	<br />
	There are no events left in this series.<br><br>
<?	}//end if	?>