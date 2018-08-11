<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	include('includes/header.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	$sysDate = date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	
	if(!isset($_GET['date'])){
		$sysDateParts = explode("-",$sysDate);
		$day = $sysDateParts[2];
		$month = $sysDateParts[1];
		$year = $sysDateParts[0];
	} else {
		$passDateParts = explode("-",$_GET['date']);
		$year = $passDateParts[0];
		$month = $passDateParts[1];
		$day = $passDateParts[2];
	}//end if
	
	$dayOfWeek = date("w", mktime($hourOffset,0,0,$month,$day,$year));
	$remove = ($dayOfWeek + 6) % 7;
	$monDay = $day - $remove;
	$startDate = date("Y-m-d", mktime($hourOffset,0,0,$month,$monDay,$year));
	$endDate = date("Y-m-d", mktime($hourOffset,0,0,$month,($monDay + 6),$year));
	
	$prevDate = ($startDate <= date("Y-m-d")) ? $startDate : date("Y-m-d", mktime($hourOffset,0,0,$month,($monDay - 7),$year));
	$nextDate = date("Y-m-d", mktime($hourOffset,0,0,$month,($monDay + 7),$year));
	$title = $hc_lang_mobile['WeekOf'] . ' ' . stampToDate($startDate, $hc_cfg14);?>
</head>
<body>
<div id="header"><?php echo $title;?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/weekly.php?date=<?php echo $prevDate;?>">&laquo;&nbsp;<?php echo $hc_lang_mobile['Previous'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/weekly.php"><?php echo $hc_lang_mobile['ThisWeek'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/weekly.php?date=<?php echo $nextDate;?>"><?php echo $hc_lang_mobile['Next'];?>&nbsp;&raquo;</a>
</div>
<div id="content">
	<ul>
<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND (StartDate BETWEEN '" . $startDate . "' AND '" . $endDate . "') AND StartDate >= '" . $sysDate . "' ORDER BY StartDate, TBD, StartTime, Title");
	if(hasRows($result)){
		$curDate = '';
		while($row = mysql_fetch_row($result)){
			if($row[9] != $curDate){
				$curDate = $row[9];
				echo '<li class="date"><b>' . cleanXMLChars(stampToDate($row[9],$hc_cfg14)) . '</b></li>';
			}//end if
			
			if($row[10] != ''){
				$timepart = split(":", $row[10]);
				$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			} else {
				$startTime = "";
			}//end if
			
			switch($row[11]){
				case 0:
					$timeStamp = $startTime;
					break;
				case 1:
					$timeStamp = $hc_lang_mobile['AllDay'];
					break;
				case 2:
					$timeStamp = $hc_lang_mobile['TBD'];
					break;
			}//end switch
			
			echo '<li><a href="' . MobileRoot . '/details.php?eID=' . $row[0] . '"><b>' . strip_tags($timeStamp) . '</b>&nbsp;' . strip_tags($row[1]) . '</a></li>';
		}//end while	
	} else {
		echo '<li style="padding:10px 0px 10px 5px;">' . $hc_lang_mobile['NoEvents'] . "</li>";
	}//end if	?>
	</ul>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>