<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modification of the source code within this file is prohibited.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = '';
	
	if(isset($eID))
		$result = doQuery("SELECT ShortURL FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
	elseif(isset($lID))
		$result = doQuery("SELECT ShortURL FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $lID . "'");
	
	if(hasRows($result) && mysql_result($result,0,0) != ''){
		$shortLink = (strpos(mysql_result($result,0,0),"http://") !== false) ? mysql_result($result,0,0) : $shortLink;
	} else {
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(57,58)");
		if(!hasRows($result)){
			$errorMsg = 'Settings Table Corrupted.';
		} else {
			if(mysql_result($result,0,0) == '' && mysql_result($result,1,0) == ''){
				$errorMsg = 'bit.ly API Settings Missing.';
			} else {
				$bitlyUser = mysql_result($result,0,0);
				$bitlyAPI = mysql_result($result,1,0);
				
				$ip = gethostbyname("api.bit.ly");
				if(!$fp = fsockopen($ip, 80, $errno, $errstr, 1)){
					$errorMsg = 'Connection to bit.ly Service Failed.';
				} else {
					$btlySend = "/shorten?version=2.0.1&format=xml&login=" . $bitlyUser . "&apiKey=" . $bitlyAPI . "&longUrl=" . urlencode($shortLink);

					$request = "GET $btlySend HTTP/1.1\r\n";
					$request .= "Host: api.bit.ly\r\n";
					$request .= "Connection: Close\r\n\r\n";

					fwrite($fp, $request);
					$data = '';
					while(!feof($fp)) {
						$data .= fread($fp,1024);
					}
					fclose($fp);

					$fetched = xml2array($data);
					if(isset($fetched[0]['elements'][1]['value'])){
						$errorMsg = 'Error Msg From bit.ly - <i>' . $fetched[0]['elements'][1]['value'] . '</i>';
					} else {
						$bitURL = '';
						$stopB = count($fetched[0]['elements'][2]['elements'][0]['elements']);
						for($x=0;$x<$stopB;$x++){
							if($fetched[0]['elements'][2]['elements'][0]['elements'][$x]['name'] == 'shortUrl'){
								$bitURL = $fetched[0]['elements'][2]['elements'][0]['elements'][$x]['value'];
								break;
							}
						}

						if($bitURL != ''){
							if(isset($eID)){
								doQuery("UPDATE " . HC_TblPrefix . "events SET ShortURL = '" . cIn($bitURL) . "' WHERE PkID = '" . $eID . "'");
							} elseif(isset($lID)){
								doQuery("UPDATE " . HC_TblPrefix . "locations SET ShortURL = '" . cIn($bitURL) . "' WHERE PkID = '" . $lID . "'");
							}
							$shortLink = $bitURL;
						}
					}
				}
				//echo ($errorMsg != '') ? $errorMsg : '';
			}
		}
	}?>