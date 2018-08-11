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
	post_only();
	
	$allowIndex = isset($_POST['indexing']) ? cIn($_POST['indexing']) : '0';
	$sitemap = isset($_POST['sitemap']) && trim($_POST['sitemap']) != '' ? cIn($_POST['sitemap']) : '100';
	$bots = isset($_POST['bots']) ? cIn($_POST['bots']) : '';
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $allowIndex . "' WHERE PkID = '7'");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $bots . "' WHERE PkID = '85'");
	doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . $sitemap . "' WHERE PkID = '87'");
	
	$ids = (isset($_POST['ids'])) ? $_POST['ids'] : array();
	$keywords = (isset($_POST['keywords'])) ? $_POST['keywords'] : array();
	$descriptions = (isset($_POST['descriptions'])) ? $_POST['descriptions'] : array();
	$titles = (isset($_POST['titles'])) ? $_POST['titles'] : array();
	$cnt = 0;

	foreach ($keywords as $val){
		doQuery("UPDATE " . HC_TblPrefix . "settingsmeta
				SET Keywords = '" . cIn(strip_tags($keywords[$cnt])) . "',
				Description = '" . cIn(strip_tags($descriptions[$cnt])) . "',
				Title = '" . cIn(strip_tags($titles[$cnt])) . "'
				WHERE PkID = '" . cIn(strip_tags($ids[$cnt])) . "'");
		++$cnt;
	}
	
	clearCache();
	
	header("Location: " . AdminRoot . "/index.php?com=seo&msg=1");
?>