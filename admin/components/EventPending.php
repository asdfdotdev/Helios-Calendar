<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
	
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
			case "7" :
				feedback(1, $hc_lang_event['Feed18']);
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
			<div style="text-align:right;clear:both;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['SelectAll'];?></a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['DeselectAll'];?></a> ]
			</div>
	
	<?php 	while($row = mysql_fetch_row($result)){
				if($row[3] == '' && $cnt == 0){
					echo '<div class="pendingList">' . $hc_lang_event['PendingIndividual'] . '</div>';
		 		}//end if
				
				if($row[3] != '' && $curSeries != $row[3]){
					$cnt = 0;
					$curSeries = $row[3];
					echo '<div class="pendingList">';
					echo '<div style="width: 545px; float:left;">' . $hc_lang_event['PendingSeries'] . '</div>';
					echo '<div style="width: 73px;float:left;text-align:right;"><a href="' . CalAdminRoot . '/index.php?com=eventpending&amp;sID=' . $row[3] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEditGroup.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a></div>';
					echo '&nbsp;</div>';
		 		}//end if
		 		
		 		echo ($cnt % 2 == 1) ? '<div class="pendingListTitleHL">' : '<div class="pendingListTitle">';
		 		echo $row[1] . '</div>';
				
		 		echo ($cnt % 2 == 1) ? '<div class="pendingListDateHL">' : '<div class="pendingListDate">';
		 		echo StampToDate($row[2], $hc_cfg24) . '</div>';

		 		echo ($cnt % 2 == 1) ? '<div class="pendingListToolsHL">' : '<div class="pendingListTools">';
		 		echo '&nbsp;<a href="' . CalAdminRoot . '/index.php?com=eventpending&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>';
				echo '&nbsp;<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />&nbsp;</div>';
					
	 			++$cnt;
			}//end while	?>
			
			<div style="text-align:right;clear:both;padding-top:10px;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['SelectAll'];?></a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventPending', 'eventID[]');"><?php echo $hc_lang_event['DeselectAll'];?></a> ]
			</div>
			<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_event['DeclineDelete'];?>" class="button" />
			</form>
