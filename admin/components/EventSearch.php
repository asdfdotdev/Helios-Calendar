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
	
	include(HCLANG.'/admin/search.php');
	
	$hc_browsePast = 1;
	$sID = (isset($_GET['sID']) && is_numeric($_GET['sID'])) ? cIn($_GET['sID']) : 0;
	$msg = (isset($_GET['msg']) && is_numeric($_GET['msg'])) ? cIn($_GET['msg']) : 0;
	$region = ($hc_lang_config['AddressRegion'] != 0) ? ' | <a href="javascript:;" onclick="toggleSearch(2)" class="legend">'.$hc_lang_config['RegionTitle'] . '</a>' : '';
	
	switch($msg){
		case "1" :
			feedback(1, $hc_lang_search['Feed01']);
			break;
		case "2" :
			feedback(1, $hc_lang_search['Feed02']);
			break;
		case "4" :
			feedback(1, $hc_lang_search['Feed04']);
			break;
		case "5" :
			feedback(2, $hc_lang_search['Feed05']);
			break;
		case "6" :
			feedback(1, $hc_lang_search['Feed06']);
			break;
	}
	switch($sID){
		case 1:
			appInstructions(0, "Editing_Events", $hc_lang_search['TitleEdit'], $hc_lang_search['InstructEdit']);
			break;
		case 2:
			appInstructions(0, "Deleting_Events", $hc_lang_search['TitleDelete'], $hc_lang_search['InstructDelete']);
			break;
		case 3:
			appInstructions(0, "Event_Series", $hc_lang_search['TitleSeries'], $hc_lang_search['InstructSeries']);
			break;
		default:
			appInstructions(0, "Reports", $hc_lang_search['TitleReport'], $hc_lang_search['InstructReport']);
			break;
	}
	echo '
	<form name="frmEventSearch" id="frmEventSearch" method="post" action="'.AdminRoot.'/index.php?com=searchresults" onsubmit="return validate();">
	<input type="hidden" name="sID" id="sID" value="'.$sID.'" />
	<input type="hidden" id="locPreset" name="locPreset" value="0" />
	<input type="hidden" id="locPresetName" name="locPresetName" value="" />
	<fieldset>
		<legend>'.$hc_lang_search['DateTitle'].'</legend>
		<label>'.$hc_lang_search['DateRange'].'</label>
		<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'startDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
		<span class="output">&nbsp;&nbsp;'.$hc_lang_search['To'].'&nbsp;&nbsp;</span>
		<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24], strtotime(SYSDATE)+($hc_cfg[53]*86400)).'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'endDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_search['KeywordTitle'].'</legend>
		<label for="keyword">'.$hc_lang_search['Keywords'].'</label>
		<input name="keyword" id="keyword" type="text" size="50" maxlength="50" value="" x-webkit-speech />
	</fieldset>
	<fieldset>
		<legend>
			<a href="javascript:;" onclick="toggleSearch(0);" class="legend">' . $hc_lang_search['LinkLocation'] . '</a>
			| <a href="javascript:;" onclick="toggleSearch(1);" class="legend">' . $hc_lang_search['LinkCity'] . '</a>
			'.$region.'
			| <a href="javascript:;" onclick="toggleSearch(3)" class="legend">' . $hc_lang_search['LinkPostal'] . '</a>
		</legend>
		<div id="location_div">';
			location_select(false);
	echo '
		</div>
		<div id="city_div" style="display:none;">
			<label for="city">'.$hc_lang_search['City'].'</label>';
	if(!file_exists(HCPATH.'/cache/selCitya.php'))
		buildCache(4,1);
	include(HCPATH.'/cache/selCitya.php');
	echo '
		</div>
		<div id="region_div" style="display:none;">
			<label for="locState">'.$hc_lang_config['RegionLabel'].'</label>';	
	$state = $hc_cfg[21];
	$regSelect = $hc_lang_search['RegSelect'];
	$stateDisabled = 1;
	include(HCLANG.'/'.$hc_lang_config['RegionFile']);
	echo '
		</div>
		<div id="postal_div" style="display:none;">
			<label for="postal">'.$hc_lang_search['Postal'].'</label>';
	if(!file_exists(HCPATH.'/cache/selPostala.php'))
		buildCache(5,1);
	include(HCPATH.'/cache/selPostala.php');
	echo '
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_search['CategoryTitle'].'</legend>
		<label>'.$hc_lang_search['Categories'].'</label>';
	getCategories('frmEventSearch', $hc_cfg['CatCols']);
	echo '
	</fieldset>';

	if($sID == 1){
		echo '
	<fieldset>
		<legend>'.$hc_lang_search['SeriesTitle'].'</legend>
		<span class="frm_ctrls">
			<label for="seriesonly"><input type="checkbox" name="seriesonly" id="seriesonly" value="1" />'.$hc_lang_search['SeriesOnly'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_search['Tip01'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>';
	}
	echo '
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_search['Search'].'" />
	</form>
	<div id="dsCal"></div>

	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	calx.offsetX = 30;
	calx.offsetY = -5;
	var srchInpts = ["locPreset","city","locState","postal"];
	var srchDivs = ["location_div","city_div","region_div","postal_div"];
	function toggleSearch(show){
		for(var i in srchDivs){
			document.getElementById(srchDivs[i]).style.display = (i == show) ? "block" : "none";
			document.getElementById(srchInpts[i]).disabled = (i == show) ? false : true;
		}
	}
	function validate(){
		var err = "";
		
		err += reqField(document.getElementById("startDate"),"'.$hc_lang_search['Valid03'].'\n");
		err += validDate(document.getElementById("startDate"),"'.$hc_cfg[51].'","'.$hc_lang_search['Valid02'].' '.strtoupper($hc_cfg[51]).'\n");
		err += reqField(document.getElementById("endDate"),"'.$hc_lang_search['Valid05'].'\n");
		err += validDate(document.getElementById("endDate"),"'.$hc_cfg[51].'","'.$hc_lang_search['Valid04'].' '.strtoupper($hc_cfg[51]).'\n");
		err += validDateBefore(document.getElementById("startDate").value,document.getElementById("endDate").value,"'.$hc_cfg[51].'","'.$hc_lang_search['Valid06'].'\n");
		if(document.getElementById("keyword").value != "")
			err += validMinLength(document.getElementById("keyword"),4,"'.$hc_lang_search['Valid19'].'\n");
		err += validCheckArray("frmEventSearch","catID[]",1,"'.$hc_lang_search['Valid07'].'\n");
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}';
	$pub_only = $evnt_only = 0;
	include_once(HCPATH.'/inc/javascript/locations.php');
	echo '
	//-->
	</script>';
?>