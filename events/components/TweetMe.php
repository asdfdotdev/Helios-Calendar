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
	include('../includes/include.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/comment.php');

     $eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn($_GET['eID']) : 0;
     $tweet = (isset($_GET['tweet'])) ? substr(utf8_ninjadecode(strip_tags(str_replace("\r","",str_replace("\n"," ",cOut($_GET['tweet']))))),0,109) : '';
	$going = (isset($_GET['going']) && $_GET['going'] == 1) ? ' ' . $hc_lang_comment['ImGoing'] : '';
     $hash = ($hc_cfg59 != '') ? ' ' . $hc_cfg59 : ' ';

	if($eID > 0 && $tweet != ''){
          $shortLink = CalRoot . "/index.php?com=detail&eID=" . $eID;
		require_once('../includes/api/bitly/ShortenURL.php');
		
          if(isset($_SESSION['hc_OpenIDLoggedIn'])){
               doQuery("UPDATE " . HC_TblPrefix . "events SET Tweetments = (Tweetments + 1) WHERE PkID = '" . $eID . "'");
          }//end if

		header('Location: http://twitter.com/home?status=' . urlencode($tweet . ' ' . $shortLink . ' ' . $hash . $going));
     } else {
          header('Location: ' . CalRoot);
     }//end if
?>