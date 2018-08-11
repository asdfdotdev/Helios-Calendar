<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	include('includes/header.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curDate = date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	$theDate = (isset($_GET['date'])) ? cIn($_GET['date']) : $curDate;
	$datepart = split("-", $theDate);
	
	if($theDate == '' || !checkdate($datepart[1], $datepart[2], $datepart[0])){
		$theDate = $curDate;
		$datepart = split("-", $theDate);
	}//end if
	
	$prevDay = ($theDate <= date("Y-m-d")) ? $theDate : date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] - 1, $datepart[0]));
	$nextDay = date("Y-m-d", mktime($hourOffset, 0, 0, $datepart[1], $datepart[2] + 1, $datepart[0]));
	$dateStamp = strftime($hc_cfg14, mktime($hourOffset, 0, 0, $datepart[1], $datepart[2], $datepart[0]));	?>
</head>
<body>
<div id="header"><?php echo $dateStamp;?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $prevDay;?>">&laquo;&nbsp;<?php echo $hc_lang_mobile['Previous'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $curDate;?>"><?php echo $hc_lang_mobile['Today'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $nextDay;?>"><?php echo $hc_lang_mobile['Next'];?>&nbsp;&raquo;</a>
</div>
<div id="content">
	<ul>
<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $theDate . "' ORDER BY StartDate, TBD, StartTime, Title");
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
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