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
	include('../loader.php');
	include(HCLANG.'/public/syndication.php');
	
	$sID = (isset($_GET['s']) && is_numeric($_GET['s']) && $_GET['s'] <= 3) ? cIn($_GET['s']) : 0;
	$cache = HCPATH.'/cache/js'.date("Ymd").'_'.$sID;
	
	if(!file_exists($cache)){
		if(count(glob(HCPATH.'/cache/js*_'.$sID)) > 0)
			foreach(glob(HCPATH.'/cache/js*_'.$sID) as $file){
				unlink($file);
			}
		ob_start();
		$fp = fopen($cache, 'w');
		$query = "SELECT DISTINCT e.PkID, e.Title, e.StartDate, e.StartTime, e.TBD
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
				WHERE e.IsActive = 1 AND 
					e.IsApproved = 1 AND 
					e.StartDate >= '".date("Y-m-d", mktime((date("G")+($hc_cfg[35])),0,0,date("m"),date("d"),date("Y")))."' AND c.IsActive = 1 ";
		switch($sID){
			case 0:
				$query .= " ORDER BY e.StartDate, e.TBD ASC, e.StartTime, e.Title LIMIT " . $hc_cfg[2];
				break;
			case 1:
				$query .= " ORDER BY e.PublishDate DESC, e.StartDate LIMIT " . $hc_cfg[2];
				break;
			case 2:
				$query .= " ORDER BY e.Views DESC, e.StartDate LIMIT " . $hc_cfg[2];
				break;
			case 3:
				$query .= " AND e.IsBillboard = 1 ORDER BY e.StartDate, e.TBD, e.Title LIMIT " . $hc_cfg[2];
				break;
		}
		$result = doQuery($query);
		if(hasRows($result)){
			$x = 1;
			fwrite($fp, 'var hc_events = {');
			while($row = mysql_fetch_row($result)){
				if($row[4] == 0)
					$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
				else
					$time = ($row[4] == 1) ? $hc_lang_synd['AllDay'] : $hc_lang_synd['TBA'];
				fwrite($fp, "\n\t". '"'.$x.'":{"id":"'.$row[0].'","title":"'.$row[1].'","date":"'.stampToDate($row[2],$hc_cfg[14]).'","time":"'.$time.'"},');
				++$x;
			}
			fwrite($fp, "\n};");
		}
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}
	include($cache);
	
	echo (isset($_GET['z']) && is_numeric($_GET['z'])) ? '
var stop = '.cIn($_GET['z']) : '';
	echo (isset($_GET['t']) && is_numeric($_GET['t'])) ? '
var showtime = '.cIn($_GET['t']) : '';
	echo '
var date = "";';

	echo '
document.write("<ul>");
for(var i in hc_events){
	if(stop < i)
		break;
	
	if(hc_events[i].date != date){
		date = hc_events[i].date;
		document.write("<li class=\"date\">" + hc_events[i].date + "</li>");
	}
	document.write("<li>");
	document.write("<a href=\"'.CalRoot.'/?eID=" + hc_events[i].id + "\">" + hc_events[i].title + "</a>");
	if(showtime == 1)
		document.write("<time>" + hc_events[i].time + "</time>");
	document.write("</li>");
}
document.write("<li class=\"date\">'.$hc_lang_synd['Powered'].' <a href=\"'.CalRoot.'\">'.CalName.'</a></li>");
document.write("</ul>");';
?>