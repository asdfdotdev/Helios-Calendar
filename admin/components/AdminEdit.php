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
	} else {
		$uID = 0;
		
		if(isset($_GET['firstname'])){
			$firstname = $_GET['firstname'];
		} else {
			$firstname = "";
		}//end if
		
		if(isset($_GET['lastname'])){
			$lastname = $_GET['lastname'];
		} else {
			$lastname = "";
		}//end if
		
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		} else {
			$email = "";
		}//end if
		
	}//end if
?>
<script language="JavaScript">
function checkEmail(obj) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(obj.value)){
		return 0;
	} else {
		return 1;
	}//end if
}//end chkEmail

function chkFrm(){
dirty = 0;
warn = "Administrator could not be added because of the following reasons:\n";
	
	if(document.frm.firstname.value == ''){
		dirty = 1;
		warn = warn + '\n*First Name is Required';
	}//end if
	
	if(document.frm.lastname.value == ''){
		dirty = 1;
		warn = warn + '\n*Last Name is Required';
	}//end if
	
	if(document.frm.email.value == ''){
		dirty = 1;
		warn = warn + '\n*Email is Required';
	}//end if
	
	if(document.frm.email.value != '' && checkEmail(document.frm.email) == 1){
		dirty = 1;
		warn = warn + '\n*Email Format is Invalid';
	}//end if
	
	if(document.frm.password.value == ''){
		dirty = 1;
		warn = warn + '\n*Password is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease make the required changes and try again.');
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
				feedback(1, "Admin Updated Successfully!");
				break;
				
			case "2" :
				feedback(1, "Admin Added Successfully!");
				break;
				
			case "3" :
				feedback(2, "Email Address Already Exists.<br>All Updates But Email Address Made Successfully.");
				break;
				
			case "4" :
				feedback(2, "Email Address Already Exists.<br>Admin Not Added");
				break;
				
		}//end switch
	}//end if
	
	if($uID == 0){
		appInstructions(0, "Add_Administrator", "Add Admin Account", "Use the form below to <b>add</b> an admin account.");
	} else {
		appInstructions(0, "Edit_Administrator", "Edit Admin Account", "Use the form below to <b>edit</b> the admin account.");
	}//end if
