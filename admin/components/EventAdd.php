<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
	
	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
	$hasLoc = (hasRows($resultL)) ? 1 : 0;
	$hourOffset = date("G") + ($hc_cfg35);
	$hrFormat = ($hc_timeInput == 23) ? "H" : "h";
	$minHr = ($hc_timeInput == 23) ? 0 : 1;
	
	appInstructions(0, "Adding_Events", $hc_lang_event['TitleAdd'], $hc_lang_event['InstructAdd']);
	echo '<br />';?>
	
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_event['Valid01'];?>";
		
		if(document.frmEventAdd.eventRegistration.value == 1){
			if(isNaN(document.frmEventAdd.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
			} else if(document.frmEventAdd.eventRegAvailable.value < 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid60'];?>';
			}//end if
			
			if(document.frmEventAdd.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
			}//end if
			
			if(document.frmEventAdd.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid04'];?>';
			}//end if
		}//end if
				
		okDate = chkDate();
		if(okDate != ''){
			dirty = 1;
			warn = warn + okDate;
		}//end if
		
		if(isNaN(document.frmEventAdd.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid05'];?>';
		} else if((document.frmEventAdd.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventAdd.startTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid06'];?> <?php echo $minHr;?> - <?php echo $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventAdd.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid07'];?>';
		} else if((document.frmEventAdd.startTimeMins.value > 59) || (document.frmEventAdd.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid08'];?> 0 - 59';
		}//end if
		
		if(isNaN(document.frmEventAdd.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid09'];?>';
		} else if((document.frmEventAdd.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventAdd.endTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid10'];?> <?php echo $minHr;?> - <?php echo $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventAdd.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid11'];?>';
		} else if((document.frmEventAdd.endTimeMins.value > 59) || (document.frmEventAdd.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid12'];?> 0 - 59';
		}//end if
		
		if(document.frmEventAdd.recurCheck.checked){
			myRecur = chkRecur();
			if(myRecur != ''){
				dirty = 1;
				warn = warn + myRecur;
			}//end if
		}//end if
		
		if(document.frmEventAdd.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid13'];?>';
		}//end if
		
		if(document.frmEventAdd.eventDate.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid14'];?>';
		}//end if
		
		if(validateCheckArray('frmEventAdd','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid15'];?>';
		}//end if
	
<?php 	if($hasLoc > 0){	?>
		if(document.frmEventAdd.locPreset.value == 0){
			if(document.frmEventAdd.locName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
			}//end if
		}//end if
<?php 	} else {	?>
		if(document.frmEventAdd.locName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
			}//end if
<?php 	}//end if	?>
		
		if(compareDates(document.frmEventAdd.eventDate.value, '<?php echo $hc_cfg51;?>', '<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_cfg51;?>') == 0){
			if(!confirm('<?php echo $hc_lang_event['Valid18'] . "\\n\\n          " . $hc_lang_event['Valid19'] . "\\n          " . $hc_lang_event['Valid20'];?>')){
				return false;
			}//end if
		}//end if
		
		if(document.frmEventAdd.contactEmail.value != '' && chkEmail(document.frmEventAdd.contactEmail) == 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid21'];?>';
		}//end if
		
		if(document.getElementById('doEventbrite').checked){
			ebFailT = 0;
			ebFailP = 0;
			ebFailQ = 0;
			for(x=1;x<=5;x++){
				if(document.getElementById('ticket' + x).value != '' && ((document.getElementById('price' + x).disabled == false && document.getElementById('price' + x).value == '') || document.getElementById('qty' + x).value == '')){
					ebFailT = 1;
				}//end if
			}//end for
			for(x=1;x<=5;x++){
				if(document.getElementById('price' + x).disabled == false && document.getElementById('price' + x).value != '' && (document.getElementById('price' + x).value != parseFloat(document.getElementById('price' + x).value))){
					ebFailP = 1;
				}//end if
			}//end for
			for(x=1;x<=5;x++){
				if(document.getElementById('qty' + x).value != '' && (document.getElementById('qty' + x).value != parseInt(document.getElementById('qty' + x).value))){
					ebFailQ = 1;
				}//end if
			}//end for

			if(document.getElementById('ticket1').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid61'];?>';
			}//end if
			if(ebFailT > 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid65'];?>';
			}//end if
			if(ebFailP > 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid62'];?>';
			}//end if
			if(ebFailQ > 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid63'];?>';
			}//end if
		}//end if
		
		if(document.getElementById('doTwitter').checked){
			if(document.getElementById('tweetThis').value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid64'];?>';
			}//end if
		}//end if

		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_event['Valid22'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	
	function togRecur(){
		if(document.frmEventAdd.recurCheck.checked == false){
			document.frmEventAdd.recurType1.disabled = true;
			document.frmEventAdd.recurType2.disabled = true;
			document.frmEventAdd.recurType3.disabled = true;
			document.frmEventAdd.recWeeklyDay_0.disabled = true;
			document.frmEventAdd.recWeeklyDay_1.disabled = true;
			document.frmEventAdd.recWeeklyDay_2.disabled = true;
			document.frmEventAdd.recWeeklyDay_3.disabled = true;
			document.frmEventAdd.recWeeklyDay_4.disabled = true;
			document.frmEventAdd.recWeeklyDay_5.disabled = true;
			document.frmEventAdd.recWeeklyDay_6.disabled = true;
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
			document.frmEventAdd.recWeeklyDay_0.disabled = false;
			document.frmEventAdd.recWeeklyDay_1.disabled = false;
			document.frmEventAdd.recWeeklyDay_2.disabled = false;
			document.frmEventAdd.recWeeklyDay_3.disabled = false;
			document.frmEventAdd.recWeeklyDay_4.disabled = false;
			document.frmEventAdd.recWeeklyDay_5.disabled = false;
			document.frmEventAdd.recWeeklyDay_6.disabled = false;
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
	
	function chkRecur(){
		var err = '';
		if(document.frmEventAdd.recurEndDate.value == ''){
			err = err + '\n<?php echo $hc_lang_event['Valid25'];?>';
		} else if(!isDate(document.frmEventAdd.recurEndDate.value, '<?php echo $hc_cfg51;?>')){
			err = err + '\n<?php echo $hc_lang_event['Valid26'];?> <?php echo strtolower($hc_cfg51);?>';
		} else if(compareDates(document.frmEventAdd.eventDate.value, '<?php echo $hc_cfg51;?>', document.frmEventAdd.recurEndDate.value, '<?php echo $hc_cfg51;?>') == 1){
			err = err + "\n<?php echo $hc_lang_event['Valid27'];?>";
		} else if(document.frmEventAdd.eventDate.value == document.frmEventAdd.recurEndDate.value){
			err = err + "\n<?php echo $hc_lang_event['Valid28'];?>";
		}//end if
		
		if(document.frmEventAdd.recurType1.checked){
			if(document.frmEventAdd.recDaily1.checked){
				if(isNaN(document.frmEventAdd.dailyDays.value) == true){
					err = err + '\n<?php echo $hc_lang_event['Valid29'];?>';
				} else if(document.frmEventAdd.dailyDays.value < 1) {
					err = err + '\n<?php echo $hc_lang_event['Valid30'];?>';
				} else if(document.frmEventAdd.dailyDays.value == '') {
					err = err + '\n<?php echo $hc_lang_event['Valid31'];?>';
				}//end if
			}//end if
		} else if(document.frmEventAdd.recurType2.checked) {
			if(isNaN(document.frmEventAdd.recWeekly.value) == true){
				err = err + '\n<?php echo $hc_lang_event['Valid32'];?>';
			} else if(document.frmEventAdd.recWeekly.value < 1) {
				err = err + '\n<?php echo $hc_lang_event['Valid33'];?>';
			} else if(document.frmEventAdd.recWeekly.value == '') {
				err = err + '\n<?php echo $hc_lang_event['Valid34'];?>';
			}//end if
			
			if(validateCheckArray('frmEventAdd','recWeeklyDay[]',1) > 0){
				err = err + '\n<?php echo $hc_lang_event['Valid35'];?>';
			}//end if
		} else if(document.frmEventAdd.recurType3.checked) {
			if(document.frmEventAdd.monthlyOption1.checked){
				if(isNaN(document.frmEventAdd.monthlyDays.value) == true){
					err = err + '\n<?php echo $hc_lang_event['Valid36'];?>';
				} else if(document.frmEventAdd.monthlyDays.value < 1) {
					err = err + '\n<?php echo $hc_lang_event['Valid37'];?>';
				} else if(document.frmEventAdd.monthlyDays.value == '') {
					err = err + '\n<?php echo $hc_lang_event['Valid38'];?>';
				}//end if
				
				if(isNaN(document.frmEventAdd.monthlyMonths.value) == true){
					err = err + '\n<?php echo $hc_lang_event['Valid39'];?>';
				} else if(document.frmEventAdd.monthlyMonths.value < 1) {
					err = err + '\n<?php echo $hc_lang_event['Valid40'];?>';
				} else if(document.frmEventAdd.monthlyMonths.value == '') {
					err = err + '\n<?php echo $hc_lang_event['Valid41'];?>';
				}//end if
			} else if(document.frmEventAdd.monthlyOption2.checked){
				if(isNaN(document.frmEventAdd.monthlyMonthRepeat.value) == true){
					err = err + '\n<?php echo $hc_lang_event['Valid42'];?>';
				} else if(document.frmEventAdd.monthlyMonthRepeat.value < 1) {
					err = err + '\n<?php echo $hc_lang_event['Valid43'];?>';
				} else if(document.frmEventAdd.monthlyMonthRepeat.value == '') {
					err = err + '\n<?php echo $hc_lang_event['Valid44'];?>';
				}//end if
			}//end if
		}//end if
		return err;
	}//end chkRecur()
	
	function confirmRecurDates(){
		if(document.frmEventAdd.recurCheck.checked){
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
				alert(warn + '\n\n<?php echo $hc_lang_event['Valid22'];?>');
			} else {
				var qStr = 'EventConfirmRecur.php';
				if(document.frmEventAdd.recurType1.checked){
					qStr = qStr + '?recurType=daily';
					if(document.frmEventAdd.recDaily1.checked){
						qStr = qStr + '&dailyOptions=EveryXDays&dailyDays=' + document.frmEventAdd.dailyDays.value;
					} else {
						qStr = qStr + '&dailyOptions=WeekdaysOnly';
					}//end if
				} else if(document.frmEventAdd.recurType2.checked) {
					var dArr = '';
					if(document.frmEventAdd.recWeeklyDay_0.checked){dArr = dArr + ',0'}
					if(document.frmEventAdd.recWeeklyDay_1.checked){dArr = dArr + ',1'}
					if(document.frmEventAdd.recWeeklyDay_2.checked){dArr = dArr + ',2'}
					if(document.frmEventAdd.recWeeklyDay_3.checked){dArr = dArr + ',3'}
					if(document.frmEventAdd.recWeeklyDay_4.checked){dArr = dArr + ',4'}
					if(document.frmEventAdd.recWeeklyDay_5.checked){dArr = dArr + ',5'}
					if(document.frmEventAdd.recWeeklyDay_6.checked){dArr = dArr + ',6'}
					qStr = qStr + '?recurType=weekly&recWeekly=' + document.frmEventAdd.recWeekly.value + '&recWeeklyDay=' + dArr.substring(1);
				} else if(document.frmEventAdd.recurType3.checked) {
					qStr = qStr + '?recurType=monthly';
					if(document.frmEventAdd.monthlyOption1.checked){
						qStr = qStr + '&monthlyOption=Day&monthlyDays=' + document.frmEventAdd.monthlyDays.value + '&monthlyMonths=' + document.frmEventAdd.monthlyMonths.value;
					} else {
						qStr = qStr + '&monthlyOption=Month&monthlyMonthOrder=' + document.frmEventAdd.monthlyMonthOrder.value + '&monthlyMonthDOW=' + document.frmEventAdd.monthlyMonthDOW.value + '&monthlyMonthRepeat=' + document.frmEventAdd.monthlyMonthRepeat.value;
					}//end if
				}//end if
				
				qStr = qStr + '&eventDate=' + document.frmEventAdd.eventDate.value + '&recurEndDate=' + document.frmEventAdd.recurEndDate.value;
				
				ajxOutput(qStr, 'recurDateChk', '<?php echo CalRoot;?>');
			}//end if
		} else {
			alert('<?php echo $hc_lang_event['Valid46'];?>');
		}//end if
	}//end confirmRecurDates()
	
	var calx = new CalendarPopup("dsCal");
	calx.setCssPrefix("hc_");

	var tweetPrefix = '<?php echo $hc_lang_event['TweetNew'];?>';
	<?php include('includes/java/events.php');?>
	//-->
	</script>
	
	<form id="frmEventAdd" name="frmEventAdd" method="post" action="<?php echo CalAdminRoot . "/components/EventAddAction.php";?>" onsubmit="return chkFrm();">
	<fieldset>
		<legend><?php echo $hc_lang_event['EventDetail'];?></legend>
		<div class="frmReq">
			<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
			<input onblur="buildTweet();" name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
	<?php makeTinyMCE('eventDescription', '525px', 'advanced');?>
		</div>
		<div class="frmReq">
			<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
			<input name="eventDate" id="eventDate" type="text" value="<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.eventDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['StartTime'];?></label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input onblur="buildTweet();" name="startTimeHour" id="startTimeHour" type="text" value="<?php echo date($hrFormat);?>" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input onblur="buildTweet();" name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
						<td>
							<select onclick="buildTweet();" name="startTimeAMPM" id="startTimeAMPM">
								<option <?php if(date("A") == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
								<option <?php if(date("A") == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
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
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="endTimeMins" id="endTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.endTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
						<td>
							<select name="endTimeAMPM" id="endTimeAMPM">
								<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "AM"){?>selected="selected"<?php }?> value="AM">AM</option>
								<option <?php if(date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971)) == "PM"){?>selected="selected"<?php }?> value="PM">PM</option>
							</select>
						</td>
			<?php 	}//end if	?>
					<td><label for="ignoreendtime" style="padding-left:20px;" class="radio"><?php echo $hc_lang_event['NoEndTime'];?></label></td>
					<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" /></td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="overridetime" class="radioWide"><input type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" /><?php echo str_replace(" ","&nbsp;",$hc_lang_event['Override']);?></label>
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="specialtimeall" class="radioWide"><input disabled="disabled" type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" /><?php echo $hc_lang_event['AllDay'];?></label>
		</div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="specialtimetbd" class="radioWide"><input disabled="disabled" type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" /><?php echo $hc_lang_event['TBA'];?></label>
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
		</div>
		<div class="frmOpt">
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
							<label for="recDaily2" class="radioWide"><input name="dailyOptions" id="recDaily2" type="radio" disabled="disabled" value="WeekdaysOnly" class="noBorderIE" /><?php echo $hc_lang_event['Daily2'];?></label>
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
							<input name="monthlyOption" id="monthlyOption1" type="radio" checked="checked" value="Day" class="noBorderIE" /><?php echo $hc_lang_event['Day'];?>&nbsp;<input name="monthlyDays" id="monthlyDays" type="text" maxlength="3" size="3" value="<?php echo date("d");?>" />&nbsp;<?php echo $hc_lang_event['ofEvery'];?>&nbsp;<input name="monthlyMonths" id="monthlyMonths" type="text" maxlength="2" size="2" value="1" />&nbsp;<?php echo $hc_lang_event['Months'];?><br />
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
			<div class="frmOpt">
				<label><?php echo $hc_lang_event['RecurUntil'];?></label>
				<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.recurEndDate,'anchor2','<?php echo $hc_cfg51;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
			</div>
			<br />
			<label>&nbsp;</label>
			<div id="recurDateChk">
				<a href="javascript:;" onclick="confirmRecurDates();" class="main"><?php echo $hc_lang_event['Confirm'];?></a>
			</div>
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
				<option value="2"><?php echo $hc_lang_event['Reg2'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventRegAvailable"><?php echo $hc_lang_event['Limit'];?></label>
			<input name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="0" disabled="disabled" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Settings'];?></legend>
		<div class="frmOpt">
			<label for="eventStatus"><?php echo $hc_lang_event['Status'];?></label>
			<select name="eventStatus" id="eventStatus">
				<option value="1"><?php echo $hc_lang_event['Status1'];?></option>
				<option value="2"><?php echo $hc_lang_event['Status2'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventBillboard"><?php echo $hc_lang_event['Billboard'];?></label>
			<select name="eventBillboard" id="eventBillboard">
				<option value="0"><?php echo $hc_lang_event['Billboard0'];?></option>
				<option value="1"><?php echo $hc_lang_event['Billboard1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['Categories'];?></label>
	<?php	getCategories('frmEventAdd', 3);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Location'];?></legend>
		<input type="hidden" id="locPreset" name="locPreset" value="0" />
		<input type="hidden" id="locPresetName" name="locPresetName" value="" />
<?php 	if($hasLoc > 0){	?>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<?php echo $hc_lang_event['CheckLocInst'];?>
			</div>
			<div class="frmReq">
				<label for="locSearchText"><?php echo $hc_lang_event['LocSearch']?></label>
				<input type="text" name="locSearchText" id="locSearchText" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
				<a href="javascript:;" onclick="setLocation(0);" class="eventMain"><?php echo $hc_lang_event['ClearSearch']?></a>
			</div>
			<div class="frmOpt">
				<label>&nbsp;</label>
				<div id="locSearchResults">&nbsp;</div>
			</div>
			
			<div id="customLocNotice" style="display:none;">
				<label>&nbsp;</label>
				<b><?php echo $hc_lang_event['PresetLoc'];?></b>
			</div>
<?php 	}//end if	?>
		<div id="customLoc">
		<div class="frmReq">
			<label for="locName"><?php echo $hc_lang_event['Name'];?></label>
			<input onblur="buildTweet();" name="locName" id="locName" value="" type="text" maxlength="50" size="25" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress"><?php echo $hc_lang_event['Address'];?></label>
			<input name="locAddress" id="locAddress" value="" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress2"><?php echo $hc_lang_event['Address2'];?></label>
			<input name="locAddress2" id="locAddress2" value="" type="text" maxlength="75" size="25" />
		</div>
	<?php
		$inputs = array(1 => array('City','locCity'),2 => array('Postal','locZip'));
		
		echo '<div class="frmOpt">';
		$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
		$second = ($first == 1) ? 2 : 1;
		
		echo '<label for="' . $inputs[$first][1] . '">' . $hc_lang_event[$inputs[$first][0]] . '</label>';
		echo '<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';
		
		if($hc_lang_config['AddressRegion'] != 0){
			echo '<div class="frmOpt">';
			echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
			$state = $hc_cfg21;
			include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);
			echo '<span style="color: #0000FF;">*</span></div>';
		}//end if

		echo '<div class=frmOpt">';
		echo '<label for="' . $inputs[$second][1] . '">' . $hc_lang_event[$inputs[$second][0]] . '</label>';
		echo '<input name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';
		?>
		<div class="frmOpt">
			<label for="locCountry"><?php echo $hc_lang_event['Country'];?></label>
			<input name="locCountry" id="locCountry" value="" type="text" maxlength="50" size="10" />
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
			<input name="contactURL" id="contactURL" type="text" maxlength="100" size="40" value="http://" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['DistPub'];?></legend>
	<?php
		echo '<i>' . $hc_lang_event['DistPubNotice'] . '</i><br /><br />';
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,36,37,38,39,46,47);");
		$goEventful = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '' && mysql_result($result,4,1) != '') ? 1 : 0;
		$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '') ? 1 : 0;
		$goTwitter = (mysql_result($result,6,1) != '' && mysql_result($result,7,1) != '') ? 1 : 0;
		
		echo '<div class="frmOpt"><label for="doEventful" class="radioDistPub"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById(\'eventful\'));"';
		echo ($goEventful == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo $hc_lang_event['EventfulLabelA'] . '</label></div>';
		echo '<div id="eventful" style="display:none;clear:both;">' . $hc_lang_event['EventfulSubmit'] . '</div>';

		echo '<div class="frmOpt" style="border-top:solid 1px #CCCCCC;"><label for="doEventbrite" class="radioDistPub"><input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));chkReg();"';
		echo ($goEventbrite == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo $hc_lang_event['EventbriteLabelA'] . '</label></div>';

		echo '<div id="eventbrite" style="display:none;clear:both;min-height:170px;">' . $hc_lang_event['EventbriteNotice'];
		echo '<div class="frmOpt" style="clear:both;padding-top:10px;"><label for="currency"><b>' . $hc_lang_event['EventCurrency'] . '</b></label>';
		echo '<select name="currency" id="currency">';
		echo '<option value="USD">' . $hc_lang_event['USD'] . '</option>';
		echo '<option value="EUR">' . $hc_lang_event['EUR'] . '</option>';
		echo '<option value="GBP">' . $hc_lang_event['GBP'] . '</option>';
		echo '<option value="JPY">' . $hc_lang_event['JPY'] . '</option>';
		echo '<option value="AUD">' . $hc_lang_event['AUD'] . '</option>';
		echo '<option value="CAD">' . $hc_lang_event['CAD'] . '</option>';
		echo '<option value="CZK">' . $hc_lang_event['CZK'] . '</option>';
		echo '<option value="DKK">' . $hc_lang_event['DKK'] . '</option>';
		echo '<option value="HKD">' . $hc_lang_event['HKD'] . '</option>';
		echo '<option value="HUF">' . $hc_lang_event['HUF'] . '</option>';
		echo '<option value="NZD">' . $hc_lang_event['NZD'] . '</option>';
		echo '<option value="NOK">' . $hc_lang_event['NOK'] . '</option>';
		echo '<option value="PLN">' . $hc_lang_event['PLN'] . '</option>';
		echo '<option value="SGD">' . $hc_lang_event['SGD'] . '</option>';
		echo '<option value="SEK">' . $hc_lang_event['SEK'] . '</option>';
		echo '<option value="CHF">' . $hc_lang_event['CHF'] . '</option>';
		echo '<option value="ILS">' . $hc_lang_event['ILS'] . '</option>';
		echo '<option value="MXN">' . $hc_lang_event['MXN'] . '</option>';
		echo '</select></div>';

		echo '<div style="clear:both;float:left;width:35%;padding:5px 0px 0px 0px;"><b>' . $hc_lang_event['TicketName'] . '</b></div>';
		echo '<div style="float:left;width:35%;padding:5px 0px 0px 0px;"><b>' . $hc_lang_event['TicketPrice'] . '</b></div>';
		echo '<div style="float:left;width:15%;padding:5px 0px 0px 0px;text-align:center;"><b>' . $hc_lang_event['TicketQty'] . '</b></div>';
		echo '<div style="float:left;width:10%;padding:5px 0px 0px 0px;text-align:center;"><b>' . $hc_lang_event['TicketFee'] . '</b></div>';
		for($x = 1; $x <= 5; ++$x){
			echo '<div style="clear:both;float:left;width:35%;padding:0px;">';
			echo '<input name="ticket' . $x . '" id="ticket' . $x . '" type="text" size="30" maxlength="200" value="" /></div>';
			echo '<div style="float:left;width:35%;padding:0px;">';
			echo '<input onclick="togTicketPrice(' . $x . ',0);" name="priceType' . $x . '" type="radio" value="0" checked="checked" /><input name="price' . $x . '" id="price' . $x . '" type="text" size="5" maxlength="7" value="" />';
			echo '<input onclick="togTicketPrice(' . $x . ',1);" name="priceType' . $x . '" type="radio" value="1" />' . $hc_lang_event['Free'] . '&nbsp;';
			echo '<input onclick="togTicketPrice(' . $x . ',1);" name="priceType' . $x . '" type="radio" value="2" />' . $hc_lang_event['Donate'];
			echo '</div>';
			echo '<div style="float:left;width:15%;padding:0px;text-align:center;">';
			echo '<input name="qty' . $x . '" id="qty' . $x . '" type="text" size="5" maxlength="5" value="" /></div>';
			echo '<div style="float:left;width:10%;padding:0px;text-align:center;">';
			echo '<input name="fee' . $x . '" id="fee' . $x . '" type="checkbox" value="" /></div>';
		}//end for
		echo '</div>';

		echo '<div class="frmOpt" style="border-top:solid 1px #CCCCCC;"><label for="doTwitter" class="radioDistPub"><input name="doTwitter" id="doTwitter" type="checkbox" onclick="toggleMe(document.getElementById(\'twitter\'));buildTweet();"';
		echo ($goTwitter == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo $hc_lang_event['TwitterLabel'] . '</label></div>';
		echo '<div id="twitter" style="display:none;clear:both;padding:12px 5px 0px 5px;">';
		echo '<input name="tweetThis" id="tweetThis" type="text" size="45" maxlength="125" value="" style="width:75%;" />';
		echo '<br /><br />' . $hc_lang_event['TwitterNotice'];
		echo '</div>';
		?>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value="   <?php echo $hc_lang_event['Save'];?>   " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>