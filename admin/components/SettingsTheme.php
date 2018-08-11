<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/settings.php');

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed03']);
				break;
		}
	}
	
	appInstructions(0, "Themes_Settings", $hc_lang_settings['TitleTheme'], $hc_lang_settings['InstructTheme']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (83,84,86) ORDER BY PkID");
	$fullsite = cOut(mysql_result($result,0,0));
	$mobile = cOut(mysql_result($result,1,0));
	$agents = cOut(mysql_result($result,2,0));
	$fullsiteOpts = $mobileOpts = '';
	
	$themes = array();
	if(file_exists(HCPATH.'/themes')){
		$dir = dir(HCPATH.'/themes');
		while(($file = $dir->read()) != false){
			if(is_dir($dir->path.'/'.$file) && $file != "." && $file != "..")
				$themes[] = $file;
		}
	}
	
	sort($themes);
	
	foreach($themes as $val){
		$fullsiteOpts .= '
			<option'.(($val == $fullsite) ? ' selected="selected"':'').' value="'.$val.'">'.ucfirst($val).'</option>';
		$mobileOpts .= '
			<option'.(($val == $mobile) ? ' selected="selected"':'').' value="'.$val.'">'.ucfirst($val).'</option>';
	}
	
	echo '
	<form name="frmSettings" id="frmSettings" method="post" action="'.AdminRoot.'/components/SettingsThemeAction.php">';
	set_form_token();
	echo '
	<fieldset>
		<legend>'.$hc_lang_settings['ThemesSettings'].'</legend>
		<label for="fullsite">'.$hc_lang_settings['FullSite'].'</label>
		<select name="fullsite" id="fullsite">
			'.$fullsiteOpts.'
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip47'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="mobile">'.$hc_lang_settings['Mobile'].'</label>
		<select name="mobile" id="mobile">
			'.$mobileOpts.'
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip48'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="agents">'.$hc_lang_settings['UserAgents'].'</label>
		<input name="agents" id="agents" type="test" size="75" maxlength="500" required="required" value="'.$agents.'" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip49'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Themes'].'</legend>
		<p>'.$hc_lang_settings['ThemeNotice'].'</p>
		<div class="themes">';
	
	foreach($themes as $val){
		$theme = $name = $version = $author = $link = $description = '';
		$theme_data = $theme_data_items = array();
		$style = HCPATH.'/themes/'.$val.'/css/style.css';
		if(is_file($style)){
			$fp = fopen($style, 'r');
			$content = fread($fp, filesize($style));
			fclose($fp);
			
			preg_match_all('/@[A-Z|a-z|0-9|.|http:\/\/|\\s|.,\'!@#$%^&()]*/i', $content, $theme_data);
			foreach($theme_data[0] as $data)
				$theme_data_items[] = preg_split('/ /',$data, 2);
			
			foreach($theme_data_items as $item){
				switch($item[0]){
					case '@name':
						$name = (trim($item[1]) != '') ? $item[1] : '';
						break;
					case '@version':
						$version = (trim($item[1]) != '') ? $item[1] : '';
						break;
					case '@author':
						$author = (trim($item[1]) != '') ? $item[1] : '';
						break;
					case '@link':
						$link = (trim($item[1]) != '') ? $item[1] : '';
						break;
					case '@description':
						$description = (trim($item[1]) != '') ? $item[1] : '';
						break;
				}
			}
			echo '
			<a href="'.CalRoot.'/?theme='.$val.'" target="_blank"><img src="'.CalRoot.(file_exists(HCPATH.'/themes/'.$val.'/preview.png') ? '/themes/'.$val.'/preview.png':'/img/nopreview.png').'" width="250" height="175" alt="" /></a>
			<div>
				<h5>'.$name.'</h5>
				<ul>
					<li><b>'.$hc_lang_settings['ThemeA'].'</b> '.(($link != '') ? '<a href="'.$link.'" target="_blank">'.$author.'</a></li>' : $author).'
					<li><b>'.$hc_lang_settings['ThemeV'].'</b> '.$version.'</li>
					<li><b>'.$hc_lang_settings['ThemeF'].'</b> /themes/'.$val.'/</li>
					<li>'.$description.'</li>
				</ul>
			</div>';
		}
	}
	echo '
		</div>
	</fieldset>
	<input type="submit" name="submit" id="submit" value=" '.$hc_lang_settings['SaveSettings'].' " />
	</form>';
?>