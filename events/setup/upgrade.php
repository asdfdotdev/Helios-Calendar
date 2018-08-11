<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/

	include('includes/include.php');
	if(!defined('DATABASE_HOST')){
		exit('This file must be run from your /events directory.');
	}//end if
	$curVersion = "1.6.1";?>
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
		if(document.getElementById('uID').value == 0){
			alert('Please select your current version to run the upgrade script.');
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
		<div style="padding:10px;">
               
			<b>Welcome to the Helios Calendar database upgrade script.</b>
			<br /><br />
	<?php	if(!isset($_POST['uID'])){	?>
				<b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE privileges</b> for your Helios database.
				<br /><br />
				<b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".
				<br /><br />
				<b>Step 3)</b> If the upgrade is successful, delete this file, and the cache files from your /events/cache/ directory.
				<br /><br />
				<div style="border: solid 1px #666666;background:#FEFFE6;padding: 0px 10px 0px 10px;">
                         <p>This script will validate your license &amp; subscription status. Upgrades are only available to eligible licensees.</p>
                         <p>To review your license &amp; subscription status visit your <a href="https://www.refreshmy.com/members/" target="_blank" class="eventMain">Refresh Members Site</a> account.</p>
				</div>
				<br />
			<?php
				echo '<div style="padding-bottom:10px;">';
				echo (isset($hc_cfg49)) ? '<b>Current Database Version:</b>&nbsp;' . $hc_cfg49 . '<br />' : '';
				echo '</div>';?>
				<form name="frm" id="frm" method="post" action="upgrade.php" onsubmit="return chkFrm();">
				<fieldset>
					<legend>Upgrade to Run</legend>
					<select name="uID" id="uID">
						<option value="0">Select Current Version</option>
						<option value="1">1.3.1</option>
                              <option value="2">1.4</option>
						<option value="3">1.4.1</option>
						<option value="4">1.5</option>
						<option value="5">1.5.1</option>
						<option value="6">1.5.2</option>
						<option value="7">1.6</option>
					</select>
					&nbsp;&nbsp;-->&nbsp;&nbsp;<b><?php echo $curVersion;?></b>
				</fieldset>
				<br />
				<input name="submit" id="submit" type="submit" value=" Run Upgrade " class="button" />
				</form>
	<?php	} else {	?>
				<strike><b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE permissions</b> for your Helios database.</strike>
				<br /><br />
				<strike><b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".</strike>
				<br /><br />
	<?php          eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIENhbGVuZGFyIFNvZnR3YXJlIExpY2Vuc2UgQWdyZWVtZW50LiovDQplY2hvICc8YnIgLz4nOw0KZWNobyAnPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KZWNobyAnPGxlZ2VuZD5VcGdyYWRlIFJlcG9ydDwvbGVnZW5kPic7DQoNCiRoY191cGdWZXIgPSAiMS42LjEiOw0KJGRiY29ubmVjdGlvbiA9IG15c3FsX2Nvbm5lY3QoREFUQUJBU0VfSE9TVCwgREFUQUJBU0VfVVNFUiwgREFUQUJBU0VfUEFTUyk7DQokcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBTZXR0aW5nVmFsdWUgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4ic2V0dGluZ3MgV0hFUkUgUGtJRCA9IDE5Iik7DQokc3RhdHVzID0gMDsNCiRob3N0ID0gInZhbGlkYXRlLnJlZnJlc2hteS5jb20iOw0KJGZpbGUgPSAiL2gucGhwP3Y9IiAuICRoY191cGdWZXIgLiAiJmM9IiAuIG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQppZighKCRmcCA9IGZzb2Nrb3BlbigkaG9zdCwgODAsICRlcnJubywgJGVycnN0ciwgMSkpICl7DQogICAgIGVjaG8gJ1VwZ3JhZGUgY2Fubm90IGJlIHJ1bi4gPGEgaHJlZj0iaHR0cHM6Ly93d3cucmVmcmVzaG15LmNvbS9tZW1iZXJzLyIgY2xhc3M9ImV2ZW50TWFpbiI+Q29udGFjdCBSZWZyZXNoIFN1cHBvcnQgZm9yIGFzc2lzdGFuY2UuPC9hPic7DQp9IGVsc2Ugew0KICAgICBmdW5jdGlvbiBkb1VwZ3JhZGUoJHN0YXR1cywgJG1zZywgJHF1ZXJ5KXsNCiAgICAgICAgICBlY2hvICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Ij4nIC4gJG1zZzsNCiAgICAgICAgICBpZihteXNxbF9xdWVyeSgkcXVlcnkpKXsNCiAgICAgICAgICAgICAgIGVjaG8gJzxiPkZpbmlzaGVkPC9iPic7DQogICAgICAgICAgfSBlbHNlIHsNCiAgICAgICAgICAgICAgICsrJHN0YXR1czsNCiAgICAgICAgICAgICAgIGVjaG8gJzxiPkZhaWxlZDwvYj4nOw0KICAgICAgICAgIH0vL2VuZCBpZg0KICAgICAgICAgIGVjaG8gJzwvZGl2Pic7DQogICAgICAgICAgcmV0dXJuICRzdGF0dXM7DQogICAgIH0vL2VuZCBkb1VwZ3JhZGUoKQ0KDQogICAgICRyZWFkID0gIiI7DQogICAgICRyZXF1ZXN0ID0gIkdFVCAkZmlsZSBIVFRQLzEuMVxyXG4iOw0KICAgICAkcmVxdWVzdCAuPSAiSG9zdDogJGhvc3RcclxuIjsNCiAgICAgJHJlcXVlc3QgLj0gIkNvbm5lY3Rpb246IENsb3NlXHJcblxyXG4iOw0KICAgICBmd3JpdGUoJGZwLCAkcmVxdWVzdCk7DQoNCiAgICAgd2hpbGUgKCFmZW9mKCRmcCkpIHsNCiAgICAgICAgICAkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQogICAgIH0vL2VuZCB3aGlsZQ0KDQogICAgICRvdXRwdXQgPSBleHBsb2RlKCJoZWxpb3MvLyIsICRyZWFkKTsNCiAgICAgaWYoaXNzZXQoJG91dHB1dFsxXSkpew0KICAgICAgICAgIHN3aXRjaCgkX1BPU1RbJ3VJRCddKXsNCiAgICAgICAgICAgICAgIGNhc2UgMToNCiAgICAgICAgICAgICAgICAgICAgZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAxKSA/ICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Zm9udC13ZWlnaHQ6Ym9sZDsiPlVwZ3JhZGluZyBmcm9tIDEuMy4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoNCiAgICAgICAgICAgICAgICAgICAgJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQ3JlYXRpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICJjb21tZW50czwvaT4gVGFibGUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiQ1JFQVRFIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImNvbW1lbnRzYCAoYFBrSURgIGludCgxMCkgdW5zaWduZWQgTk9UIE5VTEwgQVVUT19JTkNSRU1FTlQsIGBDb21tZW50YCBtZWRpdW10ZXh0LCBgUG9zdGVySURgIGludCgxMSkgdW5zaWduZWQgREVGQVVMVCBOVUxMLCBgUG9zdFRpbWVgIGRhdGV0aW1lIERFRkFVTFQgTlVMTCwgYFR5cGVJRGAgaW50KDExKSB1bnNpZ25lZCBERUZBVUxUIE5VTEwsIGBFbnRpdHlJRGAgaW50KDExKSB1bnNpZ25lZCBERUZBVUxUIE5VTEwsIGBSZWNvbW5kc2AgaW50KDExKSBOT1QgTlVMTCBERUZBVUxUICcwJywgYElzQWN0aXZlYCB0aW55aW50KDMpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBQUklNQVJZIEtFWSAoYFBrSURgKSkgVHlwZT1NeUlTQU0gQ0hBUkFDVEVSIFNFVCB1dGY4IENPTExBVEUgdXRmOF9nZW5lcmFsX2NpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkNyZWF0aW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiY29tbWVudHNyZXBvcnRsb2c8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkNSRUFURSBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJjb21tZW50c3JlcG9ydGxvZ2AgKGBQa0lEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULCBgQ29tbWVudElEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBgVHlwZUlEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBgVXNlcklEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBgUmVwb3J0ZWROYW1lYCB2YXJjaGFyKDUwKSBERUZBVUxUIE5VTEwsIGBSZXBvcnRlZEVtYWlsYCB2YXJjaGFyKDc1KSBERUZBVUxUIE5VTEwsIGBSZXBvcnRlZE1zZ2AgdGV4dCwgYFJlcG9ydGVkSVBgIHZhcmNoYXIoMTUpIERFRkFVTFQgTlVMTCwgYElzQWN0aXZlYCB0aW55aW50KDMpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBQUklNQVJZIEtFWSAoYFBrSURgKSkgVHlwZT1NeUlTQU0gQ0hBUkFDVEVSIFNFVCB1dGY4IENPTExBVEUgdXRmOF9nZW5lcmFsX2NpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkNyZWF0aW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAib2lkdXNlcnM8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkNSRUFURSBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJvaWR1c2Vyc2AgKGBQa0lEYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULCBgSWRlbnRpdHlgIHZhcmNoYXIoMjU1KSBERUZBVUxUIE5VTEwsIGBTaG9ydE5hbWVgIHZhcmNoYXIoMjUwKSBERUZBVUxUIE5VTEwsIGBMb2dpbkNudGAgaW50KDExKSB1bnNpZ25lZCBOT1QgTlVMTCwgYEZpcnN0TG9naW5gIGRhdGV0aW1lIERFRkFVTFQgTlVMTCwgYExhc3RMb2dpbmAgZGF0ZXRpbWUgREVGQVVMVCBOVUxMLCBgTGFzdExvZ2luSVBgIHZhcmNoYXIoMzApIERFRkFVTFQgTlVMTCwgYElzQWN0aXZlYCB0aW55aW50KDMpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBQUklNQVJZIEtFWSAoYFBrSURgKSkgVHlwZT1NeUlTQU0gQ0hBUkFDVEVSIFNFVCB1dGY4IENPTExBVEUgdXRmOF9nZW5lcmFsX2NpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkNyZWF0aW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAicmVjb21uZHNsb2c8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkNSRUFURSBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJyZWNvbW5kc2xvZ2AgKGBDb21tZW50SURgIGludCgxMSkgdW5zaWduZWQgTk9UIE5VTEwgREVGQVVMVCAnMCcsIGBPSURVc2VyYCBpbnQoMTEpIHVuc2lnbmVkIE5PVCBOVUxMIERFRkFVTFQgJzAnLCBgU2NvcmVgIGZsb2F0IERFRkFVTFQgTlVMTCwgUFJJTUFSWSBLRVkgKGBDb21tZW50SURgLGBPSURVc2VyYCkpIFR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIjwvaT5hZG1pbmxvZ2luaGlzdG9yeSBUYWJsZS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJhZG1pbmxvZ2luaGlzdG9yeWAgQ0hBTkdFIGBJUGAgYElQYCBWQVJDSEFSKDMwKSBERUZBVUxUIE5VTEwgTlVMTCIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIjwvaT5jYXRlZ29yaWVzIFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImNhdGVnb3JpZXNgIERST1AgYFBhdGhgLCBEUk9QIGBMZXZlbGAiKTsNCiAgICAgICAgICAgICAgICAgICAgJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICI8L2k+ZXZlbnRzIFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImV2ZW50c2AgQ0hBTkdFIGBUaXRsZWAgYFRpdGxlYCBWQVJDSEFSKDI1NSksIENIQU5HRSBgRGVzY3JpcHRpb25gIGBEZXNjcmlwdGlvbmAgbWVkaXVtdGV4dCwgQ0hBTkdFIGBMb2NhdGlvbk5hbWVgIGBMb2NhdGlvbk5hbWVgIFZBUkNIQVIoMTAwKSIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIjwvaT5zZXR0aW5ncyBUYWJsZS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgQ0hBTkdFIGBTZXR0aW5nVmFsdWVgIGBTZXR0aW5nVmFsdWVgIE1FRElVTVRFWFQgTlVMTCIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gIjwvaT5hZG1pbnBlcm1pc3Npb25zIFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImFkbWlucGVybWlzc2lvbnNgIEFERCBgQ29tbWVudHNgIElOVCggMyApIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzAnIEFGVEVSIGBMb2NhdGlvbnNgIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkdyYW50aW5nIEFsbCBBZG1pbiBVc2VycyBDb21tZW50IFBlcm1pc3Npb25zLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIlVQREFURSBgIiAuIEhDX1RibFByZWZpeCAuICJhZG1pbnBlcm1pc3Npb25zYCBTRVQgQ29tbWVudHMgPSAxIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiPC9pPmFkbWlucGVybWlzc2lvbnMgVGFibGUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiQUxURVIgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAiYWRtaW5sb2dpbmhpc3RvcnlgIENIQU5HRSBgQ2xpZW50YCBgQ2xpZW50YCBWQVJDSEFSKCAyMDAgKSBOVUxMIERFRkFVTFQgTlVMTCIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJVcGRhdGluZyBNYXAgTGluayBTZXR0aW5nLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIlVQREFURSBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgU0VUIGBTZXR0aW5nVmFsdWVgID0gJ2h0dHA6Ly9tYXBzLmdvb2dsZS5jb20vbWFwcz9mPWQmcT1bYWRkcmVzc10sJTIwW2NpdHldLCUyMFtyZWdpb25dJTIwW3Bvc3RhbGNvZGVdJTIwW2NvdW50cnldJyBXSEVSRSBQa0lEID0gOCIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJVcGRhdGluZyBXZWF0aGVyIExpbmsgU2V0dGluZy4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJVUERBVEUgYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIFNFVCBgU2V0dGluZ1ZhbHVlYCA9ICdodHRwOi8vd3d3LndlYXRoZXIuY29tL3dlYXRoZXIvbG9jYWwvW3Bvc3RhbGNvZGVdJyBXSEVSRSBQa0lEID0gOSIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA0OS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzQ5JywgJzEuNCcpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDUwLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTAnLCAnMCcpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDUxLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTEnLCAnTU0vZGQveXl5eScpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDUyLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTInLCAnaHR0cDovL21hcHMuZ29vZ2xlLmNvbS8nKSIpOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1My4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzUzJywgJy0xJykiKTsNCiAgICAgICAgICAgICAgICAgICAgJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNTQuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc1NCcsICcwJykiKTsNCiAgICAgICAgICAgICAgICAgICAgJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNTUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc1NScsIE5VTEwpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDU2Li4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTYnLCAwKSIpOw0KDQoJCQljYXNlIDI6DQogICAgICAgICAgICAgICAgICAgIGVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMikgPyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4O2ZvbnQtd2VpZ2h0OmJvbGQ7Ij5VcGdyYWRpbmcgZnJvbSAxLjQgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCg0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDcmVhdGluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gInRlbXBsYXRlczwvaT4gVGFibGUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiQ1JFQVRFIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gInRlbXBsYXRlc2AgKGBQa0lEYCBJTlQoMTEpIFVOU0lHTkVEIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULGBOYW1lYCBWQVJDSEFSKDI1NSkgTlVMTCBERUZBVUxUICcnLGBDb250ZW50YCBMT05HVEVYVCBOVUxMIERFRkFVTFQgTlVMTCxgSGVhZGVyYCBNRURJVU1URVhUIE5VTEwgREVGQVVMVCBOVUxMLGBGb290ZXJgIE1FRElVTVRFWFQgTlVMTCBERUZBVUxUIE5VTEwsYEV4dGVuc2lvbmAgVkFSQ0hBUigxNSkgTlVMTCBERUZBVUxUIE5VTEwsYFR5cGVJRGAgSU5UKDExKSBVTlNJR05FRCBOVUxMIERFRkFVTFQgJzEnLGBHcm91cEJ5YCBTTUFMTElOVCgzKSBVTlNJR05FRCBOT1QgTlVMTCBERUZBVUxUICcxJyxgU29ydEJ5YCBTTUFMTElOVCgzKSBVTlNJR05FRCBOT1QgTlVMTCBERUZBVUxUICcxJyxgQ2xlYW5VcGAgTUVESVVNVEVYVCBOVUxMIERFRkFVTFQgTlVMTCxgSXNBY3RpdmVgIFNNQUxMSU5UKDMpIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzAnLFBSSU1BUlkgS0VZIChgUGtJRGApKVR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KICAgICAgICAgICAgICAgICAgICBlY2hvICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Ij4mbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDsmbmJzcDtDcmVhdGluZyBEZWZhdWx0IFRlbXBsYXRlcy4uLic7DQogICAgICAgICAgICAgICAgICAgIG15c3FsX3F1ZXJ5KCJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJ0ZW1wbGF0ZXNgIChgUGtJRGAsIGBOYW1lYCwgYENvbnRlbnRgLCBgSGVhZGVyYCwgYEZvb3RlcmAsIGBFeHRlbnNpb25gLCBgVHlwZUlEYCwgYEdyb3VwQnlgLCBgU29ydEJ5YCwgYENsZWFuVXBgLCBgSXNBY3RpdmVgKSBWQUxVRVMgKCcxJywgJ0luRGVzaWduIC0gQnkgQ2F0ZWdvcnknLCAnfE5cclxuJCRbY2F0ZWdvcnlfdW5pcXVlXXxOXHJcbiQkW2RhdGVfdW5pcXVlXXxOXHJcbltldmVudF90aXRsZV0uIFtldmVudF90aW1lX3N0YXJ0XS4gW2xvY19uYW1lXSwgW2xvY19hZGRyZXNzXSwgW2xvY19jaXR5XS4gW2Rlc2Nfbm90YWdzXSBbY29udGFjdF91cmxdIFtldmVudF9jb3N0XScsICcnLCAnJywgJy50eHQnLCAnMScsICcxJywgJzAnLCAnXCRcJEJMQU5LfE5cclxuIEJMQU5LLFxyXG4gQkxBTksuXHJcbkJMQU5LJywgJzEnKSIpOw0KICAgICAgICAgICAgICAgICAgICBteXNxbF9xdWVyeSgiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAidGVtcGxhdGVzYCAoYFBrSURgLCBgTmFtZWAsIGBDb250ZW50YCwgYEhlYWRlcmAsIGBGb290ZXJgLCBgRXh0ZW5zaW9uYCwgYFR5cGVJRGAsIGBHcm91cEJ5YCwgYFNvcnRCeWAsIGBDbGVhblVwYCwgYElzQWN0aXZlYCkgVkFMVUVTICgnMicsICdJbkRlc2lnbiAtIEJ5IERhdGUnLCAnfE5cclxuIyNbZGF0ZV91bmlxdWVdfE5cclxuIyNbY2F0ZWdvcnlfdW5pcXVlXXxOXHJcbltldmVudF90aXRsZV0uIFtldmVudF90aW1lX3N0YXJ0XS4gW2xvY19uYW1lXSwgW2xvY19hZGRyZXNzXSwgW2xvY19jaXR5XS4gW2Rlc2Nfbm90YWdzXSBbY29udGFjdF91cmxdIFtldmVudF9jb3N0XScsICcnLCAnJywgJy50eHQnLCAnMScsICcwJywgJzEnLCAnIyNCTEFOS3xOXHJcbiBCTEFOSyxcclxuIEJMQU5LLlxyXG5CTEFOSycsICcxJykiKTsNCiAgICAgICAgICAgICAgICAgICAgbXlzcWxfcXVlcnkoIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInRlbXBsYXRlc2AgKGBQa0lEYCwgYE5hbWVgLCBgQ29udGVudGAsIGBIZWFkZXJgLCBgRm9vdGVyYCwgYEV4dGVuc2lvbmAsIGBUeXBlSURgLCBgR3JvdXBCeWAsIGBTb3J0QnlgLCBgQ2xlYW5VcGAsIGBJc0FjdGl2ZWApIFZBTFVFUyAoJzMnLCAnQ1NWIC0gQWxsIFZhcmlhYmxlcycsICdbZXZlbnRfaWRdLFtldmVudF90aXRsZV0sW2Rlc2Nfbm90YWdzXSxbZXZlbnRfZGF0ZV0sW2V2ZW50X3RpbWVfc3RhcnRdLFtldmVudF90aW1lX2VuZF0sW2V2ZW50X2Nvc3RdLFtldmVudF9iaWxsYm9hcmRdLFtjb250YWN0X25hbWVdLFtjb250YWN0X2VtYWlsXSxbY29udGFjdF9waG9uZV0sW3NwYWNlXSxbc3BhY2VfdXNlZF0sW3NwYWNlX2xlZnRdLFtsb2NfbmFtZV0sW2xvY19hZGRyZXNzXSxbbG9jX2FkZHJlc3MyXSxbbG9jX2NpdHldLFtsb2NfcmVnaW9uXSxbbG9jX3Bvc3RhbF0sW2xvY19jb3VudHJ5XVxyXG4nLCAnZXZlbnRfaWQsZXZlbnRfdGl0bGUsZXZlbnRfZGVzYyxldmVudF9kYXRlLGV2ZW50X3RpbWVfc3RhcnQsZXZlbnRfdGltZV9lbmQsZXZlbnRfY29zdCxldmVudF9iaWxsYm9hcmQsY29udGFjdF9uYW1lLGNvbnRhY3RfZW1haWwsY29udGFjdF9waG9uZSxzcGFjZSxzcGFjZV91c2VkLHNwYWNlX2xlZnQsbG9jX25hbWUsbG9jX2FkZHJlc3MsbG9jX2FkZHJlc3MyLGxvY19jaXR5LGxvY19yZWdpb24sbG9jX3Bvc3RhbCxsb2NfY291bnRyeVxyXG4nLCAnXHJcbi9lb2YnLCAnLmNzdicsICcxJywgJzEnLCAnMScsICdCTEFOSycsICcxJykiKTsNCiAgICAgICAgICAgICAgICAgICAgbXlzcWxfcXVlcnkoIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInRlbXBsYXRlc2AgKGBQa0lEYCwgYE5hbWVgLCBgQ29udGVudGAsIGBIZWFkZXJgLCBgRm9vdGVyYCwgYEV4dGVuc2lvbmAsIGBUeXBlSURgLCBgR3JvdXBCeWAsIGBTb3J0QnlgLCBgQ2xlYW5VcGAsIGBJc0FjdGl2ZWApIFZBTFVFUyAoJzQnLCAnUXVhcmsgLSBDdXN0b20gTGF5b3V0JywgJ1xyXG5AZXZlbnQgaGVhZDpbY2F0ZWdvcnlfdW5pcXVlXVxyXG5AZGF0ZSBoZWFkOltkYXRlX3Nlcmllc11cclxuQGNhbGVuZGFyIGNvcHk6W2Rlc2Nfbm90YWdzXVxyXG5cclxuJywgJzxIZWxpb3MgT3V0cHV0PicsICcnLCAnLnR4dCcsICcxJywgJzMnLCAnMCcsICdAZXZlbnQgaGVhZDpCTEFOS1xyXG5AZGF0ZSBoZWFkOkJMQU5LXHJcbkBjYWxlbmRhciBjb3B5OkJMQU5LJywgJzEnKSIpOw0KICAgICAgICAgICAgICAgICAgICBteXNxbF9xdWVyeSgiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAidGVtcGxhdGVzYCAoYFBrSURgLCBgTmFtZWAsIGBDb250ZW50YCwgYEhlYWRlcmAsIGBGb290ZXJgLCBgRXh0ZW5zaW9uYCwgYFR5cGVJRGAsIGBHcm91cEJ5YCwgYFNvcnRCeWAsIGBDbGVhblVwYCwgYElzQWN0aXZlYCkgVkFMVUVTICgnNScsICdDdXN0b20gWE1MIEZpbGUnLCAnICAgPGV2ZW50IGlkPVwnW2V2ZW50X2lkXVwnPlxyXG4gICAgICA8ZGVzY3JpcHRpb24gdGl0bGU9XCdbZXZlbnRfdGl0bGVdXCc+W2Rlc2Nfbm90YWdzXTwvZGVzY3JpcHRpb24+XHJcbiAgICAgIDxkYXRlIGZvcm1hdD1cJ20vZC95XCc+W2V2ZW50X2RhdGVdPC9kYXRlPlxyXG4gICAgICA8dGltZT5cclxuICAgICAgICAgPHN0YXJ0IGhvdXJzPVwnMTJcJyBmb3JtYXQ9XCdIOk06U1wnPltldmVudF90aW1lX3N0YXJ0XTwvc3RhcnQ+XHJcbiAgICAgICAgIDxlbmQgaG91cnM9XCcxMlwnIGZvcm1hdD1cJ0g6TTpTXCc+W2V2ZW50X3RpbWVfZW5kXTwvZW5kPlxyXG4gICAgICA8L3RpbWU+XHJcbiAgICAgIDxjb3N0IGN1cnJlbmN5PVwnJFwnPltldmVudF9jb3N0XTwvY29zdD5cclxuICAgICAgPGNvbnRhY3Q+XHJcbiAgICAgICAgIDxuYW1lPltjb250YWN0X25hbWVdPC9uYW1lPlxyXG4gICAgICAgICA8ZW1haWw+W2NvbnRhY3RfZW1haWxdPC9lbWFpbD5cclxuICAgICAgICAgPHBob25lIHByZWZpeD1cJysxXCc+W2NvbnRhY3RfcGhvbmVdPC9waG9uZT5cclxuICAgICAgICAgPHdlYnNpdGUgdXJsPVwnW2NvbnRhY3RfdXJsXVwnIC8+XHJcbiAgICAgIDwvY29udGFjdD5cclxuICAgICAgPGxvY2F0aW9uPlxyXG4gICAgICAgICA8bmFtZT5bbG9jX25hbWVdPC9uYW1lPlxyXG4gICAgICAgICA8YWRkcmVzcz5bbG9jX2FkZHJlc3NdPC9hZGRyZXNzPlxyXG4gICAgICAgICA8YWRkcmVzczI+W2xvY19hZGRyZXNzMl08L2FkZHJlc3MyPlxyXG4gICAgICAgICA8Y2l0eT5bbG9jX2NpdHldPC9jaXR5PlxyXG4gICAgICAgICA8c3RhdGU+W2xvY19jaXR5XTwvc3RhdGU+XHJcbiAgICAgICAgIDx6aXA+W2xvY19wb3N0YWxdPC96aXA+XHJcbiAgICAgICAgIDxjb3VudHJ5Pltsb2NfY291bnRyeV08L2NvdW50cnk+XHJcbiAgICAgICAgIDx3ZWJzaXRlIHVybD1cJ1tsb2NfdXJsXVwnIC8+XHJcbiAgICAgIDwvbG9jYXRpb24+XHJcbiAgIDwvZXZlbnQ+XHJcbicsICc8P3htbCB2ZXJzaW9uPVwnMS4wXCc/PlxyXG48Y2FsZW5kYXI+XHJcbiAgIDx3ZWJzaXRlIHVybD1cJ1tjYWxfdXJsXVwnIC8+XHJcbicsICc8L2NhbGVuZGFyPicsICcueG1sJywgJzEnLCAnMScsICcxJywgJ0JMQU5LJywgJzEnKSIpOw0KICAgICAgICAgICAgICAgICAgICBteXNxbF9xdWVyeSgiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAidGVtcGxhdGVzYCAoYFBrSURgLCBgTmFtZWAsIGBDb250ZW50YCwgYEhlYWRlcmAsIGBGb290ZXJgLCBgRXh0ZW5zaW9uYCwgYFR5cGVJRGAsIGBHcm91cEJ5YCwgYFNvcnRCeWAsIGBDbGVhblVwYCwgYElzQWN0aXZlYCkgVkFMVUVTICgnNycsICdDU1YgLSBJbXBvcnQgRm9ybWF0JywgJ1tldmVudF90aXRsZV0sW2V2ZW50X2Rlc2NyaXB0aW9uXSxbZXZlbnRfY29zdF0sW2V2ZW50X2RhdGVdLFtldmVudF90aW1lX3N0YXJ0XSxbZXZlbnRfdGltZV9lbmRdLE5VTEwsTlVMTCxbbG9jX25hbWVdLFtsb2NfYWRkcmVzc10sW2xvY19hZGRyZXNzMl0sW2xvY19jaXR5XSxbbG9jX3JlZ2lvbl0sW2xvY19wb3N0YWxdLFtsb2NfY291bnRyeV0sW2NvbnRhY3RfbmFtZV0sW2NvbnRhY3RfZW1haWxdLFtjb250YWN0X3Bob25lXSxbZXZlbnRfYmlsbGJvYXJkXSxbZXZlbnRfc2VyaWVzaWRdLFtzcGFjZV0sW3NwYWNlX2xlZnRdXHJcbicsICdFdmVudFRpdGxlLERlc2NyaXB0aW9uLENvc3QsRXZlbnREYXRlLFN0YXJ0VGltZSxFbmRUaW1lLEFsbERheSxMb2NhdGlvbklELExvY2F0aW9OYW1lLExvY2F0aW9uQWRkcmVzcyxMb2NhdGlvbkFkZHJlc3MyLExvY2F0aW9uQ2l0eSxMb2NhdGlvblN0YXRlLExvY2F0aW9uWmlwLExvY2F0aW9uQ291bnRyeSxDb250YWN0TmFtZSxDb250YWN0RW1haWwsQ29udGFjdFBob25lLENvbnRhY3RVUkwsQmlsbGJvYXJkLFNlcmllc0lELFJlZ2lzdHJhdGlvbixTcGFjZUF2YWlsYWJsZVxyXG4nLCAnL2VvZicsICcuY3N2JywgJzEnLCAnMScsICcxJywgTlVMTCwgJzEnKSIpOw0KICAgICAgICAgICAgICAgICAgICBlY2hvICc8Yj5GaW5pc2hlZDwvYj48L2Rpdj4nOw0KICAgICAgICAgICAgICAgICAgICAkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgU2V0dGluZyA1Ny4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJJTlNFUlQgSU5UTyBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgKGBQa0lEYCwgYFNldHRpbmdWYWx1ZWApIFZBTFVFUyAoJzU3JywgTlVMTCkiKTsNCiAgICAgICAgICAgICAgICAgICAgJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNTguLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc1OCcsIE5VTEwpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDU5Li4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNTknLCAnI3R3ZWV0bWVudCcpIik7DQogICAgICAgICAgICAgICAgICAgICRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiPC9pPmV2ZW50cyBUYWJsZS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJldmVudHNgICBBREQgQ09MVU1OIGBTaG9ydFVSTGAgVkFSQ0hBUig1MCkgTlVMTCBERUZBVUxUIE5VTEwgQUZURVIgYExvY0NvdW50cnlgLCBBREQgQ09MVU1OIGBUd2VldG1lbnRzYCBJTlQoMTEpIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzAnIEFGVEVSIGBTaG9ydFVSTGAiKTsNCg0KCQkJY2FzZSAzOg0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMykgPyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4O2ZvbnQtd2VpZ2h0OmJvbGQ7Ij5VcGdyYWRpbmcgZnJvbSAxLjQuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KDQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICJ0ZW1wbGF0ZXM8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gInRlbXBsYXRlc2AgQUREIENPTFVNTiBgRGF0ZUZvcm1hdGAgVElOWUlOVCgzKSBOT1QgTlVMTCBERUZBVUxUICcwJyBBRlRFUiBgQ2xlYW5VcGAiKTsNCgkJCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBbHRlcmluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gImxvY2F0aW9ubmV0d29yazwvaT4gVGFibGUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiQUxURVIgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAibG9jYXRpb25uZXR3b3JrYCBBREQgQ09MVU1OIGBJc0Rvd25sb2FkYCBUSU5ZSU5UKDMpIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzEnIEFGVEVSIGBOZXR3b3JrVHlwZWAiKTsNCgkJCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDbGVhcmluZyBVbnVzZWQgU2V0dGluZyBWYWx1ZXMuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiVVBEQVRFIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCBTRVQgYFNldHRpbmdWYWx1ZWAgPSBOVUxMIFdIRVJFIGBQa0lEYCBJTiAoNSw2LDQ4LDUwLDU0KSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIlVwZGF0aW5nIFNldHRpbmcgMjUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiVVBEQVRFIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCBTRVQgYFNldHRpbmdWYWx1ZWAgPSAnMzAnIFdIRVJFIGBQa0lEYCBJTiAoMjUpIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjAuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2MCcsIE5VTEwpIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjEuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2MScsIE5VTEwpIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjIuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2MicsIE5VTEwpIik7DQoNCgkJCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJDcmVhdGluZyA8aT4iIC4gSENfVGJsUHJlZml4IC4gImFkbWlubm90aWNlczwvaT4gVGFibGUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiQ1JFQVRFIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImFkbWlubm90aWNlc2AgKGBQa0lEYCBJTlQoMTEpIFVOU0lHTkVEIE5PVCBOVUxMIEFVVE9fSU5DUkVNRU5ULGBBZG1pbklEYCBJTlQoMTEpIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzAnLGBUeXBlSURgIElOVCgxMSkgVU5TSUdORUQgTk9UIE5VTEwgREVGQVVMVCAnMCcsYElzQWN0aXZlYCBJTlQoMTEpIFVOU0lHTkVEIE5PVCBOVUxMIERFRkFVTFQgJzAnLFBSSU1BUlkgS0VZIChgUGtJRGApKVR5cGU9TXlJU0FNIENIQVJBQ1RFUiBTRVQgdXRmOCBDT0xMQVRFIHV0ZjhfZ2VuZXJhbF9jaSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkNyZWF0aW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NtZXRhPC9pPiBUYWJsZS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJDUkVBVEUgVEFCTEUgYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NtZXRhYCAoYFBrSURgIElOVCgxMSkgTk9UIE5VTEwgQVVUT19JTkNSRU1FTlQsYEtleXdvcmRzYCBURVhUIE5VTEwsYERlc2NyaXB0aW9uYCBURVhUIE5VTEwsYFRpdGxlYCBURVhUIE5VTEwsUFJJTUFSWSBLRVkgKGBQa0lEYCkpIEVOR0lORT1NeUlTQU0gQ0hBUkFDVEVSIFNFVCB1dGY4IENPTExBVEUgdXRmOF9nZW5lcmFsX2NpIik7DQoJCQkJZm9yKCRpPTE7JGk8PTE3OyRpKyspew0KCQkJCQkkc3RhdHVzID0gZG9VcGdyYWRlKCRzdGF0dXMsICJBZGRpbmcgTWV0YSBTZXR0aW5nICIgLiAkaSAuICIuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NtZXRhYCAoYFBrSURgLCBgS2V5d29yZHNgLGBEZXNjcmlwdGlvbmAsYFRpdGxlYCkgVkFMVUVTICgnIiAuICRpIC4gIicsTlVMTCxOVUxMLE5VTEwpIik7DQoJCQkJfS8vZW5kIGZvcg0KDQoJCQljYXNlIDQ6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA0KSA/ICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Zm9udC13ZWlnaHQ6Ym9sZDsiPlVwZ3JhZGluZyBmcm9tIDEuNSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiYWRtaW48L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImFkbWluYCBBREQgQ09MVU1OIGBBY2Nlc3NgIFZBUkNIQVIoMzIpIE5VTEwgQUZURVIgYFBDS2V5YCIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAiZXZlbnRzPC9pPiBUYWJsZS4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJBTFRFUiBUQUJMRSBgIiAuIEhDX1RibFByZWZpeCAuICJldmVudHNgIENIQU5HRSBDT0xVTU4gYExvY2F0aW9uWmlwYCBgTG9jYXRpb25aaXBgIFZBUkNIQVIoNTApIE5VTEwgQUZURVIgYExvY2F0aW9uU3RhdGVgIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWx0ZXJpbmcgPGk+IiAuIEhDX1RibFByZWZpeCAuICJsb2NhdGlvbnM8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gImxvY2F0aW9uc2AgQ0hBTkdFIENPTFVNTiBgWmlwYCBgWmlwYCBWQVJDSEFSKDUwKSBOVUxMIEFGVEVSIGBDb3VudHJ5YCIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFsdGVyaW5nIDxpPiIgLiBIQ19UYmxQcmVmaXggLiAicmVnaXN0cmFudHM8L2k+IFRhYmxlLi4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIkFMVEVSIFRBQkxFIGAiIC4gSENfVGJsUHJlZml4IC4gInJlZ2lzdHJhbnRzYCBDSEFOR0UgQ09MVU1OIGBaaXBgIGBaaXBgIFZBUkNIQVIoNTApIE5VTEwgQUZURVIgYFN0YXRlYCIpOw0KDQoJCQljYXNlIDU6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA1KSA/ICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Zm9udC13ZWlnaHQ6Ym9sZDsiPlVwZ3JhZGluZyBmcm9tIDEuNS4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoNCgkJCQllY2hvICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Ij5VcGRhdGluZyBUd2VldCBMb2dzLi4uJzsNCgkJCQlteXNxbF9xdWVyeSgiVVBEQVRFIGAiIC4gSENfVGJsUHJlZml4IC4gImV2ZW50bmV0d29yayBTRVQgYE5ldHdvcmtJRGAgPSBSSUdIVChOZXR3b3JrSUQsKExPQ0FURSgnLycsIFJFVkVSU0UoTmV0d29ya0lEKSktMSkpIFdIRVJFIE5ldHdvcmtUeXBlID0gMzsiKTsNCgkJCQllY2hvICc8Yj5GaW5pc2hlZDwvYj48L2Rpdj4nOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIlVwZGF0aW5nIFNldHRpbmcgNDYgJiA0Ny4uLiIsDQogICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICJVcGRhdGUgYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIFNFVCBTZXR0aW5nVmFsdWUgPSBOVUxMIFdIRVJFIFBrSUQgSU4gKDQ2LDQ3KSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIlVwZGF0aW5nIFNldHRpbmcgNDguLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiVXBkYXRlIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCBTRVQgU2V0dGluZ1ZhbHVlID0gJzAnIFdIRVJFIFBrSUQgPSAnNDgnIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjMuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2MycsIE5VTEwpIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjQuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2NCcsIE5VTEwpIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjUuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2NScsIDApIik7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNjYuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc2NicsIDEwKSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDY3Li4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNjcnLCBOVUxMKSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDY4Li4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNjgnLCBOVUxMKSIpOw0KCQkJCSRzdGF0dXMgPSBkb1VwZ3JhZGUoJHN0YXR1cywgIkFkZGluZyBTZXR0aW5nIDY5Li4uIiwNCiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIklOU0VSVCBJTlRPIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCAoYFBrSURgLCBgU2V0dGluZ1ZhbHVlYCkgVkFMVUVTICgnNjknLCBOVUxMKSIpOw0KDQoJCQljYXNlIDY6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA2KSA/ICc8ZGl2IHN0eWxlPSJwYWRkaW5nLWxlZnQ6NXB4O2xpbmUtaGVpZ2h0OjE1cHg7Zm9udC13ZWlnaHQ6Ym9sZDsiPlVwZ3JhZGluZyBmcm9tIDEuNS4yIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJJHN0YXR1cyA9IGRvVXBncmFkZSgkc3RhdHVzLCAiQWRkaW5nIFNldHRpbmcgNzAuLi4iLA0KICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAiSU5TRVJUIElOVE8gYCIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3NgIChgUGtJRGAsIGBTZXR0aW5nVmFsdWVgKSBWQUxVRVMgKCc3MCcsICcxJykiKTsNCg0KCQkJY2FzZSA3Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gNykgPyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4O2ZvbnQtd2VpZ2h0OmJvbGQ7Ij5VcGdyYWRpbmcgZnJvbSAxLjYgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCg0KDQoJCQkvLwlBbGwgVXBncmFkZXMNCiAgICAgICAgICAgICAgICAgICAgbXlzcWxfcXVlcnkoIlVQREFURSBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgU0VUIGBTZXR0aW5nVmFsdWVgID0gJyIgLiAkaGNfdXBnVmVyIC4gIicgV0hFUkUgUGtJRCA9ICc0OSciKTsNCiAgICAgICAgICAgICAgICAgICAgZWNobyAnPC9maWVsZHNldD48YnIgLz4nOw0KICAgICAgICAgICAgICAgICAgICBlY2hvICc8ZmllbGRzZXQgc3R5bGU9InBhZGRpbmc6MTBweDsiPic7DQogICAgICAgICAgICAgICAgICAgIGVjaG8gJzxsZWdlbmQ+VXBncmFkZSBSZXN1bHRzPC9sZWdlbmQ+JzsNCg0KICAgICAgICAgICAgICAgICAgICBpZigkc3RhdHVzID09IDApew0KICAgICAgICAgICAgICAgICAgICAgICAgIGVjaG8gJ1VwZ3JhZGUgQ29tcGxldGVkIFN1Y2Nlc3NmdWxseS48YnIgLz48YnIgLz5EZWxldGUgdGhpcyBmaWxlIGFuZCB5b3VyIGNhY2hlIGZpbGVzIHRvIGNvbXBsZXRlIHlvdXIgdXBncmFkZS4nOw0KICAgICAgICAgICAgICAgICAgICB9IGVsc2Ugew0KICAgICAgICAgICAgICAgICAgICAgICAgIGVjaG8gJ09uZSBvciBtb3JlIG9mIHRoZSB1cGdyYWRlcyBmYWlsZWQuIFRoaXMgaXMgbW9zdCBjb21tb25seSBjYXVzZWQgYnkgaW1wcm9wZXIgY29uZmlndXJhdGlvbiBvZiB5b3VyIE15U1FMIHVzZXIgcHJpdmlsZWdlcy4gWW91IG1heSBuZWVkIHRvIHJlc3RvcmUgeW91ciBkYXRhYmFzZSBmcm9tIGEgYmFja3VwIGJlZm9yZSBydW5uaW5nIHRoaXMgdXBncmFkZSBhZ2Fpbi4nOw0KICAgICAgICAgICAgICAgICAgICAgICAgIGVjaG8gJzxiciAvPjxiciAvPic7DQogICAgICAgICAgICAgICAgICAgICAgICAgZWNobyAnSWYgeW91IGV4cGVyaWVuY2UgY29udGludWVkIGRpZmZpY3VsdHkgcGxlYXNlIGFjY2VzcyB0aGUgUmVmcmVzaCBDb21tdW5pdHkgZm9ydW0gb3Igc3VibWl0IGEgc3VwcG9ydCB0aWNrZXQgZnJvbSB5b3VyIG1lbWJlciBhY2NvdW50Lic7DQogICAgICAgICAgICAgICAgICAgIH0vL2VuZCBpZg0KICAgICAgICAgICAgICAgYnJlYWs7DQogICAgICAgICAgfS8vZW5kIHN3aXRjaA0KICAgICB9IGVsc2Ugew0KICAgICAgICAgIGVjaG8gJ1VwZ3JhZGUgZmFpbGVkLiA8YSBocmVmPSJodHRwczovL3d3dy5yZWZyZXNobXkuY29tL21lbWJlcnMvIiBjbGFzcz0iZXZlbnRNYWluIj5Db250YWN0IFJlZnJlc2ggU3VwcG9ydCBmb3IgYXNzaXN0YW5jZS48L2E+JzsNCiAgICAgfS8vZW5kIGlmDQogICAgIGVjaG8gJzwvZmllbGRzZXQ+PGJyIC8+JzsNCn0vL2VuZCBpZg=='));
			}//end if	?>
			<br />
		</div>
	</div>
	<div id="controls" align="center">
		<img src="<?php echo CalAdminRoot;?>/images/logo.png" width="235" alt="" border="0">
		
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