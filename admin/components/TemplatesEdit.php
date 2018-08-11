<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? cIn(strip_tags($_GET['tID'])) : 0;
	
	$whereAmI = $hc_lang_tools['AddT'];
	$helpDoc = "Export_Templates";
	$helpText = $hc_lang_tools['InstructAddTExp'];
	$name = $content = $header = $footer = $ext = '';
	$typeID = $groupBy = 1;
	$sortBy = 2;
	$cleanup = "BLANK";
	$dateFormat = 0;
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE PkID = '" . $tID . "' AND IsActive = 1");
	if(hasRows($result)){
		$whereAmI = $hc_lang_tools['EditT'];
		$helpText = $hc_lang_tools['InstructEditTExp'];
		$name = cOut(mysql_result($result,0,1));
		$content = cOut(mysql_result($result,0,2));
		$header = cOut(mysql_result($result,0,3));
		$footer = cOut(mysql_result($result,0,4));
		$ext = cOut(mysql_result($result,0,5));
		$typeID = cOut(mysql_result($result,0,6));
		$groupBy = cOut(mysql_result($result,0,7));
		$sortBy = cOut(mysql_result($result,0,8));
		$cleanup = cOut(mysql_result($result,0,9));
		$dateFormat = cOut(mysql_result($result,0,10));
	}
	
	appInstructions(0, $helpDoc, $whereAmI, $helpText);
	
	echo '
	<form name="frmExpTemp" id="frmExpTemp" method="post" action="'.AdminRoot.'/components/TemplatesEditAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="tID" id="tID" value="'.$tID.'" />
	<input type="hidden" name="typeID" id="typeID" value="'.$typeID.'" />
	<fieldset>
		<legend>'.$hc_lang_tools['TempSettings'].'</legend>
		<label for="name">'.$hc_lang_tools['Name'].'</label>
		<input name="name" id="name" type="text" size="50" maxlength="255" required="required" value="'.$name.'" />
		<label for="ext">'.$hc_lang_tools['Extension'].'</label>
		<input name="ext" id="ext" type="text" size="5" maxlength="15" required="required" value="'.$ext.'" />
		<label for="sort">'.$hc_lang_tools['Sort'].'</label>
		<select name="sort" id="sort">
			<option'.(($sortBy == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_tools['OptSort0'].'</option>
			<option'.(($sortBy == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_tools['OptSort1'].'</option>
			<option'.(($sortBy == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_tools['OptSort2'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_tools['Tip01'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="group">'.$hc_lang_tools['GroupBy'].'</label>
		<select name="group" id="group">
			<option'.(($groupBy == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_tools['OptCategory'].'</option>
			<option'.(($groupBy == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_tools['OptEvent'].'</option>
			<option'.(($groupBy == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_tools['OptEventS'].'</option>
			<option'.(($groupBy == 3) ? ' selected="selected"':'').' value="3">'.$hc_lang_tools['OptEventSC'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_tools['Tip02'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="dateFormat">'.$hc_lang_tools['DateFormat'].'</label>
		<select name="dateFormat" id="dateFormat">
			<option'.(($dateFormat == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_tools['Date0'].' (' . stampToDate(date("Y-m-d"),$hc_cfg[14]).')'.'</option>
			<option'.(($dateFormat == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_tools['Date1'].' (' . stampToDate(date("Y-m-d"),$hc_cfg[24]).')'.'</option>
			<option'.(($dateFormat == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_tools['Date2'].' (' . stampToDateAP(date("Y-m-d"),1).')'.'</option>
		</select>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['TempVarsOper'].'</legend>
		<label>'.$hc_lang_tools['Variables'].'</label>
		<span class="output">
			<a href="javascript:;" onclick="togVar();" id="tempLink">'.$hc_lang_tools['ShowVariables'].'</a>
		</span>
		<div id="tempVars" style="display:none;">
			<h5>'.$hc_lang_tools['VarLabelE'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[event_id] - '.$hc_lang_tools['Tip03'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_id]</span>
				<span><a class="tooltip" data-tip="[event_title] - '.$hc_lang_tools['Tip04'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_title]</span>
				<span><a class="tooltip" data-tip="[event_desc] - '.$hc_lang_tools['Tip05'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_desc]</span>
				<span><a class="tooltip" data-tip="[event_date] - '.$hc_lang_tools['Tip06'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_date]</span>
			</p>
			<p>
				<span><a class="tooltip" data-tip="[event_time_start] - '.$hc_lang_tools['Tip07'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_time_start]</span>
				<span><a class="tooltip" data-tip="[event_time_end] - '.$hc_lang_tools['Tip08'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_time_end]</span>
				<span><a class="tooltip" data-tip="[event_cost] - '.$hc_lang_tools['Tip09'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_cost]</span>
				<span><a class="tooltip" data-tip="[event_billboard] - '.$hc_lang_tools['Tip10'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event_billboard]</span>
			</p>
			<h5>'.$hc_lang_tools['VarLabelC'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[contact_name] - '.$hc_lang_tools['Tip11'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[contact_name]</span>
				<span><a class="tooltip" data-tip="[contact_email] - '.$hc_lang_tools['Tip12'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[contact_email]</span>
				<span><a class="tooltip" data-tip="[contact_phone] - '.$hc_lang_tools['Tip13'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[contact_phone]</span>
				<span><a class="tooltip" data-tip="[contact_url] - '.$hc_lang_tools['Tip14'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[contact_url]</span>
			</p>
			<h5>'.$hc_lang_tools['VarLabelR'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[space] - '.$hc_lang_tools['Tip15'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[space]</span>
			</p>
			<h5>'.$hc_lang_tools['VarLabelL'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[loc_name] - '.$hc_lang_tools['Tip16'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_name]</span>
				<span><a class="tooltip" data-tip="[loc_address] - '.$hc_lang_tools['Tip17'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_address]</span>
				<span><a class="tooltip" data-tip="[loc_address2] - '.$hc_lang_tools['Tip18'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_address2]</span>
				<span><a class="tooltip" data-tip="[loc_city] - '.$hc_lang_tools['Tip19'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_city]</span>
			</p>
			<p>
				<span><a class="tooltip" data-tip="[loc_region] - '.$hc_lang_tools['Tip20'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_region]</span>
				<span><a class="tooltip" data-tip="[loc_postal] - '.$hc_lang_tools['Tip21'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_postal]</span>
				<span><a class="tooltip" data-tip="[loc_country] - '.$hc_lang_tools['Tip22'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_country]</span>
				<span><a class="tooltip" data-tip="[loc_url] - '.$hc_lang_tools['Tip23'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[loc_url]</span>
			</p>
			<h5>'.$hc_lang_tools['VarLabelS'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[date_series] - '.$hc_lang_tools['Tip24'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[date_series]</span>
				<span><a class="tooltip" data-tip="[date_unique] - '.$hc_lang_tools['Tip25'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[date_unique]</span>
				<span><a class="tooltip" data-tip="[category_unique] - '.$hc_lang_tools['Tip26'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[category_unique]</span>
				<span><a class="tooltip" data-tip="[desc_notags] - '.$hc_lang_tools['Tip27'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[desc_notags]</span>
			</p>
			<h5>'.$hc_lang_tools['VarLabelHF'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[cal_url] - '.$hc_lang_tools['Tip28'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[cal_url]</span>
			</p>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['TempContent'].'</legend>
		<label for="ex_header">'.$hc_lang_tools['Header'].'<br />('.$hc_lang_tools['Optional'].')</label>
		<textarea name="ex_header" id="ex_header" rows="10">'.$header.'</textarea>
		<label for="ex_data">'.$hc_lang_tools['Content'].'</label>
		<textarea name="ex_data" id="ex_data" rows="10" required="required">'.$content.'</textarea>
		<label for="ex_footer">'.$hc_lang_tools['Footer'].'<br />('.$hc_lang_tools['Optional'].')</label>
		<textarea name="ex_footer" id="ex_footer" rows="10">'.$footer.'</textarea>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_tools['CleanUp'].'</legend>
		<label>&nbsp;</label>
		'.$hc_lang_tools['CleanUpNotice'].'
		<label for="cleanup">'.$hc_lang_tools['CleanList'].'<br /> ('.$hc_lang_tools['Optional'].')</label>
		<textarea name="cleanup" id="cleanup" rows="10" cols="50" style="width:80%;">'.$cleanup.'</textarea>
	</fieldset>
	<input name="submit" id="submit" type="submit" value="'.$hc_lang_tools['SaveTemplate'].'" />
	<input onclick="window.location.href=\''.AdminRoot.'/index.php?com=exporttmplts\';return false;" name="cancel" id="cancel" type="button" value="'.$hc_lang_tools['Cancel'].'" />
	</form>
	
	<script>
	//<!--
	function togVar(doTog, doLink){if(document.getElementById("tempVars").style.display == "none"){document.getElementById("tempVars").style.display = "block";document.getElementById("tempLink").innerHTML = "'.$hc_lang_tools['HideVariables'].'";} else {document.getElementById("tempVars").style.display = "none";document.getElementById("tempLink").innerHTML = "'.$hc_lang_tools['ShowVariables'].'";}}
	
	function validate(){
		var err = "";
		
		err += reqField(document.getElementById("name"),"'.$hc_lang_tools['Valid20'].'\n");
		err += reqField(document.getElementById("ext"),"'.$hc_lang_tools['Valid21'].'\n");
		err += reqField(document.getElementById("ex_data"),"'.$hc_lang_tools['Valid22'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	//-->
	</script>';
?>