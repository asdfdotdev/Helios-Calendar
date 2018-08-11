<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event Approved Successfully!");
				break;
				
			case "2" :
				feedback(1, "Event Series Approved Successfully!");
				break;
				
			case "3" :
				feedback(1, "Event Declined Successfully!");
				break;
				
			case "4" :
				feedback(1, "Event Series Declined Successfully!");
				break;
		}//end switch
	}//end if
	
	if(!isset($_GET['sID']) && !isset($_GET['eID'])){
		$result = doQuery("SELECT PkID, Title, StartDate, SeriesID
							FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND 
										IsApproved = 2 AND 
										StartDate >= NOW() 
							ORDER BY SeriesID, StartDate, Title");
		if(hasRows($result)){
			appInstructions(0, "Pending_Events", "Pending Events", "You can approve/decline events and send a message informing the event submitter of the event's status change by clicking on the <img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> icon beside the event. <br /><br />To approve/decline all pending events in a series, click the <img src=\"" . CalAdminRoot . "/images/icons/iconEditGroup.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> icon  atop the series listing.");
			$curSeries = "";
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				if($row[3] == '' && $cnt == 0){	?>
					<div class="pendingList">Pending Individual Events</div>
			<?	}//end if
				
				if($row[3] != '' && $curSeries != $row[3]){
					$cnt = 0;
					$curSeries = $row[3];?>
					<div class="pendingList">
						<div style="width: 450px; float:left;">Pending Event Series</div>
						<div style="width: 73px;float:left;text-align:right;"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;sID=<?echo $row[3];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEditGroup.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
						&nbsp;
					</div>
			<?	}//end if	?>
					<div class="pendingListTitle<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;eID=<?echo $row[0];?>" class="main"><?echo $row[1];?></a></div>
					<div class="pendingListDate<?if($cnt % 2 == 1){echo "HL";}?>"><?echo StampToDate($row[2], $hc_popDateFormat);?></div>
					<div class="pendingListTools<?if($cnt % 2 == 1){echo "HL";}?>">&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
		<?	$cnt++;
			}//end while
		} else {	?>
			<br />
			<b>There are currently no pending events.</b>
	<?	}//end if
	} else {
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (3,4)");
		
		$emailAccept = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,0,0));
		$emailDecline = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,1,0));
		$emailAccept =  str_replace('\'', '\\\'', $emailAccept);
		$emailDecline = str_replace('\'', '\\\'', $emailDecline);
		
		if($emailAccept == ''){
			$emailAccept = "Your event has been approved and is now available on our website. Thank you for using our calendar.";
		}//end if
		
		if($emailDecline == ''){
			$emailDecline = "Your event has been declined and will not be available on our website. Thank you for using our calendar.";
		}//end if
		
		if(isset($_GET['eID'])){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND PkID = '" . cIn($_GET['eID']) . "'");
			$whatAmI = "Event";
			$editThis = $_GET['eID'];
			$editType = 1;
		} elseif(isset($_GET['sID'])) {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND SeriesID = '" . cIn($_GET['sID']) . "'");
			$whatAmI = "Event Series";
			$editThis = $_GET['sID'];
			$editType = 2;
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chgStatus(){
			if(document.frmEventApprove.eventStatus.value > 0){
				document.frmEventApprove.message.value = '<?echo $emailAccept;?>';
			} else {
				document.frmEventApprove.message.value = '<?echo $emailDecline;?>';
			}//end if
			
		}//end chgStatus()
		
		function chngClock(obj,inc,maxVal){
		var val = parseInt(obj.value,10);
		val += inc;
			
			if(maxVal == 59){
				if(val > maxVal) val = 0;
				if(val < 0) val = maxVal;	
			} else {
				if(val > maxVal) val = 1;
				if(val <= 0) val = maxVal;	
			}//end if
			
			if(val < 10) val = "0" + val;
			obj.value = val;
		}//end chngClock()
		
		function togRegistrants(){
			if(document.getElementById('registrant').style.display == 'none'){
				document.getElementById('registrant').style.display = 'block';
				document.frmEventApprove.eventRegistrants.value = 'Hide Registrants';
			} else {
				document.getElementById('registrant').style.display = 'none';
				document.frmEventApprove.eventRegistrants.value = 'Show Registrants';
			}//end if
		}//end showPanel()
		
		function chkFrm(){
		dirty = 0;
		warn = "Event could not be updated for the following reason(s):";
			
			if(document.frmEventApprove.eventStatus.value == 0){
				return true;
			}//end if
			
			if(document.frmEventApprove.eventRegistration.value == 1){
				if(isNaN(document.frmEventApprove.eventRegAvailable.value) == true){
					dirty = 1;
					warn = warn + '\n*Registration Limit Value Must Be Numeric';
				}//end if
				
				if(document.frmEventApprove.contactName.value == ''){
					dirty = 1;
					warn = warn + '\n*Registration Requires Contact Name';
				}//end if
				
				if(document.frmEventApprove.contactEmail.value == ''){
					dirty = 1;
					warn = warn + '\n*Registration Requires Contact Email Address';
				}//end if
			}//end if
			
			if(validateCheckArray('frmEventApprove','catID[]',1) > 0){
				dirty = 1;
				warn = warn + '\n*Category Assignment is Required';
			}//end if
		
			if(document.frmEventApprove.eventTitle.value == ''){
				dirty = 1;
				warn = warn + '\n*Event Title is Required';
			}//end if
			
			if(!isDate(document.frmEventApprove.eventDate.value, '<?echo $hc_popDateValid;?>')){
				dirty = 1;
				warn = warn + '\n*Event Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateValid);?>';
			}//end if
			
			if(isNaN(document.frmEventApprove.startTimeHour.value) == true){
				dirty = 1;
				warn = warn + '\n*Start Hour Must Be Numeric';
			} else if((document.frmEventApprove.startTimeHour.value > 12) || (document.frmEventApprove.startTimeHour.value < 1)) {
				dirty = 1;
				warn = warn + '\n*Start Hour Must Be Between 1 and 12';
			}//end if
			
			if(isNaN(document.frmEventApprove.startTimeMins.value) == true){
				dirty = 1;
				warn = warn + '\n*Start Minute Must Be Numeric';
			} else if((document.frmEventApprove.startTimeMins.value > 59) || (document.frmEventApprove.startTimeMins.value < 0)) {
				dirty = 1;
				warn = warn + '\n*Start Minute Must Be Between 0 and 59';
			}//end if
			
			if(isNaN(document.frmEventApprove.endTimeHour.value) == true){
				dirty = 1;
				warn = warn + '\n*End Hour Must Be Numeric';
			} else if((document.frmEventApprove.endTimeHour.value > 12) || (document.frmEventApprove.endTimeHour.value < 1)) {
				dirty = 1;
				warn = warn + '\n*End Hour Must Be Between 1 and 12';
			}//end if
			
			if(isNaN(document.frmEventApprove.endTimeMins.value) == true){
				dirty = 1;
				warn = warn + '\n*End Minute Must Be Numeric';
			} else if((document.frmEventApprove.endTimeMins.value > 59) || (document.frmEventApprove.endTimeMins.value < 0)) {
				dirty = 1;
				warn = warn + '\n*End Minute Must Be Between 0 and 59';
			}//end if
			
			if(document.frmEventApprove.locPreset.value == 0){
				if(document.frmEventApprove.locName.value == ''){
					dirty = 1;
					warn = warn + '\n*Location Name is Required';
				}//end if
			}//end if
			
			if(document.frmEventApprove.contactEmail.value != '' && chkEmail(document.frmEventApprove.contactEmail) == 0){
				dirty = 1;
				warn = warn + '\n*Event Contact Email Format is Invalid';
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n\nPlease complete the form and try again.');
				return false;
			} else {
				return true;
			}//end if
		}//end chkFrm()
		
		function togOverride(){
			if(document.frmEventApprove.overridetime.checked){
				document.frmEventApprove.specialtimeall.disabled = false;
				document.frmEventApprove.specialtimetbd.disabled = false;
				document.frmEventApprove.startTimeHour.disabled = true;
				document.frmEventApprove.startTimeMins.disabled = true;
				document.frmEventApprove.startTimeAMPM.disabled = true;
				document.frmEventApprove.endTimeHour.disabled = true;
				document.frmEventApprove.endTimeMins.disabled = true;
				document.frmEventApprove.endTimeAMPM.disabled = true;
				document.frmEventApprove.ignoreendtime.disabled = true;
			} else {
				document.frmEventApprove.specialtimeall.disabled = true;
				document.frmEventApprove.specialtimetbd.disabled = true;
				document.frmEventApprove.startTimeHour.disabled = false;
				document.frmEventApprove.startTimeMins.disabled = false;
				document.frmEventApprove.startTimeAMPM.disabled = false;
				if(document.frmEventApprove.ignoreendtime.checked == false){
					document.frmEventApprove.endTimeHour.disabled = false;
					document.frmEventApprove.endTimeMins.disabled = false;
					document.frmEventApprove.endTimeAMPM.disabled = false;
				}//end if
				document.frmEventApprove.ignoreendtime.disabled = false;
			}//end if
		}//end togOverride()
		
		function togEndTime(){
			if(document.frmEventApprove.ignoreendtime.checked){
				document.frmEventApprove.endTimeHour.disabled = true;
				document.frmEventApprove.endTimeMins.disabled = true;
				document.frmEventApprove.endTimeAMPM.disabled = true;
			} else {
				document.frmEventApprove.endTimeHour.disabled = false;
				document.frmEventApprove.endTimeMins.disabled = false;
				document.frmEventApprove.endTimeAMPM.disabled = false;
			}//end if
		}//end togEndTime()
		
		function togRegistration(){
			if(document.frmEventApprove.eventRegistration.value == 0){
				document.frmEventApprove.eventRegAvailable.disabled = true;
			} else {
				document.frmEventApprove.eventRegAvailable.disabled = false;
			}//end if
		}//end togRegistration()
		
		function togLocation(){
			if(document.frmEventApprove.locPreset.value == 0){
				document.frmEventApprove.locName.disabled = false;
				document.frmEventApprove.locAddress.disabled = false;
				document.frmEventApprove.locAddress2.disabled = false;
				document.frmEventApprove.locCity.disabled = false;
				document.frmEventApprove.locState.disabled = false;
				document.frmEventApprove.locZip.disabled = false;
			} else {
				document.frmEventApprove.locName.disabled = true;
				document.frmEventApprove.locAddress.disabled = true;
				document.frmEventApprove.locAddress2.disabled = true;
				document.frmEventApprove.locCity.disabled = true;
				document.frmEventApprove.locState.disabled = true;
				document.frmEventApprove.locZip.disabled = true;
			}//end if
		}//end togEndTime()
		
		function chgButton(){
			if(document.frmEventApprove.sendmsg.checked){
				document.frmEventApprove.message.disabled = false;
				document.frmEventApprove.submit.value = ' Save Event & Send Message';
			} else {
				document.frmEventApprove.message.disabled = true;
				document.frmEventApprove.submit.value = ' Save Event ';
			}//end if
		}//end chgButton()
		
		var calx = new CalendarPopup();
		//-->
		</script>
	
	<?	if(hasRows($result)){
			$eID = cOut(mysql_result($result,0,0));
			$eventStatus = cOut(mysql_result($result,0,17));
			$eventBillboard = cOut(mysql_result($result,0,18));
			$eventTitle = cOut(mysql_result($result,0,1));
			$eventDesc = cOut(mysql_result($result,0,8));
			
			$locName = cOut(mysql_result($result,0,2));
			$locAddress = cOut(mysql_result($result,0,3));
			$locAddress2 = cOut(mysql_result($result,0,4));
			$locCity = cOut(mysql_result($result,0,5));
			$locState = cOut(mysql_result($result,0,6));
			$locZip = cOut(mysql_result($result,0,7));
			$contactName = cOut(mysql_result($result,0,13));
			$contactEmail = cOut(mysql_result($result,0,14));
			$contactPhone = cOut(mysql_result($result,0,15));
			$contactURL = cOut(mysql_result($result,0,24));
			$allowRegistration = cOut(mysql_result($result,0,25));
			$maxRegistration = cOut(mysql_result($result,0,26));
			$views = cOut(mysql_result($result,0,28));
			$message = cOut(mysql_result($result,0,29));
			$locID = cOut(mysql_result($result,0,35));
			
			$subName = cOut(mysql_result($result,0,20));
			$subEmail = cOut(mysql_result($result,0,21));
			
			if($contactURL == ""){
				$contactURL = "http://";
			}//end if
			
			$eventDate = stampToDate(mysql_result($result,0,9), $hc_popDateFormat);
			$AllDay = "";
			
			if(mysql_result($result,0,11) == 0){
				$tbd = 0;
				if(mysql_result($result,0,10) != ''){
					$startTimeParts = explode(":", mysql_result($result,0,10));
					$startTimeHour = date("h", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
					$startTimeMins = date("i", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
					$startTimeAMPM = date("A", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
					
					if(mysql_result($result,0,12) != ''){
						$endTimeParts = explode(":", mysql_result($result,0,12));
						$endTimeHour = date("h", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						$endTimeMins = date("i", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						$endTimeAMPM = date("A", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						
					} else {
						$endTimeParts = explode(":", mysql_result($result,0,10));
						$endTimeHour = date("h", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						$endTimeMins = date("i", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						$endTimeAMPM = date("A", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
						$noEndTime = 1;
					}//end if
					
				}//end if
				
			} else {
				$startTimeHour = date("h");
				$startTimeMins = "00";
				$startTimeAMPM = date("A");
				$endTimeHour = date("h", mktime(date("H") + 1, 0, 0, 1, 1, 1971));
				$endTimeMins = "00";
				$endTimeAMPM = date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971));
				
				if(mysql_result($result,0,11) == 1){
					$tbd = 1;
				} elseif(mysql_result($result,0,11) == 2){
					$tbd = 2;
				}//end if
				
			}//end if
			
			appInstructions(0, "Pending_Events", "Pending Event Status Update", "Fill out the form below to change the status of this " . $whatAmI . ".");	?>
			<br />
			<form name="frmEventApprove" id="frmEventApprove" method="post" action="<?echo CalAdminRoot . "/components/EventPendingAction.php";?>" onsubmit="return chkFrm();">
			<input type="hidden" name="editthis" id="editthis" value="<?echo cOut($editThis);?>" />
			<input type="hidden" name="edittype" id="edittype" value="<?echo cOut($editType);?>" />
			<input type="hidden" name="subname" id="subname" value="<?echo cOut($subName);?>" />
			<input type="hidden" name="subemail" id="subemail" value="<?echo cOut($subEmail);?>" />
			<input type="hidden" name="prevStatus" id="prevStatus" value="<?echo $eventStatus;?>" />
			<input name="dateFormat" id="dateFormat" type="hidden" value="<?echo strtolower($hc_popDateFormat);?>" />
		<?	if($message != ''){	?>
			<fieldset>
				<legend>Message From Event Submitter</legend>
				<?echo $message;?>
			</fieldset>
			<br />
		<?	}//end if	?>
			<fieldset>
				<legend>Event Details</legend>
				<div class="frmReq">
					<label for="eventTitle">Title:</label>
					<input name="eventTitle" id="eventTitle" type="text" size="65" maxlength="150" value="<?echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="eventDescription">Description:</label>
					<?makeTinyMCE("eventDescription", "75%", "advanced", $eventDesc);?>
				</div>
			<?	if($editType == 1){	?>
				<div class="frmReq">
					<label for="eventDate">Event Date:</label>
					<input name="eventDate" id="eventDate" type="text" value="<?echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventApprove.eventDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label>Start Time:</label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?echo $startTimeHour;?>" size="2" maxlength="2" <?if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="startTimeMins" id="startTimeMins" type="text" value="<?echo $startTimeMins;?>" size="2" maxlength="2"  <?if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td>
								<select name="startTimeAMPM" id="startTimeAMPM"<?if($tbd > 0){echo " disabled=\"disabled\" ";}//end if?>>
									<option <?if($startTimeAMPM == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
									<option <?if($startTimeAMPM == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
								</select>
							</td>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label>End Time:</label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?echo $endTimeHour;?>" size="2" maxlength="2" <?if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="endTimeMins" id="endTimeMins" type="text" value="<?echo $endTimeMins;?>" size="2" maxlength="2" <?if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td>
								<select name="endTimeAMPM" id="endTimeAMPM"<?if(isset($noEndTime) OR $tbd > 0){echo " disabled=\"disabled\" ";}//end if?>>
									<option <?if($endTimeAMPM == "AM"){?>selected="selected"<?}?> value="AM">AM</option>
									<option <?if($endTimeAMPM == "PM"){?>selected="selected"<?}?> value="PM">PM</option>
								</select>
							</td>
							<td><label for="ignoreendtime" style="padding-left:20px;" class="radio">No End Time:</label></td>
							<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" <?if(isset($noEndTime)){echo "checked=\"checked\" ";}//end if?> <?if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="overridetime">Override&nbsp;Times:</label>&nbsp;<input <?if($tbd > 0){echo "checked=\"checked\" ";}//end if?>type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
					<br />
					<label>&nbsp;</label>
					<label for="specialtimeall" class="radioWide"><input type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" <?if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 1){echo "checked=\"checked\" ";}//end if?>/>All Day Event</label>
					<br /><br />
					<label>&nbsp;</label>
					<label for="specialtimetbd" class="radioWide"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" <?if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 2){echo "checked=\"checked\" ";}//end if?>/>Event Times To Be Announced</label>
					<br />
				</div>
			<?	} else {	?>
				<div class="frmOpt">
					<label for="eventDate">Event&nbsp;Dates:</label>
					<?	echo $eventDate;
						$cnt = 1;
						while($row = mysql_fetch_row($result)){
							if($cnt % 5 == 0){
								echo "<br />";
								if($cnt % 10 == 0){echo "<label>&nbsp;</label>";}//end if
							}//end if
							
							echo " " . stampToDate($row[9], $hc_popDateFormat);
							$cnt ++;
						}//end while	?>
			    </div>
				<div class="frmOpt">
					<label>Event Time:</label>
				<?	if($tbd == 1){
						echo "All Day Event";
					} else if($tbd == 2){
						echo "TBD";
					} else {
						if(isset($noEndTime)){
							echo "Starts at: " . $startTimeHour . ":" . $startTimeMins . " " . $startTimeAMPM;
						} else {
							echo $startTimeHour . ":" . $startTimeMins . " " . $startTimeAMPM . " - " . $endTimeHour . ":" . $endTimeMins . " " . $endTimeAMPM;
						}//end if
					}//end if	?>
			    </div>
			<?	}//end if	?>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Registration</legend>
				<div class="frmOpt">
					<label for="eventRegistration">Registration:</label>
					<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
						<option <?if($allowRegistration == 0){echo "selected=\"selected\"";}?> value="0">Do Not Allow Registration</option>
						<option <?if($allowRegistration == 1){echo "selected=\"selected\"";}?> value="1">Allow Registration</option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="eventRegAvailable">Limit:</label>
					<input <?if($allowRegistration == 0){echo "disabled=\"disabled\"";}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?echo $maxRegistration;?>" />&nbsp;(0 = unlimited)
				</div>
			<?	if($allowRegistration == 1){	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
				<?	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
					$regUsed = mysql_result($result,0,0);
					$regAvailable = $maxRegistration;
					
					if($maxRegistration == 0) {
						echo "<b>" . $regUsed . " Total Registrants</b>";
					} elseif($maxRegistration <= mysql_result($result,0,0)){	?>
						<img src="<?echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;" />
					<?	echo "<b>" . $regUsed . " Total Registrants</b> -- Registering Overflow Only";
					} else {
						if($regAvailable > 0){
							if($regUsed > 0){
								$regWidth = ($regUsed / $regAvailable) * 100;
								$fillWidth = 100 - $regWidth;
							} else {
								$regWidth = 0;
								$fillWidth = 100;
							}//end if	?>
							<img src="<?echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;" /><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;" />
					<?		echo "<b>" . $regUsed . " Current Registrants</b>";
						}//end if
					}//end if	?>
				</div>
			<?	}//end if?>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Settings</legend>
				<div class="frmOpt">
					<label for="eventStatus">Status:</label>
					<select name="eventStatus" id="eventStatus" onChange="javascript:chgStatus();">
						<option value="1">Approved -- Add to Calendar</option>
						<option value="0">Declined -- Remove from Calendar</option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="eventBillboard">Billboard:</label>
					<select name="eventBillboard" id="eventBillboard">
						<option <?if($eventBillboard == 0){echo "selected=\"selected\"";}//end if?> value="0">Do Not Show On Billboard</option>
						<option <?if($eventBillboard == 1){echo "selected=\"selected\"";}//end if?> value="1">Show On Billboard</option>
					</select>
				</div>
				<div class="frmOpt">
					<label>Categories:</label>
					<?	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "eventcategories.EventID
									FROM " . HC_TblPrefix . "categories 
										LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID AND " . HC_TblPrefix . "eventcategories.EventID = " . cIn($eID) . ") 
									WHERE " . HC_TblPrefix . "categories.IsActive = 1
									ORDER BY CategoryName";
						getCategories('frmEventApprove', 2, $query);?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Location Information</legend>
			<?	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
				if(hasRows($resultL)){	?>
				<div class="frmReq">
					<label for="locPreset">Preset:</label>
					<select name="locPreset" id="locPreset" onchange="togLocation();">
						<option value="0">Custom Location (Enter Location Below)</option>
					<?	while($row = mysql_fetch_row($resultL)){	?>
						<option <?if($row[0] == $locID){echo "selected=\"selected\"";}?> value="<?echo $row[0];?>"><?echo $row[1];?></option>
					<?	}//end while	?>
					</select>
				</div>
			<?	} else {	?>
					<input type="hidden" name="locPreset" value="0" />
			<?	}//end if	?>
				<div class="frmReq">
					<label for="locName">Name:</label>
					<input <?if($locID > 0){echo "disabled=\"disabled\"";}?> name="locName" id="locName" value="<?echo $locName;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress">Address:</label>
					<input <?if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress" id="locAddress" value="<?echo $locAddress;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress2">&nbsp;</label>
					<input <?if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress2" id="locAddress2" value="<?echo $locAddress2;?>" type="text" maxlength="75" size="25" />
				</div>
				<div class="frmOpt">
					<label for="locCity">City:</label>
					<input <?if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCity" id="locCity" value="<?echo $locCity;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locState"><?echo HC_StateLabel;?>:</label>
					<?	$state = $locState;
						if($locID > 0){
							$stateDisabled = 1;
							$state = $hc_defaultState;
						}//end if
						include('../events/includes/' . HC_StateInclude);?><span style="color: #0000FF;">*</span>
				</div>
				<div class="frmOpt">
					<label for="locZip">Postal Code:</label>
					<input <?if($locID > 0){echo "disabled=\"disabled\"";}?> name="locZip" id="locZip" value="<?echo $locZip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Contact Info</legend>
				<div class="frmOpt">
					<label for="contactName">Name:</label>
					<input name="contactName" id="contactName" type="text" value="<?echo $contactName;?>" maxlength="50" size="20" /><span style="color: #008000;">*</span>
				</div>
				<div class="frmOpt">
					<label for="contactEmail">Email:</label>
					<input name="contactEmail" id="contactEmail" type="text" value="<?echo $contactEmail;?>" maxlength="75" size="30" /><span style="color: #008000;">*</span>
				</div>
				<div class="frmOpt">
					<label for="contactPhone">Phone:</label>
					<input name="contactPhone" id="contactPhone" type="text" value="<?echo $contactPhone;?>" maxlength="25" size="20" />
				</div>
				<div class="frmOpt">
					<label for="contactURL">Website:</label>
					<input name="contactURL" id="contactURL" type="text" value="<?echo $contactURL;?>" maxlength="100" size="40" />
				<?	if($contactURL != 'http://'){	?>
						<a href="<?echo $contactURL;?>" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconWebsite.gif" width="16" height="16" alt="" border="0" /></a>
				<?	}//end if	?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Confirmation Message</legend>
			<?	if($subEmail != ''){	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="sendmsg" class="radioWide"><input name="sendmsg" id="sendmsg" type="checkbox" onclick="javascript:chgButton();" class="noBorderIE" /> Send Confirmation Message</label>
				</div>
				<br /><br />
				<div class="frmOpt">
					<label for="message">&nbsp;</label>
					<?echo $subName;?> (<a href="mailto:<?echo $subEmail;?>" class="main"><?echo $subEmail;?></a>),
					<textarea convert_this="false" disabled="disabled" rows="7" cols="60" name="message" id="message"><?echo $emailAccept;?></textarea><br />
					<label>&nbsp;</label>
					<?echo CalAdmin . "<br />" . CalAdminEmail;?>
				</div>	
			<?	} else {	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<input type="hidden" name="sendmsg" id="sendmsg" value="no">
					Submitter's Email Address Unavailable.<br />Confirmation cannot be sent.
				</div>
			<?	}//end if	?>
			</fieldset>
			<br />
			<input type="submit" name="submit" id="submit" value=" Save Event " class="button" />&nbsp;&nbsp;
			<input type="button" name="cancel" id="cancel" value="  Cancel  " onclick="window.location.href='<?echo CalAdminRoot;?>/index.php?com=eventpending';return false;" class="button" />
			</form>
	<?	} else {	?>
			<br />
			You are attempting to access an invalid pending event.
			<br /><br />
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending" class="main">Click here to view pending events.</a>
	<?	}//end if
	}//end if	?>