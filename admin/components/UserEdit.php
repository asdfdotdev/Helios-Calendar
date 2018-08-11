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
		
		if(isset($_GET['name'])){
			$name = $_GET['name'];
		} else {
			$name = "";
		}//end if
		
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		} else {
			$email = "";
		}//end if
		
	}//end if
?>
<script language="JavaScript">
function chkEmail(obj){
	var x = obj.value;
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if (filter.test(x)) {
		return 1;
	} else {
		return 0;
	}//end if
}//end chkMail()

function chkFrm(){
dirty = 0;
warn = "Alert recipient could not be added for the following reason(s):\n\n";
	
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
		warn = warn + '\n*Email Address is Required';
	} else {
		if(chkEmail(document.frm.email) == 0){
			dirty = 1;
			warn = warn + '\n*Invalid Email Format';
		}//end if
	}//end if
	
	if(validateCheckArray('frm','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(document.frm.occupation.value == 0){
		dirty = 1;
		warn = warn + '\n*Occupation is Required';
	}//end if
	
	if(document.frm.zip.value == ''){
		dirty = 1;
		warn = warn + '\n*Zip Code is Required';
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
				feedback(1, "User Updated Successfully!");
				break;
				
			case "2" :
				feedback(1, "User Added Successfully!");
				break;
				
			case "3" :
				feedback(2, "Email Address Already Exists.<br>All Updates But Email Address Made Successfully.");
				break;
			
			case "4" :
				feedback(2, "Email Address Already Exists. User Not Added");
				break;
			
		}//end switch
	}//end if
	
	if($uID == 0){
		appInstructions(0, "Add_Newsletter_Recipient", "Add Newsletter Recipient", "Use the form below to add newsletter recipient to the system.");
	} else {
		appInstructions(0, "Newsletter_Recipients", "Edit Newsletter Recipient", "Use the form below to edit the newsletter recipient.");
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE PkID = " . cIn($uID));
	
	if(hasRows($result)){
		$firstname = mysql_result($result,0,1);
		$lastname = mysql_result($result,0,2);
		$email = mysql_result($result,0,3);
		$occupation = mysql_result($result,0,4);
		$zipcode = mysql_result($result,0,5);
		$addedby = mysql_result($result,0,8);
		
	} else {
		$firstname = "";
		$lastname = "";
		$email = "";
		$occupation = 0;
		$zipcode = "";
		
	}//end if
	
	if(isset($_GET['fname'])){
		$firstname = $_GET['fname'];
	}//end if
	
	if(isset($_GET['lname'])){
		$lastname = $_GET['lname'];
	}//end if
	
	if(isset($_GET['occ'])){
		$occupation = $_GET['occ'];
	}//end if
	
	if(isset($_GET['zip'])){
		$zipcode = $_GET['zip'];
	}//end if
?>
<br>
<?if($uID > 0){?>
	<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=useredit" class="main">&laquo;&laquo;Add Newsletter Recipient</a></div>
<?} else {?>
	<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=userbrowse" class="main">&laquo;&laquo;Edit Newsletter Recipient</a></div>
<?}//end if?>
<form name="frm" id="frm" method="post" action="<?echo CalAdminRoot . "/" . HC_UserEditAction;?>" onSubmit="return chkFrm();">
<input type="hidden" name="uID" id="uID" value="<?echo $uID;?>">
<input type="hidden" name="oldEmail" id="oldEmail" value="<?echo $email;?>">
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan="2" class="eventMain" width="80"><b>Recipient Details</b></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="2" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="80">First Name:</td>
		<td><input size="15" maxlength="50" type="text" name="firstname" id="firstname" value="<?echo $firstname;?>" class="input"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="80">Last Name:</td>
		<td><input size="15" maxlength="50" type="text" name="lastname" id="lastname" value="<?echo $lastname;?>" class="input"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Email:</td>
		<td><input size="30" maxlength="75" type="text" name="email" id="email" value="<?echo $email;?>" class="input"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Occupation:</td>
		<td>
			<select name="occupation" id="occupation" class="input">
				<option value="0">[Select an Occupation]</option>
			<?php
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "useroccupation WHERE IsActive = 1 ORDER BY Occupation");
				if(hasRows($result)){
					
					if(isset($_GET['occ']) && is_numeric($_GET['occ'])){
						$oID = $_GET['occ'];
					} else {
						$oID = 0;
					}//end if
					
					while($row = mysql_fetch_row($result)){
					?>
						<option<?if($occupation == $row[0]){echo " SELECTED";}//end if?> value="<?echo $row[0];?>"><?echo $row[1];?></option>
					<?
					}//while
					
				}//end if
			?>
			</select>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Zip Code:</td>
		<td><input maxlength="5" size="5" type="text" name="zip" id="zip" value="<?echo $zipcode;?>" class="input"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	
