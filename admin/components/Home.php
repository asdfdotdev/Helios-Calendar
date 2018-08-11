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
	error_reporting(0);
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/home.php');
	
	appInstructions(0, "Helios", $hc_lang_home['Title'], $hc_lang_home['Instructions']);
	echo '<br />';
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curDate = date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2 AND StartDate >= '" . $curDate . "'");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(CalAdminRoot . '/index.php?com=eventpending','iconEdit.png',$hc_lang_home['PendingNotice'] . ' <b>' . $num . '</b>',0);
	
	$result = doQuery("SELECT COUNT(*)
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID)
					WHERE e.IsActive = 1 AND e.IsApproved = 1 AND (ec.EventID IS NULL OR c.IsActive = 0)");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_result($result,0,0) : 0;
	$hc_Side[] = array(CalAdminRoot . '/index.php?com=eventorphan','iconCategory.png',$hc_lang_home['OrphanNotice'] . ' <b>' . $num . '</b>',0);
	
	
	$result = doQuery("SELECT c.PkID
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers u ON (c.PosterID = u.PkID)
							LEFT JOIN " . HC_TblPrefix . "commentsreportlog crl ON (c.PkID = crl.CommentID)
						WHERE c.IsActive = 1 AND c.TypeID = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND crl.IsActive = 1 AND crl.PkID IS NOT NULL
						GROUP BY c.PkID");
	$num = (hasRows($result) && mysql_result($result,0,0) > 0) ? mysql_num_rows($result) : 0;
	$hc_Side[] = array(CalAdminRoot . '/index.php?com=cmntrep','iconCommentWarn.png',$hc_lang_home['ComReportNotice'] . ' <b>' . $num . '</b>',0);

	$ip = gethostbyname("www.helioscalendar.com");
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = "";
		$request = "GET /_update/feed.php HTTP/1.1\r\nHost: www.helioscalendar.com\r\nConnection: Close\r\n\r\n";
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		fclose($fp);
		
		$output = explode("/bof", $read);
		if(isset($output[1])){
			$message = explode("/eof", $output[1]);
			echo '<br /><fieldset>';
			echo '<legend>' . $hc_lang_home['Update'] . '&nbsp;&nbsp;(<a href="http://www.refreshmy.com/forum/forumdisplay.php?f=13" class="main" target="_blank">' . $hc_lang_home['MoreNews'] . '</a>)</legend>';
			echo '<div style="padding:10px;">' . $message[0] . '</div>';
			echo '</fieldset><br />';
		}//end if
	}//end if?>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_home['QuickStat'];?></legend>
		<div style="float:left;width:49%;">
		<table cellpadding="4" cellspacing="0" border="0" width="97%">
			<tr>
				<td class="statsHeader1" colspan="2"><b><?php echo $hc_lang_home['Events'];?></b></td>
			</tr>
			<tr>
				<td width="240" class="eventMain"><?php echo $hc_lang_home['Active'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $curDate . "'");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Billboard'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . $curDate . "'");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Today'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $curDate . "'");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Next7'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE StartDate BETWEEN '" . $curDate . "' AND AddDate('" . $curDate . "', INTERVAL 7 DAY)");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE en.NetworkType = 1 AND en.IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Eventbrite'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE en.NetworkType = 2 AND en.IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="statsHeader2" colspan="2"><b><?php echo $hc_lang_home['Locations'];?></b></td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Public'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 1");
					echo '<b>' . cOut(mysql_result($result,0,0)) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Admin'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 0");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(ln.LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE ln.IsActive = 1 AND NetworkType = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Eventbrite'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(ln.LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE ln.IsActive = 1 AND NetworkType = 2");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
		</table>
		</div>
		<div style="float:left;width:50%;">
		<table cellpadding="4" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="statsHeader1" colspan="2"><b><?php echo $hc_lang_home['Users'];?></b></td>
			</tr>
			<tr>
				<td width="240" class="eventMain"><?php echo $hc_lang_home['ActiveOID'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['BannedOID'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 2");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['LoggedToday'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE LastLogin > '" . $curDate . " 00:00:00' AND IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['LoggedWeek'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE LastLogin BETWEEN SUBDATE('" . $curDate . "', INTERVAL 7 DAY) AND '" . $curDate . " 23:59:59' AND IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Comments'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "comments WHERE IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['UserTweets'];?> (<a href="http://search.twitter.com/search?q=<?php echo urlencode($hc_cfg59);?>" class="main" target="_blank"><?php echo $hc_lang_home['ViewTweets'];?></a>)</td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT SUM(Tweetments) FROM " . HC_TblPrefix . "events WHERE IsActive = 1");
					echo (mysql_result($result,0,0) != '') ? '<b>' . mysql_result($result,0,0) . '</b>' : '<b>0</b>';?>
				</td>
			</tr>
			<tr>
				<td class="statsHeader2" colspan="2"><b><?php echo $hc_lang_home['Misc'];?></b></td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Subscribers'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "subscribers WHERE IsConfirm = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#F7F7F7"><?php echo $hc_lang_home['Submitted'];?></td>
				<td class="eventMain" bgcolor="#F7F7F7" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND SubmittedAt IS NOT NULL");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<?php
					$result = doQuery("SELECT COUNT(en.NetworkID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.StartDate >= '" . $curDate . "' AND en.NetworkType = 3 AND en.IsActive = 1 GROUP BY en.NetworkType");?>
				<td class="eventMain">
				<?php
					echo $hc_lang_home['AdminTweets'];
					echo ($hc_cfg63) ? ' (<a href="http://twitter.com/' . $hc_cfg63 . '" target="_blank" class="eventMain">' . $hc_lang_home['ViewTweetsA'] . '</a>)</td>' : '';
				?>
				<td class="eventMain" align="right">
				<?php
					echo (hasRows($result) && mysql_result($result,0,0) != '') ? '<b>' . mysql_result($result,0,0) . '</b>' : '<b>0</b>';?>
				</td>
			</tr>
		</table>
		</div>
	</fieldset>