<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 1");
	$doSubmit = mysql_result($result,0,0);
		
	if($doSubmit == 1){
		if(!isset($_GET['msg'])){
	?>
			<script language="JavaScript">
			var isNS = (document.layers) ? true : false
			var isIE5up = (document.getElementById) ? true : false
			var gRecurX = 0;
			var gRecurY = 0;
			var gShowFirst = "daily";
			
			window.onload = init
			function init(){
				if(isNS){
					gRecurX = document.images["recurSpacer"].x;
					gRecurY = document.images["recurSpacer"].y;
					gObj = document.layers[gShowFirst];
				
				} else {
					gRecurX = getActualLeft(document.images["recurSpacer"]);
					gRecurY = getActualTop(document.images["recurSpacer"]);
					gObj = (isIE5up) ? document.getElementById(gShowFirst).style : document.all(gShowFirst).style
				}//end if
				gObj.top = gRecurY;
				gObj.left = gRecurX;
				gObj.visibility = "visible";
			
				
			}//end startUp()
			
			function getActualTop(el){
			    yPos = el.offsetTop;
			    tempEl = el.offsetParent;
			    while (tempEl != null) {
			        yPos += tempEl.offsetTop;
			        tempEl = tempEl.offsetParent;
			    }//end while
			    return yPos;
			}//end getActualTop()
			
			function getActualLeft(el){
			    xPos = el.offsetLeft;
			    tempEl = el.offsetParent;
			    while (tempEl != null) {
			        xPos += tempEl.offsetLeft;
			        tempEl = tempEl.offsetParent;
			    }//end while
			    return xPos;
			}//end getActualLeft()
			
			function showLayer(str){
				var obj = (isNS) ? document.layers[str] : (isIE5up) ? document.getElementById(str).style : document.all(str).style
				gObj.visibility = "hidden";
				gObj = obj;
				gObj.top = gRecurY;
				gObj.left = gRecurX;
				gObj.visibility = "visible";
			}//end showLayer()
					
			function chkDate(obj){
				var re = /^(\d{1,2})[\/|-](\d{1,2})[\/|-](\d{2}|\d{4})$/
				if(obj.value != ''){
					if(!re.test(obj.value)) {
						return 1;
					} else {
						return 0;
					}//end if
				}//end if
			}//end chkDate()
			
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
			
			
			function chkFrm(){
			dirty = 0;
			warn = "Event could not be added for the following reason(s):";
			
				if(document.eventSubmit.eventRegistration.value == 1){
					if(isNaN(document.eventSubmit.eventRegAvailable.value) == true){
						dirty = 1;
						warn = warn + '\n*Registration Limit Value Must Be Numeric';
					}//end if
					
					if(document.eventSubmit.contactName.value == ''){
						dirty = 1;
						warn = warn + '\n*Registration Requires Contact Name';
					}//end if
					
					if(document.eventSubmit.contactEmail.value == ''){
						dirty = 1;
						warn = warn + '\n*Registration Requires Contact Email Address';
					}//end if
				}//end if
						
				if(chkDate(document.eventSubmit.eventDate) == 1){
					dirty = 1;
					warn = warn + '\n*Event Date Format is Invalid.';
				}//end if 
				
				if(isNaN(document.eventSubmit.startTimeHour.value) == true){
					dirty = 1;
					warn = warn + '\n*Start Hour Must Be Numeric';
				} else if((document.eventSubmit.startTimeHour.value > 12) || (document.eventSubmit.startTimeHour.value < 1)) {
					dirty = 1;
					warn = warn + '\n*Start Hour Must Be Between 1 and 12';
				}//end if
				
				if(isNaN(document.eventSubmit.startTimeMins.value) == true){
					dirty = 1;
					warn = warn + '\n*Start Minute Must Be Numeric';
				} else if((document.eventSubmit.startTimeMins.value > 59) || (document.eventSubmit.startTimeMins.value < 0)) {
					dirty = 1;
					warn = warn + '\n*Start Minute Must Be Between 0 and 59';
				}//end if
				
				if(isNaN(document.eventSubmit.endTimeHour.value) == true){
					dirty = 1;
					warn = warn + '\n*End Hour Must Be Numeric';
				} else if((document.eventSubmit.endTimeHour.value > 12) || (document.eventSubmit.endTimeHour.value < 1)) {
					dirty = 1;
					warn = warn + '\n*End Hour Must Be Between 1 and 12';
				}//end if
				
				if(isNaN(document.eventSubmit.endTimeMins.value) == true){
					dirty = 1;
					warn = warn + '\n*End Minute Must Be Numeric';
				} else if((document.eventSubmit.endTimeMins.value > 59) || (document.eventSubmit.endTimeMins.value < 0)) {
					dirty = 1;
					warn = warn + '\n*End Minute Must Be Between 0 and 59';
				}//end if
				
				if(document.eventSubmit.recurCheck.checked){
					if(chkDate(document.eventSubmit.recurEndDate) == 1){
						dirty = 1;
						warn = warn + '\n*Recur End Date Format is Invalid';
					}//end if
				}//end if
				
				if(document.eventSubmit.submitName.value == ''){
					dirty = 1;
					warn = warn + '\n*Your Name is Required';
				}//end if
				
				if(document.eventSubmit.submitEmail.value == ''){
					dirty = 1;
					warn = warn + '\n*Your Email is Required';
				} else {
					if(chkEmail(document.eventSubmit.submitEmail) == 0){
						dirty = 1;
						warn = warn + '\n*Your Email Format is Invalid';
					}//end if
				}//end if
				
				if(document.eventSubmit.eventTitle.value == ''){
					dirty = 1;
					warn = warn + '\n*Event Title is Required';
				}//end if
				
				if(document.eventSubmit.eventDate.value == ''){
					dirty = 1;
					warn = warn + '\n*Event Date is Required';
				}//end if
				
				if(document.eventSubmit.locName.value == ''){
					dirty = 1;
					warn = warn + '\n*Location Name is Required';
				}//end if
				
				if(document.eventSubmit.recurCheck.checked){
					if(document.eventSubmit.recurEndDate.value == ''){
						dirty = 1;
						warn = warn + '\n*Event Recur End Date is Required';
					}//end if
				}//end if
				
				if(document.eventSubmit.recurCheck.checked == true){
					if(compareDates(document.eventSubmit.eventDate.value, 'MM/d/yyyy', document.eventSubmit.recurEndDate.value, 'MM/d/yyyy') == 1){
						dirty = 1;
						warn = warn + "\n          *Event Date Cannot Occur After End Recur Date";
					}//end if
				}//end if
				
				if(compareDates(document.eventSubmit.eventDate.value, 'MM/d/yyyy', '<?echo date("m/d/Y", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")) );?>', 'MM/d/yyyy') == 0){
					dirty = 1;
					warn = warn + "\n          *Event Date Cannot Occur Before Today";
				}//end if
				
				if(document.eventSubmit.contactEmail.value != '' && chkEmail(document.eventSubmit.contactEmail) == 0){
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
			
			function chkEmail(obj){
				var x = obj.value;
				var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (filter.test(x)) {
					return 1;
				} else {
					return 0;
				}//end if
			}//end checkMail(object)
			
			function togOverride(){
				if(document.eventSubmit.overridetime.checked){
					document.eventSubmit.specialtimeall.disabled = false;
					document.eventSubmit.startTimeHour.disabled = true;
					document.eventSubmit.startTimeMins.disabled = true;
					document.eventSubmit.startTimeAMPM.disabled = true;
					document.eventSubmit.endTimeHour.disabled = true;
					document.eventSubmit.endTimeMins.disabled = true;
					document.eventSubmit.endTimeAMPM.disabled = true;
					document.eventSubmit.ignoreendtime.disabled = true;
				} else {
					document.eventSubmit.specialtimeall.disabled = true;
					document.eventSubmit.startTimeHour.disabled = false;
					document.eventSubmit.startTimeMins.disabled = false;
					document.eventSubmit.startTimeAMPM.disabled = false;
					if(document.eventSubmit.ignoreendtime.checked == false){
						document.eventSubmit.endTimeHour.disabled = false;
						document.eventSubmit.endTimeMins.disabled = false;
						document.eventSubmit.endTimeAMPM.disabled = false;
					}//end if
					document.eventSubmit.ignoreendtime.disabled = false;
				}//end if
			}//end togOverride()
			
			function togEndTime(){
				if(document.eventSubmit.ignoreendtime.checked){
					document.eventSubmit.endTimeHour.disabled = true;
					document.eventSubmit.endTimeMins.disabled = true;
					document.eventSubmit.endTimeAMPM.disabled = true;
				} else {
					document.eventSubmit.endTimeHour.disabled = false;
					document.eventSubmit.endTimeMins.disabled = false;
					document.eventSubmit.endTimeAMPM.disabled = false;
				}//end if
			}//end togEndTime()
			
			function togRecur(){
				if(document.eventSubmit.recurCheck.checked){
					document.eventSubmit.recurType1.disabled = false;
					document.eventSubmit.recurType2.disabled = false;
					document.eventSubmit.recurType3.disabled = false;
					document.eventSubmit.recWeeklyDay1.disabled = false;
					document.eventSubmit.recWeeklyDay2.disabled = false;
					document.eventSubmit.recWeeklyDay3.disabled = false;
					document.eventSubmit.recWeeklyDay4.disabled = false;
					document.eventSubmit.recWeeklyDay5.disabled = false;
					document.eventSubmit.recWeeklyDay6.disabled = false;
					document.eventSubmit.recWeeklyDay7.disabled = false;
					document.eventSubmit.recDaily1.disabled = false;
					document.eventSubmit.recDaily2.disabled = false;
					document.eventSubmit.dailyDays.disabled = false;
					document.eventSubmit.recWeekly.disabled = false;
					document.eventSubmit.monthlyOption.disabled = false;
					document.eventSubmit.monthlyDays.disabled = false;
					document.eventSubmit.recurEndDate.disabled = false;
					document.eventSubmit.monthlyMonths.disabled = false;
				} else {
					document.eventSubmit.recurType1.disabled = true;
					document.eventSubmit.recurType2.disabled = true;
					document.eventSubmit.recurType3.disabled = true;
					document.eventSubmit.recWeeklyDay1.disabled = true;
					document.eventSubmit.recWeeklyDay2.disabled = true;
					document.eventSubmit.recWeeklyDay3.disabled = true;
					document.eventSubmit.recWeeklyDay4.disabled = true;
					document.eventSubmit.recWeeklyDay5.disabled = true;
					document.eventSubmit.recWeeklyDay6.disabled = true;
					document.eventSubmit.recWeeklyDay7.disabled = true;
					document.eventSubmit.recDaily1.disabled = true;
					document.eventSubmit.recDaily2.disabled = true;
					document.eventSubmit.dailyDays.disabled = true;
					document.eventSubmit.recWeekly.disabled = true;
					document.eventSubmit.monthlyOption.disabled = true;
					document.eventSubmit.monthlyDays.disabled = true;
					document.eventSubmit.recurEndDate.disabled = true;
					document.eventSubmit.monthlyMonths.disabled = true;
				}//end if
			}//end togRecur()
			
			function togRegistration(){
				if(document.eventSubmit.eventRegistration.value == 0){
					document.eventSubmit.eventRegAvailable.disabled = true;
				} else {
					document.eventSubmit.eventRegAvailable.disabled = false;
				}//end if
			}//end togRegistration()
			
			function togRegistration(){
				if(document.eventSubmit.eventRegistration.value == 0){
					document.eventSubmit.eventRegAvailable.disabled = true;
				} else {
					document.eventSubmit.eventRegAvailable.disabled = false;
				}//end if
			}//end togRegistration()
			
			function togAdminMsg(){
				if(document.eventSubmit.goadminmessage.checked){
					document.eventSubmit.adminmessage.disabled = false;
				} else {
					document.eventSubmit.adminmessage.disabled = true;
				}//end if
			}//end togRegistration()
			
			var calx = new CalendarPopup();
			</script>
			
			To submit an event to the <?echo CalName?> please fill out the following form.<br><br>
			
			(<span class="eventReqTag">*</span>) = Required Fields<br>
			(<span style="color: blue;">*</span>) = Optional Fields, but required for Mapquest powered directions<br>
			(<span style="color: green;">*</span>) = Required for events <b>with registration</b>
			<br><br>
			<form id="eventSubmit" name="eventSubmit" method="post" action="<?echo HC_FormSubmitAction;?>" onSubmit="return chkFrm();">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="2" class="eventMain"><b>Your Contact Information (Administrative Use Only)</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Name:</td>
					<td class="eventMain">
						<input size="25" maxlength="50" type="text" name="submitName" id="submitName" value="" class="eventInput"><span class="eventReqTag"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Email:</td>
					<td class="eventMain">
						<input size="35" maxlength="75" type="text" name="submitEmail" id="submitEmail" value="" class="eventInput"><span class="eventReqTag"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain><b>Event Description &amp; Date Information</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Title:</td>
					<td class="eventMain">
						<input size="40" maxlength="150" type="text" name="eventTitle" id="eventTitle" value="" class="eventInput"><span class="eventReqTag"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80" valign="top">Description:</td>
					<td class="eventMain"><?makeTinyMCE("eventDescription", "100%");?></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain" width="80">Event Date:</td>
								<td class="eventMain">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><input size="10" maxlength="10" type="text" name="eventDate" id="eventDate" value="<?echo date("m/d/Y");?>" class="eventInput"></td>
											<td class="eventMain"><span class="eventReqTag"> *</span></td>
											<td>&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSubmit.eventDate,'anchor1','MM/dd/yyyy'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a>&nbsp;</td>
										</tr>
									</table>
								</td>
								<td width="20"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td>
							</tr>
							<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
							<tr>
								<td class="eventMain" width="80">Start Time:</td>
								<td class="eventMain">
									<table cellpadding="1" cellspacing="0" border="0">
										<tr>
											<td class="eventMain"><input size="2" maxlength="2" type="text" name="startTimeHour" id="startTimeHour" value="<?echo date("h");?>" class="eventInput"></td>
											<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
											<td class="eventMain"><input size="2" maxlength="2" type="text" name="startTimeMins" id="startTimeMins" value="00" class="eventInput"></td>
											<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
											<td class="eventMain">
												<select name="startTimeAMPM" id="startTimeAMPM" class="eventInput">
													<option <?if(date("A") == 'AM'){echo "SELECTED";}?> value="AM">AM</option>
													<option <?if(date("A") == 'PM'){echo "SELECTED";}?> value="PM">PM</option>
												</select>
											</td>
											<td><span class="eventReqTag"> *</span></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td class="eventMain" width="70">End Time:</td>
								<td class="eventMain">
									<table cellpadding="1" cellspacing="0" border="0">
										<tr>
											<td class="eventMain"><input size="2" maxlength="2" type="text" name="endTimeHour" id="endTimeHour" value="<?echo date("h", mktime(date("h") + 1, 0, 0, 1, 1, 1971));?>" class="eventInput"></td>
											<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
											<td class="eventMain"><input size="2" maxlength="2" type="text" name="endTimeMins" id="endTimeMins" value="00" class="eventInput"></td>
											<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
											<td class="eventMain">
												<select name="endTimeAMPM" id="endTimeAMPM" class="eventInput">
													<option <?if(date("A") == 'AM'){echo "SELECTED";}?> value="AM">AM</option>
													<option <?if(date("A") == 'PM'){echo "SELECTED";}?> value="PM">PM</option>
												</select>
											</td>
											<td><span class="eventReqTag"> *</span></td>
											<td>
												<td width="10">&nbsp;</td>
												<td><input type="checkbox" name="ignoreendtime" id="ignoreendtime" input="eventInput" onClick="togEndTime();"></td>
												<td class="eventMain"><label for="ignoreendtime">No End Time</label></td>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
							<tr>
								<td>&nbsp;</td>
								<td>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td><input type="checkbox" name="overridetime" id="overridetime" input="eventInput" onClick="togOverride();"></td>
											<td class="eventMain"><label for="overridetime">Override Event Times</label>&nbsp;&nbsp;(<i>Time Values Above Ignored When Checked</i>)</td>
										</tr>
										<tr>
											<td colspan="2">
												<table cellpadding="0" cellspacing="0" border="0">
													<tr>
														<td rowspan="2" width="20">&nbsp;</td>
														<td><input DISABLED type="radio" name="specialtime" id="specialtimeall" value="allday" CHECKED></td>
														<td class="eventMain"><label for="specialtimeall">All Day Event</label></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Event Registration</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Registration:</td>
					<td>
						<select name="eventRegistration" id="eventRegistration" onChange="togRegistration();" class="eventInput">
							<option value="0">Do Not Allow Registration</option>
							<option value="1">Allow Registration</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Limit:&nbsp;</td>
					<td class="eventMain">
						<input DISABLED size="4" maxlength="4" type="text" name="eventRegAvailable" id="eventRegAvailable" value="0" class="eventInput">
						(0 = unlimited)
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Event Recurrance Information</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td class="eventMain">
					
						
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><input type="checkbox" name="recurCheck" id="recurCheck" onClick="togRecur();"></td>
								<td class="eventMain"><label for="recurCheck">Check if this is a recurring event.</label></td>
							</tr>
							<tr>
								<td colspan="2">
								<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td valign="top">
												<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"><br>
													<table cellpadding="0" cellspacing="0" border="0">
														<tr>
															<td width="15" rowspan="5">&nbsp;</td>
															<td><input DISABLED checked type="radio" name="recurType" id="recurType1" value="daily" onClick="showLayer('daily')"></td>
															<td class="eventMain"><label for="recurType1">Daily</label></td>
														</tr>
														<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
														<tr>
															<td><input DISABLED type="radio" name="recurType" id="recurType2" value="weekly" onClick="showLayer('weekly')"></td>
															<td class="eventMain"><label for="recurType2">Weekly</label></td>
														</tr>
														<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
														<tr>
															<td><input DISABLED type="radio" name="recurType" id="recurType3" value="monthly" onClick="showLayer('monthly')"></td>
															<td class="eventMain"><label for="recurType3">Monthly</label></td>
														</tr>
													</table>
													
												</td>
												<td width="5">&nbsp;</td>
												<td valign="top">
													<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0" name="recurSpacer"><br></td>
											</tr>
										</table>
										<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td class="eventMain">Event recurrs until:&nbsp;</td>
												<td>
													<input DISABLED size="10" maxlength="10" type="text" id="recurEndDate" name="recurEndDate" value="" class="eventInput">
												</td>
												<td>&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSubmit.recurEndDate,'anchor2','MM/dd/yyyy'); return false;" name="anchor2" id="anchor2"><img src="<?echo CalAdminRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a></td>
											</tr>
										</table>
								</td>
							</tr>
						</table>
					
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Location Info</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Name:</td>
					<td class="eventMain">
						<input maxlength="50" size="20" type="text" name="locName" id="locName" value="" class="eventInput"><span class="eventReqTag"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Address:</td>
					<td class="eventMain">
						<input maxlength="75" size="25" type="text" name="locAddress" id="locAddress" value="" class="eventInput"><span style="color: blue;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80"></td>
					<td class="eventMain">
						<input maxlength="75" size="25" type="text" name="locAddress2" id="locAddress2" value="" class="eventInput">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">City:</td>
					<td class="eventMain">
						<input maxlength="50" size="15" type="text" name="locCity" id="locCity" value="" class="eventInput"><span style="color: blue;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">State:</td>
					<td class="eventMain">
						<?
							$state = $defaultState;
							include('includes/selectStates.php');
						?><span style="color: blue;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Zip Code:</td>
					<td class="eventMain">
						<input maxlength="11" size="11" type="text" name="locZip" id="locZip" value="" class="eventInput"><span style="color: blue;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Event Contact Info (Displayed On Calendar)</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Name:</td>
					<td class="eventMain">
						<input maxlength="50" size="20" type="text" name="contactName" id="contactName" value="" class="eventInput"><span style="color: green;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Email:</td>
					<td class="eventMain">
						<input maxlength="75" size="30" type="text" name="contactEmail" id="contactEmail" value="" class="eventInput"><span style="color: green;"> *</span>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Phone:</td>
					<td class="eventMain">
						<input maxlength="15" size="15" type="text" name="contactPhone" id="contactPhone" value="" class="eventInput">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="80">Website:</td>
					<td class="eventMain">
						<input maxlength="100" size="30" type="text" name="contactURL" id="contactURL" value="http://" class="eventInput">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Message for Administrator (Not Displayed on Calendar)</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain">
									<input type="checkbox" name="goadminmessage" id="goadminmessage" value="" onClick="togAdminMsg();">
								</td>
								<td class="eventMain">
									<label for="goadminmessage">Include Message to Calendar Administrator</label>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td class="eventMain">
						<textarea convert_this="false" DISABLED style="width: 100%; height: 150px" name="adminmessage" id="adminmessage" class="eventInput"></textarea>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;</td>
					<td class="eventMain">
						<input type="submit" name="submit" value=" Submit Event " class="eventButton">
					</td>
				</tr>
			</table>
			
			
			
			<div id="daily" style="position: absolute; top: 525px; left: 412px; visibility: visible;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td valign="top" class="eventSubmit"><input type="radio" name="dailyOptions" id="recDaily1" checked="checked" value="EveryXDays"></td>
					<td class="eventMain">Every</td>
					<td class="eventMain">&nbsp;<input type="text" maxlength="2" size="2" value="1" name="dailyDays" id="dailyDays" class="eventInput">&nbsp;</td>
					<td class="eventMain">day(s)</td>
				</tr>
				<tr>
					<td class="eventMain"><input type="radio" name="dailyOptions" id="recDaily2" value="WeekdaysOnly"></td>
					<td class="eventMain" colspan="3"><label for="recDaily2">All Weekdays</label></td>
				</tr>
			</table>
			</div>
			
			<div id="weekly" style="position: absolute; top: 0pt; left: 0pt; visibility: hidden;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td colspan="8" class="eventMain">Every <input maxlength="2" size="2" value="1" name="recWeekly" class="eventInput"> week(s) on:</td>
			
				</tr>
				<tr>
					<td class="eventMain"><input id="recWeeklyDay1" name="recWeeklyDay1" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay1">Sun</label></td>
					<td class="eventMain"><input id="recWeeklyDay2" name="recWeeklyDay2" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay2">Mon</label></td>
					<td class="eventMain"><input id="recWeeklyDay3" name="recWeeklyDay3" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay3">Tue</label></td>
					<td class="eventMain"><input id="recWeeklyDay4" name="recWeeklyDay4" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay4">Wed</label></td>
				</tr>
				<tr>
					<td class="eventMain"><input id="recWeeklyDay5" name="recWeeklyDay5" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay5">Thu</label></td>
					<td class="eventMain"><input id="recWeeklyDay6" name="recWeeklyDay6" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay6">Fri</label></td>
					<td class="eventMain"><input id="recWeeklyDay7" name="recWeeklyDay7" type="checkbox"></td>
					<td class="eventMain"><label for="recWeeklyDay7">Sat</label></td>
				</tr>
			</table>
			</div>
			
			<div id="monthly" style="position: absolute; top: 0pt; left: 0pt; visibility: hidden;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="eventMain"><input type="radio" checked="checked" value="Day" name="monthlyOption"></td>
					<td class="eventMain">Day</td>
					<td class="eventMain">&nbsp;<input maxlength="2" size="2" value="<?echo date("d");?>" name="monthlyDays" class="eventInput">&nbsp;</td>
					<td class="eventMain">of every</td>
					<td class="eventMain">&nbsp;<input maxlength="2" size="2" value="1" name="monthlyMonths" class="eventInput">&nbsp;</td>
					<td class="eventMain">month(s)</td>
				</tr>
			</table>
			</div>
			</form>
	<?
		} else {
		?>
			<br>
			<?
				feedback(1, "Your event was submitted successfully!");
			?>
			Due to the large volume of events submitted not all receive a response from the adminstrator. 
			However, your event will be processed shortly and should the administrator decide you will
			receive an update on the status of your event(s).<br><br>
			<a href="<?echo CalRoot;?>/index.php?com=submit" class="eventMain">Click here to submit another event.</a><br>
			<a href="<?echo CalRoot;?>/" class="eventMain">Click here to browse events.</a>
		<?
		}//end if
	} else {?>
		<br>
		Public event submission has been disabled by the administrator.<br><br>
		<a href="<?echo CalRoot;?>/" class="eventMain">Click here to return to the <?echo CalName;?>.</a>
	<?
	}//end if
?>