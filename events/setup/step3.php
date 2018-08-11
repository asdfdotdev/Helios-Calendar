<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
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
			<br /><a href="<?php echo CalRoot;?>/setup/" class="eventMain">Click here to begin Helios setup.</a>
<?php	} else {
			if(!isset($_POST['regcode'])){	?>
				<script language="JavaScript">
				//<!--
				function chkFrm(){
					if(document.frm.regcode.value == ''){
						alert('Registration Code is required.\nPlease enter it to continue.');
						return false;
					} else {
						return true;
					}//end if
				}//end chkFrm()
				//-->
				</script>
				<br />
				Your can retrieve your registration code from the <a href="https://www.helioscalendar.com/members/" class="eventMain" target="new">Helios Member's Site</a>.
				<br /><br />
				
				<form name="frm" id="frm" method="post" action="<?php echo CalRoot;?>/setup/index.php?step=3" onsubmit="return chkFrm();">
				Enter your registration code:
				<input size="40" type="text" name="regcode" id="regcode" value="" class="regCode" />
				<br /><br />
				<div align="right"><input type="submit" name="submit" id="submit" value="Check Registration" class="eventButton" /></div>
				</form>
	<?php	} else {
					eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIEVuZCBVc2VyIExpY2Vuc2UuKi8NCgkJCWlmKCRfUE9TVFsncmVnY29kZSddID09ICJsb2NhbGhvc3QiICYmICRfU0VSVkVSWydIVFRQX0hPU1QnXSA9PSAibG9jYWxob3N0Iil7DQoJCQkJJF9TRVNTSU9OWyd2YWxpZCddID0gdHJ1ZTsNCgkJCQkkX1NFU1NJT05bJ3JlZ2NvZGUnXSA9ICJsb2NhbGhvc3Rfb25seSI7DQoJCQkJJF9TRVNTSU9OWydyZWduYW1lJ10gPSAiRGV2ZWxvcGVyIjsNCgkJCQkkX1NFU1NJT05bJ3JlZ3VybCddID0gImxvY2FsaG9zdCI7DQoJCQkJJF9TRVNTSU9OWydyZWdlbWFpbCddID0gInN1cHBvcnRAaGVsaW9zY2FsZW5kYXIuY29tIjsJPz4NCgkJCQlZb3VyIGxvY2FsaG9zdCBpbnN0YWxsYXRpb24gb2YgSGVsaW9zIGhhcyBiZWVuIHZhbGlkYXRlZC48YnIgLz48YnIgLz4NCgkJCQlUaGlzIGluc3RhbGxhdGlvbiB3aWxsIG9ubHkgYmUgdmlzaWJsZSBmcm9tIHRoZSBsb2NhbGhvc3QgYWRkcmVzcyBhbmQgaXMgDQoJCQkJaW50ZW5kZWQgZm9yIGRldmVsb3BtZW50IHVzZSBvbmx5Lg0KCQkJCTxiciAvPjxiciAvPg0KCQkJCTxkaXYgYWxpZ249InJpZ2h0Ij48aW5wdXQgb25DbGljaz0iZG9jdW1lbnQubG9jYXRpb24uaHJlZj0nPD9waHAgZWNobyBDYWxSb290Oz8+L3NldHVwL2luZGV4LnBocD9zdGVwPTQnOyIgdHlwZT0iYnV0dG9uIiBuYW1lPSJzdWJtaXQiIGlkPSJzdWJtaXQiIHZhbHVlPSJDb250aW51ZSIgY2xhc3M9ImV2ZW50QnV0dG9uIiAvPjwvZGl2Pg0KCTw/cGhwCX0gZWxzZSB7DQoJCQkJJGhvc3QgPSAidmFsaWRhdGUuaGVsaW9zY2FsZW5kYXIuY29tIjsNCgkJCQkkZmlsZSA9ICIvdjIucGhwP2M9IiAuICRfUE9TVFsncmVnY29kZSddIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQoJCQkJDQoJCQkJaWYoISgkZnAgPSBmc29ja29wZW4oJGhvc3QsIDgwLCAkZXJybm8sICRlcnJzdHIsIDEpKSApew0KCQkJCQlmZWVkYmFjaygzLCAiQ29ubmVjdGlvbiB0byB3d3cuSGVsaW9zQ2FsZW5kYXIuY29tIGZhaWxlZC4gVGhlIGZvbGxvd2luZyBlcnJvciBtZXNzYWdlIHdhcyByZXR1cm5lZC48YnIgLz48aHI+RXJyb3IgIzoiIC4gJGVycm5vIC4gIiAtLSAiIC4gJGVycnN0cik7DQoJCQkJfSBlbHNlIHsNCgkJCQkJJHJlYWQgPSAiIjsNCgkJCQkJJHJlcXVlc3QgPSAiR0VUICRmaWxlIEhUVFAvMS4xXHJcbiI7DQoJCQkJCSRyZXF1ZXN0IC49ICJIb3N0OiAkaG9zdFxyXG4iOw0KCQkJCQkkcmVxdWVzdCAuPSAiQ29ubmVjdGlvbjogQ2xvc2VcclxuXHJcbiI7DQoJCQkJCWZ3cml0ZSgkZnAsICRyZXF1ZXN0KTsNCgkJCQkJDQoJCQkJCXdoaWxlICghZmVvZigkZnApKSB7DQoJCQkJCQkkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQoJCQkJCX0vL2VuZCB3aGlsZQ0KCQkJCQkNCgkJCQkJJG91dHB1dCA9IGV4cGxvZGUoImhlbGlvcy8vIiwgJHJlYWQpOw0KCQkJCQlpZihpc3NldCgkb3V0cHV0WzFdKSl7DQoJCQkJCQkkcmVnRGF0YSA9IGV4cGxvZGUoIi8vIiwgYmFzZTY0X2RlY29kZSgkb3V0cHV0WzFdKSk7DQoJCQkJCQkkX1NFU1NJT05bJ3ZhbGlkJ10gPSB0cnVlOw0KCQkJCQkJJF9TRVNTSU9OWydyZWdjb2RlJ10gPSAkX1BPU1RbJ3JlZ2NvZGUnXTsNCgkJCQkJCSRfU0VTU0lPTlsncmVnbmFtZSddID0gJHJlZ0RhdGFbMF07DQoJCQkJCQkkX1NFU1NJT05bJ3JlZ3VybCddID0gJHJlZ0RhdGFbMV07DQoJCQkJCQkkX1NFU1NJT05bJ3JlZ2VtYWlsJ10gPSAkcmVnRGF0YVsyXTsJPz4NCgkJCQkJCTxiciAvPg0KCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6bGVmdDt3aWR0aDoxMjBweDsiPlJlZ2lzdHJhdGlvbjo8L2Rpdj4NCgkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lkdGg6MjAwcHg7Y29sb3I6IzY2NjY2NjsiPjw/cGhwIGVjaG8gJF9QT1NUWydyZWdjb2RlJ107Pz48L2Rpdj4NCg0KCQkJCQkJPGJyIC8+PGJyIC8+DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0O3dpZHRoOjEyMHB4OyI+UmVnaXN0cmFudDo8L2Rpdj4NCgkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lkdGg6MjAwcHg7Y29sb3I6IzY2NjY2NjsiPjw/cGhwIGVjaG8gJHJlZ0RhdGFbMF07Pz48L2Rpdj4NCgkJCQkJCTxiciAvPjxiciAvPg0KCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6bGVmdDt3aWR0aDoxMjBweDsiPlJlZ2lzdHJhbnQgRW1haWw6PC9kaXY+DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0O3dpZHRoOjIwMHB4O2NvbG9yOiM2NjY2NjY7Ij48P3BocCBlY2hvICRyZWdEYXRhWzFdOz8+PC9kaXY+DQoJCQkJCQk8YnIgLz48YnIgLz4NCgkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lkdGg6MTIwcHg7Ij5SZWdpc3RlcmVkIFVSTDo8L2Rpdj4NCg0KCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6bGVmdDt3aWR0aDoyMDBweDtjb2xvcjojNjY2NjY2OyI+PD9waHAgZWNobyAkcmVnRGF0YVsyXTs/PjwvZGl2Pg0KCQkJCQkJPGJyIC8+PGJyIC8+DQoJCQkJCQk8ZGl2IGFsaWduPSJyaWdodCI+PGlucHV0IG9uQ2xpY2s9ImRvY3VtZW50LmxvY2F0aW9uLmhyZWY9Jzw/cGhwIGVjaG8gQ2FsUm9vdDs/Pi9zZXR1cC9pbmRleC5waHA/c3RlcD00JzsiIHR5cGU9ImJ1dHRvbiIgbmFtZT0ic3VibWl0IiBpZD0ic3VibWl0IiB2YWx1ZT0iQ29udGludWUiIGNsYXNzPSJldmVudEJ1dHRvbiIgLz48L2Rpdj4NCgkJCTw/cGhwCX0gZWxzZSB7CT8+DQoJCQkJCQk8YnIgLz4NCgkJCQkJCVlvdSByZWdpc3RyYXRpb24gY29kZSBjYW5ub3QgYmUgdmFsaWRhdGVkLjxiciAvPjxiciAvPg0KCQkJCQkJSWYgeW91IGJlbGlldmUgeW91IGhhdmUgcmVjZWl2ZWQgdGhpcyBtZXNzYWdlIGluIGVycm9yLDxiciAvPm9yIG5lZWQgYXNzaXN0YW5jZSwgcGxlYXNlIGNvbnRhY3QgPGEgaHJlZj0ibWFpbHRvOnN1cHBvcnRAaGVsaW9zY2FsZW5kYXIuY29tIj5zdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbTwvYT4NCg0KCQkJPD9waHAJfS8vZW5kIGlmDQoJCQkJCWZjbG9zZSgkZnApOw0KCQkJCX0vL2VuZCBpZg0KCQkJfS8vZW5kIGlm'));
			}//end if
		}//end if	?>