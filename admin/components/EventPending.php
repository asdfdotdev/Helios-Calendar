<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (3,4)");
	
	$emailAccept =  str_replace('\'', '\\\'', mysql_result($result,0,0));
	$emailDecline = str_replace('\'', '\\\'', mysql_result($result,1,0));
	
	$emailAccept =  str_replace(chr(13), '\\n', $emailAccept);
	$emailDecline = str_replace(chr(13), '\\n', $emailDecline);
	
	if($emailAccept == ''){
		$emailAccept = "Your event has been approved and is now available on our website. I hope you continue to use our calendar and submit more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact me.";
	}//end if
	
	if($emailDecline == ''){
		$emailDecline = "Your event has been declined and will not be available on our website. Please do not let this discourage you from submiting more events in the future. Thank you for using our calendar and if you have any questions please feel free to contact me.";
	}//end if
?>
<script language="JavaScript">
function chgStatus(){
	if(document.eventApprove.eventStatus.value > 0){
		document.eventApprove.message.value = '<?echo $emailAccept;?>';
	} else {
		document.eventApprove.message.value = '<?echo $emailDecline;?>';
	}//end if
	
}//end chgStatus()

function chgButton(){
	if(document.eventApprove.sendmsg.checked){
		document.eventApprove.message.disabled = false;
		document.eventApprove.submit.value = ' Save Event & Send Message';
	} else {
		document.eventApprove.message.disabled = true;
		document.eventApprove.submit.value = ' Save Event ';
	}//end if
	
}//end chgButton()

