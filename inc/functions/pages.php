<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Output digest welcome message.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @return void
	 */
	function digest_welcome(){
		global $hc_cfg;
		
		echo cOut($hc_cfg[98]);
	}
	/**
	 * Output JavaScript required for digest newest venue location map. Uses get_map_js().
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $pin_icon URL of pushpin icon image file.
	 * @return void
	 */
	function digest_venue_map_js($pin_icon = ''){
		global $hc_cfg;
		
		$pin_icon = ($pin_icon == '') ? CalRoot.'/img/pins/pushpin.png' : $pin_icon;
		$result = doQuery("SELECT PkID, Lat, Lon FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 AND Lat != '' AND Lon != '' ORDER BY LastMod DESC LIMIT 1");
		
		if(hasRows($result))
			get_map_js(mysql_result($result,0,1), mysql_result($result,0,2), 1, $pin_icon, 1, CalRoot.'/index.php?com=location&lID='.mysql_result($result,0,0));
		else
			echo '
	<style>
		#map_canvas_single {display:none;}
	</style>';
	}
	/**
	 * Output digest event list. Events are listed in decending order by last modified date. New events are defined by admin console "New For" setting.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $size max number of events to include in the list (list may be smaller depending on settings/available events).
	 * @param binary $updated include updated events in list. (0 = new events only, 1 = new & recently updated events).
	 * @param string $dateFormat date format string, accepts any strftime format parameters.
	 * @return void
	 */
	function digest_event_list($size,$updated,$dateFormat = ''){
		global $hc_cfg, $hc_lang_pages;
		
		if(!file_exists(HCPATH.'/cache/digest_'.SYSDATE.'_e')){
			ob_start();
			$fp = fopen(HCPATH.'/cache/digest_'.SYSDATE.'_e', 'w');
			
			$cnt = 1;
			$dateFormat = ($dateFormat == '') ? $hc_cfg[24] : $dateFormat;
			$uQuery = ($updated == 0) ? " AND DATEDIFF('".SYSDATE."',e.PublishDate) <= ".$hc_cfg[99] : '';

			$result = doQuery("SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.TBD, e.LastMod, DATEDIFF('".SYSDATE."',e.PublishDate) as Age, e.SeriesID
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
							WHERE e.StartDate >= '".SYSDATE."' AND e.IsActive = 1 AND e.IsApproved = 1 AND SeriesID IS NULL".$uQuery."
							UNION
							SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.TBD, e.LastMod, DATEDIFF('".SYSDATE."',e.PublishDate) as Age, e.SeriesID 
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
								LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID AND e2.StartDate BETWEEN '".SYSDATE."' AND '2100-01-01' AND e.StartDate > e2.StartDate)
							WHERE e2.StartDate IS NULL AND e.StartDate >= '".SYSDATE."' AND e.IsActive = 1 AND e.IsApproved = 1 AND e.SeriesID IS NOT NULL".$uQuery."
							GROUP BY e.SeriesID,e.PkID,e.Title,e.StartDate,e.StartTime,e.EndTime,e.TBD, e.LastMod, e.PublishDate
							ORDER BY LastMod DESC
							LIMIT " . $size);
			if(!hasRows($result)){
				echo '
			<ul class="events"><li>' . $hc_lang_pages['NoEvents'] . '</li></ul>';
				return 0;}

			echo '
				<ul class="events">';
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 0) ? 'hl' : '';
				$new = ($row[6] <= $hc_cfg[99]) ? ' new' : '';
				$series = ($row[7] != '') ? ' series' : '';
				if($row[5] == 0){
					$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
					$time .= ($row[4] != '') ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
					$stamp = date("Y-m-d\Th:i:00",strtotime($row[2] . trim(' '.$row[3]))) . HCTZ;
				} else {
					$time = ($row[5] == 1) ? $hc_lang_pages['AllDay'] : $hc_lang_pages['TBA'];
					$stamp = date("Y-m-d",strtotime($row[2]));}
				echo '
				<li class="'.$hl.$new.$series.'" itemscope itemtype="http://schema.org/Event"><time itemprop="startDate" datetime="'.$stamp.'">'.stampToDate($row[2], $dateFormat).'</time><a itemprop="url" href="'.CalRoot . '/index.php?eID='.$row[0].'"><span itemprop="name">'.cOut($row[1]).'</span></a></li>';
				++$cnt;
			}
			echo '
				</ul>';
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/digest_'.SYSDATE.'_e');
	}
	/**
	 * Output digest location list. Locations listed in decending order by last modified date.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $size max number of locations to include in the list (list may be smaller depending on settings/available locations).
	 * @return void
	 */
	function digest_location_list($size){
		global $hc_cfg, $hc_lang_pages;
		
		if(!file_exists(HCPATH.'/cache/digest_'.SYSDATE.'_l')){
			ob_start();
			$fp = fopen(HCPATH.'/cache/digest_'.SYSDATE.'_l', 'w');
			
			$cnt = 1;
			$result = doQuery("SELECT DISTINCT PkID, Name, Address, Address2, City, State, Zip, Country, LastMod
							FROM " . HC_TblPrefix . "locations
							WHERE IsActive = 1
							ORDER BY LastMod DESC
							LIMIT " . $size);
			if(!hasRows($result)){
				echo '
			<ul class="locations"><li>' . $hc_lang_pages['NoLocations'] . '</li></ul>';
				return 0;}

			echo '
				<ul class="locations">';
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
				echo '
				<li'.$hl.'><span>'.$row[4].'</span><a href="'.CalRoot . '/index.php?com=location&amp;lID='.$row[0].'">'.cOut($row[1]).'</a></li>';
				++$cnt;
			}
			echo '
				</ul>';
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/digest_'.SYSDATE.'_l');
	}
	/**
	 * Output digest newsletters list. Newsletters listed in decending order by sent date.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $size max number of newsletters to include in the list (list may be smaller depending on settings/available newsletters).
	 * @return void
	 */
	function digest_newsletter_list($size,$dateFormat = ''){
		global $hc_cfg, $hc_lang_pages;
		
		if(!file_exists(HCPATH.'/cache/digest_'.SYSDATE.'_n')){
			ob_start();
			$fp = fopen(HCPATH.'/cache/digest_'.SYSDATE.'_n', 'w');
			
			$result = doQuery("SELECT PkID, Subject, SentDate FROM " . HC_TblPrefix . "newsletters WHERE Status > 0 AND IsArchive = 1 AND IsActive = 1 AND ArchiveContents != '' ORDER BY SentDate DESC LIMIT " . $size);
			if(!hasRows($result)){
				echo '
			<ul class="newsletters"><li>' . $hc_lang_pages['NoNewsletters'] . '</li></ul>';
				return 0;}

			$cnt = 1;
			$dateFormat = ($dateFormat == '') ? $hc_cfg[24] : $dateFormat;

			echo '
			<ul class="newsletters">';
			while($row = mysql_fetch_row($result)){
				$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
				echo '<li'.$hl.'><time datetime="'.stampToDate($row[2],'%Y-%m-%d').'">'.stampToDate($row[2],$dateFormat).'</time><a href="'.CalRoot.'/newsletter/index.php?n='.md5($row[0]).'" target="_blank">'.cOut($row[1]).'</a></li>';
				++$cnt;
			}
			echo '
			</ul>';
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/digest_'.SYSDATE.'_n');
	}
?>