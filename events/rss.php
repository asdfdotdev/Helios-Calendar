<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.index.html
*/
	include('includes/include.php');
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/rss.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	$tzOffset = date("O") + ($hc_cfg35 * 100);
	
	if($tzOffset == 0){
		$tzOffset = "+0000";
	} elseif($tzOffset < 0){
		if(strlen($tzOffset) < 5){
			$tzOffset = ltrim($tzOffset,"-");
			$tzOffset = "-0" . $tzOffset;
		}//end if
	} elseif($tzOffset > 0) {
		$tzOffset = (strlen($tzOffset) < 4) ? '+0' . $tzOffset : '+' . $tzOffset;
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
		
	header('Content-Type:application/rss+xml; charset=' . $hc_lang_config['CharSet']);
	echo "<?xml version=\"1.0\" encoding=\"" . $hc_lang_config['CharSet'] . "\"?>";
	echo "\n<?xml-stylesheet type=\"text/css\" href=\"" . CalRoot . "/css/rss.css\"?>";
	
	$query = "	SELECT distinct e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				WHERE e.IsActive = 1 AND 
					e.IsApproved = 1 AND 
					" . $dateRange . " AND
					c.IsActive = 1
				ORDER BY e.StartDate, e.TBD, e.StartTime
				LIMIT " . $hc_cfg2 * 2;
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
					LIMIT " . $hc_cfg2 * 2;
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
							LIMIT " . $hc_cfg2 * 2;
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
							LIMIT " . $hc_cfg2 * 2;
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
							LIMIT " . $hc_cfg2 * 2;
				$feedName = "Featured Events";
			}//end if
		}//end if
	}//end if?>
	
<!-- Generated by Helios Calendar <?php echo $hc_cfg49;?> <?php echo date("\\o\\n Y-m-d \\a\\t H:i:s") . ' local time.';?> -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title><?php echo $feedName . " - " . CalName;?></title>
    <link><?php echo CalRoot;?>/</link>
    <copyright>Copyright 2004-<?php echo date("Y");?> Refresh Web Development LLC</copyright>
	<generator>http://www.HeliosCalendar.com</generator>
	<docs><?php echo CalRoot;?>&#47;index.php&#63;com=rss</docs>
	<description>Upcoming Event Information From <?php echo CalName;?></description>
<?php	$result = doQuery($query);
		if(hasRows($result)){
			$hideSeries[] = array();
			$cnt = 0;
			
			while($row = mysql_fetch_row($result)){	
				if($cnt >= $hc_cfg2){break;}//end if
				
				if($row[19] == '' || !in_array($row[19], $hideSeries)){	?>
					<item>
				      <title><?php echo cleanXMLChars(stampToDate(cOut($row[9]), $hc_cfg24)) . " - " . html_entity_decode(cleanXMLChars(cOut($row[1])));?></title>
				      <link><![CDATA[<?php echo CalRoot . "/index.php?com=detail&eID=" . $row[0];?>]]></link>
				      <description><?php echo cleanXMLChars(cOut($row[8]));?></description>
				      <comments><![CDATA[<?php echo CalRoot . "/index.php?com=detail&eID=" . $row[0] . "#comments";?>]]></comments>
				      <guid><?php echo CalRoot . "/index.php&#63;com=detail&amp;eID=" . $row[0];?></guid>
					  <?php
					  	$dateParts = explode("-",$row[9]);
					  	$timeParts = explode(":",$row[10]);	
					  	$hour = (isset($timeParts[1])) ? $timeParts[0] : 0;
					  	$min = (isset($timeParts[1])) ? $timeParts[1] : 0;
					  	$sec = (isset($timeParts[1])) ? $timeParts[2] : 0;?>
					  <pubDate><?php echo cleanXMLChars(date("D\, d M Y H:i:s", mktime($hour,$min,$sec,$dateParts[1],$dateParts[2],$dateParts[0])) . ' ' . $tzOffset);?></pubDate>
				    </item>
		<?php 		$cnt++;
				}//end if
				
				if($hc_cfg33 == 0 && $row[19] != '' && (!in_array($row[19], $hideSeries))){
					$hideSeries[] = $row[19];
				}//end if
			}//end while
			
		} else {	?>
			<item>
		      <title><?php echo $hc_lang_rss['RSSNoEvents'];?></title>
		      <link><?php echo CalRoot;?>/</link>
		      <description><?php echo $hc_lang_rss['RSSMoreInfo'];?></description>
		    </item>
<?php 	}//end if	?>
  </channel>
</rss>