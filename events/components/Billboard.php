<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="eventMain">
			<?php
				$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (12,13,14,15) ORDER BY PkID");
				$maxShow = mysql_result($result,0,0);
				$fillMax = mysql_result($result,1,0);
				$dateFormat = mysql_result($result,2,0);
				$showTime = mysql_result($result,3,0);
				
				$query = "	SELECT PkID, Title, StartDate, StartTime, IsBillboard
							FROM " . HC_TblPrefix . "events
							WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW()";
				if($fillMax == 0){
					$query .= " AND IsBillboard = 1 ";
				}//end if
				
				$query .= " ORDER BY IsBillboard DESC, StartDate, Title LIMIT " . $maxShow;
				
				$result = doQuery($query);
				$blocked = false;
				$curDate = "";
				$cnt = 0;
				
				if(hasRows($result)){
					while($row = mysql_fetch_row($result)){
						
						if($row[4] == 0 && $blocked == false){
							$blocked = true;
							if($cnt > 0){echo "<br>";}
							echo "<b>Upcoming Events</b><br>";
						}//end if
						
						if($curDate != $row[2]){
							$curDate = cOut($row[2]);
							$dateParts = split("-", $row[2]);
							if($cnt > 0){echo "<br>";}
							echo "<b>" . stampToDate($row[2], $dateFormat) . "</b>";
						}//end if
						
						if($showTime == 1){
							if($row[3] != ''){
								$timeparts = split(":", $row[3]);
								$startTime = date("h:i A", mktime($timeparts[0], $timeparts[1], $timeparts[2], 1, 1, 1971));
							} else {
								$startTime = "All Day";
							}//end if
							
							echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td>&nbsp;</td><td class=\"eventMain\"><li><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a> - " . $startTime . "</td></tr></table>";
							
						} else {
							echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td>&nbsp;</td><td class=\"eventMain\"><li><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a></td></tr></table>";
							
						}//end if
						$cnt = $cnt + 1;
					}//end while
				
				} else {
					echo "There are no billboard events currently available.";
				}//end if
			?>
		</td>
	</tr>
</table>