<?php 	} else {
			echo '<br />';
			echo '<b>' . $hc_lang_event['NoPending'] . '</b>';
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
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND PkID = '" . cIn($_GET['eID']) . "'");
			$whatAmI = "Event";
			$editThis = $_GET['eID'];
			$editType = 1;
		} elseif(isset($_GET['sID'])) {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND SeriesID = '" . cIn($_GET['sID']) . "'");
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
		function toggleMe(who){
			who.style.display == 'none' ? who.style.display = 'block':who.style.display = 'none';
			return false;
		}//end toggleMe()
		
		function chgStatus(){
			if(document.frmEventApprove.eventStatus.value > 0){
				document.frmEventApprove.message.value = '<?php echo $emailAccept;?>';
			} else {
				document.frmEventApprove.message.value = '<?php echo $emailDecline;?>';
			}//end if
			
		}//end chgStatus()
		
		function chngClock(obj,inc,maxVal){
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
		
		function togOverride(){
			if(document.frmEventApprove.overridetime.checked){
				document.frmEventApprove.specialtimeall.disabled = false;
				document.frmEventApprove.specialtimetbd.disabled = false;
				document.frmEventApprove.startTimeHour.disabled = true;
				document.frmEventApprove.startTimeMins.disabled = true;
				document.frmEventApprove.endTimeHour.disabled = true;
				document.frmEventApprove.endTimeMins.disabled = true;
				document.frmEventApprove.ignoreendtime.disabled = true;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventApprove.startTimeAMPM.disabled = true;
					document.frmEventApprove.endTimeAMPM.disabled = true;
				}//end if
			} else {
				document.frmEventApprove.specialtimeall.disabled = true;
				document.frmEventApprove.specialtimetbd.disabled = true;
				document.frmEventApprove.startTimeHour.disabled = false;
				document.frmEventApprove.startTimeMins.disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventApprove.startTimeAMPM.disabled = false;
				}//end if
				if(document.frmEventApprove.ignoreendtime.checked == false){
					document.frmEventApprove.endTimeHour.disabled = false;
					document.frmEventApprove.endTimeMins.disabled = false;
					if(<?php echo $hc_timeInput;?> == 12){
						document.frmEventApprove.endTimeAMPM.disabled = false;
					}//end if
				}//end if
				document.frmEventApprove.ignoreendtime.disabled = false;
			}//end if
		}//end togOverride()
		
		function togEndTime(){
			if(document.frmEventApprove.ignoreendtime.checked){
				document.frmEventApprove.endTimeHour.disabled = true;
				document.frmEventApprove.endTimeMins.disabled = true;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventApprove.endTimeAMPM.disabled = true;
				}//end if
			} else {
				document.frmEventApprove.endTimeHour.disabled = false;
				document.frmEventApprove.endTimeMins.disabled = false;
				if(<?php echo $hc_timeInput;?> == 12){
					document.frmEventApprove.endTimeAMPM.disabled = false;
				}//end if
			}//end if
		}//end togEndTime()
		
		function togRegistration(){
			if(document.frmEventApprove.eventRegistration.value == 0){
				document.frmEventApprove.eventRegAvailable.disabled = true;
			} else {
				document.frmEventApprove.eventRegAvailable.disabled = false;
			}//end if
		}//end togRegistration()
		
		function togLocation(){
			if(document.frmEventApprove.locPreset.value == 0){
				document.frmEventApprove.locName.disabled = false;
				document.frmEventApprove.locAddress.disabled = false;
				document.frmEventApprove.locAddress2.disabled = false;
				document.frmEventApprove.locCity.disabled = false;
				document.frmEventApprove.locState.disabled = false;
				document.frmEventApprove.locZip.disabled = false;
				document.frmEventApprove.locCountry.disabled = false;
				document.getElementById('customLoc').style.display = 'block';
				document.getElementById('customLocNotice').style.display = 'none';
			} else {
				document.frmEventApprove.locName.disabled = true;
				document.frmEventApprove.locAddress.disabled = true;
				document.frmEventApprove.locAddress2.disabled = true;
				document.frmEventApprove.locCity.disabled = true;
				document.frmEventApprove.locState.disabled = true;
				document.frmEventApprove.locZip.disabled = true;
				document.frmEventApprove.locCountry.disabled = true;
				document.getElementById('customLoc').style.display = 'none';
				document.getElementById('customLocNotice').style.display = 'block';
			}//end if
		}//end togEndTime()
		
		function chgButton(){
			if(document.frmEventApprove.sendmsg.checked){
				document.frmEventApprove.message.disabled = false;
				document.frmEventApprove.submit.value = ' <?php echo $hc_lang_event['SaveWMessage'];?>';
			} else {
				document.frmEventApprove.message.disabled = true;
				document.frmEventApprove.submit.value = ' <?php echo $hc_lang_event['SaveWOMessage'];?> ';
			}//end if
		}//end chgButton()
		
		function togLocation(){
			if(document.frmEventApprove.locPreset.value == 0){
				document.frmEventApprove.locName.disabled = false;
				document.frmEventApprove.locAddress.disabled = false;
				document.frmEventApprove.locAddress2.disabled = false;
				document.frmEventApprove.locCity.disabled = false;
				document.frmEventApprove.locState.disabled = false;
				document.frmEventApprove.locZip.disabled = false;
				document.frmEventApprove.locCountry.disabled = false;
				document.getElementById('customLoc').style.display = 'block';
				document.getElementById('customLocNotice').style.display = 'none';
			} else {
				document.frmEventApprove.locName.disabled = true;
				document.frmEventApprove.locAddress.disabled = true;
				document.frmEventApprove.locAddress2.disabled = true;
				document.frmEventApprove.locCity.disabled = true;
				document.frmEventApprove.locState.disabled = true;
				document.frmEventApprove.locZip.disabled = true;
				document.frmEventApprove.locCountry.disabled = true;
				document.getElementById('customLoc').style.display = 'none';
				document.getElementById('customLocNotice').style.display = 'block';
			}//end if
		}//end togLocation()
		
		function searchLocations($page){
			if(document.frmEventApprove.locSearch.value.length > 3){
				var qStr = 'LocationSearch.php?np=1&q=' + escape(document.frmEventApprove.locSearch.value) + '&o=' + $page;
				ajxOutput(qStr, 'locSearchResults', '<?php echo CalRoot;?>');
			}//end if
		}//end searchLocations()
		
		function setLocation($id){
			document.frmEventApprove.locPreset.value = $id;
			togLocation();
			if($id == 0){
				document.getElementById('locSearchResults').innerHTML = '';
				document.frmEventApprove.locSearch.value = '';
				document.getElementById('locSearch').style.display = 'block';
	
				document.getElementById('locSetting').style.display = 'none';
			}//end if
		}//end setLocation
		
		var calx = new CalendarPopup("dsCal");
		calx.setCssPrefix("hc_");
		//-->
		</script>
	
