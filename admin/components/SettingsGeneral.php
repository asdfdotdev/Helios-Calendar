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
				feedback(1, $hc_lang_settings['Feed01']);
				break;
			case "2" :
				feedback(2, $hc_lang_settings['Feed05']);
				break;
		}
	}
	
	appInstructions(0, "Preferences", $hc_lang_settings['TitleSettings'], $hc_lang_settings['InstructSettings']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings
					WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,26,28,29,30,31,32,33,34,35,40,44,48,
								53,54,60,65,66,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82,87,88,89,90,91,92,93,
								106,107,108,109,126)
					ORDER BY PkID");
	$token = set_form_token(1);
	$submit = cOut(mysql_result($result,0,0));
	$rssMax = cOut(mysql_result($result,1,0));
	$passApprove = cOut(mysql_result($result,2,0));
	$passDecline = cOut(mysql_result($result,3,0));
	$driving = cOut(mysql_result($result,4,0));
	$weather = cOut(mysql_result($result,5,0));
	$mostPopular = cOut(mysql_result($result,6,0));
	$browsePast = cOut(mysql_result($result,7,0));
	$maxShow = cOut(mysql_result($result,8,0));
	$fillMax = cOut(mysql_result($result,9,0));
	$dateFormat = cOut(mysql_result($result,10,0));
	$showTime = cOut(mysql_result($result,11,0));
	$state = cOut(mysql_result($result,12,0));
	$calStartDay = cOut(mysql_result($result,13,0));
	$timeFormat = cOut(mysql_result($result,14,0));
	$popDateFormat = cOut(mysql_result($result,15,0));
	$passAge = cOut(mysql_result($result,16,0));
	$langType = cOut(mysql_result($result,17,0));
	$userCat = cOut(mysql_result($result,18,0));
	$WYSIWYG = cOut(mysql_result($result,19,0));
	$timeInput = cOut(mysql_result($result,20,0));
	$captchas = explode(",", cOut(mysql_result($result,21,0)));
	$series = cOut(mysql_result($result,22,0));
	$browseType = cOut(mysql_result($result,23,0));
	$timezoneOffset = cOut(mysql_result($result,24,0));
	$subLimit = cOut(mysql_result($result,25,0));
	$stats = cOut(mysql_result($result,26,0));
	$float = cOut(mysql_result($result,27,0));
	$searchWindow = cOut(mysql_result($result,28,0)) + 1;
	$pubNews = cOut(mysql_result($result,29,0));
	$jssMax = cOut(mysql_result($result,30,0));
	$capType = cOut(mysql_result($result,31,0));
	$maxNew = cOut(mysql_result($result,32,0));
	$reCapPub = cOut(mysql_result($result,33,0));
	$reCapPriv = cOut(mysql_result($result,34,0));
	$locSelect = cOut(mysql_result($result,35,0));
	$mailMethod = cOut(mysql_result($result,36,0));
	$mailHost = cOut(mysql_result($result,37,0));
	$mailPort = cOut(mysql_result($result,38,0));
	$mailSecure = cOut(mysql_result($result,39,0));
	$mailUser = cOut(mysql_result($result,40,0));
	$mailPass = base64_decode(cOut(mysql_result($result,41,0)));
	$mailAuth = cOut(mysql_result($result,42,0));
	$mailAddress = cOut(mysql_result($result,43,0));
	$mailName = cOut(mysql_result($result,44,0));
	$loginTries = cOut(mysql_result($result,45,0));
	$batchSize = cOut(mysql_result($result,46,0));
	$batchDelay = cOut(mysql_result($result,47,0));
	$sitemap = cOut(mysql_result($result,48,0));
	$iCalMax = cOut(mysql_result($result,49,0));
	$iCalRef = cOut(mysql_result($result,50,0));
	$reCapStyle = cOut(mysql_result($result,51,0));
	$passStr = cOut(mysql_result($result,52,0));
	$mc_select = cOut(mysql_result($result,53,0));
	$mc_dow = cOut(mysql_result($result,54,0));
	$rssStatus = cOut(mysql_result($result,55,0));
	$rssTrunc = cOut(mysql_result($result,56,0));
	$iCalStatus = cOut(mysql_result($result,57,0));
	$iCalTrunc = cOut(mysql_result($result,58,0));
	$sendPast = cOut(mysql_result($result,59,0));
	$mActive = ($mailMethod == 0) ? ' disabled="disabled"' : '';
	$cActive = (!function_exists('imagecreate')) ? ' disabled="disabled"' : '';
	$passOutput = $timeOpts = '';
	
	for($x=0;$x<strlen($mailPass);$x++)
		$passOutput .= '*';
	
	for($x=-23;$x<24;$x++)
		$timeOpts .= '<option'.(($timezoneOffset == $x) ? ' selected="selected"':'').' value="'.$x.'">'.(($x > 0) ? '+':'').(($x == 0) ? $hc_lang_settings['ServerTime'] : $x.' '.$hc_lang_settings['hours']).'</option>';
	
	echo '
	<form name="frmSettings" id="frmSettings" method="post" action="'.AdminRoot.'/components/SettingsGeneralAction.php" onsubmit="return validate();">
	<input type="hidden" name="token" id="token" value="'.$token.'" />
	<fieldset>
		<legend>'.$hc_lang_settings['General'].'</legend>
		<label for="browseType">'.$hc_lang_settings['BrowseType'].'</label>
		<select name="browseType" id="browseType">
			<option'.(($browseType == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['BrowseTypeD'].'</option>
			<option'.(($browseType == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['BrowseTypeW'].'</option>
			<option'.(($browseType == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['BrowseTypeM'].'</option>
		</select>
		<label for="browseFloat">'.$hc_lang_settings['BrowseFloat'].'</label>
		<select name="browseFloat" id="browseFloat">
			<option'.(($float == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['BrowseFloat0'].'</option>
			<option'.(($float == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['BrowseFloat1'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip57'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="browsePast">'.$hc_lang_settings['BrowseLimit'].'</label>
		<select name="browsePast" id="browsePast">
			<option'.(($browsePast == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['BrowseLimitC'].'</option>
			<option'.(($browsePast == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['BrowseLimitA'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip02'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
	
	if($hc_lang_config['AddressRegion'] == 1){
		echo '
		<label for="locState">'.$hc_lang_settings['Default'].' '.$hc_lang_config['RegionLabel'].'</label>';
		include(HCLANG.'/'.$hc_lang_config['RegionFile']);
		
		echo '
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip03'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
	} else {
		echo '<input type="hidden" name="locState" id="locState" value="" />';
	}
		
	echo '
		<label for="calStartDay">'.$hc_lang_settings['MCStart'].'</label>
		<select name="calStartDay" id="calStartDay">
			<option'.(($calStartDay == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['Sunday'].'</option>
			<option'.(($calStartDay == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['Monday'].'</option>
			<option'.(($calStartDay == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['Tuesday'].'</option>
			<option'.(($calStartDay == 3) ? ' selected="selected"':'').' value="3">'.$hc_lang_settings['Wednesday'].'</option>
			<option'.(($calStartDay == 4) ? ' selected="selected"':'').' value="4">'.$hc_lang_settings['Thursday'].'</option>
			<option'.(($calStartDay == 5) ? ' selected="selected"':'').' value="5">'.$hc_lang_settings['Friday'].'</option>
			<option'.(($calStartDay == 6) ? ' selected="selected"':'').' value="6">'.$hc_lang_settings['Saturday'].'</option>
		</select>
		<label for="WYSIWYG">'.$hc_lang_settings['WYSIWYG'].'</label>
		<select name="WYSIWYG" id="WYSIWYG">
			<option'.(($WYSIWYG == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['WYSIWYG3'].'</option>
			<option'.(($WYSIWYG == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['WYSIWYG4'].'</option>
			<option'.(($WYSIWYG == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['WYSIWYGN'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip04'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="locSelect">'.$hc_lang_settings['LocSelect'].'</label>
		<select name="locSelect" id="locSelect">
			<option'.(($locSelect == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['LocSelect1'].'</option>
			<option'.(($locSelect == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['LocSelect0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip39'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="searchWindow">'.$hc_lang_settings['SearchWindow'].'</label>
		<input name="searchWindow" id="searchWindow" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$searchWindow.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip34'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="">'.$hc_lang_settings['SendToFriend'].'</label>
		<select name="sendPast" id="sendPast">
			<option'.(($sendPast == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['SendToFriend1'].'</option>
			<option'.(($sendPast == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['SendToFriend0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip33'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>

	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Admin'].'</legend>
		<label for="passAge">'.$hc_lang_settings['PasswordAge'].'</label>
		<input name="passAge" id="passAge" type="number" min="0" max="999" size="4" maxlength="3" required="required" value="'.$passAge.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip50'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="loginTries">'.$hc_lang_settings['LoginAttempts'].'</label>
		<input name="loginTries" id="loginTries" type="number" min="1" max="10" size="3" maxlength="2" required="required" value="'.$loginTries.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip40'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="passStr">'.$hc_lang_settings['PassStr'].'</label>
		<select name="passStr" id="passStr">
			<option'.(($passStr == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['PassStr1'].'</option>
			<option'.(($passStr == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['PassStr0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip06'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="stats">'.$hc_lang_settings['Report'].'</label>
		<select name="stats" id="stats">
			<option'.(($stats == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['ReportY'].'</option>
			<option'.(($stats == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['ReportN'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip05'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Emails'].'</legend>
		<label for="mailAddress">'.$hc_lang_settings['MailAddress'].'</label>
		<input name="mailAddress" id="mailAddress" type="email" size="45" maxlength="200" required="required" value="'.$mailAddress.'" />
		<label for="mailName">'.$hc_lang_settings['MailName'].'</label>
		<input name="mailName" id="mailName" type="text" size="25" maxlength="100" required="required" value="'.$mailName.'" />
		<label for="mailMethod">'.$hc_lang_settings['MailMethod'].'</label>
		<select name="mailMethod" id="mailMethod" onchange="togEmail();">
			<option'.(($mailMethod == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['MailMethod0'].'</option>
			<option'.(($mailMethod == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['MailMethod1'].'</option>
		</select>
		<div id="phpMailer" style="'.(($mailMethod == 0) ? 'display:none;' : '').'">
			<label for="mailHost">'.$hc_lang_settings['MailHost'].'</label>
			<input'.$mActive.' name="mailHost" id="mailHost" type="text" size="35" maxlength="100" value="'.$mailHost.'" />
			<label for="mailPort">'.$hc_lang_settings['MailPort'].'</label>
			<input'.$mActive.' name="mailPort" id="mailPort" type="text" size="6" maxlength="5" value="'.$mailPort.'" />
			<label for="mailSecure">'.$hc_lang_settings['MailSecure'].'</label>
			<select'.$mActive.' name="mailSecure" id="mailSecure">
				<option'.(($mailSecure == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['MailSecure0'].'</option>
				<option'.(($mailSecure == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['MailSecure1'].'</option>
				<option'.(($mailSecure == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['MailSecure2'].'</option>
			</select>
			<label for="mailAuth">'.$hc_lang_settings['MailAuth'].'</label>
			<select'.$mActive.' name="mailAuth" id="mailAuth">
				<option'.(($mailAuth == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['MailAuth0'].'</option>
				<option'.(($mailAuth == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['MailAuth1'].'</option>
			</select>
			<label for="mailUser">'.$hc_lang_settings['MailUser'].'</label>
			<input'.$mActive.' name="mailUser" id="mailUser" type="text" size="25" maxlength="200" value="'.$mailUser.'" />
			<label>'.$hc_lang_settings['MailPass'].'</label>
			<span class="output">'.$passOutput.'</span>
			<label for="mailPassChg">'.$hc_lang_settings['MailPassNew'].'</label>
			<input'.$mActive.' name="mailPassChg" id="mailPassChg" type="password" size="15" maxlength="50" value="" autocomplete="off" />
			<label for="mailPassCon">'.$hc_lang_settings['MailPassCon'].'</label>
			<input'.$mActive.' name="mailPassCon" id="mailPassCon" type="password" size="15" maxlength="50" value="" autocomplete="off" />
		</div>
		<label for="mailTest">'.$hc_lang_settings['MailTest'].'</label>
		<input name="mailTest" id="mailTest" type="email" size="25" maxlength="200" value="" />
		<input name="mailtest_go" id="mailtest_go" type="button" value="'.$hc_lang_settings['MailTestGo'].'" onclick="sendTestMsg();" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip43'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Newsletter'].'</legend>
		<label for="pubNews">'.$hc_lang_settings['PublicNews'].'</label>
		<select name="pubNews" id="pubNews">
			<option'.(($pubNews == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['PublicNews0'].'</option>
			<option'.(($pubNews == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['PublicNews1'].'</option>
		</select>
		<label for="batchSize">'.$hc_lang_settings['BatchSize'].'</label>
		<input name="batchSize" id="batchSize" type="number" min="1" max="99" size="3" maxlength="3" required="required" value="'.$batchSize.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip41'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="batchDelay">'.$hc_lang_settings['BatchDelay'].'</label>
		<input name="batchDelay" id="batchDelay" type="number" min="1" max="99" size="3" maxlength="3" required="required" value="'.$batchDelay.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip42'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['LanguageSettings'].'</legend>
		<label for="langType">'.$hc_lang_settings['Language'].'</label>
		<select name="langType" id="langType">';
	
	if(file_exists(HCPATH.HCINC.'/lang')){
		$dir = dir(HCPATH.HCINC.'/lang');
		while(($file = $dir->read()) != false){
			if(is_dir($dir->path.'/'.$file) && strpos($file,'.') === false && strpos($file,'_') === false)
				$langOpt[] = $file;
		}
		sort($langOpt);
		foreach($langOpt as $val)
			echo '<option'.(($val == $langType) ? ' selected="selected"':'').' value="'.$val.'">'.ucwords($val).'</option>';
			
		echo (count($langOpt) == 0) ? '<option value="">'.$hc_lang_settings['NoLang'].'</option>' : '';
	} else {
		echo '<option>' . $hc_lang_settings['NoLangDir'] . '</option>';
	}
	
	echo '
		</select>
		<label>'.$hc_lang_settings['TinyMCELP'].'</label>
		<span class="output">
			<a href="http://www.tinymce.com/i18n/index.php?ctrl=lang" target="_blank">'.$hc_lang_settings['DownloadHere'].'</a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['CAPTCHA'].'</legend>
		<p>
			'.$hc_lang_settings['reCAPTCHANotice'].'<a href="http://www.google.com/recaptcha/terms" target="_blank">' . $hc_lang_settings['TermsOfUse'] . '</a>.
			<br />' . $hc_lang_settings['NoShare'].'
		</p>
		<label for="capType">' . $hc_lang_settings['CAPTCHAType'] . '</label>
		<select name="capType" id="capType" onchange="togCAPTCHA();">
			<option'.(($capType == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['CAPTCHAType0'].'</option>
			'.(($cActive == '') ? '<option'.(($capType == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['CAPTCHAType1'].'</option>':'').'
			<option'.(($capType == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['CAPTCHAType2'].'</option>
		</select>&nbsp;(<a href="http://www.google.com/recaptcha" target="_blank">' . $hc_lang_settings['reCAPTCHALink'] . '</a>)
		<div id="reCAPTCHA"'.(($capType != 2) ? ' style="display:none;"':'').'>
			<label for="reCapPub">' . $hc_lang_settings['reCAPTCHAPubKey'] . '</label>
			<input name="reCapPub" id="reCapPub" type="text" size="60" maxlength="150" value="' . $reCapPub . '" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip35'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="reCapPriv">' . $hc_lang_settings['reCAPTCHAPrivKey'] . '</label>
			<input name="reCapPriv" id="reCapPriv" type="text" size="60" maxlength="150" value="' . $reCapPriv . '" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip36'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="reCapStyle">'.$hc_lang_settings['reCAPTCHAStyle'].'</label>
			<select name="reCapStyle" id="reCapStyle">
				<option'.(($reCapStyle == 'red') ? ' selected="selected"':'').' value="red">'.$hc_lang_settings['reCAPTCHAStyle0'].'</option>
				<option'.(($reCapStyle == 'white') ? ' selected="selected"':'').' value="white">'.$hc_lang_settings['reCAPTCHAStyle1'].'</option>
				<option'.(($reCapStyle == 'blackglass') ? ' selected="selected"':'').' value="blackglass">'.$hc_lang_settings['reCAPTCHAStyle2'].'</option>
				<option'.(($reCapStyle == 'clean') ? ' selected="selected"':'').' value="clean">'.$hc_lang_settings['reCAPTCHAStyle3'].'</option>
			</select>
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip01'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<label>'.$hc_lang_settings['ActiveCAPTCHA'].'</label>
		<span class="frm_ctrls" style="margin-right:40px;">
			<label for="capID_1"><input'.$cActive.((in_array(1, $captchas)) ? ' checked="checked"':'').' name="capID[]" id="capID_1" type="checkbox" value="1" />'.$hc_lang_settings['ActiveEventSubmit'].'</label>
			<label for="capID_2"><input'.$cActive.((in_array(2, $captchas)) ? ' checked="checked"':'').' name="capID[]" id="capID_2" type="checkbox" value="2" />'.$hc_lang_settings['ActiveEmailFriend'].'</label>
		</span>
		<span class="frm_ctrls">
			<label for="capID_3"><input'.$cActive.((in_array(3, $captchas)) ? ' checked="checked"':'').' name="capID[]" id="capID_3" type="checkbox" value="3" />'.$hc_lang_settings['ActiveRegister'].'</label>
			<label for="capID_4"><input'.$cActive.((in_array(4, $captchas)) ? ' checked="checked"':'').' name="capID[]" id="capID_4" type="checkbox" value="4" />'.$hc_lang_settings['ActiveNewsletter'].'</label>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['PublicEventSub'].'</legend>
		<label for="allowsubmit">'.$hc_lang_settings['SubSetting'].'</label>
		<select name="allowsubmit" id="allowsubmit" onchange="toggleMe(document.getElementById(\'pubsub\'));togSubmit();">
			<option'.(($submit == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['SubSettingON'].'</option>
			<option'.(($submit == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['SubSettingOFF'].'</option>
		</select>
		<div id="pubsub"'.(($submit == 0) ? 'style="display:none;"':'').'>
			<label for="subLimit">'.$hc_lang_settings['SessionLimit'].'</label>
			<input'.(($submit == 0) ? ' disabled="disabled"':'').' name="subLimit" id="subLimit" type="number" min="0" max="999" size="4" maxlength="3" required="required" value="'.$subLimit.'" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip07'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="pubCat">'.$hc_lang_settings['PublicCategories'].'</label>
			<select'.(($submit == 0) ? ' disabled="disabled"':'').' name="pubCat" id="pubCat">
				<option'.(($userCat == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['PublicCategories0'].'</option>
				<option'.(($userCat == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['PublicCategories1'].'</option>
			</select>
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip08'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Lists'].'</legend>
		<label for="display">'.$hc_lang_settings['BillboardSize'].'</label>
		<input name="display" id="display" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$maxShow.'" />
		<label for="mostPopular">'.$hc_lang_settings['PopularSize'].'</label>
		<input name="mostPopular" id="mostPopular" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$mostPopular.'" />
		<label for="maxNew">'.$hc_lang_settings['MaxNewSize'].'</label>
		<input name="maxNew" id="maxNew" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$maxNew.'" />
		<label for="fill">'.$hc_lang_settings['AutoFill'].'</label>
		<select name="fill" id="fill">
			<option'.(($fillMax == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['AutoFill1'].'</option>
			<option'.(($fillMax == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['AutoFill0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip09'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="series">'.$hc_lang_settings['EventSeries'].'</label>
		<select name="series" id="series">
			<option'.(($series == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['EventSeries1'].'</option>
			<option'.(($series == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['EventSeries0'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip10'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_settings['StartTime'].'</label>
		<span class="frm_ctrls">
			<label for="showtime"><input'.(($showTime == 1) ? ' checked="checked"':'').' name="showtime" id="showtime" type="checkbox" value="" />'.$hc_lang_settings['StartTimeLabel'].'</label>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['iCalRSS'].'</legend>
		<label for="jssMax">'.$hc_lang_settings['JSSize'].'</label>
		<input name="jssMax" id="jssMax" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$jssMax.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip62'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="rssStatus">'.$hc_lang_settings['RSSStatus'].'</label>
		<select name="rssStatus" id="rssStatus" onchange="toggleMe(document.getElementById(\'rss\'));togRSS();">
			<option'.(($rssStatus == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['RSSStatus0'].'</option>
			<option'.(($rssStatus == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['RSSStatus1'].'</option>
		</select>
		<div id="rss"'.(($rssStatus == 0) ? ' style="display:none;"':'').'>
			<label for="maxRSS">'.$hc_lang_settings['RSSSize'].'</label>
			<input name="maxRSS" id="maxRSS" type="number" min="1" max="99" size="3" maxlength="2" required="required" value="'.$rssMax.'" />
			<label for="rssTrunc">'.$hc_lang_settings['RSSTrunc'].'</label>
			<input name="rssTrunc" id="rssTrunc" type="number" min="0" max="999" size="4" maxlength="3" required="required" value="'.$rssTrunc.'" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip31'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<label for="iCalStatus">'.$hc_lang_settings['iCalStatus'].'</label>
		<select name="iCalStatus" id="iCalStatus" onchange="toggleMe(document.getElementById(\'ical\'));togRSS();">
			<option'.(($iCalStatus == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['iCalStatus0'].'</option>
			<option'.(($iCalStatus == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['iCalStatus1'].'</option>
		</select>
		<div id="ical"'.(($iCalStatus == 0) ? ' style="display:none;"':'').'>
			<label for="iCalMax">'.$hc_lang_settings['iCalMax'].'</label>
			<input name="iCalMax" id="iCalMax" type="number" min="1" max="150" size="4" maxlength="3" required="required" value="'.$iCalMax.'" />
			<label for="iCalTrunc">'.$hc_lang_settings['iCalTrunc'].'</label>
			<input name="iCalTrunc" id="iCalTrunc" type="number" min="0" max="999" size="4" maxlength="3" required="required" value="'.$iCalTrunc.'" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip32'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="iCalRef">'.$hc_lang_settings['iCalRef'].'</label>
			<input name="iCalRef" id="iCalRef" type="number" min="60" max="999" size="5" maxlength="3" required="required" value="'.$iCalRef.'" />
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip38'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['DateTime'].' [ <a href="http://www.php.net/strftime" target="_blank">'.$hc_lang_settings['DateTimeLink'].'</a> ]</legend>
		<label>'.$hc_lang_settings['CurServerTime'].'</label>
		<span class="output">
			<b>'.strftime($dateFormat.' '.$timeFormat).'</b>
		</span>
		'.(($timezoneOffset != 0) ? '<label>'.$hc_lang_settings['HeliosTime'].'</label>
		<span class="output">
			<b>'.stampToDate(SYSDATE.' '.SYSTIME, $dateFormat.' '.$timeFormat).'</b>
		</span>':'').'
		<label for="offsetTimezone">'.$hc_lang_settings['OffsetTime'].'</label>
		<select name="offsetTimezone" id="offsetTimezone">
			'.$timeOpts.'
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip11'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="popDateFormat">'.$hc_lang_settings['DateFormatIn'].'</label>
		<select name="popDateFormat" id="popDateFormat">
			<option'.(($popDateFormat == "%m/%d/%Y") ? ' selected="selected"':'').' value="%m/%d/%Y">m/d/y ('.strftime("%m/%d/%Y").')</option>
			<option'.(($popDateFormat == "%d/%m/%Y") ? ' selected="selected"':'').' value="%d/%m/%Y">d/m/y ('.strftime("%d/%m/%Y").')</option>
			<option'.(($popDateFormat == "%Y/%m/%d") ? ' selected="selected"':'').' value="%Y/%m/%d">y/m/d ('.strftime("%Y/%m/%d").')</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip12'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="dateFormat">'.$hc_lang_settings['DateFormatOut'].'</label>
		<input name="dateFormat" id="dateFormat" type="text" size="20" maxlength="25" required="required" value="'.$dateFormat.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip13'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="timeInput">'.$hc_lang_settings['TimeFormatIn'].'</label>
		<select name="timeInput" id="timeInput">
			<option'.(($timeInput == "24") ? ' selected="selected"':'').' value="24">'.$hc_lang_settings['24Hour'].' ('.strftime("%H:%M").')</option>
			<option'.(($timeInput == "12") ? ' selected="selected"':'').' value="12">'.$hc_lang_settings['12Hour'].' ('.strftime("%I:%M %p").')</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip14'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="timeFormat">'.$hc_lang_settings['TimeFormatOut'].'</label>
		<input name="timeFormat" id="timeFormat" type="text" size="10" maxlength="20" required="required" value="'.$timeFormat.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip15'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="mc_select">'.$hc_lang_settings['MiniCalSelect'].'</label>
		<input name="mc_select" id="mc_select" type="text" size="10" maxlength="20" required="required" value="'.$mc_select.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip55'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="mc_dow">'.$hc_lang_settings['MiniCalDay'].'</label>
		<input name="mc_dow" id="mc_dow" type="text" size="10" maxlength="20" required="required" value="'.$mc_dow.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip56'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['WMLinks'].'</legend>
		<label for="weather">'.$hc_lang_settings['WeatherLink'].'</label>
		<input name="weather" id="weather" type="url" size="80" maxlength="250" required="required" value="'.$weather.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip23'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="driving">'.$hc_lang_settings['MapLink'].'</label>
		<input name="driving" id="driving" type="url" size="80" maxlength="250" required="required" value = "'.$driving.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip24'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset class="standard">
		<legend>'.$hc_lang_settings['PendingMsg'].'</legend>

		<label>'.$hc_lang_settings['VariableLabel'].'</label>
		<span class="output">
			<a id="tempLink" href="javascript:;" onclick="togVar(\'tempVars\', \'tempLink\');">'.$hc_lang_settings['ShowVariables'].'</a>
		</span>
		<div id="tempVars" style="display:none;width:75%;">
			<h5>'.$hc_lang_settings['VarLabelE'].'</h5>
			<p>
				<span><a class="tooltip" data-tip="[event] - '.$hc_lang_settings['Tip51'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event]</span>
				<span><a class="tooltip" data-tip="[facebook] - '.$hc_lang_settings['Tip52'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[facebook]</span>
				<span><a class="tooltip" data-tip="[twitter] - '.$hc_lang_settings['Tip53'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[twitter]</span>
			</p>
		</div>
		<label for="defaultApprove">'.$hc_lang_settings['AppMessage'].'</label>
		<textarea name="defaultApprove" id="defaultApprove" rows="15" class="mce_edit">'.$passApprove.'</textarea>
		<label for="defaultDecline">'.$hc_lang_settings['DecMessage'].'</label>
		<textarea name="defaultDecline" id="defaultDecline" rows="15" class="mce_edit">'.$passDecline.'</textarea>
	</fieldset>
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_settings['SaveSettings'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function togVar(doTog, doLink){if(document.getElementById("tempVars").style.display == "none"){document.getElementById("tempVars").style.display = "block";document.getElementById("tempLink").innerHTML = "'.$hc_lang_settings['HideVariables'].'";} else {document.getElementById("tempVars").style.display = "none";document.getElementById("tempLink").innerHTML = "'.$hc_lang_settings['ShowVariables'].'";}}
	function validate(){
		var err = "";
		
		err +=reqField(document.getElementById("passAge"),"'.$hc_lang_settings['Valid58'].'\n");
		if(document.getElementById("passAge").value != ""){
			err +=validNumber(document.getElementById("passAge"),"'.$hc_lang_settings['Valid59'].'\n")
			err +=validGreater(document.getElementById("passAge"),-1,"'.$hc_lang_settings['Valid60'].'\n");
		}
		err +=reqField(document.getElementById("loginTries"),"'.$hc_lang_settings['Valid42'].'\n");
		if(document.getElementById("loginTries").value != ""){
			err +=validNumber(document.getElementById("loginTries"),"'.$hc_lang_settings['Valid43'].'\n")
			err +=validGreater(document.getElementById("loginTries"),-1,"'.$hc_lang_settings['Valid44'].'\n");
		}
		err +=reqField(document.getElementById("searchWindow"),"'.$hc_lang_settings['Valid30'].'\n");
		if(document.getElementById("searchWindow").value != ""){
			err +=validNumber(document.getElementById("searchWindow"),"'.$hc_lang_settings['Valid31'].'\n")
			err +=validGreater(document.getElementById("searchWindow"),-1,"'.$hc_lang_settings['Valid32'].'\n");
		}
		err +=reqField(document.getElementById("batchSize"),"'.$hc_lang_settings['Valid45'].'\n");
		if(document.getElementById("batchSize").value != ""){
			err +=validNumber(document.getElementById("batchSize"),"'.$hc_lang_settings['Valid46'].'\n")
			err +=validGreater(document.getElementById("batchSize"),-1,"'.$hc_lang_settings['Valid47'].'\n");
		}
		err +=reqField(document.getElementById("batchDelay"),"'.$hc_lang_settings['Valid48'].'\n");
		if(document.getElementById("batchDelay").value != ""){
			err +=validNumber(document.getElementById("batchDelay"),"'.$hc_lang_settings['Valid49'].'\n")
			err +=validGreater(document.getElementById("batchDelay"),-1,"'.$hc_lang_settings['Valid50'].'\n");
		}
		err +=reqField(document.getElementById("mailAddress"),"'.$hc_lang_settings['Valid39'].'\n");
		if(document.getElementById("mailAddress").value != "")
			err +=validEmail(document.getElementById("mailAddress"),"'.$hc_lang_settings['Valid40'].'\n");
		err +=reqField(document.getElementById("mailName"),"'.$hc_lang_settings['Valid41'].'\n");
		if(document.getElementById("mailMethod").value == 1){
			err +=reqField(document.getElementById("mailHost"),"'.$hc_lang_settings['Valid34'].'\n");
			err +=reqField(document.getElementById("mailPort"),"'.$hc_lang_settings['Valid35'].'\n");
			if(document.getElementById("mailPort").value != "")
				err +=validNumber(document.getElementById("mailPort"),"'.$hc_lang_settings['Valid64'].'\n")
		}
		if(document.getElementById("mailAuth").value == 1){
			err +=reqField(document.getElementById("mailUser"),"'.$hc_lang_settings['Valid36'].'\n");

			if(document.getElementById("mailPassChg").value == "" && '.strlen($mailPass).' == 0)
				err += "'.$hc_lang_settings['Valid37'].'\n";
			
			if(document.getElementById("mailPassChg").value != "" && document.getElementById("mailPassChg").value != document.getElementById("mailPassCon").value)
				err += "'.$hc_lang_settings['Valid38'].'\n";
		}
		if(document.getElementById("capType").selectedIndex == 2){
			err +=reqField(document.getElementById("reCapPub"),"'.$hc_lang_settings['Valid27'].'\n");
			err +=reqField(document.getElementById("reCapPriv"),"'.$hc_lang_settings['Valid28'].'\n");
		}
		err +=reqField(document.getElementById("subLimit"),"'.$hc_lang_settings['Valid02'].'\n");
		if(document.getElementById("loginTries").value != ""){
			err +=validNumber(document.getElementById("subLimit"),"'.$hc_lang_settings['Valid03'].'\n")
			err +=validGreater(document.getElementById("subLimit"),-1,"'.$hc_lang_settings['Valid04'].'\n");
		}
		err +=reqField(document.getElementById("display"),"'.$hc_lang_settings['Valid11'].'\n");
		if(document.getElementById("display").value != ""){
			err +=validNumber(document.getElementById("display"),"'.$hc_lang_settings['Valid12'].'\n")
			err +=validGreater(document.getElementById("display"),0,"'.$hc_lang_settings['Valid13'].'\n");
		}
		err +=reqField(document.getElementById("mostPopular"),"'.$hc_lang_settings['Valid08'].'\n");
		if(document.getElementById("mostPopular").value != ""){
			err +=validNumber(document.getElementById("mostPopular"),"'.$hc_lang_settings['Valid09'].'\n")
			err +=validGreater(document.getElementById("mostPopular"),0,"'.$hc_lang_settings['Valid10'].'\n");
		}
		err +=reqField(document.getElementById("maxNew"),"'.$hc_lang_settings['Valid51'].'\n");
		if(document.getElementById("maxNew").value != ""){
			err +=validNumber(document.getElementById("maxNew"),"'.$hc_lang_settings['Valid52'].'\n");
			err +=validGreater(document.getElementById("maxNew"),0,"'.$hc_lang_settings['Valid53'].'\n");
		}

		err +=reqField(document.getElementById("jssMax"),"'.$hc_lang_settings['Valid86'].'\n");
		if(document.getElementById("jssMax").value != ""){
			err +=validNumber(document.getElementById("jssMax"),"'.$hc_lang_settings['Valid87'].'\n")
			err +=validGreater(document.getElementById("jssMax"),0,"'.$hc_lang_settings['Valid88'].'\n");
		}

		if(document.getElementById("rssStatus").value == 1){
			err +=reqField(document.getElementById("maxRSS"),"'.$hc_lang_settings['Valid05'].'\n");
			if(document.getElementById("maxRSS").value != ""){
				err +=validNumber(document.getElementById("maxRSS"),"'.$hc_lang_settings['Valid06'].'\n")
				err +=validGreater(document.getElementById("maxRSS"),0,"'.$hc_lang_settings['Valid07'].'\n");
			}
			err +=reqField(document.getElementById("rssTrunc"),"'.$hc_lang_settings['Valid74'].'\n");
			if(document.getElementById("rssTrunc").value != ""){
				err +=validNumber(document.getElementById("rssTrunc"),"'.$hc_lang_settings['Valid75'].'\n")
				err +=validGreater(document.getElementById("rssTrunc"),-1,"'.$hc_lang_settings['Valid76'].'\n");
			}
		}
		if(document.getElementById("iCalStatus").value == 1){
			err +=reqField(document.getElementById("iCalMax"),"'.$hc_lang_settings['Valid77'].'\n");
			if(document.getElementById("iCalMax").value != ""){
				err +=validNumber(document.getElementById("iCalMax"),"'.$hc_lang_settings['Valid78'].'\n")
				err +=validGreater(document.getElementById("iCalMax"),0,"'.$hc_lang_settings['Valid79'].'\n");
			}
			err +=reqField(document.getElementById("iCalTrunc"),"'.$hc_lang_settings['Valid80'].'\n");
			if(document.getElementById("iCalTrunc").value != ""){
				err +=validNumber(document.getElementById("iCalTrunc"),"'.$hc_lang_settings['Valid81'].'\n")
				err +=validGreater(document.getElementById("iCalTrunc"),-1,"'.$hc_lang_settings['Valid82'].'\n");
			}
			err +=reqField(document.getElementById("iCalRef"),"'.$hc_lang_settings['Valid83'].'\n");
			if(document.getElementById("iCalRef").value != ""){
				err +=validNumber(document.getElementById("iCalRef"),"'.$hc_lang_settings['Valid84'].'\n")
				err +=validGreater(document.getElementById("iCalRef"),59,"'.$hc_lang_settings['Valid85'].'\n");
			}
		}
		err +=reqField(document.getElementById("dateFormat"),"'.$hc_lang_settings['Valid14'].'\n");
		err +=reqField(document.getElementById("timeFormat"),"'.$hc_lang_settings['Valid15'].'\n");
		err +=reqField(document.getElementById("mc_select"),"'.$hc_lang_settings['Valid61'].'\n");
		err +=reqField(document.getElementById("mc_dow"),"'.$hc_lang_settings['Valid62'].'\n");
		
		try{
			err +=chkTinyMCE(tinyMCE.get("defaultApprove").getContent(),"'.$hc_lang_settings['Valid20'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("defaultApprove"),"'.$hc_lang_settings['Valid20'].'\n");}
		try{
			err +=chkTinyMCE(tinyMCE.get("defaultDecline").getContent(),"'.$hc_lang_settings['Valid21'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("defaultDecline"),"'.$hc_lang_settings['Valid21'].'\n");}
		
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	function togSubmit(){
		var obj = document.getElementById("allowsubmit");
		var inpts = (obj.options[obj.selectedIndex].value != 0) ? false : true;
		document.getElementById("pubCat").disabled = inpts;
		document.getElementById("subLimit").disabled = inpts;
	}
	function togEmail(){
		var obj = document.getElementById("mailMethod");
		var inpts = (obj.options[obj.selectedIndex].value != 0) ? false : true;
		var disp = (obj.options[obj.selectedIndex].value != 0) ? "block" : "none";
		
		document.getElementById("phpMailer").style.display = disp;
		document.getElementById("mailHost").disabled = inpts;
		document.getElementById("mailPort").disabled = inpts;
		document.getElementById("mailSecure").disabled = inpts;
		document.getElementById("mailAuth").disabled = inpts;
		document.getElementById("mailUser").disabled = inpts;
		document.getElementById("mailPassChg").disabled = inpts;
		document.getElementById("mailPassCon").disabled = inpts;
	}
	function togCAPTCHA(){
		showReCap = (document.getElementById("capType").selectedIndex == 2) ? "block" : "none";
		allowCap = (document.getElementById("capType").selectedIndex == 0) ? true : false;

		document.getElementById("reCAPTCHA").style.display = showReCap;
		for(x = 1;x <= 4;x++)
			document.getElementById("capID_" + x).disabled = allowCap;
	}
	
	function togRSS(){
		var obj = document.getElementById("rssStatus");
		var inpts = (obj.options[obj.selectedIndex].value != 0) ? false : true;
		document.getElementById("maxRSS").disabled = inpts;
		document.getElementById("rssTrunc").disabled = inpts;
	}
	function togiCal(){
		var obj = document.getElementById("icalStatus");
		var inpts = (obj.options[obj.selectedIndex].value != 0) ? false : true;
		document.getElementById("iCalMax").disabled = inpts;
		document.getElementById("iCalTrunc").disabled = inpts;
		document.getElementById("iCalRef").disabled = inpts;
	}
	function sendTestMsg(){
		if(confirm("'.$hc_lang_settings['Valid33'].'")){
			var err = "";
			err +=validEmail(document.getElementById("mailTest"),"'.$hc_lang_settings['Valid54'].'\n");
			if(err != ""){
				alert(err);
				return false;
			} else {
				window.open("'.AdminRoot.'/components/EmailTest.php?tkn='.$token.'&e=" + document.getElementById("mailTest").value,"hc_mailtest","location=1,status=1,scrollbars=1,width=800,height=300,left="+(screen.availWidth/2-400)+",top="+(screen.availHeight/2-300));
			}
		} else {
			return false;
		}
	}
	//-->
	</script>';
	
	makeTinyMCE('82%',1,0,'defaultApprove,defaultDecline');
?>