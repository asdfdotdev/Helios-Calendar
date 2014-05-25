<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');

	$page = (isset($_GET['map']) && is_numeric($_GET['map'])) ? cIn(strip_tags($_GET['map'])) : 1;

	header('Content-type: application/xml; charset="utf-8"');	
	
	if(!file_exists(HCPATH.'/cache/sitemap_locations_'.$page)){
		$result = doQuery("SELECT l.PkID, MAX(e.PublishDate) as eCount
						FROM " . HC_TblPrefix . "locations l
							LEFT JOIN " . HC_TblPrefix . "events e ON (l.PkID = e.LocID AND e.IsActive = 1 AND e.IsApproved = 1)
						WHERE l.IsActive = 1 GROUP BY l.PkID 
						ORDER BY Name
						LIMIT $hc_cfg[87] OFFSET ".($hc_cfg[87] * ($page-1)));
		if(!hasRows($result)){
			header("Location: " . CalRoot . "/sitemap");
			exit();}
		
		ob_start();
		$fp = fopen(HCPATH.'/cache/sitemap_locations_'.$page, 'w');
		
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

	if(file_exists(HCPATH.'/cache/sitemap_locations_'.$page))
		readfile(HCPATH.'/cache/sitemap_locations_'.$page);
?>