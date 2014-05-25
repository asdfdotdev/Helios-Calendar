<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');

	header('Content-type: application/xml; charset="utf-8"');
	echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>'.CalRoot.'</loc>
  </url>
  '.(($hc_cfg[45] == 1 ? '<url>
    <loc>'.CalRoot.'/index.php?com=location</loc>
  </url>':'')).'
  '.(($hc_cfg[1] == 1 ? '<url>
    <loc>'.CalRoot.'/index.php?com=submit</loc>
  </url>':'')).'
  <url>
    <loc>'.CalRoot.'/index.php?com=search</loc>
  </url>
  '.(($hc_cfg[54] == 1 ? '<url>
    <loc>'.CalRoot.'/index.php?com=newsletter</loc>
  </url>':'')).'
  <url>
    <loc>'.CalRoot.'/index.php?com=tools</loc>
  </url>
</urlset>';
?>