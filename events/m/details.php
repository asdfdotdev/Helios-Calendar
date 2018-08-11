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
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	}//end if
	
	$backDate = date("Y-m-d");
	$eventDate = "";
	$eventTitle = "";
	$starTime = "";
	$endTime = "";
	$tbd = "";
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . $eID);
	if(hasRows($result)){
		doQuery("UPDATE " . HC_TblPrefix . "events SET MViews = " . (mysql_result($result,0,34) + 1) . " WHERE PkID = " . $eID);
		
		$backDate = mysql_result($result,0,9);
		$eventDate = cleanXMLChars(stampToDate(mysql_result($result,0,9), "D M j, Y"));
		$eventTitle = cleanXMLChars(strip_tags(mysql_result($result,0,1)));
		$tbd = mysql_result($result,0,11);
		//check start time
		if(mysql_result($result,0,10) != ''){
			$timepart = split(":", mysql_result($result,0,10));
			$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		//check end time
		if(mysql_result($result,0,12) != ''){
			$timepart = split(":", mysql_result($result,0,12));
			$endTime = "<br/>to " . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		$locID = cOut(mysql_result($result,0,35));
		$contactName = cOut(mysql_result($result,0,13));
		$contactPhone = cOut(mysql_result($result,0,15));
		$eventDescription = cOut(mysql_result($result,0,8));
		
		if($locID > 0){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($locID));
			if(hasRows($result)){
				$locName = cOut(mysql_result($result,0,1));
				$locAddress = cOut(mysql_result($result,0,2));
				$locAddress2 = cOut(mysql_result($result,0,3));
				$locCity = cOut(mysql_result($result,0,4));
				$locState = cOut(mysql_result($result,0,5));
				$locZip = cOut(mysql_result($result,0,7));
				$locCountry = cOut(mysql_result($result,0,6));
			}//end if
		} else {
			$locName = mysql_result($result,0,2);
			$locAddress = mysql_result($result,0,3);
			$locAddress2 = mysql_result($result,0,4);
			$locCity = mysql_result($result,0,5);
			$locState = mysql_result($result,0,6);
			$locZip = mysql_result($result,0,7);
			$locCountry = cOut(mysql_result($result,0,37));
		}//end if
	}//end if
	
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");
	$datepart = split("-", $theDate);
	$dateStamp = date("M jS Y", mktime(0, 0, 0, $datepart[1], $datepart[2], $datepart[0]));	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName;?> Mobile</title>
</head>
<body>
	<div class="menu">
		<a accesskey="4" href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $backDate;?>" class="mnu">Back</a> &#124;
		<a accesskey="5" href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu">Today</a>
	</div>
	<div class="content">
	<?php
	if($eventTitle != ''){
		echo "<div class=\"eventDate\">" . $eventDate . "</div>";
		echo $eventTitle . "<br/>";
		echo "<div class=\"eventLabel\">Time</div>";
		
		if($tbd == 0){
			if(strlen($endTime) > 0){
				echo $startTime . $endTime;
			} else {
				echo cleanXMLChars($startTime . $endTime);
			}//end if
		} elseif($tbd == 1){
			echo "This is an All Day Event";
		} elseif($tbd == 2){
			echo "Start Time TBA";
		}//end if	
			
			
		if($locName != ''){
			echo "<div class=\"eventLabel\">Location</div>";
			echo $locName;
		
			if($locAddress != ''){
				echo "<br/>" . $locAddress;
			}//end if
			if($locAddress2 != ''){
				echo "<br/>" . $locAddress2;
			}//end if
			if($locCity != ''){
				echo "<br/>" . $locCity;
				
				if($locState != ''){
					echo ", " . $locState;
				}//end if
			}//end if
			if($locZip != ''){
				echo " " . $locZip;
			}//end if
			if($locCountry != ''){
				echo "<br/>" . $locCountry;
			}//end if
		}//end if
	
		
		if(($contactName != '') || ($contactPhone != '')){
			echo "<div class=\"eventLabel\">Contact</div>";
			
			if($contactName != ''){
				echo $contactName;
			}//end if
				
			if($contactPhone != ''){
				echo "<br/>Phone: " . $contactPhone;
			}//end if
		}//end if
		
		if($eventDescription != ''){
			echo "<div class=\"eventLabel\">Description</div>";
		
			echo cleanXMLChars( strip_tags($eventDescription) );
		}//end if
	}//end if	?>

	</div>
	<div class="menu">
		<a href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo $backDate;?>" class="mnu">Back</a> &#124;
		<a href="<?php echo CalRoot;?>/m/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu">Today</a>
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