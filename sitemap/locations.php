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
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');

	header('Content-type: application/xml; charset="utf-8"');
	$hourOffset = date("G") + ($hc_cfg[35]);
	
	if(!file_exists(HCPATH.'/cache/sitemap_locations')){
		$result = doQuery("SELECT l.PkID, MAX(e.PublishDate), COUNT(e.LocID) as eCount
						FROM " . HC_TblPrefix . "locations l
							LEFT JOIN " . HC_TblPrefix . "events e ON (l.PkID = e.LocID AND e.IsActive = 1 AND e.IsApproved = 1)
						WHERE l.IsActive = 1 GROUP BY l.PkID HAVING COUNT(e.LocID) > 0 ORDER BY Name");
		if(!hasRows($result)){
			header("Location: " . CalRoot . "/sitemap");
			exit();}
		
		ob_start();
		$fp = fopen('../cache/sitemap_locations', 'w');
		
		echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		while($row = mysql_fetch_row($result)){
			$last = ($row[1] != '') ? '<lastmod>'.stampToDate($row[1], '%Y-%m-%d').'</lastmod>' : '';
			echo '
  <url>
    <loc>' . CalRoot . '/index.php?com=location&amp;lID=' . $row[0] . '</loc>
    '.$last.'
    <priority>1.0</priority>
  </url>';
		}
		echo '
</urlset>';
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}

	if(file_exists(HCPATH.'/cache/sitemap_locations'))
		readfile(HCPATH.'/cache/sitemap_locations');
?>