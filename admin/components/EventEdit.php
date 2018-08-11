<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
	
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	} elseif(isset($_POST['eventID'])){
		$editString = "0";
		$eventID = $_POST['eventID'];
		$eID = $eventID[0];
		foreach ($eventID as $val){
			$editString .= "," . $val;
		}//end for
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
	$hasLoc = (hasRows($resultL)) ? 1 : 0;
	
	$result = doQuery("SELECT e.*, l.Name
					FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
					WHERE e.PkID = '" . cIn($eID) . "' AND e.IsActive = 1");
	if(!hasRows($result)){
		echo '<br />';
		echo '<div style="padding-bottom:150px;">' . $hc_lang_event['EditWarning'] . '</div>';
	} else {
		$hrFormat = ($hc_timeInput == 23) ? "H" : "h";
		$minHr = ($hc_timeInput == 23) ? 0 : 1;
		$eventStatus = cOut(mysql_result($result,0,17));
		$eventBillboard = cOut(mysql_result($result,0,18));
		$eventTitle = cOut(mysql_result($result,0,1));
		$eventDesc = cOut(mysql_result($result,0,8));
		$locAddress = cOut(mysql_result($result,0,3));
		$locAddress2 = cOut(mysql_result($result,0,4));
		$locCity = cOut(mysql_result($result,0,5));
		$state = cOut(mysql_result($result,0,6));
		$locZip = cOut(mysql_result($result,0,7));
		$tbd = cOut(mysql_result($result,0,11));
		$startTime = cOut(mysql_result($result,0,10));
		$endTime = cOut(mysql_result($result,0,12));
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
		$contactPhone = cOut(mysql_result($result,0,15));
		$allowRegistration = cOut(mysql_result($result,0,25));
		$maxReg = cOut(mysql_result($result,0,26));
		$views = cOut(mysql_result($result,0,28));
		$locID = cOut(mysql_result($result,0,35));
		$locName = ($locID == 0) ? cOut(mysql_result($result,0,2)) : cOut(mysql_result($result,0,40));
		$cost = cOut(mysql_result($result,0,36));
		$locCountry = cOut(mysql_result($result,0,37));
		$contactURL = (mysql_result($result,0,24) == '') ? 'http://' : cOut(mysql_result($result,0,24));
		$eventDate = stampToDate(mysql_result($result,0,9), $hc_cfg24);
		$AllDay = '';
		
		if($tbd == 0){
			$startTimeParts = explode(":", $startTime);
			$startTimeHour = date($hrFormat, mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
			$startTimeMins = $startTimeParts[1];
			$startTimeAMPM = date("A", mktime($startTimeParts[0], $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
			
			if($endTime != ''){
				$endTimeParts = explode(":", $endTime);
				$endTimeHour = date($hrFormat, mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
				$endTimeMins = $endTimeParts[1];
				$endTimeAMPM = date("A", mktime($endTimeParts[0], $endTimeParts[1], $endTimeParts[2], 1, 1, 1971));
			} else {
				$endTimeHour = date($hrFormat, mktime($startTimeParts[0] + 1, $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$endTimeMins = $startTimeParts[1];
				$endTimeAMPM = date("A", mktime($startTimeParts[0] + 1, $startTimeParts[1], $startTimeParts[2], 1, 1, 1971));
				$noEndTime = 1;
			}//end if
		} else {
			$startTimeHour = date($hrFormat);
			$startTimeMins = "00";
			$startTimeAMPM = date("A");
			$endTimeHour = date($hrFormat, mktime($startTimeHour + 1, 0, 0, 1, 1, 1971));
			$endTimeMins = "00";
			$endTimeAMPM = date("A", mktime($startTimeHour + 1, 0, 0, 1, 1, 1971));
		}//end if
		
		if(isset($_GET['msg'])){
			switch ($_GET['msg']){
				case "1" :
					feedback(1,$hc_lang_event['Feed01']);
					break;
				case "2" :
					feedback(1,$hc_lang_event['Feed02']);
					break;
				case "3" :
					feedback(1,$hc_lang_event['Feed03']);
					break;
				case "4" :
					feedback(1,$hc_lang_event['Feed04']);
					break;
				case "5" :
					feedback(1,$hc_lang_event['Feed05']);
					break;
				case "6" :
					feedback(1,$hc_lang_event['Feed06']);
					break;
				case "7" :
					feedback(2,$hc_lang_event['Feed07']);
					break;
				case "8" :
					feedback(2,$hc_lang_event['Feed08']);
					break;
				case "9" :
					feedback(1,$hc_lang_event['Feed09']);
					break;
				case "10" :
					feedback(1,$hc_lang_event['Feed10']);
					break;
				case "11" :
					feedback(1,$hc_lang_event['Feed11']);
					break;
			}//end switch
		}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function togRegistrants(){
		if(document.getElementById('registrant').style.display == 'none'){
			document.getElementById('registrant').style.display = 'block';
			document.frmEventEdit.eventRegistrants.value = '<?php echo $hc_lang_event['RegButton1a'];?>';
		} else {
			document.getElementById('registrant').style.display = 'none';
			document.frmEventEdit.eventRegistrants.value = '<?php echo $hc_lang_event['RegButton1b'];?>';
		}//end if
	}//end showPanel()
	
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_event['Valid01b'];?>";
		
		if(document.frmEventEdit.eventRegistration.value == 1){
			if(isNaN(document.frmEventEdit.eventRegAvailable.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
			} else if(document.frmEventEdit.eventRegAvailable.value < 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid60'];?>';
			}//end if
			
			if(document.frmEventEdit.contactName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
			}//end if
			
			if(document.frmEventEdit.contactEmail.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid04'];?>';
			}//end if
		}//end if
		
		if(validateCheckArray('frmEventEdit','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid15'];?>';
		}//end if
	
		if(document.frmEventEdit.eventTitle.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid13'];?>';
		}//end if
		
		if(!isDate(document.frmEventEdit.eventDate.value, '<?php echo $hc_cfg51;?>')){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid24'] . " " . strtolower($hc_cfg51);?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid05'];?>';
		} else if((document.frmEventEdit.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.startTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid06'] . " " . $minHr . " - " . $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.startTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid07'];?>';
		} else if((document.frmEventEdit.startTimeMins.value > 59) || (document.frmEventEdit.startTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid08'];?> 0 - 59';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeHour.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid09'];?>';
		} else if((document.frmEventEdit.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventEdit.endTimeHour.value < <?php echo $minHr;?>)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid10'] . " " . $minHr . " - " . $hc_timeInput;?>';
		}//end if
		
		if(isNaN(document.frmEventEdit.endTimeMins.value) == true){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid11'];?>';
		} else if((document.frmEventEdit.endTimeMins.value > 59) || (document.frmEventEdit.endTimeMins.value < 0)) {
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid12'];?> 0 - 59';
		}//end if
		
<?php 	if($hasLoc > 0){	?>
		if(document.frmEventEdit.locPreset.value == 0){
			if(document.frmEventEdit.locName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
			}//end if
		}//end if
<?php 	} else {	?>
		if(document.frmEventEdit.locName.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
		}//end if
<?php 	}//end if	?>

		if(compareDates(document.frmEventEdit.eventDate.value, '<?php echo $hc_cfg51;?>', '<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_cfg51;?>') == 0){
			if(!confirm('<?php echo $hc_lang_event['Valid18'] . "\\n\\n          " . $hc_lang_event['Valid19'] . "\\n          " . $hc_lang_event['Valid20'];?>')){
				return false;
			}//end if
		}//end if
	
		if(document.frmEventEdit.contactEmail.value != '' && chkEmail(document.frmEventEdit.contactEmail) == 0){
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
	
	function sendReg(eID){
		window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterSendRoster.php?eID=' + eID);
	}//end if
	
	function delReg(dID){
		if(confirm('<?php echo $hc_lang_event['Valid47'] . "\\n\\n          " . $hc_lang_event['Valid48'] . "\\n          " . $hc_lang_event['Valid49'];?>')){
			window.location.href=('<?php echo CalAdminRoot;?>/components/RegisterAddAction.php?dID=' + dID + '&eID=<?php echo $eID;?>');
		}//end if
	}//end delReg()
	
	var calx = new CalendarPopup("dsCal");
	calx.setCssPrefix("hc_");

	var tweetPrefix = '<?php echo $hc_lang_event['TweetUpdate'];?>';
	<?php include('includes/java/events.php');?>
	//-->
	</script>
<?php
	if(!isset($eventID)){
		appInstructions(0, "Editing_Events", $hc_lang_event['TitleEdit'], $hc_lang_event['InstructEdit']);
	} else {
		appInstructions(0, "Group_Editing_Events", $hc_lang_event['TitleGroup'], $hc_lang_event['InstructGroup']);
	}//end if
	echo "<br />";?>
	
	<form id="frmEventEdit" name="frmEventEdit" method="post" action="<?php echo CalAdminRoot . "/components/EventEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="eID" id="eID" value="<?php echo $eID;?>" />
<?php
	if(isset($editString)){
		echo '<input type="hidden" name="editString" id="editString" value="' . $editString . '" />';
		$resultD = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID IN(" . cIn($editString) . ")");
		if(hasRows($resultD)){
			echo '<b>' . $hc_lang_event['GroupNotice'] . '</b><br />';
			$cnt = 0;
			while($row = mysql_fetch_row($resultD)){
				if($cnt > 0){echo ", ";}
				echo stampToDate($row[0], $hc_cfg24);
				$cnt++;
			}//end while
			echo '<br /><br />';
			echo '<label for="makeseries"><input type="checkbox" name="makeseries" id="makeseries" class="noBorderIE" />' . $hc_lang_event['GroupCombine'] . '</label>';
			echo '<br />';
		}//end if
	}//end if	?>
	
	<fieldset>
		<legend><?php echo $hc_lang_event['EventDetail'];?></legend>
		<div class="frmReq">
			<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
			<input onblur="buildTweet();" name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" value="<?php echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
	<?php	makeTinyMCE('eventDescription', '525px', 'advanced', $eventDesc);?>
		</div>
<?php	if(!isset($editString)){	?>
		<div class="frmReq">
			<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
			<input name="eventDate" id="eventDate" type="text" value="<?php echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventEdit.eventDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
	    </div>
<?php	}//end if	?>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['StartTime'];?></label>
			<table cellpadding="1" cellspacing="0" border="0">
				<tr>
					<td><input onblur="buildTweet();" name="startTimeHour" id="startTimeHour" type="text" value="<?php echo $startTimeHour;?>" size="2" maxlength="2" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input onblur="buildTweet();" name="startTimeMins" id="startTimeMins" type="text" value="<?php echo $startTimeMins;?>" size="2" maxlength="2"  <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select onclick="buildTweet();" name="startTimeAMPM" id="startTimeAMPM" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}?>>
							<option <?php if($startTimeAMPM == "AM"){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?php if($startTimeAMPM == "PM"){echo "selected=\"selected\"";}?> value="PM">PM</option>
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
					<td><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo $endTimeHour;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeHour,-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<td><input name="endTimeMins" id="endTimeMins" type="text" value="<?php echo $endTimeMins;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
					<td><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventEdit.endTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
			<?php 	if($hc_timeInput == 12){	?>
					<td>
						<select name="endTimeAMPM" id="endTimeAMPM" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}?>>
							<option <?php if($endTimeAMPM == "AM"){echo "selected=\"selected\"";}?> value="AM">AM</option>
							<option <?php if($endTimeAMPM == "PM"){echo "selected=\"selected\"";}?> value="PM">PM</option>
						</select>
					</td>
			<?php 	}//end if	?>
					<td><label for="ignoreendtime" style="padding-left:20px;" class="radio"><?php echo $hc_lang_event['NoEndTime'];?></label></td>
					<td><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" <?php if(isset($noEndTime)){echo "checked=\"checked\" ";}//end if?> <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
				</tr>
			</table>
	    </div>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<label for="overridetime"><?php echo str_replace(" ","&nbsp;",$hc_lang_event['Override']);?></label>&nbsp;<input <?php if($tbd > 0){echo "checked=\"checked\" ";}//end if?>type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" />
			<br />
			<label>&nbsp;</label>
			<label for="specialtimeall" class="radioWide"><input type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 1){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['AllDay'];?></label>
			<br /><br />
			<label>&nbsp;</label>
			<label for="specialtimetbd" class="radioWide"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 2){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['TBA'];?></label>
			<br />
		</div>
		<br />
		<div class="frmOpt">
			<label for="cost"><?php echo $hc_lang_event['Cost'];?></label>
			<input name="cost" id="cost" type="text" value="<?php echo $cost;?>" size="25" maxlength="50" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['RegTitle'];?></legend>
		<div class="frmOpt">
			<label for="eventRegistration"><?php echo $hc_lang_event['Registration'];?></label>
			<select name="eventRegistration" id="eventRegistration" onchange="togRegistration();">
				<option <?php if($allowRegistration == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_event['Reg0'];?></option>
				<option <?php if($allowRegistration == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_event['Reg1'];?></option>
				<option <?php if($allowRegistration == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_event['Reg2'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventRegAvailable"><?php echo $hc_lang_event['Limit'];?></label>
			<input <?php if($allowRegistration == 0){echo 'disabled="disabled"';}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?php echo $maxReg;?>" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
		</div>
<?php 	if($allowRegistration == 1){	?>
		<div class="frmOpt">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . $eID . "'");
			$regUsed = mysql_result($result,0,0);
			$regAvailable = $maxReg;
			
			if($maxReg == 0) {
				echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg'] . "</b>";
			} elseif($maxReg <= mysql_result($result,0,0)){	?>
				<img src="<?php echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;" />
		<?php 	echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg'] . " </b> -- " . $hc_lang_event['Overflow'];
			} else {
				if($regAvailable > 0){
					if($regUsed > 0){
						$regWidth = ($regUsed / $regAvailable) * 100;
						$fillWidth = 100 - $regWidth;
					} else {
						$regWidth = 0;
						$fillWidth = 100;
					}//end if	?>
					<img src="<?php echo CalAdminRoot;?>/images/meter/meterGreen.png" width="<?php echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;" /><img src="<?php echo CalAdminRoot;?>/images/meter/meterLGray.png" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;" />
		<?php 		echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg']  . "</b>";
				}//end if
			}//end if	?>
		</div>
		<label>&nbsp;</label>
		<input style="width: 120px;" name="eventRegistrants" id="eventRegistrants" type="button" value="<?php if(isset($_GET['r'])){echo $hc_lang_event['RegButton1a'];}else{echo $hc_lang_event['RegButton1b'];}?>" onclick="togRegistrants();" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 160px;" name="eventSendRoster" id="eventSendRoster" type="button" value="<?php echo $hc_lang_event['RegButton2'];?>" onclick="javascript:if(confirm('<?php echo $hc_lang_event['Valid50'] . "\\n\\n          " . $hc_lang_event['Valid51'] . "\\n          " . $hc_lang_event['Valid52'];?>')){sendReg(<?php echo $eID;?>);};" class="button" />&nbsp;&nbsp;&nbsp;<input style="width: 120px;" name="addRegistrant" id="addRegistrant" type="button" value="<?php echo $hc_lang_event['RegButton3'];?>" onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=eventregister&amp;eID=<?php echo $eID;?>';" class="button" />
		<br /><br />
		<div id="registrant" style="display:<?php echo (isset($_GET['r'])) ? 'block' : 'none';?>;">
			<label>&nbsp;</label>
	<?php 	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . cIn($eID) . " ORDER BY RegisteredAt, GroupID, PkID");
			if(hasRows($result)){
				echo '<table cellpadding="2" cellspacing="0" border="0"><tr>';
				echo '<td width="160"><b>' . $hc_lang_event['Registrant'] . '</b></td>';
				echo '<td width="90"><b>' . $hc_lang_event['Phone'] . '</b></td>';
				echo '<td width="150"><b>' . $hc_lang_event['RegisteredAt'] . '</b></td>';
				echo '<td width="50" align="center">&nbsp;</td></tr>';
		 		$cnt = 0;
				$shown = false;
				while($row = mysql_fetch_row($result)){
					if($cnt == $maxReg && $maxReg > 0 && !$shown){
						$shown = true;
						$cnt = 0;
						echo '<tr><td colspan="4"><br /><b>' . $hc_lang_event['OverflowReg'] . '</b></td></tr>';
					}//end if
					
					echo '<tr>';
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($cnt + 1 < 10) ? '0' . strval($cnt + 1) : $cnt + 1;
					echo ')&nbsp;<a href="mailto:' . cOut($row[2]) . '" class="main">' . cOut($row[1]) . '</a></td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($row[3] != '') ? cOut($row[3]) : 'N/A';
					echo '</td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main">' : '<td class="tblListHL">';
					echo ($row[11] != '') ? stampToDate(cOut($row[11]), $hc_cfg24 . ' at ' . $hc_cfg23) : 'N/A';
					echo '</td>';
					
					echo ($cnt % 2 == 0) ? '<td class="main" align="center">' : '<td class="tblListHL" align="center">';
					echo '<a href="' . CalAdminRoot . '/index.php?com=eventregister&amp;rID=' . $row[0] . '&amp;eID=' . $eID . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
					echo '<a href="javascript:;" onclick="delReg(' . $row[0] . ');return false;" class="main"><img src="' . CalAdminRoot . '/images/icons/iconDelete.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></td>';
					echo '</tr>';
			 		++$cnt;
				}//end while
			echo '</table>';
			} else {
				echo $hc_lang_event['NoReg'] . '<br /><br />';
			}//end if	?>
		</div>
<?php 	}//end if?>
	</fieldset>
	<br />
	<fieldset>
		<input type="hidden" name="prevStatus" id="prevStatus" value="<?php echo $eventStatus;?>" />
		<legend><?php echo $hc_lang_event['Settings'];?></legend>
		<div class="frmOpt">
			<label for="eventStatus"><?php echo $hc_lang_event['Status'];?></label>
			<select name="eventStatus" id="eventStatus">
				<option <?php if($eventStatus == 1){echo "selected=\"selected\"";}//end if?> value="1"><?php echo $hc_lang_event['Status1'];?></option>
				<option <?php if($eventStatus == 2){echo "selected=\"selected\"";}//end if?> value="2"><?php echo $hc_lang_event['Status2'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="eventBillboard"><?php echo $hc_lang_event['Billboard'];?></label>
			<select name="eventBillboard" id="eventBillboard">
				<option <?php if($eventBillboard == 0){echo "selected=\"selected\"";}//end if?> value="0"><?php echo $hc_lang_event['Billboard0'];?></option>
				<option <?php if($eventBillboard == 1){echo "selected=\"selected\"";}//end if?> value="1"><?php echo $hc_lang_event['Billboard1'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_event['Categories'];?></label>
		<?php	$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, ec.EventID as Selected
							FROM " . HC_TblPrefix . "categories c 
							 	LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
							WHERE c.ParentID = 0 AND c.IsActive = 1 
							GROUP BY c.PkID 
							UNION 
							SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, ec.EventID as Selected 
							FROM " . HC_TblPrefix . "categories c
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID AND ec.EventID = " . cIn($eID) . ")
							WHERE c.ParentID > 0 AND c.IsActive = 1 
							GROUP BY c.PkID
							ORDER BY Sort, ParentID, CategoryName";
				getCategories('frmEventEdit', 3, $query);?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Location'];?></legend>
		<input type="hidden" id="locPreset" name="locPreset" value="<?php echo $locID;?>" />
		<input type="hidden" id="locPresetName" name="locPresetName" value="<?php echo $locName;?>" />
		<div id="locSetting">
<?php 	if($locID > 0){
			echo "<div class=\"frmOpt\">";
			$resultL = doQuery("SELECT PkID, Name, Address, Address2, City, State, Zip, Country
								FROM " . HC_TblPrefix . "locations
								WHERE PkID = " . $locID);
			echo "<label>" . $hc_lang_event['CurLocation'] . "</label>";
			echo "<b>" . mysql_result($resultL,0,1) . "</b>";
			echo "</div><div class=\"frmOpt\">";
			echo "<label>&nbsp;</label>";

			$locOutput = buildAddress(htmlentities(mysql_result($resultL,0,2),ENT_QUOTES),htmlentities(mysql_result($resultL,0,3),ENT_QUOTES),htmlentities(mysql_result($resultL,0,4),ENT_QUOTES),htmlentities(mysql_result($resultL,0,5),ENT_QUOTES),htmlentities(mysql_result($resultL,0,6),ENT_QUOTES),htmlentities(mysql_result($resultL,0,7),ENT_QUOTES),0,$hc_lang_config['AddressType']);
			echo str_replace('<br />',', ',$locOutput);

			echo "&nbsp;&nbsp;<a href=\"javascript:;\" onclick=\"setLocation(0);\" class=\"eventMain\">" . $hc_lang_event['ChngLocation'] . "</a>";
			echo "</div>";
		}//end if	?>
		</div>
		<div id="locSearch"<?php if($locID > 0){echo " style=\"display:none;\"";}?>>
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
		</div>
		<div id="customLoc"<?php if($locID > 0){echo ' style="display:none;"';}?>>
		<div class="frmReq">
			<label for="locName"><?php echo $hc_lang_event['Name'];?></label>
			<input onblur="buildTweet();" <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locName" id="locName" value="<?php echo ($locID == 0) ? $locName : '';?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress"><?php echo $hc_lang_event['Address'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress" id="locAddress" value="<?php echo $locAddress;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
		</div>
		<div class="frmOpt">
			<label for="locAddress2"><?php echo $hc_lang_event['Address2'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress2" id="locAddress2" value="<?php echo $locAddress2;?>" type="text" maxlength="75" size="25" />
		</div>
	<?php
		$inputs = array(1 => array('City','locCity',$locCity),2 => array('Postal','locZip',$locZip));

		echo '<div class="frmOpt">';
		$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
		$second = ($first == 1) ? 2 : 1;

		echo '<label for="' . $inputs[$first][1] . '">' . $hc_lang_event[$inputs[$first][0]] . '</label>';
		echo '<input ';
		echo ($locID > 0) ? 'disabled="disabled"' : '';
		echo 'name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" value="' . $inputs[$first][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';

		if($hc_lang_config['AddressRegion'] != 0){
			if($locID > 0){
				$stateDisabled = 1;
				$state = $hc_cfg21;
			}//end if
			echo '<div class="frmOpt">';
			echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
			include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);
			echo '<span style="color: #0000FF;">*</span></div>';
		}//end if

		echo '<div class="frmOpt">';
		echo '<label for="' . $inputs[$second][1] . '">' . $hc_lang_event[$inputs[$second][0]] . '</label>';
		echo '<input ';
		echo ($locID > 0) ? 'disabled="disabled"' : '';
		echo 'name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="' . $inputs[$second][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
		echo '</div>';
		?>
		<div class="frmOpt">
			<label for="locCountry"><?php echo $hc_lang_event['Country'];?></label>
			<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCountry" id="locCountry" value="<?php echo $locCountry;?>" type="text" maxlength="50" size="10" />
		</div>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['Contact'];?></legend>
		<div class="frmOpt">
			<label for="contactName"><?php echo $hc_lang_event['Name'];?></label>
			<input name="contactName" id="contactName" type="text" value="<?php echo $contactName;?>" maxlength="50" size="20" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactEmail"><?php echo $hc_lang_event['Email'];?></label>
			<input name="contactEmail" id="contactEmail" type="text" value="<?php echo $contactEmail;?>" maxlength="75" size="30" /><span style="color: #008000;">*</span>
		</div>
		<div class="frmOpt">
			<label for="contactPhone"><?php echo $hc_lang_event['Phone'];?></label>
			<input name="contactPhone" id="contactPhone" type="text" value="<?php echo $contactPhone;?>" maxlength="25" size="20" />
		</div>
		<div class="frmOpt">
			<label for="contactURL"><?php echo $hc_lang_event['Website'];?></label>
			<input name="contactURL" id="contactURL" type="text" value="<?php echo $contactURL;?>" maxlength="100" size="40" />
	<?php 	if($contactURL != 'http://'){	?>
				<a href="<?php echo $contactURL;?>" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconWebsite.png" width="16" height="16" alt="" border="0" /></a>
	<?php 	}//end if	?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_event['DistPub'];?></legend>
	<?php
		$efID = $ebID = '';
		$tweets = array();
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = '" . cIn($eID) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efID = $row[1];
						break;
					case 2:
						$ebID = $row[1];
						break;
					case 3:
						$tweets[] = $row[1];
						break;
				}//end if
			}//end while
		}//end if
		echo '<i>' . $hc_lang_event['DistPubNotice'] . '</i><br /><br />';
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(5,6,36,37,38,39,46,47,57,58);");
		$goEventful = (mysql_result($result,2,1) != '' && mysql_result($result,3,1) != '' && mysql_result($result,4,1) != '') ? 1 : 0;
		$goEventbrite = (mysql_result($result,0,1) != '' && mysql_result($result,1,1) != '') ? 1 : 0;
		$goTwitter = (mysql_result($result,6,1) != '' && mysql_result($result,7,1) != '' && mysql_result($result,8,1) && mysql_result($result,9,1)) ? 1 : 0;
		
		if($efID != '' || $ebID != ''){
			echo '<b>' . $hc_lang_event['DistPubLinks'] . '</b>';
			echo ($efID != '') ? ' <a href="http://www.eventful.com/events/' . $efID . '" class="eventMain" target="_blank">' . $hc_lang_event['EventfulView'] . '</a>' : '';
			echo ($ebID != '') ? ' <a href="http://www.eventbrite.com/event/' . $ebID . '" class="eventMain" target="_blank">' . $hc_lang_event['EventbriteView'] . '</a>' : '';
			echo '<br /><br />';
		}//end if

		if(count($tweets) > 0){
			echo '<b>' . $hc_lang_event['DistPubTweets'] . '</b>';
			foreach($tweets as $val){
				echo ' <a href="http://twitter.com/' . $val . '" class="eventMain" target="_blank">' . substr($val,strpos($val, 'status/')+7,strlen($val)) . '</a>';
			}//end for
			echo '<br /><br />';
		}//end if

		echo '<div class="frmOpt"><label for="doEventful" class="radioDistPub"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById(\'eventful\'));" class="noBorderIE"';
		echo ($goEventful == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo ($efID != '') ? $hc_lang_event['EventfulLabelU'] . '</label></div>' : $hc_lang_event['EventfulLabelA'] . '</label></div>';
		echo '<div id="eventful" style="display:none;clear:both;">' . $hc_lang_event['EventfulSubmit'] . '</div>';

		echo '<div class="frmOpt" style="border-top:solid 1px #CCCCCC;"><label for="doEventbrite" class="radioDistPub"><input name="doEventbrite" id="doEventbrite" type="checkbox" onclick="toggleMe(document.getElementById(\'eventbrite\'));chkReg();" class="noBorderIE"';
		echo ($goEventbrite == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo ($ebID != '') ? $hc_lang_event['EventbriteLabelU'] . '</label></div>' : $hc_lang_event['EventbriteLabelA'] . '</label></div>';

		
		echo '<div id="eventbrite" style="display:none;clear:both;">';
		echo ($ebID == '') ? $hc_lang_event['EventbriteNotice'] : $hc_lang_event['EventbriteNoticeU'];
		if($ebID == ''){
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
		}//end if
		echo '</div>';

		echo '<div class="frmOpt" style="border-top:solid 1px #CCCCCC;"><label for="doTwitter" class="radioDistPub"><input name="doTwitter" id="doTwitter" type="checkbox" onclick="toggleMe(document.getElementById(\'twitter\'));buildTweet();" class="noBorderIE"';
		echo ($goTwitter == 1) ?  ' />&nbsp;' : ' disabled="disabled" />&nbsp;';
		echo $hc_lang_event['TwitterLabel'] . '</label></div>';
		echo '<div id="twitter" style="display:none;clear:both;padding:12px 5px 0px 5px;">';
		echo '<input name="tweetThis" id="tweetThis" type="text" size="45" maxlength="104" value="" style="width:75%;" />';
		echo '<br /><br />' . $hc_lang_event['TwitterNotice'];
		echo '</div>';
		?>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" <?php echo $hc_lang_event['Save'];?> " class="button" />
	</form>
<?php
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>