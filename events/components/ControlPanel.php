<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	/*	include("../includes/include.php");
		$hc_calStartDay = 1;	*/?>
<div id="controlPanel">
	<form name="frmJump" id="frmJump" action="">
	<table cellpadding="0" cellspacing="0" border="0" class="miniCalTable" align="center">
<?	if(isset($_GET['day']) && is_numeric($_GET['day'])){
		$curDay = $_GET['day'];
	} else {
		$curDay = date("d");
	}//end if
	
	if(isset($_GET['month']) && is_numeric($_GET['month'])){
		$curMonth = $_GET['month'];
	} else {
		$curMonth = date("n");
	}//end if
	
	if(isset($_GET['year']) && is_numeric($_GET['year'])){
		$curYear = $_GET['year'];
	} else {
		$curYear = date("Y");
	}//end if
	
	$query = "	SELECT DISTINCT StartDate 
				FROM " . HC_TblPrefix . "events
					LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
				WHERE IsActive = 1 AND 
					  IsApproved = 1 AND
					  (StartDate BETWEEN '" . date("Y-m-d", mktime(0,0,0,$curMonth,1,$curYear)) . "' AND '" . date("Y-m-d", mktime(0,0,0,$curMonth+1,0,$curYear)) . "')";
	
	if( isset($_SESSION['hc_favorites']) ){
		$query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['hc_favorites'] . ") ";
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
			<td class="miniCalNav" onclick="window.location.href='<?echo CalRoot;?>/index.php?year=<?echo $navBackYear?>&amp;month=<?echo $navBackMonth;?>';">&lt;</td>
			<td class="miniCalTitle" colspan="5">
			<select name="jumpMonth" id="jumpMonth" class="miniCalJump" onchange="window.location.href=document.frmJump.jumpMonth.value;">
			<?	$jumpMonth = date("n", mktime(0,0,0,$curMonth-12,1,$curYear));
				$jumpYear = date("Y", mktime(0,0,0,$curMonth-12,1,$curYear));
				for($i = 0; $i < 25; $i++){	?>
				<option <?if($i == 12){echo "selected=\"selected\"";}?> value="<?echo CalRoot;?>/index.php?year=<?echo date("Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?>&amp;month=<?echo date("n", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?>"><?echo date("M Y", mktime(0,0,0,$jumpMonth + $i,1,$jumpYear));?></option>
			<?	}//end for?>
			</select>
			</td>
			<td class="miniCalNav" onclick="window.location.href='<?echo CalRoot;?>/index.php?year=<?echo $navForwYear?>&amp;month=<?echo $navForwMonth;?>';">&gt;</td>
		</tr>
<?	//0 (for Sunday) through 6 (for Saturday)
	$d = $hc_calStartDay;
	
	echo "<tr>";
	$daysOfWeek = array(0 => "S", 1 => "M", 2 => "T", 3 => "W", 4 => "T", 5 => "F", 6 => "S");
	for($i = 0; $i < 7; $i++){	?>
		<td class="miniCalDOW"><?echo $daysOfWeek[$d % 7];?></td>
<?		$d++;
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
<?	}//end for
	
	for($x = 0; $x < date("t", mktime(0,0,0,$curMonth,1,$curYear)); $x++){	
		if($i % 7 == 0){echo "</tr><tr>";}?>
		<td <?if(($x+1 == date("d")) && (date("m") == $curMonth) && (date("Y") == $curYear)){?>class="miniCalToday"<?}elseif(in_array($x+1, $events)){?>class="miniCalEvents"<?}else{?>class="miniCal"<?}?> onclick="window.location.href='<?echo CalRoot;?>/index.php?year=<?echo $curYear?>&amp;month=<?echo $curMonth;?>&amp;day=<?echo $x+1;?>';"><?echo date("d", mktime(0,0,0,$curMonth,$x+1,$curYear));?></td>
<?	$i++;
	}//end for
	
	if($i % 7 > 0){
		for($i = $i % 7; $i < 7; $i++){	?>
			<td class="miniCalFiller">&nbsp;</td>
	<?	}//end for	
	}//end if	?>
		</tr>
	</table>
	</form>
	<br />
	<a href="<?echo CalRoot;?>/rss.php" class="controlPanel"><img class="controlPanel" src="<?echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?echo CalName;?> All Events RSS Feeds" /> All Events</a>
	<br />
	<a href="<?echo CalRoot;?>/rss.php?s=1" class="controlPanel"><img class="controlPanel" src="<?echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?echo CalName;?> Newest Events RSS Feeds" /> Newest Events</a>
	<br />
	<a href="<?echo CalRoot;?>/rss.php?s=3" class="controlPanel"><img class="controlPanel" src="<?echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?echo CalName;?> Featured RSS Feeds" /> Featured Events</a>
	<br />
	<a href="<?echo CalRoot;?>/rss.php?s=2" class="controlPanel"><img class="controlPanel" src="<?echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?echo CalName;?> Most Popular RSS Feeds" /> Most Popular Events</a>
	<br />
</div>