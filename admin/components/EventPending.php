<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Event Approved Successfully!");
				break;
				
			case "2" :
				feedback(1, "Event Series Approved Successfully!");
				break;
				
			case "3" :
				feedback(1, "Event Declined Successfully!");
				break;
				
			case "4" :
				feedback(1, "Event Series Declined Successfully!");
				break;
		}//end switch
	}//end if
	
	if(!isset($_GET['sID']) && !isset($_GET['eID'])){
		$result = doQuery("SELECT PkID, Title, StartDate, SeriesID
							FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND 
										IsApproved = 2 AND 
										StartDate >= NOW() 
							ORDER BY SeriesID, StartDate, Title");
		if(hasRows($result)){
			appInstructions(0, "Pending_Events", "Pending Events", "You can approve/decline events and send a message informing the event submitter of the event's status change by clicking on the <img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> icon beside the event. <br /><br />To approve/decline all pending events in a series, click the <img src=\"" . CalAdminRoot . "/images/icons/iconEditGroup.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> icon  atop the series listing.");
			$curSeries = "";
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				if($row[3] == '' && $cnt == 0){	?>
					<div class="pendingList">Pending Individual Events</div>
			<?	}//end if
				
				if($row[3] != '' && $curSeries != $row[3]){
					$cnt = 0;
					$curSeries = $row[3];?>
					<div class="pendingList">
						<div style="width: 450px; float:left;">Pending Event Series</div>
						<div style="width: 73px;float:left;text-align:right;"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;sID=<?echo $row[3];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEditGroup.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
						&nbsp;
					</div>
			<?	}//end if	?>
					<div class="pendingListTitle<?if($cnt % 2 == 1){echo "HL";}?>"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;eID=<?echo $row[0];?>" class="main"><?echo $row[1];?></a></div>
					<div class="pendingListDate<?if($cnt % 2 == 1){echo "HL";}?>"><?echo StampToDate($row[2], $hc_popDateFormat);?></div>
					<div class="pendingListTools<?if($cnt % 2 == 1){echo "HL";}?>">&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&amp;eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a></div>
		<?	$cnt++;
			}//end while
		} else {	?>
			<br />
			<b>There are currently no pending events.</b>
	<?	}//end if
	} else {
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (3,4)");
		
		$emailAccept = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,0,0));
		$emailDecline = preg_replace(array('/\r/', '/\n/'), "", mysql_result($result,1,0));
		$emailAccept =  str_replace('\'', '\\\'', $emailAccept);
		$emailDecline = str_replace('\'', '\\\'', $emailDecline);
		
		if($emailAccept == ''){
			$emailAccept = "Your event has been approved and is now available on our website. Thank you for using our calendar.";
		}//end if
		
		if($emailDecline == ''){
			$emailDecline = "Your event has been declined and will not be available on our website. Thank you for using our calendar.";
		}//end if
		
		if(isset($_GET['eID'])){
			$resulte = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND PkID = '" . cIn($_GET['eID']) . "'");
			$whatAmI = "Event";
			$editThis = $_GET['eID'];
			$editType = 1;
		} elseif(isset($_GET['sID'])) {
			$resulte = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsApproved = 2 AND SeriesID = '" . cIn($_GET['sID']) . "'");
			$whatAmI = "Event Series";
			$editThis = $_GET['sID'];
			$editType = 2;
		}//end if	?>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Dates.js"></script>
		<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/DateSelect.js"></script>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
		function chgStatus(){
			if(document.frmEventApprove.eventStatus.value > 0){
				document.frmEventApprove.message.value = '<?echo $emailAccept;?>';
			} else {
				document.frmEventApprove.message.value = '<?echo $emailDecline;?>';
			}//end if
			
		}//end chgStatus()
		
		function chgButton(){
			if(document.frmEventApprove.sendmsg.checked){
				document.frmEventApprove.message.disabled = false;
				document.frmEventApprove.submit.value = ' Save Event & Send Message';
			} else {
				document.frmEventApprove.message.disabled = true;
				document.frmEventApprove.submit.value = ' Save Event ';
			}//end if
			
		}//end chgButton()
		
		function chkFrm(){
		dirty = 0;
		warn = "Event could not be saved for the following reason(s):\n\n";
			
			if(document.frmEventApprove.eventStatus.value == 1){
				if(validateCheckArray('frmEventApprove','catID[]',1) > 0){
					dirty = 1;
					warn = warn + '\n*Category Assignment is Required';
				}//end if
			}//end if
			
			if(document.frmEventApprove.sendmsg.checked){
				if(document.frmEventApprove.message.value == ''){
					dirty = 1;
					warn = warn + '\n*Confirmation Text is Required';
				}//end if
			}//end if
			
			if(dirty > 0){
				alert(warn + '\n\nPlease complete the form and try again.');
				return false;
			} else {
				return true;
			}//end if
		}//end chkFrm()
		
		var calx = new CalendarPopup();
		//-->
		</script>
	
	<?	if(hasRows($result)){
			appInstructions(0, "Pending_Events", "Pending Event Status Update", "Fill out the form below to change the status of this " . $whatAmI . ".");	?>
			<form name="frmEventApprove" id="frmEventApprove" method="post" action="<?echo CalAdminRoot . "/" . HC_EventPendingAction;?>" onsubmit="return chkFrm();">
			<input type="hidden" name="editthis" id="editthis" value="<?echo cOut($editThis);?>" />
			<input type="hidden" name="edittype" id="edittype" value="<?echo cOut($editType);?>" />
			<input type="hidden" name="subname" id="subname" value="<?echo cOut(mysql_result($resulte,0,20));?>" />
			<input type="hidden" name="subemail" id="subemail" value="<?echo cOut(mysql_result($resulte,0,21));?>" />
			<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending" class="main">&laquo;&laquo;Return to Pending Events List</a></div>
		<?	if(mysql_result($resulte,0,29) != ''){	?>
			<br />
	<fieldset>
				<legend>Message From Event Submitter</legend>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<?echo str_replace(chr(13), "<br />", cOut(mysql_result($resulte,0,29)));?>
				</div>
			</fieldset>
		<?	}//end if	?>
			<br />
	<fieldset>
				<legend><?echo $whatAmI;?> Details</legend>
				<div class="frmReq">
					<label for="eventTitle">Title:</label>
					<input name="eventTitle" id="eventTitle" type="text" size="65" maxlength="150" value="<?echo mysql_result($resulte,0,1);?>" />
				</div>
				<div class="frmOpt">
					<label>Description:</label>
					<?makeTinyMCE("eventDescription", "75%", "advanced", str_replace(chr(13), '<br />', cOut(mysql_result($resulte,0,8))));?>
				</div>
			<?	if($editType == 1){	?>
				<div class="frmReq">
					<label>Event Date:</label>
					<?echo stampToDate(cOut(mysql_result($resulte,0,9)), $hc_popDateFormat);?>
				</div>
			<?	} else {	?>
				<div class="frmReq">
					<label>Event Date:</label>
					Multiple Dates
				</div>
			<?	}//end if	?>
				<div class="frmOpt">
					<label>Event Time:</label>
				<?	$starTime = "";
					$endTime = "";
					
					//check start time
					if(mysql_result($resulte,0,10) != ''){
						$timepart = split(":", cOut(mysql_result($resulte,0,10)));
						$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
					}//end if
					
					//check end time
					if(mysql_result($resulte,0,12) != ''){
						$timepart = split(":", cOut(mysql_result($resulte,0,12)));
						$endTime = " - " . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
					}//end if
					
					if(mysql_result($resulte,0,11) == 0){
						if(strlen($endTime) > 0){
							echo $startTime . $endTime;
						} else {
							echo "Starts at " . $startTime;
						}//end if
					} elseif(mysql_result($resulte,0,11) == 1){
						echo "This is an All Day Event";
					} elseif(mysql_result($resulte,0,11) == 2){
						echo "Start Time TBA";
					}//end if	?>
				</div>
			</fieldset>
			<?	if(mysql_result($resulte,0,25) == 1){	?>
			<br />
	<fieldset>
				<legend>Event Registration</legend>
				<div class="frmOpt">
					<label>Registration:</label>
				<?	$resulte2 = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . mysql_result($resulte,0,0) . "'");
					if(mysql_result($resulte,0,26) == 0){
						echo "Unlimited Spaces Available";
					} else {
						echo number_format(cOut(mysql_result($resulte,0,26)), 0, '.', ',') . " Spaces Available";
					}//end if	?>
				</div>
			</fieldset>
			<?	}//end if	?>
			<br />
	<fieldset>
				<legend>Event Settings</legend>
				<div class="frmOpt">
					<label for="eventStatus">Status:</label>
					<select name="eventStatus" id="eventStatus" onchange="javascript:chgStatus();">
						<option value="1">Approved -- Add To Calendar</option>
						<option value="0">Declined -- Remove From Calendar</option>
					</select>
				</div>
				<div class="frmOpt">
					<label for="eventBillboard">Billboard:</label>
					<select name="eventBillboard" id="eventBillboard">
						<option value="0">Do Not Show On Billboard</option>
						<option value="1">Show On Billboard</option>
					</select>
				</div>
				<div class="frmOpt">
					<label>Categories:</label>
					<?	getCategories('frmEventApprove', 2);?>
				</div>
			</fieldset>
			<br />
	<fieldset>
				<legend>Location Information</legend>
				<div class="frmOpt">
					<label>Location:</label>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>
						<?	if(mysql_result($resulte,0,2) != ''){
								echo cOut(mysql_result($resulte,0,2));
							}//end if
							if(mysql_result($resulte,0,3) != ''){
								echo "<br />" . cOut(mysql_result($resulte,0,3));
							}//end if
							if(mysql_result($resulte,0,4) != ''){
								echo "<br />" . cOut(mysql_result($resulte,0,4));
							}//end if
							if(mysql_result($resulte,0,5) != ''){
								echo "<br />" . cOut(mysql_result($resulte,0,5));
								
								if(mysql_result($resulte,0,6) != ''){
									echo ", " . cOut(mysql_result($resulte,0,6));
								}//end if
							}//end if
							if(mysql_result($resulte,0,7) != ''){
								echo " " . cOut(mysql_result($resulte,0,7));
							}//end if	?>
							</td>
						</tr>
					</table>
				</div>
			</fieldset>
		<?	if((mysql_result($resulte,0,24) != '') OR (mysql_result($resulte,0,13) != '') OR (mysql_result($resulte,0,14) != '') OR (mysql_result($resulte,0,15) != '')){	?>
			<br />
	<fieldset>
				<legend>Event Contact Info</legend>
				<div class="frmOpt">
					<label>Contact:</label>
				<?	$conName = "";
					$conEmail = "";
					if(mysql_result($resulte,0,14) != ''){
						$conEmail = cOut(mysql_result($resulte,0,14));
						
					}//end if
					
					if(mysql_result($resulte,0,13) != ''){
						$conName = cOut(mysql_result($resulte,0,13));
						
						if($conEmail != ''){
							echo $conName . " [ <a href=\"mailto:" . $conEmail . "\" class=\"main\">" . $conEmail . "</a> ]";
						} else {
							echo $conName;
						}//end if
					} else {
						if($conEmail != ''){
							echo "<a href=\"mailto:" . $conEmail . "\" class=\"main\">" . $conEmail . "</a>";
						} else {
							echo $conName;
						}//end if
						
					}//end if
					
					if(mysql_result($resulte,0,15) != ''){
						echo cOut(mysql_result($resulte,0,15));
					}//end if
					
					if(mysql_result($resulte,0,24) != ''){
						echo "<a href=\"" . cOut(mysql_result($resulte,0,24)) . "\" class=\"main\" target=\"top\">" . substr(cOut(mysql_result($resulte,0,24)),7)  . "</a>";
					}//end if	?>
				</div>
			</fieldset>
		<?	}//end if	?>
			<br />
	<fieldset>
				<legend>Confirmation Message</legend>
			<?	if(mysql_result($resulte,0,21) != ''){	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<label for="sendmsg" class="radioWide"><input name="sendmsg" id="sendmsg" type="checkbox" onclick="javascript:chgButton();" class="noBorderIE" /> Send Confirmation Message</label>
				</div>
				<br /><br />
				<div class="frmOpt">
					<label for="message">&nbsp;</label>
					<?echo cOut(mysql_result($resulte,0,20));?> (<?echo cOut(mysql_result($resulte,0,21));?>),
					<textarea convert_this="false" disabled="disabled" rows="7" cols="60" name="message" id="message"><?echo $emailAccept;?></textarea><br />
					<label>&nbsp;</label>
					<?echo CalAdmin . "<br />" . CalAdminEmail;?>
				</div>	
			<?	} else {	?>
				<div class="frmOpt">
					<label>&nbsp;</label>
					<input type="hidden" name="sendmsg" id="sendmsg" value="no">
					Submitter's Email Address Unavailable.<br />Confirmation cannot be sent.
				</div>
			<?	}//end if	?>
			</fieldset>
			<br />
			<input type="submit" name="submit" id="submit" value=" Save Event " class="button" />&nbsp;&nbsp;
			<input type="button" name="cancel" id="cancel" value="  Cancel  " onclick="window.location.href='<?echo CalAdminRoot;?>/index.php?com=eventpending';return false;" class="button" />
			</form>
	<?	} else {	?>
			You are attempting to approve an invalid event.
			
			<br /><br />
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending" class="main">Click here to view pending events.</a>
	<?	}//end if
	}//end if	?>