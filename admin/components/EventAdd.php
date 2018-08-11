<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
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
	
	var recPanel = new Array('daily', 'weekly', 'monthly');
	function showPanel(who)
	{
	  for(i = 0; i < recPanel.length; i++)
	  {
	    document.getElementById(recPanel[i]).style.display = (who == recPanel[i]) ? 'block':'none';
	  }
	  return false;
	}//end showPanel()
	
	function chkFrm(){
	dirty = 0;
	warn = "Event could not be added for the following reason(s):";
		
		if(document.frmEventAdd.eventRegistration.value == 1){
			if(isNaN(document.frmEventAdd.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n*Registration Limit Value Must Be Numeric';
			}//end if
			
			if(document.frmEventAdd.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Name';
			}//end if
			
			if(document.frmEventAdd.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Email Address';
			}//end if
		}//end if
		
		if(!isDate(document.frmEventAdd.eventDate.value, '<?echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*Event Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
		}//end if 
		
		if(isNaN(document.frmEventAdd.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Numeric';
		} else if((document.frmEventAdd.startTimeHour.value > 12) || (document.frmEventAdd.startTimeHour.value < 1)) {
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Between 1 and 12';
		}//end if
		
		if(isNaN(document.frmEventAdd.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Numeric';
		} else if((document.frmEventAdd.startTimeMins.value > 59) || (document.frmEventAdd.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Between 0 and 59';
		}//end if
		
		if(isNaN(document.frmEventAdd.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Numeric';
		} else if((document.frmEventAdd.endTimeHour.value > 12) || (document.frmEventAdd.endTimeHour.value < 1)) {
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Between 1 and 12';
		}//end if
		
		if(isNaN(document.frmEventAdd.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Numeric';
		} else if((document.frmEventAdd.endTimeMins.value > 59) || (document.frmEventAdd.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Between 0 and 59';
		}//end if
		
		if(document.frmEventAdd.recurCheck.checked){
			if(!isDate(document.frmEventAdd.recurEndDate.value, '<?echo $hc_popDateValid;?>')){
				dirty = 1;
				warn = warn + '\n*Recur End Date Format is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
			} else if(document.frmEventAdd.recurEndDate.value == ''){
				dirty = 1;
				warn = warn + '\n*Event Recur End Date is Required';
			} else if(compareDates(document.frmEventAdd.eventDate.value, '<?echo $hc_popDateValid;?>', document.frmEventAdd.recurEndDate.value, 'MM/d/yyyy') == 1){
				dirty = 1;
				warn = warn + "\n*Event Date Cannot Occur After End Recur Date";
			} else if(document.frmEventAdd.eventDate.value == document.frmEventAdd.recurEndDate.value){
				dirty = 1;
				warn = warn + "\n*Event Recur End Date Cannot Be Event Date";
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventAdd','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category Assignment is Required';
		}//end if
	
		if(document.frmEventAdd.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Title is Required';
		}//end if
		
		if(document.frmEventAdd.eventDate.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Date is Required';
		}//end if
		
		if(document.frmEventAdd.locName.value == ''){
			dirty = 1;
			warn = warn + '\n*Location Name is Required';
		}//end if
		
		if(compareDates(document.frmEventAdd.eventDate.value, '<?echo $hc_popDateValid;?>', '<?echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', '<?echo $hc_popDateValid;?>') == 0){
			dirty = 1;
			warn = warn + "\n*Event Date Cannot Occur Before Today";
		}//end if
		
		if(document.frmEventAdd.contactEmail.value != '' && chkEmail(document.frmEventAdd.contactEmail) == 0){
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
		if(document.frmEventAdd.overridetime.checked == true){
			document.frmEventAdd.specialtimeall.disabled = false;
			document.frmEventAdd.specialtimetbd.disabled = false;
			document.frmEventAdd.startTimeHour.disabled = true;
			document.frmEventAdd.startTimeMins.disabled = true;
			document.frmEventAdd.startTimeAMPM.disabled = true;
			document.frmEventAdd.endTimeHour.disabled = true;
			document.frmEventAdd.endTimeMins.disabled = true;
			document.frmEventAdd.endTimeAMPM.disabled = true;
			document.frmEventAdd.ignoreendtime.disabled = true;
		} else {
			document.frmEventAdd.specialtimeall.disabled = true;
			document.frmEventAdd.specialtimetbd.disabled = true;
			document.frmEventAdd.startTimeHour.disabled = false;
			document.frmEventAdd.startTimeMins.disabled = false;
			document.frmEventAdd.startTimeAMPM.disabled = false;
			if(document.frmEventAdd.ignoreendtime.checked == false){
				document.frmEventAdd.endTimeHour.disabled = false;
				document.frmEventAdd.endTimeMins.disabled = false;
				document.frmEventAdd.endTimeAMPM.disabled = false;
			}//end if
			document.frmEventAdd.ignoreendtime.disabled = false;
		}//end if
	}//end togOverride()
	
	function togEndTime(){
		if(document.frmEventAdd.ignoreendtime.checked == true){
			document.frmEventAdd.endTimeHour.disabled = true;
			document.frmEventAdd.endTimeMins.disabled = true;
			document.frmEventAdd.endTimeAMPM.disabled = true;
		} else {
			document.frmEventAdd.endTimeHour.disabled = false;
			document.frmEventAdd.endTimeMins.disabled = false;
			document.frmEventAdd.endTimeAMPM.disabled = false;
		}//end if
	}//end togEndTime()
	
	function togRecur(){
		if(document.frmEventAdd.recurCheck.checked == false){
			document.frmEventAdd.recurType1.disabled = true;
			document.frmEventAdd.recurType2.disabled = true;
			document.frmEventAdd.recurType3.disabled = true;
			document.frmEventAdd.recWeeklyDay1.disabled = true;
			document.frmEventAdd.recWeeklyDay2.disabled = true;
			document.frmEventAdd.recWeeklyDay3.disabled = true;
			document.frmEventAdd.recWeeklyDay4.disabled = true;
			document.frmEventAdd.recWeeklyDay5.disabled = true;
			document.frmEventAdd.recWeeklyDay6.disabled = true;
			document.frmEventAdd.recWeeklyDay7.disabled = true;
			document.frmEventAdd.recDaily1.disabled = true;
			document.frmEventAdd.recDaily2.disabled = true;
			document.frmEventAdd.dailyDays.disabled = true;
			document.frmEventAdd.recWeekly.disabled = true;
			document.frmEventAdd.monthlyOption.disabled = true;
			document.frmEventAdd.monthlyDays.disabled = true;
			document.frmEventAdd.recurEndDate.disabled = true;
			document.frmEventAdd.monthlyMonths.disabled = true;
		} else {
			document.frmEventAdd.recurType1.disabled = false;
			document.frmEventAdd.recurType2.disabled = false;
			document.frmEventAdd.recurType3.disabled = false;
			document.frmEventAdd.recWeeklyDay1.disabled = false;
			document.frmEventAdd.recWeeklyDay2.disabled = false;
			document.frmEventAdd.recWeeklyDay3.disabled = false;
			document.frmEventAdd.recWeeklyDay4.disabled = false;
			document.frmEventAdd.recWeeklyDay5.disabled = false;
			document.frmEventAdd.recWeeklyDay6.disabled = false;
			document.frmEventAdd.recWeeklyDay7.disabled = false;
			document.frmEventAdd.recDaily1.disabled = false;
			document.frmEventAdd.recDaily2.disabled = false;
			document.frmEventAdd.dailyDays.disabled = false;
			document.frmEventAdd.recWeekly.disabled = false;
			document.frmEventAdd.monthlyOption.disabled = false;
			document.frmEventAdd.monthlyDays.disabled = false;
			document.frmEventAdd.recurEndDate.disabled = false;
			document.frmEventAdd.monthlyMonths.disabled = false;
		}//end if
	}//end togRecur()
	
	function togRegistration(){
		if(document.frmEventAdd.eventRegistration.value == 0){
			document.frmEventAdd.eventRegAvailable.disabled = true;
		} else {
			document.frmEventAdd.eventRegAvailable.disabled = false;
		}//end if
	}//end togRegistration()
	
	var calx = new CalendarPopup();
	//-->
	</script>

<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Event Was Added Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Adding_Events", "Add Event", "To add an event to the " . CalName . " please fill out the form below.<br />If you make a mistake and wish to start over, click the 'Reset Form' button below.<br /><br />(<span style=\"color: #DC143C;\">*</span>) = Required Fields<br />(<span style=\"color: #0000FF;\">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style=\"color: #008000;\">*</span>) = Required for events <b>with registration</b>");	?>
			
	<form id="frmEventAdd" name="frmEventAdd" method="post" action="<?echo CalAdminRoot . "/" . HC_EventAddAction;?>" onsubmit="return chkFrm();">
	<input name="dateFormat" id="dateFormat" type="hidden" value="<?echo strtolower($hc_popDateFormat);?>" />
	<br />
	<fieldset>
		<legend>Event Details</legend>
		<div class="frmReq">
			<label for="eventTitle">Title:</label>
			<input name="eventTitle" id="eventTitle" type="text" size="65" maxlength="150" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription">Description:</label>
			<?makeTinyMCE("eventDescription", "75%", "advanced");?>
		</div>
		<div class="frmReq">
			<label for="eventDate">Event Date:</label>
			<input name="eventDate" id="eventDate" type="text" value="<?echo date($hc_popDateFormat);?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.eventDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
		<div class="frmOpt">
			<label>Start Time:</label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?echo date("h");?>" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td>
						<select name="startTimeAMPM" id="startTimeAMPM">
							<option <?if(date("A") == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?if(date("A") == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
						</select>
					</td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>End Time:</label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?echo date("h", mktime(date("h") + 1, 0, 0, 1, 1, 1971));?>" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="endTimeMins" id="endTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td>
						<select name="endTimeAMPM" id="endTimeAMPM">
							<option <?if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "AM"){?>selected="selected"<?}?> value="AM">AM</option>
							<option <?if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "PM"){?>selected="selected"<?}?> value="PM">PM</option>
						</select>
					</td>
					<td><label for="ignoreendtime" style="padding-left:20px;" class="radio">No End Time:</label></td>
					<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" /></td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="overridetime">Override&nbsp;Times:</label>&nbsp;<input type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
			<br />
			<label>&nbsp;</label>
			<label for="specialtimeall" class="radioWide"><input disabled="disabled" type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" />All Day Event</label>
			<br /><br />
			<label>&nbsp;</label>
			<label for="specialtimetbd" class="radioWide"><input disabled="disabled" type="radio" name="specialtime" id="specialtimetbd" value="allday" class="noBorderIE" />Event Times To Be Announced</label>
			<br />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Registration</legend>
		<div class="frmOpt">
			<label for="eventRegistration">Registration:</label>
			<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
				<option value="0">Do Not Allow Registration</option>
				<option value="1">Allow Registration</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventRegAvailable">Limit:</label>
			<input name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="0" disabled="disabled" />&nbsp;(0 = unlimited)
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Recurrance Information</legend>
		<div class="frmOpt">
			<label for="recurCheck">Event Recurs:</label>
			<input name="recurCheck" id="recurCheck" type="checkbox" onclick="togRecur();" class="noBorderIE" />&nbsp;(Check If Recurring)
			<br />
			<label>&nbsp;</label>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="width: 105px;vertical-align: top;">
						<label for="recurType1"><input name="recurType" id="recurType1" type="radio" value="daily" disabled="disabled" checked="checked" onclick="showPanel('daily')" class="noBorderIE" />Daily</label>
						<br />
						<label for="recurType2"><input name="recurType" id="recurType2" type="radio" value="weekly" disabled="disabled" onclick="showPanel('weekly')" class="noBorderIE" />Weekly</label>
						<br />
						<label for="recurType3"><input name="recurType" id="recurType3" type="radio" value="monthly" disabled="disabled" onclick="showPanel('monthly')" class="noBorderIE" />Monthly</label>
					</td>
					<td style="vertical-align: top;">
						<div class="recPanel" id="daily">
							<input name="dailyOptions" id="recDaily1" type="radio" checked="checked" disabled="disabled" value="EveryXDays" class="noBorderIE" />Every&nbsp;<input id="dailyDays" name="dailyDays" type="text" maxlength="2" size="2" value="1" disabled="disabled" />&nbsp;day(s)<br />
							<label for="recDaily2" class="radioWide"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" class="noBorderIE" />All&nbsp;Weekdays</label>
						</div>
						
						<div class="recPanel" id="weekly" style="display: none;">
							Every <input name="recWeekly" id="recWeekly" type="text" maxlength="2" size="2" value="1" /> week(s) on:
							<br />
								<input id="recWeeklyDay1" name="recWeeklyDay1" type="checkbox" class="noBorderIE" />Sun
								<input id="recWeeklyDay2" name="recWeeklyDay2" type="checkbox" class="noBorderIE" />Mon
								<input id="recWeeklyDay3" name="recWeeklyDay3" type="checkbox" class="noBorderIE" />Tue
								<input id="recWeeklyDay4" name="recWeeklyDay4" type="checkbox" class="noBorderIE" />Wed<br />
								<input id="recWeeklyDay5" name="recWeeklyDay5" type="checkbox" class="noBorderIE" />Thu
								<input id="recWeeklyDay6" name="recWeeklyDay6" type="checkbox" class="noBorderIE" />Fri
								<input id="recWeeklyDay7" name="recWeeklyDay7" type="checkbox" class="noBorderIE" />Sat
						</div>
						
						<div class="recPanel" id="monthly" style="display: none;">
							<input name="monthlyOption" id="monthlyOption" type="radio" checked="checked" value="Day" class="noBorderIE" />Day&nbsp;<input name="monthlyDays" id="monthlyDays" type="text" maxlength="2" size="2" value="<?echo date("d");?>" />&nbsp;of every&nbsp;<input name="monthlyMonths" id="monthlyMonths" type="text" maxlength="2" size="2" value="1" />&nbsp;month(s)
						</div>
					</td>
				</tr>
			</table>
			<label>Recurs Until:</label>
			<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.recurEndDate,'anchor2','<?echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Settings</legend>
		<div class="frmOpt">
			<label for="eventStatus">Status:</label>
			<select name="eventStatus" id="eventStatus">
				<option value="1">Approved -- Show on Calendar</option>
				<option value="2">Pending -- Hidden on Calendar</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventBillboard">Billboard:</label>
			<select name="eventBillboard" id="eventBillboard">
				<option value="0">Do Not Show On Billboard</option>
				<option value="1">Show On Billboard</option>
			</select>
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
			<?getCategories('frmEventAdd', 2);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Location Information</legend>
		<div class="frmOpt">
			<label for="locName">Name:</label>
			<input name="locName" id="locName" value="" type="text" maxlength="50" size="25" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress">Address:</label>
			<input name="locAddress" id="locAddress" value="" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress2">&nbsp;</label>
			<input name="locAddress2" id="locAddress2" value="" type="text" maxlength="75" size="25" />
		</div>
		<div class="frmOpt">
			<label for="locCity">City:</label>
			<input name="locCity" id="locCity" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locState"><?echo HC_StateLabel;?>:</label>
			<?
				$state = $hc_defaultState;
				include('../events/includes/' . HC_StateInclude);
			?><span style="color: #0000FF;">*</span>
		</div>
		<div class="frmOpt">
			<label for="locZip">Postal Code:</label>
			<input name="locZip" id="locZip" value="" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Contact Info</legend>
		<div class="frmOpt">
			<label for="contactName">Name:</label>
			<input name="contactName" id="contactName" type="text" maxlength="50" size="20" value="" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactEmail">Email:</label>
			<input name="contactEmail" id="contactEmail" type="text" maxlength="75" size="30" value="" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactPhone">Phone:</label>
			<input name="contactPhone" id="contactPhone" type="text" maxlength="15" size="15" value="" />
		</div>
		<div class="frmOpt">
			<label for="contactURL">Website:</label>
			<input name="contactURL" id="contactURL" type="text" maxlength="100" size="40" value="http://" />
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value="   Save Event   " class="button" />
	</form>