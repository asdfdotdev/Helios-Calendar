<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('includes/include.php');
	hookDB();
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (2,14)");
	$maxShow = mysql_result($result,0,0);
	$dateFormat = mysql_result($result,1,0);
	
	$dateRange = HC_TblPrefix . "events.StartDate >= NOW()";
	if(isset($_GET['d']) && is_numeric($_GET['d'])){
		if($_GET['d'] == 1){
			$dateRange = HC_TblPrefix . "events.StartDate = '" . date("Y-m-d") . "'";
		} else if($_GET['d'] == 2){
			$dateRange = HC_TblPrefix . "events.StartDate Between NOW() AND '" . date("Y-m-d", mktime(0,0,0,date("m"),date("d")+7,date("Y"))) . "'";
		} else if($_GET['d'] == 3) {
			$dateRange = HC_TblPrefix . "events.StartDate Between NOW() AND '" . date("Y-m-d", mktime(0,0,0,date("m")+1,date("d"),date("Y"))) . "'";
		}//end if
	}//end if
	
	header ('Content-Type:text/xml; charset=utf-8');
	echo "<?xml-stylesheet type=\"text/css\" href=\"" . CalRoot . "/css/rss.css\"?>";	
	$query = "	SELECT distinct " . HC_TblPrefix . "events.*
				FROM " . HC_TblPrefix . "events
					LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
				WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
					" . HC_TblPrefix . "events.IsApproved = 1 AND 
					" . $dateRange . " AND
					" . HC_TblPrefix . "categories.IsActive = 1
				ORDER BY StartDate, TBD, Title
				LIMIT " . $maxShow;
	$feedName = "- All Events";
	
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$query = "	SELECT distinct " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
					WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
						" . HC_TblPrefix . "events.IsApproved = 1 AND 
						" . $dateRange . " AND
						" . HC_TblPrefix . "categories.IsActive = 1 AND
						" . HC_TblPrefix . "categories.PkID IN ('" . cIn($_GET['cID']) . "')
					ORDER BY StartDate, TBD, Title
					LIMIT " . $maxShow;
		$feedName = "- Custom Feed";
	} else {
		if(isset($_GET['s']) && is_numeric($_GET['s'])){
			if($_GET['s'] == 1){
				//	Newest Events
				$query = "	SELECT distinct " . HC_TblPrefix . "events.*
							FROM " . HC_TblPrefix . "events
								LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
							WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
								" . HC_TblPrefix . "events.IsApproved = 1 AND 
								" . $dateRange . " AND
								" . HC_TblPrefix . "categories.IsActive = 1
							ORDER BY PublishDate DESC, StartDate
							LIMIT " . $maxShow;
				$feedName = "- Newest Events";
				
			} elseif($_GET['s'] == 2){
				// Most Popular Events
				$query = "	SELECT distinct " . HC_TblPrefix . "events.*
							FROM " . HC_TblPrefix . "events
								LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
							WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
								" . HC_TblPrefix . "events.IsApproved = 1 AND 
								" . $dateRange . " AND
								" . HC_TblPrefix . "categories.IsActive = 1
							ORDER BY Views DESC, StartDate
							LIMIT " . $maxShow;
				$feedName = "- Most Popular Events";
				
			} elseif($_GET['s'] == 3){
				// Billboard Events
				$query = "	SELECT distinct " . HC_TblPrefix . "events.*
							FROM " . HC_TblPrefix . "events
								LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
								LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "eventcategories.CategoryID = " . HC_TblPrefix . "categories.PkID)
							WHERE " . HC_TblPrefix . "events.IsActive = 1 AND 
								" . HC_TblPrefix . "events.IsApproved = 1 AND 
								" . HC_TblPrefix . "events.StartDate >= NOW() AND
								" . HC_TblPrefix . "categories.IsActive = 1 AND
								" . HC_TblPrefix . "events.IsBillboard = 1
							ORDER BY StartDate, TBD, Title
							LIMIT " . $maxShow;
				$feedName = "- Featured Events";
			}//end if
		}//end if
	}//end if	?>
	
<!-- Generated by Helios <?echo HC_Version;?> <?echo date("\\o\\n Y-m-d \\a\\t H:i:s");?> <?echo "\n";?> http://www.HeliosCalendar.com -->
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title><?echo CalName . " " . $feedName;?></title>
    <link><?echo CalRoot;?>/</link>
    <language>en-us</language>
    <copyright>Copyright 2004-<?echo date("Y");?> Refresh Web Development LLC</copyright>
	<generator>http://www.HeliosCalendar.com</generator>
	<docs><?echo CalRoot;?>&#47;index.php&#63;com=rss</docs>
	<description>Upcoming Event Information From The <?echo CalName;?></description>
<?php	$result = doQuery($query);
		
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
			?>
				<item>
			      <title><?echo stampToDate(cOut($row[9]), $dateFormat) . " - " . strip_tags(htmlspecialchars(cOut(str_replace("?", "&#63;", $row[1]))));?></title>
			      <link><?echo CalRoot . "/index.php&#63;com=detail&amp;eID=" . $row[0];?></link>
			      <description><?echo strip_tags(htmlspecialchars(cOut(str_replace("?", "&#63;", $row[8]))));?></description>
				  <guid><?echo CalRoot . "/index.php&#63;com=detail&amp;eID=" . $row[0];?></guid>
			    </item>
			<?
			}//end while
		} else {
		?>
			<item>
		      <title>There Are No Upcoming Events Available For This Feed</title>
		      <link><?echo CalRoot;?>/</link>
		      <description>Visit the <?echo CalName;?> for more information.</description>
		    </item>
		<?
		}//end if
	?>
  </channel>
</rss>