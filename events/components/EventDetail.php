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
		$eventTitle = cOut(mysql_result($result,0,1));
		$eventDesc = cOut(mysql_result($result,0,8));
		$eventDate = cOut(mysql_result($result,0,9));
		$eventStartTime = cOut(mysql_result($result,0,10));
		$eventEndTime = cOut(mysql_result($result,0,12));
		$eventTBD = cOut(mysql_result($result,0,11));
		$contactName = cOut(mysql_result($result,0,13));
		$contactEmail = cOut(mysql_result($result,0,14));
		$contactPhone = cOut(mysql_result($result,0,15));
		$contactURL = cOut(mysql_result($result,0,24));
		$allowRegistration = cOut(mysql_result($result,0,25));
		$maxRegistration = cOut(mysql_result($result,0,26));
		$locID = cOut(mysql_result($result,0,35));
		$views = cOut(mysql_result($result,0,28));
		$seriesID = cOut(mysql_result($result,0,19));
		
		if($locID <= 0){
			$locName = cOut(mysql_result($result,0,2));
			$locAddress = cOut(mysql_result($result,0,3));
			$locAddress2 = cOut(mysql_result($result,0,4));
			$locCity = cOut(mysql_result($result,0,5));
			$locState = cOut(mysql_result($result,0,6));
			$locZip = cOut(mysql_result($result,0,7));
			$locDesc = "";
			$locPhone = "";
			$locURL = "";
			$locEmail = "";
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($locID));
			$locName = cOut(mysql_result($result,0,1));
			$locAddress = cOut(mysql_result($result,0,2));
			$locAddress2 = cOut(mysql_result($result,0,3));
			$locCity = cOut(mysql_result($result,0,4));
			$locState = cOut(mysql_result($result,0,5));
			$locZip = cOut(mysql_result($result,0,6));
			$locURL = cOut(mysql_result($result,0,7));
			$locPhone = cOut(mysql_result($result,0,8));
			$locEmail = cOut(mysql_result($result,0,9));
			$locDesc = cOut(mysql_result($result,0,10));
		}//end if
		
		
		doQuery("UPDATE " . HC_TblPrefix . "events SET Views = " . ($views + 1) . " WHERE PkID = " . $eID)	?>
		<div class="eventDetailTitle"><?echo $eventTitle;?></div>
	<?	if($eventDesc != ''){	?>
			<div class="eventDetailDesc"><?echo nl2br($eventDesc);?></div>
	<?	}//end if	?>
	
	<div id="eventDetailInfo">
	<?	$datepart = explode("-", $eventDate);?>
		<div <?if($eventDate >= date("Y-m-d")){?>class="eventDetailDate"<?}else{?>class="eventDetailDatePast"<?}?>><?echo stampToDate($eventDate, $hc_dateFormat);?></div>
	
	<?	if($eventStartTime != ''){
			$timepart = explode(":", $eventStartTime);
			$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		if($eventEndTime != ''){
			$timepart = explode(":", $eventEndTime);
			$endTime = " - " . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2]));
		}//end if
		
		if($eventTBD == 0){
			if(strlen($eventEndTime) > 0){
				$eventTime = $startTime . $endTime;
			} else {
				$eventTime = "Starts at " . $startTime;
			}//end if
		} elseif($eventTBD == 1){
			$eventTime = "This is an All Day Event";
		} elseif($eventTBD == 2){
			$eventTime = "Start Time TBA";
		}//end if	?>
		<div class="eventDetailTime"><?echo $eventTime;?></div>
		<br />
		<b>Location</b><br />
	<?	if($locName != ''){
			echo $locName . "<br />";
		}//end if
		if($locAddress != ''){
			echo $locAddress . "<br />";
		}//end if
		if($locAddress2 != ''){
			echo $locAddress2 . "<br />";
		}//end if
		if($locCity != ''){
			echo $locCity . ", ";
		}//end if
		if($locState != ''){
			echo $locState . " ";
		}//end if
		if($locZip != ''){
			echo $locZip . "<br />";
		}//end if
		if($locEmail != ''){	?>
			<br />Email:
			<script language="JavaScript" type="text/JavaScript">
			<?	$eParts = explode("@", $locEmail);?>
				var lname = '<?echo $eParts[0];?>';
				var ldomain = '<?echo $eParts[1];?>';
				document.write('<a href="mailto:' + lname + '@' + ldomain + '" class="eventMain">' + lname + '@' + ldomain + '</a>');
			</script>
	<?	}//end if
		if($locPhone != ''){
			echo "<br />Phone: " . $locPhone;
		}//end if
		if($locURL != ''){	?>
			<br />Website: <a href="<?echo $locURL;?>" class="eventMain" target="_blank">Click to Visit</a>
	<?	}//end if
		if($locDesc != ''){
			echo "<br /><br />" . $locDesc . "<br />";
		}//end if
		
		if(($contactURL != '' && $contactURL != 'http://') || ($contactName != '') 
			|| ($contactEmail != '') 
			|| ($contactPhone != '')){?>
			<br />
			<b>Contact</b> <?if($contactName != '' || $contactEmail != ''){?><a href="<?echo CalRoot;?>/link/SaveContact.php?eID=<?echo $eID;?>" title="Download Event Contact vCard"><img src="<?echo CalRoot;?>/images/icons/iconContact.gif" width="15" height="13" alt="Download Event Contact vCard" border="0" /></a><?}?><br />
		<?	if($contactName != '' || $contactEmail){?>
				<?if($contactName != ''){echo $contactName . "<br />";}?>
				<?if($contactEmail != ''){?>
					<script language="JavaScript" type="text/JavaScript">
					<?	$eParts = explode("@", $contactEmail);?>
						var ename = '<?echo $eParts[0];?>';
						var edomain = '<?echo $eParts[1];?>';
						document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain">' + ename + '@' + edomain + '</a><br />');
					</script>
				<?}//end if
			}//end if
			
			if($contactPhone != ''){
				echo $contactPhone . "<br>";
			}//end if
			
			if(($contactURL != '' && $contactURL != 'http://')){	?>
				<a href="<?echo CalRoot . "/link/?tID=1&amp;oID=" . $eID;?>" class="eventMain" target="_blank">Visit Website</a>
		<?	}//end if	?>
		
	<?	}//end if ?>
	</div>
	<div id="eventDetailTools">
	<div style="padding-bottom:5px;font-weight:bold;">Share This Event</div>
	<div id="eventShare">
		<li><a href="http://del.icio.us/post?url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="del.icio.us" target="_blank"><img src="<?echo CalRoot;?>/images/share/delicious.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://digg.com/submit?phase=2&amp;url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="digg" target="_blank"><img src="<?echo CalRoot;?>/images/share/digg.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Description=&amp;Url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="BlinkList" target="_blank"><img src="<?echo CalRoot;?>/images/share/blinklist.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://reddit.com/submit?url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="reddit" target="_blank"><img src="<?echo CalRoot;?>/images/share/reddit.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://co.mments.com/track?url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="co.mments" target="_blank"><img src="<?echo CalRoot;?>/images/share/co.mments.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://www.newsvine.com/_tools/seed&amp;save?u=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="NewsVine" target="_blank"><img src="<?echo CalRoot;?>/images/share/newsvine.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="YahooMyWeb" target="_blank"><img src="<?echo CalRoot;?>/images/share/yahoomyweb.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://www.furl.net/storeIt.jsp?u=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="furl" target="_blank"><img src="<?echo CalRoot;?>/images/share/furl.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://tailrank.com/share/?text=&amp;link_href=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="tailrank" target="_blank"><img src="<?echo CalRoot;?>/images/share/tailrank.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
		<li><a href="http://www.spurl.net/spurl.php?url=<?echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>" title="spurl" target="_blank"><img src="<?echo CalRoot;?>/images/share/spurl.gif" width="20" height="20" alt="" border="0" align="middle" /></a></li>
	</div>
	<div style="clear:both;"></div>