function chkFrm(){
dirty = 0;
warn = "Event could not be saved for the following reason(s):\n\n";
	
	if(document.eventApprove.eventStatus.value == 1){
		if(validateCheckArray('eventApprove','catID[]',1) > 0){
			dirty = 1;
			warn = warn + '\n*Category Assignment is Required';
		}//end if
	}//end if
	
	if(document.eventApprove.sendmsg.checked){
		if(document.eventApprove.message.value == ''){
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
</script>
								
<?php 
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
			appInstructions(0, "Pending_Events", "Pending Events", "You can approve/decline events and send a message informing the event submitter of the event's status change by clicking on the <img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"> icon beside the event. <br><br>To approve/decline all pending events in a series, click <span style=\"background: #CCCCCC;\">&nbsp;<a href=\"javascript:;\" class=\"approve\">Series Edit</a>&nbsp;</span> atop the series listing.");?>
			<br>
			<table cellpadding="0" cellspacing="0" border="0">
		<?	$curSeries = "";
			$cnt = 0;
			while($row = mysql_fetch_row($result)){
				if($row[3] == '' && $cnt == 0){	?>
					<tr>
						<td colspan="3" class="eventMain"><b>Pending Individual Events</b></td>
					</tr>
					<tr><td colspan="3" class="eventMain"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="3" class="eventMain"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><?
				}//end if
				
				if($row[3] != '' && $curSeries != $row[3]){
					$cnt = 0;
					$curSeries = $row[3];?>
					<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
						<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
						<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="25" alt="" border="0"></td></tr>
					<tr>
						<td colspan="2" class="eventMain"><b>Pending Event Series</b></td>
						<td rowspan="2" class="eventMain" align="center" bgcolor="#CCCCCC">&nbsp;&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&sID=<?echo $row[3];?>" class="approve">Series Edit</a>&nbsp;&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td>
					</tr>
					<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr>
			<?	}//end if	?>
				<tr>
					<td <?if($cnt % 2 == 1){echo "class=\"tblListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="300" class="eventMain">&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&eID=<?echo $row[0];?>" class="main"><?echo $row[1];?></a></td>
					<td <?if($cnt % 2 == 1){echo "class=\"tblListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="100" class="eventMain"><?echo StampToDate($row[2], "m/d/Y");?></td>
					<td <?if($cnt % 2 == 1){echo "class=\"tblListHL\"";}else{echo "class=\"eventMain\"";}//end if?> align="center" class="eventMain"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending&eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0"></a></td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<?	$cnt++;
			}//end while	?>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="25" alt="" border="0"></td></tr>
			</table>
	<?	} else {	?>
			<b>There are currently no pending events.</b>
	<?	}//end if
		
} else {
	
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
		
	}//end if
		
		if(hasRows($resulte)){
		?>
			<?php
				appInstructions(0, "Pending_Events", "Pending Event Status Update", "Fill out the form below to change the status of this " . $whatAmI . ".");
			?>
			<br>
			<form name="eventApprove" id="eventApprove" method="post" action="<?echo CalAdminRoot . "/" . HC_EventPendingAction;?>" onSubmit="return chkFrm();">
			<input type="hidden" name="editthis" id="editthis" value="<?echo cOut($editThis);?>">
			<input type="hidden" name="edittype" id="edittype" value="<?echo cOut($editType);?>">
			<input type="hidden" name="subname" id="subname" value="<?echo cOut(mysql_result($resulte,0,20));?>">
			<input type="hidden" name="subemail" id="subemail" value="<?echo cOut(mysql_result($resulte,0,21));?>">
			<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventpending" class="main">&laquo;&laquo;Return to Pending Events List</a></div>
			<table cellpadding="0" cellspacing="0" border="0" 
			<?php
				if(mysql_result($resulte,0,29) != ''){
			?>
				<tr>
					<td colspan="2" class="eventMain"><b>Message From Event Submitter</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td></td>
					<td class="eventMain">
						<?echo str_replace(chr(13), "<br>", cOut(mysql_result($resulte,0,29)));?>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				
			<?
				}//end if
			?>
				<tr>
					<td colspan="2" class="eventMain"><b><?echo $whatAmI;?> Details</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td>
					
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td class="eventMain">
									<b><?echo mysql_result($resulte,0,1);?></b><br>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="10">&nbsp;</td>
											<td class="eventMain">
												<?
													if(mysql_result($resulte,0,8) != ''){
														echo str_replace(chr(13), '<br>', cOut(mysql_result($resulte,0,8))) . "<br><br>";
													}//end if
												?>
											</td>
										</tr>
									</table>
									
									<?php
										$datepart = split("-", mysql_result($resulte,0,9));
										echo "<b>" . stampToDate(cOut(mysql_result($resulte,0,9)), "l \\t\h\e jS \o\f F Y") . "</b>";
									?>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td class="eventMain" width="10">&nbsp;</td>
											<td class="eventMain">
									
									<?php
										$starTime = "";
										$endTime = "";
										
										//check start time
										if(mysql_result($resulte,0,10) != ''){
											$timepart = split(":", cOut(mysql_result($resulte,0,10)));
											$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
										}//end if
										
										//check end time
										if(mysql_result($resulte,0,12) != ''){
											$timepart = split(":", cOut(mysql_result($resulte,0,12)));
											$endTime = " to " . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2]));
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
										}//end if
									?>
									</table>
									
									
									<br>
									<b>Location</b>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="10">&nbsp;</td>
											<td class="eventMain">
											<?php
												if(mysql_result($resulte,0,2) != ''){
													echo cOut(mysql_result($resulte,0,2));
												}//end if
												if(mysql_result($resulte,0,3) != ''){
													echo "<br>" . cOut(mysql_result($resulte,0,3));
												}//end if
												if(mysql_result($resulte,0,4) != ''){
													echo "<br>" . cOut(mysql_result($resulte,0,4));
												}//end if
												if(mysql_result($resulte,0,5) != ''){
													echo "<br>" . cOut(mysql_result($resulte,0,5));
													
													if(mysql_result($resulte,0,6) != ''){
														echo ", " . cOut(mysql_result($resulte,0,6));
													}//end if
												}//end if
												if(mysql_result($resulte,0,7) != ''){
													echo " " . cOut(mysql_result($resulte,0,7));
												}//end if
											?>
											</td>
										</tr>
									</table>
									
									<?php
									if(mysql_result($resulte,0,25) == 1){
										$resulte2 = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = '" . mysql_result($resulte,0,0) . "'");
									?>
									<br>
									<b>Registration</b>&nbsp;
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="10">&nbsp;</td>
											<td class="eventMain">
												Spaces Available: <?if(mysql_result($resulte,0,26) == 0){
																		echo "Unlimited";
																	} else {
																		echo cOut(mysql_result($resulte,0,26));
																	}//end if?>
											</td>
										</tr>
									</table>
									<?php
									}//end if
									?>
									
								<?php
								if((mysql_result($resulte,0,24) != '') OR (mysql_result($resulte,0,13) != '') OR (mysql_result($resulte,0,14) != '') OR (mysql_result($resulte,0,15) != '')){
								?>
									<br>
									<b>Contact</b>
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="10">&nbsp;</td>
											<td class="eventMain">
												<table cellpadding="0" cellspacing="0" border="0">
													<?php
														$conName = "";
														$conEmail = "";
														if(mysql_result($resulte,0,14) != ''){
															$conEmail = cOut(mysql_result($resulte,0,14));
															
														}//end if
														
														if(mysql_result($resulte,0,13) != ''){
															$conName = cOut(mysql_result($resulte,0,13));
															
															if($conEmail != ''){
																echo "<tr><td class=\"eventMain\">" . $conName . " [ <a href=\"mailto:" . $conEmail . "\" class=\"main\">" . $conEmail . "</a> ]</td></tr>";
															} else {
																echo "<tr><td class=\"eventMain\">" . $conName . "</td></tr>";
															}//end if
														} else {
															if($conEmail != ''){
																echo "<tr><td class=\"eventMain\">[ <a href=\"mailto:" . $conEmail . "\" class=\"main\">" . $conEmail . "</a> ]</td></tr>";
															} else {
																echo "<tr><td class=\"eventMain\">" . $conName . "</td></tr>";
															}//end if
															
														}//end if
														
														if(mysql_result($resulte,0,15) != ''){
															echo "<tr><td class=\"eventMain\">" . cOut(mysql_result($resulte,0,15)) . "</td></tr>";
														}//end if
														
														if(mysql_result($resulte,0,24) != ''){
															echo "<tr><td class=\"eventMain\"><a href=\"" . cOut(mysql_result($resulte,0,24)) . "\" class=\"main\" target=\"top\">" . substr(cOut(mysql_result($resulte,0,24)),7)  . "</a></td></tr>";
														}//end if
													?>
												</table>
											</td>
										</tr>
									</table>
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
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b><?echo $whatAmI;?> Settings</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td width="65" class="eventMain">Status:</td>
					<td>
						<select name="eventStatus" id="eventStatus" class="input" onChange="javascript:chgStatus();">
							<option value="1">Approved -- Add To Calendar</option>
							<option value="0">Declined -- Remove From Calendar</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">Billboard:</td>
					<td>
						<select name="eventBillboard" id="eventBillboard" class="input">
							<option value="0">Do Not Show On Billboard</option>
							<option value="1">Show On Billboard</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td valign="top" class="eventMain">Category:</td>
					<td class="eventMain">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
						<?	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "eventcategories.EventID
								FROM " . HC_TblPrefix . "categories 
									LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "eventcategories.CategoryID AND " . HC_TblPrefix . "eventcategories.EventID = " . cIn(mysql_result($resulte,0,0)) . ") 
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
									<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo cOut($row[1]);?></label>&nbsp;&nbsp;</td>
								<?
									$cnt = $cnt + 1;
								}//end if
							
							}//end while
						?>
							</tr>
						</table>
						<?	if($cnt > 1){	?>
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
							[ <a class="main" href="javascript:;" onClick="checkAllArray('eventApprove', 'catID[]');">Select All Categories</a> 
							&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('eventApprove', 'catID[]');">Deselect All Categories</a> ]
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
						<?	}//end if	?>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" class="eventMain"><b>Confirmation Message</b></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<?php
					if(mysql_result($resulte,0,21) != ''){
				?>
				<tr>
					<td>&nbsp;</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td align="right"><input type="checkbox" name="sendmsg" id="sendmsg" onClick="javascript:chgButton();"></td>
								<td class="eventMain"><label for="sendmsg" class="button">Send Confirmation Message</label></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td valign="top" class="eventMain">Message:</td>
					<td class="eventMain">
						<?echo cOut(mysql_result($resulte,0,20));?> (<?echo cOut(mysql_result($resulte,0,21));?>),<br>
						<textarea DISABLED rows="7" cols="60" name="message" id="message" class="input"><?echo $emailAccept;?></textarea><br>
						<?echo CalAdmin . "<br>" . CalAdminEmail;?>
					</td>
				</tr>
				<?php
				} else {
				?>
				<tr>
					<td>&nbsp;</td>
					<td class="eventMain">
						<input type="hidden" name="sendmsg" id="sendmsg" value="no">
						Submitter's Email Address Unavailable.<br>Confirmation cannot be sent.
					</td>
				</tr>
				<?
				}//end if
				?>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="submit" id="submit" value="&nbsp;Save Event&nbsp;" class="button">&nbsp;&nbsp;
						<input type="button" name="cancel" id="cancel" value="&nbsp;Cancel&nbsp;" class="button" onClick="window.location.href='<?echo CalAdminRoot;?>/index.php?com=eventpending';return false;">
					</td>
				</tr>
			</table>
			</form>
		<?
		} else {
		?>
			You are attempting to approve an invalid event.
			
			<br><br>
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventpending" class="main">Click here to view pending events.</a>
		<?
		}//end if
		
}//end if
?>