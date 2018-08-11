<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/settings.php');

	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed04']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "APIs", $hc_lang_settings['TitleAPI'], $hc_lang_settings['InstructAPI']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (5,6,26,27,36,37,38,39,41,42,43,45,46,47,52,57,58,59,60,61,62,63,69) ORDER BY PkID");
	$eventbriteKeyA = cOut(mysql_result($result,0,0));
	$eventbriteKeyU = cOut(mysql_result($result,1,0));
	$googleKey = cOut(mysql_result($result,2,0));
	$emapZoom = cOut(mysql_result($result,3,0));
	$eventfulKey = cOut(mysql_result($result,4,0));
	$eventfulUser = cOut(mysql_result($result,5,0));
	$eventfulPass = cOut(mysql_result($result,6,0));
	$eventfulSig = cOut(mysql_result($result,7,0));
	$lmapZoom = cOut(mysql_result($result,8,0));
	$lmapLat = cOut(mysql_result($result,9,0));
	$lmapLon = cOut(mysql_result($result,10,0));
	$locBrowse = cOut(mysql_result($result,11,0));
	$twtrAToken = cOut(mysql_result($result,12,0));
	$twtrASecret = cOut(mysql_result($result,13,0));
	$googMapURL = cOut(mysql_result($result,14,0));
	$bitlyUser = cOut(mysql_result($result,15,0));
     $bitlyAPI = cOut(mysql_result($result,16,0));
	$tweetHash = cOut(mysql_result($result,17,0));
	$eventbriteOrgN = cOut(mysql_result($result,18,0));
	$eventbriteOrgD = cOut(mysql_result($result,19,0));
	$eventbriteOrgID = cOut(mysql_result($result,20,0));
	$twtrUsername = cOut(mysql_result($result,21,0));
	$mapSyndication = cOut(mysql_result($result,22,0));
