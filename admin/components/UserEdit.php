<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/newsletter.php');
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? $_GET['uID'] : 0;
	$name = (isset($_GET['name'])) ? cIn($_GET['name']) : '';
	$email = (isset($_GET['email'])) ? cIn($_GET['email']) : '';
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_news['Feed01']);
				break;
			case "2" :
				feedback(1, $hc_lang_news['Feed02']);
				break;
			case "3" :
				feedback(2, $hc_lang_news['Feed03']);
				break;
			case "4" :
				feedback(2, $hc_lang_news['Feed04']);
				break;
		}//end switch
	}//end if
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "users WHERE PkID = " . cIn($uID));
	if(hasRows($result)){
		appInstructions(0, "Editing_Recipients", $hc_lang_news['TitleEditR'], $hc_lang_news['InstructEditR']);
		$firstname = mysql_result($result,0,1);
		$lastname = mysql_result($result,0,2);
		$email = mysql_result($result,0,3);
		$occupation = mysql_result($result,0,4);
		$zipcode = mysql_result($result,0,5);
		$addedby = mysql_result($result,0,8);
		$birthyear = mysql_result($result,0,11);
		$gender = mysql_result($result,0,12);
		$referral = mysql_result($result,0,13);
	} else {
		appInstructions(0, "Adding_Recipients", $hc_lang_news['TitleAddR'], $hc_lang_news['InstructAddR']);
		$firstname = "";
		$lastname = "";
		$occupation = 0;
		$zipcode = "";
		$birthyear = "";
		$gender = "";
		$referral = "";
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_news['Valid01'];?>";
		
		if(document.frmUserEdit.firstname.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid02'];?>';
		}//end if
	
		if(document.frmUserEdit.lastname.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid03'];?>';
		}//end if
		
		if(document.frmUserEdit.email.value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid04'];?>';
		} else {
			if(chkEmail(document.frmUserEdit.email) == 0){
				dirty = 1;
				warn = warn + '\n<?php echo $hc_lang_news['Valid05'];?>';
			}//end if
		}//end if
		
		if(validateCheckArray('frmUserEdit','catID[]',1,'Category') > 0){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid06'];?>';
		}//end if
		
		if(dirty > 0){
			alert(warn + '\n\n<?php echo $hc_lang_news['Valid07'];?>');
			return false;
		} else {
			return true;
		}//end if
	}//end chkFrm
	//-->
	</script>

	<form name="frmUserEdit" id="frmUserEdit" method="post" action="<?php echo CalAdminRoot . "/components/UserEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="<?php echo $email;?>" />
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['Recipient'];?></legend>
<?php 	if(isset($addedby)){	?>
		<div class="frmUserEditOpt">
			<label><?php echo $hc_lang_news['AddedBy'];?></label>
	<?php 	if($addedby > 0){
				$result = doQuery("SELECT FirstName, LastName, Email FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($addedby) . "'");
				if(hasRows($result)){
					echo "<a class=\"main\" href=\"mailto:" . cOut(mysql_result($result,0,2)) . "\">" . cOut(mysql_result($result,0,0)) . " " . cOut(mysql_result($result,0,1)) . "</a>";
				} else {
					echo $hc_lang_news['AddAdmin'];
		 		}//end if
			} else {	
				echo $hc_lang_news['AddPublic'] . "";
	 		}//end if	?>
		</div>
<?php 	}//end if	?>
		<div class="frmReq">
			<label for="firstname"><?php echo $hc_lang_news['FName'];?></label>
			<input name="firstname" id="firstname" type="text" size="15" maxlength="50" value="<?php echo $firstname;?>" />
		</div>
		<div class="frmReq">
			<label for="lastname"><?php echo $hc_lang_news['LName'];?></label>
			<input name="lastname" id="lastname" type="text" size="15" maxlength="50" value="<?php echo $lastname;?>" />
		</div>
		<div class="frmReq">
			<label for="email"><?php echo $hc_lang_news['Email'];?></label>
			<input name="email" id="email" type="text" size="30" maxlength="75" value="<?php echo $email;?>" />
		</div>
		<div class="frmOpt">
			<label for="occupation"><?php echo $hc_lang_news['Occupation'];?></label>
	<?php 	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/' . $hc_lang_config['OccupationFile']);?>
		</div>
		<div class="frmOpt">
			<label for="birthyear"><?php echo $hc_lang_news['Birth'];?></label>
			<select name="birthyear" id="birthyear">
			<option value="0"><?php echo $hc_lang_news['Birth0'];?></option>
	<?php	$yearSU = date("Y") - 13;
			for($x=0;$x<=80;$x++){
				if(($yearSU - $x) == $birthyear){
					echo "<option selected=\"selected\" value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
				} else {
					echo "<option value=\"" . ($yearSU - $x) . "\">" . ($yearSU - $x) . "</option>";
				}//end if
			}//end for	?>
			</select>
		</div>
		<div class="frmOpt">
			<label for="gender"><?php echo $hc_lang_news['Gender'];?></label>
			<select name="gender" id="gender">
				<option value="0"><?php echo $hc_lang_news['Gender0'];?></option>
				<option <?php if($gender == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_news['GenderF'];?></option>
				<option <?php if($gender == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_news['GenderM'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="referral"><?php echo $hc_lang_news['Referral'];?></label>
			<select name="referral" id="referral">
				<option value="0"><?php echo $hc_lang_news['Referral0'];?></option>
				<option <?php if($referral == 1){echo "selected=\"selected\"";}?> value="1"><?php echo $hc_lang_news['Referral1'];?></option>
				<option <?php if($referral == 2){echo "selected=\"selected\"";}?> value="2"><?php echo $hc_lang_news['Referral2'];?></option>
				<option <?php if($referral == 3){echo "selected=\"selected\"";}?> value="3"><?php echo $hc_lang_news['Referral3'];?></option>
				<option <?php if($referral == 4){echo "selected=\"selected\"";}?> value="4"><?php echo $hc_lang_news['Referral4'];?></option>
				<option <?php if($referral == 5){echo "selected=\"selected\"";}?> value="5"><?php echo $hc_lang_news['Referral5'];?></option>
				<option <?php if($referral == 6){echo "selected=\"selected\"";}?> value="6"><?php echo $hc_lang_news['Referral6'];?></option>
				<option <?php if($referral == 7){echo "selected=\"selected\"";}?> value="7"><?php echo $hc_lang_news['Referral7'];?></option>
				
			</select>
		</div>
		<div class="frmOpt">
			<label for="zip"><?php echo $hc_lang_news['Postal'];?></label>
			<input name="zip" id="zip" type="text" size="12" maxlength="10" value="<?php echo $zipcode;?>" />
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Categories'];?></label>
	<?php 	$query = NULL;
			if($uID > 0){
				$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, uc.UserID as Selected
							FROM " . HC_TblPrefix . "categories c 
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID)
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
								LEFT JOIN " . HC_TblPrefix . "usercategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
							WHERE c.ParentID = 0 AND c.IsActive = 1
							GROUP BY c.PkID
							UNION 
							SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, uc.UserID as Selected
							FROM " . HC_TblPrefix . "categories c 
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
								LEFT JOIN " . HC_TblPrefix . "usercategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
							WHERE c.ParentID > 0 AND c.IsActive = 1
							GROUP BY c.PkID 
							ORDER BY Sort, ParentID, CategoryName";
			}//end if
			
			getCategories('frmUserEdit', 3, $query);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value="  <?php echo $hc_lang_news['Save'];?>  " class="button" />
	</form>