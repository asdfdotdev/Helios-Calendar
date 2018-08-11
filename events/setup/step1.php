<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html

	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 		|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/?>
	<script language="JavaScript" type="text/JavaScript">
	function chkFrm(){
		if(document.frm.agree.checked == false){
			alert('You must accept the license to begin the setup.');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm()
	</script>	
	<form name="frm" id="frm" method="post" action="index.php?step=2" onsubmit="return chkFrm();">
	<fieldset>
		<iframe src="../docs/license.html" width="100%" height="400" frameborder="0"></iframe>
		<label for="agree" style="text-align:left;vertical-align:middle;line-height:35px;width:680px;"><input onchange="if(document.frm.agree.checked == true){document.frm.submit.disabled = false;}else{document.frm.submit.disabled = true;}" type="checkbox" name="agree" id="agree" />&nbsp;I have read, understand and agree to the terms of the Helios Calendar Software License Agreement.</label>
	</fieldset>
	<br />
	<input disabled="disabled" type="submit" name="submit" id="submit" value="Accept License Agreement &amp; Begin Setup" class="button" />
	</form>