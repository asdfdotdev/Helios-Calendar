<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	/** So Core Files Work*/
	define('isHC',true);
	/** Local Path to Helios Admin*/
	define('HCADMIN', dirname(__FILE__));
	/** Local Path to Helios Root*/
	define('HCPATH', dirname(HCADMIN.'../'));
	/** Includes Directory*/
	define('HCINC', '/inc');
		
	include_once(HCPATH . HCINC . '/config.php');
	include_once(HCPATH . HCINC . '/functions/shared.php');
	include_once(HCADMIN . HCINC . '/functions/admin.php');
	
	if(file_exists(HCPATH.'/setup')){
		echo 'Setup directory still present. Please delete it.';
		exit();}
	
	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME,$dbc);
	
	buildCache(6);
	buildCache(0);
	buildCache(3);
	include_once(HCPATH . '/cache/settings.php');
	
	header("X-Frame-Options: SAMEORIGIN");
	
	include_once(HCPATH.'/inc/cl_session.php');
	$session_a = new cl_session($hc_session_settings = [
			'name'      =>  $hc_cfg[200],
			'hash'			=>	1,
			'path'			=>	'/',
			'min'				=>	1,
			'max'				=>	10,
			'decoy'     =>  true]);
	$session_a->start();
	
	if(!isset($_SESSION['LangSet']))
		$_SESSION['LangSet'] = $hc_cfg[28];
	
	define('HCVersion',$hc_cfg[49]);
	/** Local Path to Active Language Pack*/
	define('HCLANG', HCPATH . HCINC . '/lang/' . $_SESSION['LangSet']);
	include_once(HCLANG . '/config.php');
	include_once(HCLANG . '/admin/core.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
	
	$hc_captchas = explode(",", cOut($hc_cfg[32]));
	$hc_time['input'] = cOut($hc_cfg[31]) == 12 ? 12 : 23;			/* $hc_timeInput */
	$hc_time['format'] = ($hc_time['input'] == 23) ? "H" : "h";		/* $hc_hourFormat | $hrFormat */
	$hc_time['minHr'] = ($hc_time['input'] == 23) ? 0 : 1;			/* minHr */
	
	$tz = explode(':',date("P"));
	$tz = ltrim(($tz[0]+$hc_cfg[35]).':'.$tz[1],"-+");
	$tz = (strlen($tz) < 5) ? '0' . $tz : $tz;
	$tz = ($tz[0]+$hc_cfg[35] > 0) ? '+' . $tz : '-' . $tz;
	
	$sys_stamp = mktime((date("G")+($hc_cfg[35])),date("i"),date("s"),date("m"),date("d"),date("Y"));
	/** Current System Date YYYY-MM-DD (Including Timezone Offset)*/
	define("SYSDATE", date("Y-m-d", $sys_stamp));
	/** Current System TIME HH:MM:SS (Including Timezone Offset)*/
	define("SYSTIME", date("H:i:s", $sys_stamp));
	/** Timezone UTC Offset String +hh:mm*/
	define("HCTZ", $tz);
?>