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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
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
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
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
				if(document.eventSubmit.overridetime.checked){
					document.eventSubmit.specialtimeall.disabled = false;
					document.eventSubmit.startTimeHour.disabled = true;
					document.eventSubmit.startTimeMins.disabled = true;
					document.eventSubmit.endTimeHour.disabled = true;
					document.eventSubmit.endTimeMins.disabled = true;
					document.eventSubmit.ignoreendtime.disabled = true;
					if(<?php echo $hc_timeInput;?> == 12){
						document.eventSubmit.startTimeAMPM.disabled = true;
						document.eventSubmit.endTimeAMPM.disabled = true;
					}//end if
				} else {
					document.eventSubmit.specialtimeall.disabled = true;
					document.eventSubmit.startTimeHour.disabled = false;
					document.eventSubmit.startTimeMins.disabled = false;
					if(<?php echo $hc_timeInput;?> == 12){
						document.eventSubmit.startTimeAMPM.disabled = false;
					}//end if
					if(document.eventSubmit.ignoreendtime.checked == false){
						document.eventSubmit.endTimeHour.disabled = false;
						document.eventSubmit.endTimeMins.disabled = false;
						if(<?php echo $hc_timeInput;?> == 12){
							document.eventSubmit.endTimeAMPM.disabled = false;
						}//end if
					}//end if
					document.eventSubmit.ignoreendtime.disabled = false;
				}//end if
			}//end togOverride()
			
			function togEndTime(){
				if(document.eventSubmit.ignoreendtime.checked){
					document.eventSubmit.endTimeHour.disabled = true;
					document.eventSubmit.endTimeMins.disabled = true;
					if(<?php echo $hc_timeInput;?> == 12){
						document.eventSubmit.endTimeAMPM.disabled = true;
					}//end if
				} else {
					document.eventSubmit.endTimeHour.disabled = false;
					document.eventSubmit.endTimeMins.disabled = false;
					if(<?php echo $hc_timeInput;?> == 12){
						document.eventSubmit.endTimeAMPM.disabled = false;
					}//end if
				}//end if
			}//end togEndTime()
			
			function togRecur(){
				if(document.eventSubmit.recurCheck.checked){
					document.eventSubmit.recurType1.disabled = false;
					document.eventSubmit.recurType2.disabled = false;
					document.eventSubmit.recurType3.disabled = false;
					document.eventSubmit.recWeeklyDay_0.disabled = false;
					document.eventSubmit.recWeeklyDay_1.disabled = false;
					document.eventSubmit.recWeeklyDay_2.disabled = false;
					document.eventSubmit.recWeeklyDay_3.disabled = false;
					document.eventSubmit.recWeeklyDay_4.disabled = false;
					document.eventSubmit.recWeeklyDay_5.disabled = false;
					document.eventSubmit.recWeeklyDay_6.disabled = false;
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
					document.eventSubmit.recWeeklyDay_0.disabled = true;
					document.eventSubmit.recWeeklyDay_1.disabled = true;
					document.eventSubmit.recWeeklyDay_2.disabled = true;
					document.eventSubmit.recWeeklyDay_3.disabled = true;
					document.eventSubmit.recWeeklyDay_4.disabled = true;
					document.eventSubmit.recWeeklyDay_5.disabled = true;
					document.eventSubmit.recWeeklyDay_6.disabled = true;
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
			warn = '<?php echo $hc_lang_event['Valid01'];?>';
			
	<?php 	if(in_array(1, $hc_captchas)){	?>
				if(document.eventSubmit.proof.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
				}//end if
	<?php 	}//end if	?>
				
				if(document.eventSubmit.eventTitle.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid18'];?>';
				}//end if
				
				if(tinyMCE.get('eventDescription').getContent() == ''){
	                dirty = 1;
	                warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
	            }//end if
				
				if(document.eventSubmit.eventRegistration.value == 1){
					if(isNaN(document.eventSubmit.eventRegAvailable.value) == true){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid04'];?>';
					}//end if
					
					if(document.eventSubmit.contactName.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid05'];?>';
					}//end if
					
					if(document.eventSubmit.contactEmail.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid06'];?>';
					}//end if
				}//end if
				
				okDate = chkDate();
				if(okDate != ''){
					dirty = 1;
					warn = warn + okDate;
				}//end if
						
				if(isNaN(document.eventSubmit.startTimeHour.value) == true){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid07'];?>';
				} else if((document.eventSubmit.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.eventSubmit.startTimeHour.value < <?php echo $minHr;?>)) {
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid08'] . " " . $minHr . " - " . $hc_timeInput;?>';
				}//end if
				
				if(isNaN(document.eventSubmit.startTimeMins.value) == true){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid09'];?>';
				} else if((document.eventSubmit.startTimeMins.value > 59) || (document.eventSubmit.startTimeMins.value < 0)) {
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid10'];?>';
				}//end if
				
				if(isNaN(document.eventSubmit.endTimeHour.value) == true){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid11'];?>';
				} else if((document.eventSubmit.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.eventSubmit.endTimeHour.value < <?php echo $minHr;?>)) {
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid12'] . " " . $minHr . " - " . $hc_timeInput;?>';
				}//end if
				
				if(isNaN(document.eventSubmit.endTimeMins.value) == true){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid13'];?>';
				} else if((document.eventSubmit.endTimeMins.value > 59) || (document.eventSubmit.endTimeMins.value < 0)) {
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid14'];?>';
				}//end if
				
				if(document.eventSubmit.recurCheck.checked){
					myRecur = chkRecur();
					if(myRecur != ''){
						dirty = 1;
						warn = warn + myRecur;
					}//end if
				}//end if
				
				if(document.eventSubmit.submitName.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid15'];?>';
				}//end if
				
				if(document.eventSubmit.submitEmail.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
				} else {
					if(chkEmail(document.eventSubmit.submitEmail) == 0){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid17'];?>';
					}//end if
				}//end if
				
		<?php 	if($hasLoc > 0){	?>
				if(document.eventSubmit.locPreset.value == 0){
					if(document.eventSubmit.locName.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid19'];?>';
					}//end if
				}//end if
		<?php 	} else {	?>
				if(document.eventSubmit.locName.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid19'];?>';
					}//end if
		<?php 	}//end if	?>
				
				if(compareDates(document.eventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>', '<?php echo strftime($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_popDateValid;?>') == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid20'];?>';
				}//end if
				
				if(document.eventSubmit.contactEmail.value != '' && chkEmail(document.eventSubmit.contactEmail) == 0){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid21'];?>';
				}//end if
				
				if(dirty > 0){
					alert(warn + '\n\n<?php echo $hc_lang_event['Valid22'];?>');
					return false;
				} else {
					return true;
				}//end if
			}//end chkFrm()
				
			function togLocation(){
				if(document.eventSubmit.locPreset.value == 0){
					document.eventSubmit.locName.disabled = false;
					document.eventSubmit.locAddress.disabled = false;
					document.eventSubmit.locAddress2.disabled = false;
					document.eventSubmit.locCity.disabled = false;
					document.eventSubmit.locState.disabled = false;
					document.eventSubmit.locZip.disabled = false;
					document.eventSubmit.locCountry.disabled = false;
					document.getElementById('customLoc').style.display = 'block';
					document.getElementById('customLocNotice').style.display = 'none';
				} else {
					document.eventSubmit.locName.disabled = true;
					document.eventSubmit.locAddress.disabled = true;
					document.eventSubmit.locAddress2.disabled = true;
					document.eventSubmit.locCity.disabled = true;
					document.eventSubmit.locState.disabled = true;
					document.eventSubmit.locZip.disabled = true;
					document.eventSubmit.locCountry.disabled = true;
					document.getElementById('customLoc').style.display = 'none';
					document.getElementById('customLocNotice').style.display = 'block';
				}//end if
			}//end togLocation()
			
			function chkDate(){
				var err = '';
				 if(document.eventSubmit.eventDate.value == ''){
					err = '\n<?php echo $hc_lang_event['Valid23'];?>';
				} else if(!isDate(document.eventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>')){
					err = '\n<?php echo $hc_lang_event['Valid24'] . " " . strtolower($hc_popDateValid);?>';
				}//end if
				return err;
			}//end chkDate
			
			function chkRecur(){
				var err = '';
				if(document.eventSubmit.recurEndDate.value == ''){
					err = err + '\n<?php echo $hc_lang_event['Valid25'];?>';
				} else if(!isDate(document.eventSubmit.recurEndDate.value, '<?php echo $hc_popDateValid;?>')){
					err = err + '\n<?php echo $hc_lang_event['Valid26'] . " " . strtolower($hc_popDateValid);?>';
				} else if(compareDates(document.eventSubmit.eventDate.value, '<?php echo $hc_popDateValid;?>', document.eventSubmit.recurEndDate.value, '<?php echo $hc_popDateValid;?>') == 1){
					err = err + "\n<?php echo $hc_lang_event['Valid27'];?>";
				} else if(document.eventSubmit.eventDate.value == document.eventSubmit.recurEndDate.value){
					err = err + "\n<?php echo $hc_lang_event['Valid28'];?>";
				}//end if
				
				if(document.eventSubmit.recurType1.checked){
					if(document.eventSubmit.recDaily1.checked){
						if(isNaN(document.eventSubmit.dailyDays.value) == true){
							err = err + '\n<?php echo $hc_lang_event['Valid29'];?>';
						} else if(document.eventSubmit.dailyDays.value < 1) {
							err = err + '\n<?php echo $hc_lang_event['Valid30'];?>';
						} else if(document.eventSubmit.dailyDays.value == '') {
							err = err + '\n<?php echo $hc_lang_event['Valid31'];?>';
						}//end if
					}//end if
				} else if(document.eventSubmit.recurType2.checked) {
					if(isNaN(document.eventSubmit.recWeekly.value) == true){
						err = err + '\n<?php echo $hc_lang_event['Valid32'];?>';
					} else if(document.eventSubmit.recWeekly.value < 1) {
						err = err + '\n<?php echo $hc_lang_event['Valid33'];?>';
					} else if(document.eventSubmit.recWeekly.value == '') {
						err = err + '\n<?php echo $hc_lang_event['Valid34'];?>';
					}//end if
					
					if(validateCheckArray('eventSubmit','recWeeklyDay[]',1) > 0){
						err = err + '\n<?php echo $hc_lang_event['Valid35'];?>';
					}//end if
				} else if(document.eventSubmit.recurType3.checked) {
					if(document.eventSubmit.monthlyOption1.checked){
						if(isNaN(document.eventSubmit.monthlyDays.value) == true){
							err = err + '\n<?php echo $hc_lang_event['Valid36'];?>';
						} else if(document.eventSubmit.monthlyDays.value < 1) {
							err = err + '\n<?php echo $hc_lang_event['Valid37'];?>';
						} else if(document.eventSubmit.monthlyDays.value == '') {
							err = err + '\n<?php echo $hc_lang_event['Valid38'];?>';
						}//end if
						
						if(isNaN(document.eventSubmit.monthlyMonths.value) == true){
							err = err + '\n<?php echo $hc_lang_event['Valid39'];?>';
						} else if(document.eventSubmit.monthlyMonths.value < 1) {
							err = err + '\n<?php echo $hc_lang_event['Valid40'];?>';
						} else if(document.eventSubmit.monthlyMonths.value == '') {
							err = err + '\n<?php echo $hc_lang_event['Valid41'];?>';
						}//end if
					} else if(document.eventSubmit.monthlyOption2.checked){
						if(isNaN(document.eventSubmit.monthlyMonthRepeat.value) == true){
							err = err + '\n<?php echo $hc_lang_event['Valid42'];?>';
						} else if(document.eventSubmit.monthlyMonthRepeat.value < 1) {
							err = err + '\n<?php echo $hc_lang_event['Valid43'];?>';
						} else if(document.eventSubmit.monthlyMonthRepeat.value == '') {
							err = err + '\n<?php echo $hc_lang_event['Valid44'];?>';
						}//end if
					}//end if
				}//end if
				return err;
			}//end chkRecur()
			
			function confirmRecurDates(){
				if(document.eventSubmit.recurCheck.checked){
					var warn = '<?php echo $hc_lang_event['Valid45'];?>';
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
						alert(warn + '\n\n<?php echo $hc_lang_event['Valid46'];?>');
					} else {
						var qStr = 'EventConfirmRecur.php';
						if(document.eventSubmit.recurType1.checked){
							qStr = qStr + '?recurType=daily';
							if(document.eventSubmit.recDaily1.checked){
								qStr = qStr + '&dailyOptions=EveryXDays&dailyDays=' + document.eventSubmit.dailyDays.value;
							} else {
								qStr = qStr + '&dailyOptions=WeekdaysOnly';
							}//end if
						} else if(document.eventSubmit.recurType2.checked) {
							var dArr = '';
							if(document.eventSubmit.recWeeklyDay_0.checked){dArr = dArr + ',0'}
							if(document.eventSubmit.recWeeklyDay_1.checked){dArr = dArr + ',1'}
							if(document.eventSubmit.recWeeklyDay_2.checked){dArr = dArr + ',2'}
							if(document.eventSubmit.recWeeklyDay_3.checked){dArr = dArr + ',3'}
							if(document.eventSubmit.recWeeklyDay_4.checked){dArr = dArr + ',4'}
							if(document.eventSubmit.recWeeklyDay_5.checked){dArr = dArr + ',5'}
							if(document.eventSubmit.recWeeklyDay_6.checked){dArr = dArr + ',6'}
							qStr = qStr + '?recurType=weekly&recWeekly=' + document.eventSubmit.recWeekly.value + '&recWeeklyDay=' + dArr.substring(1);
						} else if(document.eventSubmit.recurType3.checked) {
							qStr = qStr + '?recurType=monthly';
							if(document.eventSubmit.monthlyOption1.checked){
								qStr = qStr + '&monthlyOption=Day&monthlyDays=' + document.eventSubmit.monthlyDays.value + '&monthlyMonths=' + document.eventSubmit.monthlyMonths.value;
							} else {
								qStr = qStr + '&monthlyOption=Month&monthlyMonthOrder=' + document.eventSubmit.monthlyMonthOrder.value + '&monthlyMonthDOW=' + document.eventSubmit.monthlyMonthDOW.value + '&monthlyMonthRepeat=' + document.eventSubmit.monthlyMonthRepeat.value;
							}//end if
						}//end if
						
						qStr = qStr + '&eventDate=' + document.eventSubmit.eventDate.value + '&recurEndDate=' + document.eventSubmit.recurEndDate.value;
						
						ajxOutput(qStr, 'recurDateChk', '<?php echo CalRoot;?>');
					}//end if
				} else {
					alert('<?php echo $hc_lang_event['Valid47'];?>');
				}//end if
			}//end confirmRecurDates()
			
			function testCAPTCHA(){
				if(document.eventSubmit.proof.value != ''){
					var qStr = 'CaptchaCheck.php?capEntered=' + document.eventSubmit.proof.value;
					ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
				} else {
					alert('<?php echo $hc_lang_event['Valid48'];?>');
				}//end if
			}//end testCAPTCHA()
			
			function searchLocations($page){
				if(document.eventSubmit.locSearch.value.length > 3){
					var qStr = 'LocationSearch.php?q=' + escape(document.eventSubmit.locSearch.value) + '&o=' + $page;
					ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
				}//end if
			}//end searchLocations()
			
			function setLocation($id){
				document.eventSubmit.locPreset.value = $id;
				togLocation();
				if($id == 0){
					document.getElementById('locSearchResults').innerHTML = '<?php echo $hc_lang_event['CheckLoc'];?>';
					document.eventSubmit.locSearch.value = '';
				}//end if
			}//end setLocation
			
			var calx = new CalendarPopup("dsCal");
			document.write(calx.getStyles());
			//-->
			</script>
			<br />
			<?php echo $hc_lang_event['Notice'];?>
			<br /><br />
			(<span style="color: #DC143C;">*</span>) = <?php echo $hc_lang_event['Required1'];?><br />
			(<span style="color: #0000FF;">*</span>) = <?php echo $hc_lang_event['Required2'];?><br />
			(<span style="color: #008000;">*</span>) = <?php echo $hc_lang_event['Required3'];?></b>
			<br /><br />
			<form id="eventSubmit" name="eventSubmit" method="post" action="<?php echo HC_FormSubmitAction;?>" onsubmit="return chkFrm();">
			<input name="dateFormat" id="dateFormat" type="hidden" value="<?php echo strtolower($hc_popDateFormat);?>" />
			<input name="timeFormat" id="timeFormat" type="hidden" value="<?php echo $hc_timeInput;?>" />
	<?php	if(in_array(1, $hc_captchas)){	?>
			<fieldset>
				<legend><?php echo $hc_lang_event['Authentication'];?></legend>
				<?php echo $hc_lang_event['CannotRead'];?><br /><br />
				<div class="frmReq">
					<label for="proof">&nbsp;</label>
			<?php	buildCaptcha();	?><br />
				</div>
				<div class="frmReq">
					<label><?php echo $hc_lang_event['ImageText'];?></label>
					<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>
					<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain"><?php echo $hc_lang_event['Confirm'];?></a></div>
				</div>
			</fieldset>
			<br />
		<?php
			}//end if	?>
			<fieldset>
				<legend><?php echo $hc_lang_event['ContactInfo'];?></legend>
				<div class="frmReq">
					<label for="submitName"><?php echo $hc_lang_event['Name'];?></label>
					<input name="submitName" id="submitName" type="text" size="25" maxlength="50" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
				<div class="frmReq">
					<label for="submitEmail"><?php echo $hc_lang_event['Email'];?></label>
					<input name="submitEmail" id="submitEmail" type="text" size="35" maxlength="75" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['EventDetails'];?></legend>
				<div class="frmReq">
					<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
					<input name="eventTitle" id="eventTitle" type="text" size="40" maxlength="150" />&nbsp;<span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
			<?php	makeTinyMCE("eventDescription", $hc_WYSIWYG, "375px");?>
			    </div>
				<div class="frmReq">
					<label for="eventDate"><?php echo $hc_lang_event['EventDate'];?></label>
					<input name="eventDate" id="eventDate" type="text" value="<?php echo strftime($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSubmit.eventDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span><br />
			    </div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['StartTime'];?></label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo date($hrFormat);?>" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.eventSubmit.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.eventSubmit.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.eventSubmit.startTimeMins,1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.eventSubmit.startTimeMins,-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="startTimeAMPM" id="startTimeAMPM">
									<option <?php if(date("A") == 'AM'){echo "selected=\"selected\"";}?> value="AM"><?php echo $hc_lang_event['AM'];?></option>
									<option <?php if(date("A") == 'PM'){echo "selected=\"selected\"";}?> value="PM"><?php echo $hc_lang_event['PM'];?></option>
								</select>
							</td>
					<?php 	}//end if	?>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['EndTime'];?></label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo date($hrFormat, mktime(date($hrFormat) + 1, 0, 0, 1, 1, 1971));?>" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.eventSubmit.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.eventSubmit.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="endTimeMins" id="endTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
							<td><a href="javascript:;" onclick="chngClock(document.eventSubmit.endTimeMins,1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.eventSubmit.endTimeMins,-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="endTimeAMPM" id="endTimeAMPM">
									<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "AM"){?>selected="selected"<?php }?> value="AM"><?php echo $hc_lang_event['AM'];?></option>
									<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "PM"){?>selected="selected"<?php }?> value="PM"><?php echo $hc_lang_event['PM'];?></option>
								</select>
							</td>
					<?php 	}//end if	?>
							<td><label for="ignoreendtime"><?php echo $hc_lang_event['NoEndTime'];?></label></td>
							<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" /></td>
						</tr>
					</table>
			    </div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label class="radioWide" for="overridetime"><input type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" /><?php echo str_replace(" ","&nbsp;",$hc_lang_event['Override']);?></label>
				</div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label class="radioWide" for="specialtimeall"><input disabled="disabled" type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" /><?php echo $hc_lang_event['AllDay'];?></label>
					<br />
				</div>
				<br />
				<div class="frmOpt">
					<label for="cost"><?php echo $hc_lang_event['Cost'];?></label>
					<input name="cost" id="cost" type="text" value="" size="25" maxlength="50" />
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['RecurInfo'];?></legend>
				<div class="frmOpt">
					<label for="recurCheck"><?php echo $hc_lang_event['Recur'];?></label>
					<input name="recurCheck" id="recurCheck" type="checkbox" onclick="togRecur();" class="noBorderIE" />&nbsp;<?php echo $hc_lang_event['RecurCheck'];?>
					<br />
					<label>&nbsp;</label>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="width: 105px;vertical-align: top;">
								<label for="recurType1" class="radio"><input name="recurType" id="recurType1" type="radio" value="daily" disabled="disabled" checked="checked" onclick="showPanel('daily')" class="noBorderIE" /><?php echo $hc_lang_event['RecDaily'];?></label>
								<br />
								<label for="recurType2" class="radio"><input name="recurType" id="recurType2" type="radio" value="weekly" disabled="disabled" onclick="showPanel('weekly')" class="noBorderIE" /><?php echo $hc_lang_event['RecWeekly'];?></label>
								<br />
								<label for="recurType3" class="radio"><input name="recurType" id="recurType3" type="radio" value="monthly" disabled="disabled" onclick="showPanel('monthly')" class="noBorderIE" /><?php echo $hc_lang_event['RecMonthly'];?></label>
							</td>
							<td style="vertical-align: top;">
								<div class="recPanel" id="daily">
									<input name="dailyOptions" id="recDaily1" type="radio" checked="checked" disabled="disabled" value="EveryXDays" class="noBorderIE" /><?php echo $hc_lang_event['Every'];?>&nbsp;<input id="dailyDays" name="dailyDays" type="text" maxlength="2" size="2" value="1" disabled="disabled" />&nbsp;<?php echo $hc_lang_event['xDays'];?><br />
									<label for="recDaily2" class="radio"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" class="noBorderIE" /><?php echo $hc_lang_event['Daily2'];?></label>
								</div>
								
								<div class="recPanel" id="weekly" style="display: none;">
									<?php echo $hc_lang_event['Every'];?> <input name="recWeekly" id="recWeekly" type="text" maxlength="2" size="2" value="1" /> <?php echo $hc_lang_event['xWeeks'];?>
									<br />
										<input id="recWeeklyDay_0" name="recWeeklyDay[]" type="checkbox" value="0" class="noBorderIE" /><?php echo $hc_lang_event['Sun'];?>
										<input id="recWeeklyDay_1" name="recWeeklyDay[]" type="checkbox" value="1" class="noBorderIE" /><?php echo $hc_lang_event['Mon'];?>
										<input id="recWeeklyDay_2" name="recWeeklyDay[]" type="checkbox" value="2" class="noBorderIE" /><?php echo $hc_lang_event['Tue'];?>
										<input id="recWeeklyDay_3" name="recWeeklyDay[]" type="checkbox" value="3" class="noBorderIE" /><?php echo $hc_lang_event['Wed'];?><br />
										<input id="recWeeklyDay_4" name="recWeeklyDay[]" type="checkbox" value="4" class="noBorderIE" /><?php echo $hc_lang_event['Thu'];?>
										<input id="recWeeklyDay_5" name="recWeeklyDay[]" type="checkbox" value="5" class="noBorderIE" /><?php echo $hc_lang_event['Fri'];?>
										<input id="recWeeklyDay_6" name="recWeeklyDay[]" type="checkbox" value="6" class="noBorderIE" /><?php echo $hc_lang_event['Sat'];?>
								</div>
								
								<div class="recPanel" id="monthly" style="display: none;">
									<input name="monthlyOption" id="monthlyOption1" type="radio" checked="checked" value="Day" class="noBorderIE" /><?php echo $hc_lang_event['Day'];?>&nbsp;<input name="monthlyDays" id="monthlyDays" type="text" maxlength="2" size="2" value="<?php echo date("d");?>" />&nbsp;<?php echo $hc_lang_event['ofEvery'];?>&nbsp;<input name="monthlyMonths" id="monthlyMonths" type="text" maxlength="2" size="2" value="1" />&nbsp;<?php echo $hc_lang_event['Months'];?><br />
									<input name="monthlyOption" id="monthlyOption2" type="radio" value="Month" class="noBorderIE" />
									<select name="monthlyMonthOrder" id="monthlyMonthOrder">
										<option value="1"><?php echo $hc_lang_event['First'];?></option>
										<option value="2"><?php echo $hc_lang_event['Second'];?></option>
										<option value="3"><?php echo $hc_lang_event['Third'];?></option>
										<option value="4"><?php echo $hc_lang_event['Fourth'];?></option>
										<option value="0"><?php echo $hc_lang_event['Last'];?></option>
									</select>
									<select name="monthlyMonthDOW" id="monthlyMonthDOW">
										<option <?php if(date("w") == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_event['Sun'];?></option>
										<option <?php if(date("w") == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_event['Mon'];?></option>
										<option <?php if(date("w") == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_event['Tue'];?></option>
										<option <?php if(date("w") == 3){echo "selected=\"selected\"";}?> value="3"><?php echo $hc_lang_event['Wed'];?></option>
										<option <?php if(date("w") == 4){echo "selected=\"selected\"";}?> value="4"><?php echo $hc_lang_event['Thu'];?></option>
										<option <?php if(date("w") == 5){echo "selected=\"selected\"";}?> value="5"><?php echo $hc_lang_event['Fri'];?></option>
										<option <?php if(date("w") == 6){echo "selected=\"selected\"";}?> value="6"><?php echo $hc_lang_event['Sat'];?></option>
									</select>
									<?php echo $hc_lang_event['ofEvery'];?> <input name="monthlyMonthRepeat" id="monthlyMonthRepeat" type="text" maxlength="2" size="2" value="1" /> <?php echo $hc_lang_event['Months'];?>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['RecurUntil'];?></label>
					<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.eventSubmit.recurEndDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
				</div>
				<br />
				<label>&nbsp;</label>
				<div id="recurDateChk">
					<a href="javascript:;" onclick="confirmRecurDates();" class="eventMain"><?php echo $hc_lang_event['ConfirmDate'];?></a>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['RegTitle'];?></legend>
				<div class="frmOpt">
					<label for="eventRegistration"><?php echo $hc_lang_event['Registration'];?></label>
					<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
						<option value="0"><?php echo $hc_lang_event['Reg0'];?></option>
						<option value="1"><?php echo $hc_lang_event['Reg1'];?></option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="eventRegAvailable"><?php echo $hc_lang_event['Limit'];?></label>
					<input name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="0" disabled="disabled" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
				</div>
			</fieldset>
			<br />
	<?php 	if(isset($hc_pubCat) && $hc_pubCat == 1){	?>
			<fieldset>
				<legend><?php echo $hc_lang_event['EventCat'];?></legend>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['Categories'];?></label>
			<?php	getCategories('eventSubmit', 2);?>
				</div>
			</fieldset>
			<br />
	<?php 	}//end if	?>
			<fieldset>
				<input type="hidden" id="locPreset" name="locPreset" value="0" />
				<legend><?php echo $hc_lang_event['LocationLabel'];?></legend>
		<?php 	if($hasLoc > 0){	?>
					<div class="frmOpt">
						<label>&nbsp;</label>
						<?php echo $hc_lang_event['CheckLocInst'];?>
					</div>
					<div class="frmReq">
						<label for="locSearch">Name Search:</label>
						<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
						<a href="javascript:;" onclick="setLocation(0);" class="eventMain">Clear Search</a>
					</div>
					<div class="frmOpt">
						<label>&nbsp;</label>
						<div id="locSearchResults"><?php echo $hc_lang_event['CheckLoc'];?></div>
					</div>
					<div id="customLocNotice" style="display:none;">
						<label>&nbsp;</label>
						<b><?php echo $hc_lang_event['PresetLoc'];?></b>
					</div>
		<?php 	}//end if	?>
				<div id="customLoc">
				<div class="frmReq">
					<label for="locName"><?php echo $hc_lang_event['Name'];?></label>
					<input name="locName" id="locName" value="" type="text" maxlength="50" size="25" /><span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress"><?php echo $hc_lang_event['Address'];?></label>
					<input name="locAddress" id="locAddress" value="" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress2">&nbsp;</label>
					<input name="locAddress2" id="locAddress2" value="" type="text" maxlength="75" size="25" />
				</div>
				<div class="frmOpt">
					<label for="locCity"><?php echo $hc_lang_event['City'];?></label>
					<input name="locCity" id="locCity" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
					<?php
						$state = $hc_defaultState;
						include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['RegionFile']);
					?><span style="color: #0000FF;">*</span>
				</div>
				<div class="frmOpt">
					<label for="locZip"><?php echo $hc_lang_event['Postal'];?></label>
					<input name="locZip" id="locZip" value="" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locCountry"><?php echo $hc_lang_event['Country'];?></label>
					<input name="locCountry" id="locCountry" value="" type="text" maxlength="50" size="5" />
				</div>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['Contact'];?></legend>
				<div class="frmOpt">
					<label for="contactName"><?php echo $hc_lang_event['Name'];?></label>
					<input name="contactName" id="contactName" type="text" maxlength="50" size="20" value="" /><span style="color: #008000;">*</span>
				</div>
				<div class="frmOpt">
					<label for="contactEmail"><?php echo $hc_lang_event['Email'];?></label>
					<input name="contactEmail" id="contactEmail" type="text" maxlength="75" size="30" value="" /><span style="color: #008000;">*</span>
				</div>
				<div class="frmOpt">
					<label for="contactPhone"><?php echo $hc_lang_event['Phone'];?></label>
					<input name="contactPhone" id="contactPhone" type="text" maxlength="25" size="20" value="" />
				</div>
				<div class="frmOpt">
					<label for="contactURL"><?php echo $hc_lang_event['Website'];?></label>
					<input name="contactURL" id="contactURL" type="text" maxlength="100" size="30" value="http://" />
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['MessageLabel'];?></legend>
				<div class="frmOpt">
					<label for="goadminmessage"><?php echo $hc_lang_event['Include'];?></label>
					<input name="goadminmessage" id="goadminmessage" type="checkbox" value="" onclick="togAdminMsg();" class="noBorderIE" />
				</div>
				<div class="frmOpt">
					<label for="adminmessage"><?php echo $hc_lang_event['Message'];?></label>
					<textarea name="adminmessage" id="adminmessage" rows="7" cols="50" disabled="disabled"></textarea>
				</div>
			</fieldset>
			<br />
			<input name="submit" id="submit" type="submit" value="Submit Event" class="button" />
			</form>
<?php 	} else {	?>
			<br />
	<?php 	feedback(1, $hc_lang_event['Feed01']);
			echo "<br />";
			echo $hc_lang_event['ThankYou'];?>
			<br /><br />
			<a href="<?php echo CalRoot;?>/index.php?com=submit" class="eventMain"><?php echo $hc_lang_event['ClickSubmitAgain'];?></a><br />
			<a href="<?php echo CalRoot;?>/" class="eventMain"><?php echo $hc_lang_event['ClickToBrowse'];?></a>
<?php 	}//end if
	} else {
		echo "<br />" . $hc_lang_event['Disabled'];?>
		<br /><br />
		<a href="<?php echo CalRoot;?>/" class="eventMain"><?php echo $hc_lang_event['ClickToBrowse'];?></a>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>