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
	error_reporting(0);
	$hc_langPath = "includes/lang/";
	$incPre = (isset($isAction)) ? '../' : '';
	include($incPre . 'includes/globals.php');
	include($incPre . 'includes/code.php');
	
	$dbc = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
	mysql_select_db(DATABASE_NAME,$dbc);
	
	if(!file_exists(realpath($incPre . 'cache/config.php'))){
		rebuildCache(0);
	}//end if
	include($incPre . 'cache/config.php');

	session_name($hc_cfg00p);
	session_start();
	
	if(!isset($_SESSION['BrowseType'])){
		$_SESSION['BrowseType'] = $hc_cfg34;
	} else if(isset($_GET['b']) && is_numeric($_GET['b'])){
		$_SESSION['BrowseType'] = cIn($_GET['b']);
	}//end if
	
	if(!isset($_SESSION['LangSet'])){
		$_SESSION['LangSet'] = $hc_cfg28;
	}//end if
	
	if(!isset($_SESSION['hc_favCat']) && isset($_COOKIE[$hc_cfg00p . '_fn'])){
		$_SESSION['hc_favCat'] = base64_decode($_COOKIE[$hc_cfg00p . '_fn']);
	}//end if
	
	if(!isset($_SESSION['hc_favCity']) && isset($_COOKIE[$hc_cfg00p . '_fc'])){
		$_SESSION['hc_favCity'] = base64_decode($_COOKIE[$hc_cfg00p . '_fc']);
	}//end if
	
	include($incPre . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);
	
	
