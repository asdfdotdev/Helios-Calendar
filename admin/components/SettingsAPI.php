<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/settings.php');

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed04']);
				break;
		}
	}
	
	appInstructions(0, "APIs", $hc_lang_settings['TitleAPI'], $hc_lang_settings['InstructAPI']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,25,27,36,37,38,39,41,42,43,45,46,47,50,52,56,57,58,59,60,61,62,63,69) ORDER BY PkID");
	$eventbriteKeyA = cOut(mysql_result($result,0,0));
	$eventbriteKeyU = cOut(mysql_result($result,1,0));
	$disqusName = cOut(mysql_result($result,2,0));
	$emapZoom = cOut(mysql_result($result,3,0));
	$eventfulKey = cOut(mysql_result($result,4,0));
	$eventfulUser = cOut(mysql_result($result,5,0));
	$eventfulPass = base64_decode(cOut(mysql_result($result,6,0)));
	$eventfulSig = cOut(mysql_result($result,7,0));
	$lmapZoom = cOut(mysql_result($result,8,0));
	$locBrowse = cOut(mysql_result($result,11,0));
	$twtrAToken = cOut(mysql_result($result,12,0));
	$twtrASecret = cOut(mysql_result($result,13,0));
	$quickLinks = explode(",", cOut(mysql_result($result,14,0)));
	$googMapURL = cOut(mysql_result($result,15,0));
	$useComments = cOut(mysql_result($result,16,0));
	$bitlyUser = cOut(mysql_result($result,17,0));
     $bitlyAPI = cOut(mysql_result($result,18,0));
	$tweetHash = cOut(mysql_result($result,19,0));
	$eventbriteOrgN = cOut(mysql_result($result,20,0));
	$eventbriteOrgD = cOut(mysql_result($result,21,0));
	$eventbriteOrgID = cOut(mysql_result($result,22,0));
	$twtrUsername = cOut(mysql_result($result,23,0));
	$lmapLat = ($locBrowse > 0) ? cOut(mysql_result($result,9,0)) : '';
	$lmapLon = ($locBrowse > 0) ? cOut(mysql_result($result,10,0)) : '';
	$mapSyndication = ($locBrowse > 0) ? cOut(mysql_result($result,24,0)) : 0;
	$_SESSION['RequestToken'] = $_SESSION['RequestSecret'] = $passOutput = '';
	
	for($x=0;$x<strlen($eventfulPass);$x++)
		$passOutput .= '*';
	
	echo '
	<form name="frmSettings" id="frmSettings" method="post" action="'.AdminRoot.'/components/SettingsAPIAction.php" onsubmit="return validate();">
	<fieldset>
		<legend>'.$hc_lang_settings['GoogleMaps'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['GoogleNotice'].' <a href="http://code.google.com/apis/maps/terms.html" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label for="googMapURL">'.$hc_lang_settings['GoogMapURL'].'</label>
		<input name="googMapURL" id="googMapURL" type="url" size="30" maxlength="100" required="required" value="'.$googMapURL.'" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip22'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="emapZoom">'.$hc_lang_settings['EventMapZoom'].'</label>
		<select name="emapZoom" id="emapZoom">
			<option'.(($emapZoom == 1) ? ' selected="selected"':'').' value="1">01 - '.$hc_lang_settings['World'].'</option>
			<option'.(($emapZoom == 2) ? ' selected="selected"':'').' value="2">02</option>
			<option'.(($emapZoom == 3) ? ' selected="selected"':'').' value="3">03 - '.$hc_lang_settings['Country'].'</option>
			<option'.(($emapZoom == 4) ? ' selected="selected"':'').' value="4">04</option>
			<option'.(($emapZoom == 5) ? ' selected="selected"':'').' value="5">05 - '.$hc_lang_config['RegionTitle'].'</option>
			<option'.(($emapZoom == 6) ? ' selected="selected"':'').' value="6">06</option>
			<option'.(($emapZoom == 7) ? ' selected="selected"':'').' value="7">07</option>
			<option'.(($emapZoom == 8) ? ' selected="selected"':'').' value="8">08</option>
			<option'.(($emapZoom == 9) ? ' selected="selected"':'').' value="9">09</option>
			<option'.(($emapZoom == 10) ? ' selected="selected"':'').' value="10">10 - '.$hc_lang_settings['City'].'</option>
			<option'.(($emapZoom == 11) ? ' selected="selected"':'').' value="11">11</option>
			<option'.(($emapZoom == 12) ? ' selected="selected"':'').' value="12">12</option>
			<option'.(($emapZoom == 13) ? ' selected="selected"':'').' value="13">13</option>
			<option'.(($emapZoom == 14) ? ' selected="selected"':'').' value="14">14</option>
			<option'.(($emapZoom == 15) ? ' selected="selected"':'').' value="15">15 - '.$hc_lang_settings['Street'].'</option>
		</select>
		<label for="locBrowse">'.$hc_lang_settings['LocBrowse'].'</label>
		<select name="locBrowse" id="locBrowse" onchange="togLocBrowse();">
			<option'.(($locBrowse == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['LocBrowse1'].'</option>
			<option'.(($locBrowse == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['LocBrowse0'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip17'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="mapSyndication">'.$hc_lang_settings['Syndication'].'</label>
		<select'.(($locBrowse == 0) ? ' disabled="disabled"':'').' name="mapSyndication" id="mapSyndication">
			<option'.(($mapSyndication == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['Syndication1'].'</option>
			<option'.(($mapSyndication == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['Syndication0'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip37'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="lmapZoom">'.$hc_lang_settings['LocMapZoom'].'</label>
		<select'.(($locBrowse == 0) ? ' disabled="disabled"':'').' name="lmapZoom" id="lmapZoom">
			<option'.(($lmapZoom == 1) ? ' selected="selected"':'').' value="1">01 - '.$hc_lang_settings['World'].'</option>
			<option'.(($lmapZoom == 2) ? ' selected="selected"':'').' value="2">02</option>
			<option'.(($lmapZoom == 3) ? ' selected="selected"':'').' value="3">03 - '.$hc_lang_settings['Country'].'</option>
			<option'.(($lmapZoom == 4) ? ' selected="selected"':'').' value="4">04</option>
			<option'.(($lmapZoom == 5) ? ' selected="selected"':'').' value="5">05 - '.$hc_lang_config['RegionTitle'].'</option>
			<option'.(($lmapZoom == 6) ? ' selected="selected"':'').' value="6">06</option>
			<option'.(($lmapZoom == 7) ? ' selected="selected"':'').' value="7">07</option>
			<option'.(($lmapZoom == 8) ? ' selected="selected"':'').' value="8">08</option>
			<option'.(($lmapZoom == 9) ? ' selected="selected"':'').' value="9">09</option>
			<option'.(($lmapZoom == 10) ? ' selected="selected"':'').' value="10">10 - '.$hc_lang_settings['City'].'</option>
			<option'.(($lmapZoom == 11) ? ' selected="selected"':'').' value="11">11</option>
			<option'.(($lmapZoom == 12) ? ' selected="selected"':'').' value="12">12</option>
			<option'.(($lmapZoom == 13) ? ' selected="selected"':'').' value="13">13</option>
			<option'.(($lmapZoom == 14) ? ' selected="selected"':'').' value="14">14</option>
			<option'.(($lmapZoom == 15) ? ' selected="selected"':'').' value="15">15 - '.$hc_lang_settings['Street'].'</option>
		</select>
		<label>'.$hc_lang_settings['LocMapCenter'].'</label>
		<span class="frm_ctrls_map">
			<label for="lmapLat">'.$hc_lang_settings['Latitude'].'<input'.(($locBrowse == 0) ? ' disabled="disabled"':'').' name="lmapLat" id="lmapLat" type="text" size="12" maxlength="25" value="'.$lmapLat.'"  /></label>
			<label for="lmapLon">'.$hc_lang_settings['Longitude'].'<input'.(($locBrowse == 0) ? ' disabled="disabled"':'').' name="lmapLon" id="lmapLon" type="text" size="12" maxlength="25" value="'.$lmapLon.'" /></label>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Comments'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['DisqusNotice'].' <a href="http://docs.disqus.com/kb/terms-and-policies/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_1"><input'.((in_array(1, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_1" type="checkbox" value="1" />'.$hc_lang_settings['ApiQuickLink1'].'</label>
		</span>
		<label for="useComments">'.$hc_lang_settings['UseComments'].'</label>
		<select name="useComments" id="useComments">
			<option'.(($useComments == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['UseComments0'].'</option>
			<option'.(($useComments == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['UseComments1'].'</option>
		</select>
		<label for="disqusName">'.$hc_lang_settings['DisqusName'].'</label>
		<input name="disqusName" id="disqusName" type="text" size="15" maxlength="50" value="'.$disqusName.'" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip25'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Eventful'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['EventfulNotice'].' <a href="http://api.eventful.com/terms" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_3"><input'.((in_array(3, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_3" type="checkbox" value="3" />'.$hc_lang_settings['ApiQuickLink3'].'</label>
		</span>
		<label for="eventfulAPI">'.$hc_lang_settings['EventfulKey'].' (<a href="http://api.eventful.com/keys/" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="eventfulAPI" id="eventfulAPI" type="text" value="'.$eventfulKey.'" size="45" maxlength="255" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip18'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="efUser">'.$hc_lang_settings['Username'].'</label>
		<input name="efUser" id="efUser" type="text" value="'.$eventfulUser.'" size="20" maxlength="150" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip19'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_settings['Password'].'</label>
		<span class="output">
			'.$passOutput.'
		</span>
		<label for="efPass">'.$hc_lang_settings['NewPassword'].'</label>
		<input name="efPass" id="efPass" type="password" size="15" maxlength="30" value="" />
		<label for="efPass2">'.$hc_lang_settings['ConfirmPassword'].'</label>
		<input name="efPass2" id="efPass2" type="password" size="15" maxlength="30" value="" />
		<label for="efSignature">'.$hc_lang_settings['Signature'].'</label>
		<textarea name="efSignature" id="efSignature" style="width:320px;height:45px;" rows="15" cols="55">'.$eventfulSig.'</textarea>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip20'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Eventbrite'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['EventbriteNotice'].' <a href="http://www.eventbrite.com/tos" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_4"><input'.((in_array(4, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_4" type="checkbox" value="4" />'.$hc_lang_settings['ApiQuickLink4'].'</label>
		</span>
		<label for="eventbriteKeyA">'.$hc_lang_settings['EventbriteKeyA'].' (<a href="https://www.eventbrite.com/r/helios" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="eventbriteKeyA" id="eventbriteKeyA" type="text" value="'.$eventbriteKeyA.'" size="45" maxlength="255" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip29'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="eventbriteKeyU">'.$hc_lang_settings['EventbriteKeyU'].':</label>
		<input name="eventbriteKeyU" id="eventbriteKeyU" type="text" value="'.$eventbriteKeyU.'" size="45" maxlength="255" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip30'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<input type="hidden" name="ebOrgID" id="ebOrgID" value="'.$eventbriteOrgID.'" />
		<label>'.$hc_lang_settings['EventbriteOrgID'].'</label>
		<span class="output">
			<a href="http://www.eventbrite.com/org/'.$eventbriteOrgID.'" target="_blank">'.$eventbriteOrgID.'</a>
		</span>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip31'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="ebOrgName">'.$hc_lang_settings['EventbriteOrgN'].'</label>
		<input'.(($eventbriteKeyA == '' || $eventbriteKeyU = '') ? ' disabled="disabled"':'').' name="ebOrgName" id="ebOrgName" type="text" value="'.$eventbriteOrgN.'" size="15" maxlength="30" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip32'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="ebOrgDesc">'.$hc_lang_settings['EventbriteOrgD'].'</label>
		<textarea'.(($eventbriteKeyA == '' || $eventbriteKeyU = '') ? ' disabled="disabled"':'').' name="ebOrgDesc" id="ebOrgDesc" style="width:320px;height:45px;" rows="15" cols="55">'.$eventbriteOrgD.'</textarea>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip33'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['TwitterLabel'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['TwitterNotice'].' <a href="http://twitter.com/tos" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_2"><input'.((in_array(2, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_2" type="checkbox" value="2" />'.$hc_lang_settings['ApiQuickLink2'].'</label>
		</span>';
	
	if($twtrAToken == '' || $twtrASecret == ''){
		include(HCPATH.HCINC.'/api/twitter/TokenRequest.php');

		if($_SESSION['RequestToken'] != '' && $_SESSION['RequestSecret'] != ''){
			echo '
		<label>&nbsp;</label>
		<span class="output">
			<a href="https://twitter.com/oauth/authorize?oauth_token='.$_SESSION['RequestToken'].'" target="_blank"><img src="'.AdminRoot.'/img/logos/twitter_sign_in.png" width="151" height="24" /></a>
		</span>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['TwitterInst'].'
		</span>
		<label>' . $hc_lang_settings['TwitterPin'] . '</label>
		<input name="twitterpin" id="twitterpin" type="text" value="" />
		';
		} else {
			echo '
		<span class="output">
			<b>' . $hc_lang_settings['TwitterTokenFail'] . '</b>
		</span>';
		}
	} else {
		echo '
		<label>'.$hc_lang_settings['TwitterUser'].'</label>
		<span class="output">
			<a href="http://twitter.com/'.$twtrUsername.'" class="twitter" target="_blank"><img src="'.AdminRoot.'/img/logos/twitter_mini.png" width="16" height="16" alt="" /> '.$twtrUsername.'</a>
		</span>
		<label>'.$hc_lang_settings['TwitterRevoke'].'</label>
		<span class="frm_ctrls">
			<label for="twtrRevoke"><input name="twtrRevoke" id="twtrRevoke" type="checkbox" />' . $hc_lang_settings['TwitterDoRevoke'] . '</label>
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip21'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
	}

	echo '
		<label for="tweetHash">'.$hc_lang_settings['TweetHash'].'</label>
		<input name="tweetHash" id="tweetHash" type="text" value="'.$tweetHash.'" size="15" maxlength="16" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip28'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['bitlyLabel'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['bitlyNotice'].' <a href="http://bitly.net/pages/terms-of-service/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_5"><input'.((in_array(5, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_5" type="checkbox" value="5" />'.$hc_lang_settings['ApiQuickLink5'].'</label>
		</span>
		<label for="bitlyUser">'.$hc_lang_settings['bitlyUsername'].'</label>
		<input name="bitlyUser" id="bitlyUser" type="text" value="'.$bitlyUser.'" size="25" maxlength="50" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip26'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="bitlyAPI">'.$hc_lang_settings['bitlyAPI'].'</label>
		<input name="bitlyAPI" id="bitlyAPI" type="text" value="'.$bitlyAPI.'" size="45" maxlength="150" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip27'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<input type="submit" name="submit" id="submit" value=" '.$hc_lang_settings['SaveSettings'].' " />
	</form>
	
	<script>
	//<!--
	function validate(){
		var err = "";
		var obj = document.getElementById("locBrowse");

		if(obj.options[obj.selectedIndex].value != 0){
			err +=reqField(document.getElementById("lmapLat"),"'.$hc_lang_settings['Valid16'].'\n");
			err +=validNumber(document.getElementById("lmapLat"),"'.$hc_lang_settings['Valid16'].'\n");
			err +=reqField(document.getElementById("lmapLon"),"'.$hc_lang_settings['Valid17'].'\n");
			err +=validNumber(document.getElementById("lmapLon"),"'.$hc_lang_settings['Valid17'].'\n");
		}
		if(document.getElementById("useComments").selectedIndex > 0)
			err +=reqField(document.getElementById("disqusName"),"'.$hc_lang_settings['Valid29'].'\n");
		if(document.getElementById("efUser").value != "" && (0 == '.strlen($eventfulPass).'))
			err +=reqField(document.getElementById("efPass"),"'.$hc_lang_settings['Valid18'].'\n");
		if(document.getElementById("efPass").value != "")
			err += validEqual(document.getElementById("efPass"),document.getElementById("efPass2"),"'.$hc_lang_settings['Valid19'].'\n");
		if(document.getElementById("ebOrgID").value != "")
			err +=reqField(document.getElementById("ebOrgName"),"'.$hc_lang_settings['Valid55'].'\n");

		if(document.getElementById("bitlyUser").value != "" || document.getElementById("bitlyAPI").value != ""){
			err +=reqField(document.getElementById("bitlyUser"),"'.$hc_lang_settings['Valid63'].'\n");
			err +=reqField(document.getElementById("bitlyAPI"),"'.$hc_lang_settings['Valid63'].'\n");
		}
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			return true;
		}
	}
	function togLocBrowse(){
		var obj = document.getElementById("locBrowse");
		var inpts = (obj.options[obj.selectedIndex].value != 0) ? false : true;
		
		document.getElementById("mapSyndication").disabled = inpts;
		document.getElementById("lmapZoom").disabled = inpts;
		document.getElementById("lmapLat").disabled = inpts;
		document.getElementById("lmapLon").disabled = inpts;
	}
	//-->
	</script>';
?>