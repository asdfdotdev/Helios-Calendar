<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
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
				$locLink = "<a href=\"" . CalRoot . "/link/index.php?tID=2&amp;oID=" . $eID . "&amp;lID=" . $locID . "\" target=\"_blank\" class=\"eventDetailLink\">" . $hc_lang_event['ClickDirs'] . "</a>";
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
		//<!--
			document.title = "<?php echo $eventTitle;?> :: " + document.title;
			
			function togThis(doTog, doLink){
				if(document.getElementById(doTog).style.display == 'none'){
					document.getElementById(doTog).style.display = 'block';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_event['Less'];?>';
				} else {
					document.getElementById(doTog).style.display = 'none';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_event['More'];?>';
				}//end if
			}//end togThis()
			
			function bookIt(){
				var bookURL = "<?php echo CalRoot;?>";
				var bookTitle = "<?php echo CalName;?>";
				
				if(window.sidebar){
					window.sidebar.addPanel(bookTitle, bookURL,"");
				} else if(window.external){
					window.external.AddFavorite(bookURL, bookTitle); 
				} else {
					alert("Your browser doesn't support this function. Please manually bookmark this page.");
				}//end if
			}//end bookIt()
		//-->
		</script>
		<div class="vevent">
		<div id="eventDetailTitle" class="summary"><?php echo $eventTitle;?></div>
<?php	if($eventDesc != ''){	?>
			<div id="eventDetailDesc" class="description"><?php echo $eventDesc;?></div>
<?php	}//end if	?>
	
		<div id="eventDetailInfo">
<?php	$datepart = explode("-", $eventDate);

		$year = $datepart[0];
		$month = $datepart[1];

		$hourOffset = date("G");
		if($hc_timezoneOffset > 0){
			$hourOffset = $hourOffset + abs($hc_timezoneOffset);
		} else {
			$hourOffset = $hourOffset - abs($hc_timezoneOffset);
		}//end if	?>
		
		<div class="eventDetailDate<?php if($eventDate < date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")))){echo "Past";}?>"><?php echo stampToDate($eventDate, $hc_dateFormat);?></div>
	
<?php	$microStartTime = "";
		if($eventStartTime != ''){
			$timepart = explode(":", $eventStartTime);
			$microStartTime = $timepart[0] . $timepart[1] . $timepart[2];
			$startTime = "<abbr class=\"dtstart\" title=\"" . stampToDate($eventDate, "Ymd") . "T" . $microStartTime . "\">" . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2])) . "</abbr>";
		}//end if
		
		if($eventEndTime != ''){
			$timepart = explode(":", $eventEndTime);
			$microEndTime = $timepart[0] . $timepart[1] . $timepart[2];
			$microEndDate = stampToDate($eventDate, "Ymd");
			if($microStartTime > $microEndTime){
				$meDate = explode("-",$eventDate);
				$microEndDate = date("Ymd",mktime(0,0,0,$meDate[1],$meDate[2] + 1,$meDate[0]));
			}//end if
			$endTime = "<abbr class=\"dtend\" title=\"" . $microEndDate . "T" . $microEndTime . "\">" . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2])) . "</abbr>";
		}//end if
		
		if($eventTBD == 0){
			if(strlen($eventEndTime) > 0){
				$eventTime = $startTime . " - " . $endTime;
			} else {
				$eventTime = $hc_lang_event['StartsAt'] . " " . $startTime;
			}//end if
		} elseif($eventTBD == 1){
			$eventTime = $hc_lang_event['AllDay'];
		} elseif($eventTBD == 2){
			$eventTime = $hc_lang_event['TimeTBA'];
		}//end if	?>
		<div class="eventDetailTime"><?php echo $eventTime;?></div>
