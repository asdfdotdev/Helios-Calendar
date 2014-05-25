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