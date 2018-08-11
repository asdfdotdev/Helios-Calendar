<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	echo '
	function chngClock(obj,inc,max){if(obj.disabled == false){var val = (!isNaN(obj.value)) ? parseInt(obj.value,10):0;val += inc;if(max == 59){if(val > max) val = 0;if(val < 0) val = max + 1 - Math.abs(val);} else {if(val > max) val = '.$hc_time['minHr'].';if(val < '.$hc_time['minHr'].') val = max;}obj.value = (val < 10) ? "0" + val : val;}}
	function toggleMe(who){who.style.display == "none" ? who.style.display = "block":who.style.display = "none";return false;}
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
		document.getElementById("eventRegAvailable").disabled = (document.getElementById("eventRegistration").value == 1) ? false : true;
		document.getElementById("doEventbrite").checked = (document.getElementById("eventRegistration").value == 2) ? true : false;
		document.getElementById("eventbrite").style.display = (document.getElementById("eventRegistration").value == 2) ? "block" : "none";
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
	function buildTweet(){
		var twMax = 63;
		var tweetMe = twRecur = "";
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
		tweetMe = tweetPrefix + " " + twTitle + " @ " + twLoc + twTime +
				" '.$hc_lang_event['On'].' " + twDate;
		document.getElementById("tweetThis").value = tweetMe;
	}
	function chkReg(){
		document.getElementById("eventRegistration").selectedIndex = (document.getElementById("doEventbrite").checked) ? 2 : 0;
		togRegistration();
	}
	function togTicketPrice(which,type){
		document.getElementById("price" + which).disabled = (type == 1) ? true : false;
	}';
?>