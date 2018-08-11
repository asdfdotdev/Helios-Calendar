<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/locations.php');
	
	$hc_Side[] = array(CalRoot . '/index.php?com=location','iconMap.png',$hc_lang_locations['LinkMap'],1);

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
		
		echo '<div style="clear:both;padding-top:10px;">';
		echo '<input name="submit" id="submit" type="submit" value="' . $hc_lang_locations['MergeAsLoc'] . '" class="button" />';
		echo '</div></form>';
	} else {
		echo "<br />" . $hc_lang_locations['NoLoc'];
	}//end if	?>