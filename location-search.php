<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	include(HCLANG.'/public/search.php');
	
	action_headers();
	header('content-type: text/html; charset=' . $hc_lang_config['CharSet']);
	
	$resLimit = 10;
	$locName = (isset($_GET['q']) && $_GET['q'] !='') ? cIn(strip_tags($_GET['q'])) : '';
	$resOffset = (isset($_GET['o']) && is_numeric($_GET['o'])) ? cIn(strip_tags($_GET['o'])) : 0;
	$po = (isset($_GET['po']) && is_numeric($_GET['po'])) ? cIn(strip_tags($_GET['po'])) : 1;
	$eo = (isset($_GET['eo']) && is_numeric($_GET['eo'])) ? cIn(strip_tags($_GET['eo'])) : 0;
	
	if($locName != ''){
		if($eo == 0){
			$pQuery = ($po == 0) ? '' : ' AND IsPublic = 1';
			$result = doQuery("SELECT PkID, Name, Address, Address2, City, State, Zip, Country, Lat, Lon
							FROM " . HC_TblPrefix . "locations
							WHERE NAME LIKE('%" . $locName . "%')" . $pQuery . " AND IsActive = 1
							ORDER BY Name LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
			$resultP = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "locations WHERE NAME LIKE('%" . cIn($locName) . "%')" . $pQuery . " AND IsActive = 1");
		} else {
			$result = doQuery("SELECT DISTINCT(l.PkID), l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.Lat, l.Lon
							FROM " . HC_TblPrefix . "locations l
								LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID)
							WHERE l.NAME LIKE('%" . $locName . "%') AND l.IsActive = 1
								AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'
							ORDER BY Name LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
			$resultP = doQuery("SELECT COUNT(DISTINCT(l.PkID)) FROM " . HC_TblPrefix . "locations l LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID) WHERE NAME LIKE('%" . $locName . "%') AND l.IsPublic = 1 AND l.IsActive = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'");
		}
	}
	if(isset($result) && hasRows($result)){
		$x = 0;
		while($row = mysql_fetch_row($result)){
			$locAddress = buildAddress(htmlentities($row[2],ENT_QUOTES),htmlentities($row[3],ENT_QUOTES),htmlentities($row[4],ENT_QUOTES),htmlentities($row[5],ENT_QUOTES),htmlentities($row[6],ENT_QUOTES),htmlentities($row[7],ENT_QUOTES),$hc_lang_config['AddressType']);
			$locAddress = str_replace('<br />',',&nbsp;',$locAddress);
			$hl = ($x % 2 == 0) ? ' class="hl_frm"' : '';
			echo '
				<label'.$hl.' for="locValue_'.$row[0].'"><input name="locValue" id="locValue_'.$row[0].'" type="radio" onclick="setLocation('.$row[0].',\''.htmlentities(str_replace('\'','\\\'',$row[1]),ENT_QUOTES) . '\', 1);" />';
			echo ($hc_cfg[52] != '' && ($row[8] != '' && $row[9] != '')) ? '<a href="'.$hc_cfg[52].'maps?q='.$row[8].','.$row[9].'" target="_blank"><img src="'.CalRoot.'/img/icons/map.png" width="16" height="16" alt="' . $hc_lang_search['Map'] . '" /></a>' : '';
			echo ($po == 0 && isset($_SESSION['AdminLoggedIn'])) ? '<a href="' . AdminRoot . '/index.php?com=addlocation&amp;lID=' . $row[0] . '" target="_blank"><img src="' . AdminRoot . '/img/icons/edit.png" width="16" height="16" alt=""  /></a>' : '';
			echo '
				<span class="loc_name">'.htmlentities($row[1],ENT_QUOTES).'</span>
				<span class="loc_add">'.$locAddress.'</span></label>';
			++$x;
		}
		$pages = ceil(mysql_result($resultP,0,0)/$resLimit);
		if($pages > 1){
			echo '<div id="pages">';
			for($x = 0;$x < $pages;++$x){
				if($x % 20 == 0 && $x > 0){echo "<br /><br />";}elseif($x > 0){echo "&nbsp;|&nbsp;";}
				echo ($resOffset != $x) ? '<a href="javascript:;" onclick="searchLocations('.$x.');">'.($x + 1).'</a>' : '<b>'.($x + 1).'</b>';
			}
			echo '</div>';
		}
	} else {
		echo '<span class="no_loc">' . $hc_lang_search['NoLocation'] . '</span>';
	}