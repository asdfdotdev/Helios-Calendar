<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	header("X-Frame-Options: SAMEORIGIN");
	
	include_once(HCPATH.'/inc/cl_session.php');
	$session = new cl_session($hc_session_settings = [
			'name'      =>  $hc_cfg[201],
			'hash'			=>	1,
			'path'			=>	'/',
			'decoy'     =>  false]);
	$session->start();
	
	if(user_check_status() && (($_SESSION['UserLoginTime']+300) < date("U")))
		user_update_status($_SESSION['UserNetType'],$_SESSION['UserNetName'],$_SESSION['UserNetID'],$_SESSION['UserLoggedIn']);
	
	if(!isset($_SESSION['LangSet']))
		$_SESSION['LangSet'] = $hc_cfg[28];
	
	if(!isset($_SESSION['Theme']))
		$_SESSION['Theme'] = (!isset($_SERVER['HTTP_USER_AGENT']) || !preg_match("$hc_cfg[86]i",$_SERVER['HTTP_USER_AGENT'])) ?  $hc_cfg[83] : $hc_cfg[84];
	
	if(isset($_GET['theme']) && $_GET['theme'] != ''){
		$theme = cIn(strip_tags($_GET['theme']));
		if(is_dir(HCPATH.'/themes/'.$theme.'/'))
			$_SESSION['Theme'] = strtolower($theme);
	}

	if(!isset($_SESSION['hc_favCat']) && isset($_COOKIE[$hc_cfg[201] . '_fn']))
		$_SESSION['hc_favCat'] = cIn(strip_tags(base64_decode($_COOKIE[$hc_cfg[201] . '_fn'])));
	
	if(!isset($_SESSION['hc_favCity']) && isset($_COOKIE[$hc_cfg[201] . '_fc']))
		$_SESSION['hc_favCity'] = explode(',',cIn(strip_tags(base64_decode($_COOKIE[$hc_cfg[201] . '_fc']))));
	
	if(!isset($_SESSION['BrowseType']))
		$_SESSION['BrowseType'] = $hc_cfg[34];
	elseif(isset($_GET['b']) && is_numeric($_GET['b']))
		$_SESSION['BrowseType'] = cIn(strip_tags($_GET['b']));
	
	if(!isset($_SESSION['hc_trail']))
		$_SESSION['hc_trail'] = array();
	
	if(!isset($_SESSION['hc_traill']))
		$_SESSION['hc_traill'] = array();
	
	$favQ1 = (isset($_SESSION['hc_favCat']) && $_SESSION['hc_favCat'] != '') ? " AND ec.CategoryID in (" . $_SESSION['hc_favCat'] . ") " : "";
	$favQ2 = (isset($_SESSION['hc_favCity']) && $_SESSION['hc_favCity'] != '') ? " AND (e.LocationCity IN ('".implode("','",array_map('cIn',$_SESSION['hc_favCity']))."') OR l.City IN ('".implode("','",array_map('cIn',$_SESSION['hc_favCity']))."'))" : '';
	
	$hc_captchas = explode(",", cOut($hc_cfg[32]));
	$hc_time['input'] = cOut($hc_cfg[31]) == 12 ? 12 : 23;
	$hc_time['format'] = ($hc_time['input'] == 23) ? "H" : "h";
	$hc_time['minHr'] = ($hc_time['input'] == 23) ? 0 : 1;
?>