<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	appInstructions(0, "Event_Export", $hc_lang_tools['TitleExport'], $hc_lang_tools['InstructExport']);
	
	$templates = '';
	$fail = 0;
	
	$result = doQuery("SELECT PkID, Name, Extension FROM " . HC_TblPrefix . "templates WHERE IsActive = 1 AND TypeID = 1 ORDER BY Name");
	if(!hasRows($result)){
		$fail = 1;
	} else {
		while($row = mysql_fetch_row($result)){
			$templates .= '
			<option value="'.$row[0].'">'.$row[1].' ('.$row[2].')</option>';
		}
	}
	
	echo '
	<form name="frmEventExport" id="frmEventExport" method="post" action="'.AdminRoot.'/components/ToolExportAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="dateFormat" id="dateFormat" value="'.$hc_cfg[24].'" />
	<input type="hidden" name="timeFormat" id="timeFormat" value="'.$hc_cfg[23].'" />
	<fieldset>
		<legend>'.$hc_lang_tools['Export'].'</legend>
		<select name="tID" id="tID">
		'.$templates.'
		</select>	
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['Delivery'].'</legend>
		<select name="mID" id="mID">
			<option value="1">'.$hc_lang_tools['Delivery1'].'</option>
			<option value="2">'.$hc_lang_tools['Delivery2'].'</option>
		</select>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['Range'].'</legend>
		<input name="startDate" id="startDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24],strtotime(SYSDATE)).'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'startDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
		<span class="output">&nbsp;&nbsp;'.$hc_lang_tools['To'].'&nbsp;&nbsp;</span>
		<input name="endDate" id="endDate" type="text" size="12" maxlength="10" value="'.strftime($hc_cfg[24], strtotime(SYSDATE)+($hc_cfg[53]*86400)).'" required="required" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'endDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['CategoriesLabel'].'</legend>';
		getCategories('frmEventExport',3);
	echo '
	</fieldset>
	<input'.(($fail > 0) ? ' disabled="disabled"':'').' type="submit" name="submit" id="submit" value="'.$hc_lang_tools['Generate'].'" />
	</form>
	<div id="dsCal" class="datePicker"></div>
	
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
	
	function validate(){
		var err = "";
		
		err += reqField(document.getElementById("startDate"),"'.$hc_lang_tools['Valid03'].'\n");
		err += validDate(document.getElementById("startDate"),"'.$hc_cfg[51].'","'.$hc_lang_tools['Valid02'].' '.strtoupper($hc_cfg[51]).'\n");
		err += reqField(document.getElementById("endDate"),"'.$hc_lang_tools['Valid05'].'\n");
		err += validDate(document.getElementById("endDate"),"'.$hc_cfg[51].'","'.$hc_lang_tools['Valid04'].' '.strtoupper($hc_cfg[51]).'\n");
		err += validDateBefore(document.getElementById("startDate").value,document.getElementById("endDate").value,"'.$hc_cfg[51].'","'.$hc_lang_tools['Valid06'].'\n");
		err += validCheckArray("frmEventExport","catID[]",1,"'.$hc_lang_tools['Valid07'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			document.frmEventExport.target = (document.getElementById("mID").value == 1) ? "_blank" : "_self";
			return true;
		}
	}
	//-->
	</script>';
?>