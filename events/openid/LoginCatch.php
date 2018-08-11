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
/*
	This file includes source code released by JanRain Inc. that has been
	modify by Refresh Web Development, LLC. for use in Helios Calendar
	Under License: http://www.apache.org/licenses/LICENSE-2.0 Apache
	Original Available At: http://www.janrain.com/openid-enabled
*/
	$isAction = 1;
	include('../includes/include.php');
	include('LoginShared.php');

	header('X-XRDS-Location: ' . CalRoot . '/openid/xrds.php');

	$consumer = getConsumer();
     $return_to = getReturnTo();
     $response = $consumer->complete($return_to);

     if ($response->status == Auth_OpenID_CANCEL) {
          header('Location: ' . CalRoot . '/index.php?msg=3');
          exit();
     } else if ($response->status == Auth_OpenID_FAILURE) {
          header('Location: ' . CalRoot . '/index.php?com=login&msg=3');
          exit();
     } else if ($response->status == Auth_OpenID_SUCCESS) {
          $openid = $response->getDisplayIdentifier();
          $esc_identity = htmlspecialchars($openid, ENT_QUOTES);

          $result = doQuery("SELECT * FROM " . HC_TblPrefix . "oidusers WHERE Identity = '" . cIn($esc_identity) . "' AND IsActive > 0");
          if(hasRows($result)){
          if(mysql_result($result,0,7) == 1){
               doQuery("UPDATE " . HC_TblPrefix . "oidusers SET LoginCnt = LoginCnt + 1,  LastLogin = '" . date("Y-m-d H:i:s") . "', LastLoginIP = '" . $_SERVER["REMOTE_ADDR"] . "' WHERE Identity = '" . cIn($esc_identity) . "'");
          } else {
               header('Location: ' . CalRoot . '/index.php?msg=2');
               exit();
          }//end if
          } else {
          $shortName = str_replace(array('http://','https://'), '', $esc_identity);
          $shortName = (substr($shortName, -1) == '/') ? substr($shortName,0,-1) : $shortName;
          doQuery("INSERT INTO " . HC_TblPrefix . "oidusers(Identity, ShortName, LoginCnt, FirstLogin, LastLogin, LastLoginIP, IsActive) VALUES('" . cIn($esc_identity) . "','" . cIn($shortName) . "',1,'" . date("Y-m-d h:m:s") . "','" . date("Y-m-d h:m:s") . "','" . $_SERVER["REMOTE_ADDR"] . "', 1)");
          $result = doQuery("SELECT * FROM " . HC_TblPrefix . "oidusers WHERE Identity = '" . cIn($esc_identity) . "'");
          }//end if

          $_SESSION['hc_OpenIDPkID'] = mysql_result($result,0,0);
          $_SESSION['hc_OpenIDShortName'] = mysql_result($result,0,2);
          $_SESSION['hc_OpenIDLoggedIn'] = true;
          $_SESSION['hc_OpenID'] = $esc_identity;
          header('Location: ' . CalRoot);
    }//end if?>