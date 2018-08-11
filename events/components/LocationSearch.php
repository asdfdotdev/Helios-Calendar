<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/search.php');
	
	$resLimit = 10;
	$locName = (isset($_GET['q']) && $_GET['q'] !='') ? $_GET['q'] : '';
	$resOffset = (isset($_GET['o']) && is_numeric($_GET['o'])) ? $_GET['o'] : 0;
	$np = (isset($_GET['np']) && is_numeric($_GET['np'])) ? $_GET['np'] : 0;
	
	if($locName != ''){
		if(!isset($_GET['a'])){
			$pubOnly = ($np == 1) ? "" : " AND IsPublic = 1";
			
			$result = doQuery("SELECT PkID, Name, Address, Address2, City, State, Zip, Country
								FROM " . HC_TblPrefix . "locations
								WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%')" . $pubOnly . " AND IsActive = 1
								ORDER BY Name 
								LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
			
			$resultP = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "locations WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%')" . $pubOnly . " AND IsActive = 1");
		} else {
			$result = doQuery("SELECT DISTINCT(l.PkID), l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country
								FROM " . HC_TblPrefix . "locations l
									LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID)
								WHERE l.NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND l.IsActive = 1
									AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'
								ORDER BY Name 
								LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
			$resultP = doQuery("SELECT COUNT(DISTINCT(l.PkID)) FROM " . HC_TblPrefix . "locations l LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID) WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND l.IsPublic = 1 AND l.IsActive = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'");
		}//end if
	}//end if
	
	if(isset($result) && hasRows($result)){
		$x = 0;
		while($row = mysql_fetch_row($result)){
			echo '<label style="clear:both;">&nbsp;</label>';
			echo '<label class="locSearch' . ($x % 2). '" for="locValue_' . $row[0] . '"><input name="locValue" id="locValue_' . $row[0] . '" type="radio" onclick="setLocation(' . $row[0] . ',\'' . htmlentities(str_replace('\'','\\\'',$row[1]),ENT_QUOTES) . '\');" class="noBorderIE" />';
			echo '<b>' . htmlentities($row[1],ENT_QUOTES) . '</b>';
			echo ($row[2] != '' && $row[4] != '' && $row[5] != '') ? '&nbsp;-&nbsp;<a href="' . CalRoot . '/link/index.php?tID=2&oID=24&lID=' . $row[0] . '" class="eventMain" target="_blank">' . $hc_lang_search['Map'] . '</a>' : '';
			echo '<br />';
			$locAddress = buildAddress(htmlentities($row[2],ENT_QUOTES),htmlentities($row[3],ENT_QUOTES),htmlentities($row[4],ENT_QUOTES),htmlentities($row[5],ENT_QUOTES),htmlentities($row[6],ENT_QUOTES),htmlentities($row[7],ENT_QUOTES),0,$hc_lang_config['AddressType']);
			$locAddress = str_replace('<br />',', ',$locAddress);
			echo $locAddress . '</label><br />';
			++$x;
		}//end if
		
		$pages = ceil(mysql_result($resultP,0,0)/$resLimit);
		echo "<div style=\"clear:both;padding-top:10px;\" align=\"center\">";
		for($x = 0;$x < $pages;++$x){
			if($x % 20 == 0 && $x > 0){echo "<br /><br />";}elseif($x > 0){echo "&nbsp;|&nbsp;";}
			if($resOffset != $x){
				echo "<a href=\"javascript:;\" onclick=\"searchLocations(" . $x . ");\" class=\"eventMain\">" . ($x + 1) . "</a>";
			} else {
				echo "<b>" . ($x + 1) . "</b>";
			}//end if
		}//end if
		echo "</div><br />";
	} else {
		echo "<span style=\"color:#DC143C;font-weight:bold;\">" . $hc_lang_search['NoLocation'] . "</span>";
	}//end if