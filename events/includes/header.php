<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	if(isset($_GET['eID']) && !isset($_GET['com']) && is_numeric($_GET['eID'])){
		header("Location: index.php?com=detail&eID=" . $_GET['eID']);
	}//end if

	include('includes/include.php');
	
	$hc_timeInput = cOut($hc_cfg31) == 12 ? 12 : 23;
	$hc_captchas = explode(",", cOut($hc_cfg32));
	
	include('components/Core.php');
	
	define('HC_Login', 'components/LoginMenu.php');
	define('HC_Menu', 'components/Menu.php');
	define('HC_Categories', 'components/CategoryList.php');
	define('HC_Language', 'components/Language.php');
	define('HC_Controls', 'components/ControlPanel.php');
	define('HC_Links', 'components/Links.php');
	define('HC_Billboard', 'components/Billboard.php');
	define('HC_Popular', 'components/Popular.php');
     define('HC_Newest', 'components/Newest.php');
	define('HC_GMap', 'components/GMap.php');
	
	if(isset($_GET['l']) && !isset($_GET['com'])){
		$catIDs = "0";
		$catID = explode(",", $_GET['l']);
		foreach ($catID as $val){
			if(is_numeric($val)){
				$catIDs = $catIDs . "," . cleanXMLChars(strip_tags(cIn($val)));
			}//end if
		}//end while
		if($catIDs != '0'){
			$_SESSION[$hc_cfg00 . 'hc_favCat'] = $catIDs;
		}//end if
	}//end if
	
	if(isset($_GET['c']) && !isset($_GET['com'])){
		$cityNames = "";
		$cityName = explode(",", $_GET['c']);
		foreach ($cityName as $val){
			if($val != ''){
				if($cityNames != ''){$cityNames .= ",";}
				$cityNames .= "'" . cleanXMLChars(strip_tags(cIn($val))) . "'";
			}//end if
		}//end for
		if($cityNames != ""){
			$_SESSION[$hc_cfg00 . 'hc_favCity'] = $cityNames;
		}//end if
	}//end if	
	
	if(!isset($_SESSION[$hc_cfg00 . 'hc_trail'])){$_SESSION[$hc_cfg00 . 'hc_trail'] = array();}
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/links.php');
	
	echo '<!DOCTYPE html';
	echo "\n" . 'PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"';
	echo "\n" . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	echo "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $hc_lang_config['HTMLTemplate'] . '" lang="' . $hc_lang_config['HTMLTemplate'] . '">';
	echo "\n" . '<head>';

	echo ($hc_cfg7 == 1) ? "\n\t" . '<meta name="robots" content="all, index, follow" />' : "\n\t" . '<meta name="robots" content="noindex, nofollow" />';
	echo "\n\t" . '<meta name="description" content="' . $hc_mDesc . '" />';
	echo "\n\t" . '<meta name="keywords" content="' . $hc_mKeys . '" />';

	echo "\n\t" . '<meta http-equiv="Content-Type" content="text/html; charset=' . $hc_lang_config['CharSet'] . '" />';
	echo "\n\t" . '<meta http-equiv="expires" content="604800" />';

	echo "\n\t" . '<link rel="stylesheet" type="text/css" href="' . CalRoot . '/css/helios.css" />';
	echo "\n\t" . '<link rel="stylesheet" type="text/css" href="' . CalRoot . '/css/dateSelect.css" />';
	echo "\n\t" . '<!--[if IE]><link rel="stylesheet" type="text/css" href="' . CalRoot . '/css/heliosIE.css" /><![endif]-->';

	echo "\n\t" . '<link rel="icon" href="' . CalRoot . '/images/favicon.png" type="image/png" />';
	echo "\n\t" . '<link rel="apple-touch-icon" href="' . CalRoot . '/images/appleIcon.png" type="image/png" />';

	echo "\n\t" . '<link rel="alternate" type="application/rss+xml" title="' . CalName . ' ' . $hc_lang_links['All'] . '" href="' . CalRoot . '/rss/" />';
	echo "\n\t" . '<link rel="alternate" type="application/rss+xml" title="' . CalName . ' ' . $hc_lang_links['Newest'] . '" href="' . CalRoot . '/rss/?s=1" />';
	echo "\n\t" . '<link rel="alternate" type="application/rss+xml" title="' . CalName . ' ' . $hc_lang_links['Featured'] . '" href="' . CalRoot . '/rss/?s=3" />';
	echo "\n\t" . '<link rel="alternate" type="application/rss+xml" title="' . CalName . ' ' . $hc_lang_links['Popular'] . '" href="' . CalRoot . '/rss/?s=2" />';
	echo "\n\t" . '<link rel="alternate" type="application/rss+xml" title="' . CalName . ' ' . $hc_lang_links['Discuss'] . '" href="' . CalRoot . '/rss/?s=4" />';
	
	echo "\n\t" . '<link rel="search" type="application/opensearchdescription+xml" href="' . CalRoot . '/components/AddSearch.php" title="' . CalName . ' Event Search" />';
	
	echo "\n\n\t" . '<meta http-equiv="generator" content="Helios Calendar ' . $hc_cfg49 . '" /> <!-- leave this for stats -->';

	if(isset($hc_cfg26) && $hc_cfg26 != '' && ((!isset($_GET['com']) && isset($_GET['lID']) && is_numeric($_GET['lID'])) || (isset($_GET['com']) && ($_GET['com'] == 'detail' || $_GET['com'] == 'location')))){
		echo "\n\n\t" . '<script language="JavaScript" type="text/JavaScript" src="' . $hc_cfg52 . '/maps?file=api&amp;v=2&amp;key=' . $hc_cfg26 . '"></script>';
	}//end if

	echo "\n\n\t" . '<title>' . $hc_pTitle . ' - ' . CalName . ', powered by Helios Calendar</title>';