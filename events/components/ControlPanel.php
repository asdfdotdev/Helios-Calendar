<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	require("phpCal/phpCal.php");
	
	if (isset($_GET["month"])){
		$cal = new phpCal(cIn($_GET["month"]), cIn($_GET["year"]), $browsePast);
		} else {
		$cal = new phpCal(date("m"), date("Y"), $browsePast);
	}//end if
		
	$query = "	SELECT DISTINCT StartDate 
				FROM " . HC_TblPrefix . "events
					LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
				WHERE IsActive = 1 AND 
					IsApproved = 1 ";
	
	if( isset($_SESSION['BrowseCatIDs']) ){
		$query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['BrowseCatIDs'] . ") ";
	}//end if
	
	$query = $query . " ORDER BY StartDate";
	
	//echo $query;exit;
	
	$result = doQuery($query);
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
			$datepart = split("-",$row[0]);
			$datestamp = date("m/d/Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
			$events[] = $datestamp;
		}//end while
	} else {
		$events[] = "";
	}//end if
	
	$cal->setEventDays($events);
	$cal->setLinks("", "");
?>
<div align="center">
	<?php print($cal->createCal()); ?>
	<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
	<a href="<?echo CalRoot;?>/index.php?com=rss"><img src="<?echo CalRoot;?>/images/rss2.gif" width="80" height="15" alt="" border="0"></a>
	
	<br><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
	<a href="<?echo CalRoot;?>/index.php?com=mobile"><img src="<?echo CalRoot;?>/images/mobilebutton.gif" width="80" height="15" alt="" border="0"></a>
	
	<br>&nbsp;
</div>
