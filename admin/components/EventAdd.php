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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/event.php');
	
	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
	$hasLoc = 0;
	if(hasRows($resultL)){
		$hasLoc = 1;
	}//end if
	
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
	}//end if	?>
	
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION['LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/liveclipboard/script.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/liveclipboard/hCal.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	
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
	
	var recPanel = new Array('daily', 'weekly', 'monthly');
	function showPanel(who){
		for(i = 0; i < recPanel.length; i++){
			document.getElementById(recPanel[i]).style.display = (who == recPanel[i]) ? 'block':'none';
		}//end for
		return false;
	}//end showPanel()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_event['Valid01'];?>";
		
		if(document.frmEventAdd.eventRegistration.value == 1){
			if(isNaN(document.frmEventAdd.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
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
		
		if(compareDates(document.frmEventAdd.eventDate.value, '<?php echo $hc_popDateValid;?>', '<?php echo strftime($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_popDateValid;?>') == 0){
			if(!confirm('<?php echo $hc_lang_event['Valid18'] . "\\n\\n          " . $hc_lang_event['Valid19'] . "\\n          " . $hc_lang_event['Valid20'];?>')){
				return false;
			}//end if
		}//end if
		
		if(document.frmEventAdd.contactEmail.value != '' && chkEmail(document.frmEventAdd.contactEmail) == 0){
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
	
	function togOverride(){
		if(document.frmEventAdd.overridetime.checked){
			document.frmEventAdd.specialtimeall.disabled = false;
			document.frmEventAdd.specialtimetbd.disabled = false;
			document.frmEventAdd.startTimeHour.disabled = true;
			document.frmEventAdd.startTimeMins.disabled = true;
			document.frmEventAdd.endTimeHour.disabled = true;
			document.frmEventAdd.endTimeMins.disabled = true;
			document.frmEventAdd.ignoreendtime.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventAdd.startTimeAMPM.disabled = true;
				document.frmEventAdd.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventAdd.specialtimeall.disabled = true;
			document.frmEventAdd.specialtimetbd.disabled = true;
			document.frmEventAdd.startTimeHour.disabled = false;
			document.frmEventAdd.startTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventAdd.startTimeAMPM.disabled = false;
			}//end if
			if(document.frmEventAdd.ignoreendtime.checked == false){
				document.frmEventAdd.endTimeHour.disabled = false;
				document.frmEventAdd.endTimeMins.disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventAdd.endTimeAMPM.disabled = false;
				}//end if
			}//end if
			document.frmEventAdd.ignoreendtime.disabled = false;
		}//end if
	}//end togOverride()
	
	function togEndTime(){
		if(document.frmEventAdd.ignoreendtime.checked){
			document.frmEventAdd.endTimeHour.disabled = true;
			document.frmEventAdd.endTimeMins.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventAdd.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventAdd.endTimeHour.disabled = false;
			document.frmEventAdd.endTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventAdd.endTimeAMPM.disabled = false;
			}//end if
		}//end if
	}//end togEndTime()
	
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
	
	function togRegistration(){
		if(document.frmEventAdd.eventRegistration.value == 0){
			document.frmEventAdd.eventRegAvailable.disabled = true;
		} else {
			document.frmEventAdd.eventRegAvailable.disabled = false;
		}//end if
	}//end togRegistration()
	
	function togLocation(){
		if(document.frmEventAdd.locPreset.value == 0){
			document.frmEventAdd.locName.disabled = false;
			document.frmEventAdd.locAddress.disabled = false;
			document.frmEventAdd.locAddress2.disabled = false;
			document.frmEventAdd.locCity.disabled = false;
			document.frmEventAdd.locState.disabled = false;
			document.frmEventAdd.locZip.disabled = false;
			document.frmEventAdd.locCountry.disabled = false;
			document.getElementById('customLoc').style.display = 'block';
			document.getElementById('customLocNotice').style.display = 'none';
		} else {
			document.frmEventAdd.locName.disabled = true;
			document.frmEventAdd.locAddress.disabled = true;
			document.frmEventAdd.locAddress2.disabled = true;
			document.frmEventAdd.locCity.disabled = true;
			document.frmEventAdd.locState.disabled = true;
			document.frmEventAdd.locZip.disabled = true;
			document.frmEventAdd.locCountry.disabled = true;
			document.getElementById('customLoc').style.display = 'none';
			document.getElementById('customLocNotice').style.display = 'block';
		}//end if
	}//end togEndTime()
	
	function chkDate(){
		var err = '';
		 if(document.frmEventAdd.eventDate.value == ''){
			err = '\n<?php echo $hc_lang_event['Valid23'];?>';
		} else if(!isDate(document.frmEventAdd.eventDate.value, '<?php echo $hc_popDateValid;?>')){
			err = '\n<?php echo $hc_lang_event['Valid24'];?> <?php echo strtolower($hc_popDateValid);?>';
		}//end if
		return err;
	}//end chkDate
	
	function chkRecur(){
		var err = '';
		if(document.frmEventAdd.recurEndDate.value == ''){
			err = err + '\n<?php echo $hc_lang_event['Valid25'];?>';
		} else if(!isDate(document.frmEventAdd.recurEndDate.value, '<?php echo $hc_popDateValid;?>')){
			err = err + '\n<?php echo $hc_lang_event['Valid26'];?> <?php echo strtolower($hc_popDateValid);?>';
		} else if(compareDates(document.frmEventAdd.eventDate.value, '<?php echo $hc_popDateValid;?>', document.frmEventAdd.recurEndDate.value, '<?php echo $hc_popDateValid;?>') == 1){
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
	
	function searchLocations($page){
		if(document.frmEventAdd.locSearch.value.length > 3){
			var qStr = 'LocationSearch.php?q=' + escape(document.frmEventAdd.locSearch.value) + '&o=' + $page;
			ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
		}//end if
	}//end searchLocations()
	
	function setLocation($id){
		document.frmEventAdd.locPreset.value = $id;
		togLocation();
		if($id == 0){
			document.getElementById('locSearchResults').innerHTML = '';
			document.frmEventAdd.locSearch.value = '';
		}//end if
	}//end setLocation
	
	var calx = new CalendarPopup("dsCal");
	document.write(calx.getStyles());
	//-->
	</script>

<?php
	appInstructions(0, "Adding_Events", $hc_lang_event['TitleAdd'], $hc_lang_event['InstructAdd']);?>
	<br />
	<form id="frmEventAdd" name="frmEventAdd" method="post" action="<?php echo CalAdminRoot . "/components/EventAddAction.php";?>" onsubmit="return chkFrm();">
	<input name="dateFormat" id="dateFormat" type="hidden" value="<?php echo strtolower($hc_popDateFormat);?>" />
	<input name="timeFormat" id="timeFormat" type="hidden" value="<?php echo $hc_timeInput;?>" />
	<div class="ControlContainer" id="hc_LiveClip"></div>
	&nbsp; <span style="line-height: 23px;"><b><?php echo $hc_lang_event['LiveClipAdd'];?></b></span>
	<br /><br />
	<script type="text/javascript" language="JavaScript">
	//<!--
	function fakeIt(){return true;}
    function MicroFormatObjectBinding(displayDiv, microFormatObject) {
        var webClip;
        var self = this;
        
        this.updateDisplayAndWebClipData = function() {
            webClip = new LiveClipboardContent();
            webClip.source = window.location.href.replace("&", "&amp;");
            displayDiv.innerHTML = microFormatObject.HTML;
            webClip.data.formats[0] = new DataFormat();
            webClip.data.formats[0].type = microFormatObject.formatType;
            webClip.data.formats[0].contentType = "application/xhtml+xml";
            webClip.data.formats[0].items = new Array(1);
            webClip.data.formats[0].items[0] = new DataItem();
            webClip.data.formats[0].items[0].xmlData = microFormatObject.xmlData;
        }//end updateDisplayAndWebClipData()
        
        this.onCopy = function(){return webClip;}
        this.onPaste = function(clipData) {
			for (var i = 0; i < clipData.data.formats.length; i++) {
				if ((clipData.data.formats[i].contentType = "application/xhtml+xml") && (clipData.data.formats[i].type == microFormatObject.formatType) && (clipData.data.formats[i].items.length > 0) && (clipData.data.formats[i].items[0].xmlData)) {
			        microFormatObject.parseXml(clipData.data.formats[i].items[0].xmlData);
			        
			        document.frmEventAdd.eventTitle.value = microFormatObject.Summary;
			        if(<?php echo $hc_WYSIWYG;?> == 1){
			        	tinyMCE.execCommand('mceFocus', false, 'eventDescription');
			        	tinyMCE.execCommand('mceInsertContent', false, microFormatObject.Description);
			        } else {
			        	document.frmEventAdd.eventDescription.value = microFormatObject.Description;
			        }//end if
			        var year = microFormatObject.DtStart.substring(0,4);
			        var month = LZ(microFormatObject.DtStart.substring(4,6));
			        var day = LZ(microFormatObject.DtStart.substring(6,8));
					var startTimeHr = microFormatObject.DtStart.substring(9,11);
					var startTimeMin = microFormatObject.DtStart.substring(11,13);
					var endTimeHr = microFormatObject.DtEnd.substring(9,11);
					var endTimeMin = microFormatObject.DtEnd.substring(11,13);
					var newDate = new Date(year,month-1,day,0,0,0);
					
					document.frmEventAdd.eventDate.value = formatDate(newDate,'<?php echo $hc_popDateValid;?>');
					
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventAdd.startTimeMins.value = LZ(startTimeMin);
						if(startTimeHr > 12){
							document.frmEventAdd.startTimeHour.value = LZ(startTimeHr - 12);
							document.frmEventAdd.startTimeAMPM.value = "PM";
						} else {
							document.frmEventAdd.startTimeHour.value = startTimeHr;
							document.frmEventAdd.startTimeAMPM.value = "AM";
						}//end if
						
						document.frmEventAdd.endTimeMins.value = LZ(endTimeMin);
						if(endTimeHr > 12){
							document.frmEventAdd.endTimeHour.value = LZ(endTimeHr - 12);
							document.frmEventAdd.endTimeAMPM.value = "PM";
						} else {
							document.frmEventAdd.endTimeHour.value = endTimeHr;
							document.frmEventAdd.endTimeAMPM.value = "AM";
						}//end if
					} else {
						document.frmEventAdd.startTimeHour.value = LZ(startTimeHr);
						document.frmEventAdd.startTimeMins.value = LZ(startTimeMin);
						document.frmEventAdd.endTimeHour.value = LZ(endTimeHr);
						document.frmEventAdd.endTimeMins.value = LZ(endTimeMin);
					}//end if
					
					if(microFormatObject.Location > 0){
						document.frmEventAdd.locPreset.value = microFormatObject.Location;
						togLocation();
					}//end if
			        document.frmEventAdd.contactURL.value = microFormatObject.Url;
			        return;
			    }//end if
			}//end for
            
            microFormatObject.clearProps();
            self.updateDisplayAndWebClipData();
        }//end onPaste()
        self.updateDisplayAndWebClipData();
    }//end MicroFormatObjectBinding()
    
	var calObj = new HCal();
	var hc_calendarBinding = new MicroFormatObjectBinding(document.getElementById("hc_LiveClip"), calObj);
	var hc_clipBoardControl = new WebClip(document.getElementById("hc_LiveClip"), hc_calendarBinding.onCopy, hc_calendarBinding.onPaste, fakeIt, fakeIt);
	//-->
	</script>
	
	
	<fieldset>
		<legend><?php echo $hc_lang_event['EventDetail'];?></legend>
		<div class="frmReq">
			<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
			<input name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
	<?php makeTinyMCE("eventDescription", $hc_WYSIWYG, "435px", "advanced");?>
		</div>
		<div class="frmReq">
			<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
			<input name="eventDate" id="eventDate" type="text" value="<?php echo strftime($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.eventDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['StartTime'];?></label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo date($hrFormat);?>" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="startTimeMins" id="startTimeMins" type="text" value="00" size="2" maxlength="2" /></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventAdd.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
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
				<input name="recurEndDate" id="recurEndDate" type="text" value="" disabled="disabled" size="10" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventAdd.recurEndDate,'anchor2','<?php echo $hc_popDateValid;?>'); return false;" name="anchor2" id="anchor2"><img src="<?php echo CalRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" /></a>
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
	<?php	getCategories('frmEventAdd', 2);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Location'];?></legend>
		<input type="hidden" id="locPreset" name="locPreset" value="0" />
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
			<input name="contactURL" id="contactURL" type="text" maxlength="100" size="40" value="http://" />
		</div>
	</fieldset>
<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){	?>
		<br />
		<fieldset>
			<legend><?php echo $hc_lang_event['EventfulAdd'];?></legend>
			<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;<?php echo $hc_lang_event['EventfulLabelA'];?></label>
			<div id="eventful" style="display:none;clear:both;">
		<?php
			if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
			<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
			<?php echo $hc_lang_event['EventfulReq'];?>
			<br /><br />
				<div class="frmOpt">
					<label for="efUser" class="settingsLabel"><?php echo $hc_lang_event['Username'];?></label>
					<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
				</div>
				<div class="frmOpt">
					<label for="efPass" class="settingsLabel"><?php echo $hc_lang_event['Passwrd1'];?></label>
					<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
				</div>
				<div class="frmOpt">
					<label for="efPass2" class="settingsLabel"><?php echo $hc_lang_event['Passwrd2'];?></label>
					<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
				</div>
			</div>
		<?php			
			}//end if	
			echo $hc_lang_event['EventfulSubmit'];	?>
			</div>
		</fieldset>
<?php
		}//end if
	}//end if	?>
	<br />
	<input name="submit" id="submit" type="submit" value="   <?php echo $hc_lang_event['Save'];?>   " class="button" />
	</form>
	<div id="dsCal" class="datePicker"></div>