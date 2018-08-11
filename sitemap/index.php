<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');
	
	header('Content-type: application/xml; charset="utf-8"');
	
//	if(!file_exists(HCPATH.'/cache/sitemap_index')){
	if(1 == 1){
		ob_start();
		$fp = fopen(HCPATH.'/cache/sitemap_index', 'w');
		echo '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>' . CalRoot . '/sitemap/site.php</loc>
  </sitemap>';
		
		$result = doQuery("SELECT COUNT(PkID), MAX(PublishDate) FROM " . HC_TblPrefix . "events WHERE StartDate >= '" . cIn(SYSDATE) . "' AND IsActive = 1 AND IsApproved = 1");
		if(hasRows($result)){
			$last = (mysql_result($result,0,1) != '') ? '<lastmod>'.stampToDate(mysql_result($result,0,1), '%Y-%m-%d').'</lastmod>' : '';
			$x = 1;
			$stop = (mysql_result($result,0,0) > $hc_cfg[87]) ? ceil(mysql_result($result,0,0) / $hc_cfg[87]) : 1;
			
			while($x <= $stop){
				echo '
  <sitemap>
    <loc>'.CalRoot.'/sitemap/events.php?map='.$x.'</loc>
    '.$last.'
  </sitemap>';
				++$x;
			}
		}
			
		$result = doQuery("SELECT COUNT(l.PkID), MAX(l.LastMod) FROM " . HC_TblPrefix . "locations l WHERE l.IsActive = 1");
		
		if(hasRows($result)){
			$last = (mysql_result($result,0,1) != '') ? '<lastmod>'.stampToDate(mysql_result($result,0,1), '%Y-%m-%d').'</lastmod>' : '';
			$x = 1;
			$stop = (mysql_result($result,0,0) > $hc_cfg[87]) ? ceil(mysql_result($result,0,0) / $hc_cfg[87]) : 1;
			
			while($x <= $stop){
				echo '
  <sitemap>
    <loc>' . CalRoot . '/sitemap/locations.php?map='.$x.'</loc>
    '.$last.'
  </sitemap>';
				++$x;
			}	
		}
		
		echo '
</sitemapindex>';
		
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	} else {
		echo 'nope';
	}
	
	if(file_exists(HCPATH.'/cache/sitemap_index'))
		readfile(HCPATH.'/cache/sitemap_index');
?>