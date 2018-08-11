<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Settings Updated Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Settings", "Your Helios Settings", "Use the form below to configure your Helios Calendar settings.");
	
	$passApprove = "Your event has been approved and is now available on our website. We hope you continue to use our calendar and submit more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	$passDecline = "Your event has been declined and will not be available on our website. Please do not let this discourage you from submiting more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43) ORDER BY PkID");
	$submit = cOut(mysql_result($result,0,0));
	$maxDisplay = cOut(mysql_result($result,1,0));
	$passApprove = cOut(mysql_result($result,2,0));
	$passDecline = cOut(mysql_result($result,3,0));
	$driving = cOut(mysql_result($result,4,0));
	$weather = cOut(mysql_result($result,5,0));
	$mostPopular = cOut(mysql_result($result,6,0));
	$browsePast = cOut(mysql_result($result,7,0));
	$maxShow = cOut(mysql_result($result,8,0));
	$fillMax = cOut(mysql_result($result,9,0));
	$dateFormat = cOut(mysql_result($result,10,0));
	$showTime = cOut(mysql_result($result,11,0));
	$state = cOut(mysql_result($result,12,0));
	$calStartDay = cOut(mysql_result($result,13,0));
	$timeFormat = cOut(mysql_result($result,14,0));
	$popDateFormat = cOut(mysql_result($result,15,0));
	$emailNotice = cOut(mysql_result($result,16,0));
	$googleKey = cOut(mysql_result($result,17,0));
	$emapZoom = cOut(mysql_result($result,18,0));
	$yahooKey = cOut(mysql_result($result,19,0));
	$userCat = cOut(mysql_result($result,20,0));
	$WYSIWYG = cOut(mysql_result($result,21,0));
	$timeInput = cOut(mysql_result($result,22,0));
	$captchas = explode(",", cOut(mysql_result($result,23,0)));
	$series = cOut(mysql_result($result,24,0));
	$browseType = cOut(mysql_result($result,25,0));	
	$timezoneOffset = cOut(mysql_result($result,26,0));
	$eventfulKey = cOut(mysql_result($result,27,0));
	$eventfulUser = cOut(mysql_result($result,28,0));
	$eventfulPass = cOut(mysql_result($result,29,0));
	$eventfulSig = cOut(mysql_result($result,30,0));
	$subLimit = cOut(mysql_result($result,31,0));
	$lmapZoom = cOut(mysql_result($result,32,0));
	$lmapLat = cOut(mysql_result($result,33,0));
	$lmapLon = cOut(mysql_result($result,34,0));
