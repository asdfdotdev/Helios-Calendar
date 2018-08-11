<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
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
	
	appInstructions(0, "Main_Page#Settings", "Your Helios Settings", "Use the form below to configure your Helios Calendar settings.");
	
	$passApprove = "Your event has been approved and is now available on our website. We hope you continue to use our calendar and submit more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	$passDecline = "Your event has been declined and will not be available on our website. Please do not let this discourage you from submiting more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact us.";
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (1,2,3,4,8,9,10,11,12,13,14,15,21,22,23,24,25) ORDER BY PkID");
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
	$emailNotice = cOut(mysql_result($result,16,0));	?>
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/components/SettingsGeneralAction.php";?>" onsubmit="return chkFrm();">
	<br />
	<fieldset>
		<legend>General</legend>
		<div class="frmOpt">
			<label for="allowsubmit" class="settingsLabel">Public Event Submission:</label>
			<select name="allowsubmit" id="allowsubmit">
				<option <?if($submit == 1){echo "selected=\"selected\"";}//end if?> value="1">ON</option>
				<option <?if($submit == 0){echo "selected=\"selected\"";}//end if?> value="0">OFF</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="browsePast" class="settingsLabel">Event Browse:</label>
			<select name="browsePast" id="browsePast">
				<option <?if($browsePast == 0){echo "selected=\"selected\"";}//end if?> value="0">Current Only</option>
				<option <?if($browsePast == 1){echo "selected=\"selected\"";}//end if?> value="1">All Dates</option>
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
				<option <?if($calStartDay == 0){echo "selected=\"selected\"";}//end if?> value="0">Sunday</option>
				<option <?if($calStartDay == 1){echo "selected=\"selected\"";}//end if?> value="1">Monday</option>
				<option <?if($calStartDay == 2){echo "selected=\"selected\"";}//end if?> value="2">Tuesday</option>
				<option <?if($calStartDay == 3){echo "selected=\"selected\"";}//end if?> value="3">Wednesday</option>
				<option <?if($calStartDay == 4){echo "selected=\"selected\"";}//end if?> value="4">Thursday</option>
				<option <?if($calStartDay == 5){echo "selected=\"selected\"";}//end if?> value="5">Friday</option>
				<option <?if($calStartDay == 6){echo "selected=\"selected\"";}//end if?> value="6">Saturday</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="emailNotice" class="settingsLabel">New Event Admin Notice:</label>
			<select name="emailNotice" id="emailNotice">
				<option <?if($emailNotice == 0){echo "selected=\"selected\"";}//end if?> value="0">Do Not Send Email Notice</option>
				<option <?if($emailNotice == 1){echo "selected=\"selected\"";}//end if?> value="1">Send Email Notice</option>
			</select>
			&nbsp;<?appInstructionsIcon("New Event Admin Notice", "Helios can send an email notice each time a new event, or event series, is submitted through the public event submission form. Set to <b>Send Email Notice</b> to receive notices for new events.");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Billboard &amp; Most Popular Lists</legend>
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
				<option <?if($fillMax == 1){echo "selected=\"selected\"";}//end if?> value="1">On</option>
				<option <?if($fillMax == 0){echo "selected=\"selected\"";}//end if?> value="0">Off</option>
			</select>
			&nbsp;<?appInstructionsIcon("Auto Fill Billboard", "When <b>On</b> Helios will append a list of upcoming events to the bottom of your Billboard if there are fewer Billboard events then the \'Billboard Max List Size\' set above.");?>
		</div>
		<div class="frmOpt">
			<label class="settingsLabel">Show Start Time:</label>
			<label for="showtime" class="radioWide"><input <?if($showTime == 1){echo "checked=\"checked\"";}//end if?> name="showtime" id="showtime" type="checkbox" value="" class="noBorderIE" />(Billboard &amp; Most Popular Lists)</label>
			&nbsp;
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Date &amp; Time Formats ( <a href="http://www.php.net/date" class="main" target="_blank">Click Here For Format Help</a> )</legend>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Select Date Format:</label>
			<select name="popDateFormat" id="popDateFormat">
				<option <?if($popDateFormat == "m/d/Y"){echo "selected=\"selected\"";}//end if?> value="m/d/Y">m/d/y</option>
				<option <?if($popDateFormat == "d/m/Y"){echo "selected=\"selected\"";}//end if?> value="d/m/Y">d/m/y</option>
				<option <?if($popDateFormat == "Y/m/d"){echo "selected=\"selected\"";}//end if?> value="Y/m/d">y/m/d</option>
			</select>
			&nbsp;<?appInstructionsIcon("Select Date Format", "This format will be used in the JavaScript date selection popup window used in the Event Add/Edit/Search/Submit forms.");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Date Format:</label>
			<input name="dateFormat" id="dateFormat" type="text" value="<?echo $dateFormat;?>" size="10" maxlength="20" />
			&nbsp;<?appInstructionsIcon("Date Format", "This format will be used for all date output in Helios except the JavaScript date selection. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit:<br /><b>www.PHP.net/date</b>");?>
		</div>
		<div class="frmOpt">
			<label for="" class="settingsLabel">Time Format:</label>
			<input name="timeFormat" id="timeFormat" type="text" value="<?echo $timeFormat;?>" size="10" maxlength="20" />
			&nbsp;<?appInstructionsIcon("Time Format", "This format will be used for all time output in Helios. Format follows the PHP date format. For help constructing your preferred format please click the help link above or visit:<br /><b>www.PHP.net/date</b>");?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Weather &amp; Map Links</legend>
		<div class="frmOpt">
			<label for="weather" class="settingsLabel">Weather Link:</label>
			<select name="weather" id="weather">
				<option <?if($weather == 0){echo "selected=\"selected\"";}//end if?> value="0">Weather Channel</option>
				<option <?if($weather == 1){echo "selected=\"selected\"";}//end if?> value="1">AccuWeather</option>
				<option <?if($weather == 4){echo "selected=\"selected\"";}//end if?> value="4">AccuWeather (Canada)</option>
				<option <?if($weather == 2){echo "selected=\"selected\"";}//end if?> value="2">Weather Underground</option>
				<option <?if($weather == 3){echo "selected=\"selected\"";}//end if?> value="3">Yahoo Weather</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="driving" class="settingsLabel">Map Link:</label>
			<select name="driving" id="driving">
				<option <?if($driving == 0){echo "selected=\"selected\"";}//end if?> value="0">Google Maps</option>
				<option <?if($driving == 1){echo "selected=\"selected\"";}//end if?> value="1">Mapquest</option>
				<option <?if($driving == 2){echo "selected=\"selected\"";}//end if?> value="2">Yahoo Maps</option>
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