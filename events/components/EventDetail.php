<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/event.php');
	if(!file_exists(realpath('cache/censored.php'))){
		rebuildCache(2);
	}//end if
	include('cache/censored.php');
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
	
	if(hasRows($result)){
		if(!in_array($eID,$_SESSION[$hc_cfg00 . 'hc_trail'])){
			array_push($_SESSION[$hc_cfg00 . 'hc_trail'], $eID);
			doQuery("UPDATE " . HC_TblPrefix . "events SET Views = Views + 1 WHERE PkID = " . $eID);
		}//end if
		
		$hourOffset = date("G") + ($hc_cfg35);
		$hrFormat = ($hc_timeInput == 23) ? "H" : "h";
		$minHr = ($hc_timeInput == 23) ? 0 : 1;
		
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
		$locCountry = cOut(mysql_result($result,0,37));
		$locSaver = "";
		$locDesc = "";
		$locPhone = "";
		$locURL = "";
		$locEmail = "";
		$locLat = "";
		$locLon = "";
		$locLink = "";	
		
		if($locID > 0){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = " . cIn($locID));
			if(hasRows($result)){
				$locSaver = "&amp;lID=" . $locID;
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
		}//end if?>
		<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/ajxOutput.js"></script>
		<script language="JavaScript" type="text/javascript">
		//<!--
			document.title = "<?php echo $eventTitle;?> :: " + document.title;
			
			function chkFrm(){
				dirty = 0;
				warn = '<?php echo $hc_lang_event['Valid49'];?>';
		
		<?php	if(in_array(6, $hc_captchas)){	?>
					if(document.hc_cmnts.proof.value == ''){
						dirty = 1;
						warn = warn + '\n<?php echo $hc_lang_event['Valid03'];?>';
					}//end if
		<?php 	}//end if?>

				if(document.hc_cmnts.cmntText.value == ''){
					dirty = 1;
					warn = warn + '\n<?php echo $hc_lang_event['Valid50'];?>';
				}//end if	
		
				if(dirty > 0){
					alert(warn + '\n\n<?php echo $hc_lang_event['Valid46'];?>');
					return false;
				} else {
					return true;
				}//end if
			}//end chkFrm()
			
			function testCAPTCHA(){
				if(document.hc_cmnts.proof.value != ''){
					var qStr = 'CaptchaCheck.php?capEntered=' + document.hc_cmnts.proof.value;
					ajxOutput(qStr, 'capChk', '<?php echo CalRoot;?>');
				} else {
					alert('<?php echo $hc_lang_event['Valid48'];?>');
				}//end if
			}//end testCAPTCHA()
			
			function togComment(cID){
				document.getElementById('commentLink_' + cID).style.display = 'none';
				document.getElementById('commentU_' + cID).className = 'recomnds';
				document.getElementById('commentD_' + cID).className = 'recomnds';
				document.getElementById('comment_' + cID).className = 'comment';
				document.getElementById('commentBG_' + cID).className = 'commentFrame';
				document.getElementById('commentTools_' + cID).className = 'commentTools';
				document.getElementById('commentDate_' + cID).className = 'commentDate';
			}//end togComment()';
			
			function togThis(doTog, doLink){
				if(document.getElementById(doTog).style.display == 'none'){
					document.getElementById(doTog).style.display = 'block';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_event['Less'];?>';
				} else {
					document.getElementById(doTog).style.display = 'none';
					document.getElementById(doLink).innerHTML = '<?php echo $hc_lang_event['More'];?>';
				}//end if
			}//end togThis()
		//-->
		</script>
<?php
		echo '<div class="vevent">';
		echo '<div id="eventDetailTitle" class="summary">' . $eventTitle . '</div>';
		echo ($eventDesc != '') ? '<div id="eventDetailDesc" class="description">' . $eventDesc . '</div>' : '';
		echo '<div id="eventDetailInfo">';
		
		$datepart = explode("-", $eventDate);
		$year = $datepart[0];
		$month = $datepart[1];
		
		echo '<div class="eventDetailDate';
		echo ($eventDate < date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")))) ? 'Past' : '';
		echo '">';
		echo stampToDate($eventDate, $hc_cfg14) . '</div>';
	
		$microStartTime = "";
		$datepart = explode("-", $eventDate);
		if($eventStartTime != ''){
			$timepart = explode(":", $eventStartTime);
			$microStart = date("c",mktime($timepart[0],$timepart[1],$timepart[2],$datepart[1],$datepart[2],$datepart[0]));
			$startTime = '<abbr class="dtstart" title="' . $microStart . '">' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2])) . '</abbr>';
		}//end if
		
		if($eventEndTime != ''){
			$timepart = explode(":", $eventEndTime);
			$microEndTime = $timepart[0] . ':' . $timepart[1] . ':' . $timepart[2] . (date("") - $hourOffset);
			$microEnd = date("c",mktime($timepart[0],$timepart[1],$timepart[2],$datepart[1],$datepart[2],$datepart[0]));
			if($microStart > $microEnd){
				$microEnd = date("c",mktime($timepart[0],$timepart[1],$timepart[2],$datepart[1],$datepart[2] + 1,$datepart[0]));
			}//end if
			$endTime = '<abbr class="dtend" title="' . $microEnd . '">' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2])) . '</abbr>';
		}//end if
		
		if($eventTBD == 0){
			if(strlen($eventEndTime) > 0){
				$eventTime = $startTime . " - " . $endTime;
			} else {
				$eventTime = $hc_lang_event['StartsAt'] . " " . $startTime;
			}//end if
		} elseif($eventTBD == 1){
			$eventTime = '<abbr class="dtstart" title="' . date("c",mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0])) . '">' . $hc_lang_event['AllDay'] . '</abbr>';
		} elseif($eventTBD == 2){
			$eventTime = '<abbr class="dtstart" title="' . date("c",mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0])) . '">' . $hc_lang_event['TimeTBA'] . '</abbr>';
		}//end if
		
		echo '<div class="eventDetailTime">' . $eventTime . '</div>';
		echo ($cost != '') ? "<br /><div class=\"eventDetailHeader\">" . $hc_lang_event['Cost'] . "</div>" . $cost . "<br />" : '';
		
		$resultC = doQuery("SELECT c.PkID, c.CategoryName
							FROM " . HC_TblPrefix . "eventcategories ec
								LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
							WHERE c.IsActive = 1 AND ec.EventID = '" . cIn($eID) . "'
							ORDER BY c.CategoryName");
		
		if(hasRows($resultC)){
			echo '<br /><div class="eventDetailHeader">' . $hc_lang_event['Categories'] . '</div>';
			echo '<ul class="category">';
			while($row = mysql_fetch_row($resultC)){
				echo '<li class="category"><a href="' . CalRoot . '/index.php?com=searchresult&amp;t=' . $row[0] . '" rel="tag" class="eventMain">' . $row[1] . '</a></li>';
			}//end while
			echo '</ul>';
		}//end if
		
		echo '<br />';
		echo '<div class="vcard">';
		echo '<div class="eventDetailHeader">' . $hc_lang_event['Location'] . '&nbsp;';
		echo ($locAddress != '' && $locCity != '' && $locState != '' && $locZip != '') ? '<a href="' . CalRoot . '/link/index.php?tID=2&amp;oID=' . $eID . $locSaver . '" target="_blank" class="eventDetailLink"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconMap.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="' . CalRoot . '/link/index.php?tID=2&amp;oID=' . $eID . $locSaver . '" target="_blank" class="eventDetailLink">' . $hc_lang_event['Map'] . '</a>&nbsp;&nbsp;' : '';
		echo ($locZip != '') ? '<a href="' . CalRoot . '/link/index.php?tID=3&amp;oID=' . $eID . $locSaver . '" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconWeather.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="' . CalRoot . '/link/index.php?tID=3&amp;oID=' . $eID . $locSaver . '" class="eventDetailLink" target="_blank">' . $hc_lang_event['Weather'] . '</a>&nbsp;&nbsp;' : '';
		echo ($locID > 0) ? '<a href="' . CalRoot . '/rssL.php?lID=' . $locID . '" target="_blank"><img src="' . CalRoot . '/images/rss/feedIcon.gif" width="16" height="16" alt="" style="vertical-align:middle;" /></a>' : '';
		echo '</div>';
		
		//echo '<span class="location">';
		if($locName != ''){
			echo '<div class="fn org">' . $locName . '</div>';
			$locTag .= '<b>' . $locName . '</b><br />';
		}//end if
		if($locAddress != ''){
			echo '<div class="street-address">' . $locAddress . '</div>';
			$locTag .= $locAddress . '<br />';
		}//end if
		if($locAddress2 != ''){
				echo $locAddress2 . '<br />';
				$locTag .= $locAddress2 . '<br />';
			}//end if
		if($locCity != ''){
			echo '<span class="locality">' . $locCity . '</span>, ';
			$locTag .= $locCity . ", ";
		}//end if
		if($locState != ''){
			echo '<span class="region">' . $locState . '</span> ';
			$locTag .= $locState . ' ';
		}//end if
		if($locZip != ''){
			echo '<span class="postal-code">' . $locZip . '</span> ';
			$locTag .= $locZip;
		}//end if
		if($locCountry != ''){
			echo '<span class="country-name">' . $locCountry . '</span><br /> ';
			$locTag .= $locCountry . " ";
		}//end if
		if($locEmail != ''){	
			echo '<br />' . $hc_lang_event['Email'];
			cleanEmailLink($locEmail);
		}//end if
		if($locPhone != ''){
			echo '<br />' . $hc_lang_event['Phone'] . '&nbsp;' . $locPhone;
			echo '<div style="display:none;" class="tel">' . $locPhone . '</div>';
		}//end if
		//echo '</span>';
		
		echo ($locURL != '' && $locURL != 'http://') ? '<br />' . $hc_lang_event['Website'] . ' <a href="' . CalRoot . '/link/index.php?tID=4&amp;oID=' . $locID . '" target="_blank" rel="nofollow" class="eventMain">' . $hc_lang_event['ClickToVisit'] . '</a>' : '';
		echo ($locID > 0) ? '<br /><br /><a href="' . CalRoot . '/index.php?lID=' . $locID . '" class="eventMain">' . $hc_lang_event['BrowseLocation'] . '</a><br />' : '';
		echo ($locDesc != '' && $locDesc != '<br />') ? '<br />' . $locDesc : '';
		
		if(($contactURL != '' && $contactURL != 'http://') || ($contactName != '') || ($contactEmail != '') || ($contactPhone != '')){
			echo '<br />';
			echo '<div class="eventDetailHeader">' . $hc_lang_event['Contact'] . '</div>';
			
			if($contactName != '' || $contactEmail){
				if($contactName != ''){echo $contactName . "<br />";}
				if($contactEmail != ''){
					echo $hc_lang_event['Email'];
					cleanEmailLink($contactEmail);
				}//end if
			}//end if
			
			echo ($contactPhone != '') ? $hc_lang_event['Phone'] . ' ' . $contactPhone . '<br />' : '';
			echo (($contactURL != '' && $contactURL != 'http://')) ? $hc_lang_event['Website'] . ' <a href="' . CalRoot . '/link/index.php?tID=1&amp;oID=' . $eID . '" class="eventMain" rel="nofollow" target="_blank">' . $hc_lang_event['ClickToVisit'] . '</a>' : '';
		}//end if
		echo '</div></div>';
		
		echo '<div id="eventDetailTools">';
		if($allowRegistration == 1){
			echo '<div class="eventDetailReg">';
			
			$result = doQuery("Select count(*) FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $eID);
			$regUsed = mysql_result($result,0,0);
			$regAvailable = $maxRegistration;
			$regLimit = $hc_lang_event['Unlimited'];
			$fillWidth = 195;	
			$regLimit = $regAvailable;
			$regText = $hc_lang_event['Register'];
			if($regAvailable <= $regUsed && $regAvailable > 0){	
				$regText = $hc_lang_event['Overflow'];	
				echo '<div align="center"><b>' . $hc_lang_event['Attendance'] . '</b></div>';
				echo '<div style="line-height: 15px;"><img src="' . CalRoot . '/images/meter/overflow.gif" width="' . $fillWidth . '" height="7" alt="" border="0" />&nbsp;</div>';
				echo '<b>' . $regUsed . '</b> ' . $hc_lang_event['Of'] . ' <b>' . $regLimit . '</b> ' . $hc_lang_event['SpacesTaken'];
			} else {
				if($regAvailable > 0){
					$regWidth = 0;
					if($regUsed > 0){
						$regWidth = ($regUsed / $regAvailable) * $fillWidth;
						$fillWidth = $fillWidth - $regWidth;
					}//end if
					echo '<div style="line-height: 15px;"><img src="'  . CalRoot . '/images/meter/full.gif" width="' . $regWidth . '" height="7" alt="" border="0" style="line-height: 25px;border-left: .5px solid #000000;" /><img src="' . CalRoot . '/images/meter/empty.gif" width="' . $fillWidth . '" height="7" alt="" border="0" style="border-right: .5px solid #000000;" />&nbsp;</div>';
					echo "<b>" . $regUsed . "</b> " . $hc_lang_event['Of'] . " <b>" . $regLimit . "</b> " . $hc_lang_event['SpacesTaken'];
				} elseif($regAvailable == 0) {
					echo '<div align="center"><b>' . $hc_lang_event['NoLimit'] . '</b></div>';
					echo '<b>' . $regUsed . '</b>' . ' ' . $hc_lang_event['PeopleRegistered'] . '</b>';
				}//end if
			}//end if
			echo '<div align="center"><a href="' . CalRoot . '/index.php?com=register&amp;eID=' . $eID . '" class="eventDetailLink"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconRegister.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="' . CalRoot . '/index.php?com=register&amp;eID=' . $eID . '" class="eventDetailLink">' . $regText . '</a></div>';
			echo '</div>';
	
		}//end if
				
		$link = urlencode(CalRoot . '/index.php?com=detail&eID=' . $eID);
		$title = urlencode($eventTitle);
		
		echo '<div class="eventDetailToolbox">';
		echo '<b>' . $hc_lang_event['ShareEvent'] . '</b><br />';
		echo '<a target="_blank" class="eventShare" href="http://del.icio.us/post?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/delicious.png" alt="Del.icio.us" title="Del.icio.us" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://digg.com/submit?phase=2&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/digg.png" alt="Digg" title="Digg" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.facebook.com/share.php?u=' . $link . '&amp;t=' . $title . '"><img src="' . CalRoot . '/images/share/facebook.png" alt="Facebook" title="Facebook" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/google.png" alt="Google Bookmarks" title="Google Bookmarks" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=' . $link . '&amp;title=' . $title . '&amp;top=1"><img src="' . CalRoot . '/images/share/live.png" alt="Live" title="Live" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.myspace.com/Modules/PostTo/Pages/?u=' . $link . '&amp;t=' . $title . '"><img src="' . CalRoot . '/images/share/myspace.png" alt="MySpace" title="MySpace" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.propeller.com/submit/?url=' . $link . '"><img src="' . CalRoot . '/images/share/propeller.png" alt="propeller" title="propeller" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://reddit.com/submit?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/reddit.png" alt="Reddit" title="Reddit" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.stumbleupon.com/submit?url=' . $link . '"><img src="' . CalRoot . '/images/share/stumbleit.png" alt="Stumble Upon" title="Stumble Upon" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://technorati.com/faves?add=' . $link . '"><img src="' . CalRoot . '/images/share/technorati.png" alt="Technorati" title="Technorati" style="vertical-align:middle;" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://twitter.com/home?status=' . $link . '"><img src="' . CalRoot . '/images/share/twitter.png" alt="Twitter" title="Twitter" style="vertical-align:middle;" /></a>';
		echo '<a class="eventMain" href="javascript:;" onclick="togThis(\'eventDetailShareMore\', \'eventShareLink\');" id="eventShareLink">' . $hc_lang_event['More'] . '</a><br />';
		
		echo '<div id="eventDetailShareMore" style="display:none;">';
		echo '<a target="_blank" class="eventShare" href="http://www.backflip.com/add_page_pop.ihtml?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/backflip.png" alt="backflip" title="backflip" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://barrapunto.com/submit.pl?subj=' . $title . '&amp;story=' . $link . '"><img src="' . CalRoot . '/images/share/barrapunto.png" alt="barrapunto" title="barrapunto" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://bitacoras.com/anotaciones/' . $link . '"><img src="' . CalRoot . '/images/share/bitacoras.png" alt="bitacoras" title="bitacoras" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=' . $link . '&amp;Title=' . $title . '"><img src="' . CalRoot . '/images/share/blinklist.png" alt="blinklist" title="blinklist" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.buddymarks.com/add_bookmark.php?bookmark_title=' . $title . '&amp;bookmark_url=' . $link . '"><img src="' . CalRoot . '/images/share/buddymarks.png" alt="buddymarks" title="buddymarks" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.connotea.org/addpopup?continue=confirm&amp;uri=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/connotea.png" alt="connotea" title="connotea" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.designfloat.com/submit.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/designfloat.png" alt="designfloat" title="designfloat" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.diigo.com/post?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/diigo.png" alt="diigo" title="diigo" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.dotnetkicks.com/kick/?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/dotnetkicks.png" alt="dotnetkicks" title="dotnetkicks" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.dzone.com/links/add.html?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/dzone.png" alt="dzone" title="dzone" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.ekudos.nl/artikel/nieuw?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/ekudos.png" alt="ekudos" title="ekudos" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://faves.com/Authoring.aspx?t=' . $title . '&amp;u=' . $link . '"><img src="' . CalRoot . '/images/share/faves.png" alt="faves" title="faves" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.feedmelinks.com/categorize?from=toolbar&amp;op=submit&amp;name=' . $title . '&amp;url=' . $link . '&amp;version=0.7"><img src="' . CalRoot . '/images/share/feedmelinks.png" alt="feedmelinks" title="feedmelinks" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://extension.fleck.com/?v=b.0.804&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/fleck.png" alt="fleck" title="fleck" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.furl.net/storeIt.jsp?u=' . $link . '&amp;t=' . $title . '"><img src="' . CalRoot . '/images/share/furl.png" alt="furl" title="furl" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://globalgrind.com/submission/submit.aspx?url=' . $link . '&amp;type=Article&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/globalgrind.png" alt="" title="" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.gwar.pl/DodajGwar.html?u=' . $link . '"><img src="' . CalRoot . '/images/share/gwar.png" alt="gwar" title="gwar" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.hemidemi.com/user_bookmark/new?title=' . $title . '&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/hemidemi.png" alt="hemidemi" title="hemidemi" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://identi.ca/notice/new?status_textarea=' . $link . '"><img src="' . CalRoot . '/images/share/identica.png" alt="identi.ca" title="identi.ca" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.kirtsy.com/submit.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/kirtsy.png" alt="kirtsy" title="kirtsy" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://laaik.it/NewStoryCompact.aspx?uri=' . $link . '&amp;headline=' . $title . '"><img src="' . CalRoot . '/images/share/laaikit.png" alt="laaik.it" title="laaik.it" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.leonaut.com/submit.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/leonaut.png" alt="leonaut" title="leonaut" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://lister.lilisto.com/?t=' . $title . '&amp;l=' . $link . '"><img src="' . CalRoot . '/images/share/lilisto.png" alt="lilisto" title="lilisto" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.linkagogo.com/go/AddNoPopup?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/linkagogo.png" alt="linkagogo" title="linkagogo" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://linkarena.com/bookmarks/addlink/?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/linkarena.png" alt="linkarena" title="linkarena" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.linkroll.com/index.php?action=insertLink&amp;url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/linkroll.png" alt="linkroll" title="linkroll" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.linkter.hu/index.php?action=suggest_link&amp;url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/linkter.png" alt="linkter.hu" title="linkter.hu" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://meneame.net/submit.php?url=' . $link . '"><img src="' . CalRoot . '/images/share/meneame.png" alt="meneame" title="meneame" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.mister-wong.com/addurl/?bm_url=' . $link . '&amp;bm_description=' . $title . '"><img src="' . CalRoot . '/images/share/misterwong.png" alt="mister-wong" title="mister-wong" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.mixx.com/submit?page_url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/mixx.png" alt="mixx" title="mixx" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.muti.co.za/submit?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/muti.png" alt="muti.co.za" title="muti.co.za" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://myshare.url.com.tw/index.php?func=newurl&amp;url=' . $link . '&amp;desc' . $title . '"><img src="' . CalRoot . '/images/share/myshare.png" alt="myshare" title="myshare" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.n4g.com/tips.aspx?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/n4g.png" alt="n4g" title="n4g" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.netvibes.com/share?title=' . $title . '&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/netvibes.png" alt="netvibes" title="netvibes" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.netvouz.com/action/submitBookmark?url=' . $link . '&amp;title=' . $title . '&amp;popup=no"><img src="' . CalRoot . '/images/share/netvouz.png" alt="netvouz" title="netvouz" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.newsvine.com/_tools/seed&amp;save?u=' . $link . '&amp;h=' . $title . '"><img src="' . CalRoot . '/images/share/newsvine.png" alt="newsvine" title="newsvine" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://nujij.nl/jij.lynkx?t=' . $title . '&amp;u=' . $link . '"><img src="' . CalRoot . '/images/share/nujij.png" alt="nujij" title="nujij" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://ping.fm/ref/?link=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/ping.png" alt="ping.fm" title="ping.fm" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.plugim.com/submit?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/plugim.png" alt="plugim" title="plugim" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://ratimarks.org/bookmarks.php/?action=add&amp;address=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/ratimarks.png" alt="ratimarks" title="ratimarks" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://segnalo.alice.it/post.html.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/segnalo.png" alt="segnalo" title="segnalo" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.simpy.com/simpy/LinkAdd.do?href=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/simpy.png" alt="simpy" title="simpy" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://slashdot.org/bookmark.pl?title=' . $title . '&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/slashdot.png" alt="slashdot" title="slashdot" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.sphere.com/search?q=sphereit:' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/sphere.png" alt="sphere" title="sphere" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.sphinn.com/submit.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/sphinn.png" alt="sphinn" title="sphinn" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.spurl.net/spurl.php?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/spurl.png" alt="spurl" title="spurl" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.squidoo.com/lensmaster/bookmark?' . $link . '"><img src="' . CalRoot . '/images/share/squidoo.png" alt="squidoo" title="squidoo" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.symbaloo.com/nl/add/url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/symbaloo.png" alt="symbaloo" title="symbaloo" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.tagtooga.com/tapp/db.exe?c=jsEntryForm&amp;b=fx&amp;title=' . $title . '&amp;url=' . $link . '"><img src="' . CalRoot . '/images/share/tagtooga.png" alt="tagtooga" title="tagtooga" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.thisnext.com/pick/new/submit/sociable/?url=' . $link . '&amp;name=' . $title . '"><img src="' . CalRoot . '/images/share/thisnext.png" alt="thisnext" title="thisnext" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://tipd.com/submit.php?url=' . $link . '"><img src="' . CalRoot . '/images/share/tipd.png" alt="tipd" title="tipd" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.tumblr.com/share?v=3&amp;u=' . $link . '&amp;t=' . $title . '&amp;s="><img src="' . CalRoot . '/images/share/tumblr.png" alt="tumblr" title="tumblr" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.upnews.it/submit?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/upnews.png" alt="upnews.it" title="upnews.it" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.webnews.de/einstellen?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/webnews.png" alt="webnews.de" title="webnews.de" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.webride.org/discuss/split.php?uri=' . $link . '&amp;title' . $title . '"><img src="' . CalRoot . '/images/share/webride.png" alt="webride" title="webride" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.wikio.com/vote?url=' . $link . '"><img src="' . CalRoot . '/images/share/wikio.png" alt="wikio" title="wikio" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.wykop.pl/dodaj?url=' . $link . '"><img src="' . CalRoot . '/images/share/wykop.png" alt="wykop.pl" title="wykop.pl" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://www.xerpi.com/block/add_link_from_extension?url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/xerpi.png" alt="xerpi" title="xerpi" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://buzz.yahoo.com/submit/?submitUrl=' . $link . '&amp;submitHeadline=' . $title . '"><img src="' . CalRoot . '/images/share/yahoobuzz.png" alt="yahoo buzz" title="yahoo buzz" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=' . $link . '&amp;t=' . $title . '"><img src="' . CalRoot . '/images/share/yahoo.png" alt="yahoo myweb" title="yahoo myweb" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://yigg.de/neu?exturl=' . $link . '&amp;exttitle=' . $title . '"><img src="' . CalRoot . '/images/share/yiggit.png" alt="yigg.de" title="yigg.de" /></a>';
		echo '<a target="_blank" class="eventShare" href="http://tag.zurpy.com/?box=1&amp;url=' . $link . '&amp;title=' . $title . '"><img src="' . CalRoot . '/images/share/zurpy.png" alt="zurpy" title="zurpy" /></a>';
		echo '</div>';
		echo '<a href="' . CalRoot . '/index.php?com=send&amp;eID=' . $eID . '" class="eventDetailLink"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconEmail.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;<a href="' . CalRoot . '/index.php?com=send&amp;eID=' . $eID . '" class="eventDetailLink">' . $hc_lang_event['EmailToFriend'] . '</a>';
		echo '<br /><br />';
		
		echo '<b>' . $hc_lang_event['SaveToCalendar'] . '</b><br />';
		echo '<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=1" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="' . CalRoot . '/images/share/googleCal.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=1" target="_blank" class="eventDetailLink">Google ' . $hc_lang_event['Calendar'] . '</a><br />';
		echo '<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=2" class="eventDetailLink" target="_blank"><img class="eventDetailLink" src="' . CalRoot . '/images/share/yahooCal.png" width="16" height="16" alt="" border="0" /></a>&nbsp;<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=2" target="_blank" class="eventDetailLink">Yahoo! ' . $hc_lang_event['Calendar'] . '</a><br />';
		echo '<img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconiCal.png" width="16" height="16" alt="" border="0" />&nbsp;iCalendar (<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=3" class="eventDetailLink">' . $hc_lang_event['Download'] . '</a>) (<a href="webcal://' . substr(CalRoot, 7) . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=3" class="eventDetailLink">' . $hc_lang_event['Subscribe'] . '</a>)<br />';
		echo '<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=4" class="eventDetailLink"><img class="eventDetailLink" src="' . CalRoot . '/images/icons/iconDownload.png" width="16" height="16" alt="" border="0" /></a>&nbsp;vCalendar (<a href="' . CalRoot . '/link/SaveEvent.php?eID=' . $eID . '&amp;cID=4" class="eventDetailLink">' . $hc_lang_event['Download'] . '</a>)<br />';
		echo '<img class="eventDetailLink" src="' . CalRoot . '/images/share/microformats.png" width="16" height="16" alt="" border="0" />&nbsp;hCalendar &amp; hCard (<a href="http://microformats.org/about/" class="eventDetailLink" target="_blank" rel="nofollow">?</a>)';
				
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
		}//end if
		echo '</div>';
		
		if($seriesID != '' && $eventDate >= date("Y-m-d")){
			echo "<br /><b>" . $hc_lang_event['OtherDates'] . "</b><br />";
			$result = doQuery("SELECT PkID, StartDate FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . $seriesID . "' AND IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate LIMIT 5");
			if(hasRows($result)){
				while($row = mysql_fetch_row($result)){
					if($eID == $row[0]){echo "**";}//end if
					echo '<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" rel="nofollow" class="eventDetailDates">' . stampToDate($row[1], $hc_cfg14) . '</a><br />';
				}//end while
			}//end if
			echo '<div style="text-align:right;"><a href="' . CalRoot . '/index.php?com=serieslist&amp;sID=' . $seriesID . '" rel="nofollow" class="eventMain">' . $hc_lang_event['AllDates'] . '</a></div>';
		}//end if
		echo '</div></div><div style="clear:both;">&nbsp;</div>';
		
		if(isset($hc_cfg26) && $hc_cfg26 != '' && $locLat != '' && $locLon != ''){
			echo '<div id="hc_Gmap">&nbsp;</div>';
			echo "\n" . '<script language="JavaScript" type="text/JavaScript">';
			echo "\n" . '//<!--';
			echo "\n" . 'var map;';
			echo "\n" . 'var marker;';
			echo "\n" . 'function createMarker(point, msg){marker = new GMarker(point);GEvent.addListener(marker, "click", function() {marker.openInfoWindowHtml(msg);});return marker;}';
			echo "\n" . 'function buildGmap(hc_Lat, hc_Lon, hc_gMsg){if (GBrowserIsCompatible()) {map = new GMap2(document.getElementById("hc_Gmap"));map.addControl(new GSmallMapControl());map.addControl(new GMapTypeControl());map.setCenter(new GLatLng(hc_Lat, hc_Lon),' . $hc_cfg27 . ');var point = new GLatLng(hc_Lat, hc_Lon);map.addOverlay(createMarker(point, hc_gMsg));marker.openInfoWindowHtml(hc_gMsg);}}';
			echo "\n" . '//-->';
			echo "\n" . '</script>';
		}//end if
	
		if($hc_cfg56 == 1){
			echo '<a name="comments"></a>';
			echo '<br /><div class="eventDetailHeader">' . $hc_lang_event['Comments'] . '</div>';
			
			if(isset($_GET['msg'])){
				switch ($_GET['msg']){
					case '1' :
						feedback(1, $hc_lang_event['Feed04']);
						break;
					case '2' :
						feedback(1, $hc_lang_event['Feed05']);
						break;
					case '3' :
						feedback(2, $hc_lang_event['Feed06']);
						break;
					case '4' :
						feedback(2, $hc_lang_event['Feed07']);
						break;
					case '5' :
						feedback(2, $hc_lang_event['Feed08']);
						break;
					case '6' :
						feedback(1, $hc_lang_event['Feed09']);
						break;
				}//end switch
			}//end if
			
			if(!isset($_SESSION[$hc_cfg00 . 'hc_OpenID'])){
				echo '<a href="' . CalRoot . '/index.php?com=login" class="commentLogin">' . $hc_lang_event['LoginPost'] . '</a><br /><br />';
			} else {
				echo '<form name="hc_cmnts" id="hc_cmnts" method="post" action="' . CalRoot . '/components/CommentAction.php" onsubmit="return chkFrm();">';
				echo '<input name="eID" id="eID" type="hidden" value="' . cIn($eID) . '" />';
				if(in_array(6, $hc_captchas)){
					echo '<fieldset>';
					echo '<legend>' . $hc_lang_event['Authentication'] . '</legend>';
					echo $hc_lang_event['CannotRead'] . '<br /><br />';
					echo '<div class="frmReq"><label for="proof">&nbsp;</label>';
					buildCaptcha();
					echo '<br /><br /></div><div class="frmReq">';
					echo '<label>' . $hc_lang_event['ImageText'] . '</label>';
					echo '<div style="float:left;padding-right:5px;"><input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" /></div>';
					echo '<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" class="eventMain">' . $hc_lang_event['Confirm'] . '</a></div>';
					echo '</div></fieldset><br />';
				}//end if
				
				echo '<fieldset><legend>' . $hc_lang_event['PostComment'] . '</legend>';
				echo '<div class="frmOpt"><label>' . $hc_lang_event['LoggedAs'] . '</label>';
				echo '<a href="' . $_SESSION[$hc_cfg00 . 'hc_OpenID'] . '" class="commentIdentity" target="_blank">' . $_SESSION[$hc_cfg00 . 'hc_OpenIDShortName'] . '</a></div>';
				echo '<div class="frmOpt"><label>&nbsp;</label>' . $hc_lang_event['CommentWarning'] . '</div>';
				
				echo '<div class="frmOpt"><label>' . $hc_lang_event['CommentLabel'] . '</label>';
				echo '<textarea name="cmntText" id="cmntText" rows="5" cols="70" onkeyup="this.value=this.value.slice(0, 250)"></textarea></div>';
				echo '</fieldset><br /><input name="submit" id="submit" type="submit" value="' . $hc_lang_event['SubmitComment'] . '" class="button" />';
				echo '</fieldset></form><br />';
			}//end if
			
			$result = doQuery("SELECT c.*, o.Identity, o.ShortName
								FROM " . HC_TblPrefix . "comments c
									LEFT JOIN " . HC_TblPrefix . "events e ON (c.EntityID = e.PkID)
									LEFT JOIN " . HC_TblPrefix . "oidusers o ON (c.PosterID = o.PkID)
								WHERE c.EntityID = " . $eID . " AND c.IsActive = 1 AND c.TypeID = 1
								ORDER BY PostTime");
			if(hasRows($result)){
				$cnt = 0;
				while($row = mysql_fetch_row($result)){
					$hidden = ($row[6] > $hc_cfg53) ? '' : '-hidden';
					echo '<div id="commentBG_' . $row[0] . '" class="commentFrame' . $hidden . '">';
					echo '<div id="commentTools_' . $row[0] . '" class="commentTools' . $hidden . '">';
					echo ($row[6] > 0) ? '+' . $row[6] : $row[6];
					echo ' Recomnds <br />';
					echo '&nbsp;<a href="' . CalRoot . '/components/Recomnds.php?cID=' . $row[0] . '&amp;eID=' . $eID . '&amp;tID=1&amp;s=u" rel="nofollow"><img src="' . CalRoot . '/images/icons/iconCommentU.png" width="16" height="16" alt="" id="commentU_' . $row[0] . '" class="recomnds' . $hidden . '" style="vertical-align:middle;" /></a>';
					echo '&nbsp;<a href="' . CalRoot . '/components/Recomnds.php?cID=' . $row[0] . '&amp;eID=' . $eID . '&amp;tID=1&amp;s=d" rel="nofollow"><img src="' . CalRoot . '/images/icons/iconCommentD.png" width="16" height="16" alt="" id="commentD_' . $row[0] . '" class="recomnds' . $hidden . '" style="vertical-align:middle;" /></a></div>';
					
					echo '<a href="' . $row[8] . '" class="commentUser" target="_blank" rel="nofollow">' . $row[9] . '</a> ' . $hc_lang_event['Said'];
					echo ($row[6] > $hc_cfg53) ? '' : '<a href="javascript:;" class="commentShow" id="commentLink_' . $row[0] . '" onclick="javascript:togComment(\'' . $row[0] . '\')">' . $hc_lang_event['Show'] . '</a>';
					echo '<br /><br /><div id="comment_' . $row[0] . '" class="comment' . $hidden . '">';
					echo censorWords(nl2br(cOut($row[1])), $hc_censored_words);
					echo '<div id="commentDate_' . $row[0] . '" class="commentDate' . $hidden . '">';
					echo '<a href="' . CalRoot . '/index.php?com=report&amp;cID=' . $row[0] . '&amp;tID=1" class="commentReport" rel="nofollow">' . $hc_lang_event['Report'] . ' <img src="' . CalRoot . '/images/icons/iconReport.png" width="16" height="16" alt="" style="vertical-align:middle;" /></a>&nbsp;';
					echo '&nbsp;&nbsp;' . stampToDate($row[3], $hc_cfg24 . ' @ ' . $hc_cfg23) . '</div>';
					echo '</div></div>';
					++$cnt;
				}//end if
				echo '<div class="commentFooter">&nbsp;</div>';
			}//end if
		}//end if
	} else {
		echo "<br />";
		echo $hc_lang_event['NoEventDetail'];
		echo '<ol>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent1'] . ' <span class="miniCalEvents" style="padding:3px;">03</span></li>';
		echo '<li style="line-height: 30px;">' . $hc_lang_event['NoEvent2'] . ' <span class="miniCalNav" style="padding:3px;">&lt;</span> <span class="miniCalNav" style="padding:3px;">&gt;</span></li>';
		echo '<li style="line-height: 30px;"><a href="' . CalRoot . '/" class="eventMain">' . $hc_lang_event['NoEvent3'] . '</a></li>';
		echo '</ol>';
	}//end if	?>