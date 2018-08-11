<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
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
		
		$result = doQuery("SELECT PkID, Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, Lat, Lon, ShortURL, LastMod, Image From " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "' AND IsActive = 1");
		
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
		    'CommentsURL'		=>	CalRoot.'/index.php?com=location&amp;lID='.$lID,
		    'CommentsID'			=>	$lID,
		    'Link_This'		=>	CalRoot.'/index.php?com=location&amp;lID='.$lID,
		    'Link_URL'			=>	(mysql_result($result,0,"URL") != '' && mysql_result($result,0,"URL") != 'http://') ? CalRoot . '/link/index.php?tID=4&amp;oID='.mysql_result($result,0,"PkID") : NULL,
		    'Link_Weather'		=>	CalRoot . '/link/index.php?tID=3&amp;oID=0&amp;lID='.mysql_result($result,0,"PkID"),
		    'Link_Directions'	=>	CalRoot . '/link/index.php?tID=2&amp;oID=0&amp;lID='.mysql_result($result,0,"PkID"),
		    'Link_Calendar'		=>	CalRoot.'/index.php?lID='.$lID,
		    'LastMod'			=>	mysql_result($result,0,"LastMod"),
		    'Image'			=>	mysql_result($result,0,"Image"),
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
				$stamp = date("Y-m-d\Th:i:00",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
			} else {
				$time = ($row[5] == 1) ? $hc_lang_locations['AllDay'] : $hc_lang_locations['TBA'];
				$stamp = date("Y-m-d",strtotime($row[2]));}
			echo '
			<li'.$hl.' itemscope itemtype="http://schema.org/Event"><time itemprop="startDate" datetime="'.$stamp.'">'.$time.'</time><a itemprop="url" href="'.CalRoot . '/index.php?eID='.$row[0].'"><span itemprop="name">'.cOut($row[1]).'</span></a></li>';
			++$cnt;
		}
		echo '</ul>';
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
	/**
	 * Output location specific RSS feed link. Links to individual location RSS feed when RSS feeds are active.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $lID ID# of the location for the feed being linked to.
	 * @return void
	 * 
	 */
	function location_rss_link($lID){
		global $hc_cfg;
		
		if($hc_cfg[106] == 0 || !is_numeric($lID) || $lID < 1)
			return 0;
		
		echo '
		<a href="'.CalRoot.'/rss/l.php?lID='.$lID.'" class="icon rss loc_rss" target="_blank" rel="nofollow"></a>';
	}
?>