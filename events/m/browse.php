<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	include('overhead.php');
	
echo "<?xml version=\"1.0\"?>";	?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
	"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 35");
	$hc_timezoneOffset = cOut(mysql_result($result,0,0));
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
	
	$day = date("d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$month = date("n",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$year = date("Y",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	
	
	if(isset($_GET['date'])){
		$theDate = $_GET['date'];
	}//end if
	
	$datepart = split("-", $theDate);
	if($theDate == '' || !checkdate($datepart[1], $datepart[2], $datepart[0])){
		$theDate = date("Y") . "-" . date("m") . "-" . date("d");
		$datepart = split("-", $theDate);
	}//end if
	
	$theDate = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));
	if($theDate <= date("Y-m-d")){
		$prevDay = $theDate;
	} else {
		$prevDay = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] - 1, $datepart[0]));
	}//end if
	$nextDay = date("Y-m-d", mktime(0, 0, 0, $datepart[1], $datepart[2] + 1, $datepart[0]));
	$dateStamp = strftime($hc_dateFormat, mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName . " " . $hc_lang_mobile['Mobile'];?></title>
</head>
<body>
	<div class="menu">
		<a accesskey="4" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $prevDay;?>" class="mnu"><?php echo $hc_lang_mobile['Previous'];?></a> &#124;
		<a accesskey="5" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu"><?php echo $hc_lang_mobile['Today'];?></a> &#124;
		<a accesskey="6" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $nextDay;?>" class="mnu"><?php echo $hc_lang_mobile['Next'];?></a>
	</div>
	<div class="content">
	<?php
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $theDate . "' ORDER BY StartDate, TBD, StartTime, Title");
		echo "<div class=\"eventDate\">" . cleanXMLChars($dateStamp) . "</div>";
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
			
				if($row[10] != ''){
					$timepart = split(":", $row[10]);
					$startTime = strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				} else {
					$startTime = "";
				}//end if
				
				if($row[11] == 0){
					$timeStamp = $startTime;
				} elseif($row[11] == 1) {
					$timeStamp = "<i>" . $hc_lang_mobile['AllDay'] . "</i>";
				} elseif($row[11] == 2) {
					$timeStamp = "<i>" . $hc_lang_mobile['TBD'] . "</i>";
				}//end if	?>
				<div class="eventLink">
					<a href="<?php echo MobileRoot;?>/details.php?eID=<?php echo $row[0];?>"><?php echo cleanXMLChars(strip_tags($timeStamp)) . "--" . cleanXMLChars(strip_tags($row[1]));?></a>
				</div>
	<?php
			}//end while	
		} else {
			echo "<div align=\"center\"><b>" . $hc_lang_mobile['NoEvents'] . "</b></div>";
		}//end if	?>
	</div>
	<div class="menu">
		<a accesskey="4" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $prevDay;?>" class="mnu"><?php echo $hc_lang_mobile['Previous'];?></a> &#124;
		<a accesskey="5" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu"><?php echo $hc_lang_mobile['Today'];?></a> &#124;
		<a accesskey="6" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $nextDay;?>" class="mnu"><?php echo $hc_lang_mobile['Next'];?></a>
	</div>
	<div class="footer">
		<div>helios&nbsp;<img src="<?php echo CalRoot;?>/images/favicon.png" width="16" height="16" alt="" style="vertical-align:middle;" />&nbsp;calendar</div>
		<a accesskey="1" href="<?php echo MobileRoot;?>/browse.php"></a>
		<a accesskey="2" href="<?php echo MobileRoot;?>/search.php"></a>
		<a accesskey="3" href="<?php echo MobileRoot;?>/lang.php"></a>
		<a accesskey="0" href="<?php echo MobileRoot;?>/help.php"></a>
	</div>
</body>
</html>