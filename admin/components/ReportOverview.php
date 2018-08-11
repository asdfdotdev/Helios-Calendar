<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(isset($_GET['print'])){
		$isAction = 1;
		include('../includes/include.php');		
		$isAction = 1;
		checkIt(1);
		
		include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
		include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/reports.php');
		
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
			<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/css/admin.css" />
			<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	</head>
	<body>
	<div style="width:550px;">
	<div style="float:left;width:350px;">
		<span style="font-family:verdana;font-size:15px;font-weight:bold;"><?php echo CalName . " " . $hc_lang_reports['Overview'];?></span><br />
		<b><?php echo $hc_lang_reports['CreatedAt'] . "</b> " . date("Y-m-d g:i:s A");?><br />
		<b><?php echo $hc_lang_reports['Calendar'] . "</b> " . CalRoot;?>/<br />
		<b><?php echo $hc_lang_reports['GeneratedBy'] . "</b> Helios Calendar " . $hc_cfg49;?>
	</div>
	<div style="float:left;width:200px;text-align:right;vertical-align:top;">
		<br />www.HeliosCalendar.com
	</div>
	<br /><br />
<?php
	} else {
		if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}

		include($hc_langPath . $_SESSION['LangSet'] . '/admin/reports.php');?>
		<div class="reportPrint"><img src="<?php echo CalAdminRoot;?>/images/icons/iconPrint.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> <a href="<?php echo CalAdminRoot;?>/components/ReportOverview.php?print=1" class="main" target="_blank"><?php echo $hc_lang_reports['Print'];?></a></div>
