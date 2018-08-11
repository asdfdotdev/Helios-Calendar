<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.pdf
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 		|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/

	include('includes/include.php');
	if(!defined('DATABASE_HOST')){
		exit('This file must be run from your /events directory.');
	}//end if
	$curVersion = "1.4";?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development, LLC." />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="expires" content="0" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css" />
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(document.frm.uID.value == 0){
			alert('Please select the upgrade you wish to run.');
			return false;
		}//end if
		return true;
	}//end if
	//-->
	</script>
	<title>Helios Calendar Upgrade</title>
	<style type="text/css">
	html, body {margin: 0;padding: 0;background:#F5F6F7;color: #000000;font-family: Verdana, sans-serif;font-size: 11px;}
	#menu {text-align:center;width:100%;height:25px;}
	#container {margin: auto auto auto auto ; width: 980px; padding: 0;color: #000000;}
	#content {float: left; text-align: left; padding: 5px;width: 72%;background:#FFFFFF;border: solid 1px #666666;}
	#controls {float: left;padding: 0px 5px 5px 5px;width: 25%;}
	#categories{float:left;width:25%;}
	#core{float:left;width:70%;}
	#language {text-align: center;padding: 10px 0 15px 0;}
	#billboard,#popular {text-align: left;padding: 10px 0 10px 0;}
	#rssLinks {width: 760px; padding: 0;margin: auto auto auto auto ; }
	#copyright {clear: both; color: #666666;padding: 5px 0px 5px 0px;line-height: 17px;}
	a.copyright,a.copyrightR {text-decoration: none;color: #666666;}
	a.copyright:hover {text-decoration: underline;color: #FF6600;}
	a.copyrightR:hover {text-decoration: underline;color: #006532;}
	.listHeader{font-family:Garamond, Georgia, serif;font-size: 17px;font-weight: bold;color: #4396CA;}
	.setupText{font-family: Verdana, sans-serif;font-size: 11px;border-top: 1px solid #555; border-left: 1px solid #555; border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; padding: 1px; color: #333; vertical-align: center;}
	</style>
</head>

<body>
<br />
<div id="container">
	<br />
	<div id="content">
		<div style="padding:10px;"?>
			<b>Welcome to the Helios Calendar database upgrade script.</b>
			<br /><br />
	<?php	if(!isset($_POST['uID'])){	?>
				<b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE privileges</b> for your Helios database.
				<br /><br />
				<b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".
				<br /><br />
				<b>Step 3)</b> Delete this file, and the cache files from your /events/cache/ directory.
				<br /><br />
				<div style="border: solid 1px #666666;background:#FEFFE6;padding: 10px;">
					Be sure you select the proper current version (left number) repeating upgrades may break your Helios install.
				</div>
				<br />
				<form name="frm" id="frm" method="post" action="upgrade.php" onsubmit="return chkFrm();">
					<select name="uID" id="uID">
						<option value="0">Select Upgrade to Run</option>
						<option value="1">1.3.1&nbsp;&nbsp;-->&nbsp;&nbsp;<?php echo $curVersion;?></option>
					</select>
					<br /><br />
					<input name="submit" id="submit" type="submit" value=" Run Upgrade " class="button" />
				</form>
	<?php	} else {	?>
				<strike><b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE permissions</b> for your Helios database.</strike>
				<br /><br />
				<strike><b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".</strike>
				<br /><br />
				<b>Step 3)</b> Delete all cache files from your /events/cache/ directory.
				<br /><br />
	<?php		eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIENhbGVuZGFyIFNvZnR3YXJlIExpY2Vuc2UgQWdyZWVtZW50LiovDQokZGJjb25uZWN0aW9uID0gbXlzcWxfY29ubmVjdChEQVRBQkFTRV9IT1NULCBEQVRBQkFTRV9VU0VSLCBEQVRBQkFTRV9QQVNTKTsNCiRzdGF0dXMgPSAwOw0KZnVuY3Rpb24gZG9VcGdyYWRlKCRzdGF0dXMsICRtc2csICRxdWVyeSl7DQoJZWNobyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4OyI+JyAuICRtc2c7DQoJaWYobXlzcWxfcXVlcnkoJHF1ZXJ5KSl7DQoJCWVjaG8gJzxiPkZpbmlzaGVkPC9iPic7DQoJfSBlbHNlIHsNCgkJKyskc3RhdHVzOw0KCQllY2hvICc8Yj5GYWlsZWQ8L2I+JzsgDQoJfS8vZW5kIGlmDQoJZWNobyAnPC9kaXY+JzsNCglyZXR1cm4gJHN0YXR1czsNCn0vL2VuZCBkb1VwZ3JhZGUoKQ0KDQplY2hvICc8YnIgLz4nOw0KZWNobyAnPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KZWNobyAnPGxlZ2VuZD5VcGdyYWRlIFJlcG9ydDwvbGVnZW5kPic7DQpzd2l0Y2goJF9QT1NUWyd1SUQnXSl7DQoJY2FzZSAxOg0KCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDEpID8gJzxkaXYgc3R5bGU9InBhZGRpbmctbGVmdDo1cHg7bGluZS1oZWlnaHQ6MTVweDtmb250LXdlaWdodDpib2xkOyI+VXBncmFkaW5nIGZyb20gMS4zLjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJDQoJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkNyZWF0aW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiY29tbWVudHM8L2k+IFRhYmxlLi4uIiwNCgkJCQkJIkNSRUFURSBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJjb21tZW50c2AgKGBQa0lEYCBpbnQoMTApIHVuc2lnbmVkIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULCBgQ29tbWVudGAgbWVkaXVtdGV4dCwgYFBvc3RlcklEYCBpbnQoMTEpIHVuc2lnbmVkIERFRkFVTFQgTlVMTCwgYFBvc3RUaW1lYCBkYXRldGltZSBERUZBVUxUIE5VTEwsIGBUeXBlSURgIGludCgxMSkgdW5zaWduZWQgREVGQVVMVCBOVUxMLCBgRW50aXR5SURgIGludCgxMSkgdW5zaWduZWQgREVGQVVMVCBOVUxMLCBgUmVjb21uZHNgIGludCgxMSkgTk9UIE5VTEwgREVGQVVMVCAnMCcsIGBJc0FjdGl2ZWAgdGlueWludCgzKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgUFJJTUFSWSBLRVkgKGBQa0lEYCkpIFR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDcmVhdGluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gImNvbW1lbnRzcmVwb3J0bG9nPC9pPiBUYWJsZS4uLiIsDQoJCQkJCSJDUkVBVEUgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAiY29tbWVudHNyZXBvcnRsb2dgIChgUGtJRGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBBVVRPX0lOQ1JFTUVOVCwgYENvbW1lbnRJRGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgYFR5cGVJRGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgYFVzZXJJRGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgYFJlcG9ydGVkTmFtZWAgdmFyY2hhcig1MCkgREVGQVVMVCBOVUxMLCBgUmVwb3J0ZWRFbWFpbGAgdmFyY2hhcig3NSkgREVGQVVMVCBOVUxMLCBgUmVwb3J0ZWRNc2dgIHRleHQsIGBSZXBvcnRlZElQYCB2YXJjaGFyKDE1KSBERUZBVUxUIE5VTEwsIGBJc0FjdGl2ZWAgdGlueWludCgzKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgUFJJTUFSWSBLRVkgKGBQa0lEYCkpIFR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDcmVhdGluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIm9pZHVzZXJzPC9pPiBUYWJsZS4uLiIsDQoJCQkJCSJDUkVBVEUgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAib2lkdXNlcnNgIChgUGtJRGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBBVVRPX0lOQ1JFTUVOVCwgYElkZW50aXR5YCB2YXJjaGFyKDI1NSkgREVGQVVMVCBOVUxMLCBgU2hvcnROYW1lYCB2YXJjaGFyKDI1MCkgREVGQVVMVCBOVUxMLCBgTG9naW5DbnRgIGludCgxMSkgdW5zaWduZWQgTk9UIE5VTEwsIGBGaXJzdExvZ2luYCBkYXRldGltZSBERUZBVUxUIE5VTEwsIGBMYXN0TG9naW5gIGRhdGV0aW1lIERFRkFVTFQgTlVMTCwgYExhc3RMb2dpbklQYCB2YXJjaGFyKDMwKSBERUZBVUxUIE5VTEwsIGBJc0FjdGl2ZWAgdGlueWludCgzKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgUFJJTUFSWSBLRVkgKGBQa0lEYCkpIFR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDcmVhdGluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gInJlY29tbmRzbG9nPC9pPiBUYWJsZS4uLiIsDQoJCQkJCSJDUkVBVEUgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAicmVjb21uZHNsb2dgIChgQ29tbWVudElEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBgT0lEVXNlcmAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCBERUZBVUxUICcwJywgYFNjb3JlYCBmbG9hdCBERUZBVUxUIE5VTEwsIFBSSU1BUlkgS0VZIChgQ29tbWVudElEYCxgT0lEVXNlcmApKSBUeXBlPU15SVNBTSBDSEFSQUNURVIgU0VUIHV0ZjggQ09MTEFURSB1dGY4X2dlbmVyYWxfY2kiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICI8L2k+YWRtaW5sb2dpbmhpc3RvcnkgVGFibGUuLi4iLA0KCQkJCQkiQUxURVIgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAiYWRtaW5sb2dpbmhpc3RvcnlgIENIQU5HRSBgSVBgIGBJUGAgVkFSQ0hBUigzMCkgREVGQVVMVCBOVUxMIE5VTEwiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICI8L2k+Y2F0ZWdvcmllcyBUYWJsZS4uLiIsDQoJCQkJCSJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJjYXRlZ29yaWVzYCBEUk9QIGBQYXRoYCwgRFJPUCBgTGV2ZWxgIik7DQoJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiPC9pPmV2ZW50cyBUYWJsZS4uLiIsDQoJCQkJCSJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJldmVudHNgIENIQU5HRSBgVGl0bGVgIGBUaXRsZWAgVkFSQ0hBUigyNTUpLCBDSEFOR0UgYERlc2NyaXB0aW9uYCBgRGVzY3JpcHRpb25gIG1lZGl1bXRleHQsIENIQU5HRSBgTG9jYXRpb25OYW1lYCBgTG9jYXRpb25OYW1lYCBWQVJDSEFSKDEwMCkiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICI8L2k+c2V0dGluZ3MgVGFibGUuLi4iLA0KCQkJCQkiQUxURVIgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIENIQU5HRSBgU2V0dGluZ1ZhbHVlYCBgU2V0dGluZ1ZhbHVlYCBNRURJVU1URVhUIE5VTEwiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICI8L2k+YWRtaW5wZXJtaXNzaW9ucyBUYWJsZS4uLiIsDQoJCQkJCSJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJhZG1pbnBlcm1pc3Npb25zYCBBREQgYENvbW1lbnRzYCBJTlQoIDMgKSBVTlNJR05FRCBOT1QgTlVMTCBERUZBVUxUICcwJyBBRlRFUiBgTG9jYXRpb25zYCIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJHcmFudGluZyBBbGwgQWRtaW4gVXNlcnMgQ29tbWVudCBQZXJtaXNzaW9ucy4uLiIsDQoJCQkJCSJVUERBVEUgYCIgLiBIQ19UYmxQcmVmaXggLiAiYWRtaW5wZXJtaXNzaW9uc2AgU0VUIENvbW1lbnRzID0gMSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIjwvaT5hZG1pbnBlcm1pc3Npb25zIFRhYmxlLi4uIiwNCgkJCQkJIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImFkbWlubG9naW5oaXN0b3J5YCBDSEFOR0UgYENsaWVudGAgYENsaWVudGAgVkFSQ0hBUiggMjAwICkgTlVMTCBERUZBVUxUIE5VTEwiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiVXBkYXRpbmcgTWFwIExpbmsgU2V0dGluZy4uLiIsDQoJCQkJCSJVUERBVEUgYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIFNFVCBgU2V0dGluZ1ZhbHVlYCA9ICdodHRwOi8vbWFwcy5nb29nbGUuY29tL21hcHM/Zj1kJnE9W2FkZHJlc3NdLCUyMFtjaXR5XSwlMjBbcmVnaW9uXSUyMFtwb3N0YWxjb2RlXSUyMFtjb3VudHJ5XScgV0hFUkUgUGtJRCA9IDgiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiVXBkYXRpbmcgV2VhdGhlciBMaW5rIFNldHRpbmcuLi4iLA0KCQkJCQkiVVBEQVRFIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCBTRVQgYFNldHRpbmdWYWx1ZWAgPSAnaHR0cDovL3d3dy53ZWF0aGVyLmNvbS93ZWF0aGVyL2xvY2FsL1twb3N0YWxjb2RlXScgV0hFUkUgUGtJRCA9IDkiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNDkuLi4iLA0KCQkJCQkiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc0OScsICcxLjQnKSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1MC4uLiIsDQoJCQkJCSJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzUwJywgJzAnKSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1MS4uLiIsDQoJCQkJCSJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzUxJywgJ01NL2RkL3l5eXknKSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1Mi4uLiIsDQoJCQkJCSJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzUyJywgJ2h0dHA6Ly9tYXBzLmdvb2dsZS5jb20vJykiKTsNCgkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNTMuLi4iLA0KCQkJCQkiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc1MycsICctMScpIik7DQoJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDU0Li4uIiwNCgkJCQkJIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTQnLCAnMCcpIik7DQoJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDU1Li4uIiwNCgkJCQkJIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTUnLCBOVUxMKSIpOw0KCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1Ni4uLiIsDQoJCQkJCSJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzU2JywgMCkiKTsNCgkJCQkNCi8qCQljYXNlIDI6DQoJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMSkgPyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4O2ZvbnQtd2VpZ2h0OmJvbGQ7Ij5VcGdyYWRpbmcgZnJvbSAxLjQgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsJKi8NCgkJDQoJCWVjaG8gJzwvZmllbGRzZXQ+PGJyIC8+JzsNCgkJZWNobyAnPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KCQllY2hvICc8bGVnZW5kPlVwZ3JhZGUgUmVzdWx0czwvbGVnZW5kPic7DQoNCgkJaWYoJHN0YXR1cyA9PSAwKXsNCgkJCWVjaG8gJ1VwZ3JhZGUgQ29tcGxldGVkIFN1Y2Nlc3NmdWxseS48YnIgLz48YnIgLz5EZWxldGUgdGhpcyBmaWxlIGFuZCB5b3VyIGNhY2hlIGZpbGVzIHRvIGNvbXBsZXRlIHlvdXIgdXBncmFkZS4nOw0KCQl9IGVsc2Ugew0KCQkJZWNobyAnT25lIG9yIG1vcmUgb2YgdGhlIHVwZ3JhZGVzIGZhaWxlZC4gVGhpcyBpcyBtb3N0IGNvbW1vbmx5IGNhdXNlZCBieSBpbXByb3BlciBjb25maWd1cmF0aW9uIG9mIHlvdXIgTXlTUUwgdXNlciBwcml2aWxlZ2VzLiBZb3UgbWF5IG5lZWQgdG8gcmVzdG9yZSB5b3VyIGRhdGFiYXNlIGZyb20gYSBiYWNrdXAgYmVmb3JlIHJ1bm5pbmcgdGhpcyB1cGdyYWRlIGFnYWluLic7DQoJCQllY2hvICc8YnIgLz48YnIgLz4nOw0KCQkJZWNobyAnSWYgeW91IGV4cGVyaWVuY2UgY29udGludWVkIGRpZmZpY3VsdHkgcGxlYXNlIGFjY2VzcyB0aGUgUmVmcmVzaCBDb21tdW5pdHkgZm9ydW0gb3Igc3VibWl0IGEgc3VwcG9ydCB0aWNrZXQgZnJvbSB5b3VyIG1lbWJlciBhY2NvdW50Lic7DQoJCX0vL2VuZCBpZg0KCQllY2hvICc8L2ZpZWxkc2V0Pic7DQoJYnJlYWs7DQp9Ly9lbmQgc3dpdGNoPGJyIC8+PGhyIC8+PGJyIC8+'));
			}//end if	?>
			<br />
		</div>
	</div>
	<div id="controls" align="center">
		<img src="<?php echo CalAdminRoot;?>/images/logo.png" width="200" alt="" border="0">
		
		<br /><br /><br />
		<div id="popular">
			<div class="listHeader">Support Links</div>
			<ul class="popular">
				<li class="popular"><a href="https://www.refreshmy.com/members/" target="_blank" class="popular">Refresh Member Site</a></li>
				<li class="popular"><a href="http://www.refreshmy.com/documentation/?title=Upgrading" target="_blank" class="popular">Helios Upgrade Documentation</a></li>
				<li class="popular"><a href="http://www.refreshmy.com/forum/" target="_blank" class="popular">Refresh Community Forum</a></li>
			</ul>
		</div>
	</div>
	<div id="copyright">
		<a href="http://www.helioscalendar.com" class="copyright">Helios Calendar <?php echo $curVersion;?></a> Copyright 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" class="copyrightR">Refresh Web Development</a>
	</div>
</div>

</body>
</html>