<?
	if( isset($addedby) ){?>
	<tr>
		<td class="eventMain">Added By:</td>
		<td class="eventMain">
			<?php
				if($addedby > 0){
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($addedby) . "'");
					echo "<a class=\"main\" href=\"mailto:" . cOut(mysql_result($result,0,3)) . "\">" . cOut(mysql_result($result,0,1)) . " " . cOut(mysql_result($result,0,2)) . "</a>";
				} else {
				?>
					Public Sign-Up
				<?
				}//end 
			?>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
<?	}//end if
	
		if($uID == 0){?>
	
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr>
				<td valign="top" class="eventMain">Category:</td>
				<td class="eventMain">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
					<?php
						$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
						$cnt = 0;
						
						while($row = mysql_fetch_row($result)){
							if((fmod($cnt,3) == 0) AND ($cnt > 0)){echo "</tr><tr>";}//end if
						?>
							<td class="eventMain"><input type="checkbox" name="catID[]" id="catID_<?echo cOut($row[0]);?>" value="<?echo cOut($row[0]);?>"></td>
							<td class="eventMain"><label for="catID_<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></label>&nbsp;&nbsp;</td>
						<?
							$cnt = $cnt + 1;
						}//end while
					?>
						</tr>
					</table>
					<?	if($cnt > 1){	?>
						<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
						[ <a class="main" href="javascript:;" onClick="checkAllArray('frm', 'catID[]');">Select All Categories</a> 
						&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('frm', 'catID[]');">Deselect All Categories</a> ]
						<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
					<?	}//end if	?>
				</td>
			</tr>
	<?	} else {	?>
	
			<tr><td colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr>
				<td valign="top" class="eventMain">Category:</td>
				<td class="eventMain">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
					<?php
						$query = "	SELECT " . HC_TblPrefix . "categories.*, 1 as IsSelected
									FROM " . HC_TblPrefix . "categories
										LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "usercategories.CategoryID)
									WHERE " . HC_TblPrefix . "categories.IsActive = 1 AND " . HC_TblPrefix . "usercategories.UserID = " . cIn($uID) . "
									
									UNION
									
									SELECT " . HC_TblPrefix . "categories.*, 0 as IsSelected
									FROM " . HC_TblPrefix . "categories
										LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "usercategories.CategoryID)
									WHERE " . HC_TblPrefix . "categories.IsActive = 1 AND " . HC_TblPrefix . "usercategories.UserID != " . cIn($uID) . " OR " . HC_TblPrefix . "usercategories.UserID is null
									ORDER BY CategoryName, IsSelected DESC";
							//echo $query;exit;
						
						$result = doQuery($query);
						$cnt = 0;
						$curCat = "";
						while($row = mysql_fetch_row($result)){
							if($row[3] > 0){
								if((fmod($cnt,3) == 0) AND ($cnt > 0)){echo "</tr><tr>";}//end if
								
								if($curCat != $row[1]){
									$curCat = $row[1];
							?>
								<td class="eventMain"><input <?if($row[6] == 1){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo cOut($row[0]);?>" value="<?echo cOut($row[0]);?>"></td>
								<td class="eventMain"><label for="catID_<?echo cOut($row[0]);?>"><?echo cOut($row[1]);?></label>&nbsp;&nbsp;</td>
							<?
								$cnt = $cnt + 1;
								}//end if
							}//end if
						}//end while
					?>
						</tr>
					</table>
					<?	if($cnt > 1){	?>
						<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br>
						[ <a class="main" href="javascript:;" onClick="checkAllArray('frm', 'catID[]');">Select All Categories</a> 
						&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('frm', 'catID[]');">Deselect All Categories</a> ]
						<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
					<?	}//end if	?>
				</td>
			</tr>
	
	<?	}//end if	?>
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