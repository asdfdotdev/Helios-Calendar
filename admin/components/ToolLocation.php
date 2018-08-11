<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Editing_Locations", "Manage Locations", "The list below contains locations available for your events. To merge locations, and assign their collective events to a single location, select the locations you wish to merge and click \"Merge Selected Events\" below. <br /><br /><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"absmiddle\" /> = Edit Location<br /><img src=\"" . CalAdminRoot . "/images/icons/iconDelete.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"absmiddle\" /> = Delete Location");
	if (isset($_GET['msg'])){
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
				feedback(2, "Location Updated. Connection to Yahoo Failed. Please Try Again Later.");
				break;
			
			case "5" :
				feedback(2, "Location Added. Update Map Data Failed, or API Key Missing.");
				break;
				
			case "6" :
				feedback(2, "Location Updated. Update Map Data failed, or API Key Missing.");
				break;
				
			case "7" :
				feedback(1, "Location Deleted and Removed From Eventful Successfully!");
				break;
			
			case "8" :
				feedback(2, "Location Deleted Successfully. Eventful Connection failed.");
				break;
				
			case "9" :
				feedback(1, "Location Added and Submitted to Eventful Successfully!");
				break;
				
			case "10" :
				feedback(1, "Location Updated and Submitted to Eventful Successfully!");
				break;
				
			case "11" :
				feedback(2, "Location Saved Succesfully. Eventful Connection Failed");
				break;
			
			case "12" :
				feedback(2, "Locations Could Not Be Merged. Please Try Again.");
				break;
			
			case "13" :
				feedback(1, "Locations Merged Successfully.");
				break;
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 ORDER BY IsPublic, Name");
	if(hasRows($result)){	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function doDelete(dID){
		if(confirm('Location Delete Is Perminant!\nAre you sure you want to delete this location?\n\n          Ok = YES Delete Location\n          Cancel = NO Don\'t Delete Location')){
			document.location.href = '<?php echo CalAdminRoot . "/components/ToolLocationEditAction.php";?>?dID=' + dID;
		}//end if
	}//end doDelete
	
	function chkFrm(){
		if(validateCheckArray('frmMergeLocation','locID[]',2) == 1){
			alert('More selected locations required.\nPlease select at least two locations and try again.');
			return false;
		}//end if
		return true;
	}//end chkFrm()
	//-->
	</script>
		<div style="text-align:right;clear:both;padding-top:10px;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('frmMergeLocation', 'locID[]');">Select All</a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmMergeLocation', 'locID[]');">Deselect All</a> ]
		</div>
		<div class="locList">
			<div class="locName">Name</div>
			<div class="locStatus">Status</div>
			&nbsp;
		</div>
		<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="<?php echo CalAdminRoot . "/index.php?com=location&m=1";?>" onsubmit="return chkFrm();">
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="locName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
			<div class="locStatus<?php if($cnt % 2 == 1){echo "HL";}?>">
		<?php 	if($row[12] == 1){
					echo "Public";
				} else {
					echo "Admin Only";
				}//end if	?>
			</div>
			<div class="locTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=addlocation&amp;lID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a>&nbsp;
				<a href="javascript:;" onclick="doDelete('<?php echo $row[0];?>');return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a>&nbsp;
				<input type="checkbox" name="locID[]" id="locID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />
			</div>
	<?php 	$cnt++;
		}//end while	?>
		<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('frmMergeLocation', 'locID[]');">Select All</a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('frmMergeLocation', 'locID[]');">Deselect All</a> ]
		</div>
		<input name="submit" id="submit" type="submit" value="Merge Selected Locations" class="button" />
		</form>
<?php
	} else {	?>
		<br />
		There are currently no locations available.
<?php
	}//end if	?>