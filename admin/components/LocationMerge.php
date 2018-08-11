<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/locations.php');

	if(isset($_POST['locID'])){
		$locID = $_POST['locID'];
		$locIDs = "0";
		foreach ($locID as $val){
			if(is_numeric($val)){
				$locIDs = $locIDs . ',' . $val;
			}//end if
		}//end while
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
<?php
		echo '<div class="locList">';
		echo '<div class="locName">' . $hc_lang_locations['NameLabel'] . '</div>';
		echo '<div class="locStatus">' . $hc_lang_locations['StatusLabel'] . '</div>';
		echo '&nbsp;</div>';
		echo '<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="' . CalAdminRoot . '/components/LocationMergeAction.php" onsubmit="return chkFrm();">';
		echo '<input type="hidden" name="locIDs" id="locIDs" value="' . $locIDs . '" />';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			echo ($cnt % 2 == 1) ? '<div class="locNameHL">' : '<div class="locName">';
			echo $row[1] . '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="locStatusHL">' : '<div class="locStatus">';
			echo ($row[12] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly'];
			echo '</div>';
			
			echo ($cnt % 2 == 1) ? '<div class="locToolsHL">' : '<div class="locTools">';
			echo '&nbsp;<input type="radio" name="mergeID[]" id="mergeID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />';
			echo '</div>';
			
	 		++$cnt;
		}//end while
		
		echo '<div style="clear:both;padding-top:10px;border-top: 1px solid #000000;">';
		echo '<input name="submit" id="submit" type="submit" value="' . $hc_lang_locations['MergeAsLoc'] . '" class="button" />';
		echo '</div></form>';
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}//end if	?>