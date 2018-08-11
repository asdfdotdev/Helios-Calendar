<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
	<script language="JavaScript"/>
	function chkFrm(){
		var dirty = 0;
		var warn = 'Settings could not be saved for the following reasons:';
		
		if(document.frm.maxRSS.value == ''){
			dirty = 1;
			warn = warn + '\n*RSS List Size Value is Required';
		} else {
			if(isNaN(document.frm.maxRSS.value) == true){
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
			if(isNaN(document.frm.mostPopular.value) == true){
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
			if(isNaN(document.frm.display.value) == true){
				dirty = 1;
				warn = warn + '\n*Billboard List Size Value Must Be Numeric';
			} else {
				if(document.frm.display.value < 1){
					dirty = 1;
					warn = warn + '\n*Billboard List Size Value Must Be Greater Than 0';
				}//end if
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
			alert(warn + '\nPlease make the required changes and try again.');
			return false;
		}//end if
		
	}//end if
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Settings Updated Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Settings", "Your Helios Settings", "Use the form below to configure your Helios Calendar settings.");
	
	$passApprove = "Your event has been approved and is now available on our website. We hope you continue to use our calendar and submit more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	$passDecline = "Your event has been declined and will not be available on our website. Please do not let this discourage you from submiting more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,25,26,27,28,29,30,31,32,33) ORDER BY PkID");
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
	$mapZoom = cOut(mysql_result($result,18,0));
	$yahooKey = cOut(mysql_result($result,19,0));
	$userCat = cOut(mysql_result($result,20,0));
	$WYSIWYG = cOut(mysql_result($result,21,0));
	$timeInput = cOut(mysql_result($result,22,0));
	$captchas = explode(",", cOut(mysql_result($result,23,0)));
	$series = cOut(mysql_result($result,24,0));	?>
	
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/components/SettingsGeneralAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend>General</legend>
		<div class="frmOpt">
			<label for="browsePast" class="settingsLabel">Event Browse:</label>
			<select name="browsePast" id="browsePast">
				<option <?if($browsePast == 0){echo "selected=\"selected\"";}?> value="0">Current Only</option>
				<option <?if($browsePast == 1){echo "selected=\"selected\"";}?> value="1">All Dates</option>
			</select>
			&nbsp;<?appInstructionsIcon("Event Browse Setting", "<b>Current Only:</b> Allow users to browse events that occur today or in the future.<br /><br /><b>All Dates:</b> Allow users to browse back across all past months and view individual past days events by clicking the day in the control panel.");?>
		</div>
		<div class="frmOpt">
			<label for="locState" class="settingsLabel">Default <?echo HC_StateLabel;?>:</label>
		<?	include('../events/includes/' . HC_StateInclude);	?>
		</div>
		<div class="frmOpt">
			<label for="calStartDay" class="settingsLabel">Mini-Cal Start Day:</label>
			<select name="calStartDay" id="calStartDay">
				<option <?if($calStartDay == 0){echo "selected=\"selected\"";}?> value="0">Sunday</option>
				<option <?if($calStartDay == 1){echo "selected=\"selected\"";}?> value="1">Monday</option>
				<option <?if($calStartDay == 2){echo "selected=\"selected\"";}?> value="2">Tuesday</option>
				<option <?if($calStartDay == 3){echo "selected=\"selected\"";}?> value="3">Wednesday</option>
				<option <?if($calStartDay == 4){echo "selected=\"selected\"";}?> value="4">Thursday</option>
				<option <?if($calStartDay == 5){echo "selected=\"selected\"";}?> value="5">Friday</option>
				<option <?if($calStartDay == 6){echo "selected=\"selected\"";}?> value="6">Saturday</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="emailNotice" class="settingsLabel">New Event Admin Notice:</label>
			<select name="emailNotice" id="emailNotice">
				<option <?if($emailNotice == 0){echo "selected=\"selected\"";}?> value="0">Do Not Send Email Notice</option>
				<option <?if($emailNotice == 1){echo "selected=\"selected\"";}?> value="1">Send Email Notice</option>
			</select>
			&nbsp;<?appInstructionsIcon("New Event Admin Notice", "Helios can send an email notice each time a new event, or event series, is submitted through the public event submission form. Set to <b>Send Email Notice</b> to receive notices for new events.");?>
		</div>
		<div class="frmOpt">
			<label for="WYSIWYG" class="settingsLabel">WYSIWYG Editor:</label>
			<select name="WYSIWYG" id="WYSIWYG">
				<option <?if($WYSIWYG == 1){echo "selected=\"selected\"";}?> value="1">Use TinyMCE</option>
				<option <?if($WYSIWYG == 0){echo "selected=\"selected\"";}?> value="0">Disable WYSIWYG Editor</option>
			</select>
			&nbsp;<?appInstructionsIcon("WYSIWYG Editor", "Helios include the popular \'<b>W</b>hat <b>Y</b>ou <b>S</b>ee <b>I</b>s <b>W</b>hat <b>Y</b>ou <b>G</b>et\' Editor TinyMCE. Should you prefer to input your event information in plain text format you may disable TinyMCE by using this setting.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Spam Prevention CAPTCHAs <?appInstructionsIcon("Spam Prevention CAPTCHA", "Helios offers optional CAPTCHA spam prevention on the public forms listed below. When active the form will require users to input a randomly generated string of eight (8) characters to indicate they are human and not a spam bot.<br /><br />To activate CAPTCHA for a form <b>check the box</b> next to its name.");?></legend>
<?php	$capDisplay = "";
		if(!function_exists('imagecreate')){
			$capDisplay = " disabled=\"disabled\"";
			echo "<label class=\"settingsLabel\">&nbsp;</label><span style=\"font-weight:bold;color:#DC143C;\">GD Library Unavailable. CAPTCHA Cannot Be Used.</span><br /><br />";
		}//end if	?>
		<div class="frmOpt">
			<label class="settingsLabel">Active Captcha Protection:</label>
			<label for="capID_1" class="category"><input <?echo $capDisplay; if(in_array(1, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_1" type="checkbox" value="1" class="noBorderIE" />Public Event Submission</label>
			<label for="capID_2" class="category"><input <?echo $capDisplay; if(in_array(2, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_2" type="checkbox" value="2" class="noBorderIE" />Email Event to Friend</label>
		</div>
		<br />
		<div class="frmOpt">
			<label class="settingsLabel">&nbsp;</label>
			<label for="capID_3" class="category"><input <?echo $capDisplay; if(in_array(3, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_3" type="checkbox" value="3" class="noBorderIE" />Register for Event</label>
			<label for="capID_4" class="category"><input <?echo $capDisplay; if(in_array(4, $captchas)){echo "checked=\"checked\"";}//end if?> name="capID[]" id="capID_4" type="checkbox" value="4" class="noBorderIE" />Newsletter Sign-Up</label>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Public Event Submission</legend>
		<div class="frmOpt">
			<label for="allowsubmit" class="settingsLabel">Public Submission:</label>
			<select name="allowsubmit" id="allowsubmit">
				<option <?if($submit == 1){echo "selected=\"selected\"";}?> value="1">ON</option>
				<option <?if($submit == 0){echo "selected=\"selected\"";}?> value="0">OFF</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="pubCat" class="settingsLabel">Public Event Categories:</label>
			<select name="pubCat" id="pubCat" <?if($submit == 0){echo "disabled=\"disabled\"";}?>>
				<option <?if($userCat == 0){echo "selected=\"selected\"";}?> value="0">Disable Category Selection</option>
				<option <?if($userCat == 1){echo "selected=\"selected\"";}?> value="1">Allow Category Selection</option>
			</select>
			&nbsp;<?appInstructionsIcon("Public Category Selection", "Category selection by public users will be optional. As with all other settings category assignment will be available to admin users during event approval for final review/change.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Billboard, Most Popular Lists &amp; RSS</legend>
		<div class="frmOpt">
			<label for="maxRSS" class="settingsLabel">RSS Max List Size:</label>
			<input name="maxRSS" id="maxRSS" type="text" size="2" maxlength="2" value="<?echo $maxDisplay;?>" />
		</div>
		<div class="frmOpt">
			<label for="mostPopular" class="settingsLabel">Most Popular Max List Size:</label>
			<input name="mostPopular" id="mostPopular" type="text" size="2" maxlength="2" value="<?echo $mostPopular;?>" />
		</div>
		<div class="frmOpt">
			<label for="display" class="settingsLabel">Billboard Max List Size:</label>
			<input name="display" id="display" type="text" size="2" maxlength="2" value="<?echo $maxShow;?>" />
		</div>
		<div class="frmOpt">
			<label for="fill" class="settingsLabel">Auto Fill Billboard:</label>
			<select name="fill" id="fill">
				<option <?if($fillMax == 1){echo "selected=\"selected\"";}?> value="1">On</option>
				<option <?if($fillMax == 0){echo "selected=\"selected\"";}?> value="0">Off</option>
			</select>
			&nbsp;<?appInstructionsIcon("Auto Fill Billboard", "When <b>On</b> Helios will append a list of upcoming events to the bottom of your Billboard if there are fewer Billboard events then the \'Billboard Max List Size\' set above.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Billboard &amp; RSS Event Series:</label>
			<select name="series" id="series">
				<option <?if($series == 1){echo "selected=\"selected\"";}?> value="1">Show All Events</option>
				<option <?if($series == 0){echo "selected=\"selected\"";}?> value="0">Show Next Event Only</option>
			</select>
			&nbsp;<?appInstructionsIcon("Billboard &amp; RSS Event Series", "<b>Show All Events :</b> Show all events in a series as part of the Billboard and RSS lists.<br /><br /><b>Show Next Event Only :</b> Limit Event Series Display on the Billboard and RSS lists to only the next occuring event, hiding the remainder of the series until the next occuring passes.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Show Start Time:</label>
			<label for="showtime" class="radioWide"><input <?if($showTime == 1){echo "checked=\"checked\"";}?> name="showtime" id="showtime" type="checkbox" value="" class="noBorderIE" />(Billboard &amp; Most Popular Lists)</label>
			&nbsp;
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Date &amp; Time Formats ( <a href="http://www.php.net/date" class="main" target="_blank">Click Here For Format Help</a> )</legend>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Date Input Format:</label>
			<select name="popDateFormat" id="popDateFormat">
				<option <?if($popDateFormat == "m/d/Y"){echo "selected=\"selected\"";}?> value="m/d/Y">m/d/y</option>
				<option <?if($popDateFormat == "d/m/Y"){echo "selected=\"selected\"";}?> value="d/m/Y">d/m/y</option>
				<option <?if($popDateFormat == "Y/m/d"){echo "selected=\"selected\"";}?> value="Y/m/d">y/m/d</option>
			</select>
			&nbsp;<?appInstructionsIcon("Date Input Format", "This format will be used in the JavaScript date selection popup window used in the Event Add/Edit/Search/Submit forms.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Date Output Format:</label>
			<input name="dateFormat" id="dateFormat" type="text" value="<?echo $dateFormat;?>" size="10" maxlength="20" />
			&nbsp;<?appInstructionsIcon("Date Output Format", "This format will be used for all date output in Helios except the JavaScript date selection. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit:<br /><b>www.PHP.net/date</b>");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Time Input Format:</label>
			<select name="timeInput" id="timeInput">
				<option <?if($timeInput == "24"){echo "selected=\"selected\"";}?> value="24">24 Hour (<?echo date("H:i");?>)</option>
				<option <?if($timeInput == "12"){echo "selected=\"selected\"";}?> value="12">12 Hour (<?echo date("h:i A");?>)</option>
			</select>
			&nbsp;<?appInstructionsIcon("Time Input Format", "This format will be used in the event forms used in the Event Add/Edit/Pending/Submit forms.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Time Output Format:</label>
			<input name="timeFormat" id="timeFormat" type="text" value="<?echo $timeFormat;?>" size="10" maxlength="20" />
			&nbsp;<?appInstructionsIcon("Time Output Format", "This format will be used for all time output in Helios. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit:<br /><b>www.PHP.net/date</b>");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Weather &amp; Map Links</legend>
		<div class="frmOpt">
			<label for="weather" class="settingsLabel">Weather Link:</label>
			<select name="weather" id="weather">
				<option <?if($weather == 1){echo "selected=\"selected\"";}?> value="1">(US) AccuWeather</option>
				<option <?if($weather == 0){echo "selected=\"selected\"";}?> value="0">(US) Weather.com</option>
				<option <?if($weather == 3){echo "selected=\"selected\"";}?> value="3">(US) Yahoo Weather</option>
				<option <?if($weather == 2){echo "selected=\"selected\"";}?> value="2">(US) Weather Underground</option>
				<option <?if($weather == 6){echo "selected=\"selected\"";}?> value="6">(Australia) weather.News.com.au</option>
				<option <?if($weather == 7){echo "selected=\"selected\"";}?> value="7">(Australia) Weatherzone.com.au</option>
				<option <?if($weather == 4){echo "selected=\"selected\"";}?> value="4">(Canada) AccuWeather</option>
				<option <?if($weather == 5){echo "selected=\"selected\"";}?> value="5">(UK) Weather.co.uk</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="driving" class="settingsLabel">Map Link:</label>
			<select name="driving" id="driving">
				<option <?if($driving == 0){echo "selected=\"selected\"";}?> value="0">(US &amp; Can) Google Maps</option>
				<option <?if($driving == 1){echo "selected=\"selected\"";}?> value="1">(US &amp; Can) Mapquest</option>
				<option <?if($driving == 2){echo "selected=\"selected\"";}?> value="2">(US &amp; Can) Yahoo Maps</option>
				<option <?if($driving == 4){echo "selected=\"selected\"";}?> value="4">(Australia) Google Maps</option>
				<option <?if($driving == 3){echo "selected=\"selected\"";}?> value="3">(UK) Google Maps</option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Location Manager Maps</legend>
		<br />
		<b>Note:</b> Use of the Google and Yahoo API's are subject to their <b>Terms of Use</b> ( <a href="http://www.google.com/apis/maps/terms.html" class="main" target="_blank">G</a> ) ( <a href="http://developer.yahoo.com/terms/" class="main" target="_blank">Y</a> )<br />
		and requires a unique key for each, <b>DO NOT</b> share your key or use any key but your own.
		<br /><br />
		<div class="frmOpt">
			<label for="googleAPI" class="settingsLabel">Google Map Key (<a href="http://www.google.com/apis/maps/signup.html" class="main" target="_blank">Sign Up</a>):</label>
			<input name="googleAPI" id="googleAPI" type="text" value="<?echo $googleKey;?>" size="52" maxlength="255" />
			&nbsp;<?appInstructionsIcon("Google Map Key", "Enter the API key generated by Google. To obtain an API key click <b>Sign Up</b> and complete the form.");?>
		</div>
		<div class="frmOpt">
			<label for="yahooAPI" class="settingsLabel">Yahoo API Key (<a href="http://api.search.yahoo.com/webservices/register_application" class="main" target="_blank">Sign Up</a>):</label>
			<input name="yahooAPI" id="yahooAPI" type="text" value="<?echo $yahooKey;?>" size="35" maxlength="255" />
			&nbsp;<?appInstructionsIcon("Yahoo API Key", "Enter the API key generated by Yahoo. To obtain an API key click <b>Sign Up</b> and complete the form.");?>
		</div>
		<div class="frmOpt">
			<label for="mapZoom" class="settingsLabel">Map Zoom:</label>
			<select name="mapZoom" id="mapZoom">
				<option <?if($mapZoom == 1){echo "selected=\"selected\"";}?> value="1">01 - World</option>
				<option <?if($mapZoom == 2){echo "selected=\"selected\"";}?> value="2">02</option>
				<option <?if($mapZoom == 3){echo "selected=\"selected\"";}?> value="3">03 - Country</option>
				<option <?if($mapZoom == 4){echo "selected=\"selected\"";}?> value="4">04</option>
				<option <?if($mapZoom == 5){echo "selected=\"selected\"";}?> value="5">05 - State</option>
				<option <?if($mapZoom == 6){echo "selected=\"selected\"";}?> value="6">06</option>
				<option <?if($mapZoom == 7){echo "selected=\"selected\"";}?> value="7">07</option>
				<option <?if($mapZoom == 8){echo "selected=\"selected\"";}?> value="8">08</option>
				<option <?if($mapZoom == 9){echo "selected=\"selected\"";}?> value="9">09</option>
				<option <?if($mapZoom == 10){echo "selected=\"selected\"";}?> value="10">10 - City</option>
				<option <?if($mapZoom == 11){echo "selected=\"selected\"";}?> value="11">11</option>
				<option <?if($mapZoom == 12){echo "selected=\"selected\"";}?> value="12">12</option>
				<option <?if($mapZoom == 13){echo "selected=\"selected\"";}?> value="13">13</option>
				<option <?if($mapZoom == 14){echo "selected=\"selected\"";}?> value="14">14</option>
				<option <?if($mapZoom == 15){echo "selected=\"selected\"";}?> value="15">15 - Street</option>
			</select>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Pending Event Messages</legend>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Approve Message:</label>
			<textarea autocomplete="off" id="defaultApprove" name="defaultApprove" style="width: 350px; height: 125px"><?echo $passApprove;?></textarea>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Decline Message:</label>
			<textarea autocomplete="off" id="defaultDecline" name="defaultDecline" style="width: 350px; height: 125px"><?echo $passDecline;?></textarea>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Settings " class="button" />
	</form>