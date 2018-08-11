<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if($hc_pubSubmit == 1){
		if(!isset($_GET['msg'])){	
			$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND IsPublic = 1 ORDER BY Name");
			
			$hourOffset = date("G");
			if($hc_timezoneOffset > 0){
				$hourOffset = $hourOffset + abs($hc_timezoneOffset);
			} else {
				$hourOffset = $hourOffset - abs($hc_timezoneOffset);
			}//end if
			
			$hrFormat = "h";
			$minHr = 1;
			if($hc_timeInput == 23){
				$hrFormat = "H";
				$minHr = 0;
			}//end if
			
			$hasLoc = 0;
			if(hasRows($resultL)){
				$hasLoc = 1;
			}//end if	?>
			
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Dates.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
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
						if(val > maxVal) val = <?php echo $minHr;?>;
						if(val < <?php echo $minHr;?>) val = maxVal;
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
					document.frmEventSubmit.endTimeHour.disabled = true;
					document.frmEventSubmit.endTimeMins.disabled = true;
					document.frmEventSubmit.ignoreendtime.disabled = true;
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventSubmit.startTimeAMPM.disabled = true;
						document.frmEventSubmit.endTimeAMPM.disabled = true;
					}//end if
				} else {
					document.frmEventSubmit.specialtimeall.disabled = true;
					document.frmEventSubmit.startTimeHour.disabled = false;
					document.frmEventSubmit.startTimeMins.disabled = false;
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventSubmit.startTimeAMPM.disabled = false;
					}//end if
					if(document.frmEventSubmit.ignoreendtime.checked == false){
						document.frmEventSubmit.endTimeHour.disabled = false;
						document.frmEventSubmit.endTimeMins.disabled = false;
						if(<?php echo $hc_timeInput;?> == 12){
							document.frmEventSubmit.endTimeAMPM.disabled = false;
						}//end if
					}//end if
					document.frmEventSubmit.ignoreendtime.disabled = false;
				}//end if
			}//end togOverride()
			
			function togEndTime(){
				if(document.frmEventSubmit.ignoreendtime.checked){
					document.frmEventSubmit.endTimeHour.disabled = true;
					document.frmEventSubmit.endTimeMins.disabled = true;
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventSubmit.endTimeAMPM.disabled = true;
					}//end if
				} else {
					document.frmEventSubmit.endTimeHour.disabled = false;
					document.frmEventSubmit.endTimeMins.disabled = false;
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventSubmit.endTimeAMPM.disabled = false;
					}//end if
				}//end if
			}//end togEndTime()
			
			function togRecur(){
				if(document.frmEventSubmit.recurCheck.checked){
					document.frmEventSubmit.recurType1.disabled = false;
					document.frmEventSubmit.recurType2.disabled = false;
					document.frmEventSubmit.recurType3.disabled = false;
					document.frmEventSubmit.recWeeklyDay_0.disabled = false;
					document.frmEventSubmit.recWeeklyDay_1.disabled = false;
					document.frmEventSubmit.recWeeklyDay_2.disabled = false;
					document.frmEventSubmit.recWeeklyDay_3.disabled = false;
					document.frmEventSubmit.recWeeklyDay_4.disabled = false;
					document.frmEventSubmit.recWeeklyDay_5.disabled = false;
					document.frmEventSubmit.recWeeklyDay_6.disabled = false;
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
					document.frmEventSubmit.recWeeklyDay_0.disabled = true;
					document.frmEventSubmit.recWeeklyDay_1.disabled = true;
					document.frmEventSubmit.recWeeklyDay_2.disabled = true;
					document.frmEventSubmit.recWeeklyDay_3.disabled = true;
					document.frmEventSubmit.recWeeklyDay_4.disabled = true;
					document.frmEventSubmit.recWeeklyDay_5.disabled = true;
					document.frmEventSubmit.recWeeklyDay_6.disabled = true;
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
			function showPanel(who){
			  for(i = 0; i < recPanel.length; i++)
			  {
			    document.getElementById(recPanel[i]).style.display = (who == recPanel[i]) ? 'block':'none';
			  }
			  return false;
			}//end showPanel()
			
			function chkFrm(){
			dirty = 0;
			warn = "Event could not be added for the following reason(s):";

	<?php 	if(in_array(1, $hc_captchas)){	?>
				if(document.frmEventSubmit.proof.value == ''){
					dirty = 1;
					warn = warn + '\n*Authentication Text is Required';
				}//end if
	<?php 	}//end if	?>
			
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
				
				okDate = chkDate();
				if(okDate != ''){
					dirty = 1;
					warn = warn + okDate;
				}//end if
						
				if(isNaN(document.frmEventSubmit.startTimeHour.value) == true){
					dirty = 1;
					warn = warn + '\n*Start Hour Must Be Numeric';
				} else if((document.frmEventSubmit.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventSubmit.startTimeHour.value < <?php echo $minHr;?>)) {
					dirty = 1;
					warn = warn + '\n*Start Hour Must Be Between <?php echo $minHr;?> and <?php echo $hc_timeInput;?>';
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
				} else if((document.frmEventSubmit.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventSubmit.endTimeHour.value < <?php echo $minHr;?>)) {
					dirty = 1;
					warn = warn + '\n*End Hour Must Be Between <?php echo $minHr;?> and <?php echo $hc_timeInput;?>';
				}//end if
				
				if(isNaN(document.frmEventSubmit.endTimeMins.value) == true){
					dirty = 1;
					warn = warn + '\n*End Minute Must Be Numeric';
				} else if((document.frmEventSubmit.endTimeMins.value > 59) || (document.frmEventSubmit.endTimeMins.value < 0)) {
					dirty = 1;
					warn = warn + '\n*End Minute Must Be Between 0 and 59';
				}//end if
				
				if(document.frmEventSubmit.recurCheck.checked){
					myRecur = chkRecur();
					if(myRecur != ''){
						dirty = 1;
						warn = warn + myRecur;
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
				
		<?php 	if($hasLoc > 0){	?>
				if(document.frmEventSubmit.locPreset.value == 0){
					if(document.frmEventSubmit.locName.value == ''){
						dirty = 1;
						warn = warn + '\n*Location Name is Required';
					}//end if
				}//end if
		<?php 	} else {	?>
				if(document.frmEventSubmit.locName.value == ''){
						dirty = 1;
						warn = warn + '\n*Location Name is Required';
					}//end if
		<?php 	}//end if	?>
				
				if(compareDates(document.frmEventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>', '<?php echo date($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_popDateValid;?>') == 0){
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
				
			function togLocation(){
				if(document.frmEventSubmit.locPreset.value == 0){
					document.frmEventSubmit.locName.disabled = false;
					document.frmEventSubmit.locAddress.disabled = false;
					document.frmEventSubmit.locAddress2.disabled = false;
					document.frmEventSubmit.locCity.disabled = false;
					document.frmEventSubmit.locState.disabled = false;
					document.frmEventSubmit.locZip.disabled = false;
					document.frmEventSubmit.locCountry.disabled = false;
				} else {
					document.frmEventSubmit.locName.disabled = true;
					document.frmEventSubmit.locAddress.disabled = true;
					document.frmEventSubmit.locAddress2.disabled = true;
					document.frmEventSubmit.locCity.disabled = true;
					document.frmEventSubmit.locState.disabled = true;
					document.frmEventSubmit.locZip.disabled = true;
					document.frmEventSubmit.locCountry.disabled = true;
				}//end if
			}//end togLocation()
			
			function chkDate(){
				var err = '';
				 if(document.frmEventSubmit.eventDate.value == ''){
					err = '\n*Event Date is Required';
				} else if(!isDate(document.frmEventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>')){
					err = '\n*Event Date is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
				}//end if
				return err;
			}//end chkDate
			
			function chkRecur(){
				var err = '';
				if(document.frmEventSubmit.recurEndDate.value == ''){
					err = err + '\n*Event Recur End Date is Required';
				} else if(!isDate(document.frmEventSubmit.recurEndDate.value, '<?php echo $hc_popDateValid;?>')){
					err = err + '\n*Recur End Date is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
				} else if(compareDates(document.frmEventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>', document.frmEventSubmit.recurEndDate.value, '<?php echo $hc_popDateValid;?>') == 1){
					err = err + "\n*Event Date Cannot Occur After End Recur Date";
				} else if(document.frmEventSubmit.eventDate.value == document.frmEventSubmit.recurEndDate.value){
					err = err + "\n*Event Recur End Date Cannot Be Event Date";
				}//end if
				
				if(document.frmEventSubmit.recurType1.checked){
					if(document.frmEventSubmit.recDaily1.checked){
						if(isNaN(document.frmEventSubmit.dailyDays.value) == true){
							err = err + '\n*Daily Recurence Days Must Be Numeric';
						} else if(document.frmEventSubmit.dailyDays.value < 1) {
							err = err + '\n*Daily Recurence Days Must Be Greater Then 0';
						} else if(document.frmEventSubmit.dailyDays.value == '') {
							err = err + '\n*Daily Recurence Days Required';
						}//end if
					}//end if
				} else if(document.frmEventSubmit.recurType2.checked) {
					if(isNaN(document.frmEventSubmit.recWeekly.value) == true){
						err = err + '\n*Weekly Recurence Weeks Must Be Numeric';
					} else if(document.frmEventSubmit.recWeekly.value < 1) {
						err = err + '\n*Weekly Recurrence Weeks Must Be Greater Then 0';
					} else if(document.frmEventSubmit.recWeekly.value == '') {
						err = err + '\n*Weekly Recurrence Weeks Required';
					}//end if
					
					if(validateCheckArray('frmEventSubmit','recWeeklyDay[]',1) > 0){
						err = err + '\n*Weekly Recurrence Requires At Least 1 Day Selected';
					}//end if
				} else if(document.frmEventSubmit.recurType3.checked) {
					if(document.frmEventSubmit.monthlyOption1.checked){
						if(isNaN(document.frmEventSubmit.monthlyDays.value) == true){
							err = err + '\n*Monthly Recurence Day Must Be Numeric';
						} else if(document.frmEventSubmit.monthlyDays.value < 1) {
							err = err + '\n*Monthly Recurence Day Must Be Greater Then 0';
						} else if(document.frmEventSubmit.monthlyDays.value == '') {
							err = err + '\n*Monthly Recurence Day Required';
						}//end if
						
						if(isNaN(document.frmEventSubmit.monthlyMonths.value) == true){
							err = err + '\n*Monthly Recurence Month Must Be Numeric';
						} else if(document.frmEventSubmit.monthlyMonths.value < 1) {
							err = err + '\n*Monthly Recurence Month Must Be Greater Then 0';
						} else if(document.frmEventSubmit.monthlyMonths.value == '') {
							err = err + '\n*Monthly Recurence Month Required';
						}//end if
					} else if(document.frmEventSubmit.monthlyOption2.checked){
						if(isNaN(document.frmEventSubmit.monthlyMonthRepeat.value) == true){
							err = err + '\n*Monthly Recurence Month Must Be Numeric';
						} else if(document.frmEventSubmit.monthlyMonthRepeat.value < 1) {
							err = err + '\n*Monthly Recurence Month Must Be Greater Then 0';
						} else if(document.frmEventSubmit.monthlyMonthRepeat.value == '') {
							err = err + '\n*Monthly Recurence Month Required';
						}//end if
					}//end if
				}//end if
				return err;
			}//end chkRecur()
			
			function confirmRecurDates(){
				if(document.frmEventSubmit.recurCheck.checked){
					var warn = 'Cannot confirm dates for the following reason(s):';
					var dirty = 0;
					
					okDate = chkDate();
					if(okDate != ''){
						dirty = 1;
						warn = warn + okDate;
					}//end if
					
					myRecur = chkRecur();
					if(myRecur != ''){
						dirty = 1;
						warn = warn + myRecur;
					}//end if
					
					if(dirty > 0){
						alert(warn + '\n\nPlease complete the form and try again.');
					} else {
						var qStr = 'EventConfirmRecur.php';
						if(document.frmEventSubmit.recurType1.checked){
							qStr = qStr + '?recurType=daily';
							if(document.frmEventSubmit.recDaily1.checked){
								qStr = qStr + '&dailyOptions=EveryXDays&dailyDays=' + document.frmEventSubmit.dailyDays.value;
							} else {
								qStr = qStr + '&dailyOptions=WeekdaysOnly';
							}//end if
						} else if(document.frmEventSubmit.recurType2.checked) {
							var dArr = '';
							if(document.frmEventSubmit.recWeeklyDay_0.checked){dArr = dArr + ',0'}
							if(document.frmEventSubmit.recWeeklyDay_1.checked){dArr = dArr + ',1'}
							if(document.frmEventSubmit.recWeeklyDay_2.checked){dArr = dArr + ',2'}
							if(document.frmEventSubmit.recWeeklyDay_3.checked){dArr = dArr + ',3'}
							if(document.frmEventSubmit.recWeeklyDay_4.checked){dArr = dArr + ',4'}
							if(document.frmEventSubmit.recWeeklyDay_5.checked){dArr = dArr + ',5'}
							if(document.frmEventSubmit.recWeeklyDay_6.checked){dArr = dArr + ',6'}
							qStr = qStr + '?recurType=weekly&recWeekly=' + document.frmEventSubmit.recWeekly.value + '&recWeeklyDay=' + dArr.substring(1);
						} else if(document.frmEventSubmit.recurType3.checked) {
							qStr = qStr + '?recurType=monthly';
							if(document.frmEventSubmit.monthlyOption1.checked){
								qStr = qStr + '&monthlyOption=Day&monthlyDays=' + document.frmEventSubmit.monthlyDays.value + '&monthlyMonths=' + document.frmEventSubmit.monthlyMonths.value;
							} else {
								qStr = qStr + '&monthlyOption=Month&monthlyMonthOrder=' + document.frmEventSubmit.monthlyMonthOrder.value + '&monthlyMonthDOW=' + document.frmEventSubmit.monthlyMonthDOW.value + '&monthlyMonthRepeat=' + document.frmEventSubmit.monthlyMonthRepeat.value;
							}//end if
						}//end if
						
						qStr = qStr + '&dateFormat=<?php echo $hc_popDateFormat;?>&eventDate=' + document.frmEventSubmit.eventDate.value + '&recurEndDate=' + document.frmEventSubmit.recurEndDate.value;
						
						ajxOutput(qStr, 'recurDateChk', '<?php echo CalRoot;?>');
					}//end if
				} else {
					alert('To confirm dates you must enter event recurrence information.');
				}//end if
			}//end confirmRecurDates()
			
			var calx = new CalendarPopup("dsCal");
			document.write(calx.getStyles());
			//-->
			</script>
			<br />
			To submit an event to the <?php echo CalName?> please fill out the following form.<br /><br />
			
			(<span style="color: #DC143C;">*</span>) = Required Fields<br />
			(<span style="color: #0000FF;">*</span>) = Required for Dynamic Directions Link<br />
			(<span style="color: #008000;">*</span>) = Required for Events <b>With Registration</b>
			<br /><br />
			<form id="frmEventSubmit" name="frmEventSubmit" method="post" action="<?php echo HC_FormSubmitAction;?>" onsubmit="return chkFrm();">
			<input name="dateFormat" id="dateFormat" type="hidden" value="<?php echo strtolower($hc_popDateFormat);?>" />
			<input name="timeFormat" id="timeFormat" type="hidden" value="<?php echo $hc_timeInput;?>" />
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
	<?php 	if(in_array(1, $hc_captchas)){	?>
				<fieldset>
					<legend>Authentication</legend>
					Please enter the text you see in the following image in the textbox below it.<br />
					If you cannot read the text you may reload this page to generate a new image.<br /><br />
					<div class="frmOpt">
						<label>&nbsp;</label>
					<?php	buildCaptcha();?><br />
					</div>
					<div class="frmOpt">
						<label>&nbsp;</label>
						<input name="proof" id="proof" type="text" maxlength="8" size="8" value="" />
						<-- Enter Image Text Here
					</div>
				</fieldset>
				<br />
	<?php 	}//end if	?>
			<fieldset>
				<legend>Event Details</legend>
				<div class="frmReq">
					<label for="eventTitle">Title:</label>
					<input name="eventTitle" id="eventTitle" type="text" size="40" maxlength="150" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label for="eventDescription">Description:</label>
			<?php	makeTinyMCE("eventDescription", $hc_WYSIWYG, "375px");?>
			    </div>
				<div class="frmReq">
					<label for="eventDate">Event Date:</label>
					<input name="eventDate" id="eventDate" type="text" value="<?php echo date($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSubmit.eventDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span><br />
			    </div>
				<div class="frmOpt">
					<label>Start Time:</label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo date($hrFormat);?>" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeMins,1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.startTimeMins,-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="startTimeAMPM" id="startTimeAMPM">
									<option <?php if(date("A") == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
									<option <?php if(date("A") == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
								</select>
							</td>
					<?php 	}//end if	?>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label>End Time:</label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo date($hrFormat, mktime(date($hrFormat) + 1, 0, 0, 1, 1, 1971));?>" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="endTimeMins" id="endTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeMins,1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventSubmit.endTimeMins,-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="endTimeAMPM" id="endTimeAMPM">
									<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "AM"){?>selected="selected"<?php }?> value="AM">AM</option>
									<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "PM"){?>selected="selected"<?php }?> value="PM">PM</option>
								</select>
							</td>
					<?php 	}//end if	?>
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
				<br />
				<div class="frmOpt">
					<label for="cost">Cost:</label>
					<input name="cost" id="cost" type="text" value="" size="25" maxlength="50" />
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Event Recurrence Information</legend>
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
										<input id="recWeeklyDay_0" name="recWeeklyDay[]" type="checkbox" value="0" class="noBorderIE" />Sun
										<input id="recWeeklyDay_1" name="recWeeklyDay[]" type="checkbox" value="1" class="noBorderIE" />Mon
										<input id="recWeeklyDay_2" name="recWeeklyDay[]" type="checkbox" value="2" class="noBorderIE" />Tue
										<input id="recWeeklyDay_3" name="recWeeklyDay[]" type="checkbox" value="3" class="noBorderIE" />Wed<br />
										<input id="recWeeklyDay_4" name="recWeeklyDay[]" type="checkbox" value="4" class="noBorderIE" />Thu
										<input id="recWeeklyDay_5" name="recWeeklyDay[]" type="checkbox" value="5" class="noBorderIE" />Fri
										<input id="recWeeklyDay_6" name="recWeeklyDay[]" type="checkbox" value="6" class="noBorderIE" />Sat
								</div>
								
								<div class="recPanel" id="monthly" style="display: none;">
									<input name="monthlyOption" id="monthlyOption1" type="radio" checked="checked" value="Day" class="noBorderIE" />Day&nbsp;<input name="monthlyDays" id="monthlyDays" type="text" maxlength="2" size="2" value="<?php echo date("d");?>" />&nbsp;of every&nbsp;<input name="monthlyMonths" id="monthlyMonths" type="text" maxlength="2" size="2" value="1" />&nbsp;month(s)<br />
									<input name="monthlyOption" id="monthlyOption2" type="radio" value="Month" class="noBorderIE" />
									<select name="monthlyMonthOrder" id="monthlyMonthOrder">
										<option value="1">1st</option>
										<option value="2">2nd</option>
										<option value="3">3rd</option>
										<option value="4">4th</option>
										<option value="0">Last</option>
									</select>
									<select name="monthlyMonthDOW" id="monthlyMonthDOW">
										<option value="0">Sun</option>
										<option value="1">Mon</option>
										<option value="2">Tue</option>
										<option value="3">Wed</option>
										<option value="4">Thu</option>
										<option value="5">Fri</option>
										<option value="6">Sat</option>
									</select>
									of every <input name="monthlyMonthRepeat" id="monthlyMonthRepeat" type="text" maxlength="2" size="2" value="1" /> month(s)
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="frmOpt">
					<label>Recurs Until:</label>
					<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventSubmit.recurEndDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" /></a>
				</div>
				<br />
				<label>&nbsp;</label>
				<div id="recurDateChk">
					<a href="javascript:;" onclick="confirmRecurDates();" class="eventMain">Click Here to Confirm Dates</a>
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
	<?php 	if(isset($hc_pubCat) && $hc_pubCat == 1){	?>
			<fieldset>
				<legend>Event Categories</legend>
				<div class="frmOpt">
					<label>Categories:</label>
			<?php	getCategories('frmEventSubmit', 2);?>
				</div>
			</fieldset>
			<br />
	<?php 	}//end if	?>
			<fieldset>
				<legend>Location Information</legend>
		<?php 	if($hasLoc > 0){	?>
				<div class="frmReq">
					<label for="locPreset">Preset:</label>
					<select name="locPreset" id="locPreset" onchange="togLocation();">
						<option value="0">Custom Location (Enter Location Below)</option>
				<?php 	while($row = mysql_fetch_row($resultL)){	?>
						<option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
				<?php 	}//end while	?>
					</select>
				</div>
		<?php 	} else {	?>
				<input type="hidden" name="locPreset" value="0" />
		<?php 	}//end if	?>
				<div class="frmReq">
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
					<label for="locState"><?php echo HC_StateLabel;?>:</label>
					<?php
						$state = $hc_defaultState;
						include('includes/' . HC_StateInclude);
					?><span style="color: #0000FF;">*</span>
				</div>
				<div class="frmOpt">
					<label for="locZip">Postal Code:</label>
					<input name="locZip" id="locZip" value="" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locCountry">Country:</label>
					<input name="locCountry" id="locCountry" value="" type="text" maxlength="50" size="5" />
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
					<input name="contactPhone" id="contactPhone" type="text" maxlength="25" size="20" value="" />
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
			</fieldset>
			<br />
			<input name="submit" id="submit" type="submit" value="Submit Event" class="button" />
			</form>
<?php 	} else {	?>
			<br />
	<?php 	feedback(1, "Your event was submitted successfully!");	?>
			Due to the large volume of events submitted not all receive a response from the adminstrator. 
			However, your event will be processed shortly and should the administrator decide, you will
			receive an update on the status of your event(s).<br /><br />
			<a href="<?php echo CalRoot;?>/index.php?com=submit" class="eventMain">Click here to submit another event.</a><br />
			<a href="<?php echo CalRoot;?>/" class="eventMain">Click here to browse events.</a>
<?php 	}//end if
	} else {	?>
		<br />
		Public event submission has been disabled by the administrator.<br /><br />
		<a href="<?php echo CalRoot;?>/" class="eventMain">Click here to return to the <?php echo CalName;?>.</a>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>