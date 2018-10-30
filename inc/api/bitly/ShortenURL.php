<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	$errorMsg = $bitURL = '';
	if(isset($eID) && is_numeric($eID))
		$resultB = doQuery("SELECT ShortURL FROM " . HC_TblPrefix . "events WHERE PkID = ?", array(cIn($eID)));
	elseif(isset($lID) && is_numeric($lID))
		$resultB = doQuery("SELECT ShortURL FROM " . HC_TblPrefix . "locations WHERE PkID = ?", array(cIn($lID)));
	
	if(hasRows($resultB) && hc_mysql_result($resultB,0,0) != ''){
		$shortLink = (strpos(hc_mysql_result($resultB,0,0),"http://") !== false) ? hc_mysql_result($resultB,0,0) : $shortLink;
	} else {
		$resultB = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(57,58)");
		if(!hasRows($resultB)){
			$errorMsg = 'bitly API Settings Unavailable.';
		} else {
			if(hc_mysql_result($resultB,0,0) == '' && hc_mysql_result($resultB,1,0) == ''){
				$errorMsg = 'bitly API Settings Missing.';
			} else {
				$bitlyUser = hc_mysql_result($resultB,0,0);
				$bitlyAPI = hc_mysql_result($resultB,1,0);
				$bSend = "/v3/shorten?format=xml&login=" . $bitlyUser . "&apiKey=" . $bitlyAPI . "&longUrl=" . urlencode($shortLink);
				
				$host = 'api-ssl.bitly.com';
				if(!$fp = fsockopen("ssl://api-ssl.bitly.com", 443, $errno, $errstr, 20)){
					$host = 'api.bitly.com';
					$fp = fsockopen("api.bitly.com", 80, $errno, $errstr, 20);
				}

				if(!$fp){
					$apiFail = true;
					$errorMsg = 'Connection to bitly Failed.';
				} else {
					$data = '';
					$request = "GET " . $bSend . " HTTP/1.1\r\nHost: ".$host."\r\nConnection: Close\r\n\r\n";

					fwrite($fp, $request);
					while(!feof($fp)) {
						$data .= fread($fp,1024);
					}
					fclose($fp);
					
					$status_code = xml_tag_value('status_code',$data);
					if($status_code != '200'){
						$apiFail = true;
						$errorMsg = 'Error Msg From bitly - <i>' . xml_tag_value('status_txt',$data) . '</i>';
					} else {
						$bitURL = xml_tag_value('url',$data);
					}
					
					if($bitURL != ''){
						if(isset($eID))
							doQuery("UPDATE " . HC_TblPrefix . "events SET ShortURL = ? WHERE PkID = ?", array(cIn($bitURL), cIn($eID)));
						elseif(isset($lID))
							doQuery("UPDATE " . HC_TblPrefix . "locations SET ShortURL = ? WHERE PkID = ?", array(cIn($bitURL),cIn($lID)));
						
						$shortLink = $bitURL;
					}
				}
			}
		}
	}
	echo ($errorMsg != '') ? $errorMsg : '';
?>