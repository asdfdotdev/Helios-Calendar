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

	include(HCLANG.'/admin/event.php');
	
	$eID = $regUsed = 0;
	$series = $editString = $dateString = $regProgress = $dateOutput = '';
	$events = array();
	$editSingle = false;
	$startTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME));
	$endTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
	$startTimeMins = $endTimeMins = '00';
	$startTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME));
	$endTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_event['Feed01']);
				break;
			case "2" :
				feedback(1,$hc_lang_event['Feed02']);
				break;
			case "3" :
				feedback(1,$hc_lang_event['Feed03']);
				break;
			case "4" :
				feedback(1,$hc_lang_event['Feed04']);
				break;
			case "5" :
				feedback(1,$hc_lang_event['Feed05']);
				break;
			case "6" :
				feedback(1,$hc_lang_event['Feed06']);
				break;
		}
	}
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$editSingle = true;
		$eID = cIn($_GET['eID']);
		$hc_Side[] = array(CalRoot . '/index.php?eID=' . cIn($eID),'calendar.png',$hc_lang_event['LinkCalendar'],1);
		$hc_Side[] = array(AdminRoot . '/index.php?com=eventadd&amp;eID=' . cIn($eID),'recycle.png',$hc_lang_event['LinkRecycle'],0);
		$fID = $eID;
		
		appInstructions(0, "Editing_Events", $hc_lang_event['TitleEdit'], $hc_lang_event['InstructEdit']);
	} else {
		if(isset($_GET['sID'])){
			$series = cIn(strip_tags($_GET['sID']));
			$resultS = doQuery("SELECT GROUP_CONCAT(DISTINCT PkID ORDER BY PkID SEPARATOR ',')
							FROM " . HC_TblPrefix . "events WHERE SeriesID = '".$series."'");
			$events = explode(',',mysql_result($resultS,0,0));
			$events = array_filter($events,'is_numeric');
		} elseif(isset($_POST['eventID'])){
			$events = array_filter($_POST['eventID'],'is_numeric');
		}
		$eID = (count($events) > 0) ? $events[0] : '0';
		$editString = (count($events) > 0) ? implode(',',$events) : 'NULL';
		$resultS = doQuery("SELECT GROUP_CONCAT(StartDate ORDER BY StartDate SEPARATOR ',')
						FROM " . HC_TblPrefix . "events WHERE PkID IN (".$editString.")");
		$dateString = (hasRows($result)) ? explode(',',mysql_result($resultS,0,0)) : NULL;
		$fID = $series;
		
		appInstructions(0, "Group_Editing_Events", $hc_lang_event['TitleGroup'], $hc_lang_event['InstructGroup']);
	}
	$result = doQuery("SELECT e.*, l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, f.EntityID, f.Note
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						LEFT JOIN " . HC_TblPrefix . "followup f ON (f.EntityID = '" . $fID . "' AND (f.EntityType = 1 OR f.EntityType = 2))
					WHERE e.PkID = '" . $eID . "' AND e.IsActive = 1");
	if(!hasRows($result) || $eID < 1 || mysql_result($result,0,0) < 1){
		echo '<p>' . $hc_lang_event['EditWarning'] . '</p>';
	} else {
		$eventTitle = cOut(mysql_result($result,0,1));
		$eventDesc = cOut(mysql_result($result,0,8));
		$tbd = cOut(mysql_result($result,0,11));
		$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg[24]);
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
		$contactPhone = cOut(mysql_result($result,0,15));
		$contactURL = (mysql_result($result,0,24) != '') ? cOut(mysql_result($result,0,24)) : '';
		$allowRegistration = cOut(mysql_result($result,0,25));
		$maxReg = cOut(mysql_result($result,0,26));
		$views = cOut(mysql_result($result,0,28));
		$locID = cOut(mysql_result($result,0,35));
		$locName = ($locID == 0) ? cOut(mysql_result($result,0,2)) : cOut(mysql_result($result,0,40));
		$locAddress = ($locID == 0) ? cOut(mysql_result($result,0,3)) : cOut(mysql_result($result,0,41));
		$locAddress2 = ($locID == 0) ? cOut(mysql_result($result,0,4)) : cOut(mysql_result($result,0,42));
		$locCity = ($locID == 0) ? cOut(mysql_result($result,0,5)) : cOut(mysql_result($result,0,43));
		$state = ($locID == 0) ? cOut(mysql_result($result,0,6)) : cOut(mysql_result($result,0,44));
		$locPostal = ($locID == 0) ? cOut(mysql_result($result,0,7)) : cOut(mysql_result($result,0,45));
		$locCountry = ($locID == 0) ? cOut(mysql_result($result,0,37)) : cOut(mysql_result($result,0,46));
		$cost = cOut(mysql_result($result,0,36));
		$followup = (mysql_result($result,0,47) != '') ? 1 : 0;
		$fnote = cOut(mysql_result($result,0,48));
		$eventStatus = cOut(mysql_result($result,0,17));
		$eventBillboard = cOut(mysql_result($result,0,18));
		$shortURL = cOut(mysql_result($result,0,38));
		$stime_disabled = ($tbd > 0) ? ' disabled="disabled"' : '';
		$etime_disabled = (isset($noEndTime) || $tbd > 0) ? ' disabled="disabled"' : '';
		$bitChk = '';
		$bitShow = ' style="display:none;"';
		$bitLabel = $hc_lang_event['BitlyLabel'];
		$bitNotice = $hc_lang_event['BitlyNotice'];
		if($allowRegistration == 1){
			$resultR = doQuery("SELECT COUNT(r.EventID) as RegCnt 
								FROM " . HC_TblPrefix . "registrants r
							WHERE r.EventID = '" . cIn($eID) . "' AND r.IsActive = 1");
			$regUsed = (hasRows($resultR)) ? mysql_result($resultR,0,0) : 0;
		}
		
		if(strpos($shortURL,'http://') !== false){
			$bitChk = ' checked="checked"';
			$bitShow = '';
			$bitLabel = $hc_lang_event['BitlyHasLink'];
			$bitNotice = $hc_lang_event['BitlyTools'].'
				<ul>
					<li><a href="'.$shortURL.'+" target="_blank">' . $hc_lang_event['BitlyReport'] . '</a></li>
					<li><a href="'.$shortURL.'" target="_blank">' . $hc_lang_event['BitlyLink'] . '</a></li>
					<li><a href="'.$shortURL.'.qrcode" target="_blank">' . $hc_lang_event['BitlyQRImage'] . '</a></li>
					<li><a href="'.$shortURL.'.qr" target="_blank">' . $hc_lang_event['BitlyQRLink'] . '</a></li>
				</ul>';
		}
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
		if($maxReg < $regUsed && $maxReg > 0){
			$regProgress = '<div id="rsvp_full">&nbsp;</div>';
		} elseif($maxReg > 0){
			$regWidth = ($regUsed > 0) ? floor(($regUsed / $maxReg) * 100) : 0;
			$regProgress = '<div id="rsvp_progress" style="width:'.$regWidth.'%;">&nbsp;</div>';
		}
		$regOutput = '<div id="rsvp_meter">'.$regProgress.'</div><b>'.$regUsed.' '.$hc_lang_event['Of'].' '.(($maxReg > 0) ? $maxReg : $hc_lang_event['Unlimited']).'</b>';
		
		if(mysql_result($result,0,19)){
			if(isset($_GET['sID']))
				$hc_Side[] = array(AdminRoot . '/index.php?com=searchresults&amp;srsID=' . mysql_result($result,0,19),'view_series.png',$hc_lang_event['LinkSeriesView'],0);
			else
				$hc_Side[] = array(AdminRoot . '/index.php?com=eventedit&amp;sID=' . mysql_result($result,0,19),'edit_group.png',$hc_lang_event['LinkSeriesEdit'],0);
		}
		
		if($followup == 0)
			$hc_Side[] = array('javascript:;','followup.png',$hc_lang_core['LinkFollow'],0,'follow_up();return false;');
		
		echo '
		<form id="frmEventEdit" name="frmEventEdit" method="post" action="'.AdminRoot.'/components/EventEditAction.php" onsubmit="return validate();">
		<input type="hidden" name="eID" id="eID" value="'.$eID.'" />
		<input type="hidden" name="fID" id="fID" value="'.$fID.'" />
		<input type="hidden" name="editString" id="editString" value="'.$editString.'" />
		<input type="hidden" id="locPreset" name="locPreset" value="'.$locID.'" />
		<input type="hidden" id="locPresetName" name="locPresetName" value="'.$locName.'" />
		<input type="hidden" name="prevStatus" id="prevStatus" value="'.$eventStatus.'" />
		<fieldset id="follow-up"'.($followup == 0 ? 'style="display:none;"':'').'>
			<legend>'.$hc_lang_event['FollowLabel'].'</legend>
			<label for="follow_up">'.$hc_lang_event['Follow'].'</label>
			<select name="follow_up" id="follow_up"'.($followup == 0 ? ' disabled="disabled"':'').'>
				<option value="0">'.$hc_lang_event['Follow0'].'</option>
				<option selected="selected" value="1">'.$hc_lang_event['Follow1'].'</option>
			</select>
			<label for="follow_note">'.$hc_lang_event['FollowNote'].'</label>
			<input name="follow_note" id="follow_note" type="text" size="90" maxlength="300" value="'.$fnote.'"'.($followup == 0 ? ' disabled="disabled"':'').' />
		</fieldset>';
				
		if($editSingle == false){
			$cnt = 1;
			foreach($dateString as $val){
				$dateOutput .= ($cnt % 8 == 0) ? stampToDate($val, $hc_cfg[24]).'<br />' : stampToDate($val, $hc_cfg[24]).', ';
				++$cnt;
			}
		echo ($series == '') ? '
		<input type="hidden" name="grpDate" id="grpDate" value="'.stampToDate(min($dateString),$hc_cfg[24]).' - '.stampToDate(max($dateString),$hc_cfg[24]).'" />
		<fieldset>
			<legend>'.$hc_lang_event['GroupTitle'].'</legend>
			<label>&nbsp;</label>
			<span class="frm_ctrls">
				<label for="makeseries"><input type="checkbox" name="makeseries" id="makeseries" />'.$hc_lang_event['GroupCombine'].'</label>
			</span>
		</fieldset>' : '';
		}
		
		echo '
		<fieldset>
			<legend>'.$hc_lang_event['EventDetail'].'</legend>
			<label for="eventTitle">'.$hc_lang_event['Title'].'</label>
			<input onblur="buildTweet();" name="eventTitle" id="eventTitle" type="text" size="90" maxlength="150" required="required" value="'.$eventTitle.'" />
			<label for="eventDescription">'.$hc_lang_event['Description'].'</label>
			<textarea name="eventDescription" id="eventDescription" rows="20">'.$eventDesc.'</textarea>
			<label for="cost">'.$hc_lang_event['Cost'].'</label>
			<input name="cost" id="cost" type="text" size="25" maxlength="50" value="'.$cost.'" />
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_event['DateTime'].'</legend>
		'.(($editSingle == true) ? '<label for="eventDate">'.$hc_lang_event['Date'].'</label>
			<input name="eventDate" id="eventDate" type="text" size="12" maxlength="10" required="required" value="'.$eventDate.'" />
			<a href="javascript:;" onclick="calx.select(document.getElementById(\'eventDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>':
			'<label for="eventDate">'.$hc_lang_event['Dates'].'</label>
			<span class="output">'.$dateOutput.'</span>').'
			<label>'.$hc_lang_event['StartTime'].'</label>
			<input onblur="buildTweet();" name="startTimeHour" id="startTimeHour" type="text" size="2" maxlength="2" required="required" value="'.$startTimeHour.'"'.$stime_disabled.' />
			<span class="frm_ctrls">
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),1,'.$hc_time['input'].')" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeHour\'),-1,'.$hc_time['input'].')" class="time tdown" tabindex="-1"></a>
			</span>
			<input onblur="buildTweet();" name="startTimeMins" id="startTimeMins" type="text" size="2" maxlength="2" required="required" value="'.$startTimeMins.'"'.$stime_disabled.' />
			<span class="frm_ctrls">	
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),5,59)" class="time tup" tabindex="-1"></a>
				<a href="javascript:;" onclick="chngClock(document.getElementById(\'startTimeMins\'),-5,59)" class="time tdown" tabindex="-1"></a>
			</span>';
			if($hc_time['input'] == 12){
				echo '
			<select onblur="buildTweet();" name="startTimeAMPM" id="startTimeAMPM"'.$stime_disabled.'>
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
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_event['RegTitle'].'</legend>
			<label for="eventRegistration">'.$hc_lang_event['Registration'].'</label>
			<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
				<option '.(($allowRegistration == 0) ? 'selected="selected" ' : '').'value="0">'.$hc_lang_event['Reg0'].'</option>
				<option '.(($allowRegistration == 1) ? 'selected="selected" ' : '').'value="1">'.$hc_lang_event['Reg1'].'</option>
				<option '.(($allowRegistration == 2) ? 'selected="selected" ' : '').'value="2">'.$hc_lang_event['Reg2'].'</option>
			</select>
			<label for="eventRegAvailable">'.$hc_lang_event['Limit'].'</label>
			<input name="eventRegAvailable" id="eventRegAvailable" type="number" min="1" size="4" maxlength="4" value="'.$maxReg.'" '.(($allowRegistration != 1) ? 'disabled="disabled" ' : '').'/>
			<span class="output">'.$hc_lang_event['LimitLabel'].'</span>';
		
		if($allowRegistration == 1 && $regOutput != ''){
			echo '
			<label>'.$hc_lang_event['TotalReg'].'</label>
			<span class="output">'.$regOutput.'</span>
			<label>&nbsp;</label>
			<input '.($regUsed == 0 ? 'disabled="disabled" ' : '').' name="eventSendRoster" id="eventSendRoster" type="button" value="'.$hc_lang_event['RegButton1'].'" onclick="sendReg('.$eID.');" />
			<input name="addRegistrant" id="addRegistrant" type="button" value="'.$hc_lang_event['RegButton2'].'" onclick="window.location.href=\''.AdminRoot.'/index.php?com=eventregister&amp;eID='.$eID.'\';" />
			<div id="rsvps">
				<label>&nbsp;</label>
				<ul class="data" style="float:left;width:80%;">
					<li class="row header">
						<div style="width:35%;">'.$hc_lang_event['Registrant'].'</div>
						<div style="width:25%;">'.$hc_lang_event['PhoneReg'].'</div>
						<div style="width:25%;">'.$hc_lang_event['RegisteredAt'].'</div>
						<div style="width:10%;">&nbsp;</div>
					</li>
				</ul>
				<label>&nbsp;</label>
				<div id="rsvp_list">
					<ul class="data">';
		
				$result = doQuery("SELECT PkID, Name, Email, Phone, RegisteredAt FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . cIn($eID) . "' ORDER BY RegisteredAt, GroupID, PkID");
				if(hasRows($result)){
					$cnt = 1;
					
					while($row = mysql_fetch_row($result)){
						$hl = ($cnt % 2 == 0) ? ' hl':'';
						
						echo ($cnt == $maxReg + 1 && $maxReg > 0) ? '
						<li><div class="header">'.$hc_lang_event['OverflowReg'].'</li>' : '';
						
						echo '
						<li class="row'.$hl.'">
							<div class="text" style="width:35%;">
							'.(($cnt < 10) ? '0'.strval($cnt) : $cnt).') <a href="mailto:'.cOut($row[2]).'">'.cOut($row[1]).'</a>
							</div>
							<div class="text" style="width:25%;">'.(($row[3] != '') ? cOut($row[3]) : 'N/A').'</div>
							<div class="text" style="width:25%;">'.(($row[4] != '') ? stampToDate(cOut($row[4]),$hc_cfg[24].' '.$hc_cfg[23]) : 'N/A').'</div>
							<div class="tools" style="width:10%;">
								<a href="' . AdminRoot . '/index.php?com=eventregister&amp;rID='.$row[0].'&amp;eID='.$eID.'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
								<a href="javascript:;" onclick="delReg('.$row[0].');return false;"><img src="'.AdminRoot.'/img/icons/delete.png" width="16" height="16" alt="" /></a>
							</div>
						</li>';
						++$cnt;
					}
				} else {
					echo '
						<li><div class="text">'.$hc_lang_event['NoReg'].'</li>';
				}
			echo '
					</ul>
				</div>
			</div>';
		}
		echo '
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_event['Settings'].'</legend>
			<label for="eventStatus">'.$hc_lang_event['Status'].'</label>
			<select name="eventStatus" id="eventStatus">
				<option'.(($eventStatus == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_event['Status1'].'</option>
				<option'.(($eventStatus == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_event['Status2'].'</option>
			</select>
			<label for="eventBillboard">'.$hc_lang_event['Billboard'].'</label>
			<select name="eventBillboard" id="eventBillboard">
				<option'.(($eventBillboard == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_event['Billboard0'].'</option>
				<option'.(($eventBillboard == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_event['Billboard1'].'</option>
			</select>
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
		getCategories('frmEventEdit',3,$query,1);
		
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
				<input onblur="buildTweet();" name="locName" id="locName" type="text" size="25" maxlength="50" value="'.(($locID < 1) ? $locName : '').'" />
				<label for="locAddress">'.$hc_lang_event['Address'].'</label>
				<input name="locAddress" id="locAddress" type="text" size="30" maxlength="75" value="'.(($locID < 1) ? $locAddress : '').'" /><span class="output req2">*</span>
				<label for="locAddress2">'.$hc_lang_event['Address2'].'</label>
				<input name="locAddress2" id="locAddress2" type="text" size="25" maxlength="75" value="'.(($locID < 1) ? $locAddress2 : '').'" />
				<label for="' . $inputs[$first][1] . '">' . $hc_lang_event[$inputs[$first][0]] . '</label>
				<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" value="'.(($locID < 1) ? $inputs[$first][2] : '').'" /><span class="output req2">*</span>';

			if($hc_lang_config['AddressRegion'] != 0){	
				echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
				$regSelect = $state;
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

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,36,37,38,39,46,47,57,58);");
		$goEventful = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '' && mysql_result($result,4,1) != '') ? 1 : 0;
		$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '') ? 1 : 0;
		$goTwitter = (mysql_result($result,6,1) != '' && mysql_result($result,7,1) != '') ? 1 : 0;
		$goBitly = (mysql_result($result,8,1) && mysql_result($result,9,1)) ? 1 : 0;
		$efID = $ebID = $tweetLnks = '';
		$tweets = array();
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = '" . cIn($eID) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efID = $row[1];
						break;
					case 2:
						$ebID = $row[1];
						break;
					case 3:
						$tweets[] = $row[1];
						break;
				}
			}
		}
		if(count($tweets) > 0){
			foreach($tweets as $val)
				$tweetLnks .=' <a href="http://twitter.com/'.$hc_cfg[63].'/status/'.$val.'" target="_blank">'.$val.'</a>';
		}

		echo '	
		<fieldset>
			<legend>'.$hc_lang_event['DistPub'].'</legend>
			'.(($efID != '' || $ebID != '') ? '<p>
				<b>'.$hc_lang_event['PostedTo'].'</b>
				'.(($efID != '') ? '<a href="http://www.eventful.com/events/'.$efID.'" target="_blank">'.$hc_lang_event['EventfulView'].'</a>' : '').'
				'.(($ebID != '') ? '<a href="http://www.eventbrite.com/event/'.$ebID.'" target="_blank">'.$hc_lang_event['EventbriteView'].'</a>' : '').'
			</p>' : '').'
				'.((count($tweets) > 0) ? '
			<p>'.$hc_lang_event['DistPubTweets'].'
				'.$tweetLnks.'
			</p>' : '').'
			<label for="doEventful" class="distPub distPubTop">
				<input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById(\'eventful\'));"'.(($goEventful == 0) ?  ' disabled="disabled"':'').' />
				'.(($efID != '') ? $hc_lang_event['EventfulLabelU'] : $hc_lang_event['EventfulLabelA']).'
			</label>
			<div id="eventful" style="display:none;">'.$hc_lang_event['EventfulSubmit'].'</div>

			<label for="doEventbrite" class="distPub">
				<input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));chkReg();"'.(($goEventbrite == 0) ?  ' disabled="disabled"':'').' />
				'.(($ebID != '') ? $hc_lang_event['EventbriteLabelU'] : $hc_lang_event['EventbriteLabelA']).'
			</label>
			<div id="eventbrite" style="display:none;">';
		
		if($ebID == ''){
			echo '
			<p>'.$hc_lang_event['EventbriteNotice'].'</p>
			<label for="currency"><b>' . $hc_lang_event['EventCurrency'] . '</b></label>
			<select name="currency" id="currency">
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
			<ul class="data">
				<li class="header row">
					<div class="text" style="width:35%">'.$hc_lang_event['TicketName'].'</div>
					<div class="text" style="width:31%">'.$hc_lang_event['TicketPrice'].'</div>
					<div class="tools" style="width:15%">'.$hc_lang_event['TicketQty'].'</div>
					<div class="tools" style="width:10%">'.$hc_lang_event['TicketFee'].'</div>
				</li>';
			for($x = 1; $x <= 5; ++$x){
				$hl = ($x % 2 == 0) ? ' hl':'';
				echo '
				<li class="row'.$hl.'">
					<div class="text" style="width:35%">
						<input name="ticket' . $x . '" id="ticket' . $x . '" type="text" size="30" maxlength="200" value="" />
					</div>
					<div class="text" style="width:33%">
						<input onclick="togTicketPrice('.$x.',0);" name="priceType'.$x.'" type="radio" value="0" checked="checked" /><input name="price' . $x . '" id="price' . $x . '" type="text" size="5" maxlength="7" value="" />
						<input onclick="togTicketPrice('.$x.',1);" id="priceType1'.$x.'" name="priceType'.$x.'" type="radio" value="1" /><label for="priceType1'.$x.'">'.$hc_lang_event['Free'].'</label>
						<input onclick="togTicketPrice('.$x.',1);" id="priceType2'.$x.'" name="priceType'.$x.'" type="radio" value="2" /><label for="priceType2'.$x.'">'.$hc_lang_event['Donate'].'</label>
					</div>
					<div class="tools" style="width:16%">
						<input name="qty' . $x . '" id="qty' . $x . '" type="text" size="5" maxlength="5" value="" />
					</div>
					<div class="tools" style="width:10%">
						<input name="fee' . $x . '" id="fee' . $x . '" type="checkbox" value="" />
					</div>
				</li>';
			}
			echo '
			</ul>';
		} else {
			echo $hc_lang_event['EventbriteNoticeU'];
		}
		
		echo '	
		</div>
			<label for="doTwitter" class="distPub">
				<input name="doTwitter" id="doTwitter" type="checkbox" onclick="toggleMe(document.getElementById(\'twitter\'));buildTweet();"'.(($goTwitter == 0 || $goBitly == 0) ?  ' disabled="disabled"':'').' />
				'.$hc_lang_event['TwitterLabel'].'
			</label>
			<div id="twitter" style="display:none;">
				<input name="tweetThis" id="tweetThis" type="text" size="45" maxlength="104" value="" style="width:75%;" />
				'.$hc_lang_event['TwitterNotice'].'
			</div>
			<label for="doBitly" class="distPub">
				<input name="doBitly" id="doBitly" type="checkbox" onclick="toggleMe(document.getElementById(\'bitly\'));"'.($bitChk != '' ? ' checked="checked"':'').(($goBitly == 0 || $bitChk != '') ?  ' disabled="disabled"':'').' />
				'.$bitLabel.'
			</label>
			<div id="bitly"'.$bitShow.'>'.$bitNotice.'</div>
		</fieldset>
		<input name="submit" id="submit" type="submit" value="'.$hc_lang_event['Save'].'" />
		</form>
		<div id="dsCal" class="datePicker"></div>

		<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
		<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
		<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
		<script>
		//<!--
		var tweetPrefix = "'.$hc_lang_event['TweetUpdate'].'";
		var recOpts = new Array("daily","weekly","monthly");
		var calx = new CalendarPopup("dsCal");
		calx.showNavigationDropdowns();
		calx.setCssPrefix("hc_");
		calx.offsetX = 30;
		calx.offsetY = -5;

		function validate(){
			var err = "";
			
			err +=reqField(document.getElementById("eventTitle"),"'.$hc_lang_event['Valid13'].'\n");

			try{
				err +=chkTinyMCE(tinyMCE.get("eventDescription").getContent(),"'.$hc_lang_event['Valid01'].'\n");}
			catch(error){
				err +=reqField(document.getElementById("eventDescription"),"'.$hc_lang_event['Valid01'].'\n");}

			if(document.getElementById("eventRegistration").value == 1){
				err +=reqField(document.getElementById("eventRegAvailable"),"'.$hc_lang_event['Valid60'].'\n");
				err +=validNumber(document.getElementById("eventRegAvailable"),"'.$hc_lang_event['Valid02'].'\n");
				err +=validGreater(document.getElementById("eventRegAvailable"),-1,"'.$hc_lang_event['Valid60'].'\n");
				err +=reqField(document.getElementById("contactName"),"'.$hc_lang_event['Valid03'].'\n");
				err +=reqField(document.getElementById("contactEmail"),"'.$hc_lang_event['Valid04'].'\n");
			}
			
			if(document.getElementById("eventDate")){
				chkd = chkDate();
				if(chkd == -1)
					return false;
				else
					err += chkd;
			}

			err +=validNumber(document.getElementById("startTimeHour"),"'.$hc_lang_event['Valid05'].'\n");
			err +=validNumberRange(document.getElementById("startTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_event['Valid06']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
			err +=validNumber(document.getElementById("startTimeMins"),"'.$hc_lang_event['Valid07'].'\n");
			err +=validNumberRange(document.getElementById("startTimeMins"),0,59,"'.$hc_lang_event['Valid08'].'\n");
			err +=validNumber(document.getElementById("endTimeHour"),"'.$hc_lang_event['Valid09'].'\n");
			err +=validNumberRange(document.getElementById("endTimeHour"),'.$hc_time['minHr'].','.$hc_time['input'].',"'.$hc_lang_event['Valid10']." ".$hc_time['minHr']." - ".$hc_time['input'].'\n");
			err +=validNumber(document.getElementById("endTimeMins"),"'.$hc_lang_event['Valid11'].'\n");
			err +=validNumberRange(document.getElementById("endTimeMins"),0,59,"'.$hc_lang_event['Valid12'].'\n");
			err +=validCheckArray("frmEventEdit","catID[]",1,"'.$hc_lang_event['Valid15'].'\n");

			if(document.getElementById("locPreset").value == 0)
				err +=reqField(document.getElementById("locName"),"'.$hc_lang_event['Valid16'].'\n");
			if(document.getElementById("contactEmail").value != "")
				err +=validEmail(document.getElementById("contactEmail"),"'.$hc_lang_event['Valid21'].'\n");
			if(document.getElementById("doEventbrite").checked){
				ebFailT = ebFailP = ebFailQ = 0;
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
		function sendReg(eID){
			if(confirm("'.$hc_lang_event['Valid50']."\\n\\n          ".$hc_lang_event['Valid51']."\\n          ".$hc_lang_event['Valid52'].'")){
				document.getElementById("eventSendRoster").disabled = true;
				document.getElementById("eventSendRoster").value = "'.$hc_lang_core['Sending'].'";
				window.location.href=("'.AdminRoot.'/components/RegisterSendRoster.php?eID=" + eID);
			}
		}
		function delReg(dID){
			if(confirm("'.$hc_lang_event['Valid47']."\\n\\n          ".$hc_lang_event['Valid48']."\\n          ".$hc_lang_event['Valid49'].'")){
				window.location.href=("'.AdminRoot.'/components/RegisterAddAction.php?dID=" + dID + "&eID='.$eID.'");
			}
		}';
		include_once(HCADMIN.'/inc/javascript/events.php');
		$pub_only = $evnt_only = 0;
		include_once(HCPATH.'/inc/javascript/locations.php');
		echo '
		//-->
		</script>';
		
		makeTinyMCE('eventDescription',1,0,0);
	}
?>