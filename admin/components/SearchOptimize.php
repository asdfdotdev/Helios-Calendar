<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/settings.php');
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (7,85,87,134) ORDER BY PkID");
	$allowindex = cOut(mysql_result($result,0,0));
	$bots = cOut(mysql_result($result,1,0));
	$sitemap = cOut(mysql_result($result,2,0));
	$expire_days = cOut(mysql_result($result,3,0));
	$cnt = 1;
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_settings['Feed02']);
				break;
		}
	}
	
	appInstructions(0, "Search_Optimization", $hc_lang_settings['TitleSearch'], $hc_lang_settings['InstructSearch']);
	
	echo '
	<form name="frm" id="frm" method="post" action="'.AdminRoot.'/components/SearchOptimizeAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<fieldset style="border:0;">
		<label for="indexing">' . $hc_lang_settings['Indexing'] . '</label>
		<select name="indexing" id="indexing">
			<option'.(($allowindex == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['Indexing1'].'</option>
			<option'.(($allowindex == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['Indexing0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip44'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="expires">'.$hc_lang_settings['Expires'].'</label>
		<input name="expires" id="expires" type="number" min="1" max="999" size="4" maxlength="3" required="required" value="'.$expire_days.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip73'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="sitemap">'.$hc_lang_settings['Sitemap'].'</label>
		<input name="sitemap" id="sitemap" type="number" min="1" max="9999" size="5" maxlength="4" required="required" value="'.$sitemap.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip45'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="bots">'.$hc_lang_settings['Bots'].'</label>
		<input name="bots" id="bots" type="text" size="80" maxlength="300" value="'.$bots.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip46'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>';

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settingsmeta ORDER BY Sort");
	while($row = mysql_fetch_row($result)){
		echo '
	<fieldset>
		<legend>'.$hc_lang_settings['Page'.$row[0]].'</legend>
		<input type="hidden" name="ids[]" value="'.$row[0].'" />
		<label for="pgtitle'.$row[0].'">'.$hc_lang_settings['Title'].'</label>
		<input name="titles[]" id="pgtitle'.$row[0].'" size="65" maxlength="65" type="text" value="'.cOut($row[3]).'" />
		<label for="keywords'.$row[0].'">'.$hc_lang_settings['Keywords'].'</label>
		<input name="keywords[]" id="keywords'.$row[0].'" size="65" maxlength="75" type="text" value="'.cOut($row[1]).'" />
		<label for="description'.$row[0].'">'.$hc_lang_settings['Description'].'</label>
		<input name="descriptions[]" id="description'.$row[0].'" size="80" maxlength="160" type="text" value="'.cOut($row[2]).'" />	
	</fieldset>';
		++$cnt;
	}
	
	echo '
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_settings['SaveMeta'].'" />
	</form>
	
	<script>
	//<!--
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("sitemap"),"'.$hc_lang_settings['Valid56'].'\n");
		err +=validNumber(document.getElementById("expires"),"'.$hc_lang_settings['Valid101'].'\n");
		err +=validNumber(document.getElementById("sitemap"),"'.$hc_lang_settings['Valid57'].'\n");
		
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