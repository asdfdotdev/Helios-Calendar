<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
	<script language="JavaScript" type="text/JavaScript">
	function doDelete(eID){
		if(confirm('Are you sure you want to delete this event?\n\n          Ok = YES Delete Event\n          Cancel = NO Don\'t Delete Event')){
			window.location.href='<?echo CalAdminRoot . "/components/EventDelete.php";?>?dID=' + eID + '&oID=1';
		}//end if
	}//end doDelete
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Event Deleted Successfully!");
				break;
				
		}//end switch
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
		appInstructions(0, "Orphan_Events", "Orphan Events", "<img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Event (Assign Categories)<br /><img src=\"" . CalAdminRoot . "/images/icons/iconDelete.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Delete Event From Calendar");	?>
		<div class="orphanList">
			<div class="orphanListTitle"><b>Event Title</b></div>
			<div class="orphanListDate"><b>Event Date</b></div>
			<div class="orphanListTools">&nbsp;</div>&nbsp;
		</div>
	<?	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="orphanListTitle<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>&amp;oID=1" class="main"><?echo cOut($row[1]);?></a></div>
			<div class="orphanListDate<?if($cnt % 2 == 1){echo "HL";}?>"><?echo StampToDate($row[9], $hc_popDateFormat);?></div>
			<div class="orphanListTools<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>&amp;oID=1" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?echo $row[0];?>');return false;" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
		<?	$cnt++;
		}//end while
	
	} else {	?>
		<br />
		<b>There are currently no orphan events.</b>
<?	}//end if	?>