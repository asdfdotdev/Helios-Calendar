<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/locations.php');
	
	$hc_Side[] = array(CalRoot . '/index.php?com=location','map.png',$hc_lang_locations['LinkMap'],1);
	$locIDs = (isset($_POST['locID'])) ? implode(',',array_filter($_POST['locID'],'is_numeric')) : '';
	
	appInstructions(1, "Merging_Locations", $hc_lang_locations['TitleMerge'], $hc_lang_locations['InstructMerge3']);
	
	$result = doQuery("SELECT PkID, Name, IsPublic, 
						(SELECT COUNT(PkID) FROM " . HC_TblPrefix. "events e WHERE e.StartDate >= '" . cIn(SYSDATE) . "' AND e.LocID = l.PkID) AS EventCnt
					FROM " . HC_TblPrefix . "locations l
					WHERE IsActive = 1 AND PkID IN (" . $locIDs . ")
					ORDER BY IsPublic, Name");
	if(hasRows($result)){
		echo '
		<form name="frmMergeLocation" id="frmMergeLocation" method="post" action="'.AdminRoot.'/components/LocationMergeAction.php" onsubmit="return validate();">
		<input type="hidden" name="locIDs" id="locIDs" value="'.$locIDs.'" />
		<ul class="data">
			<li class="row header uline">
				<div style="width:65%;">'.$hc_lang_locations['NameLabel'].'</div>
				<div style="width:15%;">'.$hc_lang_locations['StatusLabel'].'</div>
				<div class="number" style="width:10%;">'.$hc_lang_locations['Events'].'</div>
				<div style="width:10%;">&nbsp;</div>
			</li>';
		
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 1) ? ' hl' : '';
			
			echo '
			<li class="row'.$hl.'">
				<div class="text" style="width:65%;">'.cOut($row[1]).'</div>
				<div class="text" style="width:15%;">'.(($row[2] == 1) ? $hc_lang_locations['Public'] : $hc_lang_locations['AdminOnly']).'</div>
				<div class="number" style="width:10%;">'.number_format(cOut($row[3]),0,'',',').'</div>
				<div class="tools" style="width:10%;">
					<input name="mergeID[]" id="mergeID_' . $row[0] . '" type="radio" value="'.$row[0].'" />
				</div>
			</li>';
			++$cnt;
		}
		echo '
		</ul>
		
		<input name="submit" id="submit" type="submit" value="' . $hc_lang_locations['MergeAsLoc'] . '" />
		</form>
		
		<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
		<script>
		//<!--
		function validate(){
			if(validCheckArray("frmMergeLocation","mergeID[]",1,"error") != ""){
				alert("'.$hc_lang_locations['Valid16'].'");
				return false;
			}
			return true;
		}
		//-->
		</script>';
	} else {
		echo '<p>'.$hc_lang_locations['NoLoc'].'</p>';
	}
?>