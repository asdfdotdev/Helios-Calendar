<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	This file includes source code released by JanRain Inc.
	Under License: http://www.apache.org/licenses/LICENSE-2.0 Apache
	Original Available At: http://openidenabled.com/php-openid/
*/
	$OIDPath = '../includes/php-openid-2.1.2';
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
	    $store_path = "../includes/php-openid-2.1.2/_HeliosCalendar_consumers";
	    
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