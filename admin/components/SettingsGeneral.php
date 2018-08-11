<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/settings.php');

	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed01']);
				break;
			case "2" :
				feedback(2, $hc_lang_settings['Feed05']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Settings", $hc_lang_settings['TitleSettings'], $hc_lang_settings['InstructSettings']);

	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,25,28,29,30,31,32,33,34,35,40,44,48,53,54,56,65,66,67,68,70,71,72,73,74,75,76,77,78,79,80,81,82) ORDER BY PkID");
	$submit = cOut(mysql_result($result,0,0));
	$maxDisplay = cOut(mysql_result($result,1,0));
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
	$floodTime = cOut(mysql_result($result,16,0));
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
	$mobiRedirect = cOut(mysql_result($result,27,0));
	$recomnds = cOut(mysql_result($result,28,0));
	$pubNews = cOut(mysql_result($result,29,0));
	$useComments = cOut(mysql_result($result,30,0));
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
	$batchDelay = cOut(mysql_result($result,47,0));?>
	
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_settings['Valid01'];?>';
		
		if(document.getElementById('loginTries').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid42'];?>';
		} else if(isNaN(document.getElementById('loginTries').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid43'];?>';
		} else if(document.getElementById('loginTries').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid44'];?>';
		}//end if

		if(document.getElementById('batchSize').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid45'];?>';
		} else if(isNaN(document.getElementById('batchSize').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid46'];?>';
		} else if(document.getElementById('batchSize').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid47'];?>';
		}//end if

		if(document.getElementById('batchDelay').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid48'];?>';
		} else if(isNaN(document.getElementById('batchDelay').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid49'];?>';
		} else if(document.getElementById('batchDelay').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid50'];?>';
		}//end if

		if(document.getElementById('mailAddress').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid39'];?>';
		} else if(document.getElementById('mailAddress').value != '' && chkEmail(document.getElementById('mailAddress')) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid40'];?>';
		}//end if

		if(document.getElementById('mailName').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid41'];?>';
		}//end if

		if(document.getElementById('mailMethod').value == 1){
			if(document.getElementById('mailHost').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid34'];?>';
			}//end if
			if(document.getElementById('mailPort').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid35'];?>';
			}//end if
		}//end if

		if(document.getElementById('mailAuth').value == 1){
			if(document.getElementById('mailUser').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid36'];?>';
			}//end if
			if(document.getElementById('mailPassChg').value == '' && <?php echo strlen($mailPass);?> == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid37'];?>';
			}//end if
			if(document.getElementById('mailPassChg').value != '' && document.getElementById('mailPassChg').value != document.getElementById('mailPassCon').value){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid38'];?>';
			}//end if
		}//end if
		
		if(document.getElementById('capType').selectedIndex == 2){
			if(document.getElementById('reCapPub').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid27'];?>';
			}//end if
			if(document.getElementById('reCapPriv').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid28'];?>';
			}//end if
		}//end if

		if(document.getElementById('reclimit').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid29'];?>';
		} else if(isNaN(document.getElementById('reclimit').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid30'];?>';
		}//end if
		
		if(document.getElementById('floodTime').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid32'];?>';
		} else if(isNaN(document.getElementById('floodTime').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid33'];?>';
		}//end if

		if(document.getElementById('subLimit').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid02'];?>';
		} else if(isNaN(document.getElementById('subLimit').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid03'];?>';
		} else if(document.getElementById('subLimit').value < 1){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid04'];?>';
		}//end if

		if(document.getElementById('display').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid11'];?>';
		} else if(isNaN(document.getElementById('display').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid12'];?>';
		} else if(document.getElementById('display').value < 1){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid13'];?>';
		}//end if

		if(document.getElementById('mostPopular').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid08'];?>';
		} else if(isNaN(document.getElementById('mostPopular').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid09'];?>';
		} else if(document.getElementById('mostPopular').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid10'];?>';
		}//end if

		if(document.getElementById('maxNew').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid08'];?>';
		} else if(isNaN(document.getElementById('maxNew').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid09'];?>';
		} else if(document.getElementById('maxNew').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid10'];?>';
		}//end if

		if(document.getElementById('maxRSS').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid05'];?>';
		} else if(isNaN(document.getElementById('maxRSS').value)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid06'];?>';
		} else if(document.getElementById('maxRSS').value < 1) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid07'];?>';
		}//end if

		if(document.getElementById('dateFormat').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid14'];?>';
		}//end if
		
		if(document.getElementById('timeFormat').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid15'];?>';
		}//end if
		
		if(document.getElementById('defaultApprove').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid20'];?>';
		}//end if
		
		if(document.getElementById('defaultDecline').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid21'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_settings['Valid22'];?>');
			return false;
		}//end if
		
	}//end chkFrm()
	
	function togSubmit(){
		if(document.getElementById('allowsubmit').value == 1){
			document.getElementById('pubCat').disabled = false;
			document.getElementById('subLimit').disabled = false;
		} else {
			document.getElementById('pubCat').disabled = true;
			document.getElementById('subLimit').disabled = true;
		}//end if
	}//end togSubmit()
	
	function togLocBrowse(){
		if(document.getElementById('locBrowse').value == 1){
			document.getElementById('lmapZoom').disabled = false;
			document.getElementById('lmapLat').disabled = false;
			document.getElementById('lmapLon').disabled = false;
		} else {
			document.getElementById('lmapZoom').disabled = true;
			document.getElementById('lmapLat').disabled = true;
			document.getElementById('lmapLon').disabled = true;
		}//end if
	}//end togLocBrowse()

	function togComments(){
		if(document.getElementById('useComments').value == 1){
			document.getElementById('reclimit').disabled = false;
			document.getElementById('floodTime').disabled = false;
		} else {
			document.getElementById('reclimit').disabled = true;
			document.getElementById('floodTime').disabled = true;
		}//end if
	}//end togComments()

	function togEmail(){
		if(document.getElementById('mailMethod').value == 1){
			document.getElementById('phpMailer').style.display = 'block';
			document.getElementById('mailHost').disabled = false;
			document.getElementById('mailPort').disabled = false;
			document.getElementById('mailSecure').disabled = false;
			document.getElementById('mailAuth').disabled = false;
			document.getElementById('mailUser').disabled = false;
			document.getElementById('mailPassChg').disabled = false;
			document.getElementById('mailPassCon').disabled = false;
		} else {
			document.getElementById('phpMailer').style.display = 'none';
			document.getElementById('mailHost').disabled = true;
			document.getElementById('mailPort').disabled = true;
			document.getElementById('mailSecure').disabled = true;
			document.getElementById('mailAuth').disabled = true;
			document.getElementById('mailUser').disabled = true;
			document.getElementById('mailPassChg').disabled = true;
			document.getElementById('mailPassCon').disabled = true;
		}//end if
	}//end togEmail()

	function togCAPTCHA(){
		showReCap = (document.getElementById('capType').selectedIndex == 2) ? 'block' : 'none';
		allowCap = (document.getElementById('capType').selectedIndex == 0) ? true : false;

		document.getElementById('reCAPTCHA').style.display = showReCap;
		for(x = 1;x <= 7;x++){
			document.getElementById('capID_' + x).disabled = allowCap;
		}//end if
	}//end togCAPTCHA()
	//-->
	</script>
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SettingsGeneralAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['General'];?></legend>
		<div class="frmOpt">
			<label for="browseType" class="settingsLabel"><?php echo $hc_lang_settings['BrowseType'];?></label>
			<select name="browseType" id="browseType">
				<option <?php if($browseType == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['BrowseTypeW'];?></option>
				<option <?php if($browseType == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['BrowseTypeM'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip01A'], $hc_lang_settings['Tip01B']);?>
		</div>
		<div class="frmOpt">
			<label for="browsePast" class="settingsLabel"><?php echo $hc_lang_settings['BrowseLimit'];?></label>
			<select name="browsePast" id="browsePast">
				<option <?php if($browsePast == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['BrowseLimitC'];?></option>
				<option <?php if($browsePast == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['BrowseLimitA'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip02A'], $hc_lang_settings['Tip02B']);?>
		</div>
	<?php
		if($hc_lang_config['AddressRegion'] == 1){
			echo '<div class="frmOpt">';
			echo '<label for="locState" class="settingsLabel">' . $hc_lang_settings['Default'] . ' ' . $hc_lang_config['RegionLabel'] . '</label>';
			include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);
			echo '&nbsp;' . appInstructionsIcon($hc_lang_settings['Tip03A'],$hc_lang_settings['Tip03B']);
			echo '</div>';
		} else {
			echo '<input type="hidden" name="locState" id="locState" value="" />';
		}//end if	?>
		<div class="frmOpt">
			<label for="calStartDay" class="settingsLabel"><?php echo $hc_lang_settings['MCStart'];?></label>
			<select name="calStartDay" id="calStartDay">
				<option <?php if($calStartDay == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['Sunday'];?></option>
				<option <?php if($calStartDay == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['Monday'];?></option>
				<option <?php if($calStartDay == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_settings['Tuesday'];?></option>
				<option <?php if($calStartDay == 3){echo 'selected="selected"';}?> value="3"><?php echo $hc_lang_settings['Wednesday'];?></option>
				<option <?php if($calStartDay == 4){echo 'selected="selected"';}?> value="4"><?php echo $hc_lang_settings['Thursday'];?></option>
				<option <?php if($calStartDay == 5){echo 'selected="selected"';}?> value="5"><?php echo $hc_lang_settings['Friday'];?></option>
				<option <?php if($calStartDay == 6){echo 'selected="selected"';}?> value="6"><?php echo $hc_lang_settings['Saturday'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="WYSIWYG" class="settingsLabel"><?php echo $hc_lang_settings['WYSIWYG'];?></label>
			<select name="WYSIWYG" id="WYSIWYG">
				<option <?php if($WYSIWYG == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['WYSIWYGY'];?></option>
				<option <?php if($WYSIWYG == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['WYSIWYGN'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip04A'],$hc_lang_settings['Tip04B']);?>
		</div>
		<div class="frmOpt">
			<label for="mobiRedirect" class="settingsLabel"><?php echo $hc_lang_settings['Redirect'];?></label>
			<select name="mobiRedirect" id="mobiRedirect">
				<option <?php if($mobiRedirect == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['Redirect1'];?></option>
				<option <?php if($mobiRedirect == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['Redirect0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip38A'],$hc_lang_settings['Tip38B']);?>
		</div>
		<div class="frmOpt">
			<label for="stats" class="settingsLabel"><?php echo $hc_lang_settings['LocSelect'];?></label>
			<select name="locSelect" id="locSelect">
				<option <?php if($locSelect == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['LocSelect1'];?></option>
				<option <?php if($locSelect == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['LocSelect0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip39A'],$hc_lang_settings['Tip39B']);?>
		</div>
		<div class="frmOpt">
			<label for="stats" class="settingsLabel"><?php echo $hc_lang_settings['Report'];?></label>
			<select name="stats" id="stats">
				<option <?php if($stats == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['ReportY'];?></option>
				<option <?php if($stats == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['ReportN'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip05A'],$hc_lang_settings['Tip05B']);?>
		</div>
		<div class="frmOpt">
			<label for="loginTries" class="settingsLabel"><?php echo $hc_lang_settings['LoginAttempts'];?></label>
			<input name="loginTries" id="loginTries" type="text" size="2" maxlength="2" value="<?php echo $loginTries;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip40A'],$hc_lang_settings['Tip40B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Emails'];?></legend>
		<div class="frmOpt">
			<label for="mailAddress" class="settingsLabel"><?php echo $hc_lang_settings['MailAddress'];?></label>
			<input name="mailAddress" id="mailAddress" type="text" size="45" maxlength="200" value="<?php echo $mailAddress;?>" />
		</div>
		<div class="frmOpt">
			<label for="mailName" class="settingsLabel"><?php echo $hc_lang_settings['MailName'];?></label>
			<input name="mailName" id="mailName" type="text" size="25" maxlength="100" value="<?php echo $mailName;?>" />
		</div>

		<div class="frmOpt">
			<label for="mailMethod" class="settingsLabel"><?php echo $hc_lang_settings['MailMethod'];?></label>
			<select name="mailMethod" id="mailMethod" onchange="togEmail();">
				<option <?php if($mailMethod == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['MailMethod0'];?></option>
				<option <?php if($mailMethod == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['MailMethod1'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip43A'],$hc_lang_settings['Tip43B']);?>
		</div>
		<div id="phpMailer" style="<?php echo ($mailMethod == 0) ? 'display:none;' : ''?>">
			<div class="frmOpt">
				<label for="mailHost" class="settingsLabel"><?php echo $hc_lang_settings['MailHost'];?></label>
				<input name="mailHost" id="mailHost" type="text" size="35" maxlength="100" value="<?php echo $mailHost;?>" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?> />
			</div>
			<div class="frmOpt">
				<label for="mailPort" class="settingsLabel"><?php echo $hc_lang_settings['MailPort'];?></label>
				<input name="mailPort" id="mailPort" type="text" size="5" maxlength="5" value="<?php echo $mailPort;?>" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?> />
			</div>
			<div class="frmOpt">
				<label for="mailSecure" class="settingsLabel"><?php echo $hc_lang_settings['MailSecure'];?></label>
				<select name="mailSecure" id="mailSecure" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?>>
					<option <?php if($mailSecure == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['MailSecure0'];?></option>
					<option <?php if($mailSecure == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['MailSecure1'];?></option>
					<option <?php if($mailSecure == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_settings['MailSecure2'];?></option>
				</select>
			</div>
			<div class="frmOpt">
				<label for="mailAuth" class="settingsLabel"><?php echo $hc_lang_settings['MailAuth'];?></label>
				<select name="mailAuth" id="mailAuth" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?>>
					<option <?php if($mailAuth == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['MailAuth0'];?></option>
					<option <?php if($mailAuth == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['MailAuth1'];?></option>
				</select>
			</div>
			<div class="frmOpt">
				<label for="mailUser" class="settingsLabel"><?php echo $hc_lang_settings['MailUser'];?></label>
				<input name="mailUser" id="mailUser" type="text" size="25" maxlength="200" value="<?php echo $mailUser;?>" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?> />
			</div>
			<div class="frmOpt">
				<label class="settingsLabel"><?php echo $hc_lang_settings['MailPass'];?></label>
		<?php	for($x=0;$x<strlen($mailPass);$x++){
					echo "*";
				}//end for?>&nbsp;
			</div>
			<div class="frmOpt">
				<label for="mailPassChg" class="settingsLabel"><?php echo $hc_lang_settings['MailPassNew'];?></label>
				<input name="mailPassChg" id="mailPassChg" type="password" size="10" maxlength="50" value="" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?> />
			</div>
			<div class="frmOpt">
				<label for="mailPassCon" class="settingsLabel"><?php echo $hc_lang_settings['MailPassCon'];?></label>
				<input name="mailPassCon" id="mailPassCon" type="password" size="10" maxlength="50" value="" <?php if($mailMethod == 0){echo 'disabled="disabled"';}?> />
			</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Newsletter'];?></legend>
		<div class="frmOpt">
			<label for="pubNews" class="settingsLabel"><?php echo $hc_lang_settings['PublicNews'];?></label>
			<select name="pubNews" id="pubNews">
				<option <?php if($pubNews == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['PublicNews0'];?></option>
				<option <?php if($pubNews == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['PublicNews1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="batchSize" class="settingsLabel"><?php echo $hc_lang_settings['BatchSize'];?></label>
			<input name="batchSize" id="batchSize" type="text" size="3" maxlength="3" value="<?php echo $batchSize;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip41A'],$hc_lang_settings['Tip41B']);?>
		</div>
		<div class="frmOpt">
			<label for="batchDelay" class="settingsLabel"><?php echo $hc_lang_settings['BatchDelay'];?></label>
			<input name="batchDelay" id="batchDelay" type="text" size="3" maxlength="3" value="<?php echo $batchDelay;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip42A'],$hc_lang_settings['Tip42B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['LanguageSettings'];?></legend>
		<div class="frmOpt">
			<label for="langType" class="settingsLabel"><?php echo $hc_lang_settings['Language'];?></label>
	<?php	if(file_exists(realpath($hc_langPath))){
				echo '<select name="langType" id="langType">';
				$dir = dir(realpath($hc_langPath));
				$x = 0;
				while(($file = $dir->read()) != false){
					if(is_dir($dir->path.'/'.$file) && strpos($file,'.') === false && strpos($file,'_') === false){
						$langOpt[] = $file;
					}//end if
				}//end while
				
				sort($langOpt);
				foreach($langOpt as $val){
					echo ($val == $langType) ? '<option selected="selected" value="' . $val . '">' . ucwords($val) . '</option>' : '<option value="' . $val . '">' . ucwords($val) . '</option>';
					++$x;
				}//end foreach

				echo ($x == 0) ? '<option value="">' . $hc_lang_settings['NoLang'] . '</option>' : '';
				echo '</select>';
			} else {
				echo '<b>' . $hc_lang_settings['NoLangDir'] . '</b>';
			}//end if	?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['HeliosLP'];?></label>
			<a href="https://www.refreshmy.com/members/" class="eventMain" target="_blank"><?php echo $hc_lang_settings['DownloadHere'];?></a>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['TinyMCELP'];?></label>
			<a href="http://tinymce.moxiecode.com/download_i18n.php" class="eventMain" target="_blank"><?php echo $hc_lang_settings['DownloadHere'];?></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['CAPTCHA']; appInstructionsIcon($hc_lang_settings['Tip06A'],$hc_lang_settings['Tip06B']);?></legend>
<?php	$noGD = (!function_exists('imagecreate')) ? true : false;
		$canCap = ($noGD == true || $capType == 0) ? 'disabled="disabled"' : '';

		echo $hc_lang_settings['reCAPTCHANotice'] . '<a href="http://www.google.com/recaptcha/terms" class="eventMain" target="_blank">' . $hc_lang_settings['TermsOfUse'] . '</a>.';
		echo '<br />' . $hc_lang_settings['NoShare'] . '<br /><br />';

		echo '<div class="frmOpt">';
		echo '<label class="settingsLabel" for="capType">' . $hc_lang_settings['CAPTCHAType'] . '</label>';
		echo '<select name="capType" id="capType" onchange="togCAPTCHA();">';
		echo ($capType == 0) ? '<option selected="selected" value="0">' : '<option value="0">';
		echo $hc_lang_settings['CAPTCHAType0'] . '</option>';
		if($noGD == false){
			echo ($capType == 1) ? '<option selected="selected" value="1">' : '<option value="1">';
			echo $hc_lang_settings['CAPTCHAType1'] . '</option>';
		}//end if
		echo ($capType == 2) ? '<option selected="selected" value="2">' : '<option value="2">';
		echo $hc_lang_settings['CAPTCHAType2'] . '</option>';
		echo '</select>&nbsp;(<a href="http://www.google.com/recaptcha" target="_blank" class="eventMain">' . $hc_lang_settings['reCAPTCHALink'] . '</a>)</div>';

		echo ($capType == 2) ? '<div id="reCAPTCHA">' : '<div id="reCAPTCHA" style="display:none;">';
		echo '<div class="frmOpt">';
		echo '<label class="settingsLabel" for="capType">' . $hc_lang_settings['reCAPTCHAPubKey'] . '</label>';
		echo '<input name="reCapPub" id="reCapPub" type="text" size="60" maxlength="150" value="' . $reCapPub . '" />';
		appInstructionsIcon($hc_lang_settings['Tip35A'],$hc_lang_settings['Tip35B']);
		echo '</div>';

		echo '<div class="frmOpt">';
		echo '<label class="settingsLabel" for="capType">' . $hc_lang_settings['reCAPTCHAPrivKey'] . '</label>';
		echo '<input name="reCapPriv" id="reCapPriv" type="text" size="60" maxlength="150" value="' . $reCapPriv . '" />';
		appInstructionsIcon($hc_lang_settings['Tip36A'],$hc_lang_settings['Tip36B']);
		echo '</div></div>';
		?>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['ActiveCAPTCHA'];?></label>
			<label for="capID_1" class="captcha"><input <?php echo $canCap; if(in_array(1, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_1" type="checkbox" value="1" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveEventSubmit'];?></label>
			<label for="capID_2" class="captcha"><input <?php echo $canCap; if(in_array(2, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_2" type="checkbox" value="2" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveEmailFriend'];?></label>
		</div>
		<br />
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_3" class="captcha"><input <?php echo $canCap; if(in_array(3, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_3" type="checkbox" value="3" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveRegister'];?></label>
			<label for="capID_4" class="captcha"><input <?php echo $canCap; if(in_array(4, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_4" type="checkbox" value="4" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveNewsletter'];?></label>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_5" class="captcha"><input <?php echo $canCap; if(in_array(5, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_5" type="checkbox" value="5" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveLogin'];?></label>
			<label for="capID_6" class="captcha"><input <?php echo $canCap; if(in_array(6, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_6" type="checkbox" value="6" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveComments'];?></label>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_7" class="captcha"><input <?php echo $canCap; if(in_array(7, $captchas)){echo 'checked="checked"';}//end if?> name="capID[]" id="capID_7" type="checkbox" value="7" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveCommReport'];?></label>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['PublicEventSub'];?></legend>
		<div class="frmOpt">
			<label for="allowsubmit" class="settingsLabel"><?php echo $hc_lang_settings['SubSetting'];?></label>
			<select name="allowsubmit" id="allowsubmit" onchange="togSubmit();">
				<option <?php if($submit == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['SubSettingON'];?></option>
				<option <?php if($submit == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['SubSettingOFF'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="subLimit" class="settingsLabel"><?php echo $hc_lang_settings['SessionLimit'];?></label>
			<input name="subLimit" id="subLimit" type="text" size="3" maxlength="3" value="<?php echo $subLimit;?>" <?php if($submit == 0){echo "disabled=\"disabled\"";}?> />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip07A'],$hc_lang_settings['Tip07B']);?>
		</div>
		<div class="frmOpt">
			<label for="pubCat" class="settingsLabel"><?php echo $hc_lang_settings['PublicCategories'];?></label>
			<select name="pubCat" id="pubCat" <?php if($submit == 0){echo "disabled=\"disabled\"";}?>>
				<option <?php if($userCat == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['PublicCategories0'];?></option>
				<option <?php if($userCat == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['PublicCategories1'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip08A'],$hc_lang_settings['Tip08B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Comments']?></legend>
		<div class="frmOpt">
			<label for="useComments" class="settingsLabel"><?php echo $hc_lang_settings['UseComments']?></label>
			<select name="useComments" id="useComments" onchange="togComments();">
				<option <?php echo ($useComments == 0) ? 'selected="selected"' : '';?> value="0"><?php echo $hc_lang_settings['UseComments0'];?></option>
				<option <?php echo ($useComments == 1) ? 'selected="selected"' : '';?> value="1"><?php echo $hc_lang_settings['UseComments1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="reclimit" class="settingsLabel"><?php echo $hc_lang_settings['Recomnds'];?></label>
			<input <?php echo ($useComments == 0) ? 'disabled="disabled"' : '';?> name="reclimit" id="reclimit" type="text" size="5" maxlength="4" value="<?php echo $recomnds;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip25A'],$hc_lang_settings['Tip25B']);?>
		</div>
		<div class="frmOpt">
			<label for="floodTime" class="settingsLabel"><?php echo $hc_lang_settings['CommentDelay'];?></label>
			<input <?php echo ($useComments == 0) ? 'disabled="disabled"' : '';?> name="floodTime" id="floodTime" type="text" size="5" maxlength="4" value="<?php echo $floodTime;?>" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip34A'],$hc_lang_settings['Tip34B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['BillPopRSS'];?></legend>
		<div class="frmOpt">
			<label for="display" class="settingsLabel"><?php echo $hc_lang_settings['BillboardSize'];?></label>
			<input name="display" id="display" type="text" size="2" maxlength="2" value="<?php echo $maxShow;?>" />
		</div>
		<div class="frmOpt">
			<label for="mostPopular" class="settingsLabel"><?php echo $hc_lang_settings['PopularSize'];?></label>
			<input name="mostPopular" id="mostPopular" type="text" size="2" maxlength="2" value="<?php echo $mostPopular;?>" />
		</div>
		<div class="frmOpt">
			<label for="maxNew" class="settingsLabel"><?php echo $hc_lang_settings['MaxNewSize'];?></label>
			<input name="maxNew" id="maxNew" type="text" size="2" maxlength="2" value="<?php echo $maxNew;?>" />
		</div>
		<div class="frmOpt">
			<label for="maxRSS" class="settingsLabel"><?php echo $hc_lang_settings['RSSSize'];?></label>
			<input name="maxRSS" id="maxRSS" type="text" size="2" maxlength="2" value="<?php echo $maxDisplay;?>" />
		</div>
		<div class="frmOpt">
			<label for="fill" class="settingsLabel"><?php echo $hc_lang_settings['AutoFill'];?></label>
			<select name="fill" id="fill">
				<option <?php if($fillMax == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['AutoFill1'];?></option>
				<option <?php if($fillMax == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['AutoFill0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip09A'],$hc_lang_settings['Tip09B']);?>
		</div>
		<div class="frmOpt">
			<label for="series" class="settingsLabel"><?php echo $hc_lang_settings['EventSeries'];?></label>
			<select name="series" id="series">
				<option <?php if($series == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_settings['EventSeries1'];?></option>
				<option <?php if($series == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_settings['EventSeries0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip10A'],$hc_lang_settings['Tip10B']);?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['StartTime'];?></label>
			<label for="showtime" class="radioWide"><input <?php if($showTime == 1){echo "checked=\"checked\"";}?> name="showtime" id="showtime" type="checkbox" value="" class="noBorderIE" />(<?php echo $hc_lang_settings['StartTimeLabel'];?>)</label>
			&nbsp;
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['DateTime'];?> [ <a href="http://www.php.net/strftime" class="eventMain" target="_blank"><?php echo $hc_lang_settings['DateTimeLink'];?></a> ]</legend>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['CurServerTime'];?></label>
			<div style="line-height:18px;"><b><?php echo strftime($dateFormat . " " . $timeFormat);?></b></div>
		</div>
<?php
	if($timezoneOffset != 0){	?>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['HeliosTime'];?></label>
		<?php 
			$hourOffset = date("G");
			if($timezoneOffset > 0){
				$hourOffset = $hourOffset + abs($timezoneOffset);
			} else {
				$hourOffset = $hourOffset - abs($timezoneOffset);
			}//end if
			echo '<div style="line-height:18px;"><b>' . strftime($dateFormat . ' ' . $timeFormat, mktime($hourOffset, date("i"), date("s"), date("m"), date("d"), date("Y"))) . '</b></div>';
			?>
		</div>
<?php
	}//end if	?>
		<div class="frmOpt">
			<label for="offsetTimezone" class="settingsLabel"><?php echo $hc_lang_settings['OffsetTime'];?></label>
			<select name="offsetTimezone" id="offsetTimezone">
			<?php
				for($x=-23;$x<24;$x++){
					echo '<option ';
					echo ($timezoneOffset == $x) ? 'selected="selected"' : '';
					echo 'value="' . $x . '">';
					echo ($x > 0) ? '+' : '';
					echo ($x == 0) ? $hc_lang_settings['ServerTime'] : $x . ' ' . $hc_lang_settings['hours'];
					echo '</option>';	
				}//end if
			?>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip11A'],$hc_lang_settings['Tip11B']);?>
		</div>
		<div class="frmOpt">
			<label for="popDateFormat" class="settingsLabel"><?php echo $hc_lang_settings['DateFormatIn'];?></label>
			<select name="popDateFormat" id="popDateFormat">
				<option <?php if($popDateFormat == "%m/%d/%Y"){echo 'selected="selected"';}?> value="%m/%d/%Y">m/d/y (<?php echo strftime("%m/%d/%Y");?>)</option>
				<option <?php if($popDateFormat == "%d/%m/%Y"){echo 'selected="selected"';}?> value="%d/%m/%Y">d/m/y (<?php echo strftime("%d/%m/%Y");?>)</option>
				<option <?php if($popDateFormat == "%Y/%m/%d"){echo 'selected="selected"';}?> value="%Y/%m/%d">y/m/d (<?php echo strftime("%Y/%m/%d");?>)</option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip12A'],$hc_lang_settings['Tip12B']);?>
		</div>
		<div class="frmOpt">
			<label for="dateFormat" class="settingsLabel"><?php echo $hc_lang_settings['DateFormatOut'];?></label>
			<input name="dateFormat" id="dateFormat" type="text" value="<?php echo $dateFormat;?>" size="20" maxlength="25" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip13A'],$hc_lang_settings['Tip13B']);?>
		</div>
		<div class="frmOpt">
			<label for="timeInput" class="settingsLabel"><?php echo $hc_lang_settings['TimeFormatIn'];?></label>
			<select name="timeInput" id="timeInput">
				<option <?php if($timeInput == "24"){echo 'selected="selected"';}?> value="24"><?php echo $hc_lang_settings['24Hour'];?> (<?php echo strftime("%H:%M");?>)</option>
				<option <?php if($timeInput == "12"){echo 'selected="selected"';}?> value="12"><?php echo $hc_lang_settings['12Hour'];?> (<?php echo strftime("%I:%M %p");?>)</option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip14A'],$hc_lang_settings['Tip14B']);?>
		</div>
		<div class="frmOpt">
			<label for="timeFormat" class="settingsLabel"><?php echo $hc_lang_settings['TimeFormatOut'];?></label>
			<input name="timeFormat" id="timeFormat" type="text" value="<?php echo $timeFormat;?>" size="10" maxlength="20" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip15A'],$hc_lang_settings['Tip15B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['WMLinks'];?></legend>
		<?php echo $hc_lang_settings['LinksNotice'] . ' <a href="http://www.refreshmy.com/documentation/?title=Weather/Map_Links" class="eventMain" target="_blank">' . $hc_lang_settings['LinkDoc'] . '</a>';?>
		<br /><br />
		<div class="frmOpt">
			<label for="weather" class="settingsLabel"><?php echo $hc_lang_settings['WeatherLink'];?></label>
			<input name="weather" id="weather" type="text" value = "<?php echo $weather;?>" size="60" maxlength="250" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip23A'],$hc_lang_settings['Tip23B']);?>
		</div>
		<div class="frmOpt">
			<label for="driving" class="settingsLabel"><?php echo $hc_lang_settings['MapLink'];?></label>
			<input name="driving" id="driving" type="text" value = "<?php echo $driving;?>" size="60" maxlength="250" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip24A'],$hc_lang_settings['Tip24B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['PendingMsg'];?></legend>
		<div class="frmOpt">
			<label for="defaultApprove" class="settingsLabel"><?php echo $hc_lang_settings['AppMessage'];?></label>
			<textarea id="defaultApprove" name="defaultApprove" style="width: 425px; height: 125px" rows="15" cols="55"><?php echo $passApprove;?></textarea>
		</div>
		<div class="frmOpt">
			<label for="defaultDecline" class="settingsLabel"><?php echo $hc_lang_settings['DecMessage'];?></label>
			<textarea id="defaultDecline" name="defaultDecline" style="width: 425px; height: 125px" rows="15" cols="55"><?php echo $passDecline;?></textarea>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_settings['SaveSettings'];?> " class="button" />
	</form>