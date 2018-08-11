<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/event.php');

	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	$docLink = 'Adding_Events';
	$hlpTitle = 'TitleAdd';
	$hlpDesc = 'InstructAdd';
	$eventBillboard = $locID = 0;
	$eventTitle = $tbd = $eventDesc = $locName = $locAddress = $locAddress2 = $locCity = $locPostal = $locCountry = $cost = 
		$contactName = $contactEmail = $contactPhone = $contactURL = $AllDay = $imageURL = $followup = $fnote = $featured = $state = '';
	$expire = $hc_cfg[134];
	$eventDate = strftime($hc_cfg[24],strtotime(SYSDATE));
	$startTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME));
	$endTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
	$startTimeMins = $endTimeMins = '00';
	$startTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME));
	$endTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
	$rsvp_type = $rsvp_space = $rsvp_disp = $rsvp_notice = 0;
	$rsvp_open = strftime($hc_cfg[24],strtotime(SYSDATE));
	$rsvp_close = strftime($hc_cfg[24],strtotime(SYSDATE));
	
	if($eID > 0){
		$hc_Side[] = array(CalRoot . '/index.php?eID=' . $eID,'calendar.png',$hc_lang_event['LinkCalendar'],1);
		$hc_Side[] = array(AdminRoot . '/index.php?com=eventedit&amp;eID=' . $eID,'edit.png',$hc_lang_event['LinkEdit'],0);
		$docLink = 'Recycling_Events';
		$hlpTitle = 'TitleRecycle';
		$hlpDesc = 'InstructRecycle';
		$result = doQuery("SELECT e.*, l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, er.*
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (er.EventID = e.PkID)
						WHERE e.PkID = '" . $eID . "' AND e.IsActive = 1");
		if(hasRows($result)){
			$eventStatus = cOut(mysql_result($result,0,17));
			$eventTitle = cOut(mysql_result($result,0,1));
			$eventDesc = cOut(mysql_result($result,0,8));
			$tbd = cOut(mysql_result($result,0,11));
			$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg[24]);
			$contactName = cOut(mysql_result($result,0,13));
			$contactEmail = cOut(mysql_result($result,0,14));
			$contactPhone = cOut(mysql_result($result,0,15));
			$eventBillboard = cOut(mysql_result($result,0,18));
			$contactURL = (mysql_result($result,0,24) != '') ? cOut(mysql_result($result,0,24)) : '';
			$views = cOut(mysql_result($result,0,26));
			$imageURL = cOut(mysql_result($result,0,38));
			$featured = cOut(mysql_result($result,0,40));
			$expire = (mysql_result($result,0,41) > 0) ? cOut(mysql_result($result,0,41)) : $hc_cfg[134];
			$locID = cOut(mysql_result($result,0,33));
			$locName = ($locID == 0) ? cOut(mysql_result($result,0,2)) : cOut(mysql_result($result,0,43));
			$locAddress = ($locID == 0) ? cOut(mysql_result($result,0,3)) : cOut(mysql_result($result,0,44));
			$locAddress2 = ($locID == 0) ? cOut(mysql_result($result,0,4)) : cOut(mysql_result($result,0,45));
			$locCity = ($locID == 0) ? cOut(mysql_result($result,0,5)) : cOut(mysql_result($result,0,46));
			$state = ($locID == 0) ? cOut(mysql_result($result,0,6)) : cOut(mysql_result($result,0,47));
			$locPostal = ($locID == 0) ? cOut(mysql_result($result,0,7)) : cOut(mysql_result($result,0,48));
			$locCountry = ($locID == 0) ? cOut(mysql_result($result,0,35)) : cOut(mysql_result($result,0,49));
			$cost = cOut(mysql_result($result,0,34));
			$rsvp_type = cOut(mysql_result($result,0,51));
			$rsvp_space = cOut(mysql_result($result,0,55));
			$rsvp_disp = cOut(mysql_result($result,0,56));
			$rsvp_notice = cOut(mysql_result($result,0,57));
			$rsvp_open = stampToDate(mysql_result($result,0,53), $hc_cfg[24]);
			$rsvp_close = stampToDate(mysql_result($result,0,54), $hc_cfg[24]);
			
			if($tbd == 0){
				$startTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				$startTimeMins = date("i", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				$startTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10)));
				if(mysql_result($result,0,12) != ''){
					$endTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
					$endTimeMins = date("i", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
					$endTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,12)));
				} else {
					$endTimeHour = date($hc_time['format'], strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10).' +1 hour'));
					$endTimeAMPM = date("A", strtotime(mysql_result($result,0,9).' '.mysql_result($result,0,10).' +1 hour'));
					$noEndTime = 1;
				}
			}
		}
	}
	$hc_Side[] = array('javascript:;','followup.png',$hc_lang_core['LinkFollow'],0,'follow_up();return false;');
	
	appInstructions(0, $docLink, $hc_lang_event[$hlpTitle], $hc_lang_event[$hlpDesc]);
	$stime_disabled = ($tbd > 0) ? ' disabled="disabled"' : '';
	$etime_disabled = (isset($noEndTime) || $tbd > 0) ? ' disabled="disabled"' : '';
	
	echo '
	<form id="frmEventAdd" name="frmEventAdd" method="post" action="'.AdminRoot.'/components/EventAddAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" id="locPreset" name="locPreset" value="'.$locID.'" />
	<input type="hidden" id="locPresetName" name="locPresetName" value="'.$locName.'" />
	<fieldset id="follow-up" style="display:none;">
		<legend>'.$hc_lang_event['FollowLabel'].'</legend>
		<label for="follow_up">'.$hc_lang_event['Follow'].'</label>
		<select name="follow_up" id="follow_up"'.($followup == 0 ? ' disabled="disabled"':'').'>
			<option value="0">'.$hc_lang_event['Follow0'].'</option>
			<option selected="selected" value="1">'.$hc_lang_event['Follow1'].'</option>
		</select>
		<label for="follow_note">'.$hc_lang_event['FollowNote'].'</label>
		<input name="follow_note" id="follow_note" type="text" size="90" maxlength="300" value=""'.($followup == 0 ? ' disabled="disabled"':'').' />
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['EventDetail'].'</legend>
		<label for="eventTitle">'.$hc_lang_event['Title'].'</label>
		<input onblur="buildT();buildF();" name="eventTitle" id="eventTitle" type="text" size="90" maxlength="150" required="required" value="'.$eventTitle.'" />
		<label for="eventDescription">'.$hc_lang_event['Description'].'</label>
		<textarea name="eventDescription" id="eventDescription" rows="20" class="mce_edit">'.$eventDesc.'</textarea>
		<label for="cost">'.$hc_lang_event['Cost'].'</label>
		<input name="cost" id="cost" type="text" size="25" maxlength="50" value="'.$cost.'" />
		<label for="imageURL">'.$hc_lang_event['Image'].'</label>
		<input name="imageURL" id="imageURL" type="url" size="85" maxlength="200" value="'.$imageURL.'" onblur="imgPreview();" />
		<div id="preview" style="display:none;">
			<label>'.$hc_lang_event['ImagePreview'].'</label>
			<div id="image_preview" class="frm_ctrls"></div>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['DateTime'].'</legend>
		<label for="eventDate">'.$hc_lang_event['Date'].'</label>
		<input name="eventDate" id="eventDate" type="text" size="12" maxlength="10" required="required" value="'.$eventDate.'" />
		<a href="javascript:;" onclick="calx.select(document.getElementById(\'eventDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>
		<label>'.$hc_lang_event['StartTime'].'</label>
		<input onblur="buildT();buildF();" name="startTimeHour" id="startTimeHour" type="text" size="2" maxlength="2" required="required" value="'.$startTimeHour.'"'.$stime_disabled.' />
		<span class="frm_ctrls">
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
		</span>
		<input onblur="buildT();buildF();" name="startTimeMins" id="startTimeMins" type="text" size="2" maxlength="2" required="required" value="'.$startTimeMins.'"'.$stime_disabled.' />
		<span class="frm_ctrls">	
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
		</span>';
		if($hc_time['input'] == 12){
			echo '
		<select onblur="buildT();buildF();" name="startTimeAMPM" id="startTimeAMPM"'.$stime_disabled.'>
			<option '.(($startTimeAMPM == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_event['AM'].'</option>
			<option '.(($startTimeAMPM == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_event['PM'].'</option>
		</select>';}
	echo '
		<label>'.$hc_lang_event['EndTime'].'</label>
		<input name="endTimeHour" id="endTimeHour" type="text" size="2" maxlength="2" required="required" value="'.$endTimeHour.'"'.$etime_disabled.' />
		<span class="frm_ctrls">	
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
		</span>
		<input name="endTimeMins" id="endTimeMins" type="text" size="2" maxlength="2" required="required" value="'.$endTimeMins.'"'.$etime_disabled.' />
		<span class="frm_ctrls">
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
			<a href="javascript:;" onclick="chngClock(document.getElementById(\'endTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
		</span>';
		if($hc_time['input'] == 12){
			echo '
		<select name="endTimeAMPM" id="endTimeAMPM"'.$etime_disabled.'>
			<option '.(($endTimeAMPM == 'AM') ? 'selected="selected" ' : '').'value="AM">'.$hc_lang_event['AM'].'</option>
			<option '.(($endTimeAMPM == 'PM') ? 'selected="selected" ' : '').'value="PM">'.$hc_lang_event['PM'].'</option>
		</select>';}
	echo '
		<span class="frm_ctrls">
			<label for="ignoreendtime"><input name="ignoreendtime" id="ignoreendtime" type="checkbox"'.((isset($noEndTime)) ? ' checked="checked"':'').$stime_disabled.' onclick="togEndTime(this.checked);" />'.$hc_lang_event['NoEndTime'].'</label>
		</span>
		<label>&nbsp;</label>
		<span class="frm_ctrls">
			<label for="overridetime"><input type="checkbox" name="overridetime" id="overridetime"'.(($tbd > 0) ? ' checked="checked"':'').' onclick="togOverride();" />'.$hc_lang_event['Override'].'</label>
			<label for="specialtimeall"><input type="radio" name="specialtime" id="specialtimeall" value="allday"'.(($tbd == 0) ? ' disabled="disabled"':'').(($tbd < 2) ? ' checked="checked"':'').' />'. $hc_lang_event['AllDay'].'</label>
			<label for="specialtimetbd"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd"'.(($tbd == 0) ? ' disabled="disabled"':'').(($tbd == 2) ? 'checked="checked"':'').' />'. $hc_lang_event['TBA'].'</label>
		</span>
		<label>'.$hc_lang_event['Recur'].'</label>
		<span class="frm_ctrls">
			<label for="recurCheck"><input name="recurCheck" id="recurCheck" type="checkbox" onclick="togRecur();toggleMe(document.getElementById(\'recur_inpts\'));" /> '.$hc_lang_event['RecurCheck'].'</label>
		</span>
		<div id="recur_inpts" style="display:none;">
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<label for="recurType1"><input name="recurType" id="recurType1" type="radio" value="daily" disabled="disabled" checked="checked" onclick="togArray(recOpts,\'daily\')" />'.$hc_lang_event['RecDaily'].'</label>
				<label for="recurType2"><input name="recurType" id="recurType2" type="radio" value="weekly" disabled="disabled" onclick="togArray(recOpts,\'weekly\')" />'.$hc_lang_event['RecWeekly'].'</label>
				<label for="recurType3"><input name="recurType" id="recurType3" type="radio" value="monthly" disabled="disabled" onclick="togArray(recOpts,\'monthly\')" />'.$hc_lang_event['RecMonthly'].'</label>
			</span>
			<div id="daily" class="frm_ctrls">
				<label for="recDaily1"><input name="dailyOptions" id="recDaily1" type="radio" checked="checked" disabled="disabled" value="EveryXDays" />'.$hc_lang_event['Every'].'</label><input id="dailyDays" name="dailyDays" type="number" min="1" max="31" size="3" maxlength="2" value="1" disabled="disabled" />'.$hc_lang_event['xDays'].'<br />
				<label for="recDaily2"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" />'.$hc_lang_event['Daily2'].'</label>
			</div>
			<div id="weekly" class="frm_ctrls" style="display:none;">
				'.$hc_lang_event['Every'].'<input name="recWeekly" id="recWeekly" type="number" min="1" max="52" size="3" maxlength="2" value="1" />'.$hc_lang_event['xWeeks'].'<br />
				<label for="recWeeklyDay_0"><input id="recWeeklyDay_0" name="recWeeklyDay[]" type="checkbox" value="0" />'.$hc_lang_event['Sun'].'</label>
				<label for="recWeeklyDay_1"><input id="recWeeklyDay_1" name="recWeeklyDay[]" type="checkbox" value="1" />'.$hc_lang_event['Mon'].'</label>
				<label for="recWeeklyDay_2"><input id="recWeeklyDay_2" name="recWeeklyDay[]" type="checkbox" value="2" />'.$hc_lang_event['Tue'].'</label>
				<label for="recWeeklyDay_3"><input id="recWeeklyDay_3" name="recWeeklyDay[]" type="checkbox" value="3" />'.$hc_lang_event['Wed'].'</label>
				<label for="recWeeklyDay_4"><input id="recWeeklyDay_4" name="recWeeklyDay[]" type="checkbox" value="4" />'.$hc_lang_event['Thu'].'</label>
				<label for="recWeeklyDay_5"><input id="recWeeklyDay_5" name="recWeeklyDay[]" type="checkbox" value="5" />'.$hc_lang_event['Fri'].'</label>
				<label for="recWeeklyDay_6"><input id="recWeeklyDay_6" name="recWeeklyDay[]" type="checkbox" value="6" />'.$hc_lang_event['Sat'].'</label>
			</div>
			<div id="monthly" class="frm_ctrls" style="display:none;">
				<label><input name="monthlyOption" id="monthlyOption1" type="radio" checked="checked" value="Day" />'.$hc_lang_event['Day'].'<input name="monthlyDays" id="monthlyDays" type="number" min="1" max="31" size="3" maxlength="2" value="'.date("d").'" />'.$hc_lang_event['ofEvery'].'<input name="monthlyMonths" id="monthlyMonths" type="number" min="1" max="12" size="3" maxlength="2" value="1" />'.$hc_lang_event['Months'].'</label>
				<label>
					<input name="monthlyOption" id="monthlyOption2" type="radio" value="Month" />
					<select name="monthlyMonthOrder" id="monthlyMonthOrder">
						<option value="1">'.$hc_lang_event['First'].'</option>
						<option value="2">'.$hc_lang_event['Second'].'</option>
						<option value="3">'.$hc_lang_event['Third'].'</option>
						<option value="4">'.$hc_lang_event['Fourth'].'</option>
						<option value="0">'.$hc_lang_event['Last'].'</option>
					</select>
					<select name="monthlyMonthDOW" id="monthlyMonthDOW">
						<option '.((date("w") == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_event['Sun'].'</option>
						<option '.((date("w") == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_event['Mon'].'</option>
						<option '.((date("w") == 2) ? 'selected="selected" ' : '').'value="2">'.$hc_lang_event['Tue'].'</option>
						<option '.((date("w") == 3) ? 'selected="selected" ' : '').'value="3">'.$hc_lang_event['Wed'].'</option>
						<option '.((date("w") == 4) ? 'selected="selected" ' : '').'value="4">'.$hc_lang_event['Thu'].'</option>
						<option '.((date("w") == 5) ? 'selected="selected" ' : '').'value="5">'.$hc_lang_event['Fri'].'</option>
						<option '.((date("w") == 6) ? 'selected="selected" ' : '').'value="6">'.$hc_lang_event['Sat'].'</option>
					</select>
					'.$hc_lang_event['ofEvery'].'<input name="monthlyMonthRepeat" id="monthlyMonthRepeat" type="number" min="1" max="12" size="3" maxlength="2" value="1" />'.$hc_lang_event['Months'].'
				</label>
			</div>
			<label for="recurEndDate">'.$hc_lang_event['RecurUntil'].'</label>
			<input name="recurEndDate" id="recurEndDate" type="text" disabled="disabled" size="12" maxlength="10" required="required" value="" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'recurEndDate\'),\'cal2\',\''.$hc_cfg[51].'\');return false;" id="cal2" class="ds calendar" tabindex="-1"></a>
			<label>&nbsp;</label>
			<div id="recur_chk">
				<a href="javascript:;" onclick="confirmRecurDates();">'.$hc_lang_event['ConfirmDate'].'</a>
			</div>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['RegTitle'].'</legend>
		<label for="rsvp_type">'.$hc_lang_event['Registration'].'</label>
		<select name="rsvp_type" id="rsvp_type" onchange="togRegistration();">
			<option '.(($rsvp_type == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_event['Reg0'].'</option>
			<option '.(($rsvp_type == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_event['Reg1'].'</option>
			<option '.(($rsvp_type == 2) ? 'selected="selected" ' : '').'value="2">'.$hc_lang_event['Reg2'].'</option>
		</select>
		<div id="rsvp"'.($rsvp_type != 1 ? ' style="display:none;"':'').'>
			<label for="rsvp_space">'.$hc_lang_event['Limit'].'</label>
			<input name="rsvp_space" id="rsvp_space" type="number" min="0" max="9999" size="5" maxlength="4" value="'.$rsvp_space.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
			<span class="output">'.$hc_lang_event['LimitLabel'].'</span>
			<label>'.$hc_lang_event['Allow'].'</label>
			<input name="openDate" id="openDate" type="text" size="12" maxlength="10" value="'.$rsvp_open.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'openDate\'),\'cal3\',\''.$hc_cfg[51].'\');return false;" id="cal3" class="ds calendar" tabindex="-1"></a>
			<span class="output">&nbsp;&nbsp;'.$hc_lang_event['To'].'&nbsp;&nbsp;</span>
			<input name="closeDate" id="closeDate" type="text" size="12" maxlength="10" value="'.$rsvp_close.'"'.($rsvp_type != 1 ? ' disabled="disabled"':'').' required="required" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'closeDate\'),\'cal4\',\''.$hc_cfg[51].'\');return false;" id="cal4" class="ds calendar" tabindex="-1"></a>
			<label for="rsvpFor">'.$hc_lang_event['RSVPFor'].'</label>
			<select name="rsvpFor" id="rsvpFor"'.($rsvp_type != 1 ? ' disabled="disabled"':'').'>
				<option '.(($rsvp_disp == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_event['RSVPFor0'].'</option>
				<option '.(($rsvp_disp == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_event['RSVPFor1'].'</option>
			</select>
			<span class="output">
				<a class="tooltip" data-tip="'.$hc_lang_event['Tip01'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
			</span>
			<label for="rsvpEmail">'.$hc_lang_event['EmailNotice'].'</label>
			<select name="rsvpEmail" id="rsvpEmail"'.($rsvp_type != 1 ? ' disabled="disabled"':'').'>
				<option '.(($rsvp_notice == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_event['EmailNotice0'].'</option>
				<option '.(($rsvp_notice == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_event['EmailNotice1'].'</option>
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['Settings'].'</legend>
		<label for="eventStatus">'.$hc_lang_event['Status'].'</label>
		<select name="eventStatus" id="eventStatus">
			<option value="1">'.$hc_lang_event['Status1'].'</option>
			<option value="2">'.$hc_lang_event['Status2'].'</option>
		</select>
		<label for="eventBillboard">'.$hc_lang_event['Billboard'].'</label>
		<select name="eventBillboard" id="eventBillboard">
			<option'.(($eventBillboard == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_event['Billboard0'].'</option>
			<option'.(($eventBillboard == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_event['Billboard1'].'</option>
		</select>
		<label for="eventFeatured">'.$hc_lang_event['Featured'].'</label>
		<select name="eventFeatured" id="eventFeatured">
			<option'.(($featured == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_event['Featured0'].'</option>
			<option'.(($featured == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_event['Featured1'].'</option>
		</select>
		<label for="eventHide">'.$hc_lang_event['Expire'].'</label>
		<input name="eventHide" id="eventHide" type="number" min="1" max="999" size="4" maxlength="3" value="'.$expire.'" required="required" />
		<span class="output">'.$hc_lang_event['Days'].'</span>
		<label>'.$hc_lang_event['Categories'].'</label>';
	
	$query = ($eID > 0) ? "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, ec.EventID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
			WHERE c.ParentID = 0 AND c.IsActive = 1
			GROUP BY c.PkID, c.CategoryName, c.ParentID, ec.EventID
			UNION
			SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, ec.EventID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
			WHERE c.ParentID > 0 AND c.IsActive = 1
			GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, ec.EventID
			ORDER BY Sort, ParentID, CategoryName" : NULL;
	getCategories('frmEventAdd',3,$query,1);
	
	echo '
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['Location'].'</legend>';
		echo ($locID > 0) ? '
		<div id="locSetting" class="frm_ctrl">
			<label>' . $hc_lang_event['CurLocation'] . '</label>
			<span class="output">
				<b>'.$locName.'</b><br />
				'.buildAddress($locAddress,$locAddress2,$locCity,$state,$locPostal,$locCountry,$hc_lang_config['AddressType']).'
			</span>
			<label>&nbsp;</label>
			<span class="output">
				<a href="javascript:;" onclick="setLocation(0,\'\',1);" class="locChange">' . $hc_lang_event['ChngLocation'] . '</a>
			</span>
		</div>' : '';
	echo '
		<div id="locSearch" '.(($locID > 0) ? ' style="display:none;"' : '').'>';
		location_select();

		$inputs = array(1 => array('City','locCity',$locCity),2 => array('Postal','locZip',$locPostal));
		$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
		$second = ($first == 1) ? 2 : 1;

	echo '
		</div>
		<div id="custom"'.(($locID > 0) ? ' style="display:none;"' : '').'>
			<label for="locName">'.$hc_lang_event['Name'].'</label>
			<input onblur="buildT();buildF();" name="locName" id="locName" type="text" size="25" maxlength="50" value="'.(($locID < 1) ? $locName : '').'" />
			<label for="locAddress">'.$hc_lang_event['Address'].'</label>
			<input name="locAddress" id="locAddress" type="text" size="30" maxlength="75" value="'.(($locID < 1) ? $locAddress : '').'" /><span class="output req2">*</span>
			<label for="locAddress2">'.$hc_lang_event['Address2'].'</label>
			<input name="locAddress2" id="locAddress2" type="text" size="25" maxlength="75" value="'.(($locID < 1) ? $locAddress2 : '').'" />
			<label for="' . $inputs[$first][1] . '">' . $hc_lang_event[$inputs[$first][0]] . '</label>
			<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" value="'.(($locID < 1) ? $inputs[$first][2] : '').'" /><span class="output req2">*</span>';

		if($hc_lang_config['AddressRegion'] != 0){	
			echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
			$regSelect = '';
			include(HCLANG.'/'.$hc_lang_config['RegionFile']);
			echo '<span class="output req2">*</span>';}

		echo '<label for="'.$inputs[$second][1].'">'.$hc_lang_event[$inputs[$second][0]].'</label>
			<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" value="'.(($locID < 1) ? $inputs[$second][2] : '').'" /><span class="output req2">*</span>
			<label for="locCountry">'.$hc_lang_event['Country'].'</label>
			<input name="locCountry" id="locCountry" type="text" size="10" maxlength="50" value="'.(($locID < 1) ? $locCountry : '').'" />
		</div>
		<div id="custom_notice" style="display:none;">
			<label>&nbsp;</label>
			<b>'.$hc_lang_core['PresetLoc'].'</b>
		</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_event['Contact'].'</legend>
		<label for="contactName">'.$hc_lang_event['Name'].'</label>
		<input name="contactName" id="contactName" type="text" maxlength="50" size="20" value="'.$contactName.'" /><span class="output req3">*</span>
		<label for="contactEmail">'.$hc_lang_event['Email'].'</label>
		<input name="contactEmail" id="contactEmail" type="email" maxlength="75" size="30" value="'.$contactEmail.'" /><span class="output req3">*</span>
		<label for="contactPhone">'.$hc_lang_event['Phone'].'</label>
		<input name="contactPhone" id="contactPhone" type="text" maxlength="25" size="20" value="'.$contactPhone.'" />
		<label for="contactURL">'.$hc_lang_event['Website'].'</label>
		<input name="contactURL" id="contactURL" type="url" maxlength="100" size="40" value="'.$contactURL.'" />
	</fieldset>';
			
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,46,47,57,58,120,123)");
	$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '') ? 1 : 0;
	$goTwitter = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '') ? 1 : 0;
	$goBitly = (mysql_result($result,4,1) != '' && mysql_result($result,5,1) != '') ? 1 : 0;
	$ebOrganziers = ($goEventbrite == 1) ? eventbrite_get_organizers() : array();
	$goPaypal = ($goEventbrite == 1 && $hc_cfg[103] != '') ? 1 : 0;
	$goGoogleC = ($goEventbrite == 1 && $hc_cfg[104] != '' && $hc_cfg[105] != '') ? 1 : 0;
	$goFacebook = (mysql_result($result,6,1) != '' && mysql_result($result,7,1) != '') ? 1 : 0;
		
	echo '	
	<fieldset>
		<legend>'.$hc_lang_event['DistPub'].'</legend>
		<label for="doFacebook" class="distPub distPubTop">
			<input name="doFacebook" id="doFacebook" type="checkbox" onclick="toggleMe(document.getElementById(\'facebook\'));"'.(($goFacebook == 0) ?  ' disabled="disabled"':'').' />
			'.$hc_lang_event['FacebookLabelA'].'
		</label>
		<div id="facebook" style="display:none;">
			<label for="facebookStatus" class="distPubFB"><input name="facebookStatus" id="facebookStatus" type="checkbox" onclick="toggleMe(document.getElementById(\'facebookStatusLabel\'));buildF();" /> '.$hc_lang_event['FacebookStatus'].'</label>
			<div id="facebookStatusLabel" style="display:none;">
				<input name="fbThis" id="fbThis" type="text" size="45" maxlength="250" value="" />
				'.$hc_lang_event['FacebookNotice'].'
			</div>
			<label for="facebookEvent" class="distPubFB"><input name="facebookEvent" id="facebookEvent" type="checkbox" onclick="toggleMe(document.getElementById(\'facebookEventLabel\'));" /> '.$hc_lang_event['FacebookEvent'].'</label>
			<div id="facebookEventLabel" style="display:none;">'.$hc_lang_event['FacebookEventA'].'</div>
		</div>
		<label for="doTwitter" class="distPub">
			<input name="doTwitter" id="doTwitter" type="checkbox" onclick="toggleMe(document.getElementById(\'twitter\'));buildT();"'.(($goTwitter == 0 || $goBitly == 0) ?  ' disabled="disabled"':'').' />
			'.$hc_lang_event['TwitterLabel'].'
		</label>
		<div id="twitter" style="display:none;">
			<input name="tweetThis" id="tweetThis" type="text" size="45" maxlength="104" value="" />
			' . $hc_lang_event['TwitterNotice'] . '
		</div>
		<label for="doEventbrite" class="distPub">
			<input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));chkReg();"'.(($goEventbrite == 0) ?  ' disabled="disabled"':'').' />
			'.$hc_lang_event['EventbriteLabelA'] . '
		</label>
		<div id="eventbrite" style="display:none;">
			<p>'.$hc_lang_event['EventbriteNotice'].'</p>
			<label for="ebOrgID"><b>'.$hc_lang_event['EBOrganizer'].'</b></label>
			<select name="ebOrgID" id="ebOrgID">';

			foreach($ebOrganziers as $id => $name){
				echo '
				<option'.(($hc_cfg[62] == $id) ? ' selected="selected"' : '').' value="'.$id.'">'.(($name != '') ? $name : '').' (#'.$id.')</option>';
			}
			
			echo '
			</select>
			<label for="ebStatus"><b>' . $hc_lang_event['EBStatus'] . '</b></label>
			<select name="ebStatus" id="ebStatus">
				<option value="live">'.$hc_lang_event['EBStatus1'].'</option>
				<option value="draft">'.$hc_lang_event['EBStatus0'].'</option>
			</select>
			<label for="ebPrivacy"><b>' . $hc_lang_event['EBPrivacy'] . '</b></label>
			<select name="ebPrivacy" id="ebPrivacy">
				<option value="1">'.$hc_lang_event['EBPrivacy1'].'</option>
				<option value="0">'.$hc_lang_event['EBPrivacy0'].'</option>
			</select>
			<label for="ebTimezone"><b>' . $hc_lang_event['EventTimezone'] . '</b></label>
			<select name="ebTimezone" id="ebTimezone">';
			
			$tz_default = (function_exists('ini_get')) ? ini_get('date.timezone') : '';
			include(HCPATH.HCINC.'/functions/tz.php');
			foreach($hc_master_tz as $key => $arr){
				echo '
				<option'.(($tz_default == $key) ? ' selected="selected"':'').' value="'.$key.'">'.$key.' (UTC'.((date('I', time()) == 0) ? $arr['utc'] : $arr['utc_dst']).')</option>';
			}
		
		echo '
			</select>
			<label><b>'.$hc_lang_event['EBPayment'].'</b></label>
			<span class="frm_ctrls">
				<label for="ebPaypal"><input'.(($goPaypal == 1) ? ' checked="checked"':' disabled="disabled"').' name="ebPaypal" id="ebPaypal" type="checkbox" value="" />'.$hc_lang_event['EBPayment0'].'</label>
				<label for="ebGoogleC"><input'.(($goGoogleC == 1) ? ' checked="checked"':' disabled="disabled"').' name="ebGoogleC" id="ebGoogleC" type="checkbox" value="" />'.$hc_lang_event['EBPayment1'].'</label>
			</span>
			<label for="ebCurrency"><b>' . $hc_lang_event['EventCurrency'] . '</b></label>
			<select name="ebCurrency" id="ebCurrency">
				<option value="USD">'.$hc_lang_event['USD'].'</option>
				<option value="EUR">'.$hc_lang_event['EUR'].'</option>
				<option value="GBP">'.$hc_lang_event['GBP'].'</option>
				<option value="JPY">'.$hc_lang_event['JPY'].'</option>
				<option value="AUD">'.$hc_lang_event['AUD'].'</option>
				<option value="CAD">'.$hc_lang_event['CAD'].'</option>
				<option value="CZK">'.$hc_lang_event['CZK'].'</option>
				<option value="DKK">'.$hc_lang_event['DKK'].'</option>
				<option value="HKD">'.$hc_lang_event['HKD'].'</option>
				<option value="HUF">'.$hc_lang_event['HUF'].'</option>
				<option value="NZD">'.$hc_lang_event['NZD'].'</option>
				<option value="NOK">'.$hc_lang_event['NOK'].'</option>
				<option value="PLN">'.$hc_lang_event['PLN'].'</option>
				<option value="SGD">'.$hc_lang_event['SGD'].'</option>
				<option value="SEK">'.$hc_lang_event['SEK'].'</option>
				<option value="CHF">'.$hc_lang_event['CHF'].'</option>
				<option value="ILS">'.$hc_lang_event['ILS'].'</option>
				<option value="MXN">'.$hc_lang_event['MXN'].'</option>
			</select>
			<div class="data">
				<li class="header row" style="padding-top:10px;">
					<div class="txt" style="width:40%;">'.$hc_lang_event['TicketName'].'</div>
					<div class="txt" style="width:20%;">'.$hc_lang_event['TicketPrice'].'</div>
					<div class="tools" style="width:12%;">'.$hc_lang_event['TicketQty'].'</div>
					<div class="tools" style="width:11%;">'.$hc_lang_event['TicketFee'].'</div>
					<div class="tools" style="width:12%;">'.$hc_lang_event['TicketEndDate'].'*</div>
				</li>';
		
		for($x=1;$x<=5;++$x){
			$hl = ($x % 2 == 0) ? ' hl':'';
			echo '
				<li class="row'.$hl.'">
					<input type="hidden" name="ticketid' . $x . '" id="ticketid' . $x . '" value="" />
					<div class="txt" style="width:29%;">
						<input name="ticket' . $x . '" id="ticket' . $x . '" type="text" size="30" maxlength="200" value="" />
					</div>
					<div class="txt" style="width:32%;">
						<input onclick="togTicketPrice('.$x.',0);" name="priceType'.$x.'" type="radio" value="0" checked="checked" /><input name="price' . $x . '" id="price' . $x . '" type="text" size="5" maxlength="7" value="" />
						<input onclick="togTicketPrice('.$x.',1);" id="priceType1'.$x.'" name="priceType'.$x.'" type="radio" value="1" /><label for="priceType1'.$x.'">'.$hc_lang_event['Free'].'</label>
						<input onclick="togTicketPrice('.$x.',1);" id="priceType2'.$x.'" name="priceType'.$x.'" type="radio" value="2" /><label for="priceType2'.$x.'">'.$hc_lang_event['Donate'].'</label>
					</div>
					<div class="tools" style="width:12%;">
						<input name="qty' . $x . '" id="qty' . $x . '" type="text" size="5" maxlength="5" value="" />
					</div>
					<div class="tools" style="width:8%;">
						<input name="fee' . $x . '" id="fee' . $x . '" type="checkbox" value="" />
					</div>
					<div class="txt" style="width:18%;">
						<input name="end' . $x . '" id="end' . $x . '" type="text" size="12" maxlength="10" value="" />
						<a href="javascript:;" onclick="calx.select(document.getElementById(\'end' . $x . '\'),\'caltix'.$x.'\',\''.$hc_cfg[51].'\');return false;" id="caltix'.$x.'" class="ds calendar" tabindex="-1"></a>
					</div>
				</li>';
		}
		
		echo '
			<p style="padding-top:5px;">
				*<i>'.$hc_lang_event['TicketEndNote'].'</i>
			</p>
			</div>
		</div>
		<label for="doBitly" class="distPub">
			<input name="doBitly" id="doBitly" type="checkbox" onclick="toggleMe(document.getElementById(\'bitly\'));"'.(($goBitly == 0) ?  ' disabled="disabled"':'').' />
			'.$hc_lang_event['BitlyLabel'].'
		</label>
		<div id="bitly" style="display:none;">' . $hc_lang_event['BitlyNotice'] . '</div>
	</fieldset>
	<input name="submit" id="submit" type="submit" value="'.$hc_lang_event['Save'].'" />
	</form>
	<div id="dsCal" class="datePicker"></div>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
	<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
	<script>
	//<!--
	var tweetPrefix = "'.$hc_lang_event['TweetNew'].'";
	var recOpts = new Array("daily","weekly","monthly");
	var calx = new CalendarPopup("dsCal");
	calx.showNavigationDropdowns();
	calx.setCssPrefix("hc_");
	calx.offsetX = 30;
	calx.offsetY = -5;
	
	function togRecur(){
		var inputs = (document.getElementById(\'recurCheck\').checked) ? false : true;
		document.getElementById("recurType1").disabled = inputs;
		document.getElementById("recurType2").disabled = inputs;
		document.getElementById("recurType3").disabled = inputs;
		document.getElementById("recWeeklyDay_0").disabled = inputs;
		document.getElementById("recWeeklyDay_1").disabled = inputs;
		document.getElementById("recWeeklyDay_2").disabled = inputs;
		document.getElementById("recWeeklyDay_3").disabled = inputs;
		document.getElementById("recWeeklyDay_4").disabled = inputs;
		document.getElementById("recWeeklyDay_5").disabled = inputs;
		document.getElementById("recWeeklyDay_6").disabled = inputs;
		document.getElementById("recDaily1").disabled = inputs;
		document.getElementById("recDaily2").disabled = inputs;
		document.getElementById("dailyDays").disabled = inputs;
		document.getElementById("recWeekly").disabled = inputs;
		document.getElementById("monthlyOption1").disabled = inputs;
		document.getElementById("monthlyOption2").disabled = inputs;
		document.getElementById("monthlyDays").disabled = inputs;
		document.getElementById("recurEndDate").disabled = inputs;
		document.getElementById("monthlyMonths").disabled = inputs;
		document.getElementById("monthlyMonthDOW").disabled = inputs;
		document.getElementById("monthlyMonthRepeat").disabled = inputs;
		document.getElementById("monthlyMonthOrder").disabled = inputs;
	}
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("eventTitle"),"'.$hc_lang_event['Valid13'].'\n");

		try{
			err +=chkTinyMCE(tinyMCE.get("eventDescription").getContent(),"'.$hc_lang_event['Valid01'].'\n");}
		catch(error){
			err +=reqField(document.getElementById("eventDescription"),"'.$hc_lang_event['Valid01'].'\n");}

		if(document.getElementById("rsvp_type").value == 1){
			err +=reqField(document.getElementById("rsvp_space"),"'.$hc_lang_event['Valid60'].'\n");
			err +=validNumber(document.getElementById("rsvp_space"),"'.$hc_lang_event['Valid02'].'\n");
			err +=validGreater(document.getElementById("rsvp_space"),-1,"'.$hc_lang_event['Valid60'].'\n");
			err +=reqField(document.getElementById("openDate"),"'.$hc_lang_event['Valid71'].'\n");
			err +=reqField(document.getElementById("closeDate"),"'.$hc_lang_event['Valid72'].'\n");				
			if(document.getElementById("openDate").value != ""){
				err +=validDate(document.getElementById("openDate"),"'.$hc_cfg[51].'","'.$hc_lang_event['Valid73'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("eventDate").value,"'.$hc_cfg[51].'","'.$hc_lang_event['Valid74'].'\n")
			}
			if(document.getElementById("closeDate").value != ""){
				var closeLimit = document.getElementById("recurCheck").checked ? document.getElementById("recurEndDate").value : document.getElementById("eventDate").value;
				err +=validDate(document.getElementById("closeDate"),"'.$hc_cfg[51].'","'.$hc_lang_event['Valid75'].' '.strtoupper($hc_cfg[51]).'\n");
				err +=validDateBefore(document.getElementById("closeDate").value,closeLimit,"'.$hc_cfg[51].'","'.$hc_lang_event['Valid76'].'\n")
			}
			err +=validDateBefore(document.getElementById("openDate").value,document.getElementById("closeDate").value,"'.$hc_cfg[51].'","'.$hc_lang_event['Valid77'].'\n")
			err +=reqField(document.getElementById("contactName"),"'.$hc_lang_event['Valid03'].'\n");
			err +=reqField(document.getElementById("contactEmail"),"'.$hc_lang_event['Valid04'].'\n");
		}
		
		chkd = chkDate();
		if(chkd == -1)
			return false;
		else
			err += chkd;

		err +=validNumber(document.getElementById("startTimeHour"),"'.$hc_lang_event['Valid05'].'\n");
		err +=validNumberRange(document.getElementById("startTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_event['Valid06']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=validNumber(document.getElementById("startTimeMins"),"'.$hc_lang_event['Valid07'].'\n");
		err +=validNumberRange(document.getElementById("startTimeMins"),0,59,"'.$hc_lang_event['Valid08'].'\n");
		err +=validNumber(document.getElementById("endTimeHour"),"'.$hc_lang_event['Valid09'].'\n");
		err +=validNumberRange(document.getElementById("endTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_event['Valid10']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
		err +=validNumber(document.getElementById("endTimeMins"),"'.$hc_lang_event['Valid11'].'\n");
		err +=validNumberRange(document.getElementById("endTimeMins"),0,59,"'.$hc_lang_event['Valid12'].'\n");

		if(document.getElementById("recurCheck").checked)
			err +=chkRecur();

		err += reqField(document.getElementById("eventHide"),"'.$hc_lang_event['Valid70'].'\n");
		if(document.getElementById("eventHide").value != ""){
			err += validNumber(document.getElementById("eventHide"),"'.$hc_lang_event['Valid68'].'\n");
			err += validGreater(document.getElementById("eventHide"),-1,"'.$hc_lang_event['Valid69'].'\n");
		}
		
		err +=validCheckArray("frmEventAdd","catID[]",1,"'.$hc_lang_event['Valid15'].'\n");
		
		if(document.getElementById("locPreset").value == 0)
			err +=reqField(document.getElementById("locName"),"'.$hc_lang_event['Valid16'].'\n");
		if(document.getElementById("contactEmail").value != "")
			err +=validEmail(document.getElementById("contactEmail"),"'.$hc_lang_event['Valid21'].'\n");
		if(document.getElementById("doEventbrite").checked){
			err +=reqField(document.getElementById("ticket1"),"'.$hc_lang_event['Valid61'].'\n");

			for(x=1;x<=5;x++){
				if(document.getElementById("ticket" + x).value != "" && ((document.getElementById("price" + x).disabled == false && document.getElementById("price" + x).value == "") || document.getElementById("qty" + x).value == ""))
					err += "'.$hc_lang_event["Valid65"].'\n";
			}
			for(x=1;x<=5;x++){
				if(document.getElementById("price" + x).disabled == false && document.getElementById("price" + x).value != "" && (document.getElementById("price" + x).value != parseFloat(document.getElementById("price" + x).value)))
					err += "'.$hc_lang_event["Valid62"].'\n";
			}
			for(x=1;x<=5;x++){
				if(document.getElementById("qty" + x).value != "" && (document.getElementById("qty" + x).value != parseInt(document.getElementById("qty" + x).value)))
					err += "'.$hc_lang_event["Valid63"].'\n";
			}
		}
		if(document.getElementById("doTwitter").checked)
			err +=reqField(document.getElementById("tweetThis"),"'.$hc_lang_event['Valid64'].'\n");
		
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}
	function chkRecur(){
		var err = "";
		err += reqField(document.getElementById("recurEndDate"),"'.$hc_lang_event['Valid25'].'\n");
		if(document.getElementById("recurEndDate").value != ""){
			err += validDate(document.getElementById("recurEndDate"),"'.$hc_cfg[51].'","'.$hc_lang_event['Valid26'].' '.strtoupper($hc_cfg[51]).'\n");
			err += validDateBefore(document.getElementById("eventDate").value,document.getElementById("recurEndDate").value,"'.$hc_cfg[51].'","'.$hc_lang_event['Valid27'].'\n");
			err += validNotEqual(document.getElementById("recurEndDate"),document.getElementById("eventDate"),"'.$hc_lang_event['Valid28'].'\n");
		}
		if(document.getElementById("recurType1").checked){
			if(document.getElementById("recDaily1").checked){
				err += reqField(document.getElementById("dailyDays"),"'.$hc_lang_event['Valid31'].'\n");
				err += validNumber(document.getElementById("dailyDays"),"'.$hc_lang_event['Valid29'].'\n");
				err += validGreater(document.getElementById("dailyDays"),0,"'.$hc_lang_event['Valid30'].'\n");
			}
		} else if(document.getElementById("recurType2").checked) {
			err += reqField(document.getElementById("recWeekly"),"'.$hc_lang_event['Valid34'].'\n");
			err += validNumber(document.getElementById("recWeekly"),"'.$hc_lang_event['Valid32'].'\n");
			err += validGreater(document.getElementById("recWeekly"),0,"'.$hc_lang_event['Valid33'].'\n");
			err += validCheckArray("frmEventAdd","recWeeklyDay[]",1,"'.$hc_lang_event['Valid35'].'\n");
		} else if(document.getElementById("recurType3").checked) {
			if(document.getElementById("monthlyOption1").checked){
				err += reqField(document.getElementById("monthlyDays"),"'.$hc_lang_event['Valid38'].'\n");
				err += validNumber(document.getElementById("monthlyDays"),"'.$hc_lang_event['Valid36'].'\n");
				err += validGreater(document.getElementById("monthlyDays"),0,"'.$hc_lang_event['Valid37'].'\n");
				err += reqField(document.getElementById("monthlyMonths"),"'.$hc_lang_event['Valid41'].'\n");
				err += validNumber(document.getElementById("monthlyMonths"),"'.$hc_lang_event['Valid39'].'\n");
				err += validGreater(document.getElementById("monthlyMonths"),0,"'.$hc_lang_event['Valid40'].'\n");
			} else if(document.getElementById("monthlyOption2").checked){
				err += reqField(document.getElementById("monthlyMonthRepeat"),"'.$hc_lang_event['Valid44'].'\n");
				err += validNumber(document.getElementById("monthlyMonthRepeat"),"'.$hc_lang_event['Valid42'].'\n");
				err += validGreater(document.getElementById("monthlyMonthRepeat"),0,"'.$hc_lang_event['Valid43'].'\n");
			}
		}
		return err;
	}
	function confirmRecurDates(){
		if(!document.getElementById("recurCheck").checked){
			alert("'.$hc_lang_event['Valid46'].'");
			return false;
		}

		var err = "";
		chkd = chkDate();
		if(chkd == -1)
			return false;
		else
			err += chkd;
		
		err += chkRecur();

		if(err != ""){
			alert(err + "\n\n'.$hc_lang_event['Valid46'].'");
		} else {
			var qStr = "";
			if(document.getElementById("recurType1").checked){
				qStr = qStr + "?recurType=daily";
				if(document.getElementById("recDaily1").checked)
					qStr = qStr + "&dailyOptions=EveryXDays&dailyDays=" + document.getElementById("dailyDays").value;
				else
					qStr = qStr + "&dailyOptions=WeekdaysOnly";
			} else if(document.getElementById("recurType2").checked) {
				var dArr = "";
				if(document.getElementById("recWeeklyDay_0").checked){dArr = dArr + ",0"}
				if(document.getElementById("recWeeklyDay_1").checked){dArr = dArr + ",1"}
				if(document.getElementById("recWeeklyDay_2").checked){dArr = dArr + ",2"}
				if(document.getElementById("recWeeklyDay_3").checked){dArr = dArr + ",3"}
				if(document.getElementById("recWeeklyDay_4").checked){dArr = dArr + ",4"}
				if(document.getElementById("recWeeklyDay_5").checked){dArr = dArr + ",5"}
				if(document.getElementById("recWeeklyDay_6").checked){dArr = dArr + ",6"}
				qStr = qStr + "?recurType=weekly&recWeekly=" + document.getElementById("recWeekly").value + "&recWeeklyDay=" + dArr.substring(1);
			} else if(document.getElementById("recurType3").checked) {
				qStr = qStr + "?recurType=monthly";
				if(document.getElementById("monthlyOption1").checked)
					qStr = qStr + "&monthlyOption=Day&monthlyDays=" + document.getElementById("monthlyDays").value + "&monthlyMonths=" + document.getElementById("monthlyMonths").value;
				else
					qStr = qStr + "&monthlyOption=Month&monthlyMonthOrder=" + document.getElementById("monthlyMonthOrder").value + "&monthlyMonthDOW=" + document.getElementById("monthlyMonthDOW").value + "&monthlyMonthRepeat=" + document.getElementById("monthlyMonthRepeat").value;
			}

			qStr = qStr + "&eventDate=" + document.getElementById("eventDate").value + "&recurEndDate=" + document.getElementById("recurEndDate").value;

			ajxOutput("'.CalRoot.'/event-recur-confirm.php" + qStr,"recur_chk","'.CalRoot.'");
		}
	}
	function imgPreview(){
		var img = document.getElementById("imageURL");
		
		if(img.value == ""){
			document.getElementById("preview").style.display = "none";
			return false;
		}
		if(validURL(img,"nope") != ""){
			alert("'.$hc_lang_event['Valid67'].'");
			return false;
		}
		document.getElementById("preview").style.display = "block";
		document.getElementById("image_preview").innerHTML = "<img src=\"" + img.value + "\" />";
		document.getElementById("image_preview").style.overflow = "scroll";
	}';
		
	if($imageURL != '')
		echo '
	imgPreview();';
		
	include_once(HCADMIN.'/inc/javascript/events.php');
	$pub_only = $evnt_only = 0;
	include_once(HCPATH.'/inc/javascript/locations.php');
	echo '
	//-->
	</script>';
	
	makeTinyMCE('82%',1,0,'eventDescription');
?>