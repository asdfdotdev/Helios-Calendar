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
	
	$hourOffset = date("G") + ($hc_cfg[35]);
	$page = (isset($_GET['map']) && is_numeric($_GET['map'])) ? cIn(strip_tags($_GET['map'])) : 1;

	header('Content-type: application/xml; charset="utf-8"');
	
	if(!file_exists(HCPATH.'/cache/sitemap_events_'.$page)){
		
		$result = doQuery("SELECT PkID, PublishDate 
						FROM " . HC_TblPrefix . "events 
						WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . cIn(SYSDATE) . "'
						ORDER BY StartDate
						LIMIT $hc_cfg[87] OFFSET ".($hc_cfg[87] * ($page-1)));
		
		if(!hasRows($result)){
			header("Location: " . CalRoot . "/sitemap");
			exit();}
		
		ob_start();
		$fp = fopen(HCPATH.'/cache/sitemap_events_'.$page, 'w');
		
		echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		while($row = mysql_fetch_row($result)){
			$last = ($row[1] != '') ? '<lastmod>'.stampToDate($row[1], '%Y-%m-%d').'</lastmod>' : '';
			echo '
  <url>
    <loc>' . CalRoot . '/index.php?eID=' . $row[0] . '</loc>
    '.$last.'
  </url>';
		}
		echo '
</urlset>';
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}
	if(file_exists(HCPATH.'/cache/sitemap_events_'.$page))
		readfile(HCPATH.'/cache/sitemap_events_'.$page);
?>