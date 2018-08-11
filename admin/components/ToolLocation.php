<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Location_Manager", "Manage Locations", "The list below contains locations available for your events. <br /><br /><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Location<br /><img src=\"" . CalAdminRoot . "/images/icons/iconDelete.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Delete Location");	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('Location Delete Is Perminant!\nAre you sure you want to delete this location?\n\n          Ok = YES Delete Location\n          Cancel = NO Don\'t Delete Location')){
			document.location.href = '<?echo CalAdminRoot . "/components/ToolLocationEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Location Added Successfully!");
				break;
				
			case "2" :
				feedback(1, "Location Updated Successfully!");
				break;
				
			case "3" :
				feedback(1, "Location Deleted Successfully!");
				break;
				
			case "4" :
				feedback(2, "Location Updated. Unable to Update Map Data. Please Try Again Later.");
				break;
			
			case "5" :
				feedback(2, "Location Saved. Update Map Data Failed, or API Key Missing.");
				break;
				
			case "6" :
				feedback(2, "Location Updated. Update Map Data failed, or API Key Missing.");
				break;
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 ORDER BY IsPublic, Name");
	if(hasRows($result)){	?>
		<div class="locList">
			<div class="locName">Name</div>
			<div class="locStatus">Status</div>
			&nbsp;
		</div>
	<?	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="locName<?if($cnt % 2 == 1){echo "HL";}?>"><?echo $row[1];?></div>
			<div class="locStatus<?if($cnt % 2 == 1){echo "HL";}?>">
			<?	if($row[12] == 1){
					echo "Public";
				} else {
					echo "Admin Only";
				}//end if	?>
			</div>
			<div class="locTools<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=addlocation&amp;lID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;<a href="javascript:;" onclick="doDelete('<?echo $row[0];?>');return false;" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
		<?	$cnt++;
		}//end while
	} else {	?>
		<br />
		There are currently no locations available.
<?	}//end if	?>
	