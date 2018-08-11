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
			eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSBicm9rZW4gdGhlIEhlbGlvcyBFVUwuKi8/Pjx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCIgd2lkdGg9IjEwMCUiPg0KCQkJCTx0cj4NCgkJCQkJPHRkIGNsYXNzPSJldmVudE1haW4iPg0KCQkJCTw/CWlmKCRfUE9TVFsncmVnY29kZSddID09ICJsb2NhbGhvc3QiICYmICRfU0VSVkVSWydIVFRQX0hPU1QnXSA9PSAibG9jYWxob3N0Iil7DQoJCQkJCQkkX1NFU1NJT05bJ3ZhbGlkJ10gPSB0cnVlOw0KCQkJCQkJJF9TRVNTSU9OWydyZWdjb2RlJ10gPSAibG9jYWxob3N0X29ubHkiOw0KCQkJCQkJJF9TRVNTSU9OWydyZWduYW1lJ10gPSAiRGV2ZWxvcGVyIjsNCgkJCQkJCSRfU0VTU0lPTlsncmVndXJsJ10gPSAibG9jYWxob3N0IjsNCgkJCQkJCSRfU0VTU0lPTlsncmVnZW1haWwnXSA9ICJzdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbSI7DQoJCQkJCT8+CVlvdXIgbG9jYWxob3N0IGluc3RhbGxhdGlvbiBvZiBIZWxpb3MgaGFzIGJlZW4gdmFsaWRhdGVkLjxicj48YnI+DQoJCQkJCQlUaGlzIGluc3RhbGxhdGlvbiB3aWxsIG9ubHkgYmUgdmlzaWJsZSBmcm9tIHRoZSBsb2NhbGhvc3QgYWRkcmVzcyBhbmQgaXMgDQoJCQkJCQlpbnRlbmRlZCBmb3IgZGV2ZWxvcG1lbnQgdXNlIG9ubHkuDQoJCQkJCQk8YnI+PGJyPg0KCQkJCQkJPHRhYmxlIGNlbGxwYWRkaW5nPSIwIiBjZWxsc3BhY2luZz0iMCIgYm9yZGVyPSIwIiB3aWR0aD0iMTAwJSI+DQoJCQkJCQkJPHRyPg0KCQkJCQkJCQk8dGQgYWxpZ249InJpZ2h0Ij4NCgkJCQkJCQkJCTxpbnB1dCBvbkNsaWNrPSJkb2N1bWVudC5sb2NhdGlvbi5ocmVmPSc8P2VjaG8gQ2FsUm9vdDs/Pi9zZXR1cC9pbmRleC5waHA/c3RlcD00JzsiIHR5cGU9ImJ1dHRvbiIgbmFtZT0ic3VibWl0IiBpZD0ic3VibWl0IiB2YWx1ZT0iQ29udGludWUiIGNsYXNzPSJldmVudEJ1dHRvbiI+DQoJCQkJCQkJCTwvdGQ+DQoJCQkJCQkJPC90cj4NCgkJCQkJCTwvdGFibGU+DQoJCQkJPD8JfSBlbHNlIHsNCgkJCQkJCSRmaWxlID0gImh0dHA6Ly92YWxpZGF0ZS5oZWxpb3NjYWxlbmRhci5jb20vP2M9IiAuICRfUE9TVFsncmVnY29kZSddIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQoJCQkJCQlpbmNsdWRlICcuLi9pbmNsdWRlcy9yc3MvUlNTLnBocCc7DQoJCQkJCQkNCgkJCQkJCWlmIChpc3NldCgkcnNzX2NoYW5uZWxbIklURU1TIl0pKXsNCgkJCQkJCQlpZiAoY291bnQoJHJzc19jaGFubmVsWyJJVEVNUyJdKSA+IDApew0KCQkJCQkJCQlpZigkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlZBTElEIl0gPT0gMSl7Pz4NCgkJCQkJCQkJCTx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCI+DQoJCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCQk8dGQgd2lkdGg9IjEyNSIgY2xhc3M9ImV2ZW50TWFpbiI+UmVnaXN0cmF0aW9uIENvZGU6PC90ZD4NCgkJCQkJCQkJCQkJPHRkIHN0eWxlPSJjb2xvcjogIzY2NjY2NjsiIGNsYXNzPSJldmVudE1haW4iPjw/ZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlJFR0NPREUiXTs/PjwvdGQ+DQoJCQkJCQkJCQkJPC90cj4NCgkJCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQkJCTx0ZCBjb2xzcGFuPSIyIj48aW1nIHNyYz0iPD9lY2hvIENhbFJvb3Q7Pz4vaW1hZ2VzL3NwYWNlci5naWYiIHdpZHRoPSIxIiBoZWlnaHQ9IjciIGFsdD0iIiBib3JkZXI9IjAiPjwvdGQ+DQoJCQkJCQkJCQkJPC90cj4NCgkJCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQkJCTx0ZCBjbGFzcz0iZXZlbnRNYWluIj5SZWdpc3RyYW50OjwvdGQ+DQoJCQkJCQkJCQkJCTx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJOQU1FIl07Pz48L3RkPg0KCQkJCQkJCQkJCTwvdHI+DQoJCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCQk8dGQgY29sc3Bhbj0iMiI+PGltZyBzcmM9Ijw/ZWNobyBDYWxSb290Oz8+L2ltYWdlcy9zcGFjZXIuZ2lmIiB3aWR0aD0iMSIgaGVpZ2h0PSI3IiBhbHQ9IiIgYm9yZGVyPSIwIj48L3RkPg0KCQkJCQkJCQkJCTwvdHI+DQoJCQkJCQkJCQkJPHRyPg0KCQkJCQkJCQkJCQk8dGQgY2xhc3M9ImV2ZW50TWFpbiI+UmVnaXN0ZXJlZCBVUkw6PC90ZD4NCgkJCQkJCQkJCQkJPHRkIHN0eWxlPSJjb2xvcjogIzY2NjY2NjsiIGNsYXNzPSJldmVudE1haW4iPjw/ZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlVSTCJdOz8+PC90ZD4NCgkJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQkJPHRkIGNvbHNwYW49IjIiPjxpbWcgc3JjPSI8P2VjaG8gQ2FsUm9vdDs/Pi9pbWFnZXMvc3BhY2VyLmdpZiIgd2lkdGg9IjEiIGhlaWdodD0iNyIgYWx0PSIiIGJvcmRlcj0iMCI+PC90ZD4NCgkJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJCTx0cj4NCgkJCQkJCQkJCQkJPHRkIGNsYXNzPSJldmVudE1haW4iPlJlZ2lzdGVyZWQgRW1haWw6PC90ZD4NCgkJCQkJCQkJCQkJPHRkIHN0eWxlPSJjb2xvcjogIzY2NjY2NjsiIGNsYXNzPSJldmVudE1haW4iPjw/ZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIkVNQUlMIl07Pz48L3RkPg0KCQkJCQkJCQkJCTwvdHI+DQoJCQkJCQkJCQk8L3RhYmxlPg0KCQkJCQkJCQkJPGJyPg0KCQkJCQkJCQk8PwkkX1NFU1NJT05bJ3ZhbGlkJ10gPSB0cnVlOw0KCQkJCQkJCQkJJF9TRVNTSU9OWydyZWdjb2RlJ10gPSAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlJFR0NPREUiXTsNCgkJCQkJCQkJCSRfU0VTU0lPTlsncmVnbmFtZSddID0gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJOQU1FIl07DQoJCQkJCQkJCQkkX1NFU1NJT05bJ3JlZ3VybCddID0gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJVUkwiXTsNCgkJCQkJCQkJCSRfU0VTU0lPTlsncmVnZW1haWwnXSA9ICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiRU1BSUwiXTsNCgkJCQkJCQkJCWVjaG8gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJNRVNTQUdFIl07DQoJCQkJCQkJCT8+DQoJCQkJCQkJCQk8dGFibGUgY2VsbHBhZGRpbmc9IjAiIGNlbGxzcGFjaW5nPSIwIiBib3JkZXI9IjAiIHdpZHRoPSIxMDAlIj4NCgkJCQkJCQkJCQk8dHI+DQoJCQkJCQkJCQkJCTx0ZCBhbGlnbj0icmlnaHQiPg0KCQkJCQkJCQkJCQkJPGlucHV0IG9uQ2xpY2s9ImRvY3VtZW50LmxvY2F0aW9uLmhyZWY9Jzw/ZWNobyBDYWxSb290Oz8+L3NldHVwL2luZGV4LnBocD9zdGVwPTQnOyIgdHlwZT0iYnV0dG9uIiBuYW1lPSJzdWJtaXQiIGlkPSJzdWJtaXQiIHZhbHVlPSJDb250aW51ZSIgY2xhc3M9ImV2ZW50QnV0dG9uIj4NCgkJCQkJCQkJCQkJPC90ZD4NCgkJCQkJCQkJCQk8L3RyPg0KCQkJCQkJCQkJPC90YWJsZT4NCgkJCQkJCTw/CQl9IGVsc2Ugew0KCQkJCQkJCQkJZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIk1FU1NBR0UiXTsNCgkJCQkJCQkJfS8vZW5kIGlmDQoJCQkJCQkJfSBlbHNlIHsNCgkJCQkJCQkJZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIk1FU1NBR0UiXTsNCgkJCQkJCQl9Ly9lbmQgaWYNCgkJCQkJCX0gZWxzZSB7DQoJCQkJCQkJZWNobyAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIk1FU1NBR0UiXTsNCgkJCQkJCX0vL2VuZCBpZg0KCQkJCQl9Ly9lbmQgaWY/Pg0KCQkJCQk8L3RkPg0KCQkJCTwvdHI+DQoJCQk8L3RhYmxlPg=='));
		}//end if
	}//end if?>