<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Main_Page", "Welcome to the Helios Calendar Administration Console", "Look for instruction boxes like this one throughout the admin for information about how to use each section. Also information icons are available to provide you with additional context relivant information.<br /><br /><a onmouseover=\"this.T_TITLE='Sample Information Box';this.T_SHADOWCOLOR='#3D3F3E';return escape('This is an example of how the information icons work. When you see one move your mouse over it for more information.')\" href=\"javascript:;\"><img src=\"" . CalAdminRoot . "/images/icons/iconInfo.gif\" width=\"16\" height=\"16\" alt=\"\" border=\"0\" align=\"absmiddle\" /></a> &laquo;&laquo; Point your cursor here for an example.");
	
	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2 AND StartDate >= NOW()");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == 1){
			feedback(1,"You have 1 pending event.");
		} else if(mysql_result($result,0,0) > 1){
			feedback(1,"You have " . mysql_result($result,0,0) . " pending events.");
		}//end if
	}//end if
	
	$result = doQuery("	SELECT " . HC_TblPrefix . "events.*
						FROM " . HC_TblPrefix . "events
							LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
							LEFT JOIN " . HC_TblPrefix . "categories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID)
						WHERE 
							" . HC_TblPrefix . "events.IsActive = 1 AND
							" . HC_TblPrefix . "events.IsApproved = 1 AND
							" . HC_TblPrefix . "events.StartDate >= NOW() AND
							(" . HC_TblPrefix . "eventcategories.EventID IS NULL OR
							" . HC_TblPrefix . "categories.IsActive = 0)
						ORDER BY StartDate");
	if(hasRows($result)){
		if(mysql_result($result,0,0) > 0){
			feedback(2,"You have orphan event(s).");
		}//end if
	}//end if
?>
<br />
<?	if(!($fp = fsockopen("www.helioscalendar.com", 80, $errno, $errstr, 1)) ){
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
				<legend>Helios Update</legend>
				<div style="padding:10px;"><?	echo $message[0];	?></div>
			</fieldset>
			<br />
	<?	}//end if
		
		fclose($fp);
	}//end if	?>
<fieldset>
	<legend>Quick Statistics</legend>
	<div style="float:left;width:50%;">
	<table cellpadding="4" cellspacing="0" border="0" width="97%">
		<tr>
			<td class="statsHeader" colspan="2"><b>Events</b></td>
		</tr>
		<tr>
			<td width="120" class="eventMain" bgcolor="#EFEFEF">Active:</td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain">Passed:</td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF">Billboard:</td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain">Today:</td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate = NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF">Next 7 Days:</td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE StartDate BETWEEN NOW() AND AddDate(NOW(), INTERVAL 7 DAY)");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
	</table>
	</div>
	<div style="float:left;width:50%;">
	
	<table cellpadding="4" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="statsHeader" colspan="2"><b>Users</b></td>
		</tr>
		<tr>
			<td width="120" class="eventMain" bgcolor="#EFEFEF">Registered:</td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain">New Today:</td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1 AND RegisteredAt  BETWEEN '" . date("Y-m-d") . " 00:00:00' AND NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain" bgcolor="#EFEFEF">New Last 7 Days:</td>
			<td class="eventMain" align="right" bgcolor="#EFEFEF">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 1 AND RegisteredAt  BETWEEN SubDate('" . date("Y-m-d") . " 00:00:00', INTERVAL 7 DAY) AND NOW()");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
		<tr>
			<td class="eventMain">Registration&nbsp;Pending:</td>
			<td class="eventMain" align="right">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 0");
				?>
				<b><?echo cOut(mysql_result($result,0,0));?></b></td>
		</tr>
	</table>
	</div>
</fieldset>