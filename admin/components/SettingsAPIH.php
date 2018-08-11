<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/settings.php');

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1":
				feedback(1, $hc_lang_settings['Feed04']);
				break;
		}
	}
	
	appInstructions(0, "API", $hc_lang_settings['TitleAPIL'], $hc_lang_settings['InstructAPIL']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings 
					WHERE PkID IN (127,128,129,130,131,132,133)
					ORDER BY PkID");
	
	$api_on = cOut(mysql_result($result,0,0));
	$api_cache = cOut(mysql_result($result,1,0));
	$api_event_size = cOut(mysql_result($result,2,0));
	$api_user_limit = cOut(mysql_result($result,3,0));
	$api_user_time = cOut(mysql_result($result,4,0));
	$api_news_size = cOut(mysql_result($result,5,0));
	$api_version = cOut(mysql_result($result,6,0));
	$apc_option = (function_exists('apc_clear_cache')) ? '<option'.($api_cache == 2 ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['APICache2'].'</option>':'';
	
	echo '
	<form name="frmSettings" id="frmSettings" method="post" action="'.AdminRoot.'/components/SettingsAPIHAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<fieldset>
		<legend>'.$hc_lang_settings['LocalAPI'].'</legend>
		<label>'.$hc_lang_settings['APIVers'].'</label>
		<span class="output">
			'.$api_version.'
		</span>
		<label for="mapLibrary">'.$hc_lang_settings['APIEnd'].'</label>
		<span class="output">
			<a href="'.CalRoot.'/api/" target="_blank">'.CalRoot.'/api/</a>
		</span>
	</fieldset>';
	
	if(function_exists('apc_exists') && apc_exists(HC_APCPrefix.'users_age') && apc_exists(HC_APCPrefix.'users')){
	echo '
	<fieldset id="apc_stats">
		<legend>'.$hc_lang_settings['APCStats'].'</legend>
		<label>'.$hc_lang_settings['APCNextWrite'].'</label>
		<span class="output">
			'.strftime ($hc_cfg[24]." @ ".$hc_cfg[23],apc_fetch(HC_APCPrefix.'users_age')).'
		</span>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip72'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>&nbsp;</label>
		<div class="apc_stat">
		<ul class="data">
			<li class="row header uline">
				<div class="txt" style="width:40%;">'.$hc_lang_settings['APCUser'].'</div>
				<div class="number" style="width:30%;">'.$hc_lang_settings['APCCalls'].'</div>
			</li>';
			
			$cnt = 0;
			foreach(apc_fetch(HC_APCPrefix.'users') as $user){
				$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "users WHERE NetworkName = '".cIn(strip_tags($user[1]))."' AND IsBanned = 0");
				
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				echo '
			<li class="row'.$hl.'">
				<div class="txt" title="'.cOut(strip_tags($user[1])).'" style="width:40%;">'.cOut(strip_tags($user[1])).'</div>
				<div class="number" style="width:30%;">'.number_format(cIn(strip_tags($user[0])),0,'.',',').'&nbsp;</div>
				<div class="tools" style="width:15%;">
					<a href="' . AdminRoot . '/index.php?com=useredit&amp;uID='.cOut(mysql_result($result,0,0)).'" target="_blank"><img src="' . AdminRoot . '/img/icons/user_edit.png" width="16" height="16" alt="" /></a>
				</div>
			</li>';
				++$cnt;
			}

			echo '
		</ul>
		</div>
	</fieldset>';
	}
	
	echo '
	<fieldset>
		<legend>'.$hc_lang_settings['APISet'].'</legend>
		<label for="api_onoff">'.$hc_lang_settings['APIStatus'].'</label>
		<select name="api_onoff" id="api_onoff">
			<option'.($api_on == 0 ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['APIStatus0'].'</option>
			<option'.($api_on == 1 ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['APIStatus1'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip67'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="api_cache">'.$hc_lang_settings['APICache'].'</label>
		<select'.(($api_on != 1) ? ' disabled="disabled"':'').' name="api_cache" id="api_cache" onchange="togCache(this);">
			<option'.($api_cache == 0 ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['APICache0'].'</option>
			<option'.($api_cache == 1 ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['APICache1'].'</option>
			'.$apc_option.'
		</select>
		<div id="apc_settings" style="clear:both;'.(($api_cache != 2) ? ' display:none;':'').'">
			<label for="apc_size">'.$hc_lang_settings['APCUserSize'].'</label>
			<input'.(($api_on != 1) ? ' disabled="disabled"':'').' name="apc_size" id="apc_size" type="number" type="number" min="1" max="99" size="3" maxlength="2" value="'.$api_user_limit.'" required="required" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip70'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="apc_time">'.$hc_lang_settings['APCUserTime'].'</label>
			<input'.(($api_on != 1) ? ' disabled="disabled"':'').' name="apc_time" id="apc_time" type="number" type="number" min="1" max="99" size="3" maxlength="2" value="'.$api_user_time.'" required="required" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip71'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<label for="event_size">'.$hc_lang_settings['APIEventSize'].'</label>
		<input'.(($api_on != 1) ? ' disabled="disabled"':'').' name="event_size" id="event_size" type="number" type="number" min="1" max="99" size="3" maxlength="2" value="'.$api_event_size.'" required="required" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip68'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="news_size">'.$hc_lang_settings['APINewsSize'].'</label>
		<input'.(($api_on != 1) ? ' disabled="disabled"':'').' name="news_size" id="news_size" type="number" type="number" min="1" max="99" size="3" maxlength="2" value="'.$api_news_size.'" required="required" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip69'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<input type="submit" name="submit" id="submit" value=" '.$hc_lang_settings['SaveSettings'].' " />
	</form>
	
	<script>
	//<!--
	function validate(){
		var err = "";
		
		if(!document.getElementById("apc_size").disabled){
			err += reqField(document.getElementById("apc_size"),"'.$hc_lang_settings['Valid89'].'\n");
			if(document.getElementById("apc_size").value != ""){
				err +=validNumber(document.getElementById("apc_size"),"'.$hc_lang_settings['Valid90'].'\n")
				err +=validGreater(document.getElementById("apc_size"),0,"'.$hc_lang_settings['Valid91'].'\n");
			}
		}
		if(!document.getElementById("apc_time").disabled){
			err += reqField(document.getElementById("apc_time"),"'.$hc_lang_settings['Valid92'].'\n");
			if(document.getElementById("apc_time").value != ""){
				err +=validNumber(document.getElementById("apc_time"),"'.$hc_lang_settings['Valid93'].'\n")
				err +=validGreater(document.getElementById("apc_time"),0,"'.$hc_lang_settings['Valid94'].'\n");
			}
		}
		err += reqField(document.getElementById("event_size"),"'.$hc_lang_settings['Valid95'].'\n");
		if(document.getElementById("event_size").value != ""){
			err +=validNumber(document.getElementById("event_size"),"'.$hc_lang_settings['Valid96'].'\n")
			err +=validGreater(document.getElementById("event_size"),0,"'.$hc_lang_settings['Valid97'].'\n");
		}
		err += reqField(document.getElementById("news_size"),"'.$hc_lang_settings['Valid98'].'\n");
		if(document.getElementById("news_size").value != ""){
			err +=validNumber(document.getElementById("news_size"),"'.$hc_lang_settings['Valid99'].'\n")
			err +=validGreater(document.getElementById("news_size"),0,"'.$hc_lang_settings['Valid100'].'\n");
		}

		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	function togCache(){
		var obj = document.getElementById("api_cache");
		var inpts = (obj.options[obj.selectedIndex].value == 2) ? false : true;
		
		document.getElementById("apc_settings").style.display = (inpts) ? "none":"block";
		document.getElementById("apc_stats").style.display = (inpts) ? "none":"block";
		document.getElementById("apc_size").disabled = inpts;
		document.getElementById("apc_time").disabled = inpts;
	}
	//-->
	</script>';
?>