<?	if($allowRegistration == 1){
		$result = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
		$regUsed = mysql_result($result,0,0);
		$regAvailable = $maxRegistration;
		$regLimit = "Unlimited";
		$fillWidth = 175;	
		$regLimit = $regAvailable;
		if($regAvailable <= $regUsed AND $regAvailable > 0){	
			$regText = "Overflow Registration";?>
			<b>Registration: (Event Full)</b><br />
			<div style="line-height: 15px;"><img src="<?echo CalRoot;?>/images/meter/overflow.gif" width="<?echo $fillWidth;?>" height="7" alt="Event Limit Met" border="0" />&nbsp;</div>
			<b><?echo $regUsed;?></b> of <b><?echo $regLimit;?></b> Spaces Taken
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
				<b><?echo $regUsed;?></b> of <b><?echo $regLimit;?></b> Spaces Taken
		<?	} elseif($regAvailable == 0) {
				$regText = "Register Now";	?>
				<b>Registration (No Limit):</b><br />
				<?echo $regUsed;?> Current Registrants
		<?	}//end if
		}//end if	?>
		<br /><br />
<?	}//end if	?>
		<div id="eventDetailToolbox">
	<?	if($allowRegistration == 1){	?>
		<a href="<?echo CalRoot;?>/index.php?com=register&amp;eID=<?echo $eID;?>" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconRegister.gif" width="16" height="16" alt="" border="0" /> <?echo $regText;?></a><br />
	<?	}//end if	?>
	
	
		<?	if($locAddress != '' &&
				$locCity != '' &&
				$locState != '' &&
				$locZip != ''){	?>
				<a href="<?echo CalRoot;?>/link/?tID=2&amp;oID=<?echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" target="_blank" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconGlobe.gif" width="16" height="16" alt="" border="0" /> Get Driving Directions</a><br />
		<?	}//end if
			if($locZip != ''){?>
				<a href="<?echo CalRoot;?>/link/?tID=3&amp;oID=<?echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailToolbox" target="_blank"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconWeather.gif" width="16" height="16" alt="" border="0" /> Current Weather Conditions</a><br />
		<?	}//end if	?>
	
	
	
		<a href="<?echo CalRoot;?>/link/SaveEvent.php?eID=<?echo $eID;?>" class="eventDetailToolbox"> <img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconDownload.gif" width="16" height="16" alt="" border="0" /> Download This Event</a>
		<br />
		<a href="<?echo CalRoot;?>/index.php?com=send&amp;eID=<?echo $eID;?>" class="eventDetailToolbox"><img class="eventDetailToolbox" src="<?echo CalRoot;?>/images/icons/iconEmail.gif" width="16" height="16" alt="" border="0" /> Email To A Friend</a>
		</div>
	<?	if($seriesID != '' && $eventDate >= date("Y-m-d")){?>
			<br />
			<b>Multiple Date Event</b> <a href="<?echo CalRoot;?>/index.php?com=serieslist&amp;sID=<?echo $seriesID;?>" class="eventMain">See All Dates</a>
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