<?php
		if($cost != ''){
			echo "<br /><div class=\"eventDetailHeader\">" . $hc_lang_event['Cost'] . "</div>" . $cost . "<br />";
		}//end if
		
		$resultC = doQuery("SELECT c.PkID, c.CategoryName
							FROM " . HC_TblPrefix . "eventcategories ec
								LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
							WHERE c.IsActive = 1 AND ec.EventID = '" . cIn($eID) . "'
							ORDER BY c.CategoryName");
		if(hasRows($resultC)){
			echo "<br /><div class=\"eventDetailHeader\">" . $hc_lang_event['Categories'] . "</div>";
			echo "<ul class=\"category\">";
			while($row = mysql_fetch_row($resultC)){
				echo "<li class=\"category\"><a href=\"" . CalRoot . "/index.php?com=searchresult&amp;t=" . $row[0] . "\" class=\"eventMain\">" . $row[1] . "</a></li>";
			}//end while
			echo "</ul>";
		}//end if	?>
		
		<br />
		<div class="vcard">
		<div class="eventDetailHeader"><?php echo $hc_lang_event['Location'];?>&nbsp;
<?php	if($locAddress != '' &&
			$locCity != '' &&
			$locState != '' &&
			$locZip != ''){	?>
			<a href="<?php echo CalRoot;?>/link/index.php?tID=2&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" target="_blank" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconMap.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/index.php?tID=2&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" target="_blank" class="eventDetailLink"><?php echo $hc_lang_event['Map'];?></a>&nbsp;&nbsp;
<?php	}//end if
		if($locZip != ''){?>
			<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconWeather.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/index.php?tID=3&amp;oID=<?php echo $eID;if($locID > 0){echo "&amp;lID=" . $locID;}?>" class="eventDetailLink" target="_blank"><?php echo $hc_lang_event['Weather'];?></a>&nbsp;&nbsp;
<?php	}//end if	
		if($locID > 0) {?>
			<a href="<?php echo CalRoot?>/rssL.php?lID=<?php echo $locID;?>" target="_blank"><img src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="" style="vertical-align:middle;" /></a>
<?php	}//end if	?>
		</div>
	
<?php	if($locName != ''){
			echo "<div class=\"org\">" . $locName . "</div>";
			$locTag .= "<b>" . $locName . "</b><br />";
		}//end if
		if($locAddress != ''){
			echo "<div class=\"street-address\">" . $locAddress . "</div>";
			$locTag .= $locAddress . "<br />";
		}//end if
		if($locAddress2 != ''){
				echo $locAddress2 . "<br />";
				$locTag .= $locAddress2 . "<br />";
			}//end if
		if($locCity != ''){
			echo "<span class=\"locality\">" . $locCity . "</span>, ";
			$locTag .= $locCity . ", ";
		}//end if
		if($locState != ''){
			echo "<span class=\"region\">" . $locState . "</span> ";
			$locTag .= $locState . " ";
		}//end if
		if($locCountry != ''){
			echo "<span class=\"country-name\">" . $locCountry . "</span> ";
			$locTag .= $locCountry . " ";
		}//end if
		if($locZip != ''){
			echo "<span class=\"postal-code\">" . $locZip . "</span><br /> ";
			$locTag .= $locZip;
		}//end if
		if($locEmail != ''){	
			echo "<br />" . $hc_lang_event['Email']?>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
		<?php 	$eParts = explode("@", $locEmail);?>
				var lname = '<?php echo $eParts[0];?>';
				var ldomain = '<?php echo $eParts[1];?>';
				document.write('<a href="mailto:' + lname + '@' + ldomain + '" class="email">' + lname + '@' + ldomain + '</a>');
			//-->
			</script>
<?php	}//end if
		if($locPhone != ''){
			echo "<br />" . $hc_lang_event['Phone'] . "&nbsp;" . $locPhone . "";
			echo "<div style=\"display:none;\" class=\"tel\">" . $locPhone . "</div>";
		}//end if
		if($locURL != '' && $locURL != 'http://'){	?>
			<br /><?php echo $hc_lang_event['Website'];?> <a href="<?php echo CalRoot;?>/link/index.php?tID=4&amp;oID=<?php echo $locID;?>" target="_blank" rel="nofollow" class="eventMain"><?php echo $hc_lang_event['ClickToVisit'];?></a>
<?php	}//end if
		if($locID > 0){
			echo "<br /><br /><a href=\"" . CalRoot . "/index.php?lID=" . $locID . "\" class=\"eventMain\">" . $hc_lang_event['BrowseLocation'] . "</a>";
		}//end if
		if($locDesc != '' && $locDesc != '<br />'){
			echo "<br /><br />" . $locDesc;
		}//end if
		
		if(($contactURL != '' && $contactURL != 'http://') || ($contactName != '') 
			|| ($contactEmail != '') 
			|| ($contactPhone != '')){?>
			<br />
			<div class="eventDetailHeader"><?php echo $hc_lang_event['Contact'];?></div>
	<?php	if($contactName != '' || $contactEmail){
				if($contactName != ''){echo $contactName . "<br />";}
				if($contactEmail != ''){	?>
					<?php echo $hc_lang_event['Email'];?> <script language="JavaScript" type="text/JavaScript">
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
				echo $hc_lang_event['Phone'] . " " . $contactPhone . "<br />";
			}//end if
			
			if(($contactURL != '' && $contactURL != 'http://')){	?>
				<?php echo $hc_lang_event['Website'];?> <a href="<?php echo CalRoot . "/link/index.php?tID=1&amp;oID=" . $eID;?>" class="eventMain" rel="nofollow" target="_blank"><?php echo $hc_lang_event['ClickToVisit'];?></a>
	<?php	}//end if
		}//end if	?>
		</div></div>
		<div id="eventDetailTools">
	<?php
		if($allowRegistration == 1){	?>
		<div class="eventDetailReg">
	<?php
			$result = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
			$regUsed = mysql_result($result,0,0);
			$regAvailable = $maxRegistration;
			$regLimit = $hc_lang_event['Unlimited'];
			$fillWidth = 195;	
			$regLimit = $regAvailable;
			$regText = $hc_lang_event['Register'];
			if($regAvailable <= $regUsed && $regAvailable > 0){	
				$regText = $hc_lang_event['Overflow'];?>
				<div align="center"><b><?php echo $hc_lang_event['Attendance'];?></b></div>
				<div style="line-height: 15px;"><img src="<?php echo CalRoot;?>/images/meter/overflow.gif" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" />&nbsp;</div>
		<?php	echo "<b>" . $regUsed . "</b> " . $hc_lang_event['Of'] . " <b>" . $regLimit . "</b> " . $hc_lang_event['SpacesTaken'];
			} else {
				if($regAvailable > 0){
					$regWidth = 0;
					if($regUsed > 0){
						$regWidth = ($regUsed / $regAvailable) * $fillWidth;
						$fillWidth = $fillWidth - $regWidth;
					}//end if	?>
					<div style="line-height: 15px;"><img src="<?php echo CalRoot;?>/images/meter/full.gif" width="<?php echo $regWidth;?>" height="7" alt="" border="0" style="line-height: 25px;border-left: .5px solid #000000;" /><img src="<?php echo CalRoot;?>/images/meter/empty.gif" width="<?php echo $fillWidth;?>" height="7" alt="" border="0" style="border-right: .5px solid #000000;" />&nbsp;</div>
			<?php	echo "<b>" . $regUsed . "</b> " . $hc_lang_event['Of'] . " <b>" . $regLimit . "</b> " . $hc_lang_event['SpacesTaken'];
				} elseif($regAvailable == 0) {	?>
					<div align="center"><b><?php $hc_lang_event['NoLimit'];?></b></div>
			<?php	echo "<b>" . $regUsed . "</b>" . " " . $hc_lang_event['PeopleRegistered'] . "</b>";?>
		<?php	}//end if
			}//end if	?>
			<div align="center"><a href="<?php echo CalRoot;?>/index.php?com=register&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconRegister.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/index.php?com=register&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><?php echo $regText;?></a></div>
		</div>
	<?php
		}//end if
		$liveClipLink = CalRoot . "/index.php?com=detail&#38;eID=" . $eID . "&#38;year=" . $year . "&#38;month=" . $month;	?>
		
		<div class="eventDetailToolbox">
			<b><?php echo $hc_lang_event['ShareEvent'];?></b><br />
			<a target="_blank" class="eventShare" href="http://del.icio.us/post?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/delicious.png" alt="Del.icio.us" title="Del.icio.us" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://digg.com/submit?phase=2&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/digg.png" alt="Digg" title="Digg" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://www.facebook.com/share.php?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/facebook.png" alt="Facebook" title="Facebook" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/google.png" alt="Google" title="Google" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://reddit.com/submit?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/reddit.png" alt="Reddit" title="Reddit" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://www.stumbleupon.com/submit?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/stumbleit.png" alt="Stumble Upon" title="Stumble Upon" style="vertical-align:middle;" /></a>
			<a target="_blank" class="eventShare" href="http://twitter.com/home?status=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/twitter.png" alt="stumble upon" title="Twitter" style="vertical-align:middle;" /></a>
			<a class="eventShare" href="javascript:;" onclick="bookIt();"><img src="<?php echo CalRoot;?>/images/icons/iconFavorite.png" alt="Bookmark/Favorite" title="Bookmark/Favorite" style="vertical-align:middle;" /></a>
			
			<a class="eventMain" href="javascript:;" onclick="togThis('eventDetailShareMore', 'eventShareLink');" id="eventShareLink"><?php echo $hc_lang_event['More'];?></a><br />
			<div id="eventDetailShareMore" style="display:none;">
				<a target="_blank" class="eventShare" href="http://www.backflip.com/add_page_pop.ihtml?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/backflip.png" alt="backflip" title="backflip" /></a>
				<a target="_blank" class="eventShare" href="http://www.bluedot.us/Authoring.aspx?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/bluedot.png" alt="diigo" title="diigo" /></a>
				<a target="_blank" class="eventShare" href="http://www.buddymarks.com/add_bookmark.php?bookmark_title=<?php echo urlencode($eventTitle);?>&amp;bookmark_url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/buddymarks.png" alt="buddymarks" title="buddymarks" /></a>
				<a target="_blank" class="eventShare" href="http://www.citeulike.org/posturl?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/citeulike.png" alt="citeulike" title="citeulike" /></a>
				<a target="_blank" class="eventShare" href="http://co.mments.com/track?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/co.mments.png" alt="co.mments" title="co.mments" /></a>
				<a target="_blank" class="eventShare" href="http://www.connotea.org/add?continue=return&amp;uri=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/connotea.png" alt="connotea" title="connotea" /></a>
				<a target="_blank" class="eventShare" href="http://www.diigo.com/post?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/diigo.png" alt="diigo" title="diigo" /></a>
				<a target="_blank" class="eventShare" href="http://www.dotnetkicks.com/kick/?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/dotnetkicks.png" alt="dotnetkicks" title="dotnetkicks" /></a>
				<a target="_blank" class="eventShare" href="http://faves.com/Authoring.aspx?t=<?php echo urlencode($eventTitle);?>&amp;u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/faves.png" alt="faves" title="faves" /></a>
				<a target="_blank" class="eventShare" href="http://www.feedmarker.com/admin.php?do=bookmarklet_mark&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/feedmarker.png" alt="feedmarker" title="feedmarker" /></a>
				<a target="_blank" class="eventShare" href="http://www.feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;name=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;version=0.7"><img src="<?php echo CalRoot;?>/images/share/feedmelinks.png" alt="feedmelinks" title="feedmelinks" /></a>
				<a target="_blank" class="eventShare" href="http://extension.fleck.com/?v=b.0.804&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/fleck.png" alt="fleck" title="fleck" /></a>
				<a target="_blank" class="eventShare" href="http://www.furl.net/storeIt.jsp?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/furl.png" alt="furl" title="furl" /></a>
				<a target="_blank" class="eventShare" href="http://www.gravee.com/account/bookmarkpop?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/gravee.png" alt="gravee" title="gravee" /></a>
				<a target="_blank" class="eventShare" href="http://www.gwar.pl/DodajGwar.html?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/gwar.png" alt="gwar" title="gwar" /></a>
				<a target="_blank" class="eventShare" href="http://www.hemidemi.com/user_bookmark/new?title<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/hemidemi.png" alt="hemidemi" title="hemidemi" /></a>	
				<a target="_blank" class="eventShare" href="http://lister.lilisto.com/?t=<?php echo urlencode($eventTitle);?>&amp;l=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/lilisto.png" alt="lilisto" title="lilisto" /></a>
				<a target="_blank" class="eventShare" href="http://www.linkagogo.com/go/AddNoPopup?title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/linkagogo.png" alt="linkagogo" title="linkagogo" /></a>
				<a target="_blank" class="eventShare" href="http://www.linkroll.com/index.php?action=insertLink&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/linkroll.png" alt="linkroll" title="linkroll" /></a>
				<a target="_blank" class="eventShare" href="http://www.linkter.hu/index.php?action=suggest_link&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/linkter.png" alt="linkter" title="linkter" /></a>
				<a target="_blank" class="eventShare" href="http://ma.gnolia.com/bookmarklet/add?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/magnolia.png" alt="magnolia" title="magnolia" /></a>
				<a target="_blank" class="eventShare" href="http://www.mister-wong.com/index.php?action=addurl&amp;bm_url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;bm_description<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/mister-wong.png" alt="mister-wong" title="mister-wong" /></a>
				<a target="_blank" class="eventShare" href="http://myshare.url.com.tw/index.php?func=newurl&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;desc<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/myshare.png" alt="myshare" title="myshare" /></a>
				<a target="_blank" class="eventShare" href="http://www.netscape.com/submit/?U=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;T<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/netscape.png" alt="netscape" title="netscape" /></a>
				<a target="_blank" class="eventShare" href="http://netvouz.com/action/submitBookmark?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>&amp;popup=no"><img src="<?php echo CalRoot;?>/images/share/netvouz.png" alt="netvouz" title="netvouz" /></a>
				<a target="_blank" class="eventShare" href="http://www.newsvine.com/_tools/seed&amp;save?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;h=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/newsvine.png" alt="Bookmark this at newsvine" title="Bookmark this at newsvine" /></a>
				<a target="_blank" class="eventShare" href="http://www.ppnow.net/submit.php?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/ppnow.png" alt="ppnow" title="ppnow" /></a>
				<a target="_blank" class="eventShare" href="http://www.rojo.com/add-subscription/?resource=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/rojo.png" alt="rojo" title="rojo" /></a>
				<a target="_blank" class="eventShare" href="http://www.simpy.com/simpy/LinkAdd.do?title=<?php echo urlencode($eventTitle);?>&amp;href=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;v=6&amp;border="><img src="<?php echo CalRoot;?>/images/share/simpy.png" alt="simpy" title="simpy" /></a>
				<a target="_blank" class="eventShare" href="http://www.smarking.com/editbookmark/?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;description<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/smarking.png" alt="smarking" title="smarking" /></a>
				<a target="_blank" class="eventShare" href="http://www.sphere.com/search?q=sphereit:<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/sphere.png" alt="sphere" title="sphere" /></a>
				<a target="_blank" class="eventShare" href="http://www.spurl.net/spurl.php?v=3&amp;title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/spurl.png" alt="spurl" title="spurl" /></a>
				<a target="_blank" class="eventShare" href="http://www.squidoo.com/lensmaster/bookmark?<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/squidoo.png" alt="squidoo" title="squidoo" /></a>
				<a target="_blank" class="eventShare" href="http://www.tagtooga.com/tapp/db.exe?c=jsEntryForm&amp;b=fx&amp;title=<?php echo urlencode($eventTitle);?>&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/tagtooga.png" alt="tagtooga" title="tagtooga" /></a>
				<a target="_blank" class="eventShare" href="http://www.unalog.com/my/stack/link?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/unalog.png" alt="unalog" title="unalog" /></a>
				<a target="_blank" class="eventShare" href="http://www.webride.org/discuss/split.php?uri=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/webride.png" alt="webride" title="webride" /></a>
				<a target="_blank" class="eventShare" href="http://www.wists.com/r.php?r=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/wists.png" alt="wists" title="wists" /></a>
				<a target="_blank" class="eventShare" href="http://www.wykop.pl/dodaj?url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>"><img src="<?php echo CalRoot;?>/images/share/wykop.png" alt="wykop" title="wykop" /></a>
				<a target="_blank" class="eventShare" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;t=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/yahoo.png" alt="yahoo myweb" title="yahoo myweb" /></a>
				<a target="_blank" class="eventShare" href="http://tag.zurpy.com/?box=1&amp;url=<?php echo urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);?>&amp;title=<?php echo urlencode($eventTitle);?>"><img src="<?php echo CalRoot;?>/images/share/zurpy.png" alt="zurpy" title="zurpy" /></a>
			</div>
		
			<a href="<?php echo CalRoot;?>/index.php?com=send&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconEmail.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="<?php echo CalRoot;?>/index.php?com=send&amp;eID=<?php echo $eID;?>" class="eventDetailLink"><?php echo $hc_lang_event['EmailToFriend'];?></a>
		
			<br /><br />
			<b><?php echo $hc_lang_event['SaveToCalendar'];?></b><br />
			<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=1" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/share/google.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=1" target="_blank" class="eventDetailLink">Google <?php echo $hc_lang_event['Calendar'];?></a><br />
			<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=2" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconY.gif" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=2" target="_blank" class="eventDetailLink">Yahoo! <?php echo $hc_lang_event['Calendar'];?></a><br />
			<img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconiCal.png" width="16" height="16" alt="" border="0" />&nbsp;iCalendar (<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=3" class="eventDetailLink"><?php echo $hc_lang_event['Download'];?></a>) (<a href="<?php echo "webcal://" . substr(CalRoot, 7) . "/link/SaveEvent.php?eID=" . $eID . "&amp;cID=3";?>" class="eventDetailLink"><?php echo $hc_lang_event['Subscribe'];?></a>)<br />
			<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=4" class="eventDetailLink"><img class="eventDetailLink" src="<?php echo CalRoot;?>/images/icons/iconDownload.png" width="16" height="16" alt="" border="0" /></a>&nbsp;vCalendar (<a href="<?php echo CalRoot;?>/link/SaveEvent.php?eID=<?php echo $eID;?>&amp;cID=4" class="eventDetailLink"><?php echo $hc_lang_event['Download'];?></a>)<br />
			<div class="ControlContainer" id="hc_LiveClip"></div>
			&nbsp;Live&nbsp;Clipboard&nbsp;(<a href="http://www.liveclipboard.org/" class="eventDetailLink" target="_blank"><b>?</b></a>)
			
	        
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/liveclipboard/script.js"></script>
			<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/liveclipboard/hCal.js"></script>
			<script language="JavaScript" type="text/javascript">
			//<!--
			function fakeIt(){return true;}
	    	function MicroFormatObjectBinding(displayDiv, microFormatObject) {
	            var webClip;
	            var self = this;
	            this.updateDisplayAndWebClipData = function() {
	                webClip = new LiveClipboardContent();
	                webClip.source = "<?php echo $liveClipLink;?>";
	                webClip.data.formats[0] = new DataFormat();
	                webClip.data.formats[0].type = microFormatObject.formatType;
	                webClip.data.formats[0].contentType = "application/xhtml+xml";
	                webClip.data.formats[0].items = new Array(1);
	                webClip.data.formats[0].items[0] = new DataItem();
	                webClip.data.formats[0].items[0].xmlData = microFormatObject.xmlData;
	            }//end updateDisplayAndWebClipData()
	            this.onCopy = function(){return webClip;}
	            self.updateDisplayAndWebClipData();
	        }//end MicroFormatObjectBinding()
		<?php
			$starttimepart = split(":", $eventStartTime);
			$startdatepart = split("-", $eventDate);
			$endtimepart = split(":", $eventEndTime);
			if($eventStartTime != ''){
				$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				if($eventEndTime != ''){
					if($eventStartTime > $eventEndTime){
						$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2] + 1, $startdatepart[0]));
					} else {
						$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					}//end if
				} else {
					$endDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				}//end if
			} else {
				$startDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
				$endDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
			}//end if	?>
			
			var calObj = new HCal("<?php echo $liveClipLink;?>", "<?php echo strip_tags(cleanXMLChars($eventTitle));?>", "<?php echo cleanXMLChars(cleanBreaks($eventDesc,1));?>", "<?php echo $startDate;?>", "<?php echo $endDate;?>", "<?php echo stampToDate($eventDate, $hc_dateFormat) . ", " . $eventStartTime;?>", "<?php echo cleanXMLChars(strip_tags(str_replace("<br />",", ",$locTag)));?>", "", "", "<?php echo date("Ymd\THis");?>", "<?php echo $eventEndTime;?>");   
			var hc_calendarBinding = new MicroFormatObjectBinding(document.getElementById("hc_LiveClip"), calObj);
			var hc_clipBoardControl = new WebClip(document.getElementById("hc_LiveClip"), hc_calendarBinding.onCopy, fakeIt, fakeIt, fakeIt);
			//-->
			</script>
		</div>
	<?php
		if($seriesID != '' && $eventDate >= date("Y-m-d")){
			echo "<br /><b>" . $hc_lang_event['OtherDates'] . "</b><br />";
			$result = doQuery("SELECT PkID, StartDate FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . $seriesID . "' AND IsActive = 1 AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate LIMIT 5");
			if(hasRows($result)){
				while($row = mysql_fetch_row($result)){
					if($eID == $row[0]){echo "**";}//end if
					echo "<a href=\"" . CalRoot . "/index.php?com=detail&amp;eID=" . $row[0] . "\" rel=\"nofollow\" class=\"eventDetailDates\">" . stampToDate($row[1], $hc_dateFormat) . "</a><br />";
				}//end while
			}//end if
			echo "<div style=\"text-align:right;padding-right:15px;\"><a href=\"" . CalRoot . "/index.php?com=serieslist&amp;sID=" . $seriesID . "\" rel=\"nofollow\" class=\"eventMain\">" . $hc_lang_event['AllDates'] . "</a></div>";
		}//end if	?>
		</div>
		</div>
<?php 	
		if(isset($hc_googleKey)){	?>
			<br /><br />
			<div style="clear:both;">&nbsp;</div>
			<div id="hc_Gmap"></div>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
			var map;
			var marker;
			
			function createMarker(point, msg){
				marker = new GMarker(point);
				GEvent.addListener(marker, "click", function() {
				marker.openInfoWindowHtml(msg);
				});
				return marker;
			}//end createMarker()
			
			function buildGmap(hc_Lat, hc_Lon, hc_gMsg){
				if (GBrowserIsCompatible()) {
					map = new GMap2(document.getElementById("hc_Gmap"));
					map.addControl(new GSmallMapControl());
					map.addControl(new GMapTypeControl());
					map.setCenter(new GLatLng(hc_Lat, hc_Lon), <?php echo $hc_mapZoom;?>);
					var point = new GLatLng(hc_Lat, hc_Lon);
					map.addOverlay(createMarker(point, hc_gMsg));
					marker.openInfoWindowHtml(hc_gMsg);
				}//end if
			}//end buildGmap()
			//-->
			</script>
<?php	}//end if
	} else {
		echo "<br />";
		echo $hc_lang_event['NoEventDetail'];?>
		<ol>
			<li style="line-height: 30px;"><?php echo $hc_lang_event['NoEvent1'];?> <span class="miniCalEvents" style="padding:3px;">03</span></li>
			<li style="line-height: 30px;"><?php echo $hc_lang_event['NoEvent2'];?> <span class="miniCalNav" style="padding:3px;">&lt;</span> <span class="miniCalNav" style="padding:3px;">&gt;</span></li>
			<li style="line-height: 30px;"><a href="<?php echo CalRoot;?>/" class="eventMain"><?php echo $hc_lang_event['NoEvent3'];?></a></li>
		</ol>
<?php
	}//end if	?>