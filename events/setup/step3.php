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
					eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSB2aW9sYXRlZCB0aGUgSGVsaW9zIEVuZCBVc2VyIExpY2Vuc2UuKi8NCgkJCWlmKCRfUE9TVFsn	cmVnY29kZSddID09ICJsb2NhbGhvc3QiICYmICRfU0VSVkVSWydIVFRQX0hPU1QnXSA9PSAibG9jYWxob3N0Iil7DQoJCQkJJF9TRVNTSU9OWyd2YWxpZCddID0gdHJ1ZTsNCgkJ	CQkkX1NFU1NJT05bJ3JlZ2NvZGUnXSA9ICJsb2NhbGhvc3Rfb25seSI7DQoJCQkJJF9TRVNTSU9OWydyZWduYW1lJ10gPSAiRGV2ZWxvcGVyIjsNCgkJCQkkX1NFU1NJT05bJ3Jl	Z3VybCddID0gImxvY2FsaG9zdCI7DQoJCQkJJF9TRVNTSU9OWydyZWdlbWFpbCddID0gInN1cHBvcnRAaGVsaW9zY2FsZW5kYXIuY29tIjsJPz4NCgkJCQlZb3VyIGxvY2FsaG9z	dCBpbnN0YWxsYXRpb24gb2YgSGVsaW9zIGhhcyBiZWVuIHZhbGlkYXRlZC48YnIgLz48YnIgLz4NCgkJCQlUaGlzIGluc3RhbGxhdGlvbiB3aWxsIG9ubHkgYmUgdmlzaWJsZSBm	cm9tIHRoZSBsb2NhbGhvc3QgYWRkcmVzcyBhbmQgaXMgDQoJCQkJaW50ZW5kZWQgZm9yIGRldmVsb3BtZW50IHVzZSBvbmx5Lg0KCQkJCTxiciAvPjxiciAvPg0KCQkJCTxkaXYg	YWxpZ249InJpZ2h0Ij48aW5wdXQgb25DbGljaz0iZG9jdW1lbnQubG9jYXRpb24uaHJlZj0nPD9lY2hvIENhbFJvb3Q7Pz4vc2V0dXAvaW5kZXgucGhwP3N0ZXA9NCc7IiB0eXBl	PSJidXR0b24iIG5hbWU9InN1Ym1pdCIgaWQ9InN1Ym1pdCIgdmFsdWU9IkNvbnRpbnVlIiBjbGFzcz0iZXZlbnRCdXR0b24iIC8+PC9kaXY+DQoJCTw/CX0gZWxzZSB7DQoJCQkJ	JGhvc3QgPSAidmFsaWRhdGUuaGVsaW9zY2FsZW5kYXIuY29tIjsNCgkJCQkkZmlsZSA9ICIvdjIucGhwP2M9IiAuICRfUE9TVFsncmVnY29kZSddIC4gIiZ1PSIgLiAkX1NFUlZF	UlsnSFRUUF9IT1NUJ107DQoJCQkJDQoJCQkJaWYoISgkZnAgPSBmc29ja29wZW4oJGhvc3QsIDgwLCAkZXJybm8sICRlcnJzdHIsIDEpKSApew0KCQkJCQlmZWVkYmFjaygzLCAi	Q29ubmVjdGlvbiB0byB3d3cuSGVsaW9zQ2FsZW5kYXIuY29tIGZhaWxlZC4gVGhlIGZvbGxvd2luZyBlcnJvciBtZXNzYWdlIHdhcyByZXR1cm5lZC48YnIgLz48aHI+RXJyb3Ig	IzoiIC4gJGVycm5vIC4gIiAtLSAiIC4gJGVycnN0cik7DQoJCQkJfSBlbHNlIHsNCgkJCQkJJHJlYWQgPSAiIjsNCgkJCQkJJHJlcXVlc3QgPSAiR0VUICRmaWxlIEhUVFAvMS4x	XHJcbiI7DQoJCQkJCSRyZXF1ZXN0IC49ICJIb3N0OiAkaG9zdFxyXG4iOw0KCQkJCQkkcmVxdWVzdCAuPSAiQ29ubmVjdGlvbjogQ2xvc2VcclxuXHJcbiI7DQoJCQkJCWZ3cml0	ZSgkZnAsICRyZXF1ZXN0KTsNCgkJCQkJDQoJCQkJCXdoaWxlICghZmVvZigkZnApKSB7DQoJCQkJCQkkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQoJCQkJCX0vL2VuZCB3aGls	ZQ0KCQkJCQkNCgkJCQkJJG91dHB1dCA9IGV4cGxvZGUoImhlbGlvcy8vIiwgJHJlYWQpOw0KCQkJCQlpZihpc3NldCgkb3V0cHV0WzFdKSl7DQoJCQkJCQkkcmVnRGF0YSA9IGV4	cGxvZGUoIi8vIiwgYmFzZTY0X2RlY29kZSgkb3V0cHV0WzFdKSk7DQoJCQkJCQkkX1NFU1NJT05bJ3ZhbGlkJ10gPSB0cnVlOw0KCQkJCQkJJF9TRVNTSU9OWydyZWdjb2RlJ10g	PSAkX1BPU1RbJ3JlZ2NvZGUnXTsNCgkJCQkJCSRfU0VTU0lPTlsncmVnbmFtZSddID0gJHJlZ0RhdGFbMF07DQoJCQkJCQkkX1NFU1NJT05bJ3JlZ3VybCddID0gJHJlZ0RhdGFb	MV07DQoJCQkJCQkkX1NFU1NJT05bJ3JlZ2VtYWlsJ10gPSAkcmVnRGF0YVsyXTsJPz4NCgkJCQkJCTxiciAvPg0KCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6bGVmdDt3aWR0aDox	MjBweDsiPlJlZ2lzdHJhdGlvbjo8L2Rpdj4NCgkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lkdGg6MjAwcHg7Y29sb3I6IzY2NjY2NjsiPjw/ZWNobyAkX1BPU1RbJ3Jl	Z2NvZGUnXTs/PjwvZGl2Pg0KCQkJCQkJPGJyIC8+PGJyIC8+DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0O3dpZHRoOjEyMHB4OyI+UmVnaXN0cmFudDo8L2Rpdj4NCgkJ	CQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lkdGg6MjAwcHg7Y29sb3I6IzY2NjY2NjsiPjw/ZWNobyAkcmVnRGF0YVswXTs/PjwvZGl2Pg0KCQkJCQkJPGJyIC8+PGJyIC8+	DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0O3dpZHRoOjEyMHB4OyI+UmVnaXN0cmFudCBFbWFpbDo8L2Rpdj4NCgkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OmxlZnQ7d2lk	dGg6MjAwcHg7Y29sb3I6IzY2NjY2NjsiPjw/ZWNobyAkcmVnRGF0YVsxXTs/PjwvZGl2Pg0KCQkJCQkJPGJyIC8+PGJyIC8+DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0	O3dpZHRoOjEyMHB4OyI+UmVnaXN0ZXJlZCBVUkw6PC9kaXY+DQoJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDpsZWZ0O3dpZHRoOjIwMHB4O2NvbG9yOiM2NjY2NjY7Ij48P2VjaG8g	JHJlZ0RhdGFbMl07Pz48L2Rpdj4NCgkJCQkJCTxiciAvPjxiciAvPg0KCQkJCQkJPGRpdiBhbGlnbj0icmlnaHQiPjxpbnB1dCBvbkNsaWNrPSJkb2N1bWVudC5sb2NhdGlvbi5o	cmVmPSc8P2VjaG8gQ2FsUm9vdDs/Pi9zZXR1cC9pbmRleC5waHA/c3RlcD00JzsiIHR5cGU9ImJ1dHRvbiIgbmFtZT0ic3VibWl0IiBpZD0ic3VibWl0IiB2YWx1ZT0iQ29udGlu	dWUiIGNsYXNzPSJldmVudEJ1dHRvbiIgLz48L2Rpdj4NCgkJCQk8Pwl9IGVsc2Ugewk/Pg0KCQkJCQkJPGJyIC8+DQoJCQkJCQlZb3UgcmVnaXN0cmF0aW9uIGNvZGUgY2Fubm90	IGJlIHZhbGlkYXRlZC48YnIgLz48YnIgLz4NCgkJCQkJCUlmIHlvdSBiZWxpZXZlIHlvdSBoYXZlIHJlY2VpdmVkIHRoaXMgbWVzc2FnZSBpbiBlcnJvciw8YnIgLz5vciBuZWVk	IGFzc2lzdGFuY2UsIHBsZWFzZSBjb250YWN0IDxhIGhyZWY9Im1haWx0bzpzdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbSI+c3VwcG9ydEBoZWxpb3NjYWxlbmRhci5jb208L2E+	DQoJCQkJPD8JfS8vZW5kIGlmDQoJCQkJCWZjbG9zZSgkZnApOw0KCQkJCX0vL2VuZCBpZg0KCQkJfS8vZW5kIGlm'));
			}//end if
		}//end if	?>