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
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn($_GET['eID']) : 0;
	$hourOffset = date("G") + ($hc_cfg35);
	$curDate = date("Y-m-d", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	
	$backDate = $curDate;
	$eventTitle = "Invalid Event";
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . $eID);
	if(hasRows($result)){
		if(!preg_match('/(bot|crawler|indexing|checker|sleuth|seeker)/',$_SERVER['HTTP_USER_AGENT'])){
			doQuery("UPDATE " . HC_TblPrefix . "events SET MViews = " . (mysql_result($result,0,34) + 1) . " WHERE PkID = " . $eID);
		}//end if
		
		$backDate = cOut(mysql_result($result,0,9));
		$eventDate = stampToDate(cOut(mysql_result($result,0,9)), $hc_cfg14);
		$eventTitle = strip_tags(cOut(mysql_result($result,0,1)));
		$tbd = cOut(mysql_result($result,0,11));
		
		if(mysql_result($result,0,10) != ''){
			$timepart = split(":", cOut(mysql_result($result,0,10)));
			$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		$endTime = '';
		if(mysql_result($result,0,12) != ''){
			$timepart = split(":", cOut(mysql_result($result,0,12)));
			$endTime = '<br/>' . $hc_lang_mobile['to'] . " " . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		$locID = cOut(mysql_result($result,0,35));
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
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
			$locName = cOut(mysql_result($result,0,2));
			$locAddress = cOut(mysql_result($result,0,3));
			$locAddress2 = cOut(mysql_result($result,0,4));
			$locCity = cOut(mysql_result($result,0,5));
			$locState = cOut(mysql_result($result,0,6));
			$locZip = cOut(mysql_result($result,0,7));
			$locCountry = cOut(cOut(mysql_result($result,0,37)));
		}//end if
	}//end if?>
</head>
<body>
<div id="header"><?php echo $eventTitle;?></div>
<div id="menu">
	<a href="<?php echo MobileRoot;?>/weekly.php?date=<?php echo $backDate;?>">&laquo;&nbsp;<?php echo $hc_lang_mobile['ThisWeek'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $backDate;?>">&laquo;&nbsp;<?php echo $hc_lang_mobile['ThisDay'];?></a> &#124;
	<a href="<?php echo MobileRoot;?>/browse.php?date=<?php echo $curDate;?>"><?php echo $hc_lang_mobile['Today'];?></a>
</div>
<div class="content" style="padding-left:5px;">
<?php
	if($eID > 0){
		echo '<b>' . $eventDate . '</b>';
		echo '<div class="eventLabel">' . $hc_lang_mobile['Time'] . '</div>';
		
		switch($tbd){
			case 0:
				echo $startTime . $endTime;
				break;
			case 1:
				echo $hc_lang_mobile['AllDayLong'];
				break;
			case 2:
				echo $hc_lang_mobile['TBDLong'];
				break;
		}//end switch
		
		if($locName != ''){
			echo '<div class="eventLabel">' . $hc_lang_mobile['Location'];
			if($locAddress != '' && $locCity != '' && $locState != '' && $locZip != ''){
				echo strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') ? ' [<a href="http://maps.google.com/maps?q=' . urlencode($locAddress . ', ' . $locCity . ', ' . $locState . ', ' . $locZip) . '&f=d" class="main">Map It</a>]' : '';
			}//end if
			echo '</div>';
			
			echo cleanXMLChars(strip_tags($locName));
			echo ($locAddress != '') ? "<br/>" . $locAddress : '';
			echo ($locAddress2 != '') ? "<br/>" . $locAddress2 : '';
			
			if($locCity != ''){
				echo "<br/>" . $locCity;
				echo ($locState != '') ? ", " . $locState : '';
			}//end if
			
			echo ($locZip != '') ? " " . $locZip : '';
			echo ($locCountry != '') ? "<br/>" . $locCountry : '';
		}//end if
		
		if(($contactName != '') || ($contactPhone != '')){
			echo '<div class="eventLabel">' . $hc_lang_mobile['Contact'] . '</div>';
			echo ($contactName != '') ? $contactName . '<br/>' : '';
			
			if($contactPhone != ''){
				echo (!strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')) ?
					$hc_lang_mobile['Phone'] . ' ' . $contactPhone:
					$hc_lang_mobile['Phone'] . ' <a href="tel:' . $contactPhone . '">' . $contactPhone . '</a>';
			}//end if
		}//end if
		
		if($eventDescription != ''){
			echo '<div class="eventLabel">' . $hc_lang_mobile['Description'] . '</div>';
			echo strip_tags($eventDescription);
		}//end if
	} else {
		echo '<div style="padding:5px;">' . $hc_lang_mobile['NoEvent'] . "</div>";
	}//end if	?>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>