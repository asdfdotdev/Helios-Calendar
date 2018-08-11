<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/?>
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

	function togOverride(){
		if(document.getElementById('overridetime').checked){
			document.getElementById('specialtimeall').disabled = false;
			document.getElementById('specialtimetbd').disabled = false;
			document.getElementById('startTimeHour').disabled = true;
			document.getElementById('startTimeMins').disabled = true;
			document.getElementById('endTimeHour').disabled = true;
			document.getElementById('endTimeMins').disabled = true;
			document.getElementById('ignoreendtime').disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.getElementById('startTimeAMPM').disabled = true;
				document.getElementById('endTimeAMPM').disabled = true;
			}//end if
		} else {
			document.getElementById('specialtimeall').disabled = true;
			document.getElementById('specialtimetbd').disabled = true;
			document.getElementById('startTimeHour').disabled = false;
			document.getElementById('startTimeMins').disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.getElementById('startTimeAMPM').disabled = false;
			}//end if
			if(document.getElementById('ignoreendtime').checked == false){
				document.getElementById('endTimeHour').disabled = false;
				document.getElementById('endTimeMins').disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.getElementById('endTimeAMPM').disabled = false;
				}//end if
			}//end if
			document.getElementById('ignoreendtime').disabled = false;
		}//end if
	}//end togOverride()

	function togEndTime(){
		if(document.getElementById('ignoreendtime').checked){
			document.getElementById('endTimeHour').disabled = true;
			document.getElementById('endTimeMins').disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.getElementById('endTimeAMPM').disabled = true;
			}//end if
		} else {
			document.getElementById('endTimeHour').disabled = false;
			document.getElementById('endTimeMins').disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.getElementById('endTimeAMPM').disabled = false;
			}//end if
		}//end if
	}//end togEndTime()

	function togRegistration(){
		document.getElementById('eventRegAvailable').disabled = (document.getElementById('eventRegistration').value == 1) ? false : true;
		document.getElementById('doEventbrite').checked = (document.getElementById('eventRegistration').value == 2) ? true : false;
		document.getElementById('eventbrite').style.display = (document.getElementById('eventRegistration').value == 2) ? 'block' : 'none';
	}//end togRegistration()

	function togLocation(){
		if(document.getElementById('locPreset').value == 0){
			document.getElementById('locName').disabled = false;
			document.getElementById('locAddress').disabled = false;
			document.getElementById('locAddress2').disabled = false;
			document.getElementById('locCity').disabled = false;
			document.getElementById('locZip').disabled = false;
			document.getElementById('locCountry').disabled = false;
			document.getElementById('customLoc').style.display = 'block';
			document.getElementById('customLocNotice').style.display = 'none';
			if(document.getElementById('locState'))
				document.getElementById('locState').disabled = false;
			if(document.getElementById('locSetting'))
				document.getElementById('locSetting').style.display = 'none';
			if(document.getElementById('locSearch'))
				document.getElementById('locSearch').style.display = 'block';
		} else {
			document.getElementById('locName').disabled = true;
			document.getElementById('locAddress').disabled = true;
			document.getElementById('locAddress2').disabled = true;
			document.getElementById('locCity').disabled = true;
			document.getElementById('locState').disabled = true;
			document.getElementById('locZip').disabled = true;
			document.getElementById('locCountry').disabled = true;
			document.getElementById('customLocNotice').style.display = 'block';
			document.getElementById('customLoc').style.display = 'none';
		}//end if
	}//end togEndTime()

	function chkDate(){
		var err = '';
		 if(document.getElementById('eventDate').value == ''){
			err = '\n<?php echo $hc_lang_event['Valid23'];?>';
		} else if(!isDate(document.getElementById('eventDate').value, '<?php echo $hc_cfg51;?>')){
			err = '\n<?php echo $hc_lang_event['Valid24'];?> <?php echo strtolower($hc_cfg51);?>';
		}//end if
		return err;
	}//end chkDate

	function searchLocations($page){
		if(document.getElementById('locSearchText').value.length > 3){
			var qStr = 'LocationSearch.php?np=1&q=' + escape(document.getElementById('locSearchText').value) + '&o=' + $page;
			ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
		}//end if
	}//end searchLocations()

	function setLocation($id, $name){
		document.getElementById('locPreset').value = $id;
		document.getElementById('locPresetName').value = $name;
		togLocation();
		if($id == 0){
			document.getElementById('locSearchResults').innerHTML = '';
			document.getElementById('locSearchText').value = '';
		}//end if
		buildTweet();
	}//end setLocation

	function buildTweet(){
		var twMax = 63;
		var tweetMe = twRecur = '';
		var twTitle = document.getElementById('eventTitle').value;
		var twLoc = (document.getElementById('locPreset').value > 0) ? document.getElementById('locPresetName').value : document.getElementById('locName').value;
		var twTime = (document.getElementById('startTimeAMPM') != null) ?
						document.getElementById('startTimeHour').value + ':' + document.getElementById('startTimeMins').value + ' ' + document.getElementById('startTimeAMPM').value :
						document.getElementById('startTimeHour').value + ':' + document.getElementById('startTimeMins').value;
		
		if(document.getElementById('recurCheck')){
			twRecur = (document.getElementById('recurCheck').checked) ? ' - ' + document.getElementById('recurEndDate').value : '';
			twMax = (document.getElementById('recurCheck').checked) ? 53 : twMax;
		}//end if
		
		if(twTitle.length + twLoc.length > twMax){
			twTitle = twTitle.substr(0,twMax-25);
			twLoc = twLoc.substr(0,22);
		}//end if

		tweetMe = tweetPrefix + ' ' + twTitle + ' @ ' + twLoc + ' - ' + twTime +
				' <?php echo $hc_lang_event['On'];?> ' + document.getElementById('eventDate').value + twRecur;
		
		document.getElementById('tweetThis').value = tweetMe;
	}//end buildTweet

	function chkReg(){
		document.getElementById('eventRegistration').selectedIndex = (document.getElementById('doEventbrite').checked) ? 2 : 0;
		togRegistration();
	}//end if

	function togTicketPrice(which,type){
		document.getElementById('price' + which).disabled = (type == 1) ? true : false;
	}//end if