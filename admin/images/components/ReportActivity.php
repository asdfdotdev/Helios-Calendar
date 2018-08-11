<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
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
		include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/reports.php');?>
		<div align="right"><img src="<?php echo CalAdminRoot;?>/images/icons/iconPrint.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /> <a href="<?php echo CalAdminRoot;?>/components/ReportActivity.php?print=1&amp;eID=<?php echo urlencode($eID);?>" class="main" target="_blank"><?php echo $hc_lang_reports['Print'];?></a></div>
<?php
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($eID) . ") ORDER BY StartDate");
	if(hasRows($result)){
		$resultX = doQuery("SELECT MAX(Views) as MaxViews, MAX(MViews) as MaxMobileViews, MAX(Directions), MAX(Downloads), MAX(EmailToFriend), MAX(URLClicks)
							FROM " . HC_TblPrefix . "events
							WHERE views > 0");
		$maxViews = cOut(mysql_result($resultX,0,0));
		$maxMobileViews = cOut(mysql_result($resultX,0,1));
		$maxDirections = cOut(mysql_result($resultX,0,2));
		$maxDownloads = cOut(mysql_result($resultX,0,3));
		$maxEmail = cOut(mysql_result($resultX,0,4));
		$maxURL = cOut(mysql_result($resultX,0,5));
		$cnt = 0;
		
		while($row = mysql_fetch_row($result)){
			$daysPublished = ($row[9] > date("Y-m-d")) ? daysDiff(date("Y-m-d"), $row[27]) : daysDiff($row[9], $row[27]);
			$eventDate = stampToDate($row[9], $hc_cfg14);
			$publishDate = stampToDate($row[27], $hc_cfg14);
			
			if(isset($print) && $cnt % 4 == 0){	
				if($cnt > 0){echo "<p style=\"page-break-before: always;\">&nbsp;</p>";}//end if?>
				<span class="main"><b><?php echo CalName . " " . $hc_lang_reports['EventReport'] . " -- " . $hc_lang_reports['PoweredBy'] . " Helios Calendar " . $hc_cfg49;?></b></span>
				<br /><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0" /><br />
	<?php 	}//end if	?>
			<table cellpadding="0" cellspacing="0" border="0" <?php if(!isset($print)){?>width="100%"<?php }else{?>width="550"<?php }//end if?>>
				<tr>
					<td class="eventMain" style="padding: 5px;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td colspan="2" class="eventMain">
							<?php	$meterSize = 330;
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
									$meterURL = ($maxMobileViews > 0) ? round($meterSize * ($row[33] / $maxURL), '0') : 1;
									$meterURLFill = $meterSize - $meterURL;
									$aveViews = round($row[28] / $daysPublished, 2);
									$aveMobileViews = round($row[34] / $daysPublished, 2);
									$aveDirections = round($row[30] / $daysPublished, 2);
									$aveDownloads = round($row[31] / $daysPublished, 2);
									$aveEmail = round($row[32] / $daysPublished, 2);
									$aveURL = round($row[33] / $daysPublished, 2);	?>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td class="eventMain" colspan="2"><b><?php echo $row[1];?></b></td>
											<td class="eventMain" width="30" align="right"><b><?php echo $hc_lang_reports['Best'];?></b></td>
											<td class="eventMain" width="50" align="right"><b><?php echo $hc_lang_reports['Daily'];?></b></td>
										</tr>
										<tr><td colspan="4" bgcolor="#BDBDBD"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0" /></td></tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;<?php echo $hc_lang_reports['Views'];?> (<?php echo cOut($row[28]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?php echo $meterViews;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterViewsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxViews;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;<?php echo $hc_lang_reports['MViews'];?> (<?php echo cOut($row[34]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterBlue.gif" width="<?php echo $meterMobileViews;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterMobileViewsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxMobileViews;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveMobileViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;<?php echo $hc_lang_reports['Directions'];?> (<?php echo cOut($row[30]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterYellow.gif" width="<?php echo $meterDirections;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterDirectionsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxDirections;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveDirections, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;<?php echo $hc_lang_reports['Downloads'];?> (<?php echo cOut($row[31]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterPurple.gif" width="<?php echo $meterDownloads;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterDownloadsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxDownloads;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveDownloads, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;<?php echo $hc_lang_reports['EmailToFriend'];?> (<?php echo cOut($row[32]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterPeach.gif" width="<?php echo $meterEmail;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterEmailFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxEmail;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveEmail, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;<?php echo $hc_lang_reports['URLClicks'];?> (<?php echo cOut($row[33]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterDGray.gif" width="<?php echo $meterURL;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterURLFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxURL;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveURL, 2, '.', '');?></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="25">&nbsp;</td>
								<td class="eventMain">
									<img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0" /><br />
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="eventMain" width="110"><b><?php echo $hc_lang_reports['EventDate'];?></b></td>
											<td class="eventMain"><?php echo $eventDate?></td>
										</tr>
										<tr>
											<td class="eventMain"><b><?php echo $hc_lang_reports['PublishDate'];?></b></td>
											<td class="eventMain"><?php echo $publishDate;?></td>
										</tr>
										<tr>
											<td class="eventMain"><b><?php echo $hc_lang_reports['DaysPublished'];?></b></td>
											<td class="eventMain"><?php echo $daysPublished;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
	<?php 	if(!isset($print) || (isset($print) && $cnt % 4 != 3)){
				echo "<img src=\"" . CalAdminRoot . "/images/spacer.gif\" width=\"1\" height=\"4\" alt=\"\" border=\"0\" />";
			}//end if
			$cnt++;
		}//end while
		
		if(isset($print)){
			echo "</body></html>";
		}//end if
	} else {
		echo $hc_lang_reports['InvalidEvent'] . " " . "<a href=\"" . CalAdminRoot . "/index.php?com=eventsearch\" class=\"main\">" . $hc_lang_reports['ClickEvent'] . "</a>";
	}//end if?>