<?php
	}//end if	?>
		<div class="overviewDetails"><b><?php echo $hc_lang_reports['Detail'];?></b></div><div class="overviewCount"><b><?php echo $hc_lang_reports['Stats'];?></b></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Total'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1");
			if(hasRows($result)){
				$totalEvents = mysql_result($result,0,0);
				echo $totalEvents;
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Active'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "'");
			if(hasRows($result)){
				$activeEvents = mysql_result($result,0,0);
				echo $activeEvents;
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Passed'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < '" . date("Y-m-d") . "'");
			if(hasRows($result)){
				$pastEvents = mysql_result($result,0,0);
				echo $pastEvents;
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Deleted'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 0");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Billboard'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . date("Y-m-d") . "'");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Orphan'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("	SELECT " . HC_TblPrefix . "events.*
								FROM " . HC_TblPrefix . "events
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID)
								WHERE 
									" . HC_TblPrefix . "events.IsActive = 1 AND
									" . HC_TblPrefix . "events.IsApproved = 1 AND
									" . HC_TblPrefix . "events.StartDate >= '" . date("Y-m-d") . "' AND
									(" . HC_TblPrefix . "eventcategories.EventID IS NULL OR
									" . HC_TblPrefix . "categories.IsActive = 0)
								ORDER BY StartDate");
			echo mysql_num_rows($result);?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Today'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . date("Y-m-d") . "'");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['NextSun'];?></div>
		<div class="overviewCountHL">
	<?php 	$now = date("Y-m-d");
			
			$addDays = 0;
			if(date("w") != 6){
				$addDays = 6 - date("w");
			}//end if
			$then = date("Y-m-d", mktime(0,0,0,date("m"),date("d") + $addDays,date("Y")));
			
			$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate BETWEEN '" . $now . "' AND '" . $then . "'");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['EndMonth'];?></div>
		<div class="overviewCount">
	<?php 	$now = date("Y-m-d");
			$then = date("Y-m-d", mktime(0,0,0,date("m") + 1,0,date("Y")));
			
			$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate BETWEEN '" . $now . "' AND '" . $then . "'");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['ActiveUsers'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['TotalUsers'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers");
			if(hasRows($result)){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Earliest'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("SELECT MIN(StartDate) FROM " . HC_TblPrefix . "events WHERE IsActive = 1");
			if(hasRows($result) && mysql_result($result,0,0) != ''){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Latest'];?></div>
		<div class="overviewCount">
	<?php 	$result = doQuery("SELECT MAX(StartDate) FROM " . HC_TblPrefix . "events WHERE IsActive = 1");
			if(hasRows($result) && mysql_result($result,0,0) != ''){
				echo mysql_result($result,0,0);
			} else {
				echo "N/A";
			}//end if?>
		</div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['AvePerCat'];?></div>
		<div class="overviewCountHL">
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "categories WHERE IsActive = 1");
			if(hasRows($result)){
				$activeCategories = mysql_result($result,0,0);
				echo number_format($activeEvents / $activeCategories, 2, '.', ',');
			} else {
				echo "N/A";
			}//end if?>
		</div>
<?php 	$result = doQuery("	SELECT SUM(Views) as Views,
								SUM(Directions) as Directions,
								SUM(Downloads) as Downloads,
								SUM(EmailToFriend) as EmailToFriend,
								SUM(URLClicks) as URLClicks,
								SUM(MViews) as MViews
							FROM " . HC_TblPrefix . "events
							WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "'");
		$aViews = mysql_result($result,0,0);
		$aDirections = mysql_result($result,0,1);
		$aDownloads = mysql_result($result,0,2);
		$aEmail = mysql_result($result,0,3);
		$aURL = mysql_result($result,0,4);
		$aMViews = mysql_result($result,0,5);	?>
		<div class="overviewDetails" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['Active'];?></b></div>
		<div class="overviewAverage" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Average'];?></b></div>
		<div class="overviewTotal" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['Total'];?></b></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Views'];?></div>
		<div class="overviewAverage"><?php echo $aViews !=0 ? number_format($aViews / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $aViews != 0 ? $aViews : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['MViews'];?></div>
		<div class="overviewAverageHL"><?php echo $aMViews != 0 ? number_format($aMViews / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $aMViews != 0 ? $aMViews : "0";?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['DriveDir'];?></div>
		<div class="overviewAverage"><?php echo $aDirections !=0 ? number_format($aDirections / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $aDirections != 0 ? $aDirections : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Downloads'];?></div>
		<div class="overviewAverageHL"><?php echo $aDownloads != 0 ? number_format($aDownloads / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $aDownloads != 0 ? $aDownloads : "0" ;?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['EmailToFriend'];?></div>
		<div class="overviewAverage"><?php echo $aEmail !=0 ? number_format($aEmail / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $aEmail != 0 ? $aEmail : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['URLClicks'];?></div>
		<div class="overviewAverageHL"><?php echo $aURL != 0 ? number_format($aURL / $activeEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $aURL != 0 ? $aURL : "0";?></div>
<?php 	$result = doQuery("	SELECT SUM(Views) as Views,
								SUM(Directions) as Directions,
								SUM(Downloads) as Downloads,
								SUM(EmailToFriend) as EmailToFriend,
								SUM(URLClicks) as URLClicks,
								SUM(MViews) as MViews
							FROM " . HC_TblPrefix . "events
							WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < '" . date("Y-m-d") . "'");
		$pViews = mysql_result($result,0,0);
		$pDirections = mysql_result($result,0,1);
		$pDownloads = mysql_result($result,0,2);
		$pEmail = mysql_result($result,0,3);
		$pURL = mysql_result($result,0,4);
		$pMViews = mysql_result($result,0,5);	?>
		<div class="overviewDetails" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['Passed'];?></b></div>
		<div class="overviewAverage" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Average'];?></b></div>
		<div class="overviewTotal" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['Total'];?></b></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Views'];?></div>
		<div class="overviewAverage"><?php echo $pViews !=0 ? number_format($pViews / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $pViews != 0 ? $pViews : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['MViews'];?></div>
		<div class="overviewAverageHL"><?php echo $pMViews != 0 ? number_format($pMViews / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $pMViews != 0 ? $pMViews : "0";?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['DriveDir'];?></div>
		<div class="overviewAverage"><?php echo $pDirections !=0 ? number_format($pDirections / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $pDirections != 0 ? $pDirections : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Downloads'];?></div>
		<div class="overviewAverageHL"><?php echo $pDownloads != 0 ? number_format($pDownloads / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $pDownloads != 0 ? $pDownloads : "0" ;?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['EmailToFriend'];?></div>
		<div class="overviewAverage"><?php echo $pEmail !=0 ? number_format($pEmail / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $pEmail != 0 ? $pEmail : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['URLClicks'];?></div>
		<div class="overviewAverageHL"><?php echo $pURL != 0 ? number_format($pURL / $pastEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $pURL != 0 ? $pURL : "0";?></div>
<?php 	$result = doQuery("	SELECT SUM(Views) as Views,
								SUM(Directions) as Directions,
								SUM(Downloads) as Downloads,
								SUM(EmailToFriend) as EmailToFriend,
								SUM(URLClicks) as URLClicks,
								SUM(MViews) as MViews
							FROM " . HC_TblPrefix . "events");
		$tViews = mysql_result($result,0,0);
		$tDirections = mysql_result($result,0,1);
		$tDownloads = mysql_result($result,0,2);
		$tEmail = mysql_result($result,0,3);
		$tURL = mysql_result($result,0,4);
		$tMViews = mysql_result($result,0,5);	?>
		<div class="overviewDetails" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['AllEvents'];?></b></div>
		<div class="overviewAverage" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Average'];?></b></div>
		<div class="overviewTotal" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b>&nbsp;<br /><?php echo $hc_lang_reports['Total'];?></b></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['Views'];?></div>
		<div class="overviewAverage"><?php echo $tViews !=0 ? number_format($tViews / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $tViews != 0 ? $tViews : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['MViews'];?></div>
		<div class="overviewAverageHL"><?php echo $tMViews != 0 ? number_format($tMViews / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $tMViews != 0 ? $tMViews : "0";?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['DriveDir'];?></div>
		<div class="overviewAverage"><?php echo $tDirections !=0 ? number_format($tDirections / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $tDirections != 0 ? $tDirections : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['Downloads'];?></div>
		<div class="overviewAverageHL"><?php echo $tDownloads != 0 ? number_format($tDownloads / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $tDownloads != 0 ? $tDownloads : "0" ;?></div>
		<div class="overviewDetails"><?php echo $hc_lang_reports['EmailToFriend'];?></div>
		<div class="overviewAverage"><?php echo $tEmail !=0 ? number_format($tEmail / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotal"><?php echo $tEmail != 0 ? $tEmail : "0";?></div>
		<div class="overviewDetailsHL"><?php echo $hc_lang_reports['URLClicks'];?></div>
		<div class="overviewAverageHL"><?php echo $tURL != 0 ? number_format($tURL / $totalEvents, 2, '.', ',') : "0";?></div>
		<div class="overviewTotalHL"><?php echo $tURL != 0 ? $tURL : "0";?></div>

<?php 	if(isset($_GET['print'])){	?>
			<div style="clear:both;width:650px;padding: 10px 0px 0px 0px;">
				<span class="main"><b><?php echo $hc_lang_reports['Generated'];?> Helios Calendar <?php echo $hc_cfg49;?> -- www.HeliosCalendar.com</b></span>
				<p style="page-break-before: always;"><span class="main"><b><?php echo CalName;?> Calendar Overview -- Page 2/4</b></span></p>
			</div>
<?php 	} else {	?>
			<br /><br />
<?php 	}//end if	
		
		$result = doQuery("SELECT Title, StartDate, Views FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND Views > 0 ORDER BY Views DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostViewed'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostViewed'] . "</b>";?></div>
<?php 	}//end if
			
		$result = doQuery("SELECT Title, StartDate, MViews FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND MViews > 0 ORDER BY MViews DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostMViewed'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostMViewed'] . "</b>";?></div>
<?php 	}//end if
		
		$result = doQuery("SELECT Title, StartDate, Directions FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND Directions > 0 ORDER BY Directions DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostDirections'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostDirections'] . "</b>";?></div>
<?php 	}//end if
		
		if(isset($_GET['print'])){	?>
			<div style="clear:both;width:650px;padding: 10px 0px 0px 0px;">
				<span class="main"><b><?php echo $hc_lang_reports['Generated'];?> Helios Calendar <?php echo $hc_cfg49;?> -- www.HeliosCalendar.com</b></span>
				<p style="page-break-before: always;"><span class="main"><b><?php echo CalName;?> Calendar Overview -- Page 3/4</b></span></p>
			</div>
<?php 	}//end if	
		
		$result = doQuery("SELECT Title, StartDate, Downloads FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND Downloads > 0 ORDER BY Downloads DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostDownloads'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostDownloads'] . "</b>";?></div>
<?php 	}//end if
		
		$result = doQuery("SELECT Title, StartDate, EmailToFriend FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND EmailToFriend > 0 ORDER BY EmailToFriend DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostEmail'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostEmail'] . "</b>";?></div>
<?php 	}//end if
		
		$result = doQuery("SELECT Title, StartDate, URLClicks FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND URLClicks > 0 ORDER BY URLClicks DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS" style="padding-top:10px;"><b><?php echo $hc_lang_reports['MostURL'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Date'];?></b></div>
			<div class="overviewTotal" style="padding-top:10px;"><b><?php echo $hc_lang_reports['Count'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
				<div class="overviewTotal<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['MostURL'] . "</b>";?></div>
<?php 	}//end if
		
		if(isset($_GET['print'])){	?>
			<div style="clear:both;width:650px;padding: 10px 0px 0px 0px;">
				<span class="main"><b><?php echo $hc_lang_reports['Generated'];?> Helios Calendar <?php echo $hc_cfg49;?> -- www.HeliosCalendar.com</b></span>
				<p style="page-break-before: always;"><span class="main"><b><?php echo CalName;?> Calendar Overview -- Page 4/4</b></span></p>
			</div>
<?php 	}//end if	
		
		$result = doQuery("SELECT Title, StartDate, PublishDate FROM " . HC_TblPrefix . "events WHERE StartDate >= '" . date("Y-m-d") . "' AND IsApproved = 1 AND IsActive = 1 ORDER BY PublishDate DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS2" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['RecentEvent'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['EventDate'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Published'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS2<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[1], $hc_cfg24);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[2], $hc_cfg24);?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['RecentEvent'] . "</b>";?></div>
<?php 	}//end if
		
		$result = doQuery("SELECT Title, StartDate, PublishDate FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND SubmittedByName IS NOT NULL ORDER BY PublishDate DESC, Title LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewDetailS2" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['RecentSubmit'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['EventDate'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Published'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewDetailS2<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[1], $hc_cfg24);?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[2], $hc_cfg24);?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['RecentSubmit'] . "</b>";?></div>
<?php 	}//end if
		
		$result = doQuery("SELECT FirstName, LastName, Email, RegisteredAt FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1 ORDER BY RegisteredAt DESC, LastName, FirstName LIMIT 10");
		if(hasRows($result)){	?>
			<div class="overviewName" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['NewestUsers'];?></b></div>
			<div class="overviewEmail" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Email'];?></b></div>
			<div class="overviewDate" style="padding-top:10px;line-height:11px;vertical-align:bottom;"><b><?php echo $hc_lang_reports['Registered'];?></b></div>
<?php 		$cnt = 0;
			while($row = mysql_fetch_row($result)){	?>
				<div class="overviewName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo cOut($row[0]) . " " . $row[1];?></div>
				<div class="overviewEmail<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[2];?></div>
				<div class="overviewDate<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo stampToDate($row[3], $hc_cfg24);?></div>
<?php 		++$cnt;
			}//end while
		} else {	?>
			<div style="clear:both;padding: 20px 0px 0px 0px;"><?php echo $hc_lang_reports['NoEvents'] . "<b> " . $hc_lang_reports['NewestUsers'] . "</b>";?></div>
<?php 	}//end if
		
		if(isset($_GET['print'])){	?>
			<div style="clear:both;width:650px;padding: 10px 0px 0px 0px;">
				<span class="main"><b><?php echo $hc_lang_reports['Generated'];?> Helios Calendar <?php echo $hc_cfg49;?> -- www.HeliosCalendar.com</b></span>
			</div>
<?php 	}//end if	
	
	if(isset($_GET['print'])){	?>
		</div>
		</body>
		</html>
<?php
	}//end if	?>