<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Reports", "Recently Added Events", "The following are the fifty newest events added to " . CalName . ".<br /><br /><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Event");
	$result = doQuery("	SELECT * 
							FROM " . HC_TblPrefix . "events 
						WHERE StartDate >= NOW() 
							AND IsActive = 1 
							AND IsApproved = 1
						ORDER BY PublishDate DESC, StartDate
						LIMIT 50");
	
	if(hasRows($result)){	?>
		<div class="recentList">
			<div class="recentTitle"><b>Event</b></div>
			<div class="recentDateA"><b>Added</b></div>
			<div class="recentDateO"><b>Occurs</b></div>
			<div class="recentTools">&nbsp;</div>
			&nbsp;
		</div>
	<?	$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){	?>
			<div class="recentTitle<?if($cnt % 2 == 1){echo "HL";}?>"><?echo cOut($row[1]);?></div>
			<div class="recentDateA<?if($cnt % 2 == 1){echo "HL";}?>"><?echo stampToDate(cOut($row[27]), $hc_popDateFormat)?></div>
			<div class="recentDateO<?if($cnt % 2 == 1){echo "HL";}?>"><?echo stampToDate(cOut($row[9]), $hc_popDateFormat)?></div>
			<div class="recentTools<?if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" /></a>&nbsp;&nbsp;
			<?	if($row[18] == 1){	?>
					<a href="<?echo CalAdminRoot;?>/index.php?com=eventbillboard" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconBillboard.gif" width="15" height="15" alt="" border="0" /></a>
			<?	} else {	?>
					<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="15" height="15" alt="" border="0" />
			<?	}//end if	?>
			</div>
	<?	$cnt = $cnt + 1;
		}//end while
	} else {	?>
		<br /><br />
		There are currently no events with views for this report.
<?	}//end if	?>