<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf

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
<br />
<b>This license is permanently available in /events/docs/license.html</b><br />
<iframe src="<?php echo CalRoot;?>/docs/license.html" width="100%" height="300" frameborder="0"></iframe>
<br /><br />
<form name="frm" id="frm" method="post" action="<?php echo CalRoot;?>/setup/index.php?step=2" onsubmit="return chkFrm();">
<label for="agree" style="width:360px;"><input onchange="if(document.frm.agree.checked == true){document.frm.submit.disabled = false;}else{document.frm.submit.disabled = true;}" type="checkbox" name="agree" id="agree" />&nbsp;I have read, understand and agree to the Helios License.</label>
<br /><br />
<div align="right"><input disabled="disabled" type="submit" name="submit" id="submit" value="Agree &amp; Begin Setup" class="button" /></div>
</form>