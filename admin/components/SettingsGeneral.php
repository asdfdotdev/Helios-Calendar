<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/settings.php');

	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_settings['Feed01']);
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Settings", $hc_lang_settings['TitleSettings'], $hc_lang_settings['InstructSettings']);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48) ORDER BY PkID");
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
	$emailNotice = cOut(mysql_result($result,16,0));
	$googleKey = cOut(mysql_result($result,17,0));
	$emapZoom = cOut(mysql_result($result,18,0));
	$userCat = cOut(mysql_result($result,20,0));
	$WYSIWYG = cOut(mysql_result($result,21,0));
	$timeInput = cOut(mysql_result($result,22,0));
	$captchas = explode(",", cOut(mysql_result($result,23,0)));
	$series = cOut(mysql_result($result,24,0));
	$browseType = cOut(mysql_result($result,25,0));	
	$timezoneOffset = cOut(mysql_result($result,26,0));
	$eventfulKey = cOut(mysql_result($result,27,0));
	$eventfulUser = cOut(mysql_result($result,28,0));
	$eventfulPass = cOut(mysql_result($result,29,0));
	$eventfulSig = cOut(mysql_result($result,30,0));
	$subLimit = cOut(mysql_result($result,31,0));
	$lmapZoom = cOut(mysql_result($result,32,0));
	$lmapLat = cOut(mysql_result($result,33,0));
	$lmapLon = cOut(mysql_result($result,34,0));
	$stats = cOut(mysql_result($result,35,0));
	$locBrowse = cOut(mysql_result($result,36,0));
	$langType = cOut(mysql_result($result,19,0));
	$twtrUser = cOut(mysql_result($result,37,0));
	$twtrPass = cOut(mysql_result($result,38,0));
	$twtrLog = cOut(mysql_result($result,39,0));
