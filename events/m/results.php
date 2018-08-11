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
	$searchTxt = "";
	if(isset($_POST['srchText'])){
		$searchTxt = $_POST['srchText'];
	}//end if
	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName;?> Mobile</title>
</head>
<body>
	<div class="menu">
		Search Results
	</div>
	<div class="content">
	<?php
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' AND (Title LIKE '%" . cIn($searchTxt) . "%' OR Description LIKE '%" . cIn($searchTxt) . "%') ORDER BY StartDate, TBD, StartTime, Title LIMIT 10");
		if(hasRows($result)){
			$curDate = "";
			while($row = mysql_fetch_row($result)){
				if($row[9] != $curDate){
					$curDate = $row[9];
					echo "<div class=\"eventDate\">" . cleanXMLChars(stampToDate($row[9],"D M j, Y")) . "</div>";
				}//end if
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
		<a href="<?php echo CalRoot;?>/m/browse.php" class="mnu">Browse Events</a>
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