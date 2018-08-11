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
	//<!--
	function doDelete(eID){
		if(confirm('Are you sure you want to remove this billboard event?\n\n          Ok = YES Remove Event\n          Cancel = NO Don\'t Remove Event')){
			window.location.href='<?echo CalAdminRoot . "/components/EventBillboardAction.php";?>?eID=' + eID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Event Removed From Billboard Successfully!");
				break;
				
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsBillboard = 1 AND StartDate >= NOW() ORDER BY StartDate, Views DESC");
	
	if(hasRows($result)){	?>
	
<?	appInstructions(0, "Billboard_Events", "Billboard Events", "The list below contains all events currently assigned to your billboard.<br />Here you can edit the event or remove it from the billboard. <br /><br /><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Event<br /><img src=\"" . CalAdminRoot . "/images/icons/iconDelete.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Remove Event From Billboard");	?>
	<div class="billboardList">
		<div class="billboardListTitle"><b>Event Title</b></div>
		<div class="billboardListDate"><b>Event Date</b></div>
		<div class="billboardListViews"><b>Views</b></div>
		<div class="billboardListTools">&nbsp;</div>&nbsp;
	</div>
	<?	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="billboardListTitle<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>" class="main"><?echo cOut($row[1]);?></a></div>
			<div class="billboardListDate<?if($cnt % 2 == 1){echo "HL";}?>"><?echo stampToDate($row[9], $hc_popDateFormat);?></div>
			<div class="billboardListViews<?if($cnt % 2 == 1){echo "HL";}?>"><?echo cOut($row[28]);?></div>
			<div class="billboardListTools<?if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?echo $row[0];?>');return false;" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a>
			</div>
		<?	$cnt++;
		}//end while	?>
<?	} else {	?>
		<br />
		<b>There are currently no events assigned to the billboard.</b>
<?	}//end if	?>