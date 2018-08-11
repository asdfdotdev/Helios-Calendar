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
	include('includes/include.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (2,24,28,33,35)");
	$maxShow = cOut(mysql_result($result,0,0));
	$dateFormat = cOut(mysql_result($result,1,0));
	$hc_langType = cOut(mysql_result($result,2,0));
	$series = cOut(mysql_result($result,3,0));
	$hc_timezoneOffset = cOut(mysql_result($result,4,0));
	
	if(!isset($_SESSION['LangSet'])){
		$_SESSION['LangSet'] = $hc_langType;
	}//end if
	include($hc_langPath . $_SESSION['LangSet'] . '/config.php');
	
	$hourOffset = date("G");
	$timeZone = date("O");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
		$timeZone = $timeZone + (abs($hc_timezoneOffset) . "00");
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
		$timeZone = $timeZone - (abs($hc_timezoneOffset) . "00");
	}//end if

	if($timeZone > 0 && strlen(abs($timeZone)) < 4){
		$timeZone = " +0" . abs($timeZone);
	} elseif($timeZone < 0 && strlen(abs($timeZone)) < 4){
		$timeZone = " -0" . abs($timeZone);
	}//end if
	
	$dateRange = "e.StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'";
	if(isset($_GET['d']) && is_numeric($_GET['d'])){
		if($_GET['d'] == 1){
			$dateRange = "e.StartDate = '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "'";
		} else if($_GET['d'] == 2){
			$dateRange = "e.StartDate Between '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "' AND '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d")+7,date("Y"))) . "'";
		} else if($_GET['d'] == 3) {
			$dateRange = "e.StartDate Between '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "' AND '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m")+1,date("d"),date("Y"))) . "'";
		}//end if
	}//end if
	
	header('Content-Type:text/xml; charset=' . $hc_lang_config['CharSet']);
	echo "<?xml-stylesheet type=\"text/css\" href=\"" . CalRoot . "/css/rss.css\"?>";	
	$query = "	SELECT distinct e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				WHERE e.IsActive = 1 AND 
					e.IsApproved = 1 AND 
					" . $dateRange . " AND
					c.IsActive = 1
				ORDER BY e.StartDate, e.TBD, e.StartTime
				LIMIT " . $maxShow * 2;
	$feedName = "All Events";
	
	if(isset($_GET['l']) || isset($_GET['c'])){
		$queryCats = "";
		if(isset($_GET['l'])){
			$catIDs = "0";
			$catID = explode(",", $_GET['l']);
			foreach ($catID as $val){
				$catIDs = $catIDs . "," . cleanXMLChars(strip_tags(cIn($val)));
			}//end for
			$queryCats = " AND c.PkID IN (" . cIn($catIDs) . ")";
		}//end if
		
		
		$queryCity = "";
		if(isset($_GET['c'])){
			$cityNames = "";
			$cityName = explode(",", $_GET['c']);
			foreach ($cityName as $val){
				if($cityNames != ''){$cityNames .= ",";}
				$cityNames .= "'" . cleanXMLChars(strip_tags(cIn($val))) . "'";
			}//end for
			if($cityNames != ""){
				$queryCity = " AND (e.LocationCity IN ( " . $cityNames . ") OR l.City IN (" . $cityNames . "))";
			}//end if
		}//end if
		
		$query = "	SELECT distinct e.*
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
					WHERE e.IsActive = 1 AND 
						e.IsApproved = 1 AND 
						" . $dateRange . " AND
						c.IsActive = 1
					" . $queryCats . "
					" . $queryCity . "
					ORDER BY e.StartDate, e.TBD, e.StartTime
					LIMIT " . $maxShow * 2;
		$feedName = "Custom Feed";
	} else {
		if(isset($_GET['s']) && is_numeric($_GET['s'])){
			if($_GET['s'] == 1){
				//	Newest Events
				$query = "	SELECT distinct e.*
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
							WHERE e.IsActive = 1 AND 
								e.IsApproved = 1 AND 
								" . $dateRange . " AND
								c.IsActive = 1
							ORDER BY e.PublishDate DESC, e.StartDate, e.StartTime
							LIMIT " . $maxShow * 2;
				$feedName = "Newest Events";
				
			} elseif($_GET['s'] == 2){
				// Most Popular Events
				$query = "	SELECT distinct e.*
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
							WHERE e.IsActive = 1 AND 
								e.IsApproved = 1 AND 
								" . $dateRange . " AND
								c.IsActive = 1
							ORDER BY e.Views DESC, e.StartDate, e.StartTime
							LIMIT " . $maxShow * 2;
				$feedName = "Most Popular Events";
				
			} elseif($_GET['s'] == 3){
				// Billboard Events
				$query = "	SELECT distinct e.*
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
							WHERE e.IsActive = 1 AND 
								e.IsApproved = 1 AND 
								" . $dateRange . " AND
								c.IsActive = 1 AND
								e.IsBillboard = 1
							ORDER BY e.StartDate, e.TBD, e.StartTime
							LIMIT " . $maxShow * 2;
				$feedName = "Featured Events";
			}//end if
		}//end if
	}//end if	
	
	include($hc_langPath . $_SESSION['LangSet'] . '/config.php');
	$loc = setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);?>
	
<!-- Generated by Helios Calendar <?php echo HC_Version;?> <?php echo date("\\o\\n Y-m-d \\a\\t H:i:s") . " server time.";?> <?php echo "\n";?> http://www.HeliosCalendar.com -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title><?php echo $feedName . " - " . CalName;?></title>
    <link><?php echo CalRoot;?>/</link>
    <copyright>Copyright 2004-<?php echo date("Y");?> Refresh Web Development LLC</copyright>
	<generator>http://www.HeliosCalendar.com</generator>
	<docs><?php echo CalRoot;?>&#47;index.php&#63;com=rss</docs>
	<description>Upcoming Event Information From The <?php echo CalName;?></description>
<?php	$result = doQuery($query);
		if(hasRows($result)){
			$hideSeries[] = array();
			$cnt = 0;
			while($row = mysql_fetch_row($result)){	
				if($cnt >= $maxShow){break;}//end if
				
				if($row[19] == '' || !in_array($row[19], $hideSeries)){	?>
					<item>
				      <title><![CDATA[<?php echo cleanXMLChars(cleanIntChars(stampToDate(cOut($row[9]), $dateFormat))) . " - " . cleanXMLChars(html_entity_decode(cleanIntChars(cOut($row[1]))));?>]]></title>
				      <link><![CDATA[<?php echo CalRoot . "/index.php?com=detail&eID=" . $row[0];?>]]></link>
				      <description><![CDATA[<?php echo htmlspecialchars_decode(cleanXMLChars(cleanIntChars(cOut($row[8]))));?>]]></description>
					  <guid><?php echo CalRoot . "/index.php&#63;com=detail&amp;eID=" . $row[0];?></guid>
					  <pubDate><?php echo cleanXMLChars(cleanIntChars(stampToDate(cOut($row[9] . " " . $row[10]), "%a, %d %b %Y %H:%M:%S") . $timeZone));?></pubDate>
				    </item>
		<?php 		$cnt++;
				}//end if
				
				if($series == 0 && $row[19] != '' && (!in_array($row[19], $hideSeries))){
					$hideSeries[] = $row[19];
				}//end if
			}//end while
			
		} else {	?>
			<item>
		      <title>There Are No Upcoming Events Available For This Feed</title>
		      <link><?php echo CalRoot;?>/</link>
		      <description>Visit the <?php echo CalName;?> for more information.</description>
		    </item>
<?php 	}//end if	?>
  </channel>
</rss>