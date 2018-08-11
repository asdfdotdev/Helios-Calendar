<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/search.php');
	
	$resLimit = 10;
	
	$locName = "";
	if(isset($_GET['q']) && $_GET['q'] !=''){
		$locName = $_GET['q'];
	}//end if
	
	$resOffset = 0;
	if(isset($_GET['o']) && is_numeric($_GET['o'])){
		$resOffset = $_GET['o'];
	}//end if
	
	
	if(!isset($_GET['a'])){
		$result = doQuery("SELECT PkID, Name, Address, Address2, City, State, Country
							FROM " . HC_TblPrefix . "locations
							WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND IsPublic = 1 AND IsActive = 1
							ORDER BY Name 
							LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
		$resultP = doQuery("SELECT COUNT(PkID) FROM " . HC_TblPrefix . "locations WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND IsPublic = 1 AND IsActive = 1");
	} else {
		$result = doQuery("SELECT DISTINCT(l.PkID), l.Name, l.Address, l.Address2, l.City, l.State, l.Country 
							FROM " . HC_TblPrefix . "locations l
								LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID)
							WHERE l.NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND l.IsPublic = 1 AND l.IsActive = 1
								AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'
							ORDER BY Name 
							LIMIT " . $resLimit . " OFFSET " . ($resOffset * $resLimit));
		$resultP = doQuery("SELECT COUNT(DISTINCT(l.PkID)) FROM " . HC_TblPrefix . "locations l LEFT JOIN " . HC_TblPrefix . "events e ON (e.LocID = l.PkID) WHERE NAME LIKE('%" . cleanSpecialChars(cIn($locName)) . "%') AND l.IsPublic = 1 AND l.IsActive = 1 AND e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . date("Y-m-d") . "'");
	}//end if
	if(hasRows($result)){
		$x = 0;
		while($row = mysql_fetch_row($result)){
			echo "<label style=\"clear:both;\">&nbsp;</label>";
			echo "<label class=\"locSearch" . ($x % 2). "\" for=\"locValue_" . $row[0] . "\"><input name=\"locValue\" id=\"locValue_" . $row[0] . "\" type=\"radio\" onclick=\"setLocation(" . $row[0] . ");\" class=\"noBorderIE\" /><b>" . htmlentities($row[1],ENT_QUOTES) . "</b><br />" . htmlentities($row[2],ENT_QUOTES) . " " . htmlentities($row[3],ENT_QUOTES) . ", " . htmlentities($row[4],ENT_QUOTES) . ", " . htmlentities($row[5],ENT_QUOTES) . " " . htmlentities($row[6],ENT_QUOTES) . "</label><br />";
			$x++;
		}//end if
		
		$pages = ceil(mysql_result($resultP,0,0)/$resLimit);
		echo "<div style=\"clear:both;padding-top:10px;\" align=\"center\">";
		for($x = 0;$x < $pages;$x++){
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