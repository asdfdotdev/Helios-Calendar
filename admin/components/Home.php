<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/home.php');
	
	appInstructions(0, "Helios", $hc_lang_home['Title'], $hc_lang_home['Instructions']);
	echo '<br />';
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curDate = date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2 AND StartDate >= '" . $curDate . "'");
	if(hasRows($result) && mysql_result($result,0,0) > 0){
		feedback(1, $hc_lang_home['PendingNotice']);
	}//end if
	
	$result = doQuery("	SELECT " . HC_TblPrefix . "events.*
						FROM " . HC_TblPrefix . "events
							LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
							LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID)
						WHERE 
							" . HC_TblPrefix . "events.IsActive = 1 AND
							" . HC_TblPrefix . "events.IsApproved = 1 AND
							" . HC_TblPrefix . "events.StartDate >= '" . $curDate . "' AND
							(" . HC_TblPrefix . "eventcategories.EventID IS NULL OR
							" . HC_TblPrefix . "categories.IsActive = 0)
						ORDER BY StartDate");
	if(hasRows($result) && mysql_result($result,0,0) > 0){
		feedback(2, $hc_lang_home['OrphanNotice']);
	}//end if
	
	$result = doQuery("SELECT c.*, e.PkID, e.Title, u.PkID, u.Identity, u.ShortName
						FROM " . HC_TblPrefix . "comments c
							LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "oidusers u ON (c.PosterID = u.PkID)
							LEFT JOIN " . HC_TblPrefix . "commentsreportlog crl ON (c.PkID = crl.CommentID)
						WHERE c.IsActive = 1 AND c.TypeID = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND crl.IsActive = 1 AND crl.PkID IS NOT NULL
						GROUP BY c.PkID");
	if(hasRows($result) && mysql_result($result,0,0) > 0){
		feedback(2, $hc_lang_home['ComReportNotice']);
	}//end if
	
	echo "<br />";
	$ip = gethostbyname("www.helioscalendar.com");
	if($fp = fsockopen($ip, 80, $errno, $errstr, 1)){
		$read = "";
		$request = "GET /_update/feed.php HTTP/1.1\r\n";
		$request .= "Host: www.helioscalendar.com\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);
		
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		
		$output = explode("/bof", $read);
		if(isset($output[1])){
			$message = explode("/eof", $output[1]);
			echo '<fieldset>';
			echo '<legend>' . $hc_lang_home['Update'] . '&nbsp;&nbsp;(<a href="http://www.refreshmy.com/forum/viewforum.php?id=5" class="main" target="_blank">' . $hc_lang_home['MoreNews'] . '</a>)</legend>';
			echo '<div style="padding:10px;">' . $message[0] . '</div>';
			echo '</fieldset><br />';
		}//end if
		
		fclose($fp);
	}//end if	?>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_home['QuickStat'];?></legend>
		<div style="float:left;width:49%;">
		<table cellpadding="4" cellspacing="0" border="0" width="97%">
			<tr>
				<td class="statsHeader1" colspan="2"><b><?php echo $hc_lang_home['Events'];?></b></td>
			</tr>
			<tr>
				<td width="200" class="eventMain"><?php echo $hc_lang_home['Active'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $curDate . "'");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Billboard'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
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
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Next7'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE StartDate BETWEEN '" . $curDate . "' AND AddDate('" . $curDate . "', INTERVAL 7 DAY)");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.StartDate >= '" . $curDate . "' AND en.IsActive = 1");
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
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Admin'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 0");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(ln.LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE ln.IsActive = 1");
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
				<td width="200" class="eventMain"><?php echo $hc_lang_home['ActiveOID'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['BannedOID'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
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
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['LoggedWeek'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "oidusers WHERE LastLogin BETWEEN SUBDATE('" . $curDate . "', INTERVAL 7 DAY) AND '" . $curDate . " 23:59:59' AND IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td class="statsHeader2" colspan="2"><b><?php echo $hc_lang_home['Misc'];?></b></td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Subscribers'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Comments'];?></td>
				<td class="eventMain" bgcolor="#EFEFEF" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "comments WHERE IsActive = 1");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
			<tr>
				<td class="eventMain"><?php echo $hc_lang_home['Submitted'];?></td>
				<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND SubmittedAt IS NOT NULL");
					echo '<b>' . mysql_result($result,0,0) . '</b>';?>
				</td>
			</tr>
		</table>
		</div>
	</fieldset>