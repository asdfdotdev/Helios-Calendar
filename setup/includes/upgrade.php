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
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice.	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!preg_match('/^http:\/\/'.strtolower($_SERVER['SERVER_NAME']).'/', strtolower(CalRoot))){
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
				<select name="uID" id="uID" required="required">
					<option value="0">Select Current Version</option>
					<option value="13">2.0.2</option>
					<option value="12">2.0.1</option>
					<option value="11">2.0</option>
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
		eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIENhbGVuZGFyIFNvZnR3YXJlIExpY2Vuc2UgQWdyZWVtZW50LiovDQplY2hvICcNCgkJPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KDQokaGNfdXBnVmVyID0gIjIuMSI7DQokZGJjID0gbXlzcWxfY29ubmVjdChEQl9IT1NULCBEQl9VU0VSLCBEQl9QQVNTKTsNCm15c3FsX3NlbGVjdF9kYihEQl9OQU1FLCRkYmMpOw0KJHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgU2V0dGluZ1ZhbHVlIEZST00gIiAuIEhDX1RibFByZWZpeCAuInNldHRpbmdzIFdIRVJFIFBrSUQgPSAxOSIpOw0KJHN0YXR1cyA9ICRub19maWxlID0gMDsNCiRob3N0ID0gInZhbGlkYXRlLnJlZnJlc2hteS5jb20iOw0KJGZpbGUgPSAiL2gucGhwP3Y9IiAuICRoY191cGdWZXIgLiAiJmM9IiAuIG15c3FsX3Jlc3VsdCgkcmVzdWx0LDAsMCkgIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQppZighKCRmcCA9IGZzb2Nrb3BlbigkaG9zdCwgODAsICRlcnJubywgJGVycnN0ciwgMSkpICl7DQogICAgIGVjaG8gJw0KCQk8cD5VcGdyYWRlIGNhbm5vdCBiZSBydW4uIDxhIGhyZWY9Imh0dHBzOi8vd3d3LnJlZnJlc2hteS5jb20vbWVtYmVycy8iPkNvbnRhY3QgUmVmcmVzaCBTdXBwb3J0IGZvciBhc3Npc3RhbmNlLjwvYT48L3A+JzsNCn0gZWxzZSB7DQogICAgIGZ1bmN0aW9uIGRvVXBncmFkZSgkc3RhdHVzLCAkbXNnLCAkcXVlcnkpew0KICAgICAgICAgIGVjaG8gJzxkaXYgc3R5bGU9InBhZGRpbmctbGVmdDo1cHg7bGluZS1oZWlnaHQ6MTVweDsiPicgLiAkbXNnOw0KICAgICAgICAgIGlmKG15c3FsX3F1ZXJ5KCRxdWVyeSkpew0KICAgICAgICAgICAgICAgZWNobyAnPGI+RmluaXNoZWQ8L2I+JzsNCiAgICAgICAgICB9IGVsc2Ugew0KICAgICAgICAgICAgICAgKyskc3RhdHVzOw0KICAgICAgICAgICAgICAgZWNobyAnPHNwYW4gY2xhc3M9ImVycm9yIj5GYWlsZWQ8L3NwYW4+JzsNCiAgICAgICAgICB9DQogICAgICAgICAgZWNobyAnPC9kaXY+JzsNCiAgICAgICAgICByZXR1cm4gJHN0YXR1czsNCiAgICAgfQ0KICAgICAkcmVhZCA9ICIiOw0KICAgICAkcmVxdWVzdCA9ICJHRVQgJGZpbGUgSFRUUC8xLjFcclxuIjsNCiAgICAgJHJlcXVlc3QgLj0gIkhvc3Q6ICRob3N0XHJcbiI7DQogICAgICRyZXF1ZXN0IC49ICJDb25uZWN0aW9uOiBDbG9zZVxyXG5cclxuIjsNCiAgICAgZndyaXRlKCRmcCwgJHJlcXVlc3QpOw0KDQogICAgIHdoaWxlICghZmVvZigkZnApKXsNCiAgICAgICAgICAkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQoJfQ0KDQoJDQogICAgICRvdXRwdXQgPSBleHBsb2RlKCJoZWxpb3MvLyIsICRyZWFkKTsNCiAgICAgaWYoaXNzZXQoJG91dHB1dFsxXSkpew0KICAgICAgICAgIHN3aXRjaCgkX1BPU1RbJ3VJRCddKXsNCiAgICAgICAgICAgICAgIGNhc2UgMToNCiAgICAgICAgICAgICAgICAgICAgZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAxKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuMy4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvNTRhY2I1N2FmYi50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzU0YWNiNTdhZmIudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDI6DQogICAgICAgICAgICAgICAgICAgIGVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMikgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjQgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS85ZTYwNWNlM2JiLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvOWU2MDVjZTNiYi50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgMzoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDMpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS40LjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS8xZTZkYmZhYWEwLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvMWU2ZGJmYWFhMC50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgNDoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDQpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS41IHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvNjAwODY0NzI3Ny50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzYwMDg2NDcyNzcudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDU6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA1KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNS4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvM2VmMDRiMWM0Yi50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzNlZjA0YjFjNGIudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDY6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA2KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNS4yIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvMGNlYTI0YWUzYi50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzBjZWEyNGFlM2IudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDc6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA3KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNiB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCQ0KCQkJY2FzZSA4Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gOCkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjYuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhL2UzNGNmOTc3NGUudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS9lMzRjZjk3NzRlLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSA5Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gOSkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjcgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS83ZmQzY2RhYWJhLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvN2ZkM2NkYWFiYS50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgMTA6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAxMCkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjcuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzkxYTU3MDc2ODAudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS85MWE1NzA3NjgwLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSAxMToNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDExKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDIuMCB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhL3g0ODdrajMwMjEudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS94NDg3a2ozMDIxLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJDQoJCQljYXNlIDEyOg0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMTIpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMi4wLjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQkNCgkJCQkNCgkJCWNhc2UgMTM6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAxMikgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAyLjAuMiB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhL3hmMzY5ODVyM2QudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS94ZjM2OTg1cjNkLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJDQoJCQkJDQoJCQkvLwlBbGwgVXBncmFkZXMNCiAgICAgICAgICAgICAgICAgICAgbXlzcWxfcXVlcnkoIlVQREFURSBgIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5nc2AgU0VUIGBTZXR0aW5nVmFsdWVgID0gJyIgLiAkaGNfdXBnVmVyIC4gIicgV0hFUkUgUGtJRCA9ICc0OSciKTsNCiAgICAgICAgICAgICAgICAgICAgZWNobyAnDQoJCQk8L2ZpZWxkc2V0Pg0KDQoJCQk8ZmllbGRzZXQgc3R5bGU9InBhZGRpbmc6MTBweDsiPg0KCQkJCTxsZWdlbmQ+VXBncmFkZSBSZXN1bHRzPC9sZWdlbmQ+JzsNCg0KICAgICAgICAgICAgICAgICAgICBpZigkc3RhdHVzID09IDAgJiYgJG5vX2ZpbGUgPT0gMCl7DQogICAgICAgICAgICAgICAgICAgICAgICAgZWNobyAnDQoJCQkJPHA+DQoJCQkJCVVwZ3JhZGUgU3VjY2Vzc2Z1bC48YnIgLz48YnIgLz5EZWxldGUgdGhlIC9zZXR1cCBkaXJlY3RvcnkgYW5kIHlvdXIgY2FjaGUgZmlsZXMgdG8gY29tcGxldGUgdGhlIHVwZ3JhZGUuDQoJCQkJPC9wPic7DQogICAgICAgICAgICAgICAgICAgIH0gZWxzZSB7DQoJCQkJCWVjaG8gKCRub19maWxlID4gMCkgPyAnPHA+PGI+T25lIG9yIG1vcmUgcmVxdWlyZWQgdXBncmFkZSBkYXRhIGZpbGVzIGFyZSBtaXNzaW5nLjwvYj48L3A+JyA6ICcnOw0KCQkJCQllY2hvICcNCgkJCQk8cD4NCgkJCQkJT25lIG9yIG1vcmUgb2YgdGhlIHVwZ3JhZGVzIGZhaWxlZC4gVGhpcyBpcyBtb3N0IGNvbW1vbmx5IGNhdXNlZCBieSBpbXByb3BlciBjb25maWd1cmF0aW9uIG9mIHlvdXIgTXlTUUwgdXNlciBwcml2aWxlZ2VzLiBZb3UgbWF5IG5lZWQgdG8gcmVzdG9yZSB5b3VyIGRhdGFiYXNlIGZyb20gYSBiYWNrdXAgYmVmb3JlIHJ1bm5pbmcgdGhpcyB1cGdyYWRlIGFnYWluLg0KCQkJCTwvcD4nOw0KICAgICAgICAgICAgICAgICAgICB9DQogICAgICAgICAgICAgICBicmVhazsNCiAgICAgICAgICB9DQogICAgIH0gZWxzZSB7DQogICAgICAgICAgZWNobyAnCTxwPg0KDQoJCQkJCVVwZ3JhZGUgZmFpbGVkLiA8YSBocmVmPSJodHRwczovL3d3dy5yZWZyZXNobXkuY29tL21lbWJlcnMvIj5Db250YWN0IFJlZnJlc2ggU3VwcG9ydCBmb3IgYXNzaXN0YW5jZS48L2E+DQoJCQkJPC9wPic7DQogICAgIH0NCiAgICAgZWNobyAnDQoJCQk8L2ZpZWxkc2V0Pic7DQp9'));
	}
?>