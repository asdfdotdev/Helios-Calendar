<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
	$sID = 0;
	if(isset($_GET['sID'])){
		$sID = $_GET['sID'];
	}//end if
	
	$result = doQuery("	SELECT *
						FROM " . HC_TblPrefix . "events
						WHERE SeriesID = '" . cIn($sID) . "'
							  AND IsActive = 1 AND IsApproved = 1
							  AND StartDate >= '" . date("Y-m-d") . "'
						ORDER BY Title, StartDate, TBD, StartTime");
	if(hasRows($result)){
		$cnt = 0;
		$curTitle = "";
		while($row = mysql_fetch_row($result)){
			if($cnt == 0){echo "<div id=\"eventDetailTitle\">" . cOut($row[1]) . "</div>";}?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}//end if?>">
		<?php 	$startTime = "";
				$endTime = "";
				$dateparts = explode("-", $row[9]);
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '&nbsp;-&nbsp;' . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[11] == 0){
					echo $startTime . $endTime;
				} elseif($row[11] == 1) {
					echo "<i>" . $hc_lang_event['AllDay'] . "</i>";
				} elseif($row[11] == 2) {
					echo "<i>" . $hc_lang_event['TBA'] . "</i>";
				}//end if	?>
			</div>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}//end if?>"><a href="<?php echo CalRoot . "/index.php?com=detail&amp;eID=" . $row[0] . "&year=" . $dateparts[0] . "&month=" . $dateparts[1];?>" class="eventListTitle"><?php echo stampToDate($row[9], $hc_dateFormat);?></a></div>
<?php 	$cnt++;
		}//end while
	} else {
		echo "<br />" . $hc_lang_event['NoSeries'] . "<br /><br />";
	}//end if	?>