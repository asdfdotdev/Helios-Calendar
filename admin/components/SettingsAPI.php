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
			case "2":
				feedback(1, $hc_lang_settings['Feed06']);
				break;
			case "3":
				feedback(3, $hc_lang_settings['Feed07']);
				break;
			case "4":
				feedback(1, $hc_lang_settings['Feed08']);
				break;
			case "5":
				feedback(3, $hc_lang_settings['Feed09']);
				break;
		}
	}
	
	appInstructions(0, "APIs", $hc_lang_settings['TitleAPI'], $hc_lang_settings['InstructAPI']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings 
					WHERE PkID IN (5,6,25,27,41,42,43,45,46,47,50,52,55,56,57,58,59,61,62,63,69,94,95,96,
								100,101,102,103,104,105,111,112,113,114,115,117,118,119,120,121,122,123,124,125)
					ORDER BY PkID");
	$eventbriteKeyA = cOut(mysql_result($result,0,0));
	$eventbriteKeyU = cOut(mysql_result($result,1,0));
	$disqusName = cOut(mysql_result($result,2,0));
	$emapZoom = cOut(mysql_result($result,3,0));
	$lmapZoom = cOut(mysql_result($result,4,0));
	$locBrowse = cOut(mysql_result($result,7,0));
	$twtrAToken = cOut(mysql_result($result,8,0));
	$twtrASecret = cOut(mysql_result($result,9,0));
	$quickLinks = explode(",", cOut(mysql_result($result,10,0)));
	$googMapURL = cOut(mysql_result($result,11,0));
	$mapLibrary = cOut(mysql_result($result,12,0));
	$useComments = cOut(mysql_result($result,13,0));
	$bitlyUser = cOut(mysql_result($result,14,0));
     $bitlyAPI = cOut(mysql_result($result,15,0));
	$tweetHash = cOut(mysql_result($result,16,0));
	$googMapVer = cOut(mysql_result($result,17,0));
	$eventbriteOrgID = cOut(mysql_result($result,18,0));
	$twtrUsername = cOut(mysql_result($result,19,0));
	$lmapLat = ($locBrowse > 0) ? cOut(mysql_result($result,5,0)) : '';
	$lmapLon = ($locBrowse > 0) ? cOut(mysql_result($result,6,0)) : '';
	$mapSyndication = ($locBrowse > 0) ? cOut(mysql_result($result,20,0)) : 0;
	$mapLayer = cOut(mysql_result($result,21,0));
	$yahooAPI = cOut(mysql_result($result,22,0));
	$bingAPI = cOut(mysql_result($result,23,0));
	$fbcPosts = cOut(mysql_result($result,24,0));
	$fbcWidth = cOut(mysql_result($result,25,0));
	$livefyreID = cOut(mysql_result($result,26,0));
	$eventbritePaypal = cOut(mysql_result($result,27,0));
	$eventbriteGoogleID = cOut(mysql_result($result,28,0));
	$eventbriteGoogleKey = cOut(mysql_result($result,29,0));
	$twtrComKey = cOut(mysql_result($result,30,0));
	$twtrComSec = cOut(mysql_result($result,31,0));
	$twtrSignIn = cOut(mysql_result($result,32,0));
	$fbSignIn = cOut(mysql_result($result,33,0));
	$googSignIn = cOut(mysql_result($result,34,0));
	$fbAppID = cOut(mysql_result($result,35,0));
	$fbAppSec = cOut(mysql_result($result,36,0));	
	$fbConOpts = json_decode(cOut(mysql_result($result,37,0)),true);
	$fbActiveT = cOut(mysql_result($result,38,0));
	$fbTokenU = cOut(mysql_result($result,39,0));
	$fbExpires = cOut(mysql_result($result,40,0));
	$fbActiveI = cOut(mysql_result($result,41,0));
	$googID = cOut(mysql_result($result,42,0));
	$googSec = cOut(mysql_result($result,43,0));
	$fbConOptsOut = '';
	
	echo '
	<form name="frmSettings" id="frmSettings" method="post" action="'.AdminRoot.'/components/SettingsAPIAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<fieldset>
		<legend>'.$hc_lang_settings['Maps'].'</legend>
		<label for="mapLibrary">'.$hc_lang_settings['Library'].'</label>
		<select name="mapLibrary" id="mapLibrary" onchange="togMapLibrary(this.options[this.selectedIndex].value);">
			<option'.(($mapLibrary == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['Library1'].'</option>
			<option'.(($mapLibrary == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['Library2'].'</option>
		</select>
		<div id="library1" style="clear:both;'.(($mapLibrary != 1) ? ' display:none;':'').'">
			<label for="googMapURL">'.$hc_lang_settings['GoogMapURL'].'</label>
			<input'.(($mapLibrary != 1) ? ' disabled="disabled"':'').' name="googMapURL" id="googMapURL" type="url" size="30" maxlength="100" required="required" value="'.$googMapURL.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip22'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="googMapVer">'.$hc_lang_settings['GoogMapVer'].'</label>
			<input'.(($mapLibrary != 1) ? ' disabled="disabled"':'').' name="googMapVer" id="googMapVer" type="text" size="5" maxlength="5" required="required" value="'.$googMapVer.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip18'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<div id="library2" style="clear:both;'.(($mapLibrary != 2) ? ' display:none;':'').'">
			<label for="mapLayer">'.$hc_lang_settings['Layer'].'</label>
			<select name="mapLayer" id="mapLayer" onchange="togMapLayer(this.options[this.selectedIndex].value);">
				<option'.(($mapLayer == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['Layer2'].'</option>
				<option'.(($mapLayer == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['Layer1'].'</option>
				<option'.(($mapLayer == 4) ? ' selected="selected"':'').' value="4">'.$hc_lang_settings['Layer4'].'</option>
				<option'.(($mapLayer == 3) ? ' selected="selected"':'').' value="3">'.$hc_lang_settings['Layer3'].'</option>
			</select>
			<div id="bing" style="clear:both;'.(($mapLayer != 2) ? ' display:none;':'').'">
				<label>&nbsp;</label>
				<span class="output">
					'.$hc_lang_settings['NeedKey'].' (<a href="http://bingmapsportal.com/" target="_blank">'.$hc_lang_settings['SignUp'].'</a>)
				</span>
				<label for="bingAPI">'.$hc_lang_settings['BingAPI'].'</label>
				<input'.(($mapLayer != 2) ? ' disabled="disabled"':'').' name="bingAPI" id="bingAPI" type="text" size="30" maxlength="500" required="required" value="'.$bingAPI.'" />
				<span class="frm_ctrls">
					<a class="tooltip" data-tip="'.$hc_lang_settings['Tip58'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
				</span>
			</div>
			<div id="yahoo" style="clear:both;'.(($mapLayer != 3) ? ' display:none;':'').'">
				<label>&nbsp;</label>
				<span class="output">
					'.$hc_lang_settings['NeedKey'].' (<a href="https://developer.yahoo.com/wsregapp/" target="_blank">'.$hc_lang_settings['SignUp'].'</a>)
				</span>
				<label for="yahooAPI">'.$hc_lang_settings['YahooAPI'].'</label>
				<input'.(($mapLayer != 3) ? ' disabled="disabled"':'').' name="yahooAPI" id="yahooAPI" type="text" size="50" maxlength="500" value="'.$yahooAPI.'" />
				<span class="frm_ctrls">
					<a class="tooltip" data-tip="'.$hc_lang_settings['Tip59'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
				</span>
			</div>
		</div>
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
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['BingNotice'].' <a href="http://www.microsoft.com/maps/product/terms.html" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
			'.$hc_lang_settings['GoogleNotice'].' <a href="https://developers.google.com/maps/terms" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
			'.$hc_lang_settings['MapQuestNotice'].' <a href="http://developer.mapquest.com/web/products/open/map#terms" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
			'.$hc_lang_settings['YahooNotice'].' <a href="http://info.yahoo.com/legal/us/yahoo/maps/mapsapi/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Comments'].'</legend>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_1"><input'.((in_array(1, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_1" type="checkbox" value="1" />'.$hc_lang_settings['ApiQuickLink1'].'</label>
		</span>
		<label for="useComments">'.$hc_lang_settings['UseComments'].'</label>
		<select name="useComments" id="useComments" onchange="togComments(this.options[this.selectedIndex].value);">
			<option'.(($useComments == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['UseComments0'].'</option>
			<option'.(($useComments == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['UseComments1'].'</option>
			<option'.(($useComments == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_settings['UseComments2'].'</option>
			<option'.(($useComments == 3) ? ' selected="selected"':'').' value="3">'.$hc_lang_settings['UseComments3'].'</option>
		</select>
		<div id="comments1" style="clear:both;'.(($useComments != 1) ? ' display:none;':'').'">
			<label for="disqusName">'.$hc_lang_settings['DisqusName'].'</label>
			<input'.(($useComments != 1) ? ' disabled="disabled"':'').' name="disqusName" id="disqusName" type="text" size="15" maxlength="50" required="required" value="'.$disqusName.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip25'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<div id="comments2" style="clear:both;'.(($useComments != 2) ? ' display:none;':'').'">
			<label for="fbcPosts">'.$hc_lang_settings['FacebookPosts'].'</label>
			<input'.(($useComments != 2) ? ' disabled="disabled"':'').' name="fbcPosts" id="fbcPosts" type="number" min="1" max="99" size="3" maxlength="2" value="'.$fbcPosts.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip60'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="fbcWidth">'.$hc_lang_settings['FacebookWidth'].'</label>
			<input'.(($useComments != 2) ? ' disabled="disabled"':'').' name="fbcWidth" id="fbcWidth" type="number" min="1" max="9999" size="5" maxlength="4" value="'.$fbcWidth.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip61'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<div id="comments3" style="clear:both;'.(($useComments != 3) ? ' display:none;':'').'">
			<label for="livefyreID">'.$hc_lang_settings['LivefyreID'].'</label>
			<input'.(($useComments != 3) ? ' disabled="disabled"':'').' name="livefyreID" id="livefyreID" type="text" size="15" maxlength="50" required="required" value="'.$livefyreID.'" />
			<span class="frm_ctrls">
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip54'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
		</div>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['DisqusNotice'].' <a href="http://docs.disqus.com/kb/terms-and-policies/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
			'.$hc_lang_settings['FacebookNotice'].' <a href="http://developers.facebook.com/policy/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
			'.$hc_lang_settings['LivefyreNotice'].' <a href="http://www.livefyre.com/terms/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.<br />
		</span>
	</fieldset>
	
	<fieldset>
		<legend>'.$hc_lang_settings['Facebook'].'</legend>';
	
	if(!function_exists('openssl_open')){
		echo '<label>&nbsp;</label>
		<span class="output">'.$hc_lang_settings['NoSSL'].'</span>';
	} else {
		echo '
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_6"><input'.((in_array(6, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_6" type="checkbox" value="6" />'.$hc_lang_settings['ApiQuickLink6'].'</label>
		</span>
		<label for="fbAppID">'.$hc_lang_settings['FacebookAppID'].' (<a href="https://developers.facebook.com/apps" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="fbAppID" id="fbAppID" type="text" size="30" maxlength="250" value="'.$fbAppID.'" />
		<label for="fbAppSec">'.$hc_lang_settings['FacebookAppSec'].'</label>
		<input name="fbAppSec" id="fbAppSec" type="text" size="55" maxlength="250" value="'.$fbAppSec.'" />';

		if($fbAppID != '' && $fbAppSec != ''){
			
			if($fbActiveI != ''){
				foreach($fbConOpts as $page){
					$fbConOptsOut .= '
				<option'.(($fbActiveI == $page['id']) ? ' selected="selected"':'').' value="'.$page['id'].'||'.$page['access_token'].'">'.$page['name'].' - '.$page['category'].' ('.$page['id'].')</option>';
				}
				
				echo '
			<label>'.$hc_lang_settings['FacebookExpires'].'</label>
			<span class="output"><b>'.strftime($hc_cfg[14],$fbExpires).'</b></span>
			<label for="fbConOpts">'.$hc_lang_settings['FacebookConOpts'].'</label>
			<select name="fbConOpts" id="fbConOpts">
			'.$fbConOptsOut.'
			</select>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<label for="fbRevoke"><input name="fbRevoke" id="fbRevoke" type="checkbox" />' . $hc_lang_settings['FacebookDoRevoke'] . '</label>
				<a class="tooltip" data-tip="'.$hc_lang_settings['Tip65'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>';
			}
			
			echo '
		<label for="fbSignIn">'.$hc_lang_settings['FacebookSignOn'].'</label>
		<select name="fbSignIn" id="fbSignIn">
			<option'.(($fbSignIn == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['FacebookSignOn0'].'</option>
			<option'.(($fbSignIn == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['FacebookSignOn1'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip64'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
		}
		
		echo '
		<label>&nbsp;</label>
		<span class="output">
			<a href="'.AdminRoot.'/auth/facebook.php"><img src="'.AdminRoot.'/img/logos/facebook_button.png" width="89" height="21" /></a>
		</span>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['FacebookAPINotice'].' <a href="http://developers.facebook.com/policy/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>';
	}
	
	echo '
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['TwitterLabel'].'</legend>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_2"><input'.((in_array(2, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_2" type="checkbox" value="2" />'.$hc_lang_settings['ApiQuickLink2'].'</label>
		</span>
		<label for="twtrComKey">'.$hc_lang_settings['TwitterConKey'].' (<a href="https://dev.twitter.com/apps" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="twtrComKey" id="twtrComKey" type="text" size="30" maxlength="250" value="'.$twtrComKey.'" />
		<label for="twtrComSec">'.$hc_lang_settings['TwitterConSec'].'</label>
		<input name="twtrComSec" id="twtrComSec" type="text" size="55" maxlength="250" value="'.$twtrComSec.'" />
		<label for="tweetHash">'.$hc_lang_settings['TweetHash'].'</label>
		<input name="tweetHash" id="tweetHash" type="text" value="'.$tweetHash.'" size="15" maxlength="16" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip28'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
	
	if($twtrComKey != '' && $twtrComSec != ''){
		echo '
		<label for="twtrSignIn">'.$hc_lang_settings['TwitterSignOn'].'</label>
		<select name="twtrSignIn" id="twtrSignIn">
			<option'.(($twtrSignIn == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['TwitterSignOn0'].'</option>
			<option'.(($twtrSignIn == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['TwitterSignOn1'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip63'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
		
		if($twtrAToken == '' || $twtrASecret == ''){
			echo '
		<label>'.$hc_lang_settings['TwitterUser'].'</label>
		<span class="output">
			<a href="'.AdminRoot.'/auth/twitter.php"><img src="'.AdminRoot.'/img/logos/twitter_button.png" width="151" height="24" /></a>
		</span>';
		} else {
			echo '
		<label>'.$hc_lang_settings['TwitterUser'].'</label>
		<span class="output">
			<a href="http://twitter.com/'.$twtrUsername.'" class="twitter" target="_blank"><img src="'.AdminRoot.'/img/logos/twitter_icon.png" width="16" height="16" alt="" /> '.$twtrUsername.'</a>
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="twtrRevoke"><input name="twtrRevoke" id="twtrRevoke" type="checkbox" />' . $hc_lang_settings['TwitterDoRevoke'] . '</label>
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip21'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
		}
	}
	echo '
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['TwitterNotice'].' <a href="http://twitter.com/tos" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
	</fieldset>
	
	<fieldset>
		<legend>'.$hc_lang_settings['Google'].'</legend>
		<label for="googID">'.$hc_lang_settings['GoogleCID'].' (<a href="https://code.google.com/apis/console" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="googID" id="googID" type="text" size="55" maxlength="250" value="'.$googID.'" />
		<label for="googSec">'.$hc_lang_settings['GoogleCSecret'].'</label>
		<input name="googSec" id="googSec" type="text" size="35" maxlength="250" value="'.$googSec.'" />';
	
	if($googID != '' && $googSec != ''){
		echo '
		<label for="googSignIn">'.$hc_lang_settings['GoogleSignOn'].'</label>
		<select name="googSignIn" id="googleSignIn">
			<option'.(($googSignIn == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_settings['GoogleSignOn0'].'</option>
			<option'.(($googSignIn == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_settings['GoogleSignOn1'].'</option>
		</select>
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip63'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
	}
	
	echo '
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['GoogNotice'].' <a href="https://developers.google.com/terms/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['Eventbrite'].'</legend>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="quickLinkID_4"><input'.((in_array(4, $quickLinks)) ? ' checked="checked"' : '').' name="quickLinkID[]" id="quickLinkID_4" type="checkbox" value="4" />'.$hc_lang_settings['ApiQuickLink4'].'</label>
		</span>
		<label for="eventbriteKeyA">'.$hc_lang_settings['EventbriteKeyA'].' (<a href="http://www.eventbrite.com/r/apisignup" target="_blank">'.$hc_lang_settings['SignUp'].'</a>):</label>
		<input name="eventbriteKeyA" id="eventbriteKeyA" type="text" value="'.$eventbriteKeyA.'" size="45" maxlength="255" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip29'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="eventbriteKeyU">'.$hc_lang_settings['EventbriteKeyU'].':</label>
		<input name="eventbriteKeyU" id="eventbriteKeyU" type="text" value="'.$eventbriteKeyU.'" size="45" maxlength="255" />
		<span class="frm_ctrls">
			<a class="tooltip" data-tip="'.$hc_lang_settings['Tip30'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>';
		   
	if($eventbriteKeyA != '' && $eventbriteKeyU != ''){
		$ebOrganizers = eventbrite_get_organizers();
		
		echo '
		<label for="ebOrgID">'.$hc_lang_settings['EventbriteOrg'].'</label>
		<select name="ebOrgID" id="ebOrgID">';
		
		foreach($found_organizers as $id => $name){
			echo '
			<option'.(($eventbriteOrgID == $id) ? ' selected="selected"' : '').' value="'.$id.'">'.(($name != '') ? $name : '').' (ID#: '.$id.')</option>';
		}
		echo '
		</select>
		'.(($eventbriteOrgID != '') ? '
		<span class="output">
			&nbsp;<a href="http://www.eventbrite.com/org/'.$eventbriteOrgID.'" target="_blank"><img src="'.AdminRoot.'/img/logos/eventbrite_icon.png" width="16" height="16" alt ="" /></a>
		</span>':'').'
		<label>'.$hc_lang_settings['PayPal'].'</label>
		<input name="eventbritePaypal" id="eventbritePayPal" type="email" size="50" maxlength="250" value="'.$eventbritePaypal.'" />
		<label>'.$hc_lang_settings['GoogleID'].'</label>
		<input name="eventbriteGoogleID" id="eventbriteGoogleID" type="text" size="25" maxlength="250" value="'.$eventbriteGoogleID.'" />
		<label>'.$hc_lang_settings['GoogleKey'].'</label>
		<input name="eventbriteGoogleKey" id="eventbriteGoogleKey" type="text" size="30" maxlength="250" value="'.$eventbriteGoogleKey.'" />
		';
	}
	   
	echo '
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['EventbriteNotice'].' <a href="http://www.eventbrite.com/tos" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_settings['bitlyLabel'].'</legend>
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
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_settings['bitlyNotice'].' <a href="http://bitly.net/pages/terms-of-service/" target="_blank">'.$hc_lang_settings['TermsOfUse'].'</a>.
		</span>
	</fieldset>
	<input type="submit" name="submit" id="submit" value=" '.$hc_lang_settings['SaveSettings'].' " />
	</form>
	
	<script>
	//<!--
	function validate(){
		var err = "";
		var obj = document.getElementById("locBrowse");

		if(!document.getElementById("bingAPI").disabled)
			err += reqField(document.getElementById("bingAPI"),"'.$hc_lang_settings['Valid65'].'\n");
		if(!document.getElementById("yahooAPI").disabled)
			err += reqField(document.getElementById("yahooAPI"),"'.$hc_lang_settings['Valid66'].'\n");

		if(obj.options[obj.selectedIndex].value != 0){
			err += reqField(document.getElementById("lmapLat"),"'.$hc_lang_settings['Valid16'].'\n");
			if(document.getElementById("lmapLat").value != "")
				err +=validNumber(document.getElementById("lmapLat"),"'.$hc_lang_settings['Valid16'].'\n");
			err += reqField(document.getElementById("lmapLon"),"'.$hc_lang_settings['Valid17'].'\n");
			if(document.getElementById("lmapLon").value != "")
				err +=validNumber(document.getElementById("lmapLon"),"'.$hc_lang_settings['Valid17'].'\n");
		}
		if(document.getElementById("useComments").value == 1){
			err += reqField(document.getElementById("disqusName"),"'.$hc_lang_settings['Valid29'].'\n");
		} else if(document.getElementById("useComments").value == 2){
			err += reqField(document.getElementById("fbcPosts"),"'.$hc_lang_settings['Valid67'].'\n");
			if(document.getElementById("fbcPosts").value != ""){
				err +=validNumber(document.getElementById("fbcPosts"),"'.$hc_lang_settings['Valid68'].'\n")
				err +=validGreater(document.getElementById("fbcPosts"),0,"'.$hc_lang_settings['Valid69'].'\n");
			}
			err += reqField(document.getElementById("fbcWidth"),"'.$hc_lang_settings['Valid70'].'\n");
			if(document.getElementById("fbcWidth").value != ""){
				err +=validNumber(document.getElementById("fbcWidth"),"'.$hc_lang_settings['Valid71'].'\n")
				err +=validGreater(document.getElementById("fbcWidth"),0,"'.$hc_lang_settings['Valid72'].'\n");
			}	
		} else if(document.getElementById("useComments").value == 3){
			err += reqField(document.getElementById("livefyreID"),"'.$hc_lang_settings['Valid73'].'\n");
		}
		if(document.getElementById("eventbritePayPal").value != "")
			err +=validEmail(document.getElementById("eventbritePayPal"),"'.$hc_lang_settings['Valid55'].'\n");

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
	function togMapLibrary(active){
		document.getElementById("library1").style.display = (active == 1) ? "block" : "none";
		document.getElementById("library2").style.display = (active == 2) ? "block" : "none";
		document.getElementById("googMapURL").disabled = (active == 1) ? false : true;
		document.getElementById("googMapVer").disabled = (active == 1) ? false : true;
	}
	function togMapLayer(active){
		document.getElementById("bing").style.display = (active == 2) ? "block" : "none";
		document.getElementById("yahoo").style.display = (active == 3) ? "block" : "none";
		document.getElementById("bingAPI").disabled = (active == 2) ? false : true;
		document.getElementById("yahooAPI").disabled = (active == 3) ? false : true;
	}
	function togComments(active){
		document.getElementById("comments1").style.display = (active == 1) ? "block" : "none";
		document.getElementById("comments2").style.display = (active == 2) ? "block" : "none";
		document.getElementById("comments3").style.display = (active == 3)? "block" : "none";
		document.getElementById("disqusName").disabled = (active == 1) ? false : true;
		document.getElementById("fbcPosts").disabled = (active == 2) ? false : true;
		document.getElementById("fbcWidth").disabled = (active == 2) ? false : true;
		document.getElementById("livefyreID").disabled = (active == 3) ? false : true;
	}
	//-->
	</script>';
?>