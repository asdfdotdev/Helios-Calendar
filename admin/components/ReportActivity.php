<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$eID = 0;
	
	if(isset($_POST['eventID'])){
		$catID = $_POST['eventID'];
		foreach ($catID as $val){
			$eID = $eID . ", " . $val;
		}//end while
	} elseif(isset($_GET['eID'])){
		include('../../events/includes/globals.php');
		include('../../events/includes/code.php');
		include('../../events/includes/connection.php');
		$eID = urldecode($_GET['eID']);
		$print = true;
	} else {
		header("Location: LogOut.php");
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($eID) . ") ORDER BY StartDate");
	
	if(hasRows($result)){
		if(!isset($print)){	?>
			<div align="right"><img src="<?php echo CalAdminRoot;?>/images/icons/iconPrint.gif" width="15" height="15" alt="" border="0" align="middle" /> <a href="<?php echo CalAdminRoot;?>/components/ReportActivity.php?eID=<?php echo urlencode($eID);?>" class="main" target="_blank">Printer Friendly</a></div>
<?php 	} else {	?>
			<!DOCTYPE html
			PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
			<head>
				<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/admin.css" />
			</head>
			<body>
			<span class="main"><b><?php echo CalName;?> Event Report -- Powered by Helios <?php echo HC_Version;?></b></span>
			<br /><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0" /><br />
<?php	}//end if
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
			if($row[9] > date("Y-m-d")){
				$daysPublished = daysDiff(date("Y-m-d"), $row[27]);
			} else {
				$daysPublished = daysDiff($row[9], $row[27]);
			}//end if
			
			$eventDate = stampToDate($row[9], "l \\t\h\e jS \o\f F Y");
			$publishDate = stampToDate($row[27], "l \\t\h\e jS \o\f F Y");
			
			if(isset($print) && $cnt % 4 == 0 && $cnt > 0){	?>
				<p style="page-break-before: always;">&nbsp;</p>
				<span class="main"><b><?php echo CalName;?> Event Report -- Powered by Helios <?php echo HC_Version;?></b></span>
				<br /><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0" /><br />
	<?php 	}//end if	?>
			<table cellpadding="0" cellspacing="0" border="0" <?php if(!isset($print)){?>width="100%"<?php }else{?>width="550"<?php }//end if?>>
				<tr>
					<td class="eventMain" style="padding: 5px;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td colspan="2" class="eventMain">
							<?php	$meterSize = 330;
									if($maxViews > 0){
										$meterViews = round($meterSize * ($row[28] / $maxViews), '0');
									} else {
										$meterViews = 1;
									}//end if
									$meterViewsFill = $meterSize - $meterViews;
									
									if($maxMobileViews > 0){
										$meterMobileViews = round($meterSize * ($row[34] / $maxMobileViews), '0');
									} else {
										$meterMobileViews = 1;
									}//end if
									$meterMobileViewsFill = $meterSize - $meterMobileViews;
									
									if($maxDirections > 0){
										$meterDirections = round($meterSize * ($row[30] / $maxDirections), '0');
									} else {
										$meterDirections = 1;
									}//end if
										$meterDirectionsFill = $meterSize - $meterDirections;
									
									if($maxDownloads > 0){
										$meterDownloads = round($meterSize * ($row[31] / $maxDownloads), '0');
									} else {
										$meterDownloads = 1;
									}//end if
									$meterDownloadsFill = $meterSize - $meterDownloads;
									
									if($row[32] > 0){
										$meterEmail = round($meterSize * ($row[32] / $maxEmail), '0');
									} else {
										$meterEmail = 1;
									}//end if
									$meterEmailFill = $meterSize - $meterEmail;
									
									if($maxURL > 0){
										$meterURL = round($meterSize * ($row[33] / $maxURL), '0');
									} else {
										$meterURL = 1;
									}//end if
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
											<td class="eventMain" width="30" align="right"><b>Best</b></td>
											<td class="eventMain" width="50" align="right"><b>Daily</b></td>
										</tr>
										<tr><td colspan="4" bgcolor="#BDBDBD"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0" /></td></tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;Views (<?php echo cOut($row[28]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?php echo $meterViews;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterViewsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxViews;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain" width="125">&nbsp;Mobile Views (<?php echo cOut($row[34]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterBlue.gif" width="<?php echo $meterMobileViews;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterMobileViewsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxMobileViews;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveMobileViews, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Directions (<?php echo cOut($row[30]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterYellow.gif" width="<?php echo $meterDirections;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterDirectionsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxDirections;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveDirections, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Downloads (<?php echo cOut($row[31]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterPurple.gif" width="<?php echo $meterDownloads;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterDownloadsFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxDownloads;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveDownloads, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;Email to Friend (<?php echo cOut($row[32]);?>):</td>
											<td class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterPeach.gif" width="<?php echo $meterEmail;?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $meterEmailFill?>" height="10" alt="" border="0" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterEnds.gif" width="1" height="10" alt="" border="0" /></td>
											<td class="eventMain" align="right"><?php echo $maxEmail;?></td>
											<td class="eventMain" align="right"><?php echo number_format($aveEmail, 2, '.', '');?></td>
										</tr>
										<tr><td colspan="4"><img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" /></td></tr>
										<tr>
											<td class="eventMain">&nbsp;URL Clicks (<?php echo cOut($row[33]);?>):</td>
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
											<td class="eventMain" width="110"><b>Event Date:</b></td>
											<td class="eventMain"><?php echo $eventDate?></td>
										</tr>
										<tr>
											<td class="eventMain"><b>Published Date:</b></td>
											<td class="eventMain"><?php echo $publishDate;?></td>
										</tr>
										<tr>
											<td class="eventMain"><b>Days Published:</b></td>
											<td class="eventMain"><?php echo $daysPublished;?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
<?php 	if(!isset($print)){	?>
			<img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0" />
<?php 	} elseif(isset($print) && $cnt % 4 != 3 ) {	?>
			<img src="<?php echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0" />
<?php 	}//end if
			$cnt++;
		}//end while
		
		if(!isset($print)){	?>
			</body>
			</html>
<?php 	}//end if
	} else {	?>
		Invalid event(s). Please <a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch&sID=1" class="main">click here</a> to find an event.
<?php 
	}//end if	?>