<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 1");
	$doSubmit = mysql_result($result,0,0);
		
	if($doSubmit == 1){
		if(!isset($_GET['msg'])){	?>
			<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
			<script language="JavaScript" type="text/javascript">
			//<!--
			function chngClock(obj,inc,maxVal){
				if(obj.disabled == false){
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
				}//end if
			}//end chngClock()
			
			function togOverride(){
				if(document.frmEventSubmit.overridetime.checked){
					document.frmEventSubmit.specialtimeall.disabled = false;
					document.frmEventSubmit.startTimeHour.disabled = true;
					document.frmEventSubmit.startTimeMins.disabled = true;
					document.frmEventSubmit.startTimeAMPM.disabled = true;
					document.frmEventSubmit.endTimeHour.disabled = true;
					document.frmEventSubmit.endTimeMins.disabled = true;
					document.frmEventSubmit.endTimeAMPM.disabled = true;
					document.frmEventSubmit.ignoreendtime.disabled = true;
				} else {
					document.frmEventSubmit.specialtimeall.disabled = true;
					document.frmEventSubmit.startTimeHour.disabled = false;
					document.frmEventSubmit.startTimeMins.disabled = false;
					document.frmEventSubmit.startTimeAMPM.disabled = false;
					if(document.frmEventSubmit.ignoreendtime.checked == false){
						document.frmEventSubmit.endTimeHour.disabled = false;
						document.frmEventSubmit.endTimeMins.disabled = false;
						document.frmEventSubmit.endTimeAMPM.disabled = false;
					}//end if
					document.frmEventSubmit.ignoreendtime.disabled = false;
				}//end if
			}//end togOverride()
			
			function togEndTime(){
				if(document.frmEventSubmit.ignoreendtime.checked){
					document.frmEventSubmit.endTimeHour.disabled = true;
					document.frmEventSubmit.endTimeMins.disabled = true;
					document.frmEventSubmit.endTimeAMPM.disabled = true;
				} else {
					document.frmEventSubmit.endTimeHour.disabled = false;
					document.frmEventSubmit.endTimeMins.disabled = false;
					document.frmEventSubmit.endTimeAMPM.disabled = false;
				}//end if
			}//end togEndTime()
			
			function togRecur(){
				if(document.frmEventSubmit.recurCheck.checked){
					document.frmEventSubmit.recurType1.disabled = false;
					document.frmEventSubmit.recurType2.disabled = false;
					document.frmEventSubmit.recurType3.disabled = false;
					document.frmEventSubmit.recWeeklyDay1.disabled = false;
					document.frmEventSubmit.recWeeklyDay2.disabled = false;
					document.frmEventSubmit.recWeeklyDay3.disabled = false;
					document.frmEventSubmit.recWeeklyDay4.disabled = false;
					document.frmEventSubmit.recWeeklyDay5.disabled = false;
					document.frmEventSubmit.recWeeklyDay6.disabled = false;
					document.frmEventSubmit.recWeeklyDay7.disabled = false;
					document.frmEventSubmit.recDaily1.disabled = false;
					document.frmEventSubmit.recDaily2.disabled = false;
					document.frmEventSubmit.dailyDays.disabled = false;
					document.frmEventSubmit.recWeekly.disabled = false;
					document.frmEventSubmit.monthlyOption.disabled = false;
					document.frmEventSubmit.monthlyDays.disabled = false;
					document.frmEventSubmit.recurEndDate.disabled = false;
					document.frmEventSubmit.monthlyMonths.disabled = false;
				} else {
					document.frmEventSubmit.recurType1.disabled = true;
					document.frmEventSubmit.recurType2.disabled = true;
					document.frmEventSubmit.recurType3.disabled = true;
					document.frmEventSubmit.recWeeklyDay1.disabled = true;
					document.frmEventSubmit.recWeeklyDay2.disabled = true;
					document.frmEventSubmit.recWeeklyDay3.disabled = true;
					document.frmEventSubmit.recWeeklyDay4.disabled = true;
					document.frmEventSubmit.recWeeklyDay5.disabled = true;
					document.frmEventSubmit.recWeeklyDay6.disabled = true;
					document.frmEventSubmit.recWeeklyDay7.disabled = true;
					document.frmEventSubmit.recDaily1.disabled = true;
					document.frmEventSubmit.recDaily2.disabled = true;
					document.frmEventSubmit.dailyDays.disabled = true;
					document.frmEventSubmit.recWeekly.disabled = true;
					document.frmEventSubmit.monthlyOption.disabled = true;
					document.frmEventSubmit.monthlyDays.disabled = true;
					document.frmEventSubmit.recurEndDate.disabled = true;
					document.frmEventSubmit.monthlyMonths.disabled = true;
				}//end if
			}//end togRecur()
			
			function togRegistration(){
				if(document.frmEventSubmit.eventRegistration.value == 0){
					document.frmEventSubmit.eventRegAvailable.disabled = true;
				} else {
					document.frmEventSubmit.eventRegAvailable.disabled = false;
				}//end if
			}//end togRegistration()
			
			function togRegistration(){
				if(document.frmEventSubmit.eventRegistration.value == 0){
					document.frmEventSubmit.eventRegAvailable.disabled = true;
				} else {
					document.frmEventSubmit.eventRegAvailable.disabled = false;
				}//end if
			}//end togRegistration()
			
			function togAdminMsg(){
				if(document.frmEventSubmit.goadminmessage.checked){
					document.frmEventSubmit.adminmessage.disabled = false;
				} else {
					document.frmEventSubmit.adminmessage.disabled = true;
				}//end if
			}//end togRegistration()
			
			var recPanel = new Array('daily', 'weekly', 'monthly');
			function showPanel(who)
			{
			  for(i = 0; i < recPanel.length; i++)
			  {
			    document.getElementById(recPanel[i]).style.display = (who == recPanel[i]) ? 'block':'none';
			  }
			  return false;
			}//end showPanel()
			
			var calx = new CalendarPopup();
			
			function chkFrm(){
				dirty = 0;
				warn = "Event could not be added for the following reason(s):";
				
					if(document.frmEventSubmit.eventRegistration.value == 1){
						if(isNaN(document.frmEventSubmit.eventRegAvailable.value) == true){
							dirty = 1;
							warn = warn + '\n*Registration Limit Value Must Be Numeric';
						}//end if
						
						if(document.frmEventSubmit.contactName.value == ''){
							dirty = 1;
							warn = warn + '\n*Registration Requires Contact Name';
						}//end if
						
						if(document.frmEventSubmit.contactEmail.value == ''){
							dirty = 1;
							warn = warn + '\n*Registration Requires Contact Email Address';
						}//end if
					}//end if
							
					if(!isDate(document.frmEventSubmit.eventDate.value, '<?echo $hc_popDateValid;?>')){
						dirty = 1;
						warn = warn + '\n*Event Date is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
					} else if(document.frmEventSubmit.eventDate.value == ''){
						dirty = 1;
						warn = warn + '\n*Event Date is Required';
					}//end if 
					
					if(isNaN(document.frmEventSubmit.startTimeHour.value) == true){
						dirty = 1;
						warn = warn + '\n*Start Hour Must Be Numeric';
					} else if((document.frmEventSubmit.startTimeHour.value > 12) || (document.frmEventSubmit.startTimeHour.value < 1)) {
						dirty = 1;
						warn = warn + '\n*Start Hour Must Be Between 1 and 12';
					}//end if
					
					if(isNaN(document.frmEventSubmit.startTimeMins.value) == true){
						dirty = 1;
						warn = warn + '\n*Start Minute Must Be Numeric';
					} else if((document.frmEventSubmit.startTimeMins.value > 59) || (document.frmEventSubmit.startTimeMins.value < 0)) {
						dirty = 1;
						warn = warn + '\n*Start Minute Must Be Between 0 and 59';
					}//end if
					
					if(isNaN(document.frmEventSubmit.endTimeHour.value) == true){
						dirty = 1;
						warn = warn + '\n*End Hour Must Be Numeric';
					} else if((document.frmEventSubmit.endTimeHour.value > 12) || (document.frmEventSubmit.endTimeHour.value < 1)) {
						dirty = 1;
						warn = warn + '\n*End Hour Must Be Between 1 and 12';
					}//end if
					
					if(isNaN(document.frmEventSubmit.endTimeMins.value) == true){
						dirty = 1;
						warn = warn + '\n*End Minute Must Be Numeric';
					} else if((document.frmEventSubmit.endTimeMins.value > 59) || (document.frmEventSubmit.endTimeMins.value < 0)) {
						dirty = 1;
						warn = warn + '\n*End Minute Must Be Between 0 and 59';
					}//end if
					
					if(document.frmEventSubmit.recurCheck.checked){
						if(!isDate(document.frmEventSubmit.recurEndDate.value, '<?echo $hc_popDateValid;?>')){
							dirty = 1;
							warn = warn + '\n*Recur End Date is Invalid Date or Format. Required Format: <?echo strtolower($hc_popDateFormat);?>';
						} else if(document.frmEventSubmit.recurEndDate.value == ''){
							dirty = 1;
							warn = warn + '\n*Event Recur End Date is Required';
						} else if(compareDates(document.frmEventSubmit.eventDate.value, '<?echo $hc_popDateValid;?>', document.frmEventSubmit.recurEndDate.value, '<?echo $hc_popDateValid;?>') == 1){
							dirty = 1;
							warn = warn + "\n*Event Date Cannot Occur After End Recur Date";
						} else if(document.frmEventSubmit.eventDate.value == document.frmEventSubmit.recurEndDate.value){
							dirty = 1;
							warn = warn + "\n*Event Recur End Date Cannot Be Event Date";
						}//end if
					}//end if
					
					if(document.frmEventSubmit.submitName.value == ''){
						dirty = 1;
						warn = warn + '\n*Your Name is Required';
					}//end if
					
					if(document.frmEventSubmit.submitEmail.value == ''){
						dirty = 1;
						warn = warn + '\n*Your Email is Required';
					} else {
						if(chkEmail(document.frmEventSubmit.submitEmail) == 0){
							dirty = 1;
							warn = warn + '\n*Your Email Format is Invalid';
						}//end if
					}//end if
					
					if(document.frmEventSubmit.eventTitle.value == ''){
						dirty = 1;
						warn = warn + '\n*Event Title is Required';
					}//end if
					
					if(document.frmEventSubmit.locName.value == ''){
						dirty = 1;
						warn = warn + '\n*Location Name is Required';
					}//end if
					
					if(compareDates(document.frmEventSubmit.eventDate.value, '<?echo $hc_popDateValid;?>', '<?echo date($hc_popDateFormat, mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', '<?echo $hc_popDateValid;?>') == 0){
						dirty = 1;
						warn = warn + "\n*Event Date Cannot Occur Before Today";
					}//end if
					
					if(document.frmEventSubmit.contactEmail.value != '' && chkEmail(document.frmEventSubmit.contactEmail) == 0){
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
			//-->
			</script>
			<br />
			To submit an event to the <?echo CalName?> please fill out the following form.<br /><br />
			
			(<span style="color: #DC143C;">*</span>) = Required Fields<br />
			(<span style="color: #0000FF;">*</span>) = Required for Dynamic Directions Link<br />
			(<span style="color: #008000;">*</span>) = Required for Events <b>With Registration</b>
			<br /><br />
			<form id="frmEventSubmit" name="frmEventSubmit" method="post" action="<?echo HC_FormSubmitAction;?>" onsubmit="return chkFrm();">
			<input name="dateFormat" id="dateFormat" type="hidden" value="<?echo strtolower($hc_popDateFormat);?>">
			<fieldset>
				<legend>Your Contact Information (Administrative Use Only)</legend>
				<div class="frmReq">
					<label for="submitName">Name:</label>
					<input name="submitName" id="submitName" type="text" size="25" maxlength="50" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
				<div class="frmReq">
					<label for="submitEmail">Email Address:</label>
					<input name="submitEmail" id="submitEmail" type="text" size="35" maxlength="75" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Description &amp; Date Information</legend>
				<div class="frmReq">
					<label for="eventTitle">Title:</label>
					<input name="eventTitle" id="eventTitle" type="text" size="40" maxlength="150" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label for="eventDescription">Description:</label>
					<?makeTinyMCE("eventDescription");?>
			    </div>
				<div class="frmReq">
					<label for="eventDate">Event Date:</label>
					<input name="eventDate" id="eventDate" type="text" value="<?echo date($hc_popDateFormat);?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSubmit.eventDate,'anchor1','<?echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label>Start Time:</label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?echo date("h");?>" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeHour,1,12)"><img src="<?echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeHour,-1,12)"><img src="<?echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeMins,1,59)"><img src="<?echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeMins,-1,59)"><img src="<?echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
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
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeHour,1,12)"><img src="<?echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeHour,-1,12)"><img src="<?echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="endTimeMins" id="endTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeMins,1,59)"><img src="<?echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeMins,-1,59)"><img src="<?echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td>
								<select name="endTimeAMPM" id="endTimeAMPM">
									<option <?if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "AM"){?>selected="selected"<?}?> value="AM">AM</option>
									<option <?if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "PM"){?>selected="selected"<?}?> value="PM">PM</option>
								</select>
							</td>
							<td><label for="ignoreendtime">No End Time:</label></td>
							<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" /></td>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="overridetime">Override&nbsp;Times:</label><input type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
					<br />
					<label>&nbsp;</label>
					<label for="specialtimeall"><input disabled="disabled" type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" />All&nbsp;Day&nbsp;Event</label>
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
								<label for="recurType1" class="radio"><input name="recurType" id="recurType1" type="radio" value="daily" disabled="disabled" checked="checked" onclick="showPanel('daily')" class="noBorderIE" />Daily</label>
								<br />
								<label for="recurType2" class="radio"><input name="recurType" id="recurType2" type="radio" value="weekly" disabled="disabled" onclick="showPanel('weekly')" class="noBorderIE" />Weekly</label>
								<br />
								<label for="recurType3" class="radio"><input name="recurType" id="recurType3" type="radio" value="monthly" disabled="disabled" onclick="showPanel('monthly')" class="noBorderIE" />Monthly</label>
							</td>
							<td style="vertical-align: top;">
								<div class="recPanel" id="daily">
									<input name="dailyOptions" id="recDaily1" type="radio" checked="checked" disabled="disabled" value="EveryXDays" class="noBorderIE" />Every&nbsp;<input id="dailyDays" name="dailyDays" type="text" maxlength="2" size="2" value="1" disabled="disabled" />&nbsp;day(s)<br />
									<label for="recDaily2" class="radio"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" class="noBorderIE" />All Weekdays</label>
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
					<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSubmit.recurEndDate,'anchor2','<?echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
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
						include('includes/' . HC_StateInclude);
					?><span style="color: #0000FF;">*</span>
				</div>
				<div class="frmOpt">
					<label for="locZip">Postal Code:</label>
					<input name="locZip" id="locZip" value="" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Contact Info (Displayed On Calendar)</legend>
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
					<input name="contactURL" id="contactURL" type="text" maxlength="100" size="30" value="http://" />
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Message for Administrator (Not Displayed on Calendar)</legend>
				<div class="frmOpt">
					<label for="goadminmessage">Include Message:</label>
					<input name="goadminmessage" id="goadminmessage" type="checkbox" value="" onclick="togAdminMsg();" class="noBorderIE" />
				</div>
				<div class="frmOpt">
					<label for="adminmessage">Message:</label>
					<textarea name="adminmessage" id="adminmessage" rows="7" cols="50" disabled="disabled" convert_this="false"></textarea>
				</div>
				
				<label>&nbsp;</label>
				<input name="submit" id="submit" type="submit" value="Submit Event" />
			</fieldset>
			</form>
	<?
		} else {
		?>
			<br />
			<?
				feedback(1, "Your event was submitted successfully!");
			?>
			Due to the large volume of events submitted not all receive a response from the adminstrator. 
			However, your event will be processed shortly and should the administrator decide, you will
			receive an update on the status of your event(s).<br /><br />
			<a href="<?echo CalRoot;?>/index.php?com=submit" class="eventMain">Click here to submit another event.</a><br />
			<a href="<?echo CalRoot;?>/" class="eventMain">Click here to browse events.</a>
		<?
		}//end if
	} else {?>
		<br />
		Public event submission has been disabled by the administrator.<br /><br />
		<a href="<?echo CalRoot;?>/" class="eventMain">Click here to return to the <?echo CalName;?>.</a>
	<?
	}//end if
?>