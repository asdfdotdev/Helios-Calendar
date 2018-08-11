<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	appInstructions(0, "Prune_Database", "Prune Helios Database", "Use the button below to prune your Helios database by permanently removing all declined and deleted events.");	
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Database Pruned Successfully!");
				break;
				
		}//end switch
	}//end if	?>
	<br />
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
		function chkFrm(){
			if(confirm('This will permanently remove all deleted and declined events from your Helios database.\n\nAre you sure you wish to proceed.\n\n          Ok = YES, Continue and Delete\n          Cancel = NO, Stop and DO NOT Delete')){
				return true;
			} else {
				return false;
			}//end if
		}//end updateLink()
	//-->
	</script>
<?	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 0 OR IsApproved = 0 OR StartDate = '0000-00-00'");
	if(hasRows($result)){	?>
		<b>Your Helios Database has <?echo mysql_result($result,0,0);?> deleted/decliened events.</b>
		<br /><br />
	<?	if(mysql_result($result,0,0) > 0){	?>
		<form name="frmToolPrune" id="frmToolPrune" method="post" action="<?echo CalAdminRoot . "/components/ToolPruneDBAction.php";?>" onsubmit="return chkFrm();">
		<input name="submit" id="submit" type="submit" value=" Prune Deleted &amp; Declined Events " class="button" />
		</form>
	<?	}//end if
	} else {	?>
		<b>Unable to retrieve database statistics. Cannot Prune.</b>
<?	}//end if?>
	
