<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	$isAction = 1;
	include('../includes/include.php');	

echo "<?xml version=\"1.0\"?>";	?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
	"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<?php
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");
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
	$dateStamp = date("D M j, Y", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName;?> Mobile</title>
</head>
<body>
	<div class="menu">
		<a accesskey="4" href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $prevDay;?>" class="mnu">Prev</a> &#124;
		<a accesskey="5" href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu">Today</a> &#124;
		<a accesskey="6" href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $nextDay;?>" class="mnu">Next</a>
	</div>
	<div class="content">
	<?php
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $theDate . "' ORDER BY StartDate, TBD, StartTime, Title");
		echo "<div class=\"eventDate\">" . cleanXMLChars($dateStamp) . "</div>";
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
			
				if($row[10] != ''){
					$timepart = split(":", $row[10]);
					$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				} else {
					$startTime = "";
				}//end if
				
				if($row[11] == 0){
					$timeStamp = $startTime;
				} elseif($row[11] == 1) {
					$timeStamp = "<i>All Day</i>";
				} elseif($row[11] == 2) {
					$timeStamp = "<i>TBA</i>";
				}//end if	?>
				<div class="eventLink">
					<a href="<?php echo CalRoot;?>/m/details.php?eID=<?php echo $row[0];?>"><?php echo cleanXMLChars(strip_tags($timeStamp)) . "--" . cleanXMLChars(strip_tags($row[1]));?></a>
				</div>
	<?php
			}//end while	
		} else {
			echo "<div align=\"center\"><b>No events for<br/>this date.</b></div>";
		}//end if	?>
	</div>
	<div class="menu">
		<a href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $prevDay;?>" class="mnu">Prev</a> &#124;
		<a href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu">Today</a> &#124;
		<a href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $nextDay;?>" class="mnu">Next</a>
	</div>
	<div class="footer">
		Powered by:<br/>
		Helios Calendar
	<a accesskey="1" href="<?php echo CalRoot;?>/m/browse.php"></a>
	<a accesskey="2" href="<?php echo CalRoot;?>/m/search.php"></a>
	<a accesskey="0" href="<?php echo CalRoot;?>/m/help.php"></a>
	</div>
</body>
</html>