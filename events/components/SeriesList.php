<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
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
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<?php
		$result = doQuery("	SELECT *
							FROM " . HC_TblPrefix . "events
							WHERE SeriesID = '" . cIn($sID) . "'
								  AND IsActive = 1 AND IsApproved = 1
								  AND StartDate >= NOW()
							ORDER BY StartDate, TBD, StartTime, Title");
		
		echo "<tr>";
		echo "<td colspan=\"3\" class=\"eventMain\" align=\"right\">";
		echo "<a href=\"" . CalRoot . "/\" class=\"eventMain\">View This Week's Events</a>";
		echo "<br><img src=\"" . CalRoot . "/images/spacer.gif\" width=\"1\" height=\"5\" alt=\"\" border=\"0\"><br>";
		echo "</td>";
		echo "</tr>";
		
		if(hasRows($result)){
			$cnt = 0;
			$curDate = "";
			?>
				<tr>
					<td colspan="3" class="eventMain">
					<?
						echo "<b>" . cOut(mysql_result($result,0,1)) . "</b>";
						mysql_data_seek($result,0);
					?>
					</td>
				</tr>
				<tr><td bgcolor="#666666" colspan="3"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
			
			<?
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
			?>
				<tr>
					<td style="padding:2px;" <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="120" class="eventMain">
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
					<td <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="5">&nbsp;</td>
					<td <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?>>
							<?php
								$datepart = split("-",$row[9]);
								$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
							?>
							<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0];?>" class="eventMain"><?echo $datestamp;?></a>
						</td>
				</tr>
			<?
				$cnt++;
			}//end while
	} else {
	?>
	<tr>
		<td class="eventMain">
			<br>
			There are no events left in this series.<br><br>
		</td>
	</tr>
	<?php
	}//end if
	?>
</table>