?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		var dirty = 0;
		var warn = 'Settings could not be saved for the following reasons:';
		
		if(document.frm.subLimit.value == ''){
			dirty = 1;
			warn = warn + '\n*Submission Limit is Required';
		} else {
			if(isNaN(document.frm.subLimit.value)){
				dirty = 1;
				warn = warn + '\n*Submission Limit Must Be Numeric';
			} else {
				if(document.frm.subLimit.value < 1){
					dirty = 1;
					warn = warn + '\n*Submission Limit Must Be Greater Than 0';
				}//end if
			}//end if
		}//end if
		
		if(document.frm.maxRSS.value == ''){
			dirty = 1;
			warn = warn + '\n*RSS List Size Value is Required';
		} else {
			if(isNaN(document.frm.maxRSS.value)){
				dirty = 1;
				warn = warn + '\n*RSS List Size Value Must Be Numeric';
			} else if(document.frm.maxRSS.value < 1) {
				dirty = 1;
				warn = warn + '\n*RSS List Size Value Must Be Greater Than 0';
			}//end if
		}//end if
		
		if(document.frm.mostPopular.value == ''){
			dirty = 1;
			warn = warn + '\n*Most Popular List Size Value is Required';
		} else {
			if(isNaN(document.frm.mostPopular.value)){
				dirty = 1;
				warn = warn + '\n*Most Popular List Size Value Must Be Numeric';
			} else if(document.frm.mostPopular.value < 1) {
				dirty = 1;
				warn = warn + '\n*Most Popular List Size Value Must Be Greater Than 0';
			}//end if
		}//end if
		
		if(document.frm.display.value == ''){
			dirty = 1;
			warn = warn + '\n*Billboard List Size Value is Required';
		} else {
			if(isNaN(document.frm.display.value)){
				dirty = 1;
				warn = warn + '\n*Billboard List Size Value Must Be Numeric';
			} else {
				if(document.frm.display.value < 1){
					dirty = 1;
					warn = warn + '\n*Billboard List Size Value Must Be Greater Than 0';
				}//end if
			}//end if
		}//end if
		
		if(document.frm.dateFormat.value == ''){
			dirty = 1;
			warn = warn + '\n*Date Output Format is Required';
		}//end if
		
		if(document.frm.timeFormat.value == ''){
			dirty = 1;
			warn = warn + '\n*Time Output Format is Required';
		}//end if
		
		if(isNaN(document.frm.lmapLat.value)){
			dirty = 1;
			warn = warn + '\n*Location Browse Map Center Latitude Must Be Numeric';
		}//end if
		
		if(isNaN(document.frm.lmapLon.value)){
			dirty = 1;
			warn = warn + '\n*Location Browse Map Center Longitude Must Be Numeric';
		}//end if
		
		if(document.frm.efUser.value != '' && document.frm.efPass.value == '' && (0 == <?php echo strlen($eventfulPass);?>)){
			dirty = 1;
			warn = warn + '\n*eventful Password Required';
		}//end if
		
		if(document.frm.efPass.value != ''){
			if(document.frm.efPass.value != document.frm.efPass2.value){
				dirty = 1;
				warn = warn + '\n*eventful Passwords Do Not Match';
			}//end if
		}//end if
		
		if(document.frm.defaultApprove.value == ''){
			dirty = 1;
			warn = warn + '\n*Default Event Approve Email Message is Required';
		}//end if
		
		if(document.frm.defaultDecline.value == ''){
			dirty = 1;
			warn = warn + '\n*Default Event Decline Email Message is Required';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and try again.');
			return false;
		}//end if
		
	}//end chkFrm()
	
	function togSubmit(){
		if(document.frm.allowsubmit.value == 1){
			document.frm.pubCat.disabled = false;
			document.frm.subLimit.disabled = false;
		} else {
			document.frm.pubCat.disabled = true;
			document.frm.subLimit.disabled = true;
		}//end if
	}//end togSubmit()
	//-->
	</script>
	<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot . "/components/SettingsGeneralAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend>General</legend>
		<div class="frmOpt">
			<label for="browseType" class="settingsLabel">Browse Type:</label>
			<select name="browseType" id="browseType">
				<option <?php if($browseType == 0){echo "selected=\"selected\"";}?> value="0">Weekly Browse</option>
				<option <?php if($browseType == 1){echo "selected=\"selected\"";}?> value="1">Monthly Browse</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Browse Type", "<b>Weekly Browse</b>: Display events from current day through following Sunday. Navigation moves from week to week starting with Monday.<br /><br /><b>Monthly Browse:</b> Display events from current day through the last day of the current month. Navigation moves from month to month starting with the Monday of the week the first day of the month is in.");?>
		</div>
		<div class="frmOpt">
			<label for="browsePast" class="settingsLabel">Event Browse:</label>
			<select name="browsePast" id="browsePast">
				<option <?php if($browsePast == 0){echo "selected=\"selected\"";}?> value="0">Current Only</option>
				<option <?php if($browsePast == 1){echo "selected=\"selected\"";}?> value="1">All Dates</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Event Browse Setting", "<b>Current Only:</b> Allow users to browse events that occur today or in the future.<br /><br /><b>All Dates:</b> Allow users to browse events on all dates, including past events.");?>
		</div>
		<div class="frmOpt">
			<label for="locState" class="settingsLabel">Default <?php echo HC_StateLabel;?>:</label>
	<?php 	include('../events/includes/' . HC_StateInclude);	?>
		</div>
		<div class="frmOpt">
			<label for="calStartDay" class="settingsLabel">Mini-Cal Start Day:</label>
			<select name="calStartDay" id="calStartDay">
				<option <?php if($calStartDay == 0){echo "selected=\"selected\"";}?> value="0">Sunday</option>
				<option <?php if($calStartDay == 1){echo "selected=\"selected\"";}?> value="1">Monday</option>
				<option <?php if($calStartDay == 2){echo "selected=\"selected\"";}?> value="2">Tuesday</option>
				<option <?php if($calStartDay == 3){echo "selected=\"selected\"";}?> value="3">Wednesday</option>
				<option <?php if($calStartDay == 4){echo "selected=\"selected\"";}?> value="4">Thursday</option>
				<option <?php if($calStartDay == 5){echo "selected=\"selected\"";}?> value="5">Friday</option>
				<option <?php if($calStartDay == 6){echo "selected=\"selected\"";}?> value="6">Saturday</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="emailNotice" class="settingsLabel">New Event Admin Notice:</label>
			<select name="emailNotice" id="emailNotice">
				<option <?php if($emailNotice == 0){echo "selected=\"selected\"";}?> value="0">Do Not Send Email Notice</option>
				<option <?php if($emailNotice == 1){echo "selected=\"selected\"";}?> value="1">Send Email Notice</option>
			</select>
			&nbsp;<?php appInstructionsIcon("New Event Admin Notice", "Helios can send an email notice each time a new event, or event series, is submitted through the public event submission form. Set to <b>Send Email Notice</b> to receive notices for new events.");?>
		</div>
		<div class="frmOpt">
			<label for="WYSIWYG" class="settingsLabel">WYSIWYG Editor:</label>
			<select name="WYSIWYG" id="WYSIWYG">
				<option <?php if($WYSIWYG == 1){echo "selected=\"selected\"";}?> value="1">Use TinyMCE</option>
				<option <?php if($WYSIWYG == 0){echo "selected=\"selected\"";}?> value="0">Disable WYSIWYG Editor</option>
			</select>
			&nbsp;<?php appInstructionsIcon("WYSIWYG Editor", "Helios include the popular \'<b>W</b>hat <b>Y</b>ou <b>S</b>ee <b>I</b>s <b>W</b>hat <b>Y</b>ou <b>G</b>et\' Editor TinyMCE. Should you prefer to input your event information in plain text format you may disable TinyMCE by using this setting.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Spam Prevention CAPTCHAs <?php appInstructionsIcon("Spam Prevention CAPTCHA", "Helios offers optional CAPTCHA spam prevention on the public forms listed below. When active the form will require users to input a randomly generated string of six (6) characters to indicate they are human and not a spam bot.<br /><br />To activate CAPTCHA for a form <b>check the box</b> next to its name.");?></legend>
<?php	$capDisplay = "";
		if(!function_exists('imagecreate')){
			$capDisplay = " disabled=\"disabled\"";
			echo "<span style=\"font-weight:bold;color:#DC143C;\">GD Library Unavailable. CAPTCHA Cannot Be Used.</span><br /><br />";
		} else {	?>
		<div class="frmOpt">
			<label class="settingsLabel">Active Captcha Protection:</label>
			<label for="capID_1" class="category"><input <?php echo $capDisplay; if(in_array(1, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_1" type="checkbox" value="1" class="noBorderIE" />Public Event Submission</label>
			<label for="capID_2" class="category"><input <?php echo $capDisplay; if(in_array(2, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_2" type="checkbox" value="2" class="noBorderIE" />Email Event to Friend</label>
		</div>
		<br />
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_3" class="category"><input <?php echo $capDisplay; if(in_array(3, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_3" type="checkbox" value="3" class="noBorderIE" />Register for Event</label>
			<label for="capID_4" class="category"><input <?php echo $capDisplay; if(in_array(4, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_4" type="checkbox" value="4" class="noBorderIE" />Newsletter Sign-Up</label>
		</div>
<?php	}//end if	?>
	</fieldset>
	<br />
	<fieldset>
		<legend>Public Event Submission</legend>
		<div class="frmOpt">
			<label for="allowsubmit" class="settingsLabel">Public Submission:</label>
			<select name="allowsubmit" id="allowsubmit" onchange="togSubmit();">
				<option <?php if($submit == 1){echo "selected=\"selected\"";}?> value="1">ON</option>
				<option <?php if($submit == 0){echo "selected=\"selected\"";}?> value="0">OFF</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="subLimit" class="settingsLabel">Session Submission Limit:</label>
			<input name="subLimit" id="subLimit" type="text" size="2" maxlength="2" value="<?php echo $subLimit;?>" <?php if($submit == 0){echo "disabled=\"disabled\"";}?> />
			&nbsp;<?php appInstructionsIcon("Session Submission Limit", "Maximum number of event submissions a single user can make each session. A lower value will help reduce and discourage spam. User total is reset to 0 each visit.<br /><br /><b>Caution:</b> Setting this value too low may negatively affect honest users and limit \'ham\' submissions.<br /><br />Recomended/Default Setting: 75");?>
		</div>
		<div class="frmOpt">
			<label for="pubCat" class="settingsLabel">Public Event Categories:</label>
			<select name="pubCat" id="pubCat" <?php if($submit == 0){echo "disabled=\"disabled\"";}?>>
				<option <?php if($userCat == 0){echo "selected=\"selected\"";}?> value="0">Disable Category Selection</option>
				<option <?php if($userCat == 1){echo "selected=\"selected\"";}?> value="1">Allow Category Selection</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Public Category Selection", "Category selection by public users will be optional. As with all other settings category assignment will be available to admin users during event approval for final review/change.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Billboard, Most Popular Lists &amp; RSS</legend>
		<div class="frmOpt">
			<label for="maxRSS" class="settingsLabel">RSS Max List Size:</label>
			<input name="maxRSS" id="maxRSS" type="text" size="2" maxlength="2" value="<?php echo $maxDisplay;?>" />
		</div>
		<div class="frmOpt">
			<label for="mostPopular" class="settingsLabel">Most Popular Max List Size:</label>
			<input name="mostPopular" id="mostPopular" type="text" size="2" maxlength="2" value="<?php echo $mostPopular;?>" />
		</div>
		<div class="frmOpt">
			<label for="display" class="settingsLabel">Billboard Max List Size:</label>
			<input name="display" id="display" type="text" size="2" maxlength="2" value="<?php echo $maxShow;?>" />
		</div>
		<div class="frmOpt">
			<label for="fill" class="settingsLabel">Auto Fill Billboard:</label>
			<select name="fill" id="fill">
				<option <?php if($fillMax == 1){echo "selected=\"selected\"";}?> value="1">On</option>
				<option <?php if($fillMax == 0){echo "selected=\"selected\"";}?> value="0">Off</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Auto Fill Billboard", "When <b>On</b> Helios will append a list of upcoming events to the bottom of your Billboard if there are fewer Billboard events then the \'Billboard Max List Size\' set above.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Billboard &amp; RSS Event Series:</label>
			<select name="series" id="series">
				<option <?php if($series == 1){echo "selected=\"selected\"";}?> value="1">Show All Events</option>
				<option <?php if($series == 0){echo "selected=\"selected\"";}?> value="0">Show Next Event Only</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Billboard &amp; RSS Event Series", "<b>Show All Events :</b> Show all events in a series as part of the Billboard and RSS lists.<br /><br /><b>Show Next Event Only :</b> Limit Event Series Display on the Billboard and RSS lists to only the next occuring event, hiding the remainder of the series until the next occuring passes.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Show Start Time:</label>
			<label for="showtime" class="radioWide"><input <?php if($showTime == 1){echo "checked=\"checked\"";}?> name="showtime" id="showtime" type="checkbox" value="" class="noBorderIE" />(Billboard &amp; Most Popular Lists)</label>
			&nbsp;
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Date &amp; Time Formats ( <a href="http://www.php.net/date" class="main" target="_blank">Click Here For Format Help</a> )</legend>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Current Server Time:</label>
			<b><?php echo date($dateFormat . " " . $timeFormat);?></b>
		</div>
<?php
	if($timezoneOffset != 0){	?>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Current Helios Time:</label>
		<?php 
			$hourOffset = date("G");
			if($timezoneOffset > 0){
				$hourOffset = $hourOffset + abs($timezoneOffset);
			} else {
				$hourOffset = $hourOffset - abs($timezoneOffset);
			}//end if
			echo "<b>" . date($dateFormat . " " . $timeFormat, mktime($hourOffset, date("i"), date("s"), date("m"), date("d"), date("Y"))) . "</b>";
			?>
		</div>
<?php
	}//end if	?>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Offset Server Time:</label>
			<select name="offsetTimezone" id="offsetTimezone">
				<option <?php if($timezoneOffset == "-12"){echo "selected=\"selected\"";}?> value="-12">-12 hours</option>
				<option <?php if($timezoneOffset == "-11"){echo "selected=\"selected\"";}?> value="-11">-11 hours</option>
				<option <?php if($timezoneOffset == "-10"){echo "selected=\"selected\"";}?> value="-10">-10 hours</option>
				<option <?php if($timezoneOffset == "-9"){echo "selected=\"selected\"";}?> value="-9">-09 hours</option>
				<option <?php if($timezoneOffset == "-8"){echo "selected=\"selected\"";}?> value="-8">-08 hours</option>
				<option <?php if($timezoneOffset == "-7"){echo "selected=\"selected\"";}?> value="-7">-07 hours</option>
				<option <?php if($timezoneOffset == "-6"){echo "selected=\"selected\"";}?> value="-6">-06 hours</option>
				<option <?php if($timezoneOffset == "-5"){echo "selected=\"selected\"";}?> value="-5">-05 hours</option>
				<option <?php if($timezoneOffset == "-4"){echo "selected=\"selected\"";}?> value="-4">-04 hours</option>
				<option <?php if($timezoneOffset == "-3"){echo "selected=\"selected\"";}?> value="-3">-03 hours</option>
				<option <?php if($timezoneOffset == "-2"){echo "selected=\"selected\"";}?> value="-2">-02 hours</option>
				<option <?php if($timezoneOffset == "-1"){echo "selected=\"selected\"";}?> value="-1">-01 hour</option>
				<option <?php if($timezoneOffset == "0"){echo "selected=\"selected\"";}?> value="0">Use Server Time</option>
				<option <?php if($timezoneOffset == "1"){echo "selected=\"selected\"";}?> value="1">+01 hour</option>
				<option <?php if($timezoneOffset == "2"){echo "selected=\"selected\"";}?> value="2">+02 hours</option>
				<option <?php if($timezoneOffset == "3"){echo "selected=\"selected\"";}?> value="3">+03 hours</option>
				<option <?php if($timezoneOffset == "4"){echo "selected=\"selected\"";}?> value="4">+04 hours</option>
				<option <?php if($timezoneOffset == "5"){echo "selected=\"selected\"";}?> value="5">+05 hours</option>
				<option <?php if($timezoneOffset == "6"){echo "selected=\"selected\"";}?> value="6">+06 hours</option>
				<option <?php if($timezoneOffset == "7"){echo "selected=\"selected\"";}?> value="7">+07 hours</option>
				<option <?php if($timezoneOffset == "8"){echo "selected=\"selected\"";}?> value="8">+08 hours</option>
				<option <?php if($timezoneOffset == "9"){echo "selected=\"selected\"";}?> value="9">+09 hours</option>
				<option <?php if($timezoneOffset == "10"){echo "selected=\"selected\"";}?> value="10">+10 hours</option>
				<option <?php if($timezoneOffset == "11"){echo "selected=\"selected\"";}?> value="11">+11 hours</option>
				<option <?php if($timezoneOffset == "12"){echo "selected=\"selected\"";}?> value="12">+12 hours</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Offset Server Time", "If your Helios Calendar is hosted in a timezone that varies from your desired timezone you may use the Offset Server Timez settings to correct for this. Change the setting to adjust for the difference between the two timezones.<br /><br /><b>For Example:</b> You maintain a website for a city/state/region within the Central Timezone (UTC -6) but your server is in the Eastern Timezone (UTC -5). You would set the Offset Server Timezone to -1 to move 1 timezone west from Eastern to Central.<br /><br /><b>Note:</b> Daylight Saving Time (DST) should be accounted for by your server preventing the need to change this setting, however, if DST rules apply differently between your server timezone and offset timezone manual adjustments may be needed.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Date Input Format:</label>
			<select name="popDateFormat" id="popDateFormat">
				<option <?php if($popDateFormat == "m/d/Y"){echo "selected=\"selected\"";}?> value="m/d/Y">m/d/y</option>
				<option <?php if($popDateFormat == "d/m/Y"){echo "selected=\"selected\"";}?> value="d/m/Y">d/m/y</option>
				<option <?php if($popDateFormat == "Y/m/d"){echo "selected=\"selected\"";}?> value="Y/m/d">y/m/d</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Date Input Format", "This format will be used in the JavaScript date selection popup window used in the Event Add/Edit/Search/Submit forms.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Date Output Format:</label>
			<input name="dateFormat" id="dateFormat" type="text" value="<?php echo $dateFormat;?>" size="10" maxlength="20" />
			&nbsp;<?php appInstructionsIcon("Date Output Format", "This format will be used for all date output in Helios except the JavaScript date selection. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit: <b>www.PHP.net/date</b>");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Time Input Format:</label>
			<select name="timeInput" id="timeInput">
				<option <?php if($timeInput == "24"){echo "selected=\"selected\"";}?> value="24">24 Hour (<?php echo date("H:i");?>)</option>
				<option <?php if($timeInput == "12"){echo "selected=\"selected\"";}?> value="12">12 Hour (<?php echo date("h:i A");?>)</option>
			</select>
			&nbsp;<?php appInstructionsIcon("Time Input Format", "This format will be used in the event forms used in the Event Add/Edit/Pending/Submit forms.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Time Output Format:</label>
			<input name="timeFormat" id="timeFormat" type="text" value="<?php echo $timeFormat;?>" size="10" maxlength="20" />
			&nbsp;<?php appInstructionsIcon("Time Output Format", "This format will be used for all time output in Helios. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit: <b>www.PHP.net/date</b>");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Weather &amp; Map Links</legend>
		<div class="frmOpt">
			<label for="weather" class="settingsLabel">Weather Link:</label>
			<select name="weather" id="weather">
				<option <?php if($weather == 1){echo "selected=\"selected\"";}?> value="1">(US) AccuWeather</option>
				<option <?php if($weather == 0){echo "selected=\"selected\"";}?> value="0">(US) Weather.com</option>
				<option <?php if($weather == 3){echo "selected=\"selected\"";}?> value="3">(US) Yahoo Weather</option>
				<option <?php if($weather == 2){echo "selected=\"selected\"";}?> value="2">(US) Weather Underground</option>
				<option <?php if($weather == 6){echo "selected=\"selected\"";}?> value="6">(Australia) weather.News.com.au</option>
				<option <?php if($weather == 7){echo "selected=\"selected\"";}?> value="7">(Australia) Weatherzone.com.au</option>
				<option <?php if($weather == 4){echo "selected=\"selected\"";}?> value="4">(Canada) AccuWeather</option>
				<option <?php if($weather == 5){echo "selected=\"selected\"";}?> value="5">(UK) Weather.co.uk</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="driving" class="settingsLabel">Map Link:</label>
			<select name="driving" id="driving">
				<option <?php if($driving == 0){echo "selected=\"selected\"";}?> value="0">(US &amp; Can) Google Maps</option>
				<option <?php if($driving == 1){echo "selected=\"selected\"";}?> value="1">(US &amp; Can) Mapquest</option>
				<option <?php if($driving == 2){echo "selected=\"selected\"";}?> value="2">(US &amp; Can) Yahoo Maps</option>
				<option <?php if($driving == 4){echo "selected=\"selected\"";}?> value="4">(Australia) Google Maps</option>
				<option <?php if($driving == 3){echo "selected=\"selected\"";}?> value="3">(UK) Google Maps</option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Location Maps</legend>
		<b>Note:</b> Use of the Google and Yahoo API's are subject to their <b>Terms of Use</b> ( <a href="http://www.google.com/apis/maps/terms.html" class="main" target="_blank">G</a> ) ( <a href="http://developer.yahoo.com/terms/" class="main" target="_blank">Y</a> )<br />
		and requires a unique key for each, <b>DO NOT</b> share your key or use any key but your own.
		<br /><br />
		<div class="frmOpt">
			<label for="googleAPI" class="settingsLabel">Google Map Key (<a href="http://www.google.com/apis/maps/signup.html" class="main" target="_blank">Sign Up</a>):</label>
			<input name="googleAPI" id="googleAPI" type="text" value="<?php echo $googleKey;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon("Google Map Key", "Enter the API key generated by Google. To obtain an API key click <b>Sign Up</b> and complete the form.");?>
		</div>
		<div class="frmOpt">
			<label for="yahooAPI" class="settingsLabel">Yahoo API Key (<a href="http://api.search.yahoo.com/webservices/register_application" class="main" target="_blank">Sign Up</a>):</label>
			<input name="yahooAPI" id="yahooAPI" type="text" value="<?php echo $yahooKey;?>" size="35" maxlength="255" />
			&nbsp;<?php appInstructionsIcon("Yahoo API Key", "Enter the API key generated by Yahoo. To obtain an API key click <b>Sign Up</b> and complete the form.");?>
		</div>
		<div class="frmOpt">
			<label for="emapZoom" class="settingsLabel">Event Detail Map Zoom:</label>
			<select name="emapZoom" id="emapZoom">
				<option <?php if($emapZoom == 1){echo "selected=\"selected\"";}?> value="1">01 - World</option>
				<option <?php if($emapZoom == 2){echo "selected=\"selected\"";}?> value="2">02</option>
				<option <?php if($emapZoom == 3){echo "selected=\"selected\"";}?> value="3">03 - Country</option>
				<option <?php if($emapZoom == 4){echo "selected=\"selected\"";}?> value="4">04</option>
				<option <?php if($emapZoom == 5){echo "selected=\"selected\"";}?> value="5">05 - State</option>
				<option <?php if($emapZoom == 6){echo "selected=\"selected\"";}?> value="6">06</option>
				<option <?php if($emapZoom == 7){echo "selected=\"selected\"";}?> value="7">07</option>
				<option <?php if($emapZoom == 8){echo "selected=\"selected\"";}?> value="8">08</option>
				<option <?php if($emapZoom == 9){echo "selected=\"selected\"";}?> value="9">09</option>
				<option <?php if($emapZoom == 10){echo "selected=\"selected\"";}?> value="10">10 - City</option>
				<option <?php if($emapZoom == 11){echo "selected=\"selected\"";}?> value="11">11</option>
				<option <?php if($emapZoom == 12){echo "selected=\"selected\"";}?> value="12">12</option>
				<option <?php if($emapZoom == 13){echo "selected=\"selected\"";}?> value="13">13</option>
				<option <?php if($emapZoom == 14){echo "selected=\"selected\"";}?> value="14">14</option>
				<option <?php if($emapZoom == 15){echo "selected=\"selected\"";}?> value="15">15 - Street</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="lmapZoom" class="settingsLabel">Location Browse Map Zoom:</label>
			<select name="lmapZoom" id="lmapZoom">
				<option <?php if($lmapZoom == 1){echo "selected=\"selected\"";}?> value="1">01 - World</option>
				<option <?php if($lmapZoom == 2){echo "selected=\"selected\"";}?> value="2">02</option>
				<option <?php if($lmapZoom == 3){echo "selected=\"selected\"";}?> value="3">03 - Country</option>
				<option <?php if($lmapZoom == 4){echo "selected=\"selected\"";}?> value="4">04</option>
				<option <?php if($lmapZoom == 5){echo "selected=\"selected\"";}?> value="5">05 - State</option>
				<option <?php if($lmapZoom == 6){echo "selected=\"selected\"";}?> value="6">06</option>
				<option <?php if($lmapZoom == 7){echo "selected=\"selected\"";}?> value="7">07</option>
				<option <?php if($lmapZoom == 8){echo "selected=\"selected\"";}?> value="8">08</option>
				<option <?php if($lmapZoom == 9){echo "selected=\"selected\"";}?> value="9">09</option>
				<option <?php if($lmapZoom == 10){echo "selected=\"selected\"";}?> value="10">10 - City</option>
				<option <?php if($lmapZoom == 11){echo "selected=\"selected\"";}?> value="11">11</option>
				<option <?php if($lmapZoom == 12){echo "selected=\"selected\"";}?> value="12">12</option>
				<option <?php if($lmapZoom == 13){echo "selected=\"selected\"";}?> value="13">13</option>
				<option <?php if($lmapZoom == 14){echo "selected=\"selected\"";}?> value="14">14</option>
				<option <?php if($lmapZoom == 15){echo "selected=\"selected\"";}?> value="15">15 - Street</option>
			</select>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Location Browse Map Center:</label>
			Latitude:&nbsp;<input name="lmapLat" id="lmapLat" type="text" value="<?php echo $lmapLat;?>" size="10" maxlength="25" />&nbsp;&nbsp;Longitude:&nbsp;<input name="lmapLon" id="lmapLon" type="text" value="<?php echo $lmapLon;?>" size="10" maxlength="25" />
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<a href="http://www.google.com/search?q=free+geocoding" class="main" target="_blank">Manual Geocode Lookup</a>
		</div>
	</fieldset><br />
	<fieldset>
		<legend><b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b> Settings&nbsp;&nbsp;&nbsp;[ <a href="http://about.eventful.com/" class="eventMain" target="_blank">About eventful</a> ]</legend>
		<b>Note:</b> Use of the eventful API is subject to their <a href="http://api.eventful.com/terms" class="main" target="_blank"><b>Terms of Use</a></b>
		and requires<br />a unique API key, <b>DO NOT</b> share your key or use any key but your own.
		<br /><br />
		<div class="frmOpt">
			<label for="eventfulAPI" class="settingsLabel">eventful API Key (<a href="http://api.eventful.com/keys/" class="main" target="_blank">Sign Up</a>):</label>
			<input name="eventfulAPI" id="eventfulAPI" type="text" value="<?php echo $eventfulKey;?>" size="45" maxlength="255" />
			&nbsp;<?php appInstructionsIcon("eventful API Key", "Enter the API key generated by eventful. To obtain an API key click <b>Sign Up</b> and complete the form.");?>
		</div>
		<div class="frmOpt">
			<label for="efUser" class="settingsLabel">Username:</label>
			<input name="efUser" id="efUser" type="text" value="<?php echo $eventfulUser;?>" size="20" maxlength="150" />
			&nbsp;<?php appInstructionsIcon("eventful Username &amp; Password", "Your eventful username and password are required to make submissions to eventful\'s website.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Password:</label>
		<?php
			for($x=0;$x<strlen($eventfulPass);$x++){
				echo "*";
			}//end for	?>&nbsp;
		</div>
		<div class="frmOpt">
			<label for="efPass" class="settingsLabel">New Password:</label>
			<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
		</div>
		<div class="frmOpt">
			<label for="efPass2" class="settingsLabel">Confirm Password:</label>
			<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
		</div>
		<div class="frmOpt">
			<label for="efSignature" class="settingsLabel">Signature:</label>
			<textarea name="efSignature" id="efSignature" style="width:320px;height:45px;"><?php echo $eventfulSig;?></textarea>
			&nbsp;<?php appInstructionsIcon("Signature", "Your Signature is a custom footer that will be appended to the end of all your event and location descriptions submitted to eventful. Signatures must be text only, all HTML will be removed for security.<br /><br /><b>Note:</b> Your Helios Calendar URL will be included as a link in your signature automatically.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Pending Event Messages</legend>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Approve Message:</label>
			<textarea autocomplete="off" id="defaultApprove" name="defaultApprove" style="width: 350px; height: 125px"><?php echo $passApprove;?></textarea>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Decline Message:</label>
			<textarea autocomplete="off" id="defaultDecline" name="defaultDecline" style="width: 350px; height: 125px"><?php echo $passDecline;?></textarea>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Settings " class="button" />
	</form>