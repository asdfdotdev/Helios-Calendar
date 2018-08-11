<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>

<script language="JavaScript">
function doDelete(eID, eTitle){
	if(confirm('Event Delete Is Permanent!\nAre you sure you want to delete the event:\n\n' + eTitle + '\n\n          Ok = YES Delete Event\n          Cancel = NO Don\'t Delete Event')){
		alert('delete event ' + eID);
	}//end if
}//end doDelete
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td colspan="3" class="eventMain">
			<i>Events will open in a new window</i>.
			<br>To return to your search results close that window.<br><br>
			<b>Your Search Results</b><br>
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0">
		</td>
	</tr>
	<?php
		$startDate = htmlspecialchars($_POST['startDate']);
		$endDate = htmlspecialchars($_POST['endDate']);
		$keyword = htmlspecialchars($_POST['keyword']);
		
		$startPart = split("/", $startDate);
		$endPart = split("/", $endDate);
		
		$startDate = $startPart[2] . "-" . $startPart[0] . "-" . $startPart[1];
		$endDate = $endPart[2] . "-" . $endPart[0] . "-" . $endPart[1];
		
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
		
		
		
		$query = "	SELECT " . HC_TblPrefix . "events.*, " . HC_TblPrefix . "eventcategories.CategoryID as Category
					FROM " . HC_TblPrefix . "events 
						INNER JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
					WHERE StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($endDate) . "' 
									AND Title LIKE('%" . cIn($keyword) . "%') 
									AND IsActive = 1 
									AND IsApproved = 1 ";
					
				if(isset($catIDWhere)){
					$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
				}//end if
		$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";
		
			//echo "<br><br>" . $query . "<br><br>";exit;
			
		$result = doQuery($query);
		
		if(hasRows($result)){
			$cnt = 0;
			$curDate = "";
			$curID = "";
			while( $row = mysql_fetch_row($result)){
				if(($curDate != $row[9]) or ($cnt == 0)){
					$curDate = $row[9];
					
					if($cnt > 0){
				?>
					<tr><td colspan="3"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br></td></tr>
				<?
					}//end if
				?> 
					<tr>
						<td class="eventMain" colspan="7">
							<b><?php
								$datepart = split("-",$row[9]);
								$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
								echo $datestamp;
							?></b>
						</td>
					</tr>
				<?
				}//end if
				
				if($curID != $row[0]){
					$curID = $row[0];
			?>
					<tr>
						<td width="120" class="eventMain">
							
							<?php
								//check for valid start time
								if($row[10] != ''){
									$timepart = split(":", $row[10]);
									$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
								} else {
									$startTime = "";
								}//end if
								
								//check for valid end time
								if($row[12] != ''){
									$timepart = split(":", $row[12]);
									$endTime = '-' . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
								} else {
									$endTime = "";
								}//end if
									
								//check for valid start time
								if($row[11] == 0){
									echo $startTime . $endTime;
								} elseif($row[11] == 1) {
									echo "<i>All Day Event</i>";
									
								} elseif($row[11] == 2) {
									echo "<i>TBA</i>";
									
								}//end if
							?>
						</td>
						<td width="10">&nbsp;</td>
						<td class="eventMain">
							<a href="<?echo CalRoot;;?>/index.php?com=detail&eID=<?echo $row[0];?>" class="eventMain" target="_blank"><?echo cOut($row[1]);?></a>
						</td>
					</tr>
			<?
				}//end if
				
			$cnt = $cnt + 1;
			}//end while
	} else {
	?>
	<tr>
		<td class="eventMain">
			There are no events that meet that search criteria.<br>
			<a href="<?echo CalRoot;?>/index.php?com=search" class="eventMain">Please click here to search again.</a>
			<br><br>
		</td>
	</tr>
	<?php
	}//end if
	?>
</table>