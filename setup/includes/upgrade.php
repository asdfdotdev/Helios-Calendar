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
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
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
		eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIENhbGVuZGFyIFNvZnR3YXJlIExpY2Vuc2UgQWdyZWVtZW50LiovDQplY2hvICcNCgkJPGZpZWxkc2V0IHN0eWxlPSJwYWRkaW5nOjEwcHg7Ij4nOw0KDQokaGNfdXBnVmVyID0gIjIuMC4yIjsNCiRkYmMgPSBteXNxbF9jb25uZWN0KERCX0hPU1QsIERCX1VTRVIsIERCX1BBU1MpOw0KbXlzcWxfc2VsZWN0X2RiKERCX05BTUUsJGRiYyk7DQokcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBTZXR0aW5nVmFsdWUgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4ic2V0dGluZ3MgV0hFUkUgUGtJRCA9IDE5Iik7DQokc3RhdHVzID0gJG5vX2ZpbGUgPSAwOw0KJGhvc3QgPSAidmFsaWRhdGUucmVmcmVzaG15LmNvbSI7DQokZmlsZSA9ICIvaC5waHA/dj0iIC4gJGhjX3VwZ1ZlciAuICImYz0iIC4gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSAgLiAiJnU9IiAuICRfU0VSVkVSWydIVFRQX0hPU1QnXTsNCmlmKCEoJGZwID0gZnNvY2tvcGVuKCRob3N0LCA4MCwgJGVycm5vLCAkZXJyc3RyLCAxKSkgKXsNCiAgICAgZWNobyAnDQoJCTxwPlVwZ3JhZGUgY2Fubm90IGJlIHJ1bi4gPGEgaHJlZj0iaHR0cHM6Ly93d3cucmVmcmVzaG15LmNvbS9tZW1iZXJzLyI+Q29udGFjdCBSZWZyZXNoIFN1cHBvcnQgZm9yIGFzc2lzdGFuY2UuPC9hPjwvcD4nOw0KfSBlbHNlIHsNCiAgICAgZnVuY3Rpb24gZG9VcGdyYWRlKCRzdGF0dXMsICRtc2csICRxdWVyeSl7DQogICAgICAgICAgZWNobyAnPGRpdiBzdHlsZT0icGFkZGluZy1sZWZ0OjVweDtsaW5lLWhlaWdodDoxNXB4OyI+JyAuICRtc2c7DQogICAgICAgICAgaWYobXlzcWxfcXVlcnkoJHF1ZXJ5KSl7DQogICAgICAgICAgICAgICBlY2hvICc8Yj5GaW5pc2hlZDwvYj4nOw0KICAgICAgICAgIH0gZWxzZSB7DQogICAgICAgICAgICAgICArKyRzdGF0dXM7DQogICAgICAgICAgICAgICBlY2hvICc8c3BhbiBjbGFzcz0iZXJyb3IiPkZhaWxlZDwvc3Bhbj4nOw0KICAgICAgICAgIH0NCiAgICAgICAgICBlY2hvICc8L2Rpdj4nOw0KICAgICAgICAgIHJldHVybiAkc3RhdHVzOw0KICAgICB9DQogICAgICRyZWFkID0gIiI7DQogICAgICRyZXF1ZXN0ID0gIkdFVCAkZmlsZSBIVFRQLzEuMVxyXG4iOw0KICAgICAkcmVxdWVzdCAuPSAiSG9zdDogJGhvc3RcclxuIjsNCiAgICAgJHJlcXVlc3QgLj0gIkNvbm5lY3Rpb246IENsb3NlXHJcblxyXG4iOw0KICAgICBmd3JpdGUoJGZwLCAkcmVxdWVzdCk7DQoNCiAgICAgd2hpbGUgKCFmZW9mKCRmcCkpDQogICAgICAgICAgJHJlYWQgLj0gZnJlYWQoJGZwLDEwMjQpOw0KCQ0KICAgICAkb3V0cHV0ID0gZXhwbG9kZSgiaGVsaW9zLy8iLCAkcmVhZCk7DQogICAgIGlmKGlzc2V0KCRvdXRwdXRbMV0pKXsNCiAgICAgICAgICBzd2l0Y2goJF9QT1NUWyd1SUQnXSl7DQogICAgICAgICAgICAgICBjYXNlIDE6DQogICAgICAgICAgICAgICAgICAgIGVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMSkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjMuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzU0YWNiNTdhZmIudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS81NGFjYjU3YWZiLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSAyOg0KICAgICAgICAgICAgICAgICAgICBlY2hvICgkX1BPU1RbJ3VJRCddID09IDIpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS40IHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvOWU2MDVjZTNiYi50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzllNjA1Y2UzYmIudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDM6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAzKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNC4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvMWU2ZGJmYWFhMC50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzFlNmRiZmFhYTAudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDQ6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSA0KSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDEuNSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzYwMDg2NDcyNzcudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS82MDA4NjQ3Mjc3LnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSA1Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gNSkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjUuMSB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzNlZjA0YjFjNGIudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS8zZWYwNGIxYzRiLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSA2Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gNikgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjUuMiB0byAnIC4gJGN1clZlcnNpb24gLiAnPC9kaXY+JyA6ICcnOw0KCQkJCWlmKGZpbGVfZXhpc3RzKEhDU0VUVVAuJy9kYXRhLzBjZWEyNGFlM2IudHh0JykpDQoJCQkJCWV2YWwoYmFzZTY0X2RlY29kZShmaWxlX2dldF9jb250ZW50cyhIQ1NFVFVQLicvZGF0YS8wY2VhMjRhZTNiLnR4dCcpKSk7DQoJCQkJZWxzZQ0KCQkJCQkrKyRub19maWxlOw0KCQkJCQ0KCQkJY2FzZSA3Og0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gNykgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAxLjYgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQkNCgkJCWNhc2UgODoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDgpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS42LjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS9lMzRjZjk3NzRlLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvZTM0Y2Y5Nzc0ZS50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgOToNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDkpID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS43IHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkJaWYoZmlsZV9leGlzdHMoSENTRVRVUC4nL2RhdGEvN2ZkM2NkYWFiYS50eHQnKSkNCgkJCQkJZXZhbChiYXNlNjRfZGVjb2RlKGZpbGVfZ2V0X2NvbnRlbnRzKEhDU0VUVVAuJy9kYXRhLzdmZDNjZGFhYmEudHh0JykpKTsNCgkJCQllbHNlDQoJCQkJCSsrJG5vX2ZpbGU7DQoJCQkJDQoJCQljYXNlIDEwOg0KCQkJCWVjaG8gKCRfUE9TVFsndUlEJ10gPT0gMTApID8gJzxkaXYgY2xhc3M9InV0aXRsZSI+VXBncmFkaW5nIGZyb20gMS43LjEgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS85MWE1NzA3NjgwLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEvOTFhNTcwNzY4MC50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQkNCgkJCWNhc2UgMTE6DQoJCQkJZWNobyAoJF9QT1NUWyd1SUQnXSA9PSAxMSkgPyAnPGRpdiBjbGFzcz0idXRpdGxlIj5VcGdyYWRpbmcgZnJvbSAyLjAgdG8gJyAuICRjdXJWZXJzaW9uIC4gJzwvZGl2PicgOiAnJzsNCgkJCQlpZihmaWxlX2V4aXN0cyhIQ1NFVFVQLicvZGF0YS94NDg3a2ozMDIxLnR4dCcpKQ0KCQkJCQlldmFsKGJhc2U2NF9kZWNvZGUoZmlsZV9nZXRfY29udGVudHMoSENTRVRVUC4nL2RhdGEveDQ4N2tqMzAyMS50eHQnKSkpOw0KCQkJCWVsc2UNCgkJCQkJKyskbm9fZmlsZTsNCgkJCQ0KCQkJY2FzZSAxMjoNCgkJCQllY2hvICgkX1BPU1RbJ3VJRCddID09IDEyKSA/ICc8ZGl2IGNsYXNzPSJ1dGl0bGUiPlVwZ3JhZGluZyBmcm9tIDIuMC4xIHRvICcgLiAkY3VyVmVyc2lvbiAuICc8L2Rpdj4nIDogJyc7DQoJCQkNCgkJCQkNCgkJCS8vCUFsbCBVcGdyYWRlcw0KICAgICAgICAgICAgICAgICAgICBteXNxbF9xdWVyeSgiVVBEQVRFIGAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzYCBTRVQgYFNldHRpbmdWYWx1ZWAgPSAnIiAuICRoY191cGdWZXIgLiAiJyBXSEVSRSBQa0lEID0gJzQ5JyIpOw0KICAgICAgICAgICAgICAgICAgICBlY2hvICcNCgkJCTwvZmllbGRzZXQ+DQoNCgkJCTxmaWVsZHNldCBzdHlsZT0icGFkZGluZzoxMHB4OyI+DQoJCQkJPGxlZ2VuZD5VcGdyYWRlIFJlc3VsdHM8L2xlZ2VuZD4nOw0KDQogICAgICAgICAgICAgICAgICAgIGlmKCRzdGF0dXMgPT0gMCAmJiAkbm9fZmlsZSA9PSAwKXsNCiAgICAgICAgICAgICAgICAgICAgICAgICBlY2hvICcNCgkJCQk8cD4NCgkJCQkJVXBncmFkZSBTdWNjZXNzZnVsLjxiciAvPjxiciAvPkRlbGV0ZSB0aGUgL3NldHVwIGRpcmVjdG9yeSBhbmQgeW91ciBjYWNoZSBmaWxlcyB0byBjb21wbGV0ZSB0aGUgdXBncmFkZS4NCgkJCQk8L3A+JzsNCiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHsNCgkJCQkJZWNobyAoJG5vX2ZpbGUgPiAwKSA/ICc8cD48Yj5PbmUgb3IgbW9yZSByZXF1aXJlZCB1cGdyYWRlIGRhdGEgZmlsZXMgYXJlIG1pc3NpbmcuPC9iPjwvcD4nIDogJyc7DQoJCQkJCWVjaG8gJw0KCQkJCTxwPg0KCQkJCQlPbmUgb3IgbW9yZSBvZiB0aGUgdXBncmFkZXMgZmFpbGVkLiBUaGlzIGlzIG1vc3QgY29tbW9ubHkgY2F1c2VkIGJ5IGltcHJvcGVyIGNvbmZpZ3VyYXRpb24gb2YgeW91ciBNeVNRTCB1c2VyIHByaXZpbGVnZXMuIFlvdSBtYXkgbmVlZCB0byByZXN0b3JlIHlvdXIgZGF0YWJhc2UgZnJvbSBhIGJhY2t1cCBiZWZvcmUgcnVubmluZyB0aGlzIHVwZ3JhZGUgYWdhaW4uDQoJCQkJPC9wPic7DQogICAgICAgICAgICAgICAgICAgIH0NCiAgICAgICAgICAgICAgIGJyZWFrOw0KICAgICAgICAgIH0NCiAgICAgfSBlbHNlIHsNCiAgICAgICAgICBlY2hvICcJPHA+DQoNCgkJCQkJVXBncmFkZSBmYWlsZWQuIDxhIGhyZWY9Imh0dHBzOi8vd3d3LnJlZnJlc2hteS5jb20vbWVtYmVycy8iPkNvbnRhY3QgUmVmcmVzaCBTdXBwb3J0IGZvciBhc3Npc3RhbmNlLjwvYT4NCgkJCQk8L3A+JzsNCiAgICAgfQ0KICAgICBlY2hvICcNCgkJCTwvZmllbGRzZXQ+JzsNCn0='));	
	}
?>