?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_settings['Valid01'];?>';
		
		if(isNaN(document.frm.lmapLat.value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid16'];?>';
		}//end if
		
		if(isNaN(document.frm.lmapLon.value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid17'];?>';
		}//end if
		
		if(document.frm.efUser.value != '' && document.frm.efPass.value == '' && (0 == <?php echo strlen($eventfulPass);?>)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid18'];?>';
		}//end if
		
		if(document.frm.efPass.value != ''){
			if(document.frm.efPass.value != document.frm.efPass2.value){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid19'];?>';
			}//end if
		}//end if
		
		if(document.frm.ebOrgID.value != ''){
			if(document.frm.ebOrgName.value != ''){
				dirt = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid31'];?>';
			}//end if
		}//end if

		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_settings['Valid22'];?>');
			return false;
		}//end if
		
	}//end chkFrm()
	
	function togLocBrowse(){
		if(document.getElementById('locBrowse').value == 1){
			document.getElementById('mapSyndication').disabled = false;
			document.getElementById('lmapZoom').disabled = false;
			document.getElementById('lmapLat').disabled = false;
			document.getElementById('lmapLon').disabled = false;
		} else {
			document.getElementById('mapSyndication').disabled = true;
			document.getElementById('lmapZoom').disabled = true;
			document.getElementById('lmapLat').disabled = true;
			document.getElementById('lmapLon').disabled = true;
		}//end if
	}//end togLocBrowse()

	//-->
	</script>
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SettingsAPIAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['GoogleMaps'];?></legend>
		<?php echo $hc_lang_settings['GoogleNotice'];?> <a href="http://code.google.com/apis/maps/terms.html" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><?php echo $hc_lang_settings['NoShare'];?>
		<br /><br />
		<div class="frmOpt">
			<label for="googMapURL" class="settingsLabel"><?php echo $hc_lang_settings['GoogMapURL'];?></label>
			<input name="googMapURL" id="googMapURL" type="text" value = "<?php echo $googMapURL;?>" size="30" maxlength="100" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip22A'],$hc_lang_settings['Tip22B']);?>
		</div>
		<div class="frmOpt">
			<label for="googleAPI" class="settingsLabel"><?php echo $hc_lang_settings['GoogleKey'];?> (<a href="http://code.google.com/apis/maps/signup.html" class="main" target="_blank"><?php echo $hc_lang_settings['SignUp'];?></a>)</label>
			<input name="googleAPI" id="googleAPI" type="text" value="<?php echo $googleKey;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip16A'],$hc_lang_settings['Tip16B']);?>
		</div>
		<div class="frmOpt">
			<label for="emapZoom" class="settingsLabel"><?php echo $hc_lang_settings['EventMapZoom'];?></label>
			<select name="emapZoom" id="emapZoom">
				<option <?php if($emapZoom == 1){echo "selected=\"selected\"";}?> value="1">01 - <?php echo $hc_lang_settings['World'];?></option>
				<option <?php if($emapZoom == 2){echo "selected=\"selected\"";}?> value="2">02</option>
				<option <?php if($emapZoom == 3){echo "selected=\"selected\"";}?> value="3">03 - <?php echo $hc_lang_settings['Country'];?></option>
				<option <?php if($emapZoom == 4){echo "selected=\"selected\"";}?> value="4">04</option>
				<option <?php if($emapZoom == 5){echo "selected=\"selected\"";}?> value="5">05 - <?php echo $hc_lang_config['RegionTitle'];?></option>
				<option <?php if($emapZoom == 6){echo "selected=\"selected\"";}?> value="6">06</option>
				<option <?php if($emapZoom == 7){echo "selected=\"selected\"";}?> value="7">07</option>
				<option <?php if($emapZoom == 8){echo "selected=\"selected\"";}?> value="8">08</option>
				<option <?php if($emapZoom == 9){echo "selected=\"selected\"";}?> value="9">09</option>
				<option <?php if($emapZoom == 10){echo "selected=\"selected\"";}?> value="10">10 - <?php echo $hc_lang_settings['City'];?></option>
				<option <?php if($emapZoom == 11){echo "selected=\"selected\"";}?> value="11">11</option>
				<option <?php if($emapZoom == 12){echo "selected=\"selected\"";}?> value="12">12</option>
				<option <?php if($emapZoom == 13){echo "selected=\"selected\"";}?> value="13">13</option>
				<option <?php if($emapZoom == 14){echo "selected=\"selected\"";}?> value="14">14</option>
				<option <?php if($emapZoom == 15){echo "selected=\"selected\"";}?> value="15">15 - <?php echo $hc_lang_settings['Street'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="locBrowse" class="settingsLabel"><?php echo $hc_lang_settings['LocBrowse'];?></label>
			<select name="locBrowse" id="locBrowse" onchange="togLocBrowse();">
				<option <?php if($locBrowse == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['LocBrowse1'];?></option>
				<option <?php if($locBrowse == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['LocBrowse0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip17A'],$hc_lang_settings['Tip17B']);?>
		</div>
		<div class="frmOpt">
			<label for="mapSyndication" class="settingsLabel"><?php echo $hc_lang_settings['Syndication'];?></label>
			<select name="mapSyndication" id="mapSyndication" onchange="togLocBrowse();" <?php if($locBrowse == 0){echo 'disabled="disabled"';}?>>
				<option <?php if($mapSyndication == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['Syndication1'];?></option>
				<option <?php if($mapSyndication == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['Syndication0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip37A'],$hc_lang_settings['Tip37B']);?>
		</div>
		<div class="frmOpt">
			<label for="lmapZoom" class="settingsLabel"><?php echo $hc_lang_settings['LocMapZoom'];?></label>
			<select name="lmapZoom" id="lmapZoom" <?php if($locBrowse == 0){echo "disabled=\"disabled\"";}?>>
				<option <?php if($lmapZoom == 1){echo "selected=\"selected\"";}?> value="1">01 - <?php echo $hc_lang_settings['World'];?></option>
				<option <?php if($lmapZoom == 2){echo "selected=\"selected\"";}?> value="2">02</option>
				<option <?php if($lmapZoom == 3){echo "selected=\"selected\"";}?> value="3">03 - <?php echo $hc_lang_settings['Country'];?></option>
				<option <?php if($lmapZoom == 4){echo "selected=\"selected\"";}?> value="4">04</option>
				<option <?php if($lmapZoom == 5){echo "selected=\"selected\"";}?> value="5">05 - <?php echo $hc_lang_config['RegionTitle'];?></option>
				<option <?php if($lmapZoom == 6){echo "selected=\"selected\"";}?> value="6">06</option>
				<option <?php if($lmapZoom == 7){echo "selected=\"selected\"";}?> value="7">07</option>
				<option <?php if($lmapZoom == 8){echo "selected=\"selected\"";}?> value="8">08</option>
				<option <?php if($lmapZoom == 9){echo "selected=\"selected\"";}?> value="9">09</option>
				<option <?php if($lmapZoom == 10){echo "selected=\"selected\"";}?> value="10">10 - <?php echo $hc_lang_settings['City'];?></option>
				<option <?php if($lmapZoom == 11){echo "selected=\"selected\"";}?> value="11">11</option>
				<option <?php if($lmapZoom == 12){echo "selected=\"selected\"";}?> value="12">12</option>
				<option <?php if($lmapZoom == 13){echo "selected=\"selected\"";}?> value="13">13</option>
				<option <?php if($lmapZoom == 14){echo "selected=\"selected\"";}?> value="14">14</option>
				<option <?php if($lmapZoom == 15){echo "selected=\"selected\"";}?> value="15">15 - <?php echo $hc_lang_settings['Street'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['LocMapCenter'];?></label>
			<?php echo $hc_lang_settings['Latitude'];?>&nbsp;<input name="lmapLat" id="lmapLat" type="text" value="<?php echo $lmapLat;?>" size="10" maxlength="25" <?php if($locBrowse == 0){echo "disabled=\"disabled\"";}?> />&nbsp;&nbsp;<?php echo $hc_lang_settings['Longitude'];?>&nbsp;<input name="lmapLon" id="lmapLon" type="text" value="<?php echo $lmapLon;?>" size="10" maxlength="25" <?php if($locBrowse == 0){echo "disabled=\"disabled\"";}?> />
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<a href="http://www.google.com/search?q=free+geocoding" class="main" target="_blank"><?php echo $hc_lang_settings['GeocodeLookup'];?></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Eventful'];?></legend>
		<?php echo $hc_lang_settings['EventfulNotice'];?> <a href="http://api.eventful.com/terms" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><?php echo $hc_lang_settings['NoShare'];?>
		<br /><br />
		<div class="frmOpt">
			<label for="eventfulAPI" class="settingsLabel"><?php echo $hc_lang_settings['EventfulKey'];?> (<a href="http://api.eventful.com/keys/" class="main" target="_blank"><?php echo $hc_lang_settings['SignUp'];?></a>):</label>
			<input name="eventfulAPI" id="eventfulAPI" type="text" value="<?php echo $eventfulKey;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip18A'],$hc_lang_settings['Tip18B']);?>
		</div>
		<div class="frmOpt">
			<label for="efUser" class="settingsLabel"><?php echo $hc_lang_settings['Username'];?></label>
			<input name="efUser" id="efUser" type="text" value="<?php echo $eventfulUser;?>" size="20" maxlength="150" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip19A'],$hc_lang_settings['Tip19B']);?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['Password'];?></label>
		<?php
			for($x=0;$x<strlen($eventfulPass);$x++){
				echo "*";
			}//end for?>&nbsp;
		</div>
		<div class="frmOpt">
			<label for="efPass" class="settingsLabel"><?php echo $hc_lang_settings['NewPassword'];?></label>
			<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
		</div>
		<div class="frmOpt">
			<label for="efPass2" class="settingsLabel"><?php echo $hc_lang_settings['ConfirmPassword'];?></label>
			<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
		</div>
		<div class="frmOpt">
			<label for="efSignature" class="settingsLabel"><?php echo $hc_lang_settings['Signature'];?></label>
			<textarea name="efSignature" id="efSignature" style="width:320px;height:45px;" rows="15" cols="55"><?php echo $eventfulSig;?></textarea>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip20A'],$hc_lang_settings['Tip20B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Eventbrite'];?></legend>
		<?php echo $hc_lang_settings['EventbriteNotice'];?> <a href="http://www.eventbrite.com/tos" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><?php echo $hc_lang_settings['NoShare'];?>
		<br /><br />
		<div class="frmOpt">
			<label for="eventbriteKeyA" class="settingsLabel"><?php echo $hc_lang_settings['EventbriteKeyA'];?> (<a href="https://www.eventbrite.com/r/helios" class="main" target="_blank"><?php echo $hc_lang_settings['SignUp'];?></a>):</label>
			<input name="eventbriteKeyA" id="eventbriteKeyA" type="text" value="<?php echo $eventbriteKeyA;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip29A'],$hc_lang_settings['Tip29B']);?>
		</div>
		<div class="frmOpt">
			<label for="eventbriteKeyU" class="settingsLabel"><?php echo $hc_lang_settings['EventbriteKeyU'];?>:</label>
			<input name="eventbriteKeyU" id="eventbriteKeyU" type="text" value="<?php echo $eventbriteKeyU;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip30A'],$hc_lang_settings['Tip30B']);?>
		</div>
		<div class="frmOpt">
			<input type="hidden" name="ebOrgID" id="ebOrgID" value="<?php echo $eventbriteOrgID;?>" />
			<label class="settingsLabel"><?php echo $hc_lang_settings['EventbriteOrgID'];?></label>
			<span style="float:left;line-height:18px;width:115px;"><a href="http://www.eventbrite.com/org/<?php echo $eventbriteOrgID;?>" class="eventMain" target="_blank"><?php echo $eventbriteOrgID;?></a>&nbsp;</span>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip31A'],$hc_lang_settings['Tip31B']);?>
		</div>
		<div class="frmOpt">
			<label for="ebOrgName" class="settingsLabel"><?php echo $hc_lang_settings['EventbriteOrgN'];?></label>
			<input <?php if($eventbriteKeyA == '' || $eventbriteKeyU = ''){echo 'disabled="disabled" ';}?> name="ebOrgName" id="ebOrgName" type="text" value="<?php echo $eventbriteOrgN;?>" size="15" maxlength="30" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip32A'],$hc_lang_settings['Tip32B']);?>
		</div>
		<div class="frmOpt">
			<label for="ebOrgDesc" class="settingsLabel"><?php echo $hc_lang_settings['EventbriteOrgD'];?></label>
			<textarea <?php if($eventbriteKeyA == '' || $eventbriteKeyU = ''){echo 'disabled="disabled" ';}?> name="ebOrgDesc" id="ebOrgDesc" style="width:320px;height:45px;" rows="15" cols="55"><?php echo $eventbriteOrgD;?></textarea>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip33A'],$hc_lang_settings['Tip33B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['TwitterLabel'];?></legend>
		<?php echo $hc_lang_settings['TwitterNotice'];?> <a href="http://twitter.com/tos" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><br />
		<?php
		if($twtrAToken == '' || $twtrASecret == ''){
			$_SESSION[$hc_cfg00 . 'RequestToken'] = $_SESSION[$hc_cfg00 . 'RequestSecret'] = '';
			include('../events/includes/api/twitter/TokenRequest.php');
			
			if($_SESSION[$hc_cfg00 . 'RequestToken'] != '' && $_SESSION[$hc_cfg00 . 'RequestSecret'] != ''){
				echo '<div class="frmOpt"><label class="settingsLabel">&nbsp;</label>';
				echo '<a href="https://twitter.com/oauth/authorize?oauth_token=' . $_SESSION[$hc_cfg00 . 'RequestToken'] . '" target="_blank"><img src="' . CalAdminRoot . '/images/logos/twitter_sign_in.png" width="151" height="24" border="0" /></a>';
				
				echo '<div class="frmOpt"><br />' . $hc_lang_settings['TwitterInst'] . '</div>';
				echo '<div class="frmOpt"><label class="settingsLabel">' . $hc_lang_settings['TwitterPin'] . '</label>';
				echo '<input name="twitterpin" id="twitterpin" type="text" value="" />';
				echo '</div>';
			} else {
				echo '<div class="frmOpt"><b>' . $hc_lang_settings['TwitterTokenFail'] . '</b></div>';
			}//end if
		} else {
			echo '<div class="frmOpt"><label class="settingsLabel">' . $hc_lang_settings['TwitterUser'] . '</label>';
			echo '<div style="line-height:20px;">';
			echo '<a href="http://twitter.com/' . $twtrUsername . '" class="twitter" target="_blank"><img src="' . CalRoot . '/images/share/twitter.png" width="16" height="16" border="0" style="vertical-align:middle;" /> ' . $twtrUsername . '</a>';
			echo '</div></div>';

			echo '<div class="frmOpt"><label class="settingsLabel">' . $hc_lang_settings['TwitterRevoke'] . '</label>';
			echo '<label for="twtrRevoke" style="text-align:left;width:400px;"><input name="twtrRevoke" id="twtrRevoke" type="checkbox" />' . $hc_lang_settings['TwitterDoRevoke'] . '</label>';
			appInstructionsIcon($hc_lang_settings['Tip21A'],$hc_lang_settings['Tip21B']);
			echo '</div>';
		}//end if?>
		<div class="frmOpt">
			<label for="tweetHash" class="settingsLabel"><?php echo $hc_lang_settings['TweetHash'];?></label>
			<input name="tweetHash" id="tweetHash" type="text" value="<?php echo $tweetHash;?>" size="15" maxlength="16" />
               &nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip28A'],$hc_lang_settings['Tip28B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['bitlyLabel'];?></legend>
		<?php echo $hc_lang_settings['bitlyNotice'];?> <a href="http://bitly.net/pages/terms-of-service/" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><br />
		<div class="frmOpt">
			<label for="bitlyUser" class="settingsLabel"><?php echo $hc_lang_settings['bitlyUsername'];?></label>
			<input name="bitlyUser" id="bitlyUser" type="text" value="<?php echo $bitlyUser;?>" size="25" maxlength="50" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip26A'],$hc_lang_settings['Tip26B']);?>
		</div>
		<div class="frmOpt">
			<label for="bitlyAPI" class="settingsLabel"><?php echo $hc_lang_settings['bitlyAPI'];?></label>
			<input name="bitlyAPI" id="bitlyAPI" type="text" value="<?php echo $bitlyAPI;?>" size="45" maxlength="150" />
               &nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip27A'],$hc_lang_settings['Tip27B']);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_settings['SaveSettings'];?> " class="button" />
	</form>