?>
<br>
<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=adminbrowse" class="main">&laquo;&laquo;Return to Admin List</a></div>
<table cellpadding="0" cellspacing="0" border="0" width="400">
	<tr>
		<td valign="top">
			<?php
				$result = doQuery("	SELECT * FROM " . HC_TblPrefix . "admin
										LEFT JOIN " . HC_TblPrefix . "adminpermissions ON (" . HC_TblPrefix . "admin.PkID = " . HC_TblPrefix . "adminpermissions.AdminID)
									WHERE " . HC_TblPrefix  . "admin.PkID = " . cIn($uID));
				
				if(hasRows($result)){
					$firstname = cOut(mysql_result($result,0,1));
					$lastname = cOut(mysql_result($result,0,2));
					$email = cOut(mysql_result($result,0,3));
					$oldEmail = cOut(mysql_result($result,0,3));
					$password = cOut(mysql_result($result,0,4));
					$editEvent = cOut(mysql_result($result,0,11));
					$eventPending = cOut(mysql_result($result,0,12));
					$eventCategory = cOut(mysql_result($result,0,13));
					$userEdit = cOut(mysql_result($result,0,14));
					$adminEdit = cOut(mysql_result($result,0,15));
					$newsletter = cOut(mysql_result($result,0,16));
					$settings = cOut(mysql_result($result,0,17));
					$reports = cOut(mysql_result($result,0,18));
					
				} else {
					$oldEmail = "";
					$editEvent = 0;
					$eventPending = 0;
					$eventCategory = 0;
					$userEdit = 0;
					$adminEdit = 0;
					$newsletter = 0;
					$settings = 0;
					$reports = 0;
					
				}//end if
			?>
			<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_AdminEditAction;?>" onSubmit="return chkFrm();">
			<input type="hidden" name="uID" id="uID" value="<?echo $uID;?>">
			<input type="hidden" name="oldEmail" id="oldEmail" value="<?echo $oldEmail;?>">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td class="eventMain" colspan="2"><b>Admin Details</b></td>
				</tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain" width="125">&nbsp;First Name:</td>
					<td>
						<input size="20" maxlength="50" type="text" name="firstname" id="firstname" value="<?echo $firstname;?>" class="input">
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Last Name:</td>
					<td>
						<input size="20" maxlength="50" type="text" name="lastname" id="lastname" value="<?echo cOut($lastname);?>" class="input">
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Email:</td>
					<td>
						
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><input size="30" maxlength="100" type="text" name="email" id="email" value="<?echo $email;?>" class="input"></td>
								<td width="25" align="right"><? appInstructionsIcon("Admin Email Address", "This will be used as the username for the Admin account and <b>must be unique</b>."); ?></td>
							</tr>
						</table>
						
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Password:</td>
					<td>
						<input size="15" maxlength="15" type="text" name="password" id="password" value="<?if($uID == 0){echo makeRandomPassword(6);}else{echo $password;}//end if?>" class="input">
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<?php
			if($_SESSION['AdminPkID'] != $uID){
		?>
				<tr>
					<td bgcolor="#EFEFEF" class="eventMain">&nbsp;Event Edit:</td>
					<td bgcolor="#EFEFEF">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($editEvent == 1){echo "CHECKED";}//end if?> type="radio" name="editEvent" id="editEventAllow" value="1"></td>
								<td class="eventMain"><label for="editEventAllow">Allow</label></td>
								<td class="eventMain"><input <?if($editEvent == 0){echo "CHECKED";}//end if?> type="radio" name="editEvent" id="editEventLocked" value="0"></td>
								<td class="eventMain"><label for="editEventLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Event Edit", "Allows Administrator to Add, Edit and Delete Events. Add/Remove Events to the Billboard and manage Orphan Events."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Event Approval:</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($eventPending == 1){echo "CHECKED";}//end if?> type="radio" name="eventPending" id="eventPendingAllow" value="1"></td>
								<td class="eventMain"><label for="eventPendingAllow">Allow</label></td>
								<td class="eventMain"><input <?if($eventPending == 0){echo "CHECKED";}//end if?> type="radio" name="eventPending" id="eventPendingLocked" value="0"></td>
								<td class="eventMain"><label for="eventPendingLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Event Approval", "Allows Administrator to Approve/Decline Events in the Pending Event Queue."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td bgcolor="#EFEFEF" class="eventMain">&nbsp;Category Edit:</td>
					<td bgcolor="#EFEFEF">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($eventCategory == 1){echo "CHECKED";}//end if?> type="radio" name="eventCategory" id="eventCategoryAllow" value="1"></td>
								<td class="eventMain"><label for="eventCategoryAllow">Allow</label></td>
								<td class="eventMain"><input <?if($eventCategory == 0){echo "CHECKED";}//end if?> type="radio" name="eventCategory" id="eventCategoryLocked" value="0"></td>
								<td class="eventMain"><label for="eventCategoryLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Category Edit", "Allows Administrator to Add, Edit and Delete Event Categories."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Alert Recipient Edit:</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($userEdit == 1){echo "CHECKED";}//end if?> type="radio" name="userEdit" id="userEditAllow" value="1"></td>
								<td class="eventMain"><label for="userEditAllow">Allow</label></td>
								<td class="eventMain"><input <?if($userEdit == 0){echo "CHECKED";}//end if?> type="radio" name="userEdit" id="userEditLocked" value="0"></td>
								<td class="eventMain"><label for="userEditLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Alert Recipient Edit", "Allows Administrator to Add, Edit and Delete Alert Recipients."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td bgcolor="#EFEFEF" class="eventMain">&nbsp;Admin Edit:</td>
					<td bgcolor="#EFEFEF">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($adminEdit == 1){echo "CHECKED";}//end if?> type="radio" name="adminEdit" id="adminEditAllow" value="1"></td>
								<td class="eventMain"><label for="adminEditAllow">Allow</label></td>
								<td class="eventMain"><input <?if($adminEdit == 0){echo "CHECKED";}//end if?> type="radio" name="adminEdit" id="adminEditLocked" value="0"></td>
								<td class="eventMain"><label for="adminEditLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Admin Edit", "Allows Administrator to Add, Edit and Delete Admin Accounts."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Event Newsletter:</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($newsletter == 1){echo "CHECKED";}//end if?> type="radio" name="newsletter" id="newsletterAllow" value="1"></td>
								<td class="eventMain"><label for="newsletterAllow">Allow</label></td>
								<td class="eventMain"><input <?if($newsletter == 0){echo "CHECKED";}//end if?> type="radio" name="newsletter" id="newsletterLocked" value="0"></td>
								<td class="eventMain"><label for="newsletterLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Event Newsletter", "Allows Administrator to Create and Send Event Newsletters."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td class="eventMain">&nbsp;Settings:</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($settings == 1){echo "CHECKED";}//end if?> type="radio" name="settings" id="settingsAllow" value="1"></td>
								<td class="eventMain"><label for="settingsAllow">Allow</label></td>
								<td class="eventMain"><input <?if($settings == 0){echo "CHECKED";}//end if?> type="radio" name="settings" id="settingsLocked" value="0"></td>
								<td class="eventMain"><label for="settingsLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Settings", "Allows Administrator to Modify Calendar Settings."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td bgcolor="#EFEFEF" class="eventMain">&nbsp;Reports:</td>
					<td bgcolor="#EFEFEF">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain"><input <?if($reports == 1){echo "CHECKED";}//end if?> type="radio" name="reports" id="reportsAllow" value="1"></td>
								<td class="eventMain"><label for="reportsAllow">Allow</label></td>
								<td class="eventMain"><input <?if($reports == 0){echo "CHECKED";}//end if?> type="radio" name="reports" id="reportsLocked" value="0"></td>
								<td class="eventMain"><label for="reportsLocked">Locked</label></td>
								<td width="25" align="right"><? appInstructionsIcon("Reports", "Allows Administrator access to event activity reports."); ?></td>
							</tr>
						</table>
					</td>
				</tr>
		<?php
			} else {
		?>
				<tr>
					<td colspan="2" class="eventMain"><br>&nbsp;You cannot change access settings for your account.</td>
				</tr>
		<?
			}//end if
		?>	
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr><td colspan="3" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
				<tr><td colspan="3"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="submit" name="submit" id="submit" value=" Save User " class="button">
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>