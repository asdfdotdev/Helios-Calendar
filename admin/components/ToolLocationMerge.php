<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	if(isset($_POST['locID'])){
		$locID = $_POST['locID'];
		$locIDs = "";
		foreach ($locID as $val){
			$locIDs = $locIDs . $val . ",";
		}//end while
		$locIDs = substr($locIDs, 0, strlen($locIDs) - 1);
	}//end if	
	
	appInstructions(1, "Merging_Locations", "Merge Locations", "To merge the locations below select the location you want to merge the others with by selecting the radio button next to that location.<br /><br />The <b>selected location</b> will replace the other locations and all events will be assigned to it.");
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 AND PkID IN (" . cIn($locIDs) . ") ORDER BY IsPublic, Name");
	if(hasRows($result)){	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(validateCheckArray('frmMergeLocation','mergeID[]',1) == 1){
			alert('You must select a location to merge the others with.\nPlease select a location and try again.');
			return false;
		}//end if
		return true;
	}//end chkFrm()
	//-->
	</script>
		<div class="locList">
			<div class="locName">Name</div>
			<div class="locStatus">Status</div>
			&nbsp;
		</div>
		<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="<?php echo CalAdminRoot . "/components/ToolLocationMergeAction.php";?>" onsubmit="return chkFrm();">
		<input type="hidden" name="locIDs" id="locIDs" value="<?php echo $locIDs;?>" />
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
				&nbsp;<input type="radio" name="mergeID[]" id="mergeID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />
			</div>
	<?php 	$cnt++;
		}//end while	?>
		<div style="clear:both;padding-top:10px;border-top: 1px solid #000000;">
			<input name="submit" id="submit" type="submit" value="Merge as Selected Location" class="button" />
		</div>
		
		</form>
<?php
	} else {	?>
		<br />
		There are currently no locations available.
<?php
	}//end if	?>