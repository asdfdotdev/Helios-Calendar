<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Message Sent Successfully!<br>A Helios Support Team Meber Will Respond Shortly.");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Support Contact Form", "Before contacting the WebCalendar support staff please be sure to review the <a href=\" " . CalAdminRoot . "/index.php?com=helptopics\" class=\"main\">help topics</a>");
?>

<script language="JavaScript">
function chkFrm(){
dirty = 0;
warn = 'Your message cannot be sent because of the following:';

	if (document.frm.message.value == ''){
		dirty = 1;
		warn = warn + '\n*Message is Required';
	}//end if
	
	if (dirty > 0){
		alert(warn + '\nPlease make the required changes and try again.');
		return false;
	}//end if
}
</script>
<br>
<form id="frm" name="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_ContactAction;?>" onSubmit="return chkFrm();">

	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="75" class="eventMain">Name:</td>
			<td class="eventMain">
				<input type="hidden" name="name" id="name" value="<?echo $_SESSION['AdminFirstName'] . " " . $_SESSION['AdminLastName'];?>">
				<?echo $_SESSION['AdminFirstName'] . " " . $_SESSION['AdminLastName'];?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Email:</td>
			<td class="eventMain">
				<input type="hidden" name="email" id="email" value="<?echo $_SESSION['AdminEmail'];?>">
				<?echo $_SESSION['AdminEmail'];?>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain">Subject:</td>
			<td class="eventMain">
				<select name="ihave" id="ihave" class="input">
					<option value="Question">Question</option>
					<option value="Idea">Idea</option>
					<option value="Comment">Comment</option>
					<option value="Bug Report">Bug Report</option>
				</select>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
			<td class="eventMain" valign="top">Message:</td>
			<td class="eventMain">
				<textarea rows="10" cols="65" name="message" id="message" class="input"></textarea>
			</td>
		</tr>
		<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
			<td>&nbsp;</td>
			<td>
				<input type="submit" name="submit" value=" Send Your Message " class="button">
			</td>
		</tr>
	</table>
	
</form>