?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = 0;
		var warn = '<?php echo $hc_lang_settings['Valid01'];?>';
		
		if(document.frm.subLimit.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid02'];?>';
		} else {
			if(isNaN(document.frm.subLimit.value)){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid03'];?>';
			} else {
				if(document.frm.subLimit.value < 1){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_settings['Valid04'];?>';
				}//end if
			}//end if
		}//end if
		
		if(document.frm.maxRSS.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid05'];?>';
		} else {
			if(isNaN(document.frm.maxRSS.value)){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid06'];?>';
			} else if(document.frm.maxRSS.value < 1) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid07'];?>';
			}//end if
		}//end if
		
		if(document.frm.mostPopular.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid08'];?>';
		} else {
			if(isNaN(document.frm.mostPopular.value)){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid09'];?>';
			} else if(document.frm.mostPopular.value < 1) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid10'];?>';
			}//end if
		}//end if
		
		if(document.frm.display.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid11'];?>';
		} else {
			if(isNaN(document.frm.display.value)){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid12'];?>';
			} else {
				if(document.frm.display.value < 1){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_settings['Valid13'];?>';
				}//end if
			}//end if
		}//end if
		
		if(document.frm.dateFormat.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid14'];?>';
		}//end if
		
		if(document.frm.timeFormat.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid15'];?>';
		}//end if
		
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
		
		if(document.frm.twEmail.value != '' && document.frm.twPass.value == '' && (0 == <?php echo strlen($twtrPass);?>)){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid27'];?>';
		}//end if
		
		if(document.frm.twPass.value != ''){
			if(document.frm.twPass.value != document.frm.twPass2.value){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_settings['Valid28'];?>';
			}//end if
		}//end if
		
		if(document.frm.defaultApprove.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid20'];?>';
		}//end if
		
		if(document.frm.defaultDecline.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_settings['Valid21'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_settings['Valid22'];?>');
			return false;
		}//end if
		
	}//end chkFrm()
	
	function togSubmit(){
		if(document.frm.allowsubmit.value == 1){
			document.frm.pubCat.disabled = false;
			document.frm.subLimit.disabled = false;
		} else {
			document.frm.pubCat.disabled = true;
			document.frm.subLimit.disabled = true;
		}//end if
	}//end togSubmit()
	
	function togLocBrowse(){
		if(document.frm.locBrowse.value == 1){
			document.frm.lmapZoom.disabled = false;
			document.frm.lmapLat.disabled = false;
			document.frm.lmapLon.disabled = false;
		} else {
			document.frm.lmapZoom.disabled = true;
			document.frm.lmapLat.disabled = true;
			document.frm.lmapLon.disabled = true;
		}//end if
	}//end togSubmit()
	//-->
	</script>
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SettingsGeneralAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['General'];?></legend>
		<div class="frmOpt">
			<label for="browseType" class="settingsLabel"><?php echo $hc_lang_settings['BrowseType'];?></label>
			<select name="browseType" id="browseType">
				<option <?php if($browseType == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['BrowseTypeW'];?></option>
				<option <?php if($browseType == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['BrowseTypeM'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip01A'], $hc_lang_settings['Tip01B']);?>
		</div>
		<div class="frmOpt">
			<label for="browsePast" class="settingsLabel"><?php echo $hc_lang_settings['BrowseLimit'];?></label>
			<select name="browsePast" id="browsePast">
				<option <?php if($browsePast == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['BrowseLimitC'];?></option>
				<option <?php if($browsePast == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['BrowseLimitA'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip02A'], $hc_lang_settings['Tip02B']);?>
		</div>
		<div class="frmOpt">
			<label for="locState" class="settingsLabel">Default <?php echo $hc_lang_config['RegionLabel'];?></label>
	<?php 	include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);?>
		</div>
		<div class="frmOpt">
			<label for="calStartDay" class="settingsLabel"><?php echo $hc_lang_settings['MCStart'];?></label>
			<select name="calStartDay" id="calStartDay">
				<option <?php if($calStartDay == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['Sunday'];?></option>
				<option <?php if($calStartDay == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['Monday'];?></option>
				<option <?php if($calStartDay == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_settings['Tuesday'];?></option>
				<option <?php if($calStartDay == 3){echo "selected=\"selected\"";}?> value="3"><?php echo $hc_lang_settings['Wednesday'];?></option>
				<option <?php if($calStartDay == 4){echo "selected=\"selected\"";}?> value="4"><?php echo $hc_lang_settings['Thursday'];?></option>
				<option <?php if($calStartDay == 5){echo "selected=\"selected\"";}?> value="5"><?php echo $hc_lang_settings['Friday'];?></option>
				<option <?php if($calStartDay == 6){echo "selected=\"selected\"";}?> value="6"><?php echo $hc_lang_settings['Saturday'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="emailNotice" class="settingsLabel"><?php echo $hc_lang_settings['EventNotice'];?></label>
			<select name="emailNotice" id="emailNotice">
				<option <?php if($emailNotice == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['EventNoticeN'];?></option>
				<option <?php if($emailNotice == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['EventNoticeY'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip03A'],$hc_lang_settings['Tip03B']);?>
		</div>
		<div class="frmOpt">
			<label for="WYSIWYG" class="settingsLabel"><?php echo $hc_lang_settings['WYSIWYG'];?></label>
			<select name="WYSIWYG" id="WYSIWYG">
				<option <?php if($WYSIWYG == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['WYSIWYGY'];?></option>
				<option <?php if($WYSIWYG == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['WYSIWYGN'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip04A'],$hc_lang_settings['Tip04B']);?>
		</div>
		<div class="frmOpt">
			<label for="stats" class="settingsLabel"><?php echo $hc_lang_settings['Report'];?></label>
			<select name="stats" id="stats">
				<option <?php if($stats == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['ReportY'];?></option>
				<option <?php if($stats == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['ReportN'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip05A'],$hc_lang_settings['Tip05B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['LanguageSettings'];?></legend>
		<div class="frmOpt">
			<label for="langType" class="settingsLabel"><?php echo $hc_lang_settings['Language'];?></label>
	<?php	if(file_exists(realpath($hc_langPath))){
				echo "<select name=\"langType\" id=\"langType\">";
				$dir = dir(realpath($hc_langPath));
				$x = 0;
				while(($file = $dir->read()) != false){
					if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
						$langOption = $file;
						if($langOption == $langType){
							echo "<option selected=\"selected\" value=\"" . $langOption . "\">" . ucwords($langOption) . "</option>";
						} else {
							echo "<option value=\"" . $langOption . "\">" . ucwords($langOption) . "</option>";
						}//end if
						$x++;
					}//end if
				}//end while
				if($x == 0){
					echo "<option value=\"\">" . $hc_lang_settings['NoLang'] . "</option>";
				}//end if
				echo "</select>";
			} else {
				echo "<b>" . $hc_lang_settings['NoLangDir'] . "</b>";
			}//end if	?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['HeliosLP'];?></label>
			<a href="https://www.helioscalendar.com/members/" class="eventMain" target="_blank"><?php echo $hc_lang_settings['DownloadHere'];?></a>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['TinyMCELP'];?></label>
			<a href="http://services.moxiecode.com/i18n/" class="eventMain" target="_blank"><?php echo $hc_lang_settings['DownloadHere'];?></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['CAPTCHA']; appInstructionsIcon($hc_lang_settings['Tip06A'],$hc_lang_settings['Tip06B']);?></legend>
<?php	$capDisplay = "";
		if(!function_exists('imagecreate')){
			$capDisplay = " disabled=\"disabled\"";
			echo "<span style=\"font-weight:bold;color:#DC143C;\">" . $hc_lang_settings['NOGD'] . "</span><br /><br />";
		} else {	?>
		<div class="frmOpt">
			<label class="settingsLabel"><?php echo $hc_lang_settings['ActiveCAPTCHA'];?></label>
			<label for="capID_1" class="captcha"><input <?php echo $capDisplay; if(in_array(1, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_1" type="checkbox" value="1" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveEventSubmit'];?></label>
			<label for="capID_2" class="captcha"><input <?php echo $capDisplay; if(in_array(2, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_2" type="checkbox" value="2" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveEmailFriend'];?></label>
		</div>
		<br />
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_3" class="captcha"><input <?php echo $capDisplay; if(in_array(3, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_3" type="checkbox" value="3" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveRegister'];?></label>
			<label for="capID_4" class="captcha"><input <?php echo $capDisplay; if(in_array(4, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_4" type="checkbox" value="4" class="noBorderIE" /><?php echo $hc_lang_settings['ActiveNewsletter'];?></label>
		</div>
<?php	}//end if	?>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['PublicEventSub'];?></legend>
		<div class="frmOpt">
			<label for="allowsubmit" class="settingsLabel"><?php echo $hc_lang_settings['SubSetting'];?></label>
			<select name="allowsubmit" id="allowsubmit" onchange="togSubmit();">
				<option <?php if($submit == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['SubSettingON'];?></option>
				<option <?php if($submit == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['SubSettingOFF'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="subLimit" class="settingsLabel"><?php echo $hc_lang_settings['SessionLimit'];?></label>
			<input name="subLimit" id="subLimit" type="text" size="2" maxlength="2" value="<?php echo $subLimit;?>" <?php if($submit == 0){echo "disabled=\"disabled\"";}?> />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip07A'],$hc_lang_settings['Tip07B']);?>
		</div>
		<div class="frmOpt">
			<label for="pubCat" class="settingsLabel"><?php echo $hc_lang_settings['PublicCategories'];?></label>
			<select name="pubCat" id="pubCat" <?php if($submit == 0){echo "disabled=\"disabled\"";}?>>
				<option <?php if($userCat == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['PublicCategories0'];?></option>
				<option <?php if($userCat == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['PublicCategories1'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip08A'],$hc_lang_settings['Tip08B']);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['BillPopRSS'];?></legend>
		<div class="frmOpt">
			<label for="maxRSS" class="settingsLabel"><?php echo $hc_lang_settings['RSSSize'];?></label>
			<input name="maxRSS" id="maxRSS" type="text" size="2" maxlength="2" value="<?php echo $maxDisplay;?>" />
		</div>
		<div class="frmOpt">
			<label for="mostPopular" class="settingsLabel"><?php echo $hc_lang_settings['PopularSize'];?></label>
			<input name="mostPopular" id="mostPopular" type="text" size="2" maxlength="2" value="<?php echo $mostPopular;?>" />
		</div>
		<div class="frmOpt">
			<label for="display" class="settingsLabel"><?php echo $hc_lang_settings['BillboardSize'];?></label>
			<input name="display" id="display" type="text" size="2" maxlength="2" value="<?php echo $maxShow;?>" />
		</div>
		<div class="frmOpt">
			<label for="fill" class="settingsLabel"><?php echo $hc_lang_settings['AutoFill'];?></label>
			<select name="fill" id="fill">
				<option <?php if($fillMax == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['AutoFill1'];?></option>
				<option <?php if($fillMax == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['AutoFill0'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip09A'],$hc_lang_settings['Tip09B']);?>
		</div>
		<div class="frmOpt">
			<label for="series" class="settingsLabel"><?php echo $hc_lang_settings['EventSeries'];?></label>
			<select name="series" id="series">
				<option <?php if($series == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_settings['EventSeries1'];?></option>
				<option <?php if($series == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['EventSeries0'];?></option>
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
			<label class="settingsLabel"><?php echo $hc_lang_settings['ServerTime'];?></label>
			<b><?php echo strftime($dateFormat . " " . $timeFormat);?></b>
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
			echo "<b>" . strftime($dateFormat . " " . $timeFormat, mktime($hourOffset, date("i"), date("s"), date("m"), date("d"), date("Y"))) . "</b>";
			?>
		</div>
<?php
	}//end if	?>
		<div class="frmOpt">
			<label for="offsetTimezone" class="settingsLabel"><?php echo $hc_lang_settings['OffsetTime'];?></label>
			<select name="offsetTimezone" id="offsetTimezone">
				<option <?php if($timezoneOffset == "-12"){echo "selected=\"selected\"";}?> value="-12">-12 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-11"){echo "selected=\"selected\"";}?> value="-11">-11 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-10"){echo "selected=\"selected\"";}?> value="-10">-10 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-9"){echo "selected=\"selected\"";}?> value="-9">-09 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-8"){echo "selected=\"selected\"";}?> value="-8">-08 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-7"){echo "selected=\"selected\"";}?> value="-7">-07 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-6"){echo "selected=\"selected\"";}?> value="-6">-06 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-5"){echo "selected=\"selected\"";}?> value="-5">-05 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-4"){echo "selected=\"selected\"";}?> value="-4">-04 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-3"){echo "selected=\"selected\"";}?> value="-3">-03 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-2"){echo "selected=\"selected\"";}?> value="-2">-02 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "-1"){echo "selected=\"selected\"";}?> value="-1">-01 <?php echo $hc_lang_settings['hour'];?></option>
				<option <?php if($timezoneOffset == "0"){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_settings['ServerTime'];?></option>
				<option <?php if($timezoneOffset == "1"){echo "selected=\"selected\"";}?> value="1">+01 <?php echo $hc_lang_settings['hour'];?></option>
				<option <?php if($timezoneOffset == "2"){echo "selected=\"selected\"";}?> value="2">+02 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "3"){echo "selected=\"selected\"";}?> value="3">+03 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "4"){echo "selected=\"selected\"";}?> value="4">+04 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "5"){echo "selected=\"selected\"";}?> value="5">+05 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "6"){echo "selected=\"selected\"";}?> value="6">+06 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "7"){echo "selected=\"selected\"";}?> value="7">+07 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "8"){echo "selected=\"selected\"";}?> value="8">+08 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "9"){echo "selected=\"selected\"";}?> value="9">+09 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "10"){echo "selected=\"selected\"";}?> value="10">+10 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "11"){echo "selected=\"selected\"";}?> value="11">+11 <?php echo $hc_lang_settings['hours'];?></option>
				<option <?php if($timezoneOffset == "12"){echo "selected=\"selected\"";}?> value="12">+12 <?php echo $hc_lang_settings['hours'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip11A'],$hc_lang_settings['Tip11B']);?>
		</div>
		<div class="frmOpt">
			<label for="popDateFormat" class="settingsLabel"><?php echo $hc_lang_settings['DateFormatIn'];?></label>
			<select name="popDateFormat" id="popDateFormat">
				<option <?php if($popDateFormat == "%m/%d/%Y"){echo "selected=\"selected\"";}?> value="%m/%d/%Y">m/d/y</option>
				<option <?php if($popDateFormat == "%d/%m/%Y"){echo "selected=\"selected\"";}?> value="%d/%m/%Y">d/m/y</option>
				<option <?php if($popDateFormat == "%Y/%m/%d"){echo "selected=\"selected\"";}?> value="%Y/%m/%d">y/m/d</option>
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
				<option <?php if($timeInput == "24"){echo "selected=\"selected\"";}?> value="24"><?php echo $hc_lang_settings['24Hour'];?> (<?php echo strftime("%H:%M");?>)</option>
				<option <?php if($timeInput == "12"){echo "selected=\"selected\"";}?> value="12"><?php echo $hc_lang_settings['12Hour'];?> (<?php echo strftime("%I:%M %p");?>)</option>
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
		<div class="frmOpt">
			<label for="weather" class="settingsLabel"><?php echo $hc_lang_settings['WeatherLink'];?></label>
			<select name="weather" id="weather">
				<option <?php if($weather == 1){echo "selected=\"selected\"";}?> value="1">(US) AccuWeather</option>
				<option <?php if($weather == 0){echo "selected=\"selected\"";}?> value="0">(US) Weather.com</option>
				<option <?php if($weather == 3){echo "selected=\"selected\"";}?> value="3">(US) Yahoo Weather</option>
				<option <?php if($weather == 2){echo "selected=\"selected\"";}?> value="2">(US) Weather Underground</option>
				<option <?php if($weather == 6){echo "selected=\"selected\"";}?> value="6">(Australia) weather.News.com.au</option>
				<option <?php if($weather == 7){echo "selected=\"selected\"";}?> value="7">(Australia) Weatherzone.com.au</option>
				<option <?php if($weather == 4){echo "selected=\"selected\"";}?> value="4">(Canada) AccuWeather</option>
				<option <?php if($weather == 5){echo "selected=\"selected\"";}?> value="5">(UK) Weather.co.uk</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="driving" class="settingsLabel"><?php echo $hc_lang_settings['MapLink'];?></label>
			<select name="driving" id="driving">
				<option <?php if($driving == 0){echo "selected=\"selected\"";}?> value="0">(US &amp; Can) Google Maps</option>
				<option <?php if($driving == 1){echo "selected=\"selected\"";}?> value="1">(US &amp; Can) Mapquest</option>
				<option <?php if($driving == 2){echo "selected=\"selected\"";}?> value="2">(US &amp; Can) Yahoo Maps</option>
				<option <?php if($driving == 4){echo "selected=\"selected\"";}?> value="4">(Australia) Google Maps</option>
				<option <?php if($driving == 3){echo "selected=\"selected\"";}?> value="3">(UK) Google Maps</option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['LocationMaps'];?></legend>
		<?php echo $hc_lang_settings['GoogleNotice'];?> <a href="http://code.google.com/apis/maps/terms.html" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><?php echo $hc_lang_settings['NoShare'];?>
		<br /><br />
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
	</fieldset><br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['Eventful'];?>&nbsp;&nbsp;&nbsp;[ <a href="http://about.eventful.com/" class="eventMain" target="_blank"><?php echo $hc_lang_settings['EventfulAbout'];?></a> ]</legend>
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
			}//end for	?>&nbsp;
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
		<legend><?php echo $hc_lang_settings['TwitterLabel'];?></legend>
		<?php echo $hc_lang_settings['TwitterNotice'];?> <a href="http://twitter.com/tos" class="main" target="_blank"><?php echo $hc_lang_settings['TermsOfUse'];?></a>.
		<br /><br />
		<div class="frmOpt">
			<label for="" class="settingsLabel"><?php echo $hc_lang_settings['TwitterEmail'];?></label>
			<input name="twEmail" id="twEmail" type="text" value="<?php echo $twtrUser;?>" size="45" maxlength="200" />
			&nbsp;<?php appInstructionsIcon($hc_lang_settings['Tip21A'],$hc_lang_settings['Tip21B']);?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel"><?php echo $hc_lang_settings['Password'];?></label>
		<?php
			for($x=0;$x<strlen($twtrPass);$x++){
				echo "*";
			}//end for	?>&nbsp;
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel"><?php echo $hc_lang_settings['NewPassword'];?></label>
			<input name="twPass" id="twPass" type="password" value="" size="15" maxlength="30" />
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel"><?php echo $hc_lang_settings['ConfirmPassword'];?></label>
			<input name="twPass2" id="twPass2" type="password" value="" size="15" maxlength="30" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_settings['PendingMsg'];?></legend>
		<div class="frmOpt">
			<label for="defaultApprove" class="settingsLabel"><?php echo $hc_lang_settings['AppMessage'];?></label>
			<textarea id="defaultApprove" name="defaultApprove" style="width: 350px; height: 125px" rows="15" cols="55"><?php echo $passApprove;?></textarea>
		</div>
		<div class="frmOpt">
			<label for="defaultDecline" class="settingsLabel"><?php echo $hc_lang_settings['DecMessage'];?></label>
			<textarea id="defaultDecline" name="defaultDecline" style="width: 350px; height: 125px" rows="15" cols="55"><?php echo $passDecline;?></textarea>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_settings['SaveSettings'];?> " class="button" />
	</form>