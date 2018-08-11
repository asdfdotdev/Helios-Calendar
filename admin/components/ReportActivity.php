<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$eID = 0;
	if(isset($_POST['eventID'])){
		$catID = $_POST['eventID'];
		foreach ($catID as $val){
			$eID = $eID . ", " . $val;
		}//end while
	} elseif(isset($_GET['eID']) && $_GET['eID'] != ''){
		$eID = urldecode($_GET['eID']);
		$print = true;
	}//end if

	if(isset($_GET['print'])){
		$isAction = 1;
		include('../includes/include.php');	
		checkIt(1);
		include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
		include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/reports.php');
		
		setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);?>
		<!DOCTYPE html
		PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<meta name="robots" content="noindex, nofollow" />
			<meta http-equiv="author" content="Refresh Web Development LLC" />
			<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> Refresh Web Development All Rights Reserved" />
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
			<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
			<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
		</head><body>
<?php
	} else {
		include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/reports.php');
		echo '<div class="reportPrint"><img src="' . CalAdminRoot . '/images/icons/iconPrint.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> <a href="' . CalAdminRoot . '/components/ReportActivity.php?print=1&amp;eID=' . urlencode($eID) . '" class="main" target="_blank">' . $hc_lang_reports['Print'] . '</a></div>';
	}//end if
	
	$result = doQuery("SELECT e.*, COUNT(c.EntityID) AS Comments
                         FROM " . HC_TblPrefix . "events e
                              LEFT JOIN " . HC_TblPrefix . "comments c ON (e.PkID = c.EntityID AND c.IsActive = 1)
                         WHERE e.PkID IN(" . cIn($eID) . ")
                         GROUP BY e.PkID
                         ORDER BY e.StartDate");
	if(hasRows($result)){
		$maxViews = $maxMobileViews = $maxDirections = $maxDownloads = $maxEmail = $maxURL = $maxTweets = $cnt = 0;
		$resultX = doQuery("SELECT MAX(Views) as MaxViews,MAX(MViews) as MaxMobileViews, MAX(Directions),
                                   MAX(Downloads), MAX(EmailToFriend), MAX(URLClicks), MAX(Tweetments)
                              FROM " . HC_TblPrefix . "events
                              WHERE IsActive = 1");
		if(hasRows($resultX)){
			$maxViews = cOut(mysql_result($resultX,0,0));
			$maxMobileViews = cOut(mysql_result($resultX,0,1));
			$maxDirections = cOut(mysql_result($resultX,0,2));
			$maxDownloads = cOut(mysql_result($resultX,0,3));
			$maxEmail = cOut(mysql_result($resultX,0,4));
			$maxURL = cOut(mysql_result($resultX,0,5));
			$maxTweets = cOut(mysql_result($resultX,0,6));
		}//end if
		$resultC = doQuery("SELECT COUNT(EntityID) as NUM
                              FROM " . HC_TblPrefix . "comments
                              WHERE IsActive = 1
                              GROUP BY EntityID
                              ORDER BY Num DESC
                              LIMIT 1");
          $maxComment = hasRows($resultC) ? cOut(mysql_result($resultC,0,0)) : 0;
		
          $curDate = date("Y-m-d");
		while($row = mysql_fetch_row($result)){
			$daysPublished = ($row[9] > $curDate) ? daysDiff($curDate, $row[27]) : daysDiff($row[9], $row[27]);
			$eventDate = stampToDate($row[9], $hc_cfg14);
			$publishDate = stampToDate($row[27], $hc_cfg14);
			
			if(isset($print) && $cnt % 4 == 0){	
				echo ($cnt > 0) ? '<p style="page-break-before: always;">&nbsp;</p>' : '';
				echo '<span class="main"><b>' . CalName . ' ' . $hc_lang_reports['EventReport'] . ' -- ' . $hc_lang_reports['PoweredBy'] . ' Helios Calendar ' . $hc_cfg49 . '</b></span>';
				echo '<br /><img src="' . CalAdminRoot . '/images/spacer.gif" width="1" height="7" alt="" border="0" /><br />';
               }//end if
			
               echo (!isset($print)) ? '<div style="width:100%;padding:5px;">' : '<div style="width:675px;padding:5px;">';
			echo '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td colspan="2" class="eventMain">';

               $meterSize = 395;
               $meterViews = ($maxViews > 0) ? round($meterSize * ($row[28] / $maxViews), '0') : 1;
               $meterViewsFill = $meterSize - $meterViews;
               $meterMobileViews = ($maxMobileViews > 0) ? round($meterSize * ($row[34] / $maxMobileViews), '0') : 1;
               $meterMobileViewsFill = $meterSize - $meterMobileViews;
               $meterDirections = ($maxDirections > 0) ? round($meterSize * ($row[30] / $maxDirections), '0') : 1;
               $meterDirectionsFill = $meterSize - $meterDirections;
               $meterDownloads = ($maxDownloads > 0) ? round($meterSize * ($row[31] / $maxDownloads), '0') : 1;
               $meterDownloadsFill = $meterSize - $meterDownloads;
               $meterEmail = ($row[32] > 0) ? round($meterSize * ($row[32] / $maxEmail), '0') : 1;
               $meterEmailFill = $meterSize - $meterEmail;
               $meterURL = ($maxURL > 0) ? round($meterSize * ($row[33] / $maxURL), '0') : 1;
               $meterURLFill = $meterSize - $meterURL;
               $meterTweets = ($maxTweets > 0) ? round($meterSize * ($row[39] / $maxTweets), '0') : 1;
               $meterTweetsFill = $meterSize - $meterTweets;
               $meterComment = ($maxComment > 0) ? round($meterSize * ($row[40] / $maxComment), '0') : 1;
               $meterCommentFill = $meterSize - $meterComment;

               $aveViews = round($row[28] / $daysPublished, 2);
               $aveMobileViews = round($row[34] / $daysPublished, 2);
               $aveDirections = round($row[30] / $daysPublished, 2);
               $aveDownloads = round($row[31] / $daysPublished, 2);
               $aveEmail = round($row[32] / $daysPublished, 2);
               $aveURL = round($row[33] / $daysPublished, 2);
               $aveComment = round($row[39] / $daysPublished, 2);
               $aveTweets = round($row[40] / $daysPublished, 2);
               $aveTweet = round($row[39] / $daysPublished, 2);
               $aveComment = round($row[40] / $daysPublished, 2);
               
               echo '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>';
               echo '<td class="eventMain" colspan="2"><b>' . $row[1] . '</b></td>';
               echo '<td class="eventMain" width="65" align="right"><b>' . $hc_lang_reports['Best'] . '</b></td>';
               echo '<td class="eventMain" width="65" align="right"><b>' . $hc_lang_reports['Daily'] . '</b></td>';
               echo '</tr><tr>';
               echo '<td colspan="4" bgcolor="#BDBDBD"><img src="' . CalAdminRoot . '/images/spacer.gif" width="1" height="2" alt="" border="0" /></td></tr>';

               buildMeter('Green',$hc_lang_reports['Views'],$row[28],$meterViews,$meterViewsFill,$maxViews,$aveViews);
               buildMeter('Blue',$hc_lang_reports['MViews'],$row[34],$meterMobileViews,$meterMobileViewsFill,$maxMobileViews,$aveMobileViews);
               buildMeter('Yellow',$hc_lang_reports['Directions'],$row[30],$meterDirections,$meterDirectionsFill,$maxDirections,$aveDirections);
               buildMeter('Purple',$hc_lang_reports['Downloads'],$row[31],$meterDownloads,$meterDownloadsFill,$maxDownloads,$aveDownloads);
               buildMeter('Peach',$hc_lang_reports['EmailToFriend'],$row[32],$meterEmail,$meterEmailFill,$maxEmail,$aveEmail);
               buildMeter('DGray',$hc_lang_reports['URLClicks'],$row[33],$meterURL,$meterURLFill,$maxURL,$aveURL);
               buildMeter('Brown',$hc_lang_reports['Comments'],$row[40],$meterComment,$meterCommentFill,$maxComment,$aveComment);
               buildMeter('Orange',$hc_lang_reports['Tweetments'],$row[39],$meterTweets,$meterTweetsFill,$maxTweets,$aveTweets);
               
               echo '</table>';
               echo '</td></tr><tr>';
               echo '<td width="25">&nbsp;</td>';
               echo '<td class="eventMain">';
               echo '<img src="' . CalAdminRoot . '/images/spacer.gif" width="1" height="5" alt="" border="0" /><br />';
               echo '<table cellpadding="0" cellspacing="0" border="0"><tr>';
               echo '<td class="eventMain" width="110"><b>' . $hc_lang_reports['EventDate'] . '</b></td>';
               echo '<td class="eventMain">' . $eventDate . '</td>';
               echo '</tr><tr>';
               echo '<td class="eventMain"><b>' . $hc_lang_reports['PublishDate'] . '</b></td>';
               echo '<td class="eventMain">' . $publishDate . '</td>';
               echo '</tr><tr>';
               echo '<td class="eventMain"><b>' . $hc_lang_reports['DaysPublished'] . '</b></td>';
               echo '<td class="eventMain">' . $daysPublished . '</td>';
               echo '</tr></table>';
               echo '</td></tr></table>';
			echo '</div>';
               
               echo (!isset($print) || (isset($print) && $cnt % 4 != 3)) ? '<img src="' . CalAdminRoot . '/images/spacer.gif" width="1" height="4" alt="" border="0" />' : '';
			
			++$cnt;
		}//end while
		
		echo (isset($print)) ? '</body></html>' : '';
	} else {
		echo $hc_lang_reports['InvalidEvent'] . " " . "<a href=\"" . CalAdminRoot . "/index.php?com=eventsearch\" class=\"main\">" . $hc_lang_reports['ClickEvent'] . "</a>";
	}//end if


     function buildMeter($color,$label,$data,$meter,$meterFill,$max,$ave){
          echo '<tr><td colspan="4"><img src="' . CalAdminRoot . '/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>';
          echo '<tr>';
          echo '<td class="eventMain" width="130">&nbsp;' . $label . ' (' . cOut($data) . '):</td>';
          echo '<td class="eventMain"><img src="' . CalAdminRoot . '/images/meter/meter' . $color . '.png" width="' . $meter . '" height="10" alt="" border="0" />';
          echo '<img src="' . CalAdminRoot . '/images/meter/meterLGray.png" width="' . $meterFill . '" height="10" alt="" border="0" /></td>';
          echo '<td class="eventMain" align="right">' . $max . '</td>';
          echo '<td class="eventMain" align="right">' . number_format($ave, 2, '.', '') . '</td>';
          echo '</tr>';
     }//end buildMeter
     ?>