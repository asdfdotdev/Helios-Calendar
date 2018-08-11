<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$uID = 0;
	$fname = "";
	$lname = "";
	$email = "";
	$oID = 0;
	$zip = "";
	$guid = 0;
	
	if(isset($_GET['guid'])){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE GUID = '" . cIn($_GET['guid']) . "'");
		
		if(hasRows($result)){
			$uID = cOut(mysql_result($result,0,0));
			$fname = cOut(mysql_result($result,0,1));
			$lname = cOut(mysql_result($result,0,2));
			$email = cOut(mysql_result($result,0,3));
			$oID = cOut(mysql_result($result,0,4));
			$zip = cOut(mysql_result($result,0,5));
			$guid = cOut($_GET['guid']);
		}//end if
	}//end if
?>
<script language="JavaScript">
function chkFrm()
{
dirty = 0;
warn = "Your submission could not be completed for the following reason(s):";

	if(document.eventReg.firstname.value == ''){
		dirty = 1;
		warn = warn + '\n*First Name is Required';
	}//end if

	if(document.eventReg.lastname.value == ''){
		dirty = 1;
		warn = warn + '\n*Last Name is Required';
	}//end if
	
	if(validateCheckArray('eventReg','catID[]',1,'Category') > 0){
		dirty = 1;
		warn = warn + '\n*Category Selection is Required';
	}//end if
	
	if(document.eventReg.occupation.value == 0){
		dirty = 1;
		warn = warn + '\n*Occupation is Required';
	}//end if
	
	if(document.eventReg.zip.value == ''){
		dirty = 1;
		warn = warn + '\n*Zip Code is Required';
	}//end if
	
	if(dirty > 0){
		alert(warn + '\n\nPlease complete the form and try again.');
		return false;
	} else {
		return true;
	}//end if
	
}//end chkFrm()

</script>

<?php 
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Registration Update Successful.");
				break;
				
		}//end switch
	}//end if
?>

Make the changes you'd like to your registration below.<br>Then click "Save Changes" to save your registration.<br><br>
If you wish to change your email address unsubscribe with your current address and signup with a new one.<br><br>
<table cellpadding="0" cellspacing="0" border="0">
<form name="eventReg" id="eventReg" method="post" action="<?echo HC_EditRegisterAction;?>" onSubmit="return chkFrm();">
<input type="hidden" name="guid" id="guid" value="<?echo $guid;?>">
	<tr>
		<td class="eventMain" width="80">First Name:</td>
		<td><input size="15" maxlength="50" type="text" name="firstname" id="firstname" value="<?echo $fname;?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain" width="80">Last Name:</td>
		<td><input size="15" maxlength="50" type="text" name="lastname" id="lastname" value="<?echo $lname;?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td class="eventMain">Occupation:</td>
		<td>
			<select name="occupation" id="occupation" class="eventInput">
				<option value="0">[Select an Occupation]</option>
			<?php
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "useroccupation WHERE IsActive = 1 ORDER BY Occupation");
				if(hasRows($result)){
					
					while($row = mysql_fetch_row($result)){
					?>
						<option<?if($oID == $row[0]){echo " SELECTED";}//end if?> value="<?echo $row[0];?>"><?echo cOut($row[1]);?></option>
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
		<td><input maxlength="5" size="5" type="text" name="zip" id="zip" value="<?echo $zip;?>" class="eventInput"></td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td valign="top" class="eventMain">Category:</td>
		<td class="eventMain">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
			<?php
				$query = "	SELECT " . HC_TblPrefix . "categories.*, 1 as IsSelected
							FROM " . HC_TblPrefix . "categories
								LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "usercategories.CategoryID)
							WHERE " . HC_TblPrefix . "categories.IsActive = 1 AND " . HC_TblPrefix . "usercategories.UserID = " . $uID . "
							UNION
							SELECT " . HC_TblPrefix . "categories.*, 0 as IsSelected
							FROM " . HC_TblPrefix . "categories
								LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID = " . HC_TblPrefix . "usercategories.CategoryID)
							WHERE " . HC_TblPrefix . "categories.IsActive = 1 AND " . HC_TblPrefix . "usercategories.UserID != " . $uID . " OR " . HC_TblPrefix . "usercategories.UserID is null
							ORDER BY CategoryName, IsSelected DESC";
					//echo $query;exit;
				
				$result = doQuery($query);
				$cnt = 0;
				$curCat = "";
				while($row = mysql_fetch_row($result)){
					if($row[3] > 0){
						if((fmod($cnt,2) == 0) AND ($cnt > 0)){echo "</tr><tr>";}//end if
						
						if($curCat != $row[1]){
							$curCat = $row[1];
					?>
						<td class="eventMain"><input <?if($row[6] == 1){echo "checked";}//end if?> type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
						<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
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
				[ <a class="eventMain" href="javascript:;" onClick="checkAllArray('eventReg', 'catID[]');">Select All Categories</a> 
				&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onClick="uncheckAllArray('eventReg', 'catID[]');">Deselect All Categories</a> ]
				<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
			<?	}//end if	?>
		</td>
	</tr>
	<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="submit" id="submit" value=" Save Changes " class="eventButton">
		</td>
	</tr>
</form>
</table>