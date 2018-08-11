<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
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
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Your edit could not be completed for the following reason(s):";
	
		if(document.frmEventReg.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n*First Name is Required';
		}//end if
	
		if(document.frmEventReg.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n*Last Name is Required';
		}//end if
		
		if(validateCheckArray('frmEventReg','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n*Category Selection is Required';
		}//end if
		
		if(document.frmEventReg.occupation.value == 0){
			dirty = 1;
			warn = warn + '\n*Occupation is Required';
		}//end if
		
		if(document.frmEventReg.zip.value == ''){
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
	//-->
	</script>
<?	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Registration Update Successful.");
				break;
				
		}//end switch
	}//end if	?>
	<br />
	Make the changes you'd like to your registration below.<br>Then click "Save Changes" to save your registration.<br><br>
	If you wish to change your email address unsubscribe with your current address and signup with a new one.<br><br>
	<form name="frmEventReg" id="frmEventReg" method="post" action="<?echo HC_EditRegisterAction;?>" onsubmit="return chkFrm();">
	<input type="hidden" name="guid" id="guid" value="<?echo $guid;?>">
	<fieldset>
		<legend>Edit Your Registration</legend>
		<div class="frmReq">
			<label for="firstname">First Name:</label>
			<input name="firstname" id="firstname" type="text" size="15" maxlength="50" value="<?echo $fname;?>" />
		</div>
		<div class="frmReq">
			<label for="lastname">Last Name:</label>
			<input name="lastname" id="lastname" type="text" size="15" maxlength="50" value="<?echo $lname;?>" />
		</div>
		<div class="frmReq">
			<label for="firstname">Occupation:</label>
			<select name="occupation" id="occupation" class="eventInput">
				<option value="0">[Select an Occupation]</option>
			<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "useroccupation WHERE IsActive = 1 ORDER BY Occupation");
				if(hasRows($result)){
					
					while($row = mysql_fetch_row($result)){
					?>
						<option<?if($oID == $row[0]){echo " SELECTED";}//end if?> value="<?echo $row[0];?>"><?echo cOut($row[1]);?></option>
					<?
					}//while
					
				}//end if	?>
			</select>
		</div>
		<div class="frmOpt">
			<label for="lastname">Postal Code:</label>
			<input name="zip" id="zip" type="text" size="5" maxlength="5" value="<?echo $zip;?>" />
		</div>
		<div class="frmOpt">
			<label>Categories:</label>
			<?	$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "usercategories.UserID
							FROM " . HC_TblPrefix . "categories 
								LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID =" . HC_TblPrefix . "usercategories.CategoryID AND " . HC_TblPrefix . "usercategories.UserID = " . cIn($uID) . ") 
							WHERE " . HC_TblPrefix . "categories.IsActive = 1
							ORDER BY CategoryName";
				getCategories('frmEventReg', 2, $query);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value=" Save Changes " class="button" />			
	</form>