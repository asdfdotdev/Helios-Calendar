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
	 * Increment location view count.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $lID ID number of the location to increment.
	 * @return void
	 */
	function location_add_session_view($lID){
		global $hc_cfg;
		
		if(preg_match($hc_cfg[85],$_SERVER['HTTP_USER_AGENT']) || in_array($lID,$_SESSION['hc_trail']))
			return 0;
		
		array_push($_SESSION['hc_traill'], $lID);
		doQuery("UPDATE " . HC_TblPrefix . "locations SET Views = Views + 1 WHERE PkID = '" . cIn($lID) . "'");
	}
	/**
	 * Retrieves array of location data.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return array Location Data
	 */
	function location_fetch(){
		global $lID, $hc_cfg, $hc_lang_event, $title, $desc;
		
		location_add_session_view($lID);
		
		$result = doQuery("SELECT PkID, Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, Lat, Lon, ShortURL From " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "' AND IsActive = 1");
		
		if(!hasRows($result) || mysql_result($result,0,0) <= 0)
			go_home();
		
		$loc = array(
		    'LocID'			=>	mysql_result($result,0,"PkID"),
		    'Name'			=>	mysql_result($result,0,"Name"),
		    'Description'		=>	mysql_result($result,0,"Descript"),
		    'Address'			=>	mysql_result($result,0,"Address"),
		    'Address2'			=>	mysql_result($result,0,"Address2"),
		    'City'			=>	mysql_result($result,0,"City"),
		    'Region'			=>	mysql_result($result,0,"State"),
		    'Postal'			=>	mysql_result($result,0,"Zip"),
		    'Country'			=>	mysql_result($result,0,"Country"),
		    'Email'			=>	mysql_result($result,0,"Email"),
		    'Phone'			=>	mysql_result($result,0,"Phone"),
		    'Lat'				=>	mysql_result($result,0,"Lat"),
		    'Lon'				=>	mysql_result($result,0,"Lon"),
		    'Bitly'			=>	mysql_result($result,0,"ShortURL"),
		    'Comments'			=>	($hc_cfg[56] == 1 && $hc_cfg[25] != '') ? true : false,
		    'DisqusURL'		=>	CalRoot.'/index.php?com=location&amp;lID='.$lID,
		    'DisqusID'			=>	$lID . ' ' . CalRoot.'/index.php?com=location&amp;lID='.$lID,
		    'Link_This'		=>	CalRoot.'/index.php?com=location&amp;lID='.$lID,
		    'Link_URL'			=>	(mysql_result($result,0,"URL") != 'http://') ? CalRoot . '/link/index.php?tID=4&amp;oID='.mysql_result($result,0,"PkID") : NULL,
		    'Link_Weather'		=>	CalRoot . '/link/index.php?tID=3&amp;oID=0&amp;lID='.mysql_result($result,0,"PkID"),
		    'Link_Directions'	=>	CalRoot . '/link/index.php?tID=2&amp;oID=0&amp;lID='.mysql_result($result,0,"PkID"),
		    'Link_Calendar'		=>	CalRoot.'/index.php?lID='.$lID,
		    );
		
		$title = cOut(mysql_result($result,0,"Name"));
		$desc = cOut(mysql_result($result,0,"Descript"));
		
		return array_map('cOut', $loc);
	}
	/**
	 * Output list of upcoming events for the location.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $limit [optional] Event List Size (Default:5)
	 * @return void
	 */
	function location_events($limit = 5){
		global $lID, $hc_cfg, $hc_lang_core, $hc_lang_locations;
		
		$result = doQuery("SELECT PkID, Title, StartDate, StartTime, EndTime, TBD
						FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND IsApproved = 1 AND LocID = '" . cIn($lID) . "' AND StartDate >= '" . cIn(SYSDATE) . "'
						ORDER BY StartDate, TBD, StartTime, Title
						LIMIT " . cIn($limit));
		if(!hasRows($result)){
			echo '<p>'.$hc_lang_locations['NoEvents'].' <a href="'.CalRoot.'/index.php?com=submit" rel="nofollow">'.$hc_lang_locations['NoEventsLink'].'</a></p>';
			return 0;}
		
		$cnt = $date = 0;
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
				$stamp = date("Y-m-d\Th:i:00.0",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_locations['AllDay'] : $hc_lang_locations['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			echo '
			<li'.$hl.'><time datetime="'.$stamp.'">'.$time.'</time><a href="'.CalRoot . '/index.php?eID='.$row[0].'" rel="nofollow">'.cOut($row[1]).'</a></li>';
			++$cnt;
		}
		echo '</ul>';
	}
	/**
	 * Output menu icons w/links for the location map infowindow.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function gmap_infowindow_menu(){
		global $hc_cfg, $hc_lang_locations;
		echo '<a target="_blank" href="'.CalRoot.'/?lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuCalendar'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/icons/calendar.png" width="16" height="16" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/index.php?com=location&lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuProfile'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/icons/card.png" width="16" height="16" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/link/index.php?tID=3&oID=0&lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuWeather'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/icons/weather.png" width="16" height="16" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/link/index.php?tID=2&oID=0&lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuDirection'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/icons/car.png" width="16" height="16" /></a>'
		.'<a href="webcal://'.substr(CalRoot, 7).'/link/SaveLocation.php?lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuiCalendar'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/icons/ical.png" width="16" height="16" /></a>'
		.'<a target="_blank" href="'.CalRoot.'/rss/l.php?lID=\'+location[0]+\'" onmouseover="mnuMsg(\\\''.$hc_lang_locations['MnuRSS'].'\\\');" onmouseout="mnuMsg(\\\'\\\');"><img src="'.CalRoot.'/img/feed.png" width="16" height="16" /></a>'
		.'<div id="iw_msg">&nbsp;</div>';
	}
	/**
	 * Create JavaScript array() variable named "locations" with location entries for use with location Google map. Saves array to cache if cache not present.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function gmap_locations(){
		global $hc_cfg;		
		
		if(!file_exists(HCPATH.'/cache/lmap'.SYSDATE)){
			purge_cache_map();
			
			$cnt = 0;

			ob_start();
			$fp = fopen(HCPATH.'/cache/lmap'.SYSDATE, 'w');
			
			$result = doQuery("SELECT l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Country, l.Zip, l.Lat, l.Lon, COUNT(e.LocID), MIN(e.StartDate), l.URL, l.Phone
							FROM " . HC_TblPrefix . "locations l
								LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID)
							WHERE l.Lat IS NOT NULL AND l.Lon IS NOT NULL AND l.Lat != '' AND l.Lon != '' AND l.IsActive = 1 AND
								e.LocID > 0 AND e.IsActive = 1 AND e.IsApproved = 1 AND e.PkID IS NOT NULL AND e.StartDate >= '" . cIn(SYSDATE) . "'
							GROUP BY l.PkID, l.Name, l.Address, l.Address2, l.City, l.State, l.Country, l.Zip, l.Lat, l.Lon, l.URL, l.Phone
							HAVING COUNT(e.LocID) > 0
							ORDER BY l.Name");
			if(hasRows($result)){
				echo '
	var locations = [';
				while($row = mysql_fetch_row($result)){
					echo '
		["'.$row[0].'","'.cOut($row[1]).'","'.$row[8].'","'.$row[9].'","'.cOut($row[1]).'","'.cOut($row[2]).'","'.cOut($row[3]).'","'.cOut($row[4]).'","'.cOut($row[5]).'","'.cOut($row[6]).'","'.cOut($row[7]).'","'.$row[10].'","'.stampToDate($row[11], $hc_cfg[14]).'","'.(($row[12] != '' && $row[12] != 'http://') ? '1' : '0').'","'.cOut($row[13]).'"],';
					++$cnt;
				}
				echo '
	];';
			}
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/lmap'.SYSDATE);
	}
	/**
	 * Deletes all location map related cache files from cache directory.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function purge_cache_map(){
		if(count(glob(HCPATH.'/cache/lmap*')) > 0)
			foreach(glob(HCPATH.'/cache/lmap*') as $file){
				unlink($file);
			}
	}
	/**
	 * Retrieves interface text entry from location language file.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which language file array key
	 * @return string language file entry
	 */
	function location_lang($which){
		global $hc_lang_locations;
		
		if(!array_key_exists($which,$hc_lang_locations))
			return;
		
		return $hc_lang_locations[$which];
	}
?>