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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/locations.php');

	if(isset($_POST['locID'])){
		$locID = $_POST['locID'];
		$locIDs = "";
		foreach ($locID as $val){
			$locIDs = $locIDs . $val . ",";
		}//end while
		$locIDs = substr($locIDs, 0, strlen($locIDs) - 1);
	}//end if	
	
	appInstructions(1, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge3']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "locations WHERE IsActive = 1 AND PkID IN (" . cIn($locIDs) . ") ORDER BY IsPublic, Name");
	if(hasRows($result)){	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(validateCheckArray('frmMergeLocation','mergeID[]',1) == 1){
			alert('<?php echo $hc_lang_locations['Valid16'];?>');
			return false;
		}//end if
		return true;
	}//end chkFrm()
	//-->
	</script>
		<div class="locList">
			<div class="locName"><?php echo $hc_lang_locations['NameLabel'];?></div>
			<div class="locStatus"><?php echo $hc_lang_locations['StatusLabel'];?></div>
			&nbsp;
		</div>
		<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="<?php echo CalAdminRoot . "/components/LocationMergeAction.php";?>" onsubmit="return chkFrm();">
		<input type="hidden" name="locIDs" id="locIDs" value="<?php echo $locIDs;?>" />
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($result)){	?>
			<div class="locName<?php if($cnt % 2 == 1){echo "HL";}?>"><?php echo $row[1];?></div>
			<div class="locStatus<?php if($cnt % 2 == 1){echo "HL";}?>">
		<?php 	if($row[12] == 1){
					echo $hc_lang_locations['Public'];
				} else {
					echo $hc_lang_locations['AdminOnly'];
				}//end if	?>
			</div>
			<div class="locTools<?php if($cnt % 2 == 1){echo "HL";}?>">
				&nbsp;<input type="radio" name="mergeID[]" id="mergeID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />
			</div>
	<?php 	$cnt++;
		}//end while	?>
		<div style="clear:both;padding-top:10px;border-top: 1px solid #000000;">
			<input name="submit" id="submit" type="submit" value="<?php echo $hc_lang_locations['MergeAsLoc'];?>" class="button" />
		</div>
		</form>
<?php
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}//end if	?>