/*	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code following this notice is 		|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/
	
	eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB0aGVuIHlvdSBoYXZlIHZpb2xhdGVkIHRoZSBIZWxpb3MgQ2FsZW5kYXIgU0xBLiovDQoJaWYoIWlzc2V0KCRzZXR1cCkpew0KCQlpZigkaGNfY2ZnMjAgIT0gbWQ1KGRhdGUoIlktbS1kIiwgbWt0aW1lKCAwLCAwLCAwLCBkYXRlKCJtIiksIDEsIGRhdGUoIlkiKSkpKSAmJiAkX1NFUlZFUlsnSFRUUF9IT1NUJ10gIT0gJ2xvY2FsaG9zdCcpew0KCQkJJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgU2V0dGluZ1ZhbHVlIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5ncyBXSEVSRSBQa0lEID0gMTkiKTsNCgkJCSRjaGtNZSA9IG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCk7DQoJCQlpZigkY2hrTWUgPT0gImxvY2FsaG9zdF9vbmx5IiAmJiBzdHJwb3MoJHJvb3RVUkwsICJodHRwOi8vbG9jYWxob3N0IikgPT09IGZhbHNlKXsNCgkJCQlleGl0KCJUaGlzIGluc3RhbGwgaXMgb25seSBsaWNlbnNlZCBmb3IgbG9jYWxob3N0IGRldmVsb3BtZW50IHVzZSBhbmQgaXMgbm90IGJlaW5nIHVzZWQgb24gYSBsb2NhbGhvc3QuIFBsZWFzZSBwcm9wZXJseSBsaWNlbnNlIHRoaXMgaW5zdGFsbGF0aW9uIGZvciBjb250aW51ZWQgdXNlLiIpOw0KCQkJfS8vZW5kIGlmDQoJCQlpZigkaGNfY2ZnNDQgPT0gMSl7DQoJCQkJJGhvc3QgPSAid3d3LnJlZnJlc2hteS5jb20iOw0KCQkJCWlmKCgkcnAgPSBmc29ja29wZW4oJGhvc3QsIDgwLCAkZXJybm8sICRlcnJzdHIsIDEpKSApew0KCQkJCQkkY3VyRGF0ZSA9IGRhdGUoIlktbS1kIik7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJldmVudHMgV0hFUkUgSXNBY3RpdmUgPSAxIEFORCBJc0FwcHJvdmVkID0gMSBBTkQgU3RhcnREYXRlID49ICciIC4gJGN1ckRhdGUgLiAiJyIpOw0KCQkJCQkkYWUgPSAoJHJlc3VsdCkgPyBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIDogTlVMTDsNCgkJCQkJJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgQ09VTlQoKikgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4gImV2ZW50cyBXSEVSRSBJc0FjdGl2ZSA9IDEgQU5EIElzQXBwcm92ZWQgPSAxIEFORCBTdGFydERhdGUgPCAnIiAuICRjdXJEYXRlIC4gIiciKTsNCgkJCQkJJHBlID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJhZG1pbiBXSEVSRSBJc0FjdGl2ZSA9IDEiKTsNCgkJCQkJJGFhID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJ1c2VycyBXSEVSRSBJc1JlZ2lzdGVyZWQgPSAxIik7DQoJCQkJCSRhciA9ICgkcmVzdWx0KSA/IG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgOiBOVUxMOw0KCQkJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBDT1VOVCgqKSBGUk9NICIgLiBIQ19UYmxQcmVmaXggLiAibG9jYXRpb25zIFdIRVJFIElzQWN0aXZlID0gMSIpOw0KCQkJCQkkYWwgPSAoJHJlc3VsdCkgPyBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIDogTlVMTDsNCgkJCQkJJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgQ09VTlQoKikgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4gIm9pZHVzZXJzIFdIRVJFIElzQWN0aXZlID0gMSIpOw0KCQkJCQkkYW9pZCA9ICgkcmVzdWx0KSA/IG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgOiBOVUxMOw0KCQkJCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBDT1VOVCgqKSBGUk9NICIgLiBIQ19UYmxQcmVmaXggLiAib2lkdXNlcnMgV0hFUkUgSXNBY3RpdmUgPSAyIik7DQoJCQkJCSRib2lkID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJjb21tZW50cyBXSEVSRSBJc0FjdGl2ZSA9IDEgQU5EIFR5cGVJRCA9IDEiKTsNCgkJCQkJJGVjID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIENPVU5UKCopIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJldmVudHMgV0hFUkUgSXNBY3RpdmUgPSAxIEFORCBJc0FwcHJvdmVkID0gMSBBTkQgU3VibWl0dGVkQXQgSVMgTk9UIE5VTEwiKTsNCgkJCQkJJHNlID0gKCRyZXN1bHQpID8gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSA6IE5VTEw7DQoJCQkJCSRzbyA9IFBIUF9PUzsNCgkJCQkJJHdzID0gJF9TRVJWRVJbJ1NFUlZFUl9TT0ZUV0FSRSddOw0KCQkJCQkkcHYgPSBwaHB2ZXJzaW9uKCk7DQoJCQkJCSRyZXN1bHQgPSBteXNxbF9xdWVyeSgiU0VMRUNUIFZFUlNJT04oKSIpOw0KCQkJCQkkbXYgPSAoJHJlc3VsdCkgPyBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIDogTlVMTDsNCgkJCQkJJGh2ID0gJGhjX2NmZzQ5Ow0KCQkJCQkNCgkJCQkJJHJlYWQgPSAiIjsNCgkJCQkJJHJlcXVlc3QgPSAiR0VUIC9fdXBkYXRlL3JlcGgucGhwPyIgLiANCgkJCQkJCQkJImFlPSIgLiB1cmxlbmNvZGUoJGFlKSAuDQoJCQkJCQkJCSImcGU9IiAuIHVybGVuY29kZSgkcGUpIC4gDQoJCQkJCQkJCSImYWE9IiAuIHVybGVuY29kZSgkYWEpIC4gDQoJCQkJCQkJCSImYXI9IiAuIHVybGVuY29kZSgkYXIpIC4gDQoJCQkJCQkJCSImYWw9IiAuIHVybGVuY29kZSgkYWwpIC4gDQoJCQkJCQkJCSImYW9pZD0iIC4gdXJsZW5jb2RlKCRhb2lkKSAuDQoJCQkJCQkJCSImYm9pZD0iIC4gdXJsZW5jb2RlKCRib2lkKSAuDQoJCQkJCQkJCSImZWM9IiAuIHVybGVuY29kZSgkZWMpIC4NCgkJCQkJCQkJIiZzZT0iIC4gdXJsZW5jb2RlKCRzZSkgLg0KCQkJCQkJCQkiJnNvPSIgLiB1cmxlbmNvZGUoJHNvKSAuIA0KCQkJCQkJCQkiJndzPSIgLiB1cmxlbmNvZGUoJHdzKSAuIA0KCQkJCQkJCQkiJnB2PSIgLiB1cmxlbmNvZGUoJHB2KSAuIA0KCQkJCQkJCQkiJm12PSIgLiB1cmxlbmNvZGUoJG12KSAuIA0KCQkJCQkJCQkiJmh2PSIgLiB1cmxlbmNvZGUoJGh2KSAuDQoJCQkJCQkJCSIgSFRUUC8xLjFcclxuIjsNCgkJCQkJJHJlcXVlc3QgLj0gIkhvc3Q6ICRob3N0XHJcbiI7DQoJCQkJCSRyZXF1ZXN0IC49ICJDb25uZWN0aW9uOiBDbG9zZVxyXG5cclxuIjsNCgkJCQkJZndyaXRlKCRycCwgJHJlcXVlc3QpOw0KCQkJCQlmY2xvc2UoJHJwKTsNCgkJCQkJDQoJCQkJCWRvUXVlcnkoIlVQREFURSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFNFVCBTZXR0aW5nVmFsdWUgPSAnIiAuIG1kNShkYXRlKCJZLW0tZCIsIG1rdGltZSggMCwgMCwgMCwgZGF0ZSgibSIpLCAxLCBkYXRlKCJZIikpKSkgLiAiJyBXSEVSRSBQa0lEID0gJzIwJyIpOw0KCQkJCQlyZWJ1aWxkQ2FjaGUoMCk7DQoJCQkJfS8vZW5kIGlmDQoJCQl9Ly9lbmQgaWYNCgkJfS8vZW5kIGlmDQoJfS8vZW5kIGlm'));?>