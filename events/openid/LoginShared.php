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
	$OIDPath = realpath('../includes/phpopenid');
	define('Auth_OpenID_RAND_SOURCE', NULL);

	function displayError($message) {
	    echo $message;
	    exit(0);
	}//end displayError()
	
	require_once "$OIDPath/Auth/OpenID/Consumer.php";
	require_once "$OIDPath/Auth/OpenID/FileStore.php";
	require_once "$OIDPath/Auth/OpenID/SReg.php";
	require_once "$OIDPath/Auth/OpenID/PAPE.php";
	
	global $pape_policy_uris;
	$pape_policy_uris = array(
				  PAPE_AUTH_MULTI_FACTOR_PHYSICAL,
				  PAPE_AUTH_MULTI_FACTOR,
				  PAPE_AUTH_PHISHING_RESISTANT
				  );
	
	function &getStore() {
	    $store_path = "../includes/phpopenid/_HeliosCalendar_consumers";
	    
	    if (!file_exists($store_path) &&
	        !mkdir($store_path)) {
	        print "Could not create the FileStore directory '$store_path'. ".
	            " Please check the effective permissions.";
	        exit(0);
	    }
	 	$returnVal = new Auth_OpenID_FileStore($store_path);
	    return $returnVal;
	}//end &getStore()
	
	function &getConsumer() {
	    $store = getStore();
	    $returnVal = new Auth_OpenID_Consumer($store);
	    return $returnVal;
	}//end &getConsumer()
	
	function getScheme() {
	    $scheme = 'http';
	    if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on') {
	        $scheme .= 's';
	    }
	    return $scheme;
	}//end getScheme()
	
	function getReturnTo() {
	    return sprintf("%s://%s:%s%s/LoginCatch.php",
	                   getScheme(), $_SERVER['SERVER_NAME'],
	                   $_SERVER['SERVER_PORT'],
	                   dirname($_SERVER['PHP_SELF']));
	}//end getReturnTo()
	
	function getTrustRoot() {
	    return sprintf("%s://%s:%s%s/",
	                   getScheme(), $_SERVER['SERVER_NAME'],
	                   $_SERVER['SERVER_PORT'],
	                   dirname($_SERVER['PHP_SELF']));
	}//end getTrustRoot()?>