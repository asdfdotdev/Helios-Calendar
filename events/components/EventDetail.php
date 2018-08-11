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
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
	
	if(hasRows($result)){
		$hrFormat = "h";
		$minHr = 1;
		if($hc_timeInput == 23){
			$hrFormat = "H";
			$minHr = 0;
		}//end if
		
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
		$locTag = "";
		$cost = cOut(mysql_result($result,0,36));
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
		$locLat = "";
		$locLon = "";
		$locLink = "";
		$locCountry = cOut(mysql_result($result,0,37));
			
		if($locID > 0){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($locID));
			if(hasRows($result)){
				$locName = cOut(mysql_result($result,0,1));
				$locAddress = cOut(mysql_result($result,0,2));
				$locAddress2 = cOut(mysql_result($result,0,3));
				$locCity = cOut(mysql_result($result,0,4));
				$locState = cOut(mysql_result($result,0,5));
				$locZip = cOut(mysql_result($result,0,7));
				$locURL = cOut(mysql_result($result,0,8));
				$locPhone = cOut(mysql_result($result,0,9));
				$locEmail = cOut(mysql_result($result,0,10));
				$locDesc = cOut(mysql_result($result,0,11));
				$locCountry = cOut(mysql_result($result,0,6));
				$locLat = cOut(mysql_result($result,0,15));
				$locLon = cOut(mysql_result($result,0,16));
				$locLink = "<a href=\"" . CalRoot . "/link/index.php?tID=2&amp;oID=" . $eID . "&amp;lID=" . $locID . "\" target=\"_blank\" class=\"eventDetailLink\">Click For Directions</a>";
			}//end if
		}//end if
		
		$addIt = 0;
		if(isset($_SESSION['hc_trail'])){
			if(!preg_match("," . $eID . ",", $_SESSION['hc_trail'])){
				$_SESSION['hc_trail'] = $_SESSION['hc_trail'] . $eID . ',';
				$addIt = 1;
			}//end if
			
		} else {
			$_SESSION['hc_trail'] = "0," . $eID . ',';
			$addIt = 1;
		}//end if
		
		if($addIt > 0){
			doQuery("UPDATE " . HC_TblPrefix . "events SET Views = Views + 1 WHERE PkID = " . $eID);
		}//end if	?>
		
		<script language="JavaScript" type="text/javascript">
			document.title = "<?php echo $eventTitle;?> :: " + document.title;
			
			function togThis(doTog, doLink){
				if(document.getElementById(doTog).style.display=='none'){
						document.getElementById(doTog).style.display = 'block';
						document.getElementById(doLink).innerHTML = 'Less...';
				} else {
						document.getElementById(doTog).style.display = 'none';
						document.getElementById(doLink).innerHTML = 'More...';
				}//end if
			}//end togThis()
		</script>
		<div class="eventDetailTitle"><?php echo $eventTitle;?></div>
<?php	if($eventDesc != ''){	?>
			<div class="eventDetailDesc"><?php echo nl2br($eventDesc);?></div>
<?php	}//end if	?>
	
	<div id="eventDetailInfo">
<?php	$datepart = explode("-", $eventDate);?>
		<div <?php if($eventDate >= date("Y-m-d")){?>class="eventDetailDate"<?php }else{?>class="eventDetailDatePast"<?php }?>><?php echo stampToDate($eventDate, $hc_dateFormat);?></div>
	
