<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	
	if(isset($_GET['eID']) && !isset($_GET['com']) && is_numeric($_GET['eID'])){
		header("Location: index.php?com=detail&eID=" . $_GET['eID']);
	}//end if

	include('includes/include.php');
	if($hc_cfg50 == 1){
		include('includes/tawc.php');
	}//end if
	
	$hc_timeInput = cOut($hc_cfg31) == 12 ? 12 : 23;
	$hc_captchas = explode(",", cOut($hc_cfg32));
	
	define('HC_Login', 'components/LoginMenu.php');
	define('HC_Menu', 'components/Menu.php');
	define('HC_Core', 'components/Core.php');
	define('HC_Categories', 'components/CategoryList.php');
	define('HC_Language', 'components/Language.php');
	define('HC_Controls', 'components/ControlPanel.php');
	define('HC_Links', 'components/Links.php');
	define('HC_Billboard', 'components/Billboard.php');
	define('HC_Popular', 'components/Popular.php');
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
	
	if(!isset($_SESSION[$hc_cfg00 . 'hc_trail'])){$_SESSION[$hc_cfg00 . 'hc_trail'] = array();}?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $hc_lang_config['HTMLTemplate'];?>" lang="<?php echo $hc_lang_config['HTMLTemplate'];?>">
<head>
<?php
	if($hc_cfg7 == 1){?>
	<meta name="robots" content="all, index, follow" />
	<meta name="GOOGLEBOT" content="index, follow" />
<?php 
	} else {	?>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
<?php
	}//end if	?>
	<meta name="author" content="Refresh Web Development LLC" />
	<meta name="email" content="<?php echo CalAdminEmail;?>" />
	<meta name="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<meta http-equiv="expires" content="604800" />
	<meta http-equiv="MSSmartTagsPreventParsing" content="yes" />
	<meta name="description" content="<?php echo $hc_cfg6;?>" />
	<meta name="keywords" content="<?php echo $hc_cfg5;?>" />
	<link rel="bookmark" title="<?php echo CalName;?>" href="<?php echo CalRoot;?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/dateSelect.css" />
	<!--[if IE]><link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/heliosIE.css" /><![endif]-->
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<link rel="apple-touch-icon" href="<?php echo CalRoot;?>/images/appleIcon.png" type="image/png" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> All Events" href="<?php echo CalRoot;?>/rss.php" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Newest Events" href="<?php echo CalRoot;?>/rss.php?s=1" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Billboard Events" href="<?php echo CalRoot;?>/rss.php?s=3" />
	<link rel="alternate" type="application/rss+xml" title="<?php echo CalName;?> Most Popular Events" href="<?php echo CalRoot;?>/rss.php?s=2" />
	<link rel="search" type="application/opensearchdescription+xml" href="<?php echo CalRoot;?>/components/AddSearch.php" title="<?php echo CalName;?> Event Search" />
	<meta http-equiv="generator" content="Helios Calendar <?php echo $hc_cfg49;?>" /> <!-- leave this for stats -->
<?php
	if(isset($hc_cfg26) && $hc_cfg26 != '' && ((!isset($_GET['com']) && isset($_GET['lID']) && is_numeric($_GET['lID'])) || (isset($_GET['com']) && ($_GET['com'] == 'detail' || $_GET['com'] == 'location')))){	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo $hc_cfg52 . '/maps?file=api&amp;v=2&amp;key=' . $hc_cfg26;?>"></script>
<?php
	}//end if	?>