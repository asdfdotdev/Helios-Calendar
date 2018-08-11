<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
	<br />
	<i>Events will open in a new window</i>.
	<br>To return to your search results close that window.<br><br>
	<b>Your Search Results</b><br>

<?	$startDate = dateToMySQL($_POST['startDate'], "/", $hc_popDateFormat);
	$endDate = dateToMySQL($_POST['endDate'], "/", $hc_popDateFormat);
	$keyword = cIn($_POST['keyword']);
	
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
		$catIDWhere = "";
		$cnt = 0;
		foreach ($catID as $val){
			$catIDWhere = $catIDWhere . $val . ",";
			$cnt = $cnt + 1;
		}//end while
		$catIDWhere = substr($catIDWhere, 0, strlen($catIDWhere) - 1);
	}//end if
	
	$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
				FROM " . HC_TblPrefix . "events 
					INNER JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
				WHERE (StartDate BETWEEN '" . $startDate . "' AND '" . $endDate . "') 
						AND Title LIKE('%" . cIn($keyword) . "%')
						AND IsActive = 1 
						AND IsApproved = 1 ";
	
	if(isset($_POST['catID'])){
		$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
	}//end if
	
	if($_POST['city'] != ''){
		$query = $query . " AND " . HC_TblPrefix . "events.LocationCity = '" . $_POST['city'] . "'";
	}//end if
	
	$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";
	
	$result = doQuery($query);
	
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];?>
				<div class="eventDateTitle"><?echo stampToDate($row[9], $hc_dateFormat);?></div>
			<?	$cnt = 0;
			}//end if?>
			<div class="<?if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}?>">
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
			
			<?	$calSaver = "";
				if(isset($_GET['theDate'])){
					$calSaver = "&amp;month=" . $month . "&amp;year=" . $year;
				}//end if	?>
			</div>
			<div class="<?if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}?>"><a href="<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $row[0] . $calSaver;?>" class="eventListTitle" target="_blank"><?echo cOut($row[1]);?></a></div>
	<?	$cnt++;
		}//end while
	} else {	?>
		There are no events that meet that search criteria.<br>
		<a href="<?echo CalRoot;?>/index.php?com=search" class="eventMain">Please click here to search again.</a>
		<br /><br />
<?	}//end if?>