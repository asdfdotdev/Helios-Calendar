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
	if(!defined('isHC')){exit(-1);}
	
	header("X-Frame-Options: SAMEORIGIN");
	
	if(function_exists('ini_set'))
		ini_set("session.cookie_httponly", true);
	
	session_name($hc_cfg[201]);
	session_start();
	
	if(user_check_status() && (($_SESSION['UserLoginTime']+300) < date("U")))
		user_update_status($_SESSION['UserNetType'],$_SESSION['UserNetName'],$_SESSION['UserNetID'],$_SESSION['UserLoggedIn']);
	
	if(!isset($_SESSION['LangSet']))
		$_SESSION['LangSet'] = $hc_cfg[28];
	
	if(!isset($_SESSION['Theme']))
		$_SESSION['Theme'] = (!preg_match("$hc_cfg[86]i",$_SERVER['HTTP_USER_AGENT'])) ?  $hc_cfg[83] : $hc_cfg[84];
	
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
	
	
/*	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code following this notice is 		|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/	
	eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB0aGVuIHlvdSBoYXZlIHZpb2xhdGVkIHRoZSBIZWxpb3MgQ2FsZW5kYXIgU0xBLiovDQoJaWYoIWlzc2V0KCRzZXR1cCkpew0KCQlpZigkaGNfY2ZnWzIwXSAhPSBtZDUoZGF0ZSgiWS1tLWQiLCBta3RpbWUoIDAsIDAsIDAsIGRhdGUoIm0iKSwgMSwgZGF0ZSgiWSIpKSkpICYmICRfU0VSVkVSWydIVFRQX0hPU1QnXSAhPSAnbG9jYWxob3N0Jyl7DQoJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBTZXR0aW5nVmFsdWUgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFdIRVJFIFBrSUQgPSAxOSIpOw0KCQkJJGNoa01lID0gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKTsNCgkJCWlmKCRjaGtNZSA9PSAibG9jYWxob3N0X29ubHkiICYmIHN0cnBvcygkcm9vdFVSTCwgImh0dHA6Ly9sb2NhbGhvc3QiKSA9PT0gZmFsc2Upew0KCQkJCWV4aXQoIlRoaXMgaW5zdGFsbCBpcyBvbmx5IGxpY2Vuc2VkIGZvciBsb2NhbGhvc3QgZGV2ZWxvcG1lbnQgdXNlIGFuZCBpcyBub3QgYmVpbmcgdXNlZCBvbiBhIGxvY2FsaG9zdC4gUGxlYXNlIHByb3Blcmx5IGxpY2Vuc2UgdGhpcyBpbnN0YWxsYXRpb24gZm9yIGNvbnRpbnVlZCB1c2UuIik7DQoJCQl9Ly9lbmQgaWYNCgkJCWlmKCRoY19jZmdbNDRdID09IDEpew0KCQkJCSRob3N0ID0gInd3dy5yZWZyZXNobXkuY29tIjsNCgkJCQlpZigoJHJwID0gZnNvY2tvcGVuKCRob3N0LCA4MCwgJGVycm5vLCAkZXJyc3RyLCAxKSkgKXsNCgkJCQkJJGN1ckRhdGUgPSBkYXRlKCJZLW0tZCIpOw0KCQkJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBDT1VOVCgqKSBGUk9NICIgLiBIQ19UYmxQcmVmaXggLiAiZXZlbnRzIFdIRVJFIElzQWN0aXZlID0gMSBBTkQgSXNBcHByb3ZlZCA9IDEgQU5EIFN0YXJ0RGF0ZSA+PSAnIiAuICRjdXJEYXRlIC4gIiciKTsNCgkJCQkJJGFlID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJldmVudHMgV0hFUkUgSXNBY3RpdmUgPSAxIEFORCBJc0FwcHJvdmVkID0gMSBBTkQgU3RhcnREYXRlIDwgJyIgLiAkY3VyRGF0ZSAuICInIik7DQoJCQkJCSRwZSA9ICgkcmVzdWx0KSA/IG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgOiBOVUxMOw0KCQkJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBDT1VOVCgqKSBGUk9NICIgLiBIQ19UYmxQcmVmaXggLiAiYWRtaW4gV0hFUkUgSXNBY3RpdmUgPSAxIik7DQoJCQkJCSRhYSA9ICgkcmVzdWx0KSA/IG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgOiBOVUxMOw0KCQkJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBDT1VOVCgqKSBGUk9NICIgLiBIQ19UYmxQcmVmaXggLiAic3Vic2NyaWJlcnMgV0hFUkUgSXNDb25maXJtID0gMSIpOw0KCQkJCQkkYXIgPSAoJHJlc3VsdCkgPyBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIDogTlVMTDsNCgkJCQkJJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgQ09VTlQoKikgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4gImxvY2F0aW9ucyBXSEVSRSBJc0FjdGl2ZSA9IDEiKTsNCgkJCQkJJGFsID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJldmVudHMgV0hFUkUgSXNBY3RpdmUgPSAxIEFORCBJc0FwcHJvdmVkID0gMSBBTkQgU3VibWl0dGVkQXQgSVMgTk9UIE5VTEwiKTsNCgkJCQkJJHNlID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRzbyA9IFBIUF9PUzsNCgkJCQkJJHdzID0gJF9TRVJWRVJbJ1NFUlZFUl9TT0ZUV0FSRSddOw0KCQkJCQkkcHYgPSBwaHB2ZXJzaW9uKCk7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIFZFUlNJT04oKSIpOw0KCQkJCQkkbXYgPSAoJHJlc3VsdCkgPyBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIDogTlVMTDsNCgkJCQkJJGh2ID0gJGhjX2NmZ1s0OV07DQoJCQkJCSRhb2lkID0gJGJvaWQgPSAkZWMgPSAnJzsNCgkJCQkJDQoJCQkJCSRyZWFkID0gIiI7DQoJCQkJCSRyZXF1ZXN0ID0gIkdFVCAvX3VwZGF0ZS9yZXBoLnBocD8iIC4gDQoJCQkJCQkJCSJhZT0iIC4gdXJsZW5jb2RlKCRhZSkgLg0KCQkJCQkJCQkiJnBlPSIgLiB1cmxlbmNvZGUoJHBlKSAuIA0KCQkJCQkJCQkiJmFhPSIgLiB1cmxlbmNvZGUoJGFhKSAuIA0KCQkJCQkJCQkiJmFyPSIgLiB1cmxlbmNvZGUoJGFyKSAuIA0KCQkJCQkJCQkiJmFsPSIgLiB1cmxlbmNvZGUoJGFsKSAuIA0KCQkJCQkJCQkiJmFvaWQ9IiAuIHVybGVuY29kZSgkYW9pZCkgLg0KCQkJCQkJCQkiJmJvaWQ9IiAuIHVybGVuY29kZSgkYm9pZCkgLg0KCQkJCQkJCQkiJmVjPSIgLiB1cmxlbmNvZGUoJGVjKSAuDQoJCQkJCQkJCSImc2U9IiAuIHVybGVuY29kZSgkc2UpIC4NCgkJCQkJCQkJIiZzbz0iIC4gdXJsZW5jb2RlKCRzbykgLiANCgkJCQkJCQkJIiZ3cz0iIC4gdXJsZW5jb2RlKCR3cykgLiANCgkJCQkJCQkJIiZwdj0iIC4gdXJsZW5jb2RlKCRwdikgLiANCgkJCQkJCQkJIiZtdj0iIC4gdXJsZW5jb2RlKCRtdikgLiANCgkJCQkJCQkJIiZodj0iIC4gdXJsZW5jb2RlKCRodikgLg0KCQkJCQkJCQkiIEhUVFAvMS4xXHJcbiI7DQoJCQkJCSRyZXF1ZXN0IC49ICJIb3N0OiAkaG9zdFxyXG4iOw0KCQkJCQkkcmVxdWVzdCAuPSAiQ29ubmVjdGlvbjogQ2xvc2VcclxuXHJcbiI7DQoJCQkJCWZ3cml0ZSgkcnAsICRyZXF1ZXN0KTsNCgkJCQkJZmNsb3NlKCRycCk7DQoJCQkJCQ0KCQkJCQlkb1F1ZXJ5KCJVUERBVEUgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5ncyBTRVQgU2V0dGluZ1ZhbHVlID0gJyIgLiBtZDUoZGF0ZSgiWS1tLWQiLCBta3RpbWUoIDAsIDAsIDAsIGRhdGUoIm0iKSwgMSwgZGF0ZSgiWSIpKSkpIC4gIicgV0hFUkUgUGtJRCA9ICcyMCciKTsNCgkJCQkJYnVpbGRDYWNoZSgwKTsNCgkJCQl9Ly9lbmQgaWYNCgkJCX0vL2VuZCBpZg0KCQl9Ly9lbmQgaWYNCgl9Ly9lbmQgaWY='));
?>