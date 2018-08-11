<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Your search could not be completed because of the following reasons:\n";
		
		if(document.frm.name.value == '' && document.frm.email.value == ''){
			dirty = 1;
			warn = warn + '\n*Name or Email is Required';
		}//end if
	
		if(dirty > 0){
			alert(warn + '\n\nPlease make the required changes and submit your search again.');
			return false;
		} else {
			return true;
		}//end if
		
	}//end chkFrm
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "User Deleted Successfully!");
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "Newsletter_Recipients", "User Edit", "Use the form below to search for the user you wish to edit.<br /><br />If you wish to search by only part of a name or email address, only type as much of the search criteria as you wish to match.");	?>
	<div style="width:55%;">
	<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/index.php?com=userbrowse";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="search" id="search" value="1" />
	<br />
	<fieldset>
		<legend>Search for Newsletter Recipient</legend>
		<div class="frmReq">
			<label for="name">Last Name:</label>
			<input name="name" id="name" type="text" size="25" maxlength="50" value="" />
		</div>
		<div class="frmReq">
			<label for="email">Email:</label>
			<input name="email" id="email" type="text" size="30" maxlength="75" value="" />
		</div>
	</fieldset>
	<br />
	<input name="submit" id="submit" type="submit" value=" Begin Search " class="button" />
	</form>
	</div>