<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

/*
	$hc_calStartDay = 1;		//	Calendar First Day of the Week, 0 = Sunday, 6 = Saturday
	$hc_timezoneOffset = 0;		//	Number of Hours to Modify Server Time By
*/
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
?>
	<form name="frmJump" id="frmJump" action="">
	<table cellpadding="0" cellspacing="0" border="0" class="miniCalTable" align="center">
<?php
	$servDay = date("d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$servMonth = date("n",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$servYear = date("Y",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	
	$curDay = $servDay;
	if(isset($_GET['day']) && is_numeric($_GET['day'])){
		$curDay = $_GET['day'];
	}//end if
	
	$curMonth = $servMonth;
	if(isset($_GET['month']) && is_numeric($_GET['month'])){
		$curMonth = $_GET['month'];
	}//end if
	
	$curYear = $servYear;
	if(isset($_GET['year']) && is_numeric($_GET['year'])){
		$curYear = $_GET['year'];
	}//end if
	
	if(($curMonth < $servMonth && $curYear == $servYear) && $hc_browsePast == 0){
		$curMonth = $servMonth;
	} elseif(($curYear < $servYear) && $hc_browsePast == 0){
		$curMonth = $servMonth;
		$curYear = $servYear;
	}//end if
	
	$query = "	SELECT DISTINCT e.StartDate
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
				WHERE e.IsActive = 1 AND 
					  e.IsApproved = 1 AND
					  (e.StartDate BETWEEN '" . date("Y-m-d", mktime(0,0,0,$curMonth,1,$curYear)) . "' AND '" . date("Y-m-d", mktime(0,0,0,$curMonth+1,0,$curYear)) . "')";
	
	if(isset($_SESSION['hc_favCat']) && $_SESSION['hc_favCat'] != ''){
		$query = $query . " AND ec.CategoryID in (" . $_SESSION['hc_favCat'] . ") ";
	}//end if
	
	if(isset($_SESSION['hc_favCity']) && $_SESSION['hc_favCity'] != ''){
		$query = $query . " AND (e.LocationCity IN (" . $_SESSION['hc_favCity'] . ") OR l.City IN (" . $_SESSION['hc_favCity'] . "))";
	}//end if
	
	$query = $query . " ORDER BY StartDate";
	$result = doQuery($query);
	
	$events[] = "";
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
			$events[] = stampToDate($row[0], "d");
		}//end while
	}//end if
	
	$navBackMonth = ($curMonth-1) % 12;
	$navBackYear = $curYear;
	$navForwMonth = ($curMonth+1) % 12;
	$navForwYear = $curYear;
	if($curMonth == 1){
		$navBackMonth = 12;
		$navBackYear = $curYear - 1;
	} elseif($curMonth == 11) {
		$navForwMonth = 12;
	} elseif($curMonth == 12){
		$navForwYear = $curYear + 1;
	}//end if
?>		<tr>
			<td class="miniCalNav" onclick="window.location.href='<?php echo CalRoot;?>/index.php?year=<?php echo $navBackYear?>&amp;month=<?php echo $navBackMonth;?>';">&lt;</td>
			<td class="miniCalTitle" colspan="5">
			<select name="jumpMonth" id="jumpMonth" class="miniCalJump" onchange="window.location.href=document.frmJump.jumpMonth.value;">
		<?php 	$jumpMonth = date("n", mktime(0,0,0,$curMonth-12,1,$curYear));
				$jumpYear = date("Y", mktime(0,0,0,$curMonth-12,1,$curYear));
				for($i = 0; $i <= 24; $i++){	?>
				<option <?php if($i == 12){echo "selected=\"selected\"";}?> value="<?php echo CalRoot;?>/index.php?year=<?php echo date("Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?>&amp;month=<?php echo date("n", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?>"><?php echo date("M Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?></option>
		<?php 	}//end for?>
			</select>
			</td>
			<td class="miniCalNav" onclick="window.location.href='<?php echo CalRoot;?>/index.php?year=<?php echo $navForwYear?>&amp;month=<?php echo $navForwMonth;?>';">&gt;</td>
		</tr>
<?php	$d = $hc_calStartDay;
	
	echo "<tr>";
	$daysOfWeek = array(0 => "S", 1 => "M", 2 => "T", 3 => "W", 4 => "T", 5 => "F", 6 => "S");
	for($i = 0; $i < 7; $i++){	?>
		<td class="miniCalDOW"><?php echo $daysOfWeek[$d % 7];?></td>
<?php 		$d++;
	}//end for
	echo "</tr><tr>";
	
	$fillCnt = date("w", mktime(0,0,0,$curMonth,1,$curYear));
	if($hc_calStartDay > 0){
		if($hc_calStartDay <= date("w", mktime(0,0,0,$curMonth,1,$curYear))){
			$fillCnt = $fillCnt - $hc_calStartDay;
		} else {
			$fillCnt = 7 - ($hc_calStartDay - $fillCnt);
		}//end if
	}//end if
	
	for($i = 0; $i < $fillCnt; $i++){	?>
		<td class="miniCalFiller">&nbsp;</td>
<?php 	}//end for
	
	for($x = 0; $x < date("t", mktime(0,0,0,$curMonth,1,$curYear)); $x++){	
		if($i % 7 == 0){echo "</tr><tr>";}?>
		<td <?php if(($x+1 == $servDay) && ($servMonth == $curMonth) && ($servYear == $curYear)){?>class="miniCalToday"<?php }elseif(in_array($x+1, $events)){?>class="miniCalEvents"<?php }else{?>class="miniCal"<?php }?> onclick="window.location.href='<?php echo CalRoot;?>/index.php?year=<?php echo $curYear?>&amp;month=<?php echo $curMonth;?>&amp;day=<?php echo $x+1;?>';"><?php echo date("d", mktime(0,0,0,$curMonth,$x+1,$curYear));?></td>
<?php 	$i++;
	}//end for
	
	if($i % 7 > 0){
		for($i = $i % 7; $i < 7; $i++){	?>
			<td class="miniCalFiller">&nbsp;</td>
<?php 	}//end for	
	}//end if	?>
		</tr>
	</table>
	</form>