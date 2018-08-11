<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/event.php');

	$pubSub = ($hc_cfg[1] == 1) ? $hc_lang_event['LinkPublicOn'] : $hc_lang_event['LinkPublicOff'];
	$hc_Side[] = array(AdminRoot . '/index.php?com=generalset','public.png',$pubSub,0);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_event['Feed12']);
				break;
			case "2" :
				feedback(1, $hc_lang_event['Feed13']);
				break;
			case "3" :
				feedback(1, $hc_lang_event['Feed14']);
				break;
			case "4" :
				feedback(1, $hc_lang_event['Feed15']);
				break;
			case "5" :
				feedback(1, $hc_lang_event['Feed16']);
				break;
			case "8" :
				feedback(1, $hc_lang_event['Feed19']);
				break;
			case "9" :
				feedback(1, $hc_lang_event['Feed20']);
				break;
		}
	}
	if(!isset($_GET['sID']) && !isset($_GET['eID'])){
		appInstructions(0, "Pending_Events", $hc_lang_event['TitlePendingA'], $hc_lang_event['InstructPendingA']);
		
		$resultI = doQuery("SELECT e.PkID, e.Title, e.StartDate, e.SeriesID, e.SubmittedAt, e.SubmittedByName, e.SubmittedByEmail, e.OwnerID, u.NetworkName, u.Email
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "users u ON (e.OwnerID = u.PkID)
						WHERE e.SeriesID IS NULL AND e.IsActive = 1 AND e.IsApproved = 2
						ORDER BY SubmittedAt DESC, StartDate, Title");
		
		$resultS = doQuery("SELECT e.PkID, e.Title, e.StartDate, e.SeriesID, e.SubmittedAt, e.SubmittedByName, e.SubmittedByEmail, e.OwnerID, u.NetworkName, u.Email
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "users u ON (e.OwnerID = u.PkID)
						WHERE e.SeriesID IS NOT NULL AND e.IsActive = 1 AND e.IsApproved = 2
						ORDER BY e.SubmittedAt DESC, e.SeriesID, e.StartDate, e.Title");
		if(hasRows($resultI) || hasRows($resultS)){
			$curSeries = '';
			$cnt = 0;
			$token = set_form_token(1);
			
			echo '
			<form name="eventPending" id="eventPending" method="post" action="'.AdminRoot.'/components/EventDelete.php" onsubmit="return validate();">
			<input type="hidden" name="token" id="token" value="'.$token.'" />
			<input type="hidden" name="pID" id="pID" value="1" />
			<div class="catCtrl">
				[ <a href="javascript:;" onclick="checkAllArray(\'eventPending\', \'eventID[]\');">' . $hc_lang_core['SelectAll'] . '</a>
				&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventPending\', \'eventID[]\');">' . $hc_lang_core['DeselectAll'] . '</a> ]
			</div>
			<ul class="data">';
			
			if(hasRows($resultI)){
				echo '
				<li class="row header uline">
					<div style="width:50%;">'.$hc_lang_event['PendingIndividual'].'</div>
					<div style="width:18%;">'.$hc_lang_event['Author'].'</div>
					<div style="width:12%;">'.$hc_lang_event['Submitted'].'</div>
					<div style="width:10%;">'.$hc_lang_event['Happens'].'</div>
				</li>';
				while($row = mysql_fetch_row($resultI)){
					$sinceSubmit = daysDiff(date("Y-m-d"), $row[4]) - 1;
					$untilHappens = daysDiff($row[2], date("Y-m-d"), 0) - 1;
					$name = ($row[7] > 0) ? $row[8] : $row[5];
					$email = ($row[7] > 0) ? $row[9] : $row[6];
					$link = ($row[7] > 0) ? '<a href="'.AdminRoot.'/index.php?com=useredit&uID='.$row[7].'" target="_blank"><img src="'.AdminRoot.'/img/icons/user_edit.png" widt="16" height="16" style="vertical-align:middle;" /></a>&nbsp;':'';					
					$hl = ($cnt % 2 == 1) ? ' hl':'';
					echo '
					<li class="row'.$hl.'">
						<div class="txt" style="width:50%;">'.cOut($row[1]).'</div>
						<div class="txt" style="width:18%;" title="'.$email.'">'.(($row[4] != '' || $row[7] > 0) ? $link.'<a href="mailto:'.$email.'">'.$name.'</a>' : $hc_lang_event['Admin']).'</div>
						'.(($row[4] != '') ? '<div class="txt" style="width:12%;">'.(($sinceSubmit > 0) ? $sinceSubmit.' '.$hc_lang_event['Days'].' '.$hc_lang_event['Ago'] : $hc_lang_event['Today']).'</div>':'<div class="txt" style="width:12%;">N/A</div>').'
						<div class="txt'.(($untilHappens <= 7) ? ' alert':'').'" style="width:10%;">'.(($untilHappens >= 0) ? $untilHappens.' '.$hc_lang_event['Days'] : $hc_lang_event['Past']).'</div>
						<div class="tools" style="width:10%;">
							<a href="'.AdminRoot.'/index.php?com=eventpending&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
							<input type="checkbox" name="eventID[]" id="eventID_'.$row[0].'" value="'.$row[0].'" />
						</div>
					</li>';
					++$cnt;
				}
			}
			if(hasRows($resultS)){
				while($row = mysql_fetch_row($resultS)){
					$sinceSubmit = daysDiff(date("Y-m-d"), $row[4]) - 1;
					$untilHappens = daysDiff($row[2], date("Y-m-d"), 0) - 1;
					$name = ($row[7] > 0) ? $row[8] : $row[5];
					$email = ($row[7] > 0) ? $row[9] : $row[6];
					$link = ($row[7] > 0) ? '<a href="'.AdminRoot.'/index.php?com=useredit&uID='.$row[7].'" target="_blank"><img src="'.AdminRoot.'/img/icons/user_edit.png" widt="16" height="16" style="vertical-align:middle;" /></a>&nbsp;':'';
					
					if($curSeries != $row[3]){
						$cnt = 0;
						$curSeries = $row[3];	
						echo '
					<li class="row header uline">
						<div style="width:50%;">'.$hc_lang_event['PendingSeries'].'</div>
						<div style="width:18%;">'.$hc_lang_event['Author'].'</div>
						<div style="width:12%;">'.$hc_lang_event['Submitted'].'</div>
						<div style="width:10%;">'.$hc_lang_event['Happens'].'</div>
						<div class="tools" style="width:10%;">
							<a href="'.AdminRoot.'/index.php?com=eventpending&amp;sID='.$row[3].'"><img src="'.AdminRoot.'/img/icons/edit_group.png" width="16" height="16" alt="" /></a>
							<a href="javascript:;" onclick="deleteSeries(\''.$row[3].'\');return false;"><img src="'.AdminRoot.'/img/icons/delete_group.png" width="16" height="16" alt="" /></a>
						</div>
					</li>';}

					$hl = ($cnt % 2 == 1) ? ' hl':'';
					echo '
					<li class="row'.$hl.'">
						<div class="txt" style="width:50%;">'.cOut($row[1]).'</div>
						<div class="txt" style="width:18%;" title="'.$email.'">'.(($row[4] != '') ? $link.'<a href="mailto:'.$email.'">'.$name.'</a>' : $hc_lang_event['Admin']).'</div>
						'.(($row[4] != '') ? '<div class="txt" style="width:12%;">'.(($sinceSubmit > 0) ? $sinceSubmit.' '.$hc_lang_event['Days'].' '.$hc_lang_event['Ago'] : $hc_lang_event['Today']).'</div>':'<div class="txt" style="width:12%;">N/A</div>').'
						<div class="txt'.(($untilHappens <= 7) ? ' alert':'').'" style="width:10%;">'.(($untilHappens >= 0) ? $untilHappens.' '.$hc_lang_event['Days'] : $hc_lang_event['Past']).'</div>
						<div class="tools" style="width:10%;">
							<a href="'.AdminRoot.'/index.php?com=eventpending&amp;eID='.$row[0].'"><img src="'.AdminRoot.'/img/icons/edit.png" width="16" height="16" alt="" /></a>
							<input type="checkbox" name="eventID[]" id="eventID_'.$row[0].'" value="'.$row[0].'" />
						</div>
					</li>';
					++$cnt;
				}
			}
			echo '
			</ul>
			<div class="catCtrl">
				[ <a href="javascript:;" onclick="checkAllArray(\'eventPending\', \'eventID[]\');">' . $hc_lang_core['SelectAll'] . '</a>
				&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'eventPending\', \'eventID[]\');">' . $hc_lang_core['DeselectAll'] . '</a> ]
			</div>
			<input type="submit" name="submit" id="submit" value="'.$hc_lang_event['DeclineDelete'].'" />
			</form>
			
			<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
			<script>
			//<!--
			function validate(){
				if(validCheckArray("eventPending","eventID[]",1,"error") != ""){
					alert("'.$hc_lang_event['Valid56'].'");
					return false;
				} else {
					if(confDelete())
						return true;
					else
						return false;
				}
			}
			function deleteSeries(sID){
				if(confDelete())
					document.location.href = "'.AdminRoot.'/components/EventDelete.php?series=" + sID + "&pID=1&tkn='.$token.'";
			}
			function confDelete(){
				if(confirm("'.$hc_lang_event['Valid57'].'\\n\\n          '.$hc_lang_event['Valid58'].'\\n          '.$hc_lang_event['Valid59'].'"))
					return true;
				else
					return false;
			}
			//-->
			</script>';
		} else {
			echo '<p>'.$hc_lang_event['NoPending'].'</p>';
		}
	} else {
		$series = $editString = $dateString = $regProgress = '';
		$events = array();
		$editSingle = false;
		$startTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME));
		$endTimeHour = date($hc_time['format'], strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
		$startTimeMins = $endTimeMins = '00';
		$startTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME));
		$endTimeAMPM = date("A",strtotime(SYSDATE.' '.SYSTIME.' +1 hour'));
		$qWhere = " WHERE e.PkID = 0";
		
		if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
			$editSingle = true;
			$eID = cIn($_GET['eID']);
			$qWhere = " WHERE e.PkID = '" . $eID . "' AND e.IsActive = 1 AND e.IsApproved = 2";
			$fID = $eID;			
		} elseif(isset($_GET['sID'])){
			$series = cIn(strip_tags($_GET['sID']));
			$resultS = doQuery("SELECT GROUP_CONCAT(DISTINCT PkID ORDER BY PkID SEPARATOR ','), GROUP_CONCAT(StartDate ORDER BY StartDate SEPARATOR ',')
							FROM " . HC_TblPrefix . "events WHERE SeriesID = '".$series."'");
			$events = (hasRows($result)) ? explode(',',mysql_result($resultS,0,0)) : array();
			$events = array_filter($events,'is_numeric');
			$editString = implode(',',$events);
			$dates = (hasRows($result)) ? explode(',',mysql_result($resultS,0,1)) : array();
			$cnt = 1;
			foreach($dates as $val){
				$dateString .= ($cnt % 8 == 0) ? stampToDate($val, $hc_cfg[24]).'<br />' : stampToDate($val, $hc_cfg[24]).', ';
				++$cnt;
			}
			$qWhere = " WHERE e.SeriesID = '" . $series . "' ORDER BY e.PkID";
			$fID = $series;
		}
		
		$query = "SELECT e.*, l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, er.*, f.EntityID, f.Note, u.NetworkName, u.Email
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
					LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (er.EventID = e.PkID)
					LEFT JOIN " . HC_TblPrefix . "followup f ON (f.EntityID = '" . $fID . "' AND (f.EntityType = 1 OR f.EntityType = 2))
					LEFT JOIN " . HC_TblPrefix . "users u ON (e.OwnerID = u.PkID)";
		
		$result = doQuery($query.$qWhere);
		if(!hasRows($result)){
			echo '<p>'.$hc_lang_event['ApproveWarning'].'</p>';
		} else {
			$eID = (isset($eID) && $eID > 1) ? $eID : cOut(mysql_result($result,0,0));
			$eventTitle = cOut(mysql_result($result,0,1));
			$eventDesc = cOut(mysql_result($result,0,8));
			$tbd = cOut(mysql_result($result,0,11));
			$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg[24]);
			$contactName = cOut(mysql_result($result,0,13));
			$contactEmail = cOut(mysql_result($result,0,14));
			$contactPhone = cOut(mysql_result($result,0,15));
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
			$followup = (mysql_result($result,0,58) != '') ? 1 : 0;
			$fnote = cOut(mysql_result($result,0,59));
			$eventStatus = cOut(mysql_result($result,0,17));
			$eventBillboard = cOut(mysql_result($result,0,18));
			$shortURL = cOut(mysql_result($result,0,36));
			$message = cOut(mysql_result($result,0,27));
			$subName = (mysql_result($result,0,41) > 0) ? cOut(mysql_result($result,0,60)) : cOut(mysql_result($result,0,20));
			$subEmail = (mysql_result($result,0,41) > 0) ? cOut(mysql_result($result,0,61)) : cOut(mysql_result($result,0,21));
			$subLink = (mysql_result($result,0,41) > 0) ? '<a href="'.AdminRoot.'/index.php?com=useredit&uID='.mysql_result($result,0,41).'" target="_blank"><img src="'.AdminRoot.'/img/icons/user_edit.png" widt="16" height="16" style="vertical-align:middle;" /></a>&nbsp;':'';							
			$bitChk = '';
			$bitShow = ' style="display:none;"';
			$bitLabel = $hc_lang_event['BitlyLabel'];
			$bitNotice = $hc_lang_event['BitlyNotice'];
			if($rsvp_type == 1){
				$resultR = doQuery("SELECT COUNT(r.EventID) as RegCnt 
									FROM " . HC_TblPrefix . "registrants r
								WHERE r.EventID = '" . cIn($eID) . "' AND r.IsActive = 1");
				$rsvp_taken = (hasRows($resultR)) ? mysql_result($resultR,0,0) : 0;
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
			$stime_disabled = ($tbd > 0) ? ' disabled="disabled"' : '';
			$etime_disabled = (isset($noEndTime) || $tbd > 0) ? ' disabled="disabled"' : '';
			$emailAccept = cleanBreaks('<p>'.$subName.',</p>'.$hc_cfg[3]);
			$emailDecline = cleanBreaks('<p>'.$subName.',</p>'.$hc_cfg[4]);
			
			appInstructions(0, "Pending_Events", $hc_lang_event['TitlePendingB'], $hc_lang_event['InstructPendingB']);
			
			if($followup == 0)
				$hc_Side[] = array('javascript:;','followup.png',$hc_lang_core['LinkFollow'],0,'follow_up();');
			
			echo '
			<form name="frmEventApprove" id="frmEventApprove" method="post" action="'.AdminRoot.'/components/EventPendingAction.php" onsubmit="return validate();">';
			set_form_token();
			echo '
			<input type="hidden" name="eID" id="eID" value="'.$eID.'" />
			<input type="hidden" name="sID" id="sID" value="'.$series.'" />
			<input type="hidden" name="fID" id="fID" value="'.$fID.'" />
			<input type="hidden" name="editString" id="editString" value="'.$editString.'" />
			<input type="hidden" id="locPreset" name="locPreset" value="'.$locID.'" />
			<input type="hidden" id="locPresetName" name="locPresetName" value="'.$locName.'" />
			<input type="hidden" name="prevStatus" id="prevStatus" value="'.$eventStatus.'" />
			<input type="hidden" name="subname" id="subname" value="'.cOut($subName).'" />
			<input type="hidden" name="subemail" id="subemail" value="'.cOut($subEmail).'" />
			'.((!$editSingle) ? '<input type="hidden" name="grpDate" id="grpDate" value="'.stampToDate(min($dates),$hc_cfg[24]).' - '.stampToDate(max($dates),$hc_cfg[24]).'" />':'').'
			<fieldset id="follow-up"'.($followup == 0 ? 'style="display:none;"':'').'>
				<legend>'.$hc_lang_event['FollowLabel'].'</legend>
				<label for="follow_up">'.$hc_lang_event['Follow'].'</label>
				<select name="follow_up" id="follow_up"'.($followup == 0 ? ' disabled="disabled"':'').'>
					<option value="0">'.$hc_lang_event['Follow0'].'</option>
					<option selected="selected" value="1">'.$hc_lang_event['Follow1'].'</option>
				</select>
				<label for="follow_note">'.$hc_lang_event['FollowNote'].'</label>
				<input name="follow_note" id="follow_note" type="text" size="90" maxlength="300" value="'.$fnote.'"'.($followup == 0 ? ' disabled="disabled"':'').' />
			</fieldset>
			'.(($message != '') ? '
			<fieldset id="user_note">
				<legend>'.$hc_lang_event['Message'].' '.$subLink.$subName.' (<a href="mailto:'.$subEmail.'">'.$subEmail.'</a>)</legend>
				<p>'.nl2br($message).'</p>
			</fieldset>':'').'
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
				'.(($editSingle == true) ? '<label for="eventDate">'.$hc_lang_event['Date'].'</label>
				<input name="eventDate" id="eventDate" type="text" size="12" maxlength="10" required="required" value="'.$eventDate.'" />
				<a href="javascript:;" onclick="calx.select(document.getElementById(\'eventDate\'),\'cal1\',\''.$hc_cfg[51].'\');return false;" id="cal1" class="ds calendar" tabindex="-1"></a>': '
				<label>'.$hc_lang_event['Dates'].'</label>
				<span class="output">'.$dateString.'</span>').'
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
				<select name="eventStatus" id="eventStatus" onchange="javascript:chgStatus();">
					<option value="1">'.$hc_lang_event['Status1'].'</option>
					<option value="0">'.$hc_lang_event['Status0'].'</option>
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
			getCategories('frmEventApprove',3,$query,1);

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
				<div id="custom" style="'.(($locID > 0) ? 'display:none;' : '').'clear:both;padding:15px 0 0 0;">
					<label>&nbsp;</label>
					<span class="frm_ctrls">
						<label for="newLoc"><input name="newLoc" id="newLoc" type="checkbox" checked="checked" />'.$hc_lang_event['LocNew'].' '.$hc_lang_event['GeocodeNotice'].'</label>
					</span>
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
			$goBitly = (mysql_result($result,4,1) && mysql_result($result,5,1)) ? 1 : 0;
			$ebOrganziers = ($goEventbrite == 1) ? eventbrite_get_organizers() : array();
			$goPaypal = ($goEventbrite == 1 && $hc_cfg[103] != '') ? 1 : 0;
			$goGoogleC = ($goEventbrite == 1 && $hc_cfg[104] != '' && $hc_cfg[105] != '') ? 1 : 0;
			$goFacebook = (mysql_result($result,6,1) != '' && mysql_result($result,7,1) != '') ? 1 : 0;
			$ebID = $tweetLnks = $fbID = $fbStatLnks = '';
			$tweets = $statuses = array();
			
			$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = '" . cIn($eID) . "'");
			if(hasRows($resultD)){
				while($row = mysql_fetch_row($resultD)){
					switch($row[2]){
						case 1:
							//	Nothing
							break;
						case 2:
							$ebID = $row[1];
							break;
						case 3:
							$tweets[] = $row[1];
							break;
						case 4:
							$statuses[] = $row[1];
							break;
						case 5:
							$fbID = $row[1];
							break;
					}
				}
			}
			if(count($statuses) > 0){
				foreach($statuses as $val){
					$lnk = explode('_',$val);
					$fbStatLnks .= ($lnk[0] != '' && $lnk[1] != '') ? ' <a href="https://www.facebook.com/permalink.php?story_fbid='.$lnk[1].'&id='.$lnk[0].'" target="_blank">'.$lnk[1].'</a>' : '';
				}
			}
			if(count($tweets) > 0){
				foreach($tweets as $val)
					$tweetLnks .=' <a href="http://twitter.com/'.$hc_cfg[63].'/status/'.$val.'" target="_blank">'.$val.'</a>';
			}
			$organizerID = $hc_cfg[62];
			$status = $privacy = $currency = '';
			$ebtickets[] = array();
			if($ebID != '')
				include(HCPATH.HCINC.'/api/eventbrite/EventGet.php');
			

			echo '	
			<fieldset>
				<legend>'.$hc_lang_event['DistPub'].'</legend>
				'.(($fbID != '' || $ebID != '') ? '<p>
					<b>'.$hc_lang_event['PostedTo'].'</b>
					'.(($fbID != '') ? '<a href="https://www.facebook.com/events/'.$fbID.'" target="_blank">'.$hc_lang_event['FacebookView'].'</a>' : '').'
					'.(($ebID != '') ? '<a href="http://www.eventbrite.com/event/'.$ebID.'" target="_blank">'.$hc_lang_event['EventbriteView'].'</a>' : '').'
				</p>' : '').'
					'.((count($statuses) > 0) ? '
				<p>'.$hc_lang_event['DistPubStatus'].'
					'.$fbStatLnks.'
				</p>' : '').'
					'.((count($tweets) > 0) ? '
				<p>'.$hc_lang_event['DistPubTweets'].'
					'.$tweetLnks.'
				</p>' : '').'
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
					<label for="facebookEvent" class="distPubFB"><input name="facebookEvent" id="facebookEvent" type="checkbox" onclick="toggleMe(document.getElementById(\'facebookEventLabel\'));" /> '.(($fbID == '') ? $hc_lang_event['FacebookEvent']:$hc_lang_event['FacebookEventU']).'</label>
					<div id="facebookEventLabel" style="display:none;">'.$hc_lang_event['FacebookEventA'].'</div>
				</div>
				<label for="doTwitter" class="distPub">
					<input name="doTwitter" id="doTwitter" type="checkbox" onclick="toggleMe(document.getElementById(\'twitter\'));buildT();"'.(($goTwitter == 0 || $goBitly == 0) ?  ' disabled="disabled"':'').' />
					'.$hc_lang_event['TwitterLabel'].'
				</label>
				<div id="twitter" style="display:none;">
					<input name="tweetThis" id="tweetThis" type="text" size="45" maxlength="104" value="" style="width:75%;" />
					'.$hc_lang_event['TwitterNotice'].'
				</div>
				<label for="doEventbrite" class="distPub">
					<input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));chkReg();"'.(($goEventbrite == 0) ?  ' disabled="disabled"':'').' />
					'.(($ebID != '') ? $hc_lang_event['EventbriteLabelU'] : $hc_lang_event['EventbriteLabelA']).'
				</label>
				<div id="eventbrite" style="display:none;">
					<p>'.$hc_lang_event['EventbriteNotice'].'</p>
					<label for="ebOrgID"><b>'.$hc_lang_event['EBOrganizer'].'</b></label>
					<select name="ebOrgID" id="ebOrgID">';

					foreach($ebOrganziers as $id => $name){
						echo '
						<option'.(($organizerID == $id) ? ' selected="selected"' : '').' value="'.$id.'">'.(($name != '') ? $name : '').' (#'.$id.')</option>';
					}

					echo '
					</select>
					<label for="ebStatus"><b>' . $hc_lang_event['EBStatus'] . '</b></label>
					<select name="ebStatus" id="ebStatus">
						<option'.((strtolower($status) == 'live') ? ' selected="selected"':'').' value="live">'.$hc_lang_event['EBStatus1'].'</option>
						<option'.((strtolower($status) == 'draft') ? ' selected="selected"':'').'  value="draft">'.$hc_lang_event['EBStatus0'].'</option>
					</select>
					<label for="ebPrivacy"><b>' . $hc_lang_event['EBPrivacy'] . '</b></label>
					<select name="ebPrivacy" id="ebPrivacy">
						<option'.((strtolower($privacy) == 'public') ? ' selected="selected"':'').' value="1">'.$hc_lang_event['EBPrivacy1'].'</option>
						<option'.((strtolower($privacy) == 'private') ? ' selected="selected"':'').' value="0">'.$hc_lang_event['EBPrivacy0'].'</option>
					</select>
					<label for="ebTimezone"><b>' . $hc_lang_event['EventTimezone'] . '</b></label>
					<select name="ebTimezone" id="ebTimezone">';
			
					$tz_default = (function_exists('ini_get')) ? ini_get('date.timezone') : '';
					include(HCPATH.HCINC.'/functions/tz.php');
					foreach($hc_master_tz as $key => $arr){
						echo '
						<option'.(($tz_default == $key) ? ' selected="selected"':'').' value="'.$key.'">'.$key.' (UTC'.$arr['utc'].')</option>';
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
						<option'.(($currency == 'USD') ? ' selected="selected"':'').' value="USD">'.$hc_lang_event['USD'].'</option>
						<option'.(($currency == 'EUR') ? ' selected="selected"':'').' value="EUR">'.$hc_lang_event['EUR'].'</option>
						<option'.(($currency == 'GBP') ? ' selected="selected"':'').' value="GBP">'.$hc_lang_event['GBP'].'</option>
						<option'.(($currency == 'JPY') ? ' selected="selected"':'').' value="JPY">'.$hc_lang_event['JPY'].'</option>
						<option'.(($currency == 'AUD') ? ' selected="selected"':'').' value="AUD">'.$hc_lang_event['AUD'].'</option>
						<option'.(($currency == 'CAD') ? ' selected="selected"':'').' value="CAD">'.$hc_lang_event['CAD'].'</option>
						<option'.(($currency == 'CZK') ? ' selected="selected"':'').' value="CZK">'.$hc_lang_event['CZK'].'</option>
						<option'.(($currency == 'DKK') ? ' selected="selected"':'').' value="DKK">'.$hc_lang_event['DKK'].'</option>
						<option'.(($currency == 'HKD') ? ' selected="selected"':'').' value="HKD">'.$hc_lang_event['HKD'].'</option>
						<option'.(($currency == 'HUF') ? ' selected="selected"':'').' value="HUF">'.$hc_lang_event['HUF'].'</option>
						<option'.(($currency == 'NZD') ? ' selected="selected"':'').' value="NZD">'.$hc_lang_event['NZD'].'</option>
						<option'.(($currency == 'NOK') ? ' selected="selected"':'').' value="NOK">'.$hc_lang_event['NOK'].'</option>
						<option'.(($currency == 'PLN') ? ' selected="selected"':'').' value="PLN">'.$hc_lang_event['PLN'].'</option>
						<option'.(($currency == 'SGD') ? ' selected="selected"':'').' value="SGD">'.$hc_lang_event['SGD'].'</option>
						<option'.(($currency == 'SEK') ? ' selected="selected"':'').' value="SEK">'.$hc_lang_event['SEK'].'</option>
						<option'.(($currency == 'CHF') ? ' selected="selected"':'').' value="CHF">'.$hc_lang_event['CHF'].'</option>
						<option'.(($currency == 'ILS') ? ' selected="selected"':'').' value="ILS">'.$hc_lang_event['ILS'].'</option>
						<option'.(($currency == 'MXN') ? ' selected="selected"':'').' value="MXN">'.$hc_lang_event['MXN'].'</option>
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

					$ticket = isset($ebtickets[$x]) ? $ebtickets[$x] : array();

					$hl = ($x % 2 == 0) ? ' hl':'';
					echo '
						<li class="row'.$hl.'">
							<input type="hidden" name="ticketid' . $x . '" id="ticketid' . $x . '" value="'.(isset($ticket['id']) ? $ticket['id'] : '').'" />
							<div class="txt" style="width:29%;">
								<input name="ticket' . $x . '" id="ticket' . $x . '" type="text" size="30" maxlength="200" value="'.(isset($ticket['name']) ? $ticket['name'] : '').'" />
							</div>
							<div class="txt" style="width:32%;">
								<input'.((isset($ticket['type']) && $ticket['type'] == 0 && $ticket['price'] != '0.00') ? ' checked="checked"' : '').' onclick="togTicketPrice('.$x.',0);" name="priceType'.$x.'" type="radio" value="0" checked="checked" />
									<input'.(((isset($ticket['type']) && $ticket['type'] > 0) || (isset($ticket['price']) && $ticket['price'] == '0.00')) ? ' disabled="disabled"' : '').' name="price' . $x . '" id="price' . $x . '" type="text" size="5" maxlength="7" value="'.((isset($ticket['type']) && $ticket['type'] == 0 && isset($ticket['price']) && $ticket['price'] != '0.00') ? $ticket['price'] : '').'" />
								<input'.((isset($ticket['type']) && $ticket['type'] == 0 && $ticket['price'] == '0.00') ? ' checked="checked"' : '').' onclick="togTicketPrice('.$x.',1);" id="priceType1'.$x.'" name="priceType'.$x.'" type="radio" value="1" /><label for="priceType1'.$x.'">'.$hc_lang_event['Free'].'</label>
								<input'.((isset($ticket['type']) && $ticket['type'] == 1) ? ' checked="checked"' : '').' onclick="togTicketPrice('.$x.',1);" id="priceType2'.$x.'" name="priceType'.$x.'" type="radio" value="2" /><label for="priceType2'.$x.'">'.$hc_lang_event['Donate'].'</label>
							</div>
							<div class="tools" style="width:12%;">
								<input name="qty' . $x . '" id="qty' . $x . '" type="text" size="5" maxlength="5" value="'.(isset($ticket['qty']) ? $ticket['qty'] : '').'" />
							</div>
							<div class="tools" style="width:8%;">
								<input name="fee' . $x . '" id="fee' . $x . '" type="checkbox" value="" />
							</div>
							<div class="txt" style="width:18%;">
								<input name="end' . $x . '" id="end' . $x . '" type="text" size="12" maxlength="10" value="'.(isset($ticket['end']) ? stampToDate($ticket['end'], $hc_cfg[24]) : '').'" />
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
					<input name="doBitly" id="doBitly" type="checkbox" onclick="toggleMe(document.getElementById(\'bitly\'));"'.($bitChk != '' ? ' checked="checked"':'').(($goBitly == 0 || $bitChk != '') ?  ' disabled="disabled"':'').' />
					'.$bitLabel.'
				</label>
				<div id="bitly"'.$bitShow.'>'.$bitNotice.'</div>
			</fieldset>
			<fieldset>
				<legend>'.$hc_lang_event['ConfMessage'].'</legend>
				'.(($subEmail != '') ? '
				<label>'.$hc_lang_event['ConfMessageSend'].'</label>
				<span class="frm_ctrls">
					<label for="sendmsg"><input name="sendmsg" id="sendmsg" type="checkbox" onclick="chgButton();">'.$hc_lang_event['SendMsg'].' <b>'.$subLink.$subName.'</b> (<a href="mailto:'.$subEmail.'">'.$subEmail.'</a>)</label>
				</span>
				<label>'.$hc_lang_event['VariableLabel'].'</label>
				<span class="output">
					<a id="tempLink" href="javascript:;" onclick="togVar(\'tempVars\', \'tempLink\');">'.$hc_lang_event['ShowVariables'].'</a>
				</span>
				<div id="tempVars" style="display:none;">
					<p>
						<span><a class="tooltip" data-tip="[event] - '.$hc_lang_event['Tip51'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[event]</span>
						<span><a class="tooltip" data-tip="[facebook] - '.$hc_lang_event['Tip52'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[facebook]</span>
						<span><a class="tooltip" data-tip="[twitter] - '.$hc_lang_event['Tip53'].'" href="javascript:;"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>[twitter]</span>
					</p>
				</div>
				<label for="message">'.$hc_lang_event['ConfMessageLabel'].'</label>
				<textarea rows="20" name="message" id="message" class="mce_edit">'.cOut($emailAccept).'</textarea>' : '
				<input type="hidden" name="sendmsg" id="sendmsg" value="no">'.$hc_lang_event['NoMessage']).'
			</fieldset>
			<input name="submit" id="submit" type="submit" value="'.$hc_lang_event['Save'].'" />
			</form>
			<div id="dsCal" class="datePicker"></div>
			
			<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
			<script src="'.CalRoot.'/inc/lang/'.$_SESSION['LangSet'].'/popCal.js"></script>
			<script src="'.CalRoot.'/inc/javascript/DateSelect.js"></script>
			<script>
			//<!--
			var tweetPrefix = "'.$hc_lang_event['TweetApproved'].'";
			var recOpts = new Array("daily","weekly","monthly");
			var calx = new CalendarPopup("dsCal");
			calx.showNavigationDropdowns();
			calx.setCssPrefix("hc_");
			calx.offsetX = 30;
			calx.offsetY = -5;

			function togVar(doTog, doLink){if(document.getElementById("tempVars").style.display == "none"){document.getElementById("tempVars").style.display = "block";document.getElementById("tempLink").innerHTML = "'.$hc_lang_event['HideVariables'].'";} else {document.getElementById("tempVars").style.display = "none";document.getElementById("tempLink").innerHTML = "'.$hc_lang_event['ShowVariables'].'";}}
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
				
				err += reqField(document.getElementById("eventHide"),"'.$hc_lang_event['Valid70'].'\n");
				if(document.getElementById("eventHide").value != ""){
					err += validNumber(document.getElementById("eventHide"),"'.$hc_lang_event['Valid68'].'\n");
					err += validGreater(document.getElementById("eventHide"),-1,"'.$hc_lang_event['Valid69'].'\n");
				}

				err +=validCheckArray("frmEventApprove","catID[]",1,"'.$hc_lang_event['Valid15'].'\n");

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
			}
			function chgStatus(){
				if(document.getElementById("eventStatus").value <= 1){
					newMsg = (document.getElementById("eventStatus").value == 1) ? "'.$emailAccept.'" : "'.$emailDecline.'";
					try {
						tinyMCE.get("message").setContent(newMsg);
					} catch(error) {
						document.getElementById("message").value = newMsg;
					}
				} else {
					document.getElementById("sendmsg").checked = false;
					chgButton();
				}
			}
			function chgButton(){
				document.getElementById("submit").value = (document.getElementById("sendmsg").checked) ? 
					"'.html_entity_decode(htmlentities($hc_lang_event["SaveWMessage"],ENT_QUOTES),ENT_NOQUOTES).'" : 
					"'.html_entity_decode(htmlentities($hc_lang_event["SaveWOMessage"],ENT_QUOTES),ENT_NOQUOTES).'";
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

			makeTinyMCE('82%',1,0,'eventDescription,message');
 		}
	}
?>