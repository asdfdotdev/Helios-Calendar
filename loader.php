<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/** Local Path to Helios Root*/
	define('HCPATH', dirname(__FILE__));
	/** Includes Directory*/
	define('HCINC', '/inc');
	
	include_once(HCPATH . HCINC . '/config.php');
	include_once(HCPATH . HCINC . '/functions/misc.php');
	include_once(HCPATH . HCINC . '/functions/users.php');
	include_once(HCPATH . HCINC . '/functions/shared.php');
	
	$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
	mysql_select_db(DB_NAME,$dbc);
	
	buildCache(6);
	buildCache(0);
	buildCache(1);
	buildCache(3);
	
	include_once(HCPATH . '/cache/settings.php');
	include_once(HCPATH . '/cache/meta.php');
	include_once(HCPATH . HCINC . '/functions/session.php');
	
	$hc_cfg_named = array();
	if(defined('HC_Named'))
		include_once(HCPATH . '/cache/settings_named.php');
	
	/** Local Path to Active Language Pack*/
	define('HCLANG', HCPATH . HCINC . '/lang/' . $_SESSION['LangSet']);
	include_once(HCLANG . '/config.php');
	include_once(HCLANG . '/public/core.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
	define('HCVersion',$hc_cfg[49]);
	
	$sys_stamp = mktime((date("G")+($hc_cfg[35])),date("i"),date("s"),date("m"),date("d"),date("Y"));
	/** Current System Date YYYY-MM-DD (Includes Timezone Offset)*/
	define("SYSDATE", date("Y-m-d", $sys_stamp));
	/** Current System TIME HH:MM:SS in 24 hour format (Includes Timezone Offset)*/
	define("SYSTIME", date("H:i:s", $sys_stamp));
	
	$date = (isset($_GET['d'])) ? cIn(strip_tags($_GET['d'])) : SYSDATE;
	$d = explode('-',$date);
	$year = (isset($d[0]) && is_numeric($d[0])) ? $d[0] : NULL;
	$month = (isset($d[1]) && is_numeric($d[1])) ? $d[1] : NULL;
	$day = (isset($d[2]) && is_numeric($d[2])) ? $d[2] : NULL;
	
	if(!checkdate($month, $day, $year) || (strtotime(SYSDATE) > strtotime($date) && $hc_cfg[11] == 0) || $hc_cfg['Last'] < date("U",mktime(0,0,0,$month,1,$year))){
		$date = SYSDATE;
		$day = date('d', strtotime(SYSDATE));
		$month = date('m', strtotime(SYSDATE));
		$year = date('Y', strtotime(SYSDATE));}
	
	$tz = explode(':',date("P"));
	$tz = ltrim(($tz[0]+$hc_cfg[35]).':'.$tz[1],"-+");
	$tz = (strlen($tz) < 5) ? '0' . $tz : $tz;
	$tz = ($tz[0]+$hc_cfg[35] > 0) ? '+' . $tz : '-' . $tz;
	
	/** Current Browse Year*/
	define("HCYEAR", $year);
	/** Current Browse Month*/
	define("HCMONTH", $month);
	/** Current Browse Date YYYY-MM-DD*/
	define("HCDATE", $date);
	/** Timezone UTC Offset String +hh:mm*/
	define("HCTZ", $tz);
	
	if(!defined('isAction')){
		include_once(HCPATH . HCINC . '/functions/theme.php');
		$com = (isset($_GET['com'])) ? cIn(strip_tags($_GET['com'])) : '';
		$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn($_GET['eID']) : 0;
		$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? cIn($_GET['lID']) : 0;
		$crmbAdd = array();
		define('HCCOM',$com);
		
		switch($com){
			case 'location':
				include_once(HCLANG . '/public/locations.php');
				include_once(HCPATH . HCINC . '/functions/maps.php');
				include_once(HCPATH . HCINC . '/functions/locations.php');
				$crmbAdd[CalRoot.'/index.php?com=location'] = $hc_lang_core['location'];
				if($lID > 0){
					include_once(HCPATH . HCINC . '/functions/comments.php');
					define('HCCanURL',CalRoot.'/index.php?com=location&amp;lID='.$lID);
					load_theme_page('location.php');
				} elseif($hc_cfg[45] == 1 && ($hc_cfg[42] != '' && $hc_cfg[43] != '')){
					define('HCCanURL',CalRoot.'/index.php?com=location');
					load_theme_page('map.php');
				} else {
					go_home();}
				break;
			case 'searchresult':
				define('HCCanURL',CalRoot.'/index.php?com=searchresult');
				$crmbAdd[CalRoot.'/index.php?com=search'] = $hc_lang_core['search'];
				$crmbAdd['NULL'] = $hc_lang_core['searchresult'];
				include_once(HCLANG . '/public/search.php');
				include_once(HCPATH . HCINC . '/functions/forms.php');
				load_theme_page('form.php');
				break;
			case 'newsletter':
				if($hc_cfg[54] == 0)
					go_home();
				define('HCCanURL',CalRoot.'/index.php?com=newsletter');
				$crmbAdd[CalRoot.'/index.php?com=newsletter'] = $hc_lang_core['newsletter'];
				include_once(HCLANG . '/public/news.php');
				include_once(HCPATH . HCINC . '/functions/newsletter.php');
				load_theme_page('newsletter.php');
				break;
			case 'archive':
				if($hc_cfg[54] == 0)
					go_home();
				$n = (isset($_GET['n']) && is_numeric(strtotime($_GET['n']))) ? cIn(strip_tags($_GET['n'])) : date("Y-m-d",mktime(0,0,0,$month,1,$year));
				define('HCCanURL',CalRoot.'/index.php?com=archive&amp;n='.$n);
				$crmbAdd[CalRoot.'/index.php?com=newsletter'] = $hc_lang_core['newsletter'];
				$crmbAdd[CalRoot.'/index.php?com=archive&amp;n='.$n] = strftime('%B %Y',strtotime($n)).' '.$hc_lang_core['archive'];
				include_once(HCLANG . '/public/news.php');
				include_once(HCPATH . HCINC . '/functions/newsletter.php');
				load_theme_page('archive.php');
				break;
			case 'signup':
			case 'edit':
				$crmbAdd[CalRoot.'/index.php?com=newsletter'] = $hc_lang_core['newsletter'];
				$crmbAdd[CalRoot.'/index.php?com='.HCCOM] = $hc_lang_core[HCCOM];
				include_once(HCLANG . '/public/news.php');
				include_once(HCPATH . HCINC . '/functions/forms.php');
				load_theme_page('form.php');
				break;
			case 'series':
				$sID = (isset($_GET['sID'])) ? cIn(strip_tags($_GET['sID'])) : '';
				$result = doQuery("SELECT DISTINCT Title FROM " . HC_TblPrefix . "events
						WHERE SeriesID = '".$sID."' AND IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . SYSDATE . "'
						ORDER BY StartDate");
				if(hasRows($result)){
					define('HCCanURL',CalRoot.'/index.php?com=series&sID='.$sID);
					$crmbAdd[HCCanURL] = $hc_lang_core['Series'].' '.mysql_result($result,0,0);}
				include_once(HCLANG . '/public/event.php');
				include_once(HCPATH . HCINC . '/functions/events.php');
				include_once(HCPATH . HCINC . '/functions/maps.php');
				load_theme_page('series.php');
				break;
			case 'tools':
				$t = (isset($_GET['t'])) ? '&amp;t='.cIn(strip_tags($_GET['t'])) : '';
				if(isset($_GET['t']) && cIn(strip_tags($_GET['t'])) == 1 && $hc_cfg[106] == 0)
					go_home();
				define('HCCanURL',CalRoot.'/index.php?com=tools'.$t);
				include_once(HCLANG . '/public/tools.php');
				include_once(HCPATH . HCINC . '/functions/tools.php');
				load_theme_page('tools.php');
				break;
			case 'send':
				if($lID > 0){
					$result = doQuery("SELECT Name, Address, Address2, City, State, Zip, Country FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $lID . "'");
					if(hasRows($result)){
						define('HCCanURL',CalRoot.'/index.php?com='.HCCOM.'&amp;lID='.$lID);
						$crmbAdd[CalRoot.'/index.php?com=location'] = $hc_lang_core['location'];
						$crmbAdd[CalRoot.'/index.php?com=location&amp;lID='.$lID] = mysql_result($result,0,0);
						$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];}
				} elseif($eID > 0) {
					$result = doQuery("SELECT Title, StartDate, StartTime, TBD FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'".(($hc_cfg[126] == 0) ? " AND StartDate >= '" . SYSDATE . "'" : ""));
					if(hasRows($result)){
						define('HCCanURL',CalRoot.'/index.php?com='.HCCOM.'&amp;eID='.$eID);
						$crmbAdd[CalRoot.'/index.php?eID='.$eID] = mysql_result($result,0,0);
						$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];}
				}
				$title = cOut(mysql_result($result,0,0));
				include_once(HCLANG . '/public/'.HCCOM.'.php');
				include_once(HCPATH . HCINC . '/functions/forms.php');
				load_theme_page('form.php');
				break;
			case 'rsvp':
				$result = doQuery("SELECT e.Title,e.StartDate,e.StartTime,e.TBD,e.ContactName,e.ContactEmail,e.SeriesID,er.OpenDate,er.CloseDate,er.RegOption,
									MIN(e2.StartDate),MAX(e2.StartDate),MIN(e2.PkID)
								FROM " . HC_TblPrefix . "events e
									LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID)
									LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
								WHERE
									e.PkID = '" . $eID . "'
								GROUP BY e.Title,e.StartDate,e.StartTime,e.TBD,e.ContactName,e.ContactEmail,e.SeriesID,er.OpenDate,er.CloseDate, er.RegOption
								ORDER BY StartDate
								LIMIT 1");
				
				if($eID > 0 && hasRows($result)){
					define('HCCanURL',CalRoot.'/index.php?com=rsvp&amp;eID='.$eID);
					$crmbAdd[CalRoot.'/index.php?eID='.$eID] = mysql_result($result,0,0);
					$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];}
				$title = (hasRows($result)) ? cOut(mysql_result($result,0,0)) : '';
				include_once(HCLANG . '/public/rsvp.php');
				include_once(HCPATH . HCINC . '/functions/forms.php');
				load_theme_page('form.php');
				break;
			case 'submit':
				if($hc_cfg[1] == 0)
					go_home();
			case 'filter':
			case 'search':
				define('HCCanURL',CalRoot.'/index.php?com='.HCCOM);
				$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];
				include_once(HCLANG . '/public/'.HCCOM.'.php');
				include_once(HCPATH . HCINC . '/functions/forms.php');
				load_theme_page('form.php');
				break;
			case 'digest':
				if($hc_cfg[97] == 0)
					go_home();
				define('HCCanURL',CalRoot.'/index.php?com='.HCCOM);
				$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];
				include_once(HCLANG . '/public/pages.php');
				include_once(HCPATH . HCINC . '/functions/comments.php');
				include_once(HCPATH . HCINC . '/functions/maps.php');
				include_once(HCPATH . HCINC . '/functions/pages.php');
				load_theme_page('digest.php');
				break;
			case 'signin':
				if(!($hc_cfg[113]+$hc_cfg[114]+$hc_cfg[115]) > 0 || user_check_status())
					go_home();
				define('HCCanURL',CalRoot.'/index.php?com='.HCCOM);
				$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];
				include_once(HCLANG . '/public/user.php');
				load_theme_page('signin.php');
				break;
			case 'acc':
				if(!user_check_status())
					go_home();
				define('HCCanURL',CalRoot.'/index.php?com='.HCCOM);
				$crmbAdd[HCCanURL] = $hc_lang_core[HCCOM];
				include_once(HCLANG . '/public/user.php');
				load_theme_page('user.php');
				break;
			case 'detail':
			default:
				include_once(HCLANG . '/public/event.php');
				include_once(HCPATH . HCINC . '/functions/events.php');
				if($eID > 0){
					include_once(HCPATH . HCINC . '/functions/comments.php');
					include_once(HCPATH . HCINC . '/functions/maps.php');
					define('HCCanURL',CalRoot.'/index.php?eID='.$eID);
					load_theme_page('event.php');
				} else {
					if($lID > 0){
						$result = doQuery("SELECT Name FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $lID . "'");
						if(hasRows($result))
							$crmbAdd['NULL'] = $hc_lang_core['LocCal'].' '.mysql_result($result,0,0);
					}
					$m = (isset($_GET['m'])) ? '&amp;m=1' : '';
					$d = (isset($_GET['d'])) ? '?d='.HCDATE : '';
					define('HCCanURL',CalRoot.'/'.$d.$m);
					load_theme_page('index.php');
				}
		}
	}
?>