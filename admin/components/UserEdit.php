<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright � 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
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
		
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "Alert recipient could not be added for the following reason(s):";
		
		if(document.frmUserEdit.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n*First Name is Required';
		}//end if
	
		if(document.frmUserEdit.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n*Last Name is Required';
		}//end if
		
		if(document.frmUserEdit.email.value == ''){
			dirty = 1;
			warn = warn + '\n*Email Address is Required';
		} else {
			if(chkEmail(document.frmUserEdit.email) == 0){
				dirty = 1;
				warn = warn + '\n*Invalid Email Format';
			}//end if
		}//end if
		
		if(validateCheckArray('frmUserEdit','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n*Category Selection is Required';
		}//end if
		
		if(document.frmUserEdit.occupation.value == 0){
			dirty = 1;
			warn = warn + '\n*Occupation is Required';
		}//end if
		
		if(document.frmUserEdit.zip.value == ''){
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
	//-->
	</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "Newsletter Recipient Updated Successfully!");
				break;
				
			case "2" :
				feedback(1, "Newsletter RecipientUser Added Successfully!");
				break;
				
			case "3" :
				feedback(2, "Email Address Already Exists.<br />All Updates But Email Address Made Successfully.");
				break;
			
			case "4" :
				feedback(2, "Email Address Already Exists. Newsletter Recipient Not Added");
				break;
			
		}//end switch
	}//end if
	
	if($uID == 0){
		appInstructions(0, "Adding_Recipients", "Add Newsletter Recipient", "Use the form below to add newsletter recipient.<br />Once added they will receive the next newsletter sent by Helios.");
	} else {
		appInstructions(0, "Editing_Recipients", "Edit Newsletter Recipient", "Use the form below to edit the newsletter recipient.");
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
	}//end if	?>
	<form name="frmUserEdit" id="frmUserEdit" method="post" action="<?php echo CalAdminRoot . "/components/UserEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="<?php echo $email;?>" />
	<br />
	<fieldset>
		<legend>Recipient Details</legend>
<?php 	if( isset($addedby) ){	?>
		<div class="frmUserEditOpt">
			<label>Added By:</label>
	<?php 	if($addedby > 0){
				$result = doQuery("SELECT FirstName, LastName, Email FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($addedby) . "'");
				if(hasRows($result)){
					echo "<a class=\"main\" href=\"mailto:" . cOut(mysql_result($result,0,2)) . "\">" . cOut(mysql_result($result,0,0)) . " " . cOut(mysql_result($result,0,1)) . "</a>";
				} else {	?>
					Deleted Admin
		<?php 	}//end if
			} else {	?>
				Public Sign-Up
	<?php 	}//end if	?>
		</div>
<?php 	}//end if	?>
		<div class="frmUserEditReq">
			<label for="firstname">First Name:</label>
			<input name="firstname" id="firstname" type="text" size="15" maxlength="50" value="<?php echo $firstname;?>" />
		</div>
		<div class="frmUserEditReq">
			<label for="lastname">Last Name:</label>
			<input name="lastname" id="lastname" type="text" size="15" maxlength="50" value="<?php echo $lastname;?>" />
		</div>
		<div class="frmUserEditReq">
			<label for="email">Email:</label>
			<input name="email" id="email" type="text" size="30" maxlength="75" value="<?php echo $email;?>" />
		</div>
		<div class="frmUserEditReq">
			<label for="occupation">Occupation:</label>
	<?php 	include('../events/includes/selectOccupation.php');?>
		</div>
		<div class="frmUserEditReq">
			<label for="zip">Postal Code:</label>
			<input name="zip" id="zip" type="text" size="5" maxlength="5" value="<?php echo $zipcode;?>" />
		</div>
		<div class="frmUserEditOpt">
			<label for="firstname">Categories:</label>
	<?php 	if($uID == 0){
				getCategories('frmUserEdit', 2);
			} else {
				$query = "	SELECT " . HC_TblPrefix . "categories.*, " . HC_TblPrefix . "usercategories.UserID
							FROM " . HC_TblPrefix . "categories 
								LEFT JOIN " . HC_TblPrefix . "usercategories ON (" . HC_TblPrefix . "categories.PkID =" . HC_TblPrefix . "usercategories.CategoryID AND " . HC_TblPrefix . "usercategories.UserID = " . cIn($uID) . ") 
							WHERE " . HC_TblPrefix . "categories.IsActive = 1
							ORDER BY CategoryName";
				getCategories('frmUserEdit', 2, $query);
			}//end if	?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value="   Save Recipient   " class="button" />
	</form>
