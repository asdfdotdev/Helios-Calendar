<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
	
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying Helios Setup files is not permitted and violates the Helios EUL.	|
	|	Please do not edit this or any of the setup files							|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	if(!isset($_SESSION['license'])){?>
		<a href="<?echo CalRoot;?>/setup/" class="main">Click here to being Helios setup.</a>
<?	} else {
		if(!isset($_POST['regcode'])){	?>
<script language="JavaScript">
function chkFrm(){
	if(document.frm.regcode.value == ''){
		alert('Registration Code is required.\nPlease enter it to continue.');
		return false;
	} else {
		return true;
	}//end if
}//end chkFrm()
</script>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="2" class="eventMain">
					<b>Registration authentication.</b>
					<br><br>
					Your can retrieve your registration code from the <a href="https://www.helioscalendar.com/members/" class="main" target="new">Helios Member's Site</a>.
					<br><br>
					</td>
				</tr>
				<form name="frm" id="frm" method="post" action="<?echo CalRoot;?>/setup/index.php?step=3" onSubmit="return chkFrm();">
				<tr>
					<td class="eventMain" width="175">Enter your registration code:</td>
					<td class="eventMain">
						<input size="40" type="text" name="regcode" id="regcode" value="" class="eventInput">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="20" alt="" border="0"></td></tr>
				<tr>
					<td colspan="2" align="right"><input type="submit" name="submit" id="submit" value="Check Registration" class="eventButton"></td>
				</tr>
				</form>
			</table>
	<?	} else {
			eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSBicm9rZW4gdGhlIEhlbGlvcyBFVUwuKi8/Pjx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCIgd2lkdGg9IjEwMCUiPg0KCQkJCTx0cj4NCgkJCQkJPHRkIGNsYXNzPSJldmVudE1haW4iPg0KCQkJCTw/CWlmKCRfUE9TVFsncmVnY29kZSddID09ICJsb2NhbGhvc3QiICYmICRfU0VSVkVSWydIVFRQX0hPU1QnXSA9PSAibG9jYWxob3N0Iil7DQoJCQkJCQkkX1NFU1NJT05bJ3ZhbGlkJ10gPSB0cnVlOw0KCQkJCQkJJF9TRVNTSU9OWydyZWdjb2RlJ10gPSAibG9jYWxob3N0X29ubHkiOw0KCQkJCQkJJF9TRVNTSU9OWydyZWduYW1lJ10gPSAiRGV2ZWxvcGVyIjsNCgkJCQkJCSRfU0VTU0lPTlsncmVndXJsJ10gPSAibG9jYWxob3N0IjsNCgkJCQkJCSRfU0VTU0lPTlsncmVnZW1haWwnXSA9ICJzdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbSI7DQoJCQkJCT8+CVlvdXIgbG9jYWxob3N0IGluc3RhbGxhdGlvbiBvZiBIZWxpb3MgaGFzIGJlZW4gdmFsaWRhdGVkLjxicj48YnI+DQoJCQkJCQlUaGlzIGluc3RhbGxhdGlvbiB3aWxsIG9ubHkgYmUgdmlzaWJsZSBmcm9tIHRoZSBsb2NhbGhvc3QgYWRkcmVzcyBhbmQgaXMgDQoJCQkJCQlpbnRlbmRlZCBmb3IgZGV2ZWxvcG1lbnQgdXNlIG9ubHkuDQoJCQkJCQk8YnI+PGJyPg0KCQkJCQkJPHRhYmxlIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgYm9yZGVyPSIwIiB3aWR0aD0iMTAwJSI+DQoJCQkJCQkJPHRyPg0KDQoJCQkJCQkJCTx0ZCBhbGlnbj0icmlnaHQiPg0KCQkJCQkJCQkJPGlucHV0IG9uQ2xpY2s9ImRvY3VtZW50LmxvY2F0aW9uLmhyZWY9Jzw/ZWNobyBDYWxSb290Oz8+L3NldHVwL2luZGV4LnBocD9zdGVwPTQnOyIgdHlwZT0iYnV0dG9uIiBuYW1lPSJzdWJtaXQiIGlkPSJzdWJtaXQiIHZhbHVlPSJDb250aW51ZSIgY2xhc3M9ImV2ZW50QnV0dG9uIj4NCgkJCQkJCQkJPC90ZD4NCgkJCQkJCQk8L3RyPg0KCQkJCQkJPC90YWJsZT4NCgkJCQk8Pwl9IGVsc2Ugew0KCQkJCQkJJGhvc3QgPSAidmFsaWRhdGUuaGVsaW9zY2FsZW5kYXIuY29tIjsNCgkJCQkJCSRmaWxlID0gIi92Mi5waHA/Yz0iIC4gJF9QT1NUWydyZWdjb2RlJ10gLiAiJnU9IiAuICRfU0VSVkVSWydIVFRQX0hPU1QnXTsNCgkJCQkJCQ0KCQkJCQkJaWYoISgkZnAgPSBmc29ja29wZW4oJGhvc3QsIDgwLCAkZXJybm8sICRlcnJzdHIsIDEpKSApew0KCQkJCQkJCWZlZWRiYWNrKDMsICJDb25uZWN0aW9uIHRvIHd3dy5IZWxpb3NDYWxlbmRhci5jb20gZmFpbGVkLiBUaGUgZm9sbG93aW5nIGVycm9yIG1lc3NhZ2Ugd2FzIHJldHVybmVkLjxicj48aHI+RXJyb3IgIzoiIC4gJGVycm5vIC4gIiAtLSAiIC4gJGVycnN0cik7DQoJCQkJCQl9IGVsc2Ugew0KCQkJCQkJCSRyZWFkID0gIiI7DQoJCQkJCQkJJHJlcXVlc3QgPSAiR0VUICRmaWxlIEhUVFAvMS4xXHJcbiI7DQoJCQkJCQkJJHJlcXVlc3QgLj0gIkhvc3Q6ICRob3N0XHJcbiI7DQoJCQkJCQkJJHJlcXVlc3QgLj0gIkNvbm5lY3Rpb246IENsb3NlXHJcblxyXG4iOw0KCQkJCQkJCWZ3cml0ZSgkZnAsICRyZXF1ZXN0KTsNCgkJCQkJCQkNCgkJCQkJCQl3aGlsZSAoIWZlb2YoJGZwKSkgew0KCQkJCQkJCQkkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQoJCQkJCQkJfS8vZW5kIHdoaWxlDQoJCQkJCQkJDQoJCQkJCQkJJG91dHB1dCA9IGV4cGxvZGUoImhlbGlvcy8vIiwgJHJlYWQpOw0KCQkJCQkJCWlmKGlzc2V0KCRvdXRwdXRbMV0pKXsNCgkJCQkJCQkJJHJlZ0RhdGEgPSBleHBsb2RlKCIvLyIsIGJhc2U2NF9kZWNvZGUoJG91dHB1dFsxXSkpOz8+DQoJCQkJCQkJCTx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCI+DQoJCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQkJPHRkIHdpZHRoPSIxMjUiIGNsYXNzPSJldmVudE1haW4iPlJlZ2lzdHJhdGlvbiBDb2RlOjwvdGQ+DQoJDQoJCQkJCQkJCQkJPHRkIHN0eWxlPSJjb2xvcjogIzY2NjY2NjsiIGNsYXNzPSJldmVudE1haW4iPjw/ZWNobyAkX1BPU1RbJ3JlZ2NvZGUnXTs/PjwvdGQ+DQoJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCTx0ZCBjb2xzcGFuPSIyIj48aW1nIHNyYz0iPD9lY2hvIENhbFJvb3Q7Pz4vaW1hZ2VzL3NwYWNlci5naWYiIHdpZHRoPSIxIiBoZWlnaHQ9IjciIGFsdD0iIiBib3JkZXI9IjAiPjwvdGQ+DQoJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCTx0ZCBjbGFzcz0iZXZlbnRNYWluIj5SZWdpc3RyYW50OjwvdGQ+DQoJCQkJCQkJCQkJPHRkIHN0eWxlPSJjb2xvcjogIzY2NjY2NjsiIGNsYXNzPSJldmVudE1haW4iPjw/ZWNobyAkcmVnRGF0YVswXTs/PjwvdGQ+DQoJDQoJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCTx0ZCBjb2xzcGFuPSIyIj48aW1nIHNyYz0iPD9lY2hvIENhbFJvb3Q7Pz4vaW1hZ2VzL3NwYWNlci5naWYiIHdpZHRoPSIxIiBoZWlnaHQ9IjciIGFsdD0iIiBib3JkZXI9IjAiPjwvdGQ+DQoJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCTx0ZCBjbGFzcz0iZXZlbnRNYWluIj5SZWdpc3RlcmVkIFVSTDo8L3RkPg0KCQkJCQkJCQkJCTx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJlZ0RhdGFbMV07Pz48L3RkPg0KCQkJCQkJCQkJPC90cj4NCgkNCgkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQk8dGQgY29sc3Bhbj0iMiI+PGltZyBzcmM9Ijw/ZWNobyBDYWxSb290Oz8+L2ltYWdlcy9zcGFjZXIuZ2lmIiB3aWR0aD0iMSIgaGVpZ2h0PSI3IiBhbHQ9IiIgYm9yZGVyPSIwIj48L3RkPg0KCQkJCQkJCQkJPC90cj4NCgkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQk8dGQgY2xhc3M9ImV2ZW50TWFpbiI+UmVnaXN0ZXJlZCBFbWFpbDo8L3RkPg0KCQkJCQkJCQkJCTx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJlZ0RhdGFbMl07Pz48L3RkPg0KCQkJCQkJCQkJPC90cj4NCgkJCQkJCQkJPC90YWJsZT4NCgkNCgkJCQkJCQkJPGJyPg0KCQkJCQkJCTw/CSRfU0VTU0lPTlsndmFsaWQnXSA9IHRydWU7DQoJCQkJCQkJCSRfU0VTU0lPTlsncmVnY29kZSddID0gJF9QT1NUWydyZWdjb2RlJ107DQoJCQkJCQkJCSRfU0VTU0lPTlsncmVnbmFtZSddID0gJHJlZ0RhdGFbMF07DQoJCQkJCQkJCSRfU0VTU0lPTlsncmVndXJsJ10gPSAkcmVnRGF0YVsxXTsNCgkJCQkJCQkJJF9TRVNTSU9OWydyZWdlbWFpbCddID0gJHJlZ0RhdGFbMl07DQoJCQkJCQkJPz4NCgkJCQkJCQkJPHRhYmxlIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgYm9yZGVyPSIwIiB3aWR0aD0iMTAwJSI+DQoJCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQkJPHRkIGFsaWduPSJyaWdodCI+DQoJCQkJCQkJCQkJCTxpbnB1dCBvbkNsaWNrPSJkb2N1bWVudC5sb2NhdGlvbi5ocmVmPSc8P2VjaG8gQ2FsUm9vdDs/Pi9zZXR1cC9pbmRleC5waHA/c3RlcD00JzsiIHR5cGU9ImJ1dHRvbiIgbmFtZT0ic3VibWl0IiBpZD0ic3VibWl0IiB2YWx1ZT0iQ29udGludWUiIGNsYXNzPSJldmVudEJ1dHRvbiI+DQoJCQkJCQkJCQkJPC90ZD4NCgkJCQkJCQkJCTwvdHI+DQoJCQkJCQkJCTwvdGFibGU+PD8NCgkJCQkJCQl9IGVsc2Ugewk/Pg0KCQkJCQkJCQlZb3UgcmVnaXN0cmF0aW9uIGNvZGUgY2Fubm90IGJlIHZhbGlkYXRlZC48YnI+PGJyPg0KCQkJCQkJCQlJZiB5b3UgYmVsaWV2ZSB5b3UgaGF2ZSByZWNlaXZlZCB0aGlzIG1lc3NhZ2UgaW4gZXJyb3IsPGJyPm9yIG5lZWQgYXNzaXN0YW5jZSwgcGxlYXNlIGNvbnRhY3QgPGEgaHJlZj0ibWFpbHRvOnN1cHBvcnRAaGVsaW9zY2FsZW5kYXIuY29tIj5zdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbTwvYT4NCgkJCQkJCTw/CX0vL2VuZCBpZg0KCQkJCQkJCWZjbG9zZSgkZnApOw0KCQkJCQkJfS8vZW5kIGlmPz4NCgkJCQkJPC90ZD4NCgkJCQk8L3RyPg0KCQkJPC90YWJsZT48Pw0KCQkJfS8vZW5kIGlm'));
		}//end if
	}//end if?>