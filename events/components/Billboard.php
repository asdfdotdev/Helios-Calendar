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
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="eventMain">
			<?php
				$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (12,13,14,15) ORDER BY PkID");
				$maxShow = mysql_result($result,0,0);
				$fillMax = mysql_result($result,1,0);
				$dateFormat = mysql_result($result,2,0);
				$showTime = mysql_result($result,3,0);
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= now() ORDER BY StartDate LIMIT " . $maxShow);
				$row_cnt = mysql_num_rows($result);
				$showLimit = 0;
				
				if(($row_cnt < $maxShow) and ($fillMax == 1)){
					if($row_cnt == 0){
						$showLimit = $maxShow;
					} else {
						$showLimit = $maxShow - $row_cnt;
					}//end if
					
					$query = "	SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = now() LIMIT " . $showLimit . "
								UNION
								SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= now() ORDER BY StartDate";
					$result = doQuery($query);
				}//end if
				$row_cnt = mysql_num_rows($result);
				
				$curDate = "";
				$cnt = 0;
				
				while($row = mysql_fetch_row($result)){
					
					if($curDate != $row[9]){
						$curDate = cOut($row[9]);
						$dateParts = split("-", $row[9]);
						if($cnt > 0){echo "<br>";}
						echo "<b>" . stampToDate($row[9], $dateFormat) . "</b>";
					}//end if
					
					if($showTime == 1){
						if($row[10] != ''){
							$timepart = split(":", $row[10]);
							$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], $datepart[1], $datepart[2], $datepart[0]));
						} else {
							$startTime = "All Day";
						}//end if
						
						echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td>&nbsp;</td><td class=\"eventMain\"><li><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a> - " . $startTime . "</td></tr></table>";
						
					} else {
						echo "<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td>&nbsp;</td><td class=\"eventMain\"><li><a href=\"" . CalRoot ."/index.php?com=detail&eID=" . $row[0] . "&month=" . $dateParts[1] . "&year=" . $dateParts[0] . "\" class=\"eventMain\">" . cOut($row[1]) . "</a></td></tr></table>";
						
					}//end if
					$cnt = $cnt + 1;
				}//end while
				
				if($row_cnt == 0){
					echo "There are no billboard events currently available.";
				}//end if
			?>
		</td>
	</tr>
</table>