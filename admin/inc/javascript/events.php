<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	echo '
	function chngClock(obj,inc,max){if(obj.disabled == false){var val = (!isNaN(obj.value)) ? parseInt(obj.value,10):0;val += inc;if(max == 59){if(val > max) val = 0;if(val < 0) val = max + 1 - Math.abs(val);} else {if(val > max) val = '.$hc_time['minHr'].';if(val < '.$hc_time['minHr'].') val = max;}obj.value = (val < 10) ? "0" + val : val;}}
	function togOverride(){
		var inputs = (document.getElementById("overridetime").checked) ? true : false;
		document.getElementById("startTimeHour").disabled = inputs;
		document.getElementById("startTimeMins").disabled = inputs;
		document.getElementById("ignoreendtime").disabled = inputs;
		document.getElementById("specialtimeall").disabled = (inputs == true) ? false : true;
		document.getElementById("specialtimetbd").disabled = (inputs == true) ? false : true;
		if('.$hc_time['input'].' == 12)
			document.getElementById("startTimeAMPM").disabled = inputs;
		if(document.getElementById("ignoreendtime").checked || inputs)
			togEndTime(true);
		else
			togEndTime(false);
	}
	function togEndTime(disable){		
		document.getElementById("endTimeHour").disabled = disable;
		document.getElementById("endTimeMins").disabled = disable;
		if('.$hc_time['input'].' == 12)
			document.getElementById("endTimeAMPM").disabled = disable;
	}
	function togRegistration(){
		var regChk = document.getElementById("rsvp_type").value; 
		document.getElementById("rsvp_space").disabled = (regChk == 1) ? false : true;
		document.getElementById("openDate").disabled = (regChk == 1) ? false : true;
		document.getElementById("closeDate").disabled = (regChk == 1) ? false : true;
		document.getElementById("rsvpFor").disabled = (regChk == 1) ? false : true;
		document.getElementById("rsvpEmail").disabled = (regChk == 1) ? false : true;
		document.getElementById("rsvp").style.display = (regChk == 1) ? "block" : "none";
		document.getElementById("doEventbrite").checked = (regChk == 2) ? true : false;
		document.getElementById("eventbrite").style.display = (regChk == 2) ? "block" : "none";
	}
	function chkDate(){
		var err = "";
		err += reqField(document.getElementById("eventDate"),"'.$hc_lang_event['Valid14'].'\n");
		err += validDate(document.getElementById("eventDate"),"'.$hc_cfg[51].'","'.$hc_lang_event['Valid24'].' '.strtoupper($hc_cfg[51]).'\n");
		if(validDateBefore("'.strftime($hc_cfg[24],strtotime(SYSDATE)).'",document.getElementById("eventDate").value,"'.$hc_cfg[51].'","error") != ""){
			if(!confirm("'.$hc_lang_event['Valid18'].'\\n\\n          '.$hc_lang_event['Valid19'].'\\n          '.$hc_lang_event['Valid20'].'"))
				return -1;
		}
		return err;
	}
	function buildSocial(){
		var twMax = 63;
		var socialStr = twRecur = "";
		var twTitle = document.getElementById("eventTitle").value;
		var twLoc = (document.getElementById("locPreset").value > 0) ? document.getElementById("locPresetName").value : document.getElementById("locName").value;
		var twTime = "";
		
		if(!document.getElementById("overridetime").checked){
			twTime = (document.getElementById("startTimeAMPM") != null) ?
							" - " + document.getElementById("startTimeHour").value + ":" + document.getElementById("startTimeMins").value + " " + document.getElementById("startTimeAMPM").value :
							" - " + document.getElementById("startTimeHour").value + ":" + document.getElementById("startTimeMins").value;
		}
		if(document.getElementById("recurCheck")){
			twRecur = (document.getElementById("recurCheck").checked) ? " - " + document.getElementById("recurEndDate").value : "";
			twMax = (document.getElementById("recurCheck").checked) ? 53 : twMax;
		}
		if(twTitle.length + twLoc.length > twMax){
			twTitle = twTitle.substr(0,twMax-25);
			twLoc = twLoc.substr(0,22);
		}
		twDate = (document.getElementById("eventDate")) ?
			document.getElementById("eventDate").value + twRecur :
			document.getElementById("grpDate").value;
		socialStr = tweetPrefix + " " + twTitle + " @ " + twLoc + twTime +
				" '.$hc_lang_event['On'].' " + twDate;
		
		return socialStr;
	}
	function buildT(){
		document.getElementById("tweetThis").value = buildSocial();
	}
	function buildF(){
		document.getElementById("fbThis").value = buildSocial();
	}
	function chkReg(){
		document.getElementById("rsvp_type").selectedIndex = (document.getElementById("doEventbrite").checked) ? 2 : 0;
		togRegistration();
	}
	function togTicketPrice(which,type){
		document.getElementById("price" + which).disabled = (type == 1) ? true : false;
	}';
?>