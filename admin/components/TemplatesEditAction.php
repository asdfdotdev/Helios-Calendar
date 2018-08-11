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
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	if(!isset($_GET['dID'])){
		$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? cIn(strip_tags($_POST['tID'])) : '';
		$name = isset($_POST['name']) ? cIn($_POST['name']) : '';
		$content = isset($_POST['ex_data']) ? cIn($_POST['ex_data']) : '';
		$header = isset($_POST['ex_header']) ? cIn($_POST['ex_header']) : '';
		$footer = isset($_POST['ex_footer']) ? cIn($_POST['ex_footer']) : '';
		$ext = isset($_POST['ext']) ? cIn($_POST['ext']) : '';
		$typeID = isset($_POST['typeID']) ? cIn($_POST['typeID']) : '';
		$groupBy = isset($_POST['group']) ? cIn($_POST['group']) : '';
		$sortBy = isset($_POST['sort']) ? cIn($_POST['sort']) : '';
		$dateFormat = isset($_POST['dateFormat']) ? cIn($_POST['dateFormat']) : '';
		$cleanup = isset($_POST['cleanup']) ? cIn($_POST['cleanup']) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE PkID = '" . $tID . "' AND IsActive = 1");
		if(hasRows($result)){
			$msgID = 1;
			doQuery("UPDATE " . HC_TblPrefix . "templates
						SET Name = '" . $name . "',
							Content = '" . $content . "',
							Header = '" . $header . "',
							Footer = '" . $footer . "',
							Extension = '" . $ext . "',
							TypeID = '" . $typeID . "',
							GroupBy = '" . $groupBy . "',
							SortBy = '" . $sortBy . "',
							CleanUp = '" . $cleanup . "',
							DateFormat = '" . $dateFormat . "'
						WHERE PkID = '" . $tID . "'");
		} else {
			$msgID = 2;
			doQuery("INSERT INTO " . HC_TblPrefix . "templates(Name, Content, Header, Footer, Extension, TypeID, GroupBy, SortBy, DateFormat, CleanUp, IsActive)
					VALUES(	'" . $name . "','" . $content . "','" . $header . "','" . $footer . "','" . $ext . "',
							'" . $typeID . "','" . $groupBy . "','" . $sortBy . "','" . $dateFormat . "','" . $cleanup . "',1)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = (hasRows($result)) ? mysql_result($result,0,0) : 0;
		}
	} else {
		$msgID = 3;
		$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(strip_tags($_GET['dID'])) : 0;
		doQuery("UPDATE " . HC_TblPrefix . "templates SET IsActive = 0 WHERE PkID = '" . $dID . "'");
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocationName = 'Unknown', LocID = 0 WHERE LocID = '" . $dID . "'");
	}
	
	header('Location: ' . AdminRoot . '/index.php?com=exporttmplts&msg=' . $msgID);
?>