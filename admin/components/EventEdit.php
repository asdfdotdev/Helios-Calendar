<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} else {
		if(isset($_POST['eventID'])){
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
		} else {
			$eID = 0;
		}//end if
		
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	
	if(!hasRows($result)){
	?>
		<br>
		You are attempting to edit an event that does not exist.
		<br><br>
		<a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch" class="main">Click here to search for a new event.</a>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<?
	} else {
	
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
		
		if($contactURL == ""){
			$contactURL = "http://";
		}//end if
		
		$chkDate = mysql_result($result,0,9);
		$eventDate = stampToDate(mysql_result($result,0,9), "m/d/Y");
		$AllDay = "";
		
		if(mysql_result($result,0,11) == 0){
			$tbd = 0;
			if(mysql_result($result,0,10) != ''){
				$startTimeParts = split(":", mysql_result($result,0,10));
				$startTimeHour = date("h", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$startTimeMins = date("i", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$startTimeAMPM = date("A", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				
				if(mysql_result($result,0,12) != ''){
					$endTimeParts = split(":", mysql_result($result,0,12));
					$endTimeHour = date("h", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeMins = date("i", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeAMPM = date("A", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					
				} else {
					$endTimeParts = split(":", mysql_result($result,0,10));
					$endTimeHour = date("h", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeMins = date("i", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$endTimeAMPM = date("A", mktime($endTimeParts[0] + 1, $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
					$noEndTime = 1;
				}//end if
				
			}//end if
			
		} else {
			$startTimeHour = date("h");
			$startTimeMins = "00";
			$startTimeAMPM = date("A");
			$endTimeHour = date("h", mktime(date("H") + 1, 0, 0, 1, 1, 1971));
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
					feedback(1,"Event Was Updated Successfully!");
					break;
					
				case "2" :
					feedback(1,"Event Was Added Successfully!");
					break;
					
			}//end switch
		}//end if
	?>
	<script language="JavaScript">
	function chkDate(obj)
	{
		var re = /^(\d{1,2})[\/|-](\d{1,2})[\/|-](\d{2}|\d{4})$/
		if(!re.test(obj.value)) 
		{
			return 1;
		} else {
			return 0;
		}//end if
	}//end chkDate()
	
	function chngClock(obj,inc,maxVal)
	{
		var val = parseInt(obj.value,10);
		val += inc;
		
		if(maxVal == 59){
			if(val > maxVal) val = 0;
			if(val < 0) val = maxVal;	
		} else {
			if(val > maxVal) val = 1;
			if(val <= 0) val = maxVal;	
		}//end if
		
		if(val < 10) val = "0" + val;
		obj.value = val;
	}//end chngClock()
	
	function chkFrm(){
	dirty = 0;
	warn = "Event could not be updated for the following reason(s):";
		
		if(document.eventSubmit.eventRegistration.value == 1){
			if(isNaN(document.eventSubmit.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n*Registration Limit Value Must Be Numeric';
			}//end if
			
			if(document.eventSubmit.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Name';
			}//end if
			
			if(document.eventSubmit.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n*Registration Requires Contact Email Address';
			}//end if
		}//end if
		
		if(validateCheckArray('eventSubmit','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category Assignment is Required';
		}//end if
	
		if(document.eventSubmit.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Title is Required';
		}//end if
		
		if(document.eventSubmit.eventDate.value == ''){
			dirty = 1;
			warn = warn + '\n*Event Date is Required';
		} else if(chkDate(document.eventSubmit.eventDate) == 1){
			dirty = 1;
			warn = warn + '\n*Event Date Format Invalid';
		} else if(document.eventSubmit.eventDate.value.length < 10) {
			dirty = 1;
			warn = warn + '\n*Event Date Must Include Leading 0\'s (MM/DD/YYYY)';
		}//end if
		
		if(isNaN(document.eventSubmit.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Numeric';
		} else if((document.eventSubmit.startTimeHour.value > 12) || (document.eventSubmit.startTimeHour.value < 1)) {
			dirty = 1;
			warn = warn + '\n*Start Hour Must Be Between 1 and 12';
		}//end if
		
		if(isNaN(document.eventSubmit.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Numeric';
		} else if((document.eventSubmit.startTimeMins.value > 59) || (document.eventSubmit.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*Start Minute Must Be Between 0 and 59';
		}//end if
		
		if(isNaN(document.eventSubmit.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Numeric';
		} else if((document.eventSubmit.endTimeHour.value > 12) || (document.eventSubmit.endTimeHour.value < 1)) {
			dirty = 1;
			warn = warn + '\n*End Hour Must Be Between 1 and 12';
		}//end if
		
		if(isNaN(document.eventSubmit.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Numeric';
		} else if((document.eventSubmit.endTimeMins.value > 59) || (document.eventSubmit.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n*End Minute Must Be Between 0 and 59';
		}//end if
		
		if(document.eventSubmit.locName.value == ''){
			dirty = 1;
			warn = warn + '\n*Location Name is Required';
		}//end if
		
		if(document.eventSubmit.contactEmail.value != '' && chkEmail(document.eventSubmit.contactEmail) == 0){
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
	
	function chkEmail(obj){
		var x = obj.value;
		var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if (filter.test(x)) {
			return 1;
		} else {
			return 0;
		}//end if
	}//end checkMail(object)
	
	function togOverride(){
		if(document.eventSubmit.overridetime.checked == true){
			document.eventSubmit.specialtimeall.disabled = false;
			document.eventSubmit.specialtimetbd.disabled = false;
			document.eventSubmit.startTimeHour.disabled = true;
			document.eventSubmit.startTimeMins.disabled = true;
			document.eventSubmit.startTimeAMPM.disabled = true;
			document.eventSubmit.endTimeHour.disabled = true;
			document.eventSubmit.endTimeMins.disabled = true;
			document.eventSubmit.endTimeAMPM.disabled = true;
			document.eventSubmit.ignoreendtime.disabled = true;
		} else {
			document.eventSubmit.specialtimeall.disabled = true;
			document.eventSubmit.specialtimetbd.disabled = true;
			document.eventSubmit.startTimeHour.disabled = false;
			document.eventSubmit.startTimeMins.disabled = false;
			document.eventSubmit.startTimeAMPM.disabled = false;
			if(document.eventSubmit.ignoreendtime.checked == false){
				document.eventSubmit.endTimeHour.disabled = false;
				document.eventSubmit.endTimeMins.disabled = false;
				document.eventSubmit.endTimeAMPM.disabled = false;
			}//end if
			document.eventSubmit.ignoreendtime.disabled = false;
		}//end if
	}//end togOverride()
	
	function togEndTime(){
		if(document.eventSubmit.ignoreendtime.checked == true){
			document.eventSubmit.endTimeHour.disabled = true;
			document.eventSubmit.endTimeMins.disabled = true;
			document.eventSubmit.endTimeAMPM.disabled = true;
		} else {
			document.eventSubmit.endTimeHour.disabled = false;
			document.eventSubmit.endTimeMins.disabled = false;
			document.eventSubmit.endTimeAMPM.disabled = false;
		}//end if
	}//end togEndTime()
	
	function togRegistration(){
		if(document.eventSubmit.eventRegistration.value == 0){
			document.eventSubmit.eventRegAvailable.disabled = true;
		} else {
			document.eventSubmit.eventRegAvailable.disabled = false;
		}//end if
	}//end togRegistration()
	
	var calx = new CalendarPopup();
	</script>
	
<?	if(!isset($eventID)){
		appInstructions(0, "Editing_Events", "Event Edit", "You can make changes to the event via the form below then click 'Save Event'.<br>If you make a mistake and wish to start over click the 'Reset Form' button below.<br><br>(<span class=\"eventReqTag\">*</span>) = Required Fields<br>(<span style=\"color: blue;\">*</span>) = Optional Fields, but required for dynamic driving directions<br>(<span style=\"color: green;\">*</span>) = Required for events <b>with registration</b>");
	} else {
		appInstructions(0, "Group_Editing_Events", "Group Event Edit", "You can make changes to the events via the form below then click 'Save Event'.<br>If you make a mistake and wish to start over click the 'Reset Form' button below.<br><br>(<span class=\"eventReqTag\">*</span>) = Required Fields<br>(<span style=\"color: blue;\">*</span>) = Optional Fields, but required for dynamic driving directions<br>(<span style=\"color: green;\">*</span>) = Required for events <b>with registration</b>");
	}//end if
	echo "<br>";
	
	if(isset($_GET['oID'])){	?>
		<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventorphan" class="main">&laquo;&laquo;&nbsp;Return to Orphan Event Report</a></div>
	<?
	}//end if?>
	<b>This event has been viewed <?echo $views;?> times.</b><br><br>
	<form id="eventSubmit" name="eventSubmit" method="post" action="<?echo CalAdminRoot . "/" . HC_EventEditAction;?>" onSubmit="return chkFrm();">
	<input type="hidden" name="eID" id="eID" value="<?echo $eID;?>">
<?	if(isset($editString)){
		$resultD = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($editString) . ")");
		if(hasRows($resultD)){	?>
		<b>You are editing this event for the following dates:</b><br>
		<table cellpadding="0" cellspacing="0" border="0">
		<?	$cnt = 0;
			while($row = mysql_fetch_row($resultD)){	
				if($cnt % 7 == 0){
					echo "</tr><tr>";
				}//end if	?>
				<td class="main" width="75"><?echo stampToDate($row[0], "m/d/Y");?></td>
		<?		$cnt++;
			}//end while
		?>	</table><br>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><input type="checkbox" name="makeseries" id="makeseries"></td>
				<td class="main"><label for="makeseries">Combine Events to New Series</label></td>
			</tr>
		</table>
		<br>
<?		}//end if
	}//end if	?>
	
<?	if(isset($editString)){	?>
	<input type="hidden" name="editString" id="editString" value="<?echo $editString;?>">
<?	}//end if
	
	if($chkDate < date("Y-m-d")){
	?>
	<b><span style="color: crimson;">Attention:</span> This is a Past Event</b><br><br>
	<?
	}//end if	?>
	<table cellpadding="0" cellspacing="0" border="0" class="eventSubmitTable">
		<tr>
			<td colspan="2" class="eventMain"><b>Event Details</b></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Title:</td>
			<td class="eventMain">
				<input size="65" maxlength="150" type="text" name="eventTitle" id="eventTitle" value="<?if(isset($eventTitle)){echo $eventTitle;}//end if?>" class="input"><span class="eventReqTag"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80" valign="top">Description:</td>
			<td class="eventMain">
			<?	if(isset($eventDesc)){
					$passContent = $eventDesc;
				} else {
					$passContent = "";
				}//end if?>
				<?makeTinyMCE("eventDescription", "99%", "advanced", $passContent);?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" border="0">
				<?if(!isset($editString)){	?>
					<tr>
						<td class="eventMain" width="80">Event Date:</td>
						<td class="eventMain">
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td><input size="10" maxlength="10" type="text" name="eventDate" id="eventDate" value="<?echo $eventDate;?>" class="input"></td>
									<td><span class="eventReqTag"> *</span></td>
									<td>&nbsp;<a href="javascript:;" onclick="calx.select(document.forms[0].eventDate,'anchor1','MM/dd/yyyy'); return false;" name="anchor1" id="anchor1"><img src="<?echo CalAdminRoot;?>/images/datepicker/cal.gif" width="16" height="16" border="0" alt=""></a>&nbsp;</td>
								</tr>
							</table>
						</td>
						<td width="20"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<?}//end if	?>
					<tr>
						<td class="eventMain" width="80">Start Time:</td>
						<td class="eventMain">
							<table cellpadding="1" cellspacing="0" border="0">
								<tr>
									<td class="eventMain"><input <?if($tbd > 0){echo "DISABLED";}//end if?> size="2" maxlength="2" type="text" name="startTimeHour" id="startTimeHour" value="<?echo $startTimeHour;?>" class="input"></td>
									<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
									<td class="eventMain"><input <?if($tbd > 0){echo "DISABLED";}//end if?> size="2" maxlength="2" type="text" name="startTimeMins" id="startTimeMins" value="<?echo $startTimeMins;?>" class="input"></td>
									<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.startTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
									<td class="eventMain">
										<select <?if($tbd > 0){echo "DISABLED";}//end if?> name="startTimeAMPM" id="startTimeAMPM" class="input">
											<option <?if($startTimeAMPM == 'AM'){echo "SELECTED";}?> value="AM">AM</option>
											<option <?if($startTimeAMPM == 'PM'){echo "SELECTED";}?> value="PM">PM</option>
										</select>
									</td>
									<td><span class="eventReqTag"> *</span></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td class="eventMain" width="70">End Time:</td>
						<td class="eventMain">
							<table cellpadding="1" cellspacing="0" border="0">
								<tr>
									<td class="eventMain"><input <?if(isset($noEndTime) OR $tbd > 0){echo "DISABLED";}//end if?> size="2" maxlength="2" type="text" name="endTimeHour" id="endTimeHour" value="<?echo $endTimeHour;?>" class="input"></td>
									<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeHour,1,12)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeHour,-1,12)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
									<td class="eventMain"><input <?if(isset($noEndTime) OR $tbd > 0){echo "DISABLED";}//end if?> size="2" maxlength="2" type="text" name="endTimeMins" id="endTimeMins" value="<?echo $endTimeMins;?>" class="input"></td>
									<td class="eventMain"><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeMins,1,59)"><img src="<?echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0"></a><br><a href="javascript:;" onClick="chngClock(document.eventSubmit.endTimeMins,-1,59)"><img src="<?echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0"></a></td>
									<td class="eventMain">
										<select <?if(isset($noEndTime) OR $tbd > 0){echo "DISABLED";}//end if?> name="endTimeAMPM" id="endTimeAMPM" class="input">
											<option <?if($endTimeAMPM == 'AM'){echo "SELECTED";}?> value="AM">AM</option>
											<option <?if($endTimeAMPM == 'PM'){echo "SELECTED";}?> value="PM">PM</option>
										</select>
									</td>
									<td><span class="eventReqTag"> *</span></td>
									<td>
										<td width="10">&nbsp;</td>
										<td><input <?if(isset($noEndTime)){echo "CHECKED";}//end if?> <?if($tbd > 0){echo "DISABLED";}//end if?>  type="checkbox" name="ignoreendtime" id="ignoreendtime" input="eventInput" onClick="togEndTime();"></td>
										<td class="eventMain"><label for="ignoreendtime">No End Time</label></td>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td><input <?if($tbd > 0){echo "CHECKED";}//end if?>  type="checkbox" name="overridetime" id="overridetime" input="eventInput" onClick="togOverride();"></td>
									<td class="eventMain"><label for="overridetime">Override Event Times</label>&nbsp;&nbsp;(<i>Time Values Above Ignored When Checked</i>)</td>
								</tr>
								<tr>
									<td colspan="2">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td rowspan="2" width="20">&nbsp;</td>
												<td><input <?if($tbd == 0){echo "DISABLED";}elseif($tbd == 1){echo "CHECKED";}//end if?> type="radio" name="specialtime" id="specialtimeall" value="allday" CHECKED></td>
												<td class="eventMain"><label for="specialtimeall">All Day Event</label></td>
											</tr>
											<tr>
												<td><input <?if($tbd == 0){echo "DISABLED";}elseif($tbd == 2){echo "CHECKED";}//end if?> type="radio" name="specialtime" id="specialtimetbd" value="tbd"></td>
												<td class="eventMain"><label for="specialtimetbd">Event Times To Be Announced</label></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
		<tr>
			<td colspan="2" class="eventMain"><b>Event Settings</b></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Status:</td>
			<td>
				<input type="hidden" name="prevStatus" id="prevStatus" value="<?echo $eventStatus;?>">
				<select name="eventStatus" id="eventStatus" class="input">
					<option <?if($eventStatus == 1){echo "selected";}//end if?> value="1">Approved -- Show on Calendar</option>
					<option <?if($eventStatus == 2){echo "selected";}//end if?> value="2">Pending -- Hidden on Calendar</option>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Billboard:</td>
			<td>
				<select name="eventBillboard" id="eventBillboard" class="input">
					<option <?if($eventBillboard == 0){echo "selected";}//end if?> value="0">Do Not Show On Billboard</option>
					<option <?if($eventBillboard == 1){echo "selected";}//end if?> value="1">Show On Billboard</option>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" valign="top">Registration:</td>
			<td>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td colspan="2">
							<select name="eventRegistration" id="eventRegistration" onChange="togRegistration();" class="input">
								<option <?if($allowRegistration == 0){echo "selected";}//end if?> value="0">Do Not Allow Registration</option>
								<option <?if($allowRegistration == 1){echo "selected";}//end if?> value="1">Allow Registration</option>
							</select>
						</td>
					</tr>
					<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
					<tr>
						<td class="eventMain">Limit:&nbsp;</td>
						<td class="eventMain">
							<input <?if($allowRegistration == 0){echo "DISABLED";}//end if?> size="4" maxlength="4" type="text" name="eventRegAvailable" id="eventRegAvailable" value="<?echo $maxRegistration;?>" class="input">
							(0 = unlimited)
						</td>
					</tr>
				</table>
			</td>
		</tr>
	<?php
	if($allowRegistration == 1){
	?>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td>&nbsp;</td>
			<td class="eventMain">
				<?php
					$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
					$regUsed = mysql_result($result,0,0);
					$regAvailable = $maxRegistration;
					
						if($maxRegistration == 0) {
							echo "<b>" . $regUsed . " Total Registrants</b>";
						} elseif($maxRegistration <= mysql_result($result,0,0)){
						?>
							<img src="<?echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;">
						<?
							echo "<b>" . $regUsed . " Total Registrants</b> -- Registering Overflow Only";
						} else {
							if($regAvailable > 0){
								if($regUsed > 0){
									$regWidth = ($regUsed / $regAvailable) * 100;
									$fillWidth = 100 - $regWidth;
								} else {
									$regWidth = 0;
									$fillWidth = 100;
								}//end if
							?>
								<img src="<?echo CalAdminRoot;?>/images/meter/meterGreen.gif" width="<?echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;"><img src="<?echo CalAdminRoot;?>/images/meter/meterLGray.gif" width="<?echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;">
						<?
								echo "<b>" . $regUsed . " Total Registrants</b>";
							}//end if
						}//end if
					?>
					<br><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
					 <a href="<?echo CalAdminRoot;?>/index.php?com=eventregister&eID=<?echo $eID;?>" class="main">Click to Add Registrant</a>
			</td>
		</tr>
	<?
	}//end if
	?>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td valign="top" class="eventMain">Category:</td>
			<td class="eventMain">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
				<?	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "eventcategories.EventID
								FROM " . HC_TblPrefix . "categories 
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID AND " . HC_TblPrefix . "eventcategories.EventID = " . cIn($eID) . ") 
								WHERE " . HC_TblPrefix . "categories.IsActive = 1
								ORDER BY CategoryName";
					$result = doQuery($query);
					$cnt = 0;
					$curCat = "";
					while($row = mysql_fetch_row($result)){
						if($row[3] > 0){
							if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
						?>
							<td class="eventMain"><input <?if($row[6] != ''){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
							<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
						<?
							$cnt = $cnt + 1;
						}//end if
					
					}//end while
				?>
					</tr>
				</table>
				<?	if($cnt > 1){	?>
					<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
					[ <a class="main" href="javascript:;" onClick="checkAllArray('eventSubmit', 'catID[]');">Select All Categories</a> 
					&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('eventSubmit', 'catID[]');">Deselect All Categories</a> ]
					<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
				<?	}//end if	?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
		<tr>
			<td colspan="2" class="eventMain"><b>Location Info</b></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Name:</td>
			<td class="eventMain">
				<input maxlength="50" size="20" type="text" name="locName" id="locName" value="<?if(isset($locName)){echo $locName;}//end if?>" class="input"><span class="eventReqTag"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Address:</td>
			<td class="eventMain">
				<input maxlength="75" size="25" type="text" name="locAddress" id="locAddress" value="<?if(isset($locAddress)){echo $locAddress;}//end if?>" class="input"><span style="color: blue;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80"></td>
			<td class="eventMain">
				<input maxlength="75" size="25" type="text" name="locAddress2" id="locAddress2" value="<?if(isset($locAddress2)){echo $locAddress2;}//end if?>" class="input">
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">City:</td>
			<td class="eventMain">
				<input maxlength="50" size="15" type="text" name="locCity" id="locCity" value="<?if(isset($locCity)){echo $locCity;}//end if?>" class="input"><span style="color: blue;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">State:</td>
			<td class="eventMain">
				<?php
					$state = $locState;
					include('../events/includes/selectStates.php');
				?><span style="color: blue;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Zip Code:</td>
			<td class="eventMain">
				<input maxlength="11" size="11" type="text" name="locZip" id="locZip" value="<?if(isset($locZip)){echo $locZip;}//end if?>" class="input"><span style="color: blue;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
		<tr>
			<td colspan="2" class="eventMain"><b>Contact Info</b></td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Name:</td>
			<td class="eventMain">
				<input maxlength="50" size="25" type="text" name="contactName" id="contactName" value="<?if(isset($contactName)){echo $contactName;}//end if?>" class="input"><span style="color: green;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Email:</td>
			<td class="eventMain">
				<input maxlength="75" size="30" type="text" name="contactEmail" id="contactEmail" value="<?if(isset($contactEmail)){echo $contactEmail;}//end if?>" class="input"><span style="color: green;"> *</span>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Phone:</td>
			<td class="eventMain">
				<input maxlength="15" size="15" type="text" name="contactPhone" id="contactPhone" value="<?if(isset($contactPhone)){echo $contactPhone;}//end if?>" class="input">
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" width="80">Website:</td>
			<td class="eventMain">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td>
							<input maxlength="100" size="30" type="text" name="contactURL" id="contactURL" value="<?if(isset($contactURL)){echo $contactURL;}//end if?>" class="input">
						</td>
						<td>
							<?php
								if($contactURL != "http://"){
							?>
								&nbsp;<a href="<?echo $contactURL?>" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconWebsite.gif" width="16" height="16" alt="" border="0"></a>
							<?php
								} else {
							?>
								&nbsp;
							<?php
								}//end if
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">&nbsp;</td>
			<td class="eventMain">
				<input type="submit" name="submit" value=" Save Event " class="button">&nbsp;&nbsp;
				<input type="reset" name="reset" value=" Reset Form " class="button">
			</td>
		</tr>
	</table>
	</form>
<?
}//end if?>