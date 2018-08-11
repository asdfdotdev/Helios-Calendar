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
?>
<script language="JavaScript">
function chkFrm(){
	if(document.frm.agree.checked == false){
		alert('You must accept the license to begin the setup.');
		return false;
	} else {
		return true;
	}//end if
}//end chkFrm()
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="eventMain">
			<b>This license is perminantly available in /docs/license.html</b><br>
			<iframe src="<?echo CalRoot;?>/docs/license.html" width="100%" height="300" frameborder="0"></iframe>
		</td>
	</tr>
	<tr><td><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<form name="frm" id="frm" method="post" action="<?echo CalRoot;?>/setup/index.php?step=2" onSubmit="return chkFrm();">
		<td class="eventMain" align="center">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><input type="checkbox" name="agree" id="agree"></td>
					<td class="eventMain"><label for="agree">I have read, understand, and agree to the Helios License.</label></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr><td><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td align="right"><input type="submit" name="submit" id="submit" value="Agree &amp; Begin Setup" class="eventButton"></td>
		</form>
	</tr>
</table>