<?php 	if(hasRows($result)){
			$eID = cOut(mysql_result($result,0,0));
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
					<input name="eventTitle" id="eventTitle" type="text" maxlength="150" style="width:75%;" value="<?php echo $eventTitle;?>" />&nbsp;<span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="eventDescription"><?php echo $hc_lang_event['Description'];?></label>
			<?php	makeTinyMCE('eventDescription', '525px', 'advanced', $hc_cfg24, $eventDesc);?>
				</div>
		<?php 	if($editType == 1){	?>
				<div class="frmReq">
					<label for="eventDate"><?php echo $hc_lang_event['Date'];?></label>
					<input name="eventDate" id="eventDate" type="text" value="<?php echo $eventDate;?>" size="12" maxlength="10" />&nbsp;<a href="javascript:;" onclick="calx.select(document.frmEventApprove.eventDate,'anchor1','<?php echo $hc_cfg51;?>'); return false;" name="anchor1" id="anchor1"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCalendar.png" width="16" height="16" border="0" alt="" class="img" /></a><span style="color: #DC143C">*</span>
			    </div>
				<div class="frmOpt">
					<label><?php echo $hc_lang_event['StartTime'];?></label>
					<table cellpadding="1" cellspacing="0" border="0">
						<tr>
							<td><input name="startTimeHour" id="startTimeHour" type="text" value="<?php echo $startTimeHour;?>" size="2" maxlength="2" <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeHour,1,12)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeHour,-1,12)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="startTimeMins" id="startTimeMins" type="text" value="<?php echo $startTimeMins;?>" size="2" maxlength="2"  <?php if($tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.startTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="startTimeAMPM" id="startTimeAMPM"<?php if($tbd > 0){echo " disabled=\"disabled\" ";}//end if?>>
									<option <?php if($startTimeAMPM == 'AM'){echo "selected=\"selected\"";}?> value="AM">AM</option>
									<option <?php if($startTimeAMPM == 'PM'){echo "selected=\"selected\"";}?> value="PM">PM</option>
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
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeHour,1,12)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeHour,-1,12)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
							<td><input name="endTimeMins" id="endTimeMins" type="text" value="<?php echo $endTimeMins;?>" size="2" maxlength="2" <?php if(isset($noEndTime) OR $tbd > 0){echo "disabled=\"disabled\" ";}//end if?>/></td>
							<td><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeMins,1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_up.gif" width="16" height="8" alt="" border="0" /></a><br /><a href="javascript:;" onclick="chngClock(document.frmEventApprove.endTimeMins,-1,59)"><img src="<?php echo CalAdminRoot;?>/images/time_down.gif" width="16" height="9" alt="" border="0" /></a></td>
					<?php 	if($hc_timeInput == 12){	?>
							<td>
								<select name="endTimeAMPM" id="endTimeAMPM"<?php if(isset($noEndTime) OR $tbd > 0){echo " disabled=\"disabled\" ";}//end if?>>
									<option <?php if($endTimeAMPM == "AM"){?>selected="selected"<?php }?> value="AM">AM</option>
									<option <?php if($endTimeAMPM == "PM"){?>selected="selected"<?php }?> value="PM">PM</option>
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
						<option <?php if($allowRegistration == 0){echo "selected=\"selected\"";}?> value="0"><?php echo $hc_lang_event['Reg0'];?></option>
						<option <?php if($allowRegistration == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_event['Reg1'];?></option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="eventRegAvailable"><?php echo $hc_lang_event['Limit'];?></label>
					<input <?php if($allowRegistration == 0){echo "disabled=\"disabled\"";}?> name="eventRegAvailable" id="eventRegAvailable" type="text" size="4" maxlength="4" value="<?php echo $maxRegistration;?>" />&nbsp;<?php echo $hc_lang_event['LimitLabel'];?>
				</div>
		<?php 	if($allowRegistration == 1){	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
			<?php 	$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
					$regUsed = mysql_result($result,0,0);
					$regAvailable = $maxRegistration;
					
					if($maxRegistration == 0) {
						echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg'] . "</b>";
					} elseif($maxRegistration <= mysql_result($result,0,0)){	?>
						<img src="<?php echo CalAdminRoot;?>/images/meter/regOverflow.gif" width="100" height="7" alt="" border="0" style="border-left: solid #000000 0.5px; border-right: solid #000000 0.5px;" />
				<?php 	echo "<b>" . $regUsed . $hc_lang_event['TotalReg'] . " </b> -- " . $hc_lang_event['Overflow'];
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
				<?php 		echo "<b>" . $regUsed . " " . $hc_lang_event['TotalReg']  . "</b>";
						}//end if
					}//end if	?>
				</div>
		<?php 	}//end if?>
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
					getCategories('frmEventApprove', 2, $query);?>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend><?php echo $hc_lang_event['Location'];?></legend>
				<input type="hidden" id="locPreset" name="locPreset" value="<?php echo $locID;?>" />
				<div id="locSetting">
		<?php 	if($locID > 0){
					echo "<div class=\"frmOpt\">";
					$resultL = doQuery("SELECT PkID, Name, Address, Address2, City, State, Country
										FROM " . HC_TblPrefix . "locations
										WHERE PkID = " . $locID);
					echo "<label>" . $hc_lang_event['CurLocation'] . "</label>";
					echo "<b>" . mysql_result($resultL,0,1) . "</b>";
					echo "</div><div class=\"frmOpt\">";
					echo "<label>&nbsp;</label>";
					echo mysql_result($resultL,0,2) . " " . mysql_result($resultL,0,3) . " " . mysql_result($resultL,0,4) . " " . mysql_result($resultL,0,5) . " " . mysql_result($resultL,0,6);
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
						<label for="locSearch"><?php echo $hc_lang_event['LocSearch']?></label>
						<input type="text" name="locSearch" id="locSearch" onkeyup="searchLocations();" value = "" size="25" maxlength="100" />
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
				<div id="customLoc"<?php if($locID > 0){echo " style=\"display:none;\"";}?>>		
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="newLoc" class="radioWide"><input name="newLoc" id="newLoc" type="checkbox" checked="checked" /><?php echo $hc_lang_event['LocNew'];?></label>
				</div>
				<div class="frmReq">
					<label for="locName"><?php echo $hc_lang_event['Name'];?></label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locName" id="locName" value="<?php echo $locName;?>" type="text" maxlength="50" size="40" /><span style="color: #DC143C">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress"><?php echo $hc_lang_event['Address'];?></label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress" id="locAddress" value="<?php echo $locAddress;?>" type="text" maxlength="75" size="30" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locAddress2">&nbsp;</label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locAddress2" id="locAddress2" value="<?php echo $locAddress2;?>" type="text" maxlength="75" size="25" />
				</div>
				<div class="frmOpt">
					<label for="locCity"><?php echo $hc_lang_event['City'];?></label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locCity" id="locCity" value="<?php echo $locCity;?>" type="text" maxlength="50" size="20" /><span style="color: #0000FF">*</span>
				</div>
				<div class="frmOpt">
					<label for="locState"><?php echo $hc_lang_config['RegionLabel'];?></label>
				<?php 	$state = $locState;
						if($locID > 0){
							$stateDisabled = 1;
							$state = $hc_cfg21;
						}//end if
						include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['RegionFile']);?><span style="color: #0000FF;">*</span>
				</div>
				<div class="frmOpt">
					<label for="locZip"><?php echo $hc_lang_event['Postal'];?></label>
					<input <?php if($locID > 0){echo "disabled=\"disabled\"";}?> name="locZip" id="locZip" value="<?php echo $locZip;?>" type="text" maxlength="11" size="11" /><span style="color: #0000FF">*</span>
				</div>
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
			<?php
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
			if(hasRows($result)){
				if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){	?>
				<br />
				<fieldset>
					<legend><?php echo $hc_lang_event['EventfulAdd'];?></legend>
					<div class="frmOpt">
						<label for="doEventful" class="radioWide"><input name="doEventful" id="doEventful" type="checkbox" onclick="toggleMe(document.getElementById('eventful'));" class="noBorderIE" />&nbsp;<?php echo $hc_lang_event['EventfulLabelA'];?></label>
					</div>
					<div id="eventful" style="display:none;clear:both;">
				<?php
					if(mysql_result($result,1,1) == '' || mysql_result($result,2,1) == ''){	?>
					<div style="width:70%;padding:5px;border:solid 1px #0043FF;background:#EFEFEF;">
					<?php echo $hc_lang_event['EventfulReq'];?>
					<br /><br />
						<div class="frmOpt">
							<label for="efUser" class="settingsLabel"><?php echo $hc_lang_event['Username'];?></label>
							<input name="efUser" id="efUser" type="text" value="" size="20" maxlength="150" />
						</div>
						<div class="frmOpt">
							<label for="efPass" class="settingsLabel"><?php echo $hc_lang_event['Passwrd1'];?></label>
							<input name="efPass" id="efPass" type="password" value="" size="15" maxlength="30" />
						</div>
						<div class="frmOpt">
							<label for="efPass2" class="settingsLabel"><?php echo $hc_lang_event['Passwrd2'];?></label>
							<input name="efPass2" id="efPass2" type="password" value="" size="15" maxlength="30" />
						</div>
					</div>
				<?php			
					}//end if	
					echo $hc_lang_event['EventfulSubmit'];	?>
					</div>
				</fieldset>
		<?php
				}//end if
			}//end if	?>
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
					<textarea disabled="disabled" rows="7" cols="80" name="message" id="message"><?php echo $emailAccept;?></textarea><br />
					
			<?php 	echo CalAdmin . "<br />" . CalAdminEmail;?>
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