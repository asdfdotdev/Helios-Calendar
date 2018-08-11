<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="eventMain">
			<?php
				if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
					if(is_numeric($_GET['eID'])){
						$eID = $_GET['eID'];
					} else {
						$eID = 0;
					}//end if
				} else {
					$eID = 0;
				}//end if
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
				
				if(hasRows($result)){
					doQuery("UPDATE " . HC_TblPrefix . "events SET Views = " . (mysql_result($result,0,28) + 1) . " WHERE PkID = " . $eID)
				?>
				<div align="right"><a href="<?echo CalRoot;?>/" class="eventMain">&laquo;&laquo;&nbsp;Return to This Weeks Events</a></div>
				<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="5" alt="" border="0"><br>
				
					<b><?echo cOut(mysql_result($result,0,1));?></b><br>
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td width="10">&nbsp;</td>
							<td class="eventMain">
								<?
									if(mysql_result($result,0,8) != ''){
										echo str_replace(chr(13),'<br>',cOut(mysql_result($result,0,8))) . "<br><br>";
									}//end if
								?>
							</td>
						</tr>
					</table>
					
					<?php
						$datepart = split("-", mysql_result($result,0,9));
						if(mysql_result($result,0,9) >= date("Y-m-d")){
							echo "<b>" . stampToDate(mysql_result($result,0,9), "l \\t\h\e jS \o\f F Y") . "</b>";
						} else {
							echo "<span style=\"color: crimson;\"><b>" . stampToDate(mysql_result($result,0,9), "l \\t\h\e jS \o\f F Y") . "</b></span>";
						}//end if
					?> [ <a href="<?echo CalRoot;?>/?month=<?echo $datepart[1];?>&day=<?echo $datepart[2];?>&year=<?echo $datepart[0];?>" class="eventMain">All Events This Day</a> ]
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="eventMain" width="10">&nbsp;</td>
							<td class="eventMain">
					
					<?php
						$starTime = "";
						$endTime = "";
						
						//check start time
						if(mysql_result($result,0,10) != ''){
							$timepart = split(":", mysql_result($result,0,10));
							$startTime = date("h:ia", mktime($timepart[0], $timepart[1], $timepart[2]));
						}//end if
						
						//check end time
						if(mysql_result($result,0,12) != ''){
							$timepart = split(":", mysql_result($result,0,12));
							$endTime = " to " . date("h:ia", mktime($timepart[0], $timepart[1], $timepart[2]));
						}//end if
						
						if(mysql_result($result,0,11) == 0){
							if(strlen($endTime) > 0){
								echo $startTime . $endTime;
							} else {
								echo "Starts at " . $startTime;
							}//end if
						} elseif(mysql_result($result,0,11) == 1){
							echo "This is an All Day Event";
						} elseif(mysql_result($result,0,11) == 2){
							echo "Start Time TBA";
						}//end if
					?>
					</table>
				<?php
					if(mysql_result($result,0,"SeriesID") != ''){
					?>
						<br>
						<b>This event occurs on multiple dates.</b> [ <a href="<?echo CalRoot;?>/index.php?com=serieslist&sID=<?echo mysql_result($result,0,"SeriesID");?>" class="eventMain">All Events in this Series</a> ]
						<br>
					<?
					}//end if
				?>	
					<br>
					
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td class="eventMain" width="50%" valign="top">
								<b>Location</b>
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tr>
										<td width="10">&nbsp;</td>
										<td class="eventMain">
										<?php
											$showDrive = 1;
											if(mysql_result($result,0,2) != ''){
												echo cOut(mysql_result($result,0,2)) . "<br>";
											} else {
												$showDrive = 0;
											}//end if
											if(mysql_result($result,0,3) != ''){
												echo cOut(mysql_result($result,0,3)) . "<br>";
											} else {
												$showDrive = 0;
											}//end if
											if(mysql_result($result,0,4) != ''){
												echo cOut(mysql_result($result,0,4)) . "<br>";
											}//end if
											if(mysql_result($result,0,5) != ''){
												echo cOut(mysql_result($result,0,5)) . ", ";
											} else {
												$showDrive = 0;
											}//end if
											if(mysql_result($result,0,6) != ''){
													echo cOut(mysql_result($result,0,6)) . " ";
												} else {
													$showDrive = 0;
												}//end if
											if(mysql_result($result,0,7) != ''){
												echo cOut(mysql_result($result,0,7));
											} else {
												$showDrive = 0;
											}//end if
										?>
										</td>
									</tr>
								</table>
								
								<?php
								if(mysql_result($result,0,25) == 1){
									$result2 = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
								?>
								<br>
								<b>Registration</b>&nbsp;
								<?php
									if(mysql_result($result,0,26) != 0 && mysql_result($result,0,26) <= mysql_result($result2,0,0)){
									?>
										<img src="<?echo CalRoot;?>/images/reg-overflow.gif" width="100" height="7" alt="" border="0">
									<?
									} else {
										$regUsed = mysql_result($result2,0,0);
										$regAvailable = mysql_result($result,0,26);
										
										if($regAvailable > 0){
											if($regUsed > 0){
												$regWidth = ($regUsed / $regAvailable) * 100;
												$fillWidth = 100 - $regWidth;
											} else {
												$regWidth = 0;
												$fillWidth = 100;
											}//end if
										?>
											<img src="<?echo CalRoot;?>/images/reg-full.gif" width="<?echo $regWidth;?>" height="7" alt="" border="0" style="border-left: solid #000000 0.5px;"><img src="<?echo CalRoot;?>/images/reg-empty.gif" width="<?echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: solid #000000 0.5px;">
									<?
										}//end if
									}//end if
								?>
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tr>
										<td width="10">&nbsp;</td>
										<td class="eventMain">
											Registrants: <?echo mysql_result($result2,0,0);?><br>
											Spaces Available: <?if(mysql_result($result,0,26) == 0){
																	echo "Unlimited";
																} else {
																	echo mysql_result($result,0,26);
																}//end if?>
										</td>
									</tr>
								</table>
								<?php
								}//end if
								?>
								
							<?php
							if((mysql_result($result,0,24) != '' && mysql_result($result,0,24) != 'http://') OR (mysql_result($result,0,13) != '') OR (mysql_result($result,0,14) != '') OR (mysql_result($result,0,15) != '')){
							?>
								<br>
								<b>Contact</b>
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tr>
										<td width="10">&nbsp;</td>
										<td class="eventMain">
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
													<td class="eventMain">
													<?if(mysql_result($result,0,14) != '' || mysql_result($result,0,13) != ''){?>
															<?if(mysql_result($result,0,13) != ''){echo cOut(mysql_result($result,0,13)) . "<br>";}?>
															<?if(mysql_result($result,0,14) != ''){?>
																<script language="JavaScript">
																<?
																	$eParts = explode("@", cOut(mysql_result($result,0,14)));
																	$edParts = explode(".", $eParts[1]);
																?>
																	var ename = '<?echo $eParts[0];?>';
																	var edomain = '<?echo $edParts[0];?>';
																	var eext = '<?echo $edParts[1];?>';
																	document.write('Email: <a href="mailto:' + ename + '@' + edomain + '.' + eext + '" class="eventMain">' + ename + '@' + edomain + '.' + eext + '</a><br>');
																</script>
															<?}//end if
														}//end if
														
														if(mysql_result($result,0,15) != ''){
															echo cOut(mysql_result($result,0,15)) . "<br>";
														}//end if
														
														if((mysql_result($result,0,24) != '' && mysql_result($result,0,24) != 'http://')){	?>
															<a href="<?echo CalRoot . "/link/?tID=1&oID=" . mysql_result($result,0,0);?>" class="eventMain" target="_blank">Visit Website</a>
													<?	}//end if	?>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								
						<?	}//end if ?>
							</td>
							<td>&nbsp;</td>
							<td class="eventMain" width="50%" valign="top">
							<?	if(mysql_result($result,0,25) == 1){?>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="25"><img src="<?echo CalRoot;?>/images/icon-clipboard.gif" width="20" height="20" alt="" border="0"></td>
											<?if(mysql_result($result,0,26) <= mysql_result($result2,0,0) AND mysql_result($result,0,26) > 0){?>
												<td class="eventMain"><b>Event Full</b> -- <a href="<?echo CalRoot;?>/index.php?com=register&eID=<?echo $eID;?>&wID=1" class="eventMain">Overflow Registration Only</a></td>
											<?} else {?>
												<td><a href="<?echo CalRoot;?>/index.php?com=register&eID=<?echo $eID;?>" class="eventMain">Register For This Event</a></td>
											<?}//end if?>
										</tr>
									</table>
							<?	}//end if	?>
							
							<?	if($showDrive == 1){
								?>
									<br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="25"><img src="<?echo CalRoot;?>/images/icon-globe.gif" width="20" height="20" alt="" border="0"></td>
											<td><a href="<?echo CalRoot;?>/link/?tID=2&oID=<?echo mysql_result($result,0,0);?>" target="_blank" class="eventMain">Get Driving Directions</a></td>
										</tr>
									</table>
									
							<?}//end if?>
								
								<br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="25"><img src="<?echo CalRoot;?>/images/icon-calendar.gif" width="20" height="20" alt="" border="0"></td>
											<td><a href="<?echo CalRoot;?>/link/SaveEvent.php?eID=<?echo mysql_result($result,0,0);?>" class="eventMain">Add To Your Personal Calendar</a></td>
										</tr>
									</table>
								
								<br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="25"><img src="<?echo CalRoot;?>/images/icon-email.gif" width="20" height="20" alt="" border="0"></td>
											<td><a href="<?echo CalRoot;?>/index.php?com=send&eID=<?echo mysql_result($result,0,0);?>" class="eventMain">Email To A Friend</a></td>
										</tr>
									</table>
								
							<?	if(mysql_result($result,0,7) != ''){?>
								<br>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="25"><img src="<?echo CalRoot;?>/images/icon-weather.gif" width="20" height="20" alt="" border="0"></td>
											<td><a href="<?echo CalRoot;?>/link/?tID=3&oID=<?echo mysql_result($result,0,0);?>" class="eventMain" target="_blank">Current Weather Conditions</a></td>
										</tr>
									</table>
							<?	}//end if?>
								
							</td>
						</tr>
					</table>
					
				<?php
				} else {
					echo "<br>The event you're looking for is unavailable.<br><br><a href=\"" . CalRoot . "/\" class=\"main\">Click here to browse available events.</a>";
				}//end if
			?>
		</td>
	</tr>
</table>