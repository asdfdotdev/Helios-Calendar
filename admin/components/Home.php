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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/home.php');
	
	appInstructions(0, "Helios", $hc_lang_home['Title'], $hc_lang_home['Instructions']);
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if	
	$curDate = date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2 AND StartDate >= '" . $curDate . "'");
	if(hasRows($result)){
		if(mysql_result($result,0,0) > 0){
			feedback(1, $hc_lang_home['PendingNotice']);
		}//end if
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
	if(hasRows($result)){
		if(mysql_result($result,0,0) > 0){
			feedback(2, $hc_lang_home['OrphanNotice']);
		}//end if
	}//end if
	echo "<br />";
	$ip = gethostbyname("www.helioscalendar.com");
	if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
		//echo "Helios Feed Unavailable<br /><br />";
	} else {
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
			$message = explode("/eof", $output[1]);	?>
			<fieldset>
				<legend><?php echo $hc_lang_home['Update'];?></legend>
				<div style="padding:10px;"><?php echo $message[0];?></div>
			</fieldset>
			<br />
<?php 	}//end if
		
		fclose($fp);
	}//end if	?>
<fieldset>
	<legend><?php echo $hc_lang_home['QuickStat'];?></legend>
	<div style="float:left;width:49%;">
	<table cellpadding="4" cellspacing="0" border="0" width="97%">
		<tr>
			<td class="statsHeader" colspan="2"><b><?php echo $hc_lang_home['Events'];?></b></td>
		</tr>
		<tr>
			<td width="200" class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Active'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . $curDate . "'");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain"><?php echo $hc_lang_home['Passed'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < '" . $curDate . "'");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Billboard'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= '" . $curDate . "'");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain"><?php echo $hc_lang_home['Today'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = '" . $curDate . "'");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Next7'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE StartDate BETWEEN '" . $curDate . "' AND AddDate('" . $curDate . "', INTERVAL 7 DAY)");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
		<?php
			$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(37);");
			$efUser = "";
			if(hasRows($result)){
				$efUser = mysql_result($result,0,0);
			}//end if	?>
			<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(en.EventID) FROM " . HC_TblPrefix . "eventnetwork en LEFT JOIN " . HC_TblPrefix . "events e ON (e.PkID = en.EventID) WHERE e.StartDate >= '" . $curDate . "' AND en.IsActive = 1");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
	</table>
	</div>
	<div style="float:left;width:50%;">
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="statsHeader" colspan="2"><b><?php echo $hc_lang_home['Users'];?></b></td>
		</tr>
		<tr>
			<td width="200" class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Registered'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain"><?php echo $hc_lang_home['New7'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1 AND RegisteredAt  BETWEEN SubDate('" . $curDate . " 00:00:00', INTERVAL 7 DAY) AND " . date("Y-m-d"));	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Pending'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 0");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
	</table>
		
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="statsHeader" colspan="2"><b><?php echo $hc_lang_home['Locations'];?></b></td>
		</tr>
		<tr>
			<td width="200" class="eventMain" bgcolor="#EFEFEF"><?php echo $hc_lang_home['Public'];?></td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 1");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain"><?php echo $hc_lang_home['Admin'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 0");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
		<?php
			$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(37);");
			$efUser = "";
			if(hasRows($result)){
				$efUser = mysql_result($result,0,0);
			}//end if	?>
			<td class="eventMain"><?php echo $hc_lang_home['Eventful'];?></td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(ln.LocationID) FROM " . HC_TblPrefix . "locationnetwork ln LEFT JOIN " . HC_TblPrefix . "locations l ON (l.PkID = ln.LocationID) WHERE ln.IsActive = 1");	?>
				<b><?php echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
	</table>
	
	</div>
</fieldset>