<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
		$efCheck = $eID;
	} elseif(isset($_POST['eventID'])){
		$editString = "0";
		$eventID = $_POST['eventID'];
		$ecnt = 0;
		foreach ($eventID as $val){
			if($ecnt == 0){
				$eID = $val;
			}//end if
			$editString = $editString . "," . $val;
			$ecnt++;
		}//end for
		$efCheck = $editString;
	}//end if
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
	
	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
	$hasLoc = 0;
	if(hasRows($resultL)){
		$hasLoc = 1;
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($eID) . "'");
	
	if(!hasRows($result)){	?>
		<br />
		You are attempting to edit an event that does not exist.
		<br /><br />
		<a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch" class="main">Click here to search for a new event.</a>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
<?php
	} else {
		
		$hrFormat = "h";
		$minHr = 1;
		if($hc_timeInput == 23){
			$hrFormat = "H";
			$minHr = 0;
		}//end if
		
		$eventStatus = cOut(mysql_result($result,0,17));
		$eventBillboard = cOut(mysql_result($result,0,18));
		$eventTitle = cOut(mysql_result($result,0,1));
		$eventDesc = cOut(mysql_result($result,0,8));
		
		$locName = cOut(mysql_result($result,0,2));
		$locAddress = cOut(mysql_result($result,0,3));
		$locAddress2 = cOut(mysql_result($result,0,4));
		$locCity = cOut(mysql_result($result,0,5));
		$locState = cOut(mysql_result($result,0,6));
		$locZip = cOut(mysql_result($result,0,7));
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
		$contactPhone = cOut(mysql_result($result,0,15));
		$contactURL = cOut(mysql_result($result,0,24));
		$allowRegistration = cOut(mysql_result($result,0,25));
		$maxRegistration = cOut(mysql_result($result,0,26));
		$views = cOut(mysql_result($result,0,28));
		$locID = cOut(mysql_result($result,0,35));
		$cost = cOut(mysql_result($result,0,36));
		$locCountry = cOut(mysql_result($result,0,37));
		
		if($contactURL == ""){
			$contactURL = "http://";
		}//end if
		
		$eventDate = stampToDate(mysql_result($result,0,9), $hc_popDateFormat);
		$AllDay = "";
		
		if(mysql_result($result,0,11) == 0){
			$tbd = 0;
			if(mysql_result($result,0,10) != ''){
				$startTimeParts = explode(":", mysql_result($result,0,10));
				$startTimeHour = date($hrFormat, mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$startTimeMins = date("i", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$startTimeAMPM = date("A", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				
				if(mysql_result($result,0,12) != ''){
					$endTimeParts = explode(":", mysql_result($result,0,12));
					$endTimeHour = date($hrFormat, mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeMins = date("i", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeAMPM = date("A", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					
				} else {
					$endTimeParts = explode(":", mysql_result($result,0,10));
					$endTimeHour = date($hrFormat, mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeMins = date("i", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeAMPM = date("A", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$noEndTime = 1;
				}//end if
				
			}//end if
			
		} else {
			$startTimeHour = date($hrFormat);
			$startTimeMins = "00";
			$startTimeAMPM = date("A");
			$endTimeHour = date($hrFormat, mktime(date($hrFormat) + 1, 0, 0, 1, 1, 1971));
			$endTimeMins = "00";
			$endTimeAMPM = date("A", mktime(date("H") + 1, 0, 0, 1, 1, 1971));
			
			if(mysql_result($result,0,11) == 1){
				$tbd = 1;
			} elseif(mysql_result($result,0,11) == 2){
				$tbd = 2;
			}//end if
			
		}//end if
		
		if (isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,"Event Updated Successfully!");
					break;
					
				case "2" :
					feedback(1,"Event Added Successfully!");
					break;
					
				case "3" :
					feedback(1,"Registrant Added Successfully.");
					break;
					
				case "4" :
					feedback(1,"Registrant Updated Successfully.");
					break;
					
				case "5" :
					feedback(1, "Registrant Deleted Successfully.");
					break;
					
				case "6" :
					feedback(1, "Registrant Roster Sent to Event Contact Successfully.");
					break;
					
				case "7" :
					feedback(2, "Add to Helios Successful. Eventful Submission Failed: API Key Not Found.");
					break;
					
				case "8" :
					feedback(2, "Add to Helios Successful. Eventful Submission Failed: Connection Failed.");
					break;
					
				case "9" :
					feedback(1, "Add to Helios and Eventful Submission Successful.");
					break;
					
				case "10" :
					feedback(1, "Event Update and Eventful Re-Submission Successful.");
					break;
					
				case "11" :
					feedback(1, "Event Update and Eventful Submission Successful.");
					break;
			}//end switch
		}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Dates.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function toggleMe(who){
		who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
		return false;
	}//end toggleMe()
	
	function cloneEvent(){
		if(confirm('Saving as a new event will create a new event entry based on the current settings of this event.\nThe original event will remain unchanged.\n\nDo you want to create a new event?\n\n          Ok = YES, Create New Event\n          Cancel = NO, Save Changes to This Event')){
			document.frmEventEdit.action='<?php echo CalAdminRoot;?>/components/EventAddAction.php';
		}//end if
	}//end cloneEvent()
	
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
	
	function togRegistrants(){
		if(document.getElementById('registrant').style.display == 'none'){
			document.getElementById('registrant').style.display = 'block';
			document.frmEventEdit.eventRegistrants.value = 'Hide Registrants';
		} else {
			document.getElementById('registrant').style.display = 'none';
			document.frmEventEdit.eventRegistrants.value = 'Show Registrants';
		}//end if
	}//end showPanel()
	
	function chkFrm(){
	dirty = 0;
	warn = "Event could not be updated for the following reason(s):";
		
		if(document.frmEventEdit.eventRegistration.value == 1){
			if(isNaN(document.frmEventEdit.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n*Registration Limit Value Must Be Numeric';
			}//end if
			
			if(document.frmEventEdit.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Name';
			}//end if
			
			if(document.frmEventEdit.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Email Address';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventEdit','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category Assignment is Required';
		}//end if
	
		if(document.frmEventEdit.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Title is Required';
		}//end if
		
		if(!isDate(document.frmEventEdit.eventDate.value, '<?php echo $hc_popDateValid;?>')){
			dirty = 1;
			warn = warn + '\n*Event Date Format is Invalid Date or Format. Required Format: <?php echo strtolower($hc_popDateValid);?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Numeric';
		} else if((document.frmEventEdit.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.startTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Between <?php echo $minHr;?> and <?php echo $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Numeric';
		} else if((document.frmEventEdit.startTimeMins.value > 59) || (document.frmEventEdit.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Between 0 and 59';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Numeric';
		} else if((document.frmEventEdit.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.endTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Between <?php echo $minHr;?> and <?php echo $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Numeric';
		} else if((document.frmEventEdit.endTimeMins.value > 59) || (document.frmEventEdit.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Between 0 and 59';
		}//end if
		
<?php 	if($hasLoc > 0){	?>
		if(document.frmEventEdit.locPreset.value == 0){
			if(document.frmEventEdit.locName.value == ''){
				dirty = 1;
				warn = warn + '\n*Location Name is Required';
			}//end if
		}//end if
<?php 	} else {	?>
		if(document.frmEventEdit.locName.value == ''){
			dirty = 1;
			warn = warn + '\n*Location Name is Required';
		}//end if
<?php 	}//end if	?>

		if(compareDates(document.frmEventEdit.eventDate.value, '<?php echo $hc_popDateValid;?>', '<?php echo date($hc_popDateFormat,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_popDateValid;?>') == 0){
			if(!confirm('You have selected a past date for this event.\nAre you sure you want to save this event as a past event?\n\n          Ok = YES Continue Saving Past Event\n          Cancel = NO Cancel and Change Date')){
				return false;
			}//end if
		}//end if
	
		if(document.frmEventEdit.contactEmail.value != '' && chkEmail(document.frmEventEdit.contactEmail) == 0){
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
	
	function togOverride(){
		if(document.frmEventEdit.overridetime.checked){
			document.frmEventEdit.specialtimeall.disabled = false;
			document.frmEventEdit.specialtimetbd.disabled = false;
			document.frmEventEdit.startTimeHour.disabled = true;
			document.frmEventEdit.startTimeMins.disabled = true;
			document.frmEventEdit.endTimeHour.disabled = true;
			document.frmEventEdit.endTimeMins.disabled = true;
			document.frmEventEdit.ignoreendtime.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.startTimeAMPM.disabled = true;
				document.frmEventEdit.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventEdit.specialtimeall.disabled = true;
			document.frmEventEdit.specialtimetbd.disabled = true;
			document.frmEventEdit.startTimeHour.disabled = false;
			document.frmEventEdit.startTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.startTimeAMPM.disabled = false;
			}//end if
			if(document.frmEventEdit.ignoreendtime.checked == false){
				document.frmEventEdit.endTimeHour.disabled = false;
				document.frmEventEdit.endTimeMins.disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventEdit.endTimeAMPM.disabled = false;
				}//end if
			}//end if
			document.frmEventEdit.ignoreendtime.disabled = false;
		}//end if
	}//end togOverride()
	
	function togEndTime(){
		if(document.frmEventEdit.ignoreendtime.checked){
			document.frmEventEdit.endTimeHour.disabled = true;
			document.frmEventEdit.endTimeMins.disabled = true;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.endTimeAMPM.disabled = true;
			}//end if
		} else {
			document.frmEventEdit.endTimeHour.disabled = false;
			document.frmEventEdit.endTimeMins.disabled = false;
			if(<?php echo $hc_timeInput;?> == 12){
				document.frmEventEdit.endTimeAMPM.disabled = false;
			}//end if
		}//end if
	}//end togEndTime()
	
	function togRegistration(){
		if(document.frmEventEdit.eventRegistration.value == 0){
			document.frmEventEdit.eventRegAvailable.disabled = true;
		} else {
			document.frmEventEdit.eventRegAvailable.disabled = false;
		}//end if
	}//end togRegistration()
	
	function sendReg(eID){
		window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterSendRoster.php?eID=' + eID);
	}//end if
	
	function delReg(dID){
		if(confirm('Registrant Delete Is Permanent!\nAre you sure you want to delete?\n\n          Ok = YES Delete Registrant\n          Cancel = NO Don\'t Delete Registrant')){
			window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterAddAction.php?dID=' + dID + '&eID=<?php echo $eID;?>');
		}//end if
	}//end delReg()
	
	function togLocation(){
		if(document.frmEventEdit.locPreset.value == 0){
			document.frmEventEdit.locName.disabled = false;
			document.frmEventEdit.locAddress.disabled = false;
			document.frmEventEdit.locAddress2.disabled = false;
			document.frmEventEdit.locCity.disabled = false;
			document.frmEventEdit.locState.disabled = false;
			document.frmEventEdit.locZip.disabled = false;
			document.frmEventEdit.locCountry.disabled = false;
		} else {
			document.frmEventEdit.locName.disabled = true;
			document.frmEventEdit.locAddress.disabled = true;
			document.frmEventEdit.locAddress2.disabled = true;
			document.frmEventEdit.locCity.disabled = true;
			document.frmEventEdit.locState.disabled = true;
			document.frmEventEdit.locZip.disabled = true;
			document.frmEventEdit.locCountry.disabled = true;
		}//end if
	}//end togEndTime()
	
	var calx = new CalendarPopup("dsCal");
	document.write(calx.getStyles());
	//-->
	</script>
	
<?php
	if(!isset($eventID)){
		appInstructions(0, "Editing_Events", "Event Edit", "You can make changes to the event via the form below then click 'Save Event'.<br /><br />(<span style=\"color: #DC143C\">*</span>) = Required Fields<br />(<span style=\"color: #0000FF;\">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style=\"color: green;\">*</span>) = Required for events <b>with registration</b>");
	} else {
		appInstructions(0, "Group_Editing_Events", "Group Event Edit", "You can make changes to the events via the form below then click 'Save Event'.<br />If you make a mistake and wish to start over click the 'Reset Form' button below.<br /><br />(<span class=\"eventReqTag\">*</span>) = Required Fields<br />(<span style=\"color: blue;\">*</span>) = Optional Fields, but required for dynamic driving directions<br />(<span style=\"color: green;\">*</span>) = Required for events <b>with registration</b>");
	}//end if
	echo "<br />";?>
	<b>This event has been viewed <?php echo $views;?> times.</b><br />
	<form id="frmEventEdit" name="frmEventEdit" method="post" action="<?php echo CalAdminRoot . "/components/EventEditAction.php";?>" onsubmit="return chkFrm();">
	<input name="dateFormat" id="dateFormat" type="hidden" value="<?php echo strtolower($hc_popDateFormat);?>" />
	<input name="timeFormat" id="timeFormat" type="hidden" value="<?php echo $hc_timeInput;?>">
	<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
<?php
	if(isset($editString)){	?>
	<input type="hidden" name="editString" id="editString" value="<?php echo $editString;?>" />	<?php
		$resultD = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($editString) . ")");
		if(hasRows($resultD)){	?>
		<br />
		<b>You are editing this event for the following dates:</b><br />
<?php 	$cnt = 0;
		while($row = mysql_fetch_row($resultD)){	
			if($cnt > 0){echo ", ";}
			echo stampToDate($row[0], "m/d/Y");
			$cnt++;
		}//end while	?>
		<br /><br />
		<label for="makeseries"><input type="checkbox" name="makeseries" id="makeseries" class="noBorderIE" />Combine Events to New Series</label>
		<br />
<?php 	}//end if
	}//end if	?>
	
	<br />
	<fieldset>
		<legend>Event Details</legend>
		<div class="frmReq">
			<label for="eventTitle">Title:</label>
			<input name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" value="<?php echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription">Description:</label>
	<?php	makeTinyMCE("eventDescription", $hc_WYSIWYG, "435px", "advanced", $eventDesc);?>
		</div>
<?php	if(!isset($editString)){	?>
		<div class="frmReq">
			<label for="eventDate">Event Date:</label>
			<input name="eventDate" id="eventDate" type="text" value="<?php echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventEdit.eventDate,'anchor1','<?php echo $hc_popDateValid;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.gif" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
<?php	}//end if	?>
		<div class="frmOpt">
			<label>Start Time:</label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo $startTimeHour;?>" size="2" maxlength="2" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="startTimeMins" id="startTimeMins" type="text" value="<?php echo $startTimeMins;?>" size="2" maxlength="2"  <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select name="startTimeAMPM" id="startTimeAMPM" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>>
							<option <?php if($startTimeAMPM == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?php if($startTimeAMPM == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
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
					<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo $endTimeHour;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="endTimeMins" id="endTimeMins" type="text" value="<?php echo $endTimeMins;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select name="endTimeAMPM" id="endTimeAMPM" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>>
							<option <?php if($endTimeAMPM == "AM"){?>selected="selected"<?php }?> value="AM">AM</option>
							<option <?php if($endTimeAMPM == "PM"){?>selected="selected"<?php }?> value="PM">PM</option>
						</select>
					</td>
			<?php 	}//end if	?>
					<td><label for="ignoreendtime" style="padding-left:20px;" class="radio">No End Time:</label></td>
					<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" <?php if(isset($noEndTime)){echo "checked=\"checked\" ";}//end if?> <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="overridetime">Override&nbsp;Times:</label>&nbsp;<input <?php if($tbd > 0){echo "checked=\"checked\" ";}//end if?>type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
			<br />
			<label>&nbsp;</label>
			<label for="specialtimeall" class="radioWide"><input type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 1){echo "checked=\"checked\" ";}//end if?>/>All Day Event</label>
			<br /><br />
			<label>&nbsp;</label>
			<label for="specialtimetbd" class="radioWide"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 2){echo "checked=\"checked\" ";}//end if?>/>Event Times To Be Announced</label>
			<br />
		</div>
		<br />
		<div class="frmOpt">
			<label for="cost">Cost:</label>
			<input name="cost" id="cost" type="text" value="<?php echo $cost;?>" size="25" maxlength="50" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Registration</legend>
		<div class="frmOpt">
			<label for="eventRegistration">Registration:</label>
			<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
				<option <?php if($allowRegistration == 0){echo "selected=\"selected\"";}?> value="0">Do Not Allow Registration</option>
				<option <?php if($allowRegistration == 1){echo "selected=\"selected\"";}?> value="1">Allow Registration</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventRegAvailable">Limit:</label>
			<input <?php if($allowRegistration == 0){echo "disabled=\"disabled\"";}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?php echo $maxRegistration;?>" />&nbsp;(0 = unlimited)
		</div>
<?php 	if($allowRegistration == 1){	?>
		<div class="frmOpt">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . $eID . "'");
			$regUsed = mysql_result($result,0,0);
			$regAvailable = $maxRegistration;
			
			if($maxRegistration == 0) {
				echo "<b>" . $regUsed . " Total Registrants</b>";
			} elseif($maxRegistration <= mysql_result($result,0,0)){	?>
				<img src="<?php echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;" />
		<?php 	echo "<b>" . $regUsed . " Total Registrants</b> -- Registering Overflow Only";
			} else {
				if($regAvailable > 0){
					if($regUsed > 0){
						$regWidth = ($regUsed / $regAvailable) * 100;
						$fillWidth = 100 - $regWidth;
					} else {
						$regWidth = 0;
						$fillWidth = 100;
					}//end if	?>
					<img src="<?php echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?php echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;" />
		<?php 		echo "<b>" . $regUsed . " Current Registrants</b>";
				}//end if
			}//end if	?>
		</div>
		<label>&nbsp;</label>
		<input style="width: 120px;" name="eventRegistrants" id="eventRegistrants" type="button" value="<?php if(isset($_GET['r'])){echo "Hide";}else{echo "Show";}?> Registrants" onclick="togRegistrants();" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 160px;" name="eventSendRoster" id="eventSendRoster" type="button" value="Send Roster to Contact" onclick="javascript:if(confirm('This will send a full roster of all current registrants\nto the event contact listed below.\n\nAre you sure you want to send the roster?\n\n          Ok = YES Send Roster to Event Contact\n          Cancel = NO Do Not Send Roster')){sendReg(<?php echo $eID;?>);};" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 120px;" name="addRegistrant" id="addRegistrant" type="button" value="Add Registrant" onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=eventregister&amp;eID=<?php echo $eID;?>';" class="button" />
		<br /><br />
		<div id="registrant" style="display:<?php if(isset($_GET['r'])){echo "block";}else{echo "none";}?>;">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . cIn($eID) . " ORDER BY RegisteredAt");
			$row_cnt = mysql_num_rows($result);
			
			if($row_cnt > 0){	?>
			<table cellpadding="2" cellspacing="0" border="0">
				<tr>
					<td width="150"><b>Registrant</b></td>
					<td width="100"><b>Phone</b></td>
					<td width="150"><b>Registered At</b></td>
					<td width="50" align="center">&nbsp;</td>
				</tr>
		<?php 	$cnt = 0;
				$shown = 0;
				while($row = mysql_fetch_row($result)){
					if($cnt >= $maxRegistration && $maxRegistration != 0 && $shown == 0){
						$shown = 1;
						$cnt = 0;	?>
						<tr>
							<td colspan="4">
								<br />
								<b>Overflow Registrants</b>
							</td>
						</tr>
			<?php 	}//end if	?>
					<tr>
						<td class="<?php if($cnt % 2 == 0){echo "main";}else{echo"tblListHL";}?>"><?php echo $cnt+1;?>)&nbsp;<a href="mailto:<?php echo cOut($row[2]);?>" class="main"><?php echo cOut($row[1]);?></a></td>
						<td class="<?php if($cnt % 2 == 0){echo "main";}else{echo"tblListHL";}?>"><?php if($row[3] != ''){echo cOut($row[3]);}else{echo "N/A";}?></td>
						<td class="<?php if($cnt % 2 == 0){echo "main";}else{echo"tblListHL";}?>"><?php if($row[11] != ''){echo stampToDate(cOut($row[11]), $hc_popDateFormat . ' \a\t ' . $hc_timeFormat);}else{echo "N/A";}?></td>
						<td class="<?php if($cnt % 2 == 0){echo "main";}else{echo"tblListHL";}?>" align="center">
							<a href="<?php echo CalAdminRoot;?>/index.php?com=eventregister&amp;rID=<?php echo $row[0];?>&amp;eID=<?php echo $eID;?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;
							<a href="javascript:;" onclick="delReg(<?php echo $row[0];?>);return false;" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconDelete.gif" width="15" height="15" alt="" border="0" align="middle" /></a></td>
					</tr>
			<?php 	$cnt++;
				}//end while	?>
			</table>
	<?php 	} else {	?>
				There are currently no registrants for this event.
				<br /><br />
	<?php 	}//end if	?>
		</div>
<?php 	}//end if?>
	</fieldset>
	<br />
	<fieldset>
		<input type="hidden" name="prevStatus" id="prevStatus" value="<?php echo $eventStatus;?>" />
		<legend>Event Settings</legend>
		<div class="frmOpt">
			<label for="eventStatus">Status:</label>
			<select name="eventStatus" id="eventStatus">
				<option <?php if($eventStatus == 1){echo "selected=\"selected\"";}//end if?> value="1">Approved -- Show on Calendar</option>
				<option <?php if($eventStatus == 2){echo "selected=\"selected\"";}//end if?> value="2">Pending -- Hidden on Calendar</option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventBillboard">Billboard:</label>
			<select name="eventBillboard" id="eventBillboard">
				<option <?php if($eventBillboard == 0){echo "selected=\"selected\"";}//end if?> value="0">Do Not Show On Billboard</option>
				<option <?php if($eventBillboard == 1){echo "selected=\"selected\"";}//end if?> value="1">Show On Billboard</option>
			</select>
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
		<?php 	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "eventcategories.EventID
							FROM " . HC_TblPrefix . "categories 
								LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID AND " . HC_TblPrefix . "eventcategories.EventID = " . cIn($eID) . ") 
							WHERE " . HC_TblPrefix . "categories.IsActive = 1
							ORDER BY CategoryName";
				getCategories('frmEventEdit', 2, $query);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Location Information</legend>
<?php 	if($hasLoc > 0){	?>
		<div class="frmReq">
			<label for="locPreset">Preset:</label>
			<select name="locPreset" id="locPreset" onchange="togLocation();">
				<option value="0">Custom Location (Enter Location Below)</option>
		<?php 	while($row = mysql_fetch_row($resultL)){	?>
				<option <?php if($row[0] == $locID){echo "selected=\"selected\"";}?> value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
		<?php 	}//end while	?>
			</select>
		</div>
<?php 	} else {	?>
		<input type="hidden" name="locPreset" value="0" />
<?php 	}//end if	?>
	
		<div class="frmReq">
			<label for="locName">Name:</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locName" id="locName" value="<?php echo $locName;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress">Address:</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress" id="locAddress" value="<?php echo $locAddress;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress2">&nbsp;</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress2" id="locAddress2" value="<?php echo $locAddress2;?>" type="text" maxlength="75" size="25" />
		</div>
		<div class="frmOpt">
			<label for="locCity">City:</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCity" id="locCity" value="<?php echo $locCity;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locState"><?php echo HC_StateLabel;?>:</label>
		<?php 	$state = $locState;
				if($locID > 0){
					$stateDisabled = 1;
					$state = $hc_defaultState;
				}//end if
				include('../events/includes/' . HC_StateInclude);?><span style="color: #0000FF;">*</span>
		</div>
		<div class="frmOpt">
			<label for="locZip">Postal Code:</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locZip" id="locZip" value="<?php echo $locZip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locCountry">Country:</label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCountry" id="locCountry" value="<?php echo $locCountry;?>" type="text" maxlength="50" size="5" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend>Event Contact Info</legend>
		<div class="frmOpt">
			<label for="contactName">Name:</label>
			<input name="contactName" id="contactName" type="text" value="<?php echo $contactName;?>" maxlength="50" size="20" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactEmail">Email:</label>
			<input name="contactEmail" id="contactEmail" type="text" value="<?php echo $contactEmail;?>" maxlength="75" size="30" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactPhone">Phone:</label>
			<input name="contactPhone" id="contactPhone" type="text" value="<?php echo $contactPhone;?>" maxlength="25" size="20" />
		</div>
		<div class="frmOpt">
			<label for="contactURL">Website:</label>
			<input name="contactURL" id="contactURL" type="text" value="<?php echo $contactURL;?>" maxlength="100" size="40" />
	<?php 	if($contactURL != 'http://'){	?>
				<a href="<?php echo $contactURL;?>" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconWebsite.gif" width="16" height="16" alt="" border="0" /></a>
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<?php
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
	if(hasRows($result)){
		if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){	?>
		<br />
		<fieldset>
		<?php
			$resultEF = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID IN (" . $efCheck . ")");
			if(hasRows($resultEF)){	?>
				<legend>Update This Event on eventful</legend>
				<a href="http://eventful.com/events/<?php echo mysql_result($resultEF,0,1)?>" class="eventMain" target="_blank">View this Event on eventful</a><br /><br />
				<input type="hidden" name="efSetting" id="efSetting" value="modify" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;Check to Update on <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			} else {	?>
				<legend>Add This Event to eventful</legend>
				<input type="hidden" name="efSetting" id="efSetting" value="new" />
				<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" />&nbsp;Check to Add to <b><span style="color:#0043FF;">event</span><span style="color:#66CC33;">ful</span></b></label>
		<?php
			}//end if	?>
			<div id="eventful" style="display:none;clear:both;">
		<?php
			if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
			<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
			<b>eventful Username &amp; Password Required</b><br />
			Enter your eventful Username &amp; Password to submit this event.<br /><br />
			To skip this step in the future save your eventful account info in your <a href="<?php echo CalAdminRoot;?>/index.php?com=generalset" target="_blank" class="main">Helios Calendar Settings</a><br /><br />
				<div class="frmOpt">
					<label for="efUser" class="settingsLabel">Username:</label>
					<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
				</div>
				<div class="frmOpt">
					<label for="efPass" class="settingsLabel">Password:</label>
					<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
				</div>
				<div class="frmOpt">
					<label for="efPass2" class="settingsLabel">Confirm Password:</label>
					<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
				</div>
			</div>
		<?php			
			}//end if	?>
				The following information about this event will be submitted:
				<ul>
					<li>Title</li>
					<li>Description</li>
					<li>Start &amp; End Time</li>
					<li>Venue ID*</li>
					<li>Categories (Listed on eventful as "Tags")</li>
					<li>Cost</li>
				</ul>
				<b>*Note:</b> If you did not select a preset location previously submitted to eventful the event location information provided will be included in the event description.
			</div>
		</fieldset>
<?php
		}//end if
	}//end if	?>
	<br />
	<input name="submit" id="submit" type="submit" value=" Save Event " class="button" />
<?php
	if(!isset($_POST['eventID'])){	?>
	&nbsp;&nbsp;|&nbsp;&nbsp;
	<input name="makeClone" id="makeClone" type="submit" value=" Save As New Event " class="button" onclick="cloneEvent();" />
<?php
	}//end if	?>
	</form>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>