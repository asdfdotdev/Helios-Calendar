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
			<b>Registration authentication.</b>
			<br><br>
			Your can retrieve your registration code from the <a href="http://www.helioscalendar.com/members/" class="main" target="new">Helios Member's Site</a>.
			<br><br>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
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
			$stop = true;?>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="eventMain">
					<?eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB5b3UgaGF2ZSBicm9rZW4gdGhlIEhlbGlvcyBFVUwuKi8kZmlsZSA9ICJodHRwOi8vdmFsaWRhdGUuaGVsaW9zY2FsZW5kYXIuY29tLz9jPSIgLiAkX1BPU1RbJ3JlZ2NvZGUnXSAuICImdT0iIC4gJF9TRVJWRVJbJ0hUVFBfSE9TVCddO2luY2x1ZGUgJy4uL2luY2x1ZGVzL3Jzcy9SU1MucGhwJztpZiAoaXNzZXQoJHJzc19jaGFubmVsWyJJVEVNUyJdKSkge2lmIChjb3VudCgkcnNzX2NoYW5uZWxbIklURU1TIl0pID4gMCkge2lmKCRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiVkFMSUQiXSA9PSAxKXs/Pjx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCI+PHRyPjx0ZCB3aWR0aD0iMTI1IiBjbGFzcz0iZXZlbnRNYWluIj5SZWdpc3RyYXRpb24gQ29kZTo8L3RkPjx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJSRUdDT0RFIl07Pz48L3RkPjwvdHI+PHRyPjx0ZCBjb2xzcGFuPSIyIj48aW1nIHNyYz0iPD9lY2hvIENhbFJvb3Q7Pz4vaW1hZ2VzL3NwYWNlci5naWYiIHdpZHRoPSIxIiBoZWlnaHQ9IjciIGFsdD0iIiBib3JkZXI9IjAiPjwvdGQ+PC90cj48dHI+PHRkIGNsYXNzPSJldmVudE1haW4iPlJlZ2lzdHJhbnQ6PC90ZD48dGQgc3R5bGU9ImNvbG9yOiAjNjY2NjY2OyIgY2xhc3M9ImV2ZW50TWFpbiI+PD9lY2hvICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiTkFNRSJdOz8+PC90ZD48L3RyPjx0cj48dGQgY29sc3Bhbj0iMiI+PGltZyBzcmM9Ijw/ZWNobyBDYWxSb290Oz8+L2ltYWdlcy9zcGFjZXIuZ2lmIiB3aWR0aD0iMSIgaGVpZ2h0PSI3IiBhbHQ9IiIgYm9yZGVyPSIwIj48L3RkPjwvdHI+PHRyPjx0ZCBjbGFzcz0iZXZlbnRNYWluIj5SZWdpc3RlcmVkIFVSTDo8L3RkPjx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJVUkwiXTs/PjwvdGQ+PC90cj48dHI+PHRkIGNvbHNwYW49IjIiPjxpbWcgc3JjPSI8P2VjaG8gQ2FsUm9vdDs/Pi9pbWFnZXMvc3BhY2VyLmdpZiIgd2lkdGg9IjEiIGhlaWdodD0iNyIgYWx0PSIiIGJvcmRlcj0iMCI+PC90ZD48L3RyPjx0cj48dGQgY2xhc3M9ImV2ZW50TWFpbiI+UmVnaXN0ZXJlZCBFbWFpbDo8L3RkPjx0ZCBzdHlsZT0iY29sb3I6ICM2NjY2NjY7IiBjbGFzcz0iZXZlbnRNYWluIj48P2VjaG8gJHJzc19jaGFubmVsWyJJVEVNUyJdWzBdWyJFTUFJTCJdOz8+PC90ZD48L3RyPjwvdGFibGU+PGJyPjw/CSRfU0VTU0lPTlsndmFsaWQnXSA9IHRydWU7JF9TRVNTSU9OWydyZWdjb2RlJ10gPSAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlJFR0NPREUiXTskX1NFU1NJT05bJ3JlZ25hbWUnXSA9ICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiTkFNRSJdOyRfU0VTU0lPTlsncmVndXJsJ10gPSAkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlVSTCJdOyRfU0VTU0lPTlsncmVnZW1haWwnXSA9ICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiRU1BSUwiXTtlY2hvICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiTUVTU0FHRSJdOwk/Pjx0YWJsZSBjZWxscGFkZGluZz0iMCIgY2VsbHNwYWNpbmc9IjAiIGJvcmRlcj0iMCIgd2lkdGg9IjEwMCUiPjx0cj48dGQgYWxpZ249InJpZ2h0Ij48aW5wdXQgb25DbGljaz0iZG9jdW1lbnQubG9jYXRpb24uaHJlZj0nPD9lY2hvIENhbFJvb3Q7Pz4vc2V0dXAvaW5kZXgucGhwP3N0ZXA9NCc7IiB0eXBlPSJidXR0b24iIG5hbWU9InN1Ym1pdCIgaWQ9InN1Ym1pdCIgdmFsdWU9IkNvbnRpbnVlIiBjbGFzcz0iZXZlbnRCdXR0b24iPjwvdGQ+PC90cj48L3RhYmxlPjw/fSBlbHNlIHtlY2hvICRyc3NfY2hhbm5lbFsiSVRFTVMiXVswXVsiTUVTU0FHRSJdO319fQ=='));?>
					</td>
				</tr>
			</table>
	<?	}//end if	
}//end if?>