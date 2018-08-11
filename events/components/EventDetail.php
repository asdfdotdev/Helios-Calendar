<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	$eID = 0;
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$eID = $_GET['eID'];
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
	
	if(hasRows($result)){
		doQuery("UPDATE " . HC_TblPrefix . "events SET Views = " . (mysql_result($result,0,28) + 1) . " WHERE PkID = " . $eID)	?>
		<div class="eventDetailTitle"><?echo cOut(mysql_result($result,0,1));?></div>
	<?	if(mysql_result($result,0,8) != ''){	?>
			<div class="eventDetailDesc"><?echo nl2br(cOut(mysql_result($result,0,8)));?></div>
	<?	}//end if	?>
	
	<div id="eventDetailInfo">
	<?	$datepart = split("-", mysql_result($result,0,9));?>
		<div <?if(mysql_result($result,0,9) >= date("Y-m-d")){?>class="eventDetailDate"<?}else{?>class="eventDetailDatePast"<?}?>><?echo stampToDate(mysql_result($result,0,9), $hc_dateFormat);?></div>
	
	<?	if(mysql_result($result,0,10) != ''){
			$timepart = split(":", mysql_result($result,0,10));
			$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		if(mysql_result($result,0,12) != ''){
			$timepart = split(":", mysql_result($result,0,12));
			$endTime = " - " . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		if(mysql_result($result,0,11) == 0){
			if(strlen(mysql_result($result,0,12) != '') > 0){
				$eventTime = $startTime . $endTime;
			} else {
				$eventTime = "Starts at " . $startTime;
			}//end if
		} elseif(mysql_result($result,0,11) == 1){
			$eventTime = "This is an All Day Event";
		} elseif(mysql_result($result,0,11) == 2){
			$eventTime = "Start Time TBA";
		}//end if	?>
		<div class="eventDetailTime"><?echo $eventTime;?></div>
		<br />
		<b>Location</b><br />
	<?	if(mysql_result($result,0,2) != ''){
			echo cOut(mysql_result($result,0,2)) . "<br />";
		}//end if
		if(mysql_result($result,0,3) != ''){
			echo cOut(mysql_result($result,0,3)) . "<br />";
		}//end if
		if(mysql_result($result,0,4) != ''){
			echo cOut(mysql_result($result,0,4)) . "<br />";
		}//end if
		if(mysql_result($result,0,5) != ''){
			echo cOut(mysql_result($result,0,5)) . ", ";
		}//end if
		if(mysql_result($result,0,6) != ''){
			echo cOut(mysql_result($result,0,6)) . " ";
		}//end if
		if(mysql_result($result,0,7) != ''){
			echo cOut(mysql_result($result,0,7));
		}//end if?>
		
	<?	if((mysql_result($result,0,24) != '' && mysql_result($result,0,24) != 'http://') || (mysql_result($result,0,13) != '') || (mysql_result($result,0,14) != '') || (mysql_result($result,0,15) != '')){?>
			<br /><br />
			<b>Contact</b> <?if(mysql_result($result,0,14) != '' || mysql_result($result,0,13) != ''){?><a href="<?echo CalRoot;?>/link/SaveContact.php?eID=<?echo mysql_result($result,0,0);?>" title="Download Event Contact vCard"><img src="<?echo CalRoot;?>/images/icons/iconContact.gif" width="15" height="13" alt="Download Event Contact vCard" border="0" /></a><?}?><br />
		<?	if(mysql_result($result,0,14) != '' || mysql_result($result,0,13) != ''){?>
				<?if(mysql_result($result,0,13) != ''){echo cOut(mysql_result($result,0,13)) . "<br />";}?>
				<?if(mysql_result($result,0,14) != ''){?>
					<script language="JavaScript" type="text/JavaScript">
					<?	$eParts = explode("@", cOut(mysql_result($result,0,14)));?>
						var ename = '<?echo $eParts[0];?>';
						var edomain = '<?echo $eParts[1];?>';
						document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain">' + ename + '@' + edomain + '</a><br />');
					</script>
				<?}//end if
			}//end if
			
			if(mysql_result($result,0,15) != ''){
				echo cOut(mysql_result($result,0,15)) . "<br>";
			}//end if
			
			if((mysql_result($result,0,24) != '' && mysql_result($result,0,24) != 'http://')){	?>
				<a href="<?echo CalRoot . "/link/?tID=1&amp;oID=" . mysql_result($result,0,0);?>" class="eventMain" target="_blank">Visit Website</a>
		<?	}//end if	?>
		
	<?	}//end if ?>
	</div>
	<div id="eventDetailTools">
	<div style="padding-bottom:5px;font-weight:bold;">Share This Event</div>
	<a href="http://digg.com/submit?phase=2&amp;url=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="digg" target="_blank"><img src="<?echo CalRoot;?>/images/share/digg.gif" width="20" height="20" alt="" border="0" align="middle" /></a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.furl.net/storeIt.jsp?u=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="furl" target="_blank"><img src="<?echo CalRoot;?>/images/share/furl.gif" width="20" height="20" alt="" border="0" align="middle" /></a>&nbsp;&nbsp;&nbsp;
	<a href="http://www.spurl.net/spurl.php?url=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="spurl" target="_blank"><img src="<?echo CalRoot;?>/images/share/spurl.gif" width="20" height="20" alt="" border="0" align="middle" /></a>&nbsp;&nbsp;&nbsp;
	<a href="http://tailrank.com/share/?text=&amp;link_href=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="tailrank" target="_blank"><img src="<?echo CalRoot;?>/images/share/tailrank.gif" width="20" height="20" alt="" border="0" align="middle" /></a>&nbsp;&nbsp;&nbsp;
	<a href="http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="blogmarks" target="_blank"><img src="<?echo CalRoot;?>/images/share/blogmarks.gif" width="20" height="20" alt="" border="0" align="middle" /></a>&nbsp;&nbsp;&nbsp;
	<a href="http://reddit.com/submit?url=<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $eID;?>" title="reddit" target="_blank"><img src="<?echo CalRoot;?>/images/share/reddit.gif" width="20" height="20" alt="" border="0" align="middle" /></a>
	<br /><br />
<?	if(mysql_result($result,0,25) == 1){
		$result2 = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
		$regUsed = mysql_result($result2,0,0);
		$regAvailable = mysql_result($result,0,26);
		$regLimit = "Unlimited";
		$fillWidth = 175;	
		$regLimit = $regAvailable;
		if($regAvailable <= $regUsed AND $regAvailable > 0){	
			$regText = "Overflow Registration";?>
			<b>Registration: (Event Full)</b><br />
			<div style="line-height: 15px;"><img src="<?echo CalRoot;?>/images/meter/overflow.gif" width="<?echo $fillWidth;?>" height="7" alt="Event Limit Met" border="0" />&nbsp;</div>
	<?	} else {
			if($regAvailable > 0){
				$regText = "Register Now";
				$regWidth = 0;
				if($regUsed > 0){
					$regWidth = ($regUsed / $regAvailable) * $fillWidth;
					$fillWidth = $fillWidth - $regWidth;
				}//end if	?>
				<b>Registration:</b><br />
				<div style="line-height: 15px;"><img src="<?echo CalRoot;?>/images/meter/full.gif" width="<?echo $regWidth;?>" height="7" alt="" border="0" style="line-height: 25px;border-left: .5px solid #000000;" /><img src="<?echo CalRoot;?>/images/meter/empty.gif" width="<?echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: .5px solid #000000;" />&nbsp;</div>
				
		<?	}//end if
		}//end if	?>
		<b><?echo $regUsed;?></b> of <b><?echo $regLimit;?></b> Spaces Taken
		<br /><br />
<?	}//end if	?>
		<div id="eventDetailToolbox">
	<?	if(mysql_result($result,0,25) == 1){	?>
		<a href="<?echo CalRoot;?>/index.php?com=register&amp;eID=<?echo $eID;?>" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconRegister.gif" width="16" height="16" alt="" border="0" /> <?echo $regText;?></a>
		<br />
	<?	}//end if	?>
	
	<?	if(mysql_result($result,0,3) != '' &&
			mysql_result($result,0,5) != '' &&
			mysql_result($result,0,6) != '' &&
			mysql_result($result,0,7) != ''){	?>
			<a href="<?echo CalRoot;?>/link/?tID=2&amp;oID=<?echo mysql_result($result,0,0);?>" target="_blank" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconGlobe.gif" width="16" height="16" alt="" border="0" /> Get Driving Directions</a>
			<br />
	<?	}//end if?>
		<a href="<?echo CalRoot;?>/link/SaveEvent.php?eID=<?echo mysql_result($result,0,0);?>" class="eventDetailToolbox"> <img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconDownload.gif" width="16" height="16" alt="" border="0" /> Download This Event</a>
		<br />
		<a href="<?echo CalRoot;?>/index.php?com=send&amp;eID=<?echo mysql_result($result,0,0);?>" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconEmail.gif" width="16" height="16" alt="" border="0" /> Email To A Friend</a>
	<?	if(mysql_result($result,0,7) != ''){?>
		<br />
		<a href="<?echo CalRoot;?>/link/?tID=3&amp;oID=<?echo mysql_result($result,0,0);?>" class="eventDetailToolbox" target="_blank"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconWeather.gif" width="16" height="16" alt="" border="0" /> Current Weather Conditions</a>
	<?	}//end if?>
		</div>
	<?	if(mysql_result($result,0,"SeriesID") != '' && mysql_result($result,0,9) >= date("Y-m-d")){?>
			<br />
			<b>Multiple Date Event</b> <a href="<?echo CalRoot;?>/index.php?com=serieslist&amp;sID=<?echo mysql_result($result,0,"SeriesID");?>" class="eventMain">See All Dates</a>
	<?	}//end if	?>
		
	</div>	
		
<?	} else {	?>
		<br />
		The event you are looking for cannot be found.
		<br /><br />
		From here you can:
		<ol>
			<li style="line-height: 30px;">Select a shaded day <span class="miniCalEvents" style="padding:3px;">17</span> from the mini-cal.</li>
			<li style="line-height: 30px;">Use the mini-cal to Navigate forward <span class="miniCalNav" style="padding:3px;">&gt;</span> or backward <span class="miniCalNav" style="padding:3px;">&lt;</span> a month.</li>
			<li style="line-height: 30px;"><a href="<?echo CalRoot;?>/" class="eventMain">Click here to view this week's events.</a></li>
		</ol>
<?	}//end if	?>