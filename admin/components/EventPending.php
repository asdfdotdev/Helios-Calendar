<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');

	$pubSub = ($hc_cfg1 == 1) ? $hc_lang_event['LinkPublicOn'] : $hc_lang_event['LinkPublicOff'];
	$hc_Side[] = array(CalAdminRoot . '/index.php?com=generalset','iconPublic.png',$pubSub,0);
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_event['Feed12']);
				break;
			case "2" :
				feedback(1, $hc_lang_event['Feed13']);
				break;
			case "3" :
				feedback(1, $hc_lang_event['Feed14']);
				break;
			case "4" :
				feedback(1, $hc_lang_event['Feed15']);
				break;
			case "5" :
				feedback(1, $hc_lang_event['Feed16']);
				break;
			case "6" :
				feedback(1, $hc_lang_event['Feed17']);
				break;
			case "8" :
				feedback(1, $hc_lang_event['Feed19']);
				break;
			case "9" :
				feedback(1, $hc_lang_event['Feed20']);
				break;
		}//end switch
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	
	if(!isset($_GET['sID']) && !isset($_GET['eID'])){
		$result = doQuery("SELECT PkID, Title, StartDate, SeriesID
							FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND 
										IsApproved = 2 AND 
										StartDate >= '" . date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y"))) . "' 
							ORDER BY SeriesID, SubmittedAt, StartDate, Title");
		if(hasRows($result)){
			appInstructions(0, "Pending_Events", $hc_lang_event['TitlePendingA'], $hc_lang_event['InstructPendingA']);
			$curSeries = "";
			$cnt = 0;	?>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			function chkFrm(){
				if(validateCheckArray('eventPending','eventID[]',1) == 1){
					alert('<?php echo $hc_lang_event['Valid56'];?>');
					return false;
				} else {
					if(confirm('<?php echo $hc_lang_event['Valid57'] . "\\n\\n          " . $hc_lang_event['Valid58'] . "\\n          " . $hc_lang_event['Valid59'];?>')){
						return true;
					} else {
						return false;
					}//end if
				}//end if
			}//end chkFrm()
			//-->
			</script>
			<form name="eventPending" id="eventPending" method="post" action="<?php echo CalAdminRoot?>/components/EventDelete.php" onsubmit="return chkFrm();">
			<input type="hidden" name="pID" id="pID" value="1" />
			<br />
			<div class="catCtrl">
				[ <a class="catLink" href="javascript:;" onclick="checkAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['SelectAll'];?></a>
				&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['DeselectAll'];?></a> ]
			</div>
	
	<?php 	while($row = mysql_fetch_row($result)){
				if($row[3] == '' && $cnt == 0){
					echo '<div class="pendingList">' . $hc_lang_event['PendingIndividual'] . '</div>';
		 		}//end if
				
				if($row[3] != '' && $curSeries != $row[3]){
					$cnt = 0;
					$curSeries = $row[3];
					echo '<div class="pendingList">';
					echo '<div class="pendingSeries">' . $hc_lang_event['PendingSeries'] . '</div>';
					echo '<div class="pendingSeriesTools"><a href="' . CalAdminRoot . '/index.php?com=eventpending&amp;sID=' . $row[3] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEditGroup.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
					echo '&nbsp;</div>';
		 		}//end if
		 		
		 		echo ($cnt % 2 == 1) ? '<div class="pendingListTitleHL">' : '<div class="pendingListTitle">';
		 		echo cOut($row[1]) . '</div>';
				
		 		echo ($cnt % 2 == 1) ? '<div class="pendingListDateHL">' : '<div class="pendingListDate">';
		 		echo StampToDate($row[2], $hc_cfg24) . '</div>';

		 		echo ($cnt % 2 == 1) ? '<div class="pendingListToolsHL">' : '<div class="pendingListTools">';
		 		echo '<div class="hc_align">&nbsp;<a href="' . CalAdminRoot . '/index.php?com=eventpending&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;</div>';
				echo '&nbsp;<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />&nbsp;</div>';
					
	 			++$cnt;
			}//end while	?>
			
			<div class="catCtrl" style="padding-top:10px;">
				[ <a class="catLink" href="javascript:;" onclick="checkAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['SelectAll'];?></a>
				&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['DeselectAll'];?></a> ]
			</div>
			<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_event['DeclineDelete'];?>" class="button" />
			</form>
<?php 	} else {
			echo '<br />';
			echo '<div style="font-weight:bold;height:250px;">' . $hc_lang_event['NoPending'] . '</div>';
		}//end if
	} else {
		
		$resultL = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 ORDER BY Name");
		$hasLoc = (hasRows($resultL)) ? 1 : 0;
		$hourOffset = date("G") + ($hc_cfg35);
		$hrFormat = ($hc_timeInput == 23) ? "H" : "h";
		$minHr = ($hc_timeInput == 23) ? 0 : 1;
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (3,4)");
		$emailAccept = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,0,0));
		$emailDecline = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,1,0));
		$emailAccept =  str_replace('\'', '\\\'', $emailAccept);
		$emailDecline = str_replace('\'', '\\\'', $emailDecline);

		if(isset($_GET['eID'])){
			$result = doQuery("SELECT e.*, l.Name
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							WHERE e.PkID = '" . cIn($_GET['eID']) . "' AND e.IsApproved = 2");
			$whatAmI = "Event";
			$editThis = $_GET['eID'];
			$editType = 1;
		} elseif(isset($_GET['sID'])) {
			$result = doQuery("SELECT e.*, l.Name
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							WHERE e.SeriesID = '" . cIn($_GET['sID']) . "' AND e.IsApproved = 2");

			$whatAmI = "Event Series";
			$editThis = $_GET['sID'];
			$editType = 2;
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot . "/" . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . "/popCal.js";?>"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/DateSelect.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chgStatus(){
			if(document.frmEventApprove.eventStatus.value > 0){
				document.frmEventApprove.message.value = '<?php echo $emailAccept;?>';
			} else {
				document.frmEventApprove.message.value = '<?php echo $emailDecline;?>';
			}//end if
			
		}//end chgStatus()
		
		function chkFrm(){
		dirty = 0;
		warn = "<?php echo $hc_lang_event['Valid01b'];?>";
			
			if(document.frmEventApprove.eventStatus.value == 0){
				return true;
			}//end if
			
			if(document.frmEventApprove.eventRegistration.value == 1){
				if(isNaN(document.frmEventApprove.eventRegAvailable.value) == true){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid02'];?>';
				}//end if
				
				if(document.frmEventApprove.contactName.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
				}//end if
				
				if(document.frmEventApprove.contactEmail.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid04'];?>';
				}//end if
			}//end if
			
			if(validateCheckArray('frmEventApprove','catID[]',1) > 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid15'];?>';
			}//end if
		
			if(document.frmEventApprove.eventTitle.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid13'];?>';
			}//end if
			
		<?php
			if(!isset($_GET['sID'])){	?>
			if(!isDate(document.frmEventApprove.eventDate.value, '<?php echo $hc_cfg51;?>')){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid24'] . " " . strtolower($hc_cfg51);?>';
			}//end if
			
			if(isNaN(document.frmEventApprove.startTimeHour.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid05'];?>';
			} else if((document.frmEventApprove.startTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventApprove.startTimeHour.value < 1)) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid06'] . " " . $minHr . " - " . $hc_timeInput;?>';
			}//end if
			
			if(isNaN(document.frmEventApprove.startTimeMins.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid07'];?>';
			} else if((document.frmEventApprove.startTimeMins.value > 59) || (document.frmEventApprove.startTimeMins.value < 0)) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid08'];?> 0 - 59';
			}//end if
			
			if(isNaN(document.frmEventApprove.endTimeHour.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid09'];?>';
			} else if((document.frmEventApprove.endTimeHour.value > <?php echo $hc_timeInput;?>) || (document.frmEventApprove.endTimeHour.value < 1)) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid10'] . " " . $minHr . " - " . $hc_timeInput;?>';
			}//end if
			
			if(isNaN(document.frmEventApprove.endTimeMins.value) == true){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid11'];?>';
			} else if((document.frmEventApprove.endTimeMins.value > 59) || (document.frmEventApprove.endTimeMins.value < 0)) {
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid12'];?> 0 - 59';
			}//end if

			if(compareDates(document.frmEventApprove.eventDate.value, '<?php echo $hc_cfg51;?>', '<?php echo strftime($hc_cfg24,mktime($hourOffset,date("i"),date("s"),date("m"),date("d")-1,date("Y")));?>', '<?php echo $hc_cfg51;?>') == 0){
				if(!confirm('<?php echo $hc_lang_event['Valid18'] . "\\n\\n          " . $hc_lang_event['Valid19'] . "\\n          " . $hc_lang_event['Valid20'];?>')){
					return false;
				}//end if
			}//end if
	<?php
			}//end if	?>
			
	<?php 	if($hasLoc > 0){	?>
			if(document.frmEventApprove.locPreset.value == 0){
				if(document.frmEventApprove.locName.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
				}//end if
			}//end if
	<?php 	} else {	?>
			if(document.frmEventApprove.locName.value == ''){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_event['Valid16'];?>';
			}//end if
	<?php 	}//end if	?>

			if(document.frmEventApprove.contactEmail.value != '' && chkEmail(document.frmEventApprove.contactEmail) == 0){
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
		
		function chgButton(){
			if(document.frmEventApprove.sendmsg.checked){
				document.frmEventApprove.message.disabled = false;
				document.frmEventApprove.submit.value = ' <?php echo htmlentities($hc_lang_event['SaveWMessage'],ENT_QUOTES);?>';
			} else {
				document.frmEventApprove.message.disabled = true;
				document.frmEventApprove.submit.value = ' <?php echo htmlentities($hc_lang_event['SaveWOMessage'],ENT_QUOTES);?> ';
			}//end if
		}//end chgButton()
		
		var calx = new CalendarPopup("dsCal");
		calx.showNavigationDropdowns();
		calx.setCssPrefix("hc_");

		var tweetPrefix = '<?php echo $hc_lang_event['TweetApproved'];?>';
		<?php include('includes/java/events.php');?>
		//-->
		</script>
	
<?php 	if(hasRows($result)){
			$eID = cOut(mysql_result($result,0,0));
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
			$contactURL = (mysql_result($result,0,24) != '') ? cOut(mysql_result($result,0,24)) : 'http://';
			$allowRegistration = cOut(mysql_result($result,0,25));
			$maxRegistration = cOut(mysql_result($result,0,26));
			$views = cOut(mysql_result($result,0,28));
			$message = cOut(mysql_result($result,0,29));
			$locID = cOut(mysql_result($result,0,35));
			$locName = ($locID == 0) ? cOut(mysql_result($result,0,2)) : cOut(mysql_result($result,0,40));
			$cost = cOut(mysql_result($result,0,36));
			$locCountry = cOut(mysql_result($result,0,37));
			$subName = cOut(mysql_result($result,0,20));
			$subEmail = cOut(mysql_result($result,0,21));
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
			
			appInstructions(0, "Pending_Events", $hc_lang_event['TitlePendingB'], $hc_lang_event['InstructPendingB']);	?>
			<br />
			<form name="frmEventApprove" id="frmEventApprove" method="post" action="<?php echo CalAdminRoot . "/components/EventPendingAction.php";?>" onsubmit="return chkFrm();">
			<input type="hidden" name="editthis" id="editthis" value="<?php echo cOut($editThis);?>" />
			<input type="hidden" name="edittype" id="edittype" value="<?php echo cOut($editType);?>" />
			<input type="hidden" name="subname" id="subname" value="<?php echo cOut($subName);?>" />
			<input type="hidden" name="subemail" id="subemail" value="<?php echo cOut($subEmail);?>" />
			<input type="hidden" name="prevStatus" id="prevStatus" value="<?php echo $eventStatus;?>" />
	<?php 	if($message != ''){	?>
			<fieldset>
				<legend><?php echo $hc_lang_event['Message'];?></legend>
				<div style="padding:5px 5px 0px 10px;"><?php echo nl2br($message);?></div>
			</fieldset>
			<br />
	<?php 	}//end if	?>
			<fieldset>
				<legend><?php echo $hc_lang_event['EventDetail'];?></legend>
				<div class="frmReq">
					<label for="eventTitle"><?php echo $hc_lang_event['Title'];?></label>
					<input onblur="buildTweet();" name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" value="<?php echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
			<?php	makeTinyMCE('eventDescription', '565px', 'advanced', $eventDesc);?>
				</div>
		<?php 	if($editType == 1){	?>
				<div class="frmReq">
					<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
					<input name="eventDate" id="eventDate" type="text" value="<?php echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventApprove.eventDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['StartTime'];?></label>
					<div class="eventStartH"><input onblur="buildTweet();" name="startTimeHour" id="startTimeHour" type="text" value="<?php echo $startTimeHour;?>" size="2" maxlength="2" <?php echo ($tbd > 0) ? 'disabled="disabled" ':'';?>/></div>
					<div class="eventStartCtrl"><a href="javascript:;" onclick="chngClock(document.getElementById('startTimeHour'),1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="<?php echo $hc_lang_event['ALTIncStartH'];?>" /></a><br /><a href="javascript:;" onclick="chngClock(document.getElementById('startTimeHour'),-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="<?php echo $hc_lang_event['ALTDecStartH'];?>" /></a></div>
					<div class="eventStartM"><input onblur="buildTweet();" name="startTimeMins" id="startTimeMins" type="text" value="<?php echo $startTimeMins;?>" size="2" maxlength="2" <?php echo ($tbd > 0) ? 'disabled="disabled" ':'';?>/></div>
					<div class="eventStartCtrl"><a href="javascript:;" onclick="chngClock(document.getElementById('startTimeMins'),1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="<?php echo $hc_lang_event['ALTIncStartM'];?>" /></a><br /><a href="javascript:;" onclick="chngClock(document.getElementById('startTimeMins'),-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="<?php echo $hc_lang_event['ALTDecStartM'];?>" /></a></div>
			<?php 	if($hc_timeInput == 12){	?>
					<div class="eventStartAP">
						<select onblur="buildTweet();" name="startTimeAMPM" id="startTimeAMPM" <?php echo ($tbd > 0) ? 'disabled="disabled" ':'';?>>
							<option <?php if($startTimeAMPM == "AM"){echo "selected=\"selected\"";}?> value="AM"><?php echo $hc_lang_event['AM'];?></option>
							<option <?php if($startTimeAMPM == "PM"){echo "selected=\"selected\"";}?> value="PM"><?php echo $hc_lang_event['PM'];?></option>
						</select>
					</div>
			<?php 	}//end if	?>
			    </div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['EndTime'];?></label>
					<div class="eventEndH"><input name="endTimeHour" id="endTimeHour" type="text" value="<?php echo $endTimeHour;?>" size="2" maxlength="2" <?php echo (isset($noEndTime) || $tbd > 0) ? 'disabled="disabled" ':'';?>/></div>
					<div class="eventEndCtrl"><a href="javascript:;" onclick="chngClock(document.getElementById('endTimeHour'),1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="<?php echo $hc_lang_event['ALTIncEndH'];?>" /></a><br /><a href="javascript:;" onclick="chngClock(document.getElementById('endTimeHour'),-1,<?php echo $hc_timeInput;?>)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="<?php echo $hc_lang_event['ALTDecEndH'];?>" /></a></div>
					<div class="eventEndM"><input name="endTimeMins" id="endTimeMins" type="text" value="<?php echo $endTimeMins;?>" size="2" maxlength="2" <?php echo (isset($noEndTime) || $tbd > 0) ? 'disabled="disabled" ':'';?>/></div>
					<div class="eventEndCtrl"><a href="javascript:;" onclick="chngClock(document.getElementById('endTimeMins'),1,59)"><img src="<?php echo CalRoot;?>/images/time_up.gif" width="16" height="8" alt="<?php echo $hc_lang_event['ALTIncEndM'];?>" /></a><br /><a href="javascript:;" onclick="chngClock(document.getElementById('endTimeMins'),-1,59)"><img src="<?php echo CalRoot;?>/images/time_down.gif" width="16" height="9" alt="<?php echo $hc_lang_event['ALTDecEndM'];?>" /></a></div>
			<?php 	if($hc_timeInput == 12){	?>
					<div class="eventEndAP">
						<select name="endTimeAMPM" id="endTimeAMPM" <?php echo (isset($noEndTime) || $tbd > 0) ? 'disabled="disabled" ':'';?>>
							<option <?php if($endTimeAMPM == "AM"){echo 'selected="selected"';}?> value="AM"><?php echo $hc_lang_event['AM'];?></option>
							<option <?php if($endTimeAMPM == "PM"){echo 'selected="selected"';}?> value="PM"><?php echo $hc_lang_event['PM'];?></option>
						</select>
					</div>
			<?php 	}//end if	?>
					<label for="ignoreendtime" class="noEndTime"><input name="ignoreendtime" id="ignoreendtime" type="checkbox" onclick="togEndTime();" class="noBorderIE" <?php if(isset($noEndTime)){echo 'checked="checked" ';}else if($tbd > 0){echo 'disabled="disabled" ';}?>/><?php echo $hc_lang_event['NoEndTime'];?></label>
			    </div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="overridetime" class="radioWide"><input <?php if($tbd > 0){echo "checked=\"checked\" ";}//end if?>type="checkbox" name="overridetime" id="overridetime" onclick="togOverride();" class="noBorderIE" /><?php echo str_replace(" ","&nbsp;",$hc_lang_event['Override']);?></label>
				</div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="specialtimeall" class="radioWide"><input type="radio" name="specialtime" id="specialtimeall" value="allday" checked="checked" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 1){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['AllDay'];?></label>
				</div>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="specialtimetbd" class="radioWide"><input type="radio" name="specialtime" id="specialtimetbd" value="tbd" class="noBorderIE" <?php if($tbd == 0){echo "disabled=\"disabled\" ";}elseif($tbd == 2){echo "checked=\"checked\" ";}//end if?>/><?php echo $hc_lang_event['TBA'];?></label>
					<br />
				</div>
				<br />
		<?php 	} else {	?>
				<div class="frmOpt">
					<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
				<?php 	echo $eventDate;
						$cnt = 1;
						while($row = mysql_fetch_row($result)){
							if($cnt % 5 == 0){
								echo "<br />";
								if($cnt % 10 == 0){echo "<label>&nbsp;</label>";}//end if
							}//end if
							
							echo " " . stampToDate($row[9], $hc_cfg24);
							$cnt ++;
						}//end while	?>
			    </div>
				<div class="frmOpt">
					<label>Event Time:</label>
			<?php 	if($tbd == 1){
						echo "All Day Event";
					} else if($tbd == 2){
						echo "TBD";
					} else {
						if(isset($noEndTime)){
							echo "Starts at: " . $startTimeHour . ":" . $startTimeMins . " " . $startTimeAMPM;
						} else {
							echo $startTimeHour . ":" . $startTimeMins . " " . $startTimeAMPM . " - " . $endTimeHour . ":" . $endTimeMins . " " . $endTimeAMPM;
						}//end if
					}//end if	?>
			    </div>
		<?php 	}//end if	?>
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
					<input <?php if($allowRegistration == 0){echo "disabled=\"disabled\"";}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?php echo $maxRegistration;?>" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['Settings'];?></legend>
				<div class="frmOpt">
					<label for="eventStatus"><?php echo $hc_lang_event['Status'];?></label>
					<select name="eventStatus" id="eventStatus" onchange="javascript:chgStatus();">
						<option value="1"><?php echo $hc_lang_event['Status1P'];?></option>
						<option value="0"><?php echo $hc_lang_event['Status0'];?></option>
						<option value="2"><?php echo $hc_lang_event['Status2'];?></option>
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
		<?php		$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, ec.EventID as Selected
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
					getCategories('frmEventApprove', 3, $query);?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['Location'];?></legend>
				<input type="hidden" id="locPreset" name="locPreset" value="<?php echo $locID;?>" />
				<input type="hidden" id="locPresetName" name="locPresetName" value="<?php echo $locName;?>" />
			<?php
				if($locID > 0){
					echo '<div id="locSetting">';
					echo '<div class="frmOpt">';
					$resultL = doQuery("SELECT PkID, Name, Address, Address2, City, State, Zip, Country
										FROM " . HC_TblPrefix . "locations
										WHERE PkID = " . $locID);
					echo '<label>' . $hc_lang_event['CurLocation'] . '</label>';
					echo '<div class="hc_align"><b>' . mysql_result($resultL,0,1) . '</b><br />';
					echo buildAddress(mysql_result($resultL,0,2),mysql_result($resultL,0,3),mysql_result($resultL,0,4),mysql_result($resultL,0,5),mysql_result($resultL,0,6),mysql_result($resultL,0,7),$hc_lang_config['AddressType']);
					echo '</div></div>';
					echo '<div class="frmOpt"><label>&nbsp;</label>';
					echo '<a href="javascript:;" onclick="setLocation(0);" class="locChange">' . $hc_lang_event['ChngLocation'] . '</a>';
					echo '</div></div>';
				}//end if
				if($hc_cfg70 == 1){	?>
					<div id="locSearch"<?php echo ($locID > 0) ? ' style="display:none;"' : '';?>>
			<?php 	if($hasLoc > 0){	?>
						<div class="frmOpt">
							<label>&nbsp;</label>
							<?php echo $hc_lang_event['CheckLocInst'];?>
						</div>
						<div class="frmReq">
							<label for="locSearchText"><?php echo $hc_lang_event['LocSearch']?></label>
							<input type="text" name="locSearchText" id="locSearchText" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
							&nbsp;<a href="javascript:;" onclick="setLocation(0);" class="eventMain"><?php echo $hc_lang_event['ClearSearch']?></a>&nbsp;
						</div>
						<div id="locSearchResults">&nbsp;</div>
			<?php 	}//end if
					echo '</div>';
				} else {
					$NewAll = $hc_lang_event['CustomLoc'];
					echo ($locID > 0) ? '<div id="locSelect" style="display:none;">' : '<div class="locSelect">';
					echo '<label for="locListI">' . $hc_lang_event['Preset'] . '</label>';
					include('../events/components/LocationSelect.php');
					echo '</div>';
				}//end if?>
				<div id="customLocNotice" style="display:none;">
					<label>&nbsp;</label>
					<b><?php echo $hc_lang_event['PresetLoc'];?></b>
				</div>
				<div id="customLoc"<?php if($locID > 0){echo " style=\"display:none;\"";}?>>		
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="newLoc" class="radioWide"><input name="newLoc" id="newLoc" type="checkbox" checked="checked" /><?php echo $hc_lang_event['LocNew'];?></label>
				</div>
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
				echo ($locID > 0) ? 'disabled="disabled" ' : '';
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
				echo ($locID > 0) ? 'disabled="disabled" ' : '';
				echo 'name="' . $inputs[$second][1] . '" id="' . $inputs[$second][1] . '" value="' . $inputs[$second][2] . '" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>';
				echo '</div>';
				?>
				<div class="frmOpt">
					<label for="locCountry"><?php echo $hc_lang_event['Country'];?></label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCountry" id="locCountry" value="<?php echo $locCountry;?>" type="text" maxlength="50" size="5" />
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

					echo '<div class="ebTicketNameH"><b>' . $hc_lang_event['TicketName'] . '</b></div>';
					echo '<div class="ebTicketPriceH"><b>' . $hc_lang_event['TicketPrice'] . '</b></div>';
					echo '<div class="ebTicketQtyH"><b>' . $hc_lang_event['TicketQty'] . '</b></div>';
					echo '<div class="ebTicketFeeH"><b>' . $hc_lang_event['TicketFee'] . '</b></div>';
					for($x = 1; $x <= 5; ++$x){
						echo '<div class="ebTicketName">';
						echo '<input name="ticket' . $x . '" id="ticket' . $x . '" type="text" size="30" maxlength="200" value="" /></div>';
						echo '<div class="ebTicketPrice">';
						echo '<input onclick="togTicketPrice(' . $x . ',0);" name="priceType' . $x . '" type="radio" value="0" checked="checked" /><input name="price' . $x . '" id="price' . $x . '" type="text" size="5" maxlength="7" value="" />';
						echo '<input onclick="togTicketPrice(' . $x . ',1);" name="priceType' . $x . '" type="radio" value="1" /><div class="hc_align">&nbsp;' . $hc_lang_event['Free'] . '&nbsp;</div>';
						echo '<input onclick="togTicketPrice(' . $x . ',1);" name="priceType' . $x . '" type="radio" value="2" /><div class="hc_align">&nbsp;' . $hc_lang_event['Donate'] . '&nbsp;</div>';
						echo '</div>';
						echo '<div class="ebTicketQty">';
						echo '<input name="qty' . $x . '" id="qty' . $x . '" type="text" size="5" maxlength="5" value="" /></div>';
						echo '<div class="ebTicketFee">';
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
			<fieldset>
				<legend><?php echo $hc_lang_event['SendMsg'];?></legend>
		<?php 	if($subEmail != ''){	?>
				<div class="frmOpt">
					<label for="sendmsg" class="radioWide"><input name="sendmsg" id="sendmsg" type="checkbox" onclick="javascript:chgButton();" class="noBorderIE" /> <?php echo $hc_lang_event['SendMsg'];?></label>
				</div>
				<br /><br />
				<div class="frmOpt">
			<?php	echo $subName;?> (<a href="mailto:<?php echo $subEmail;?>" class="main"><?php echo $subEmail;?></a>),
					<br />
					<textarea disabled="disabled" rows="7" cols="80" name="message" id="message"><?php echo $emailAccept;?></textarea>

			<?php 	echo '<div style="clear:both;">' . CalAdmin . "<br />" . CalAdminEmail . '</div>';?>
				</div>
		<?php 	} else {	?>
				<div class="frmOpt">
					<input type="hidden" name="sendmsg" id="sendmsg" value="no">
			<?php	echo $hc_lang_event['NoMessage'];	?>
				</div>
		<?php 	}//end if	?>
			</fieldset>
			<br />
			<input type="submit" name="submit" id="submit" value=" <?php echo $hc_lang_event['SaveWOMessage'];?> " class="button" />&nbsp;&nbsp;
			<input type="button" name="cancel" id="cancel" value="  <?php echo $hc_lang_event['Cancel'];?>  " onclick="window.location.href='<?php echo CalAdminRoot;?>/index.php?com=eventpending';return false;" class="button" />
			</form>
<?php 	} else {
			echo "<br />";
			echo $hc_lang_event['ApproveWarning'];
 		}//end if
	}//end if	?>
	<div id="dsCal" class="datePicker"></div>