<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');

	header('Content-type: application/xml; charset="utf-8"');
	$hourOffset = date("G") + ($hc_cfg35);

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo "\n" . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	if(!file_exists(realpath('../cache/sitemap_eventsmm.php'))){
		ob_start();
		$fp = fopen('../cache/sitemap_eventsm.php', 'w');

		$result = doQuery("SELECT PkID, PublishDate FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND StartDate >= '" . date("Y-m-d") . "' ORDER BY StartDate");
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
				$parts = explode(' ', $row[1]);
				$dateParts = (isset($parts[0])) ? explode('-', $parts[0]) : '';
				$timeParts = (isset($parts[1])) ? explode(':', $parts[1]) : '';

				echo "\n\t" . '<url>';
				echo "\n\t\t" . '<loc>' . CalRoot . '/m/details.php?eID=' . $row[0] . '</loc>';
				echo ($dateParts != '' && $timeParts != '') ? "\n\t\t" . '<lastmod>' . date("Y-m-d",mktime(($timeParts[0] + $hourOffset),$timeParts[1],$timeParts[2],$dateParts[1],$dateParts[2],$dateParts[0])) . '</lastmod>' : '';
				echo "\n\t\t" . '<priority>1.0</priority>';
				echo "\n\t" . '</url>';
			}//end while
		}//end if
		echo "\n" . '</urlset>';

		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}//end if

	if(file_exists('../cache/sitemap_eventsm.php')){include('../cache/sitemap_eventsm.php');}

;?>