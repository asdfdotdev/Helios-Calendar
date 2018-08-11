<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('isHC')){exit(-1);}
	/**
	 * Increment event view count.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $eID ID number of the event to increment.
	 * @return void
	 */
	function event_add_session_view($eID){
		global $hc_cfg;
		
		if(preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']) || in_array($eID,$_SESSION['hc_trail']))
			return 0;
		
		array_push($_SESSION['hc_trail'], $eID);
		doQuery("UPDATE " . HC_TblPrefix . "events SET Views = Views + 1 WHERE PkID = '" . cIn($eID) . "'");
	}
	/**
	 * Retrieve active ids/dates for event series.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $series Event Series ID
	 * @param numeric $list Number of dates to return. (0 = All Active Dates in Series)
	 * @return array Event IDs / Event Dates (Ordered Chronologically by Date)
	 */
	function event_series_dates($series,$list){
		global $hc_cfg;
		
		$limit = ($list > 0) ? " LIMIT ".cIn($list) : '';
		$result = doQuery("SELECT PkID, StartDate FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($series) . "' AND IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "' ORDER BY StartDate".$limit);
		
		if(!hasRows($result))
			return 0;
		
		$dates = array();
		while($row = mysql_fetch_row($result)){
			$dates[$row[0]] = stampToDate($row[1], $hc_cfg[14]);}
		return $dates;
	}
	/**
	 * Output unordered list (markup) of event dates in series with links to events.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $series Event Series ID
	 * @param string $eventDate Date of currently viewed event, highlighted. (Format: YYYY-MM-DD)
	 * @return void
	 */
	function event_series($series,$eventDate = ''){
		global $hc_lang_event;
		
		$dates = event_series_dates($series,5);
		if(count($dates) < 2)
			return 0;
		echo '<ul class="series">';
		foreach($dates as $id => $date){
			$today = ($eventDate == $date) ? ' class="series_today"' : '';
			echo '
			<li'.$today.'><a href="'.CalRoot.'/index.php?eID='.$id.'">'.$date.'</a></li>';}
		echo '
		</ul>
		<a href="'.CalRoot.'/index.php?com=series&sID='.$series.'" class="series">'.$hc_lang_event['AllDates'].'</a>';
	}
	/**
	 * Output unordered list of event categories.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $event Event ID
	 * @return void
	 */
	function event_categories($event){
		$result = doQuery("SELECT c.PkID, c.CategoryName
						FROM " . HC_TblPrefix . "eventcategories ec
							LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
						WHERE c.IsActive = 1 AND ec.EventID = '" . cIn($event) . "'
						ORDER BY c.CategoryName");
		if(!hasRows($result))
			return 0;
		
		echo '
		<ul>';
		while($row = mysql_fetch_row($result)){
			echo '
		<li><a itemprop="eventType" href="'.CalRoot.'/index.php?com=searchresult&amp;t='.$row[0].'" rel="nofollow">'.cOut($row[1]).'</a></li>';
		}
		echo '
		</ul>';
	}
	/**
	 * Retrieves array of event data.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return array Event Data
	 */
	function event_fetch(){
		global $eID, $hc_cfg, $hc_lang_event, $title, $desc;
				
		$result = doQuery("SELECT e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.ContactName, e.ContactEmail, e.ContactURL, e.ContactPhone, e.AllowRegister, 
							e.SpacesAvailable, e.LocID, e.SeriesID, e.Cost, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
							e.LocationZip, e.LocCountry, e.ShortURL, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, l.Phone, l.Email, l.Lat, l.Lon,
							en.NetworkID, COUNT(r.EventID) as Taken
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventnetwork en ON (e.PkID = en.EventID AND en.NetworkType = 2)
							LEFT JOIN " . HC_TblPrefix . "registrants r ON (e.PkID = r.EventID AND r.IsActive = 1)
						WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.PkID = '" . $eID . "'
						GROUP BY e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.ContactName, e.ContactEmail, e.ContactURL, e.ContactPhone, e.AllowRegister,
							e.SpacesAvailable, e.LocID, e.SeriesID, e.Cost, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
							e.LocationZip, e.LocCountry, e.ShortURL, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, l.Phone, l.Email, l.Lat, l.Lon, 
							en.NetworkID");
		if(!hasRows($result) || mysql_result($result,0,0) <= 0 || ((strtotime(mysql_result($result,0,"StartDate")) < strtotime(SYSDATE)) && $hc_cfg[11] == 0))
			go_home();
		
		event_add_session_view($eID);
		
		if(mysql_result($result,0,6) == 0){
			$time = (mysql_result($result,0,4) != '') ? stampToDate(mysql_result($result,0,4), $hc_cfg[23]) : '';
			$time .= (mysql_result($result,0,5) != '') ? ' - ' . stampToDate(mysql_result($result,0,5), $hc_cfg[23]) : '';
			$stamp = date("Y-m-d\Th:i:00.0",strtotime(mysql_result($result,0,3) . trim(' '.mysql_result($result,0,4)))) . HCTZ;
		} else {
			$time = (mysql_result($result,0,6) == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TimeTBA'];
			$stamp = date("Y-m-d",strtotime(mysql_result($result,0,3)));}
		$eBrite = (mysql_result($result,0,"NetworkID") != '') ? '<iframe src="http://www.eventbrite.com/tickets-external?eid='.mysql_result($result,0,"NetworkID").'" class="eventbrite"></iframe>' : '';
		$event = array(
		    'EventID'			=>	mysql_result($result,0,"PkID"),
		    'Title'			=>	mysql_result($result,0,"Title"),
		    'Description'		=>	mysql_result($result,0,"Description") . $eBrite,
		    'Date'			=>	stampToDate(mysql_result($result,0,"StartDate"), $hc_cfg[14]),
		    'Time'			=>	$time,
		    'Timestamp'		=>	$stamp,
		    'Contact'			=>	mysql_result($result,0,"ContactName"),
		    'Contact_Email'		=>	mysql_result($result,0,"ContactEmail"),
		    'Contact_URL'		=>	(mysql_result($result,0,"ContactURL") != 'http://') ? mysql_result($result,0,"ContactURL") : NULL ,
		    'Contact_Phone'		=>	mysql_result($result,0,"ContactPhone"),
		    'RSVP'			=>	mysql_result($result,0,"AllowRegister"),
		    'RSVP_Spaces'		=>	(mysql_result($result,0,"SpacesAvailable") > 0) ? mysql_result($result,0,"SpacesAvailable") : event_lang('Unlimited'),
		    'RSVP_Taken'		=>	mysql_result($result,0,"Taken"),
		    'VenueID'			=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"LocID") : 0,
		    'Venue_Name'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Name") : mysql_result($result,0,"LocationName"),
		    'Venue_Address'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Address") : mysql_result($result,0,"LocationAddress"),
		    'Venue_Address2'	=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Address2") : mysql_result($result,0,"LocationAddress2"),
		    'Venue_City'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"City") : mysql_result($result,0,"LocationCity"),
		    'Venue_Region'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"State") : mysql_result($result,0,"LocationState"),
		    'Venue_Postal'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Zip") : mysql_result($result,0,"LocationZip"),
		    'Venue_Country'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Country") : mysql_result($result,0,"LocCountry"),
		    'Venue_Email'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Email") : NULL,
		    'Venue_URL'		=>	(mysql_result($result,0,"LocID") > 0 && mysql_result($result,0,"URL") != 'http://') ? mysql_result($result,0,"URL") : NULL,
		    'Venue_Phone'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Phone") : NULL,
		    'Venue_Lat'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Lat") : NULL,
		    'Venue_Lon'		=>	(mysql_result($result,0,"LocID") > 0) ? mysql_result($result,0,"Lon") : NULL,
		    'SeriesID'			=>	mysql_result($result,0,"SeriesID"),
		    'Cost'			=>	mysql_result($result,0,"Cost"),
		    'Bitly'			=>	mysql_result($result,0,"ShortURL"),
		    'Comments'			=>	($hc_cfg[56] == 1 && $hc_cfg[25] != '') ? true : false,
		    'DisqusURL'		=>	(mysql_result($result,0,"SeriesID") != '') ? CalRoot.'/index.php?com=series&sID='.mysql_result($result,0,"SeriesID") : CalRoot.'/index.php?eID='.$eID,
		    'DisqusID'			=>	(mysql_result($result,0,"SeriesID") != '') ? mysql_result($result,0,"SeriesID").' '.CalRoot.'/index.php?com=series&sID='.mysql_result($result,0,"SeriesID") : $eID.' '.CalRoot.'/index.php?eID='.$eID,
		    'DateRaw'			=>	mysql_result($result,0,"StartDate"),
		    );
		
		$title = cOut(mysql_result($result,0,"Title"));
		$desc = cOut(mysql_result($result,0,"Description"));
		
		return array_map('cOut', $event);
	}
	/**
	 * Generates event browse navigation icons.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $prev unix timestamp of previous date
	 * @param integer $next unix timestamp of next date
	 * @param integer $window Number of days being browsed
	 * @param string $location Location URL Argument w/ID "&amp;lID=X" (Preserves Browse by Location)
	 * @return string Browse Navigation HTML Markup
	 */
	function event_browse_nav($prev,$next,$window,$location){
		global $lID, $hc_cfg, $hc_lang_event;
		
		$m = ($window == 0) ? '&amp;m=1' : '';
		$pLink = ($window > 518400) ? date("U", mktime(0,0,0,HCMONTH-1,1,HCYEAR)) : $prev - ($window + 86400);
		$fltr = (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? 'R' : '';
		$bak = ($hc_cfg['First'] > $prev || (HCDATE <= SYSDATE && $hc_cfg[11] == 0)) ? 
				'<a href="#"><img src="'.CalRoot.'/img/nav/leftb.png" alt="'.$hc_lang_event['BrowseBack'].'" /></a>':
				'<a href="'.CalRoot.'?d='.date("Y-m-d",$pLink).$location.$m.'"><img src="'.CalRoot.'/img/nav/left.png" alt="'.$hc_lang_event['BrowseBack'].'" /></a>';
		$fwd = ($hc_cfg['Last'] > $next) ? 
				'<a href="'.CalRoot.'?d='.date("Y-m-d",($next+86400)).$location.$m.'"><img src="'.CalRoot.'/img/nav/right.png" alt="'.$hc_lang_event['BrowseForward'].'" /></a>':
				'<a href="#"><img src="'.CalRoot.'/img/nav/rightb.png" alt="'.$hc_lang_event['BrowseForward'].'" /></a>';
		$loc = ($lID > 0) ? '<a href="'.CalRoot.'/index.php?com=location&lID='.$lID.'" rel="nofollow"><img src="'.CalRoot.'/img/icons/card.png" /></a>' : '';
		
		return '
		<div class="nav">
			'.$loc.'
			<a href="'.CalRoot.'/index.php?b=2'.$location.'" rel="nofollow"><img src="'.CalRoot.'/img/nav/daily.png" alt="'.$hc_lang_event['ALTBrowseD'].'" title="'.$hc_lang_event['ALTBrowseD'].'" /></a>
			<a href="'.CalRoot.'/index.php?b=0'.$location.'" rel="nofollow"><img src="'.CalRoot.'/img/nav/weekly.png" alt="'.$hc_lang_event['ALTBrowseW'].'" title="'.$hc_lang_event['ALTBrowseW'].'" /></a>
			<a href="'.CalRoot.'/index.php?b=1'.$location.'" rel="nofollow"><img src="'.CalRoot.'/img/nav/monthly.png" alt="'.$hc_lang_event['ALTBrowseM'].'" title="'.$hc_lang_event['ALTBrowseM'].'" /></a>
			<a href="'.CalRoot.'/index.php?com=filter'.$location.'" rel="nofollow"><img src="'.CalRoot.'/img/nav/filter'.$fltr.'.png" alt="' . $hc_lang_event['Filter'] . '" /></a>
			<a href="' . CalRoot . '/?d='.SYSDATE.$location.$m.'" rel="nofollow"><img src="' . CalRoot . '/img/nav/home.png" alt="' . $hc_lang_event['Home'] . '" /></a>
			'.$bak.'
			'.$fwd.'
		</div>';
	}
	/**
	 * Output event browse navigation icons & event list by date.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $nav_function [optional] Browse Navigation function to use. (Default: event_browse_nav)
	 * @return void
	 */
	function event_browse($nav_function = 'event_browse_nav'){
		global $lID, $hc_cfg, $hc_lang_event, $favQ1, $favQ2;
		
		$location = $lQuery = '';
		if($lID > 0){
			$location = '&amp;lID=' . $lID;
			$lQuery = " AND e.LocID = '" . $lID . "'";
		}
		
		if(isset($_GET['m']) || $_SESSION['BrowseType'] == 2){
			$sqlStart = $sqlEnd = strtotime(HCDATE);
			$startDate = $endDate = strtotime(HCDATE);
			$window = 0;
		} else {
			if($_SESSION['BrowseType'] == 1){
				$window = (date("t", strtotime(HCDATE)) - 1) * 86400;
				$remove = ($hc_cfg[48] == 0) ? (date("j", strtotime(HCDATE)) - 1) * 86400 : 0;
			} else {
				$window = 6 * 86400;
				$remove = ($hc_cfg[48] == 0) ? (date("w", strtotime(HCDATE)) - 1) * 86400 : 0;}
			
			$startDate = (strtotime(HCDATE) - $remove);
			$endDate = $sqlEnd = ($startDate + $window);
			$sqlStart = (HCDATE != SYSDATE) ? $startDate : strtotime(SYSDATE);
		}
		
		$cnt = $date = 0;
		$myNav = call_user_func($nav_function,$startDate,$endDate,$window,$location);
		
		$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE e.StartDate BETWEEN '" . date("Y-m-d", $sqlStart) . "' AND '" . date("Y-m-d", $sqlEnd) . "'"
						.$lQuery.$favQ1.$favQ2." AND e.IsActive = 1 AND e.IsApproved = 1
						ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title");
		echo $myNav;
		
		if(!hasRows($result)){
			no_event_notice();
			return 0;}
		
		while($row = mysql_fetch_row($result)){
			if(($date != $row[2])){
				$date = $row[2];
				echo ($cnt > 0) ? '
			</ul>' : '';
				echo '
			<header>' . stampToDate($row[2], $hc_cfg[14]) . '</header>
			<ul>';
				$cnt = 1;
			}

			$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
				$stamp = date("Y-m-d\Th:i:00",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			echo '
			<li'.$hl.' itemscope itemtype="http://schema.org/Event"><time itemprop="startDate" datetime="'.$stamp.'">'.$time.'</time><a itemprop="name" href="'.CalRoot . '/index.php?eID='.$row[0].$location.'">'.cOut($row[1]).'</a></li>';
			++$cnt;
		}
		echo '</ul>
		'.$myNav;
	}
	/**
	 * Output "no events" notice when the current browse criteria contains no active events to display.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function no_event_notice(){
		global $hc_cfg, $hc_lang_event;
		
		$filter = (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? '<li>' . $hc_lang_event['NoEvent4'] . ' <a href="' . CalRoot . '/index.php?com=filter"><img src="'.CalRoot.'/img/nav/filterR.png" width="16" height="16" alt="' . $hc_lang_event['Filter'] . '" /></a></li>' :'';
		$submit = ($hc_cfg[1] == 1) ? '<li><a href="' . CalRoot . '/index.php?com=submit" rel="nofollow">' . $hc_lang_event['NoEvent3'] . '</a></li>' : '';
		echo '
			'.$hc_lang_event['NoEventBrowse'].'
			<ul class="no_events">
				<li>'.$hc_lang_event['NoEvent1'] . '&nbsp;
					<table class="mini-cal" style="display:inline;vertical-align:middle;width:25px;height:25px;"><tr><td class="events" style="padding:3px;">03</td></tr></table>
				</li>
				<li>' . $hc_lang_event['NoEvent2'] . '&nbsp;
					<table class="mini-cal" style="width:25px;height:25px;display:inline;vertical-align:middle;"><tr><td class="nav" style="padding:3px;"><a href="#" rel="nofollow">&lt;</a></td></tr></table>&nbsp;
					<table class="mini-cal" style="width:25px;height:25px;display:inline;vertical-align:middle;"><tr><td class="nav" style="padding:3px;"><a href="#" rel="nofollow">&gt;</a></td></tr></table>
				</li>
				'.$filter.'
				'.$submit.'
			</ul>';
	}
	/**
	 * Generates save, share & location urls for passed event.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $eID Event ID
	 * @param integer $lID Location ID
	 * @return array Array of URLs for the event.
	 */
	function event_location_links($eID,$lID){
		if(!is_numeric($eID) || !is_numeric($lID))
			return 0;
		$links = array(
		    'Event_iCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=3',
		    'Event_vCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=4',
		    'Event_GoogleCal'	=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=1',
		    'Event_YahooCal'	=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=2',
		    'Event_LiveCal'		=>	CalRoot . '/link/SaveEvent.php?eID='.$eID.'&amp;cID=5',
		    'Event_URL'		=>	CalRoot . '/link/index.php?tID=1&amp;oID='.$eID,
		    'Venue_Profile'		=>	CalRoot . '/index.php?com=location&amp;lID='.$lID,
		    'Venue_Weather'		=>	CalRoot . '/link/index.php?tID=3&amp;oID='.$eID.'&amp;lID='.$lID,
		    'Venue_Directions'	=>	CalRoot . '/link/index.php?tID=2&amp;oID='.$eID.'&amp;lID='.$lID,
		    'Venue_URL'		=>	CalRoot . '/link/index.php?tID=4&amp;oID='.$lID,
		    'This'			=>	CalRoot . '/index.php?eID='.$eID,
		);
		
		return array_map('cOut', $links);
	}
	/**
	 * Retrieves interface text entry from event language file.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which language file array key
	 * @return string language file entry
	 */
	function event_lang($which){
		global $hc_lang_event;
		
		if(!array_key_exists($which,$hc_lang_event))
			return;
		
		return $hc_lang_event[$which];
	}
	/**
	 * Output RSVP progress meter for event.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $spaces Number of RSVPs available for the event. (0 = No Limit, Text Notice Only)
	 * @param integer $taken Number of RSVPs requested.
	 * @param integer $width width, in pixels, of the meter.
	 * @return void
	 */
	function event_rsvp_meter($spaces,$taken,$width){
		global $eID, $hc_lang_event;
		
		if($spaces > 0 && $spaces > $taken){
			$regWidth = ($taken / $spaces) * $width;
			$fillWidth = $width - $regWidth;
			echo '
		<img src="'.CalRoot.'/img/meter/full.gif" width="'.$regWidth.'" class="regFull" /><img src="'.CalRoot.'/img/meter/empty.gif" width="'.$fillWidth.'" class="regAvailable" />';
		} elseif($spaces > 0 && $spaces <= $taken) {
			echo '
		<img src="'.CalRoot.'/img/meter/overflow.gif" width="'.$width.'" class="regOver" />';
		} elseif($spaces == 0){
			echo $hc_lang_event['NoLimit'];
		}
	}
	/**
	 * Output conditional RSVP link (based on availability).
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $spaces Number of RSVPs available for the event. (0 = No Limit)
	 * @param integer $taken Number of RSVPs requested.
	 * @return void
	 */
	function event_rsvp_link($spaces,$taken){
		global $eID, $hc_lang_event;
		
		$txt = ($spaces > 0 && $spaces <= $taken) ? $hc_lang_event['Overflow'] : $hc_lang_event['Register'];
		echo '
		<a href="'.CalRoot.'/index.php?com=rsvp&amp;eID='.$eID.'" class="icon rsvp">'.$txt.'</a>';
	}
	/**
	 * Retrieve ID, Title, Date, Start Time, End Time & TBD values for all events in the current series.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return resource MySQL result set
	 */
	function series_fetch(){
		global $title, $desc;
		
		$sID = (isset($_GET['sID'])) ? cIn(strip_tags($_GET['sID'])) : 0;
		$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.Description
						FROM " . HC_TblPrefix . "events e
						WHERE e.SeriesID = '".$sID."' AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "'
						ORDER BY e.StartDate, e.Title, e.TBD, e.StartTime");
		if(!hasRows($result))
			go_home();
		
		$title = cOut(mysql_result($result,0,1));
		$desc = cOut(mysql_result($result,0,6));
		
		return $result;
	}
	/**
	 * Output event series list.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param resource $result MySQL result set (Requires: ID, Title, Date, Start Time, End Time & TBD)
	 * @return void
	 */
	function series_list($result){
		global $hc_cfg, $hc_lang_event;		
		$cnt = 1;
		
		echo '
		<header>'.cOut(mysql_result($result,0,1)).'</header>
		<ul>';
		
		mysql_data_seek($result,0);
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
			
			if($row[5] == 0){
				$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
				$stamp = date("Y-m-d\Th:i:00.0",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			
			echo '
				<li'.$hl.'><time datetime="'.$stamp.'">'.$time.'</time><a href="'.CalRoot . '/index.php?eID='.$row[0].'">'.stampToDate($row[2], $hc_cfg[14]).'</a></li>';
			++$cnt;
		}
		echo '</ul>';
	}
?>