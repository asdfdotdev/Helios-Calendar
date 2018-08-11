<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['uID']) && is_numeric($_GET['uID'])){
		$uID = $_GET['uID'];
		$where = "Edit";
	} else {
		$uID = 0;
		$where = "Add";
	}//end if
?>
<script language="JavaScript">
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
</script>
<?php 
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "User Deleted Successfully!");
				break;
		}//end switch
	}//end if
	
	appInstructions(0, "User Edit", "Use the form below to search for the user you wish to edit.<br><br>If you wish to search by only part of a name or email address, only type as much of the search criteria as you wish to match.");
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE PkID = " . $uID);
	$row_cnt = mysql_num_rows($result);
	
	if($row_cnt > 0){
		$name = mysql_result($result,0,1);
		$email = mysql_result($result,0,2);
	} else {
		$name = "";
		$email = "";
	}//end if
?>
<br>
<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=userbrowse" class="main">&laquo;&laquo;Browse Newsletter Recipients</a></div>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/index.php?com=usersearchresult";?>" onSubmit="return chkFrm();">
<input type="hidden" name="uID" id="uID" value="<?echo $uID;?>">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan="2" class="eventMain"><b>Search For Users</b></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="75">
			Last Name:
		</td>
		<td>
			<input type="text" name="name" id="name" value="" class="input">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Email:</td>
		<td>
			<input type="text" name="email" id="email" value="" class="input">
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Begin Search " class="button">
		</td>
	</tr>
</table>
</form>