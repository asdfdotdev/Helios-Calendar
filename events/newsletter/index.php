<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	include('../includes/include.php');
	
	if(!isset($_SESSION['hc_trailn'])){$_SESSION['hc_trailn'] = array();}

	$hc_timeInput = cOut($hc_cfg31) == 12 ? 12 : 23;
	$hc_captchas = explode(",", cOut($hc_cfg32));
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/core.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');
	include('../includes/agents.php');

	$nID = (isset($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : '';

	$result = doQuery("SELECT Subject, SentDate, ArchiveContents FROM " . HC_TblPrefix . "newsletters WHERE md5(PkID) = '" . $nID . "' AND Status > 0");
	if(hasRows($result)){
		if(!preg_match($hc_bots,$_SERVER['HTTP_USER_AGENT']) && !in_array($nID,$_SESSION['hc_trailn'])){
			array_push($_SESSION['hc_trailn'], $nID);
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET ArchViews = ArchViews + 1 WHERE md5(PkID) = '" . $nID . "'");
		}//end if
		echo '<!DOCTYPE html';
		echo "\n" . 'PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"';
		echo "\n" . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $hc_lang_config['HTMLTemplate'] . '" lang="' . $hc_lang_config['HTMLTemplate'] . '"';
		echo ($hc_lang_config['Direction'] == 1) ? '>' : ' dir="rtl">';
		echo "\n" . '<head>';
		echo ($hc_cfg7 == 1) ? "\n\t" . '<meta name="robots" content="all, index, follow" />' : "\n\t" . '<meta name="robots" content="noindex, nofollow" />';
		echo "\n\t" . '<meta http-equiv="Content-Type" content="text/html; charset=' . $hc_lang_config['CharSet'] . '" />';
		echo "\n\t" . '<meta http-equiv="expires" content="never" />';
		echo "\n\t" . '<link rel="icon" href="' . CalRoot . '/images/favicon.png" type="image/png" />';
		echo "\n\t" . '<meta http-equiv="generator" content="Helios Calendar ' . $hc_cfg49 . '" /> <!-- leave this for stats -->';
		echo "\n\t" . '<meta name="description" content="' . CalName . ' ' . $hc_lang_register['MetaDesc'] . ' ' . stampToDate(mysql_result($result,0,1), $hc_cfg24) . '" />';
		echo "\n\n\t" . '<title>' . html_entity_decode(cOut(mysql_result($result,0,0))) . '</title>';
		echo "\n" . '</head>';
		echo (!stristr(mysql_result($result,0,2),'<body')) ? "\n" . '<body>' . "\n" . mysql_result($result,0,2) . "\n" . '</body>' : "\n" . mysql_result($result,0,2);
		echo "\n" . '</html>';
	} else {
		header("Location: " . CalRoot);
	}//end if
?>