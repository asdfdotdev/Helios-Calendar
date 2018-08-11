<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<table cellpadding="4" cellspacing="0" border="0" width="100%">
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
		<?	if($adminEventPending == 1){	?>
				<td class="eventMain"><a href="<?echo CalAdminRoot . "/index.php?com=eventpending" ;?>" class="main">Pending:</a></td>
		<?	} else {	?>
			<td class="eventMain">Pending:</td>
		<?	}//end if	?>
		<td class="eventMain" align="right">
			<?php
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 2 AND StartDate >= NOW()");
			?>
			<b><?echo cOut(mysql_result($result,0,0));?></b></td>
	</tr>
	<tr>
		<td class="eventMain" bgcolor="#EFEFEF">Passed:</td>
		<td class="eventMain" align="right" bgcolor="#EFEFEF">
			<?php
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate < now()");
			?>
			<b><?echo cOut(mysql_result($result,0,0));?></b></td>
	</tr>
	<tr>
		<td class="eventMain">Billboard:</td>
		<td class="eventMain" align="right">
			<?php
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND IsBillboard = 1 AND StartDate >= now()");
			?>
			<b><?echo cOut(mysql_result($result,0,0));?></b></td>
	</tr>
	<tr>
		<?	if($adminEventEdit == 1){	?>
				<td class="eventMain" bgcolor="#EFEFEF"><a href="<?echo CalAdminRoot . "/index.php?com=eventorphan";?>" class="main">Orphan</a>:</td>
		<?	} else {	?>
			<td class="eventMain" bgcolor="#EFEFEF">Orphan:</td>
		<?	}//end if	?>
		<td class="eventMain" align="right" bgcolor="#EFEFEF">
			<?php
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
			?><b><?echo cOut(mysql_num_rows($result));?></b></td>
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
	<?	if($adminEventEdit == 1){	?>
	<tr>
		<td class="eventMain" colspan="2" align="center">
			[ <a href="<?echo CalAdminRoot . "/index.php?com=eventadd";?>" class="main">add event</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?echo CalAdminRoot . "/index.php?com=eventsearch";?>" class="main">edit event</a> ]
		</td>
	</tr>
	<?	}//end if	?>
</table>
<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0">
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
		<td class="eventMain">Unregistered:</td>
		<td class="eventMain" align="right">
			<?php
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "users WHERE IsRegistered = 0");
			?>
			<b><?echo cOut(mysql_result($result,0,0));?></b></td>
	</tr>
	<?if($adminUserEdit == 1){?>
	<tr>
		<td class="eventMain" colspan="2" align="center">
			[ <a href="<?echo CalAdminRoot . "/index.php?com=useredit";?>" class="main">add user</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?echo CalAdminRoot . "/index.php?com=usersearch";?>" class="main">edit user</a> ]
		</td>
	</tr>
	<?}//end if?>
</table>
<br><br>
<div align="center"><a target="_blank" href="http://www.spreadfirefox.com/?q=affiliates&amp;id=105684&amp;t=86"><img border="0" alt="Get Firefox!" title="Get Firefox!" src="<?echo CalAdminRoot;?>/images/firefox.png"/></a></div>