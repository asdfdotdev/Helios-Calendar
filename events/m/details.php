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
		$eventDate = cleanXMLChars(stampToDate(mysql_result($result,0,9), $hc_dateFormat));
		$eventTitle = cleanXMLChars(strip_tags(mysql_result($result,0,1)));
		$tbd = mysql_result($result,0,11);
		//check start time
		if(mysql_result($result,0,10) != ''){
			$timepart = split(":", mysql_result($result,0,10));
			$startTime = strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		//check end time
		if(mysql_result($result,0,12) != ''){
			$timepart = split(":", mysql_result($result,0,12));
			$endTime = "<br/>" . $hc_lang_mobile['to'] . " " . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
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
	
	$theDate = date("Y") . "-" . date("m") . "-" . date("d");	?>
	
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<link rel="apple-touch-icon" href="<?php echo CalRoot;?>/images/appleIcon.png" type="image/png" />
	<title><?php echo CalName . " " . $hc_lang_mobile['Mobile'];?></title>
</head>
<body>
	<div class="menu">
		<a accesskey="4" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $backDate;?>" class="mnu"><?php echo $hc_lang_mobile['Back'];?></a> &#124;
		<a accesskey="5" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu"><?php echo $hc_lang_mobile['Today'];?></a>
	</div>
	<div class="content">
	<?php
	if($eventTitle != ''){
		echo "<div class=\"eventDate\">" . $eventDate . "</div>";
		echo cleanXMLChars(strip_tags($eventTitle)) . "<br/>";
		echo "<div class=\"eventLabel\">" . $hc_lang_mobile['Time'] . "</div>";
		
		if($tbd == 0){
			if(strlen($endTime) > 0){
				echo $startTime . $endTime;
			} else {
				echo cleanXMLChars($startTime . $endTime);
			}//end if
		} elseif($tbd == 1){
			echo $hc_lang_mobile['AllDayLong'];
		} elseif($tbd == 2){
			echo $hc_lang_mobile['TBDLong'];
		}//end if	
			
			
		if($locName != ''){
			echo "<div class=\"eventLabel\">" . $hc_lang_mobile['Location'] . "</div>";
			echo cleanXMLChars(strip_tags($locName));
		
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
			echo "<div class=\"eventLabel\">" . $hc_lang_mobile['Contact'] . "</div>";
			
			if($contactName != ''){
				echo $contactName;
			}//end if
				
			if($contactPhone != ''){
				echo "<br/>" . $hc_lang_mobile['Phone'] . $contactPhone;
			}//end if
		}//end if
		
		if($eventDescription != ''){
			echo "<div class=\"eventLabel\">" . $hc_lang_mobile['Description'] . "</div>";
			echo cleanXMLChars(strip_tags($eventDescription));
		}//end if
	}//end if	?>

	</div>
	<div class="menu">
		<a accesskey="4" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $backDate;?>" class="mnu"><?php echo $hc_lang_mobile['Back'];?></a> &#124;
		<a accesskey="5" href="<?php echo MobileRoot;?>/browse.php?date=<?php echo date("Y") . "-" . date("m") . "-" . date("d");?>" class="mnu"><?php echo $hc_lang_mobile['Today'];?></a>
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