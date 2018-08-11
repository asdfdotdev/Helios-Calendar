<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/newsletter.php');
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? $_GET['uID'] : 0;
	
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
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE PkID = '" . cIn($uID) . "' AND IsConfirm = 1");
	if(hasRows($result)){
		appInstructions(0, "Manage_Subscribers", $hc_lang_news['TitleEditR'], $hc_lang_news['InstructEditR']);
		$firstname = mysql_result($result,0,1);
		$lastname = mysql_result($result,0,2);
		$email = mysql_result($result,0,3);
		$occupation = mysql_result($result,0,4);
		$zipcode = mysql_result($result,0,5);
		$addedby = mysql_result($result,0,8);
		$birthyear = mysql_result($result,0,11);
		$gender = mysql_result($result,0,12);
		$referral = mysql_result($result,0,13);
		$format = mysql_result($result,0,14);
	} else {
		appInstructions(0, "Manage_Subscribers", $hc_lang_news['TitleAddR'], $hc_lang_news['InstructAddR']);
		$firstname = $lastname = $email = $zipcode = $birthyear = $gender = $referral = $format = '';
		$occupation = 0;
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Email.js"></script>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
	dirty = 0;
	warn = "<?php echo $hc_lang_news['Valid01'];?>";
		
		if(document.getElementById('firstname').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid02'];?>';
		}//end if
	
		if(document.getElementById('lastname').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid03'];?>';
		}//end if
		
		if(document.getElementById('email').value == ''){
			dirty = 1;
			warn = warn + '\n<?php echo $hc_lang_news['Valid04'];?>';
		} else {
			if(chkEmail(document.getElementById('email')) == 0){
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
			document.getElementById('submit').disabled = true;
			document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
			return true;
		}//end if
	}//end chkFrm
	//-->
	</script>

	<form name="frmUserEdit" id="frmUserEdit" method="post" action="<?php echo CalAdminRoot . "/components/MailSubEditAction.php";?>" onsubmit="return chkFrm();">
	<input type="hidden" name="uID" id="uID" value="<?php echo $uID;?>" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="<?php echo $email;?>" />
<?php
	if($uID == 0){
		echo '<br />';
		echo '<fieldset>';
		echo '<legend>' . $hc_lang_news['OptInLabel'] . '</legend>';
		echo '<div class="frmOpt">' . $hc_lang_news['OptInNotice'] . '</div>';
		echo '<div class="frmOpt">';
		echo '<label>' . $hc_lang_news['OptIn'] . '</label>';
		echo '<select name="sendOIE" id="sendOIE">';
		echo '<option value="0">' . $hc_lang_news['OptIn0'] . '</option>';
		echo '<option value="1" selected="selected">' . $hc_lang_news['OptIn1'] . '</option>';
		echo '</select>';
		echo '</fieldset>';
	}//end if?>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['Subscriber'];?></legend>
<?php 	if(isset($addedby)){
			echo '<div class="frmUserEditOpt">';
			echo '<label>' . $hc_lang_news['AddedBy'] . '</label>';
			if($addedby > 0){
				$result = doQuery("SELECT FirstName, LastName, Email FROM " . HC_TblPrefix . "admin WHERE PkID = '" . cIn($addedby) . "'");
				echo (hasRows($result)) ? '<a class="main" href="mailto:' . cOut(mysql_result($result,0,2)) . '">' . trim(cOut(mysql_result($result,0,0)) . ' ' . cOut(mysql_result($result,0,1))) . '</a>' : $hc_lang_news['AddAdmin'];
			} else {	
				echo $hc_lang_news['AddPublic'];
	 		}//end if
			echo '</div>';
		}//end if	?>
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
			<label for="birthyear"><?php echo $hc_lang_news['Birth'];?></label>
			<select name="birthyear" id="birthyear">
			<option value="0"><?php echo $hc_lang_news['Birth0'];?></option>
	<?php	$yearSU = date("Y") - 14;
			for($x=0;$x<=80;$x++){
				$year = $yearSU - $x;
				echo ($year == $birthyear) ? '<option selected="selected" value="' . $year . '">' : '<option value="' . $year . '">';
				echo $year . '</option>';
			}//end for?>
			</select>
		</div>
		<div class="frmOpt">
			<label for="occupation"><?php echo $hc_lang_news['Occupation'];?></label>
	<?php 	include($hc_langPath . $_SESSION['LangSet'] . '/' . $hc_lang_config['OccupationFile']);?>
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
				<option <?php if($referral == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_news['Referral1'];?></option>
				<option <?php if($referral == 2){echo 'selected="selected"';}?> value="2"><?php echo $hc_lang_news['Referral2'];?></option>
				<option <?php if($referral == 3){echo 'selected="selected"';}?> value="3"><?php echo $hc_lang_news['Referral3'];?></option>
				<option <?php if($referral == 4){echo 'selected="selected"';}?> value="4"><?php echo $hc_lang_news['Referral4'];?></option>
				<option <?php if($referral == 5){echo 'selected="selected"';}?> value="5"><?php echo $hc_lang_news['Referral5'];?></option>
				<option <?php if($referral == 6){echo 'selected="selected"';}?> value="6"><?php echo $hc_lang_news['Referral6'];?></option>
				<option <?php if($referral == 7){echo 'selected="selected"';}?> value="7"><?php echo $hc_lang_news['Referral7'];?></option>
			</select>
		</div>
		<div class="frmOpt">
			<label for="zip"><?php echo $hc_lang_news['Postal'];?></label>
			<input name="zip" id="zip" type="text" size="12" maxlength="10" value="<?php echo $zipcode;?>" />
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['NewsLabel'];?></legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<div class="adminNotice"><?php echo $hc_lang_news['NewsAbout'];?></div>
		</div>
		<div class="frmOpt">
			<label for="format"><?php echo $hc_lang_news['LinkFormat'];?></label>
			<select name="format" id="format">
				<option <?php if($format == 0){echo 'selected="selected"';}?> value="0"><?php echo $hc_lang_news['LinkFormat0'];?></option>
				<option <?php if($format == 1){echo 'selected="selected"';}?> value="1"><?php echo $hc_lang_news['LinkFormat1'];?></option>
			</select>
			&nbsp;<?php appInstructionsIcon($hc_lang_news['Tip03A'],$hc_lang_news['Tip03B']);?>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Groups'];?></label>
	<?php	$result = doQuery("SELECT mg.PkID, mg.Name, sg.GroupID
							FROM " . HC_TblPrefix . "mailgroups mg
								LEFT JOIN " . HC_TblPrefix . "subscribersgroups sg ON (mg.PkID = sg.GroupID AND sg.UserID = '" . $uID . "')
							WHERE mg.IsActive = 1 && mg.PkID > 1
							ORDER BY Name");
			$columns = 3;
			$cnt = 1;
			echo '<div class="catCol">';
			while($row = mysql_fetch_row($result)){
				if($cnt > ceil(mysql_num_rows($result) / $columns)){
					echo '</div><div class="catCol">';
					$cnt = 1;
				}//end if
				echo '<label for="grpID_' . $row[0] . '" class="group"><input ';
				echo ($row[2] != '') ? 'checked="checked" ' : '';
				echo 'name="grpID[]" id="grpID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" />' . cOut($row[1]) . '</label>';
				++$cnt;
			}//end while
			echo '</div>';?>
		</div>
	</fieldset>
	<br />
	<fieldset>
		<legend><?php echo $hc_lang_news['EventLabel'];?></legend>
		<div class="frmOpt">
			<label>&nbsp;</label>
			<div class="adminNotice"><?php echo $hc_lang_news['EventAbout'];?></div>
		</div>
		<div class="frmOpt">
			<label><?php echo $hc_lang_news['Categories'];?></label>
	<?php 	$query = ($uID > 0) ?
					"SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, uc.UserID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID
					UNION
					SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, uc.UserID as Selected
					FROM " . HC_TblPrefix . "categories c
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID
					ORDER BY Sort, ParentID, CategoryName" : NULL;
			getCategories('frmUserEdit', 3, $query);?>
		</div>
	</fieldset>
	<br />
	<input type="submit" name="submit" id="submit" value="  <?php echo $hc_lang_news['Save'];?>  " class="button" />
	</form>