<?php	if($eventStartTime != ''){
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
		<div class="eventDetailTime"><?php echo $eventTime;?></div>
<?php
		if($cost != ''){
			echo "<br /><div class=\"eventDetailHeader\">Cost:</div>" . $cost . "<br />";
		}//end if	?>
		
		<br />
		<div class="eventDetailHeader">Location:&nbsp;&nbsp;
		
<?php	if($locAddress != '' &&
			$locCity != '' &&
			$locState != '' &&
			$locZip != ''){	?>
			<a href="<?php echo CalRoot;?>/link/index.php?tID=2&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" target="_blank" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconGlobe.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/index.php?tID=2&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" target="_blank" class="eventDetailLink">Map</a>&nbsp;&nbsp;
<?php	}//end if
		if($locZip != ''){?>
			<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconWeather.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank">Weather</a>
<?php	}//end if	?>
		</div>
	
<?php	if($locName != ''){
			echo $locName . "<br />";
			$locTag .= "<b>" . $locName . "</b><br />";
		}//end if
		if($locAddress != ''){
			echo $locAddress . "<br />";
			$locTag .= $locAddress . "<br />";
		}//end if
		if($locAddress2 != ''){
			echo $locAddress2 . "<br />";
			$locTag .= $locAddress2 . "<br />";
		}//end if
		if($locCity != ''){
			echo $locCity . ", ";
			$locTag .= $locCity . ", ";
		}//end if
		if($locState != ''){
			echo $locState . " ";
			$locTag .= $locState . " ";
		}//end if
		if($locCountry != ''){
			echo $locCountry . " ";
		}//end if
		if($locZip != ''){
			echo $locZip . "<br />";
			$locTag .= $locZip;
		}//end if
		if($locEmail != ''){	?>
			<br />Email:
			<script language="JavaScript" type="text/JavaScript">
			//<!--
		<?php 	$eParts = explode("@", $locEmail);?>
				var lname = '<?php echo $eParts[0];?>';
				var ldomain = '<?php echo $eParts[1];?>';
				document.write('<a href="mailto:' + lname + '@' + ldomain + '" class="eventMain">' + lname + '@' + ldomain + '</a>');
			//-->
			</script>
<?php	}//end if
		if($locPhone != ''){
			echo "<br />Phone: " . $locPhone;
		}//end if
		if($locURL != '' && $locURL != 'http://'){	?>
			<br />Website: <a href="<?php echo CalRoot;?>/link/index.php?tID=4&amp;oID=<?php echo $locID;?>" target="_blank" class="eventMain">Click to Visit</a>
<?php	}//end if
		if($locDesc != ''){
			echo "<br /><br />" . $locDesc . "<br />";
		}//end if
		
		if(($contactURL != '' && $contactURL != 'http://') || ($contactName != '') 
			|| ($contactEmail != '') 
			|| ($contactPhone != '')){?>
			<br /><br />
			<div class="eventDetailHeader">Contact:</div>
	<?php	if($contactName != '' || $contactEmail){?>
	<?php		if($contactName != ''){echo $contactName . "<br />";}?>
	<?php		if($contactEmail != ''){?>
					Email: <script language="JavaScript" type="text/JavaScript">
							//<!--
				<?php	$eParts = explode("@", $contactEmail);?>
						var ename = '<?php echo $eParts[0];?>';
						var edomain = '<?php echo $eParts[1];?>';
						document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain">' + ename + '@' + edomain + '</a><br />');
						//-->
					</script>
		<?php	}//end if
			}//end if
			
			if($contactPhone != ''){
				echo "Phone: " . $contactPhone . "<br>";
			}//end if
			
			if(($contactURL != '' && $contactURL != 'http://')){	?>
				Website: <a href="<?php echo CalRoot . "/link/index.php?tID=1&amp;oID=" . $eID;?>" class="eventMain" target="_blank">Click to Visit</a>
	<?php	}//end if	?>
		
<?php	}//end if ?>
	</div>
	<div id="eventDetailTools">
	
<?php
	if($allowRegistration == 1){	?>
	<div class="eventDetailReg">
<?php
		$result = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
		$regUsed = mysql_result($result,0,0);
		$regAvailable = $maxRegistration;
		$regLimit = "Unlimited";
		$fillWidth = 195;	
		$regLimit = $regAvailable;
		$regText = "Register Now";
		if($regAvailable <= $regUsed AND $regAvailable > 0){	
			$regText = "Overflow Registration";?>
			<div align="center"><b>Attendence Limit Met</b></div>
			<div style="line-height: 15px;"><img src="<?php echo CalRoot;?>/images/meter/overflow.gif" width="<?php echo $fillWidth;?>" height="7" alt="Event Limit Met" border="0" />&nbsp;</div>
			<b><?php echo $regUsed;?></b> of <b><?php echo $regLimit;?></b> Spaces Taken
<?php	} else {
			if($regAvailable > 0){
				$regWidth = 0;
				if($regUsed > 0){
					$regWidth = ($regUsed / $regAvailable) * $fillWidth;
					$fillWidth = $fillWidth - $regWidth;
				}//end if	?>
				<div style="line-height: 15px;"><img src="<?php echo CalRoot;?>/images/meter/full.gif" width="<?php echo $regWidth;?>" height="7" alt="" border="0" style="line-height: 25px;border-left: .5px solid #000000;" /><img src="<?php echo CalRoot;?>/images/meter/empty.gif" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: .5px solid #000000;" />&nbsp;</div>
				<b><?php echo $regUsed;?></b> of <b><?php echo $regLimit;?></b> Spaces Taken
	<?php	} elseif($regAvailable == 0) {	?>
				<div align="center"><b>No Attendence Limit</b></div>
				<b><?php echo $regUsed;?></b> People Currently Registered
	<?php	}//end if
		}//end if	?>
		<div align="center"><a href="<?php echo CalRoot;?>/index.php?com=register&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconRegister.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/index.php?com=register&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><?php echo $regText;?></a></div>
	</div>
<?php
	}//end if	?>

	
	<div class="eventDetailToolbox">
		<b>Share this Event</b><br />
		<a class="eventShare" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/google.png" alt="Bookmark this on google" title="Bookmark this on google" align="absmiddle" /></a>
		<a class="eventShare" href="http://del.icio.us/post?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/delicious.png" alt="Bookmark this on del.icio.us" title="Bookmark this on del.icio.us" align="absmiddle" /></a>
		<a class="eventShare" href="http://digg.com/submit?phase=2&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/digg.png" alt="Submit this to digg" title="Submit this to digg" align="absmiddle" /></a>
		<a class="eventShare" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/yahoo.png" alt="Bookmark this at yahoo" title="Bookmark this at yahoo" align="absmiddle" /></a>
		<a class="eventShare" href="http://reddit.com/submit?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/reddit.png" alt="Bookmark this at reddit" title="Bookmark this at reddit" align="absmiddle" /></a>
		<a class="eventShare" href="http://www.furl.net/storeIt.jsp?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/furl.png" alt="Bookmark this at furl" title="Bookmark this at furl" align="absmiddle" /></a>
		
		<a class="eventMain" href="javascript:;" onclick="togThis('eventDetailShareMore', 'eventShareLink');" id="eventShareLink">More...</a><br />
		<div id="eventDetailShareMore" style="display:none;">
			<a class="eventShare" href="http://www.backflip.com/add_page_pop.ihtml?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/backflip.png" alt="backflip" title="backflip" /></a>
			<a class="eventShare" href="http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/blinkbits.png" alt="blink bits" title="blink bits" /></a>
			<a class="eventShare" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Title=<?php echo urlencode($eventTitle);?>&amp;Url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/blinklist.png" alt="blinklist" title="blinklist" /></a>
			<a class="eventShare" href="http://www.bluedot.us/Authoring.aspx?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/bluedot.png" alt="diigo" title="diigo" /></a>
			<a class="eventShare" href="http://www.buddymarks.com/add_bookmark.php?bookmark_title=<?php echo urlencode($eventTitle);?>&amp;bookmark_url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/buddymarks.png" alt="buddymarks" title="buddymarks" /></a>
			<a class="eventShare" href="http://www.citeulike.org/posturl?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/citeulike.png" alt="citeulike" title="citeulike" /></a>
			<a class="eventShare" href="http://www.connotea.org/add?continue=return&amp;uri=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/connotea.png" alt="connotea" title="connotea" /></a>
			<a class="eventShare" href="http://de.lirio.us/rubric/post?uri=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/delirious.png" alt="delirious" title="delirious" /></a>
			<a class="eventShare" href="http://www.diigo.com/post?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/diigo.png" alt="diigo" title="diigo" /></a>
			<a class="eventShare" href="http://www.feedmarker.com/admin.php?do=bookmarklet_mark&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/feedmarker.png" alt="feedmarker" title="feedmarker" /></a>
			<a class="eventShare" href="http://www.feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;name=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;version=0.7"><img src="<?php echo CalRoot;?>/images/share/feedmelinks.png" alt="feedmelinks" title="feedmelinks" /></a>
			<a class="eventShare" href="http://www.givealink.org/cgi-pub/bookmarklet/bookmarkletLogin.cgi?&amp;uri=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/givealink.png" alt="givealink" title="givealink" /></a>
			<a class="eventShare" href="http://www.gravee.com/account/bookmarkpop?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/gravee.png" alt="gravee" title="gravee" /></a>
			<a class="eventShare" href="http://lister.lilisto.com/?t=<?php echo urlencode($eventTitle);?>&amp;l=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/lilisto.png" alt="lilisto" title="lilisto" /></a>
			<a class="eventShare" href="http://www.linkagogo.com/go/AddNoPopup?title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/linkagogo.png" alt="linkagogo" title="linkagogo" /></a>
			<a class="eventShare" href="http://www.linkroll.com/index.php?action=insertLink&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/linkroll.png" alt="linkroll" title="linkroll" /></a>
			<a class="eventShare" href="http://ma.gnolia.com/bookmarklet/add?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/magnolia.png" alt="magnolia" title="magnolia" /></a>
			<a class="eventShare" href="http://netvouz.com/action/submitBookmark?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>&amp;popup=no"><img src="<?php echo CalRoot;?>/images/share/netvouz.png" alt="netvouz" title="netvouz" /></a>
			<a class="eventShare" href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;h=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/newsvine.png" alt="Bookmark this at newsvine" title="Bookmark this at newsvine" /></a>
			<a class="eventShare" href="http://www.rojo.com/add-subscription/?resource=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/rojo.png" alt="rojo" title="rojo" /></a>
			<a class="eventShare" href="http://www.scuttle.org/bookmarks.php/?action=add&amp;address=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/scuttle.png" alt="scuttle" title="scuttle" /></a>
			<a class="eventShare" href="http://www.shadows.com/shadows.aspx?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/shadows.png" alt="shadows" title="shadows" /></a>
			<a class="eventShare" href="http://www.simpy.com/simpy/LinkAdd.do?title=<?php echo urlencode($eventTitle);?>&amp;href=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;v=6&amp;border="><img src="<?php echo CalRoot;?>/images/share/simpy.png" alt="simpy" title="simpy" /></a>
			<a class="eventShare" href="http://www.spurl.net/spurl.php?v=3&amp;title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/spurl.png" alt="spurl" title="spurl" /></a>
			<a class="eventShare" href="http://www.squidoo.com/lensmaster/bookmark?<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/squidoo.png" alt="squidoo" title="squidoo" /></a>
			<a class="eventShare" href="http://www.stumbleupon.com/submit?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/stumbleit.png" alt="stumble upon" title="stumble upon" /></a>
			<a class="eventShare" href="http://www.tagtooga.com/tapp/db.exe?c=jsEntryForm&amp;b=fx&amp;title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/tagtooga.png" alt="tagtooga" title="tagtooga" /></a>
			<a class="eventShare" href="http://www.unalog.com/my/stack/link?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/unalog.png" alt="unalog" title="unalog" /></a>
			<a class="eventShare" href="http://www.wists.com/r.php?r=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/wists.png" alt="wists" title="wists" /></a>
			<a class="eventShare" href="http://tag.zurpy.com/?box=1&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/zurpy.png" alt="zurpy" title="zurpy" /></a>
		</div>
	
		<a href="<?php echo CalRoot;?>/index.php?com=send&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconEmail.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/index.php?com=send&amp;eID=<?php echo $eID;?>" class="eventDetailLink">Email to a Friend</a>
	
		<br /><br />
		<b>Save to Your Calendar</b><br />
		<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=1" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/share/google.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=1" target="_blank" class="eventDetailLink">Google Calendar</a><br />
		<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=2" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconY.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=2" target="_blank" class="eventDetailLink">Yahoo! Calendar</a><br />
		<img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconDownload.gif" width="16" height="16" alt="" border="0" />&nbsp;iCal (<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&cID=3" class="eventDetailLink">download</a>) (<a href="<?php echo "webcal://" . substr(CalRoot, 7) . "/link/SaveEvent.php?eID=" . $eID . "&amp;cID=3";?>" class="eventDetailLink">subscribe</a>)<br />
		<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=4" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconDownload.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=4" class="eventDetailLink">Outlook (vCalendar)</a><br />
	</div>

<?php
	if($seriesID != '' && $eventDate >= date("Y-m-d")){?>
		<br />
		<b>Other Dates For This Event</b><br />
<?php	$result = doQuery("SELECT PkID, StartDate FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . $seriesID . "' AND StartDate >= NOW() LIMIT 5");
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
				if($eID == $row[0]){echo "**";}//end if
				echo "<a href=\"" . CalRoot . "/index.php?com=detail&amp;eID=" . $row[0] . "\" class=\"eventDetailDates\">" . stampToDate($row[1], $hc_dateFormat) . "</a><br />";
			}//end while
		}//end if
		echo "<div style=\"text-align:right;padding-right:15px;\"><a href=\"" . CalRoot . "/index.php?com=serieslist&amp;sID=" . $seriesID . "\" class=\"eventMain\">View All Dates</a></div>";
	}//end if	?>
	
	</div>	
<?php 	if(isset($hc_googleKey) && $hc_googleKey != '' && isset($_GET['com']) && $_GET['com'] == 'detail'){	
			if($locLat != '' && $locLon != ''){?>
				<br /><br />
				<div style="clear:both;">&nbsp;</div>
				<div id="hc_Gmap"></div>
	<?php 	}//end if	?>
<?php 	}//end if	?>
<?php
	} else {	?>
		<br />
		The event you are looking for cannot be found.
		<br /><br />
		From here you can:
		<ol>
			<li style="line-height: 30px;">Select a shaded day <span class="miniCalEvents" style="padding:3px;">17</span> from the mini-cal.</li>
			<li style="line-height: 30px;">Use the mini-cal to Navigate forward <span class="miniCalNav" style="padding:3px;">&gt;</span> or backward <span class="miniCalNav" style="padding:3px;">&lt;</span> a month.</li>
			<li style="line-height: 30px;"><a href="<?php echo CalRoot;?>/" class="eventMain">Click here to view this week's events.</a></li>
		</ol>
<?php
	}//end if	?>