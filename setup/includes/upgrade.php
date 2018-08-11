<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!preg_match('/^http:\/\/'.$_SERVER['SERVER_NAME'].'/', CalRoot)){
		   echo '<a href="'.CalRoot.'/setup/">URL mismatch. Click here to run the upgrade.</a>';
		   return(-1);
	}
	
	if(!isset($_POST['uID'])){
		try {
			$dbc = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			mysql_select_db(DB_NAME,$dbc);
			
			$result = mysql_query("SELECT SettingValue, VERSION() FROM " . HC_TblPrefix."settings WHERE PkID = 49");
			if(mysql_result($result,0,0) != ''){
				$_SESSION['mysql_version'] = mysql_result($result,0,1);
				$db_ver = mysql_result($result,0,0);
			}
		} catch(Exception $e) {}
		
		echo '
			<p>
				<b>Step 1)</b> Confirm your Helios Calendar <b>MySQL user has ALTER &amp; CREATE privileges</b> for your Helios database.
			</p>
			<p>
				<b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".
			</p>
			<p>
				<b>Step 3)</b> When the upgrade is complete, delete the /setup directory and cache files from your /cache directory.
			</p>
			<div class="notice">
				<p>Your license &amp; subscription status will be verified. Upgrades are only available to eligible subscribers.</p>
			</div>
			'.(isset($db_ver) ? '<p><b>Current Database Version:</b>&nbsp;'.$db_ver.'</p>':'').'
			<form name="frm" id="frm" method="post" action="index.php" onsubmit="return (document.getElementById(\'uID\').value != 0) ? true : false;">
			<fieldset>
				<legend>Upgrade to Run</legend>
				<select name="uID" id="uID">
					<option value="0">Select Current Version</option>
					<option value="10">1.7.1</option>
					<option value="9">1.7</option>
					<option value="8">1.6.1</option>
					<option value="7">1.6</option>
					<option value="6">1.5.2</option>
					<option value="5">1.5.1</option>
					<option value="4">1.5</option>
					<option value="3">1.4.1</option>
					<option value="2">1.4</option>
					<option value="1">1.3.1</option>
				</select>
				&nbsp;&nbsp;-->&nbsp;&nbsp;<b>'.$curVersion.'</b>
			</fieldset>
			<input name="submit" id="submit" type="submit" value=" Run Upgrade " />
			</form>';
	} else {
		set_time_limit(300);
		echo '
			<p>
				<strike><b>Step 1)</b> Confirm that your Helios <b>MySQL User has ALTER &amp; CREATE permissions</b> for your Helios database.</strike>
			</p>
			<p>
				<strike><b>Step 2)</b> Select the upgrade you wish to run from the list below and click "Run Upgrade".</strike>
			</p>';
		eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIENhbGVuZGFyIFNvZnR3YXJlIExpY2Vuc2UgQWdyZWVtZW50LiovDQplY2hvICcNCgkJPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KDQokaGNfdXBnVmVyID0gIjIuMCI7DQokZGJjID0gbXlzcWxfY29ubmVjdChEQl9IT1NULCBEQl9VU0VSLCBEQl9QQVNTKTsNCm15c3FsX3NlbGVjdF9kYihEQl9OQU1FLCRkYmMpOw0KJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgU2V0dGluZ1ZhbHVlIEZST00gIiAuIEhDX1RibFByZWZpeCAuInNldHRpbmdzIFdIRVJFIFBrSUQgPSAxOSIpOw0KJHN0YXR1cyA9ICRub19maWxlID0gMDsNCiRob3N0ID0gInZhbGlkYXRlLnJlZnJlc2hteS5jb20iOw0KJGZpbGUgPSAiL2gucGhwP3Y9IiAuICRoY191cGdWZXIgLiAiJmM9IiAuIG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQppZighKCRmcCA9IGZzb2Nrb3BlbigkaG9zdCwgODAsICRlcnJubywgJGVycnN0ciwgMSkpICl7DQogICAgIGVjaG8gJw0KCQk8cD5VcGdyYWRlIGNhbm5vdCBiZSBydW4uIDxhIGhyZWY9Imh0dHBzOi8vd3d3LnJlZnJlc2hteS5jb20vbWVtYmVycy8iPkNvbnRhY3QgUmVmcmVzaCBTdXBwb3J0IGZvciBhc3Npc3RhbmNlLjwvYT48L3A+JzsNCn0gZWxzZSB7DQogICAgIGZ1bmN0aW9uIGRvVXBncmFkZSgkc3RhdHVzLCAkbXNnLCAkcXVlcnkpew0KICAgICAgICAgIGVjaG8gJzxkaXYgc3R5bGU9InBhZGRpbmctbGVmdDo1cHg7bGluZS1oZWlnaHQ6MTVweDsiPicgLiAkbXNnOw0KICAgICAgICAgIGlmKG15c3FsX3F1ZXJ5KCRxdWVyeSkpew0KICAgICAgICAgICAgICAgZWNobyAnPGI+RmluaXNoZWQ8L2I+JzsNCiAgICAgICAgICB9IGVsc2Ugew0KICAgICAgICAgICAgICAgKyskc3RhdHVzOw0KICAgICAgICAgICAgICAgZWNobyAnPHNwYW4gY2xhc3M9ImVycm9yIj5GYWlsZWQ8L3NwYW4+JzsNCiAgICAgICAgICB9DQogICAgICAgICAgZWNobyAnPC9kaXY+JzsNCiAgICAgICAgICByZXR1cm4gJHN0YXR1czsNCiAgICAgfQ0KICAgICAkcmVhZCA9ICIiOw0KICAgICAkcmVxdWVzdCA9ICJHRVQgJGZpbGUgSFRUUC8xLjFcclxuIjsNCiAgICAgJHJlcXVlc3QgLj0gIkhvc3Q6ICRob3N0XHJcbiI7DQogICAgICRyZXF1ZXN0IC49ICJDb25uZWN0aW9uOiBDbG9zZVxyXG5cclxuIjsNCiAgICAgZndyaXRlKCRmcCwgJHJlcXVlc3QpOw0KDQogICAgIHdoaWxlICghZmVvZigkZnApKQ0KICAgICAgICAgICRyZWFkIC49IGZyZWFkKCRmcCwxMDI0KTsNCgkNCiAgICAgJG91dHB1dCA9IGV4cGxvZGUoImhlbGlvcy8vIiwgJHJlYWQpOw0KICAgICBpZihpc3NldCgkb3V0cHV0WzFdKSl7DQogICAgICAgICAgc3dpdGNoKCRfUE9TVFsndUlEJ10pew0KICAgICAgICAgICAgICAgY2FzZSAxOg0KICAgICAgICAgICAgICAgICAgICBlY2hvICgkX1BPU1RbJ3VJRCddID09IDEpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS4zLjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS81NGFjYjU3YWZiLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvNTRhY2I1N2FmYi50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgMjoNCiAgICAgICAgICAgICAgICAgICAgZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAyKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNCB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzllNjA1Y2UzYmIudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS85ZTYwNWNlM2JiLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSAzOg0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMykgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjQuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzFlNmRiZmFhYTAudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS8xZTZkYmZhYWEwLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSA0Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gNCkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjUgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS82MDA4NjQ3Mjc3LnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvNjAwODY0NzI3Ny50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgNToNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDUpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS41LjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS8zZWYwNGIxYzRiLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvM2VmMDRiMWM0Yi50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgNjoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDYpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS41LjIgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS8wY2VhMjRhZTNiLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvMGNlYTI0YWUzYi50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgNzoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDcpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS42IHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJDQoJCQljYXNlIDg6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA4KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNi4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvZTM0Y2Y5Nzc0ZS50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhL2UzNGNmOTc3NGUudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDk6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA5KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNyB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzdmZDNjZGFhYmEudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS83ZmQzY2RhYWJhLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSAxMDoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDEwKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNy4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvOTFhNTcwNzY4MC50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzkxYTU3MDc2ODAudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQkvLwlBbGwgVXBncmFkZXMNCiAgICAgICAgICAgICAgICAgICAgbXlzcWxfcXVlcnkoIlVQREFURSBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgU0VUIGBTZXR0aW5nVmFsdWVgID0gJyIgLiAkaGNfdXBnVmVyIC4gIicgV0hFUkUgUGtJRCA9ICc0OSciKTsNCiAgICAgICAgICAgICAgICAgICAgZWNobyAnDQoJCQk8L2ZpZWxkc2V0Pg0KCQkJPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4NCgkJCQk8bGVnZW5kPlVwZ3JhZGUgUmVzdWx0czwvbGVnZW5kPic7DQoNCiAgICAgICAgICAgICAgICAgICAgaWYoJHN0YXR1cyA9PSAwICYmICRub19maWxlID09IDApew0KICAgICAgICAgICAgICAgICAgICAgICAgIGVjaG8gJw0KCQkJCTxwPg0KCQkJCQlVcGdyYWRlIFN1Y2Nlc3NmdWwuPGJyIC8+PGJyIC8+RGVsZXRlIHRoZSAvc2V0dXAgZGlyZWN0b3J5IGFuZCB5b3VyIGNhY2hlIGZpbGVzIHRvIGNvbXBsZXRlIHRoZSB1cGdyYWRlLg0KCQkJCTwvcD4nOw0KICAgICAgICAgICAgICAgICAgICB9IGVsc2Ugew0KCQkJCQllY2hvICgkbm9fZmlsZSA+IDApID8gJzxwPjxiPk9uZSBvciBtb3JlIHJlcXVpcmVkIHVwZ3JhZGUgZGF0YSBmaWxlcyBhcmUgbWlzc2luZy48L2I+PC9wPicgOiAnJzsNCgkJCQkJZWNobyAnDQoJCQkJPHA+DQoJCQkJCU9uZSBvciBtb3JlIG9mIHRoZSB1cGdyYWRlcyBmYWlsZWQuIFRoaXMgaXMgbW9zdCBjb21tb25seSBjYXVzZWQgYnkgaW1wcm9wZXIgY29uZmlndXJhdGlvbiBvZiB5b3VyIE15U1FMIHVzZXIgcHJpdmlsZWdlcy4gWW91IG1heSBuZWVkIHRvIHJlc3RvcmUgeW91ciBkYXRhYmFzZSBmcm9tIGEgYmFja3VwIGJlZm9yZSBydW5uaW5nIHRoaXMgdXBncmFkZSBhZ2Fpbi4NCgkJCQk8L3A+DQoJCQkJPHA+DQoJCQkJCUlmIHlvdSBleHBlcmllbmNlIGNvbnRpbnVlZCBkaWZmaWN1bHR5IHBsZWFzZSBzdWJtaXQgYSBzdXBwb3J0IHRpY2tldCBmcm9tIHlvdXIgbWVtYmVyIGFjY291bnQgZm9yIGFzc2lzdGFuY2UuDQoJCQkJPC9wPic7DQogICAgICAgICAgICAgICAgICAgIH0NCiAgICAgICAgICAgICAgIGJyZWFrOw0KICAgICAgICAgIH0NCiAgICAgfSBlbHNlIHsNCiAgICAgICAgICBlY2hvICcJPHA+DQoJCQkJCVVwZ3JhZGUgZmFpbGVkLiA8YSBocmVmPSJodHRwczovL3d3dy5yZWZyZXNobXkuY29tL21lbWJlcnMvIj5Db250YWN0IFJlZnJlc2ggU3VwcG9ydCBmb3IgYXNzaXN0YW5jZS48L2E+DQoJCQkJPC9wPic7DQogICAgIH0NCiAgICAgZWNobyAnDQoJCQk8L2ZpZWxkc2V0Pic7DQp9'));
	}
?>