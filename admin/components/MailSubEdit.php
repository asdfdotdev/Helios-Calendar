<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/newsletter.php');
	
	$uID = (isset($_GET['uID']) && is_numeric($_GET['uID'])) ? cIn(strip_tags($_GET['uID'])) : 0;
	$firstname = $lastname = $email = $zipcode = $birthyear = $gender = $referral = $bOptions = '';
	$format = 2;
	$yearSU = date("Y") - 14;
	
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
		}
	}
	
	echo '
	<form name="frmUserEdit" id="frmUserEdit" method="post" action="'.AdminRoot.'/components/MailSubEditAction.php" onsubmit="return validate();">';
	set_form_token();
	
	$result = doQuery("SELECT s.*, a.FirstName, a.LastName, a.Email
					FROM " . HC_TblPrefix . "subscribers s
						LEFT JOIN " . HC_TblPrefix . "admin a ON (s.AddedBy = a.PkID)
					WHERE s.PkID = '" . $uID . "' AND s.IsConfirm = 1");
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
		$added = ($addedby > 0 && mysql_result($result,0,15) != '') ? '<a href="mailto:' . cOut(mysql_result($result,0,17)) . '">' . trim(cOut(mysql_result($result,0,15)) . ' ' . cOut(mysql_result($result,0,16))) . '</a>' : $hc_lang_news['AddAdmin'];
	} else {
		appInstructions(0, "Manage_Subscribers", $hc_lang_news['TitleAddR'], $hc_lang_news['InstructAddR']);
		$occupation = 0;
		echo '
	<fieldset>
		<legend>'.$hc_lang_news['OptInLabel'].'</legend>
		<span class="output">
			'.$hc_lang_news['OptInNotice'].'
		</span>
		<label>'.$hc_lang_news['OptIn'].'</label>
		<select name="sendOIE" id="sendOIE">
			<option value="0">'.$hc_lang_news['OptIn0'].'</option>
			<option value="1" selected="selected">'.$hc_lang_news['OptIn1'].'</option>
		</select>
	</fieldset>';
	}
	
	for($x=0;$x<=80;$x++){
		$bOptions .= '<option'.((($yearSU - $x) == $birthyear) ? ' selected="selected"':'').' value="'.($yearSU - $x).'">'.($yearSU - $x).'</option>';
	}
	
	echo '
	<fieldset>
		<legend>'.$hc_lang_news['Subscriber'].'</legend>
		'.(($uID > 0) ? '<label>' . $hc_lang_news['AddedBy'] . '</label>
		<span class="output">
			'.(($added == '') ? $hc_lang_news['AddPublic'] : $added).'
		</span>':'').'
		<label for="firstname">'.$hc_lang_news['FName'].'</label>
		<input name="firstname" id="firstname" type="text" size="15" maxlength="50" required="required" value="'.$firstname.'" />
		<label for="lastname">'.$hc_lang_news['LName'].'</label>
		<input name="lastname" id="lastname" type="text" size="20" maxlength="50" required="required" value="'.$lastname.'" />
		<label for="email">'.$hc_lang_news['Email'].'</label>
		<input name="email" id="email" type="email" size="40" maxlength="75" required="required" value="'.$email.'" />
		<label for="birthyear">'.$hc_lang_news['Birth'].'</label>
		<select name="birthyear" id="birthyear">
			<option value="0">'.$hc_lang_news['Birth0'].'</option>
			'.$bOptions.'
		</select>
		<label for="occupation">'.$hc_lang_news['Occupation'].'</label>';
		include(HCLANG.'/'.$hc_lang_config['OccupationFile']);

		echo '
		<label for="gender">'.$hc_lang_news['Gender'].'</label>
		<select name="gender" id="gender">
			<option value="0">'.$hc_lang_news['Gender0'].'</option>
			<option'.(($gender == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_news['GenderF'].'</option>
			<option'.(($gender == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_news['GenderM'].'</option>
		</select>
		<label for="referral">'.$hc_lang_news['Referral'].'</label>
		<select name="referral" id="referral">
			<option value="0">'.$hc_lang_news['Referral0'].'</option>
			<option'.(($referral == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_news['Referral1'].'</option>
			<option'.(($referral == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_news['Referral2'].'</option>
			<option'.(($referral == 3) ? ' selected="selected"':'').' value="3">'.$hc_lang_news['Referral3'].'</option>
			<option'.(($referral == 4) ? ' selected="selected"':'').' value="4">'.$hc_lang_news['Referral4'].'</option>
			<option'.(($referral == 5) ? ' selected="selected"':'').' value="5">'.$hc_lang_news['Referral5'].'</option>
			<option'.(($referral == 6) ? ' selected="selected"':'').' value="6">'.$hc_lang_news['Referral6'].'</option>
			<option'.(($referral == 7) ? ' selected="selected"':'').' value="7">'.$hc_lang_news['Referral7'].'</option>
		</select>
		<label for="zip">'.$hc_lang_news['Postal'].'</label>
		<input name="zip" id="zip" type="text" size="12" maxlength="10" value="'.$zipcode.'" />
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_news['NewsLabel'].'</legend>
		<label>&nbsp;</label>
		<span class="output">
			'.$hc_lang_news['NewsAbout'].'
		</span>
		<label for="format">'.$hc_lang_news['LinkFormat'].'</label>
		<select name="format" id="format">
			<option'.(($format == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_news['LinkFormat0'].'</option>
			<option'.(($format == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_news['LinkFormat1'].'</option>
			<option'.(($format == 2) ? ' selected="selected"':'').' value="2">'.$hc_lang_news['LinkFormat2'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_news['Tip03'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_news['Groups'].'</label>
		<div class="catCol">';
		
	$columns = 3;
	$cnt = 1;
	$result = doQuery("SELECT mg.PkID, mg.Name, sg.GroupID
					FROM " . HC_TblPrefix . "mailgroups mg
						LEFT JOIN " . HC_TblPrefix . "subscribersgroups sg ON (mg.PkID = sg.GroupID AND sg.UserID = '" . $uID . "')
					WHERE mg.IsActive = 1 && mg.PkID > 1 ORDER BY Name");
	while($row = mysql_fetch_row($result)){
		if($cnt > ceil(mysql_num_rows($result) / $columns)){
			echo '
	</div>
	<div class="catCol">';
			$cnt = 1;}
		echo '<label for="grpID_'.$row[0].'" class="group"><input'.(($row[2] != '') ? ' checked="checked" ':'').' name="grpID[]" id="grpID_'.$row[0].'" type="checkbox" value="'.$row[0].'" />'.cOut($row[1]).'</label>';
		++$cnt;
	}
	echo '
			</div>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_news['EventLabel'].'</legend>
		<label>&nbsp;</label>
		'.$hc_lang_news['EventAbout'].'
		<label>'.$hc_lang_news['Categories'].'</label>';
		
	$query = ($uID > 0) ?
			"SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, uc.UserID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID)
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
				LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
			WHERE c.ParentID = 0 AND c.IsActive = 1
			GROUP BY c.PkID, c.CategoryName, c.ParentID, uc.UserID
			UNION
			SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, uc.UserID as Selected
			FROM " . HC_TblPrefix . "categories c
				LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
				LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
				LEFT JOIN " . HC_TblPrefix . "subscriberscategories uc ON (uc.CategoryID = c.PkID AND uc.UserID = '" . cIn($uID) . "')
			WHERE c.ParentID > 0 AND c.IsActive = 1
			GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, uc.UserID
			ORDER BY Sort, ParentID, CategoryName" : NULL;
	
	getCategories('frmUserEdit', 3, $query);
	
	echo '
	</fieldset>
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_news['Save'].'" />
	<input type="hidden" name="uID" id="uID" value="'.$uID.'" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="'.$email.'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("firstname"),"'.$hc_lang_news['Valid02'].'\n");
		err +=reqField(document.getElementById("lastname"),"'.$hc_lang_news['Valid03'].'\n");
		err +=reqField(document.getElementById("email"),"'.$hc_lang_news['Valid04'].'\n");
		if(document.getElementById("email").value != "")
			err +=validEmail(document.getElementById("email"),"'.$hc_lang_news['Valid05'].'\n");
		err +=validCheckArray("frmUserEdit","catID[]",1,"'.$hc_lang_news['Valid06'].'\n");
			
		if(err != ""){
			alert(err);
			return false;
		} else {
			valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");
			return true;
		}
	}
	//-->
	</script>';
?>