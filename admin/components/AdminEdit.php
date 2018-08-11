<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/admin.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(2, $hc_lang_admin['Feed01']);
				break;
			case "2" :
				feedback(3, $hc_lang_admin['Feed02']);
				break;
		}
	}
	
	$aID = (isset($_GET['aID']) && is_numeric($_GET['aID'])) ? cIn($_GET['aID']) : 0;
	$result = doQuery("SELECT a.PkID, a.FirstName, a.LastName, a.Email, a.LoginCnt, a.LastLogin, a.PAge,
					ap.EventEdit, ap.EventPending, ap.EventCategory, ap.UserEdit, ap.AdminEdit, ap.Newsletter, ap.Settings, ap.Tools, ap.Reports, ap.Locations, ap.Pages,
					(SELECT GROUP_CONCAT(TypeID) FROM " . HC_TblPrefix . "adminnotices an WHERE an.AdminID = '" . $aID ."') as Notices,
					(SELECT COUNT(*) FROM " . HC_TblPrefix . "adminloginhistory WHERE AdminID = '" . $aID . "' AND LoginTime > subdate(NOW(), INTERVAL 24 HOUR) AND IsFail = 1) as Fails
					FROM " . HC_TblPrefix . "admin a
						LEFT JOIN " . HC_TblPrefix . "adminpermissions ap ON (a.PkID = ap.AdminID)
					WHERE a.PkID = '" . $aID . "' AND a.IsActive = 1 AND ap.IsActive = 1 AND a.SuperAdmin = 0
					ORDER BY LastName, FirstName");
	$oldEmail = $firstname = $lastname = $email = $login_history = $active = '';
	$editEvent = $eventPending = $eventCategory = $userEdit = $adminEdit = $newsletter = $settings = $tools = $reports = $locEdit = $pages = 0;
	$notices = array();
	
	if(hasRows($result)){
		appInstructions(0, "Editing_Admin_Users", $hc_lang_admin['TitleEditA'], $hc_lang_admin['InstructEditA']);
		$firstname = cOut(mysql_result($result,0,1));
		$lastname = cOut(mysql_result($result,0,2));
		$email = cOut(mysql_result($result,0,3));
		$oldEmail = cOut(mysql_result($result,0,3));
		$logins = mysql_result($result,0,4);
		$lastlogin = mysql_result($result,0,5);
		$passAge = mysql_result($result,0,6);
		$editEvent = mysql_result($result,0,7);
		$eventPending = mysql_result($result,0,8);
		$eventCategory = mysql_result($result,0,9);
		$userEdit = mysql_result($result,0,10);
		$adminEdit = mysql_result($result,0,11);
		$newsletter = mysql_result($result,0,12);
		$settings = mysql_result($result,0,13);
		$tools = mysql_result($result,0,14);
		$reports = mysql_result($result,0,15);
		$locEdit = mysql_result($result,0,16);
		$pages = mysql_result($result,0,17);
		$notices = array_filter(explode(',',mysql_result($result,0,18)),'is_numeric');
		$fails = mysql_result($result,0,19);
		$active = ($_SESSION['AdminPkID'] == $aID) ? ' disabled="disabled"' : '';
		
		$resultH = doQuery("SELECT * FROM " . HC_TblPrefix . "adminloginhistory WHERE AdminID = '" . $aID . "' ORDER BY LoginTime DESC LIMIT 100");
		if(hasRows($resultH)){
			$login_history .= '
		<ul class="data">';
			
			$cnt = 0;
			while ($row = mysql_fetch_row($resultH)){
				$hl = ($cnt % 2 == 1) ? ' hl':'';
				$login_history .= '
			<li class="row'.$hl.(($row[5] == 1) ? ' error':'').'">
				<div style="width:25%;">'.stampToDate($row[4],$hc_cfg[24].' '.$hc_cfg[23]).'</div>
				<div style="width:18%;">'.cOut($row[2]).'</div>
				<div class="txt" title="'.cOut($row[3]).'" style="width:55%;">'.cOut($row[3]).'</div>
				
			</li>';
				++$cnt;
			}
			
			$login_history .='
		</ul>';
		}
	} else {
		$aID = 0;
		appInstructions(0, "Adding_Admin_Users", $hc_lang_admin['TitleAddA'], $hc_lang_admin['InstructAddA']);
	}
	
	echo '
	<form name="frmAdminEdit" id="frmAdminEdit" method="post" action="'.AdminRoot.'/components/AdminEditAction.php" onsubmit="return validate();">';
	set_form_token();
	echo '
	<input type="hidden" name="aID" id="aID" value="'.$aID.'" />
	<input type="hidden" name="oldEmail" id="oldEmail" value="'.$oldEmail.'" />
	<fieldset>
		<legend>'.$hc_lang_admin['Details'].'</legend>
		<label for="firstname">'.$hc_lang_admin['FName'].'</label>
		<input name="firstname" id="firstname" type="text" size="20" maxlength="50" required="required" value="'.$firstname.'" />
		<label for="lastname">'.$hc_lang_admin['LName'].'</label>
		<input name="lastname" id="lastname" type="text" size="20" maxlength="50" required="required" value="'.$lastname.'" />
		<label for="email">'.$hc_lang_admin['Email'].'</label>
		<input name="email" id="email" type="email" size="30" maxlength="100" required="required" value="'.$email.'" />
		'.(($aID > 0) ? '
		<span class="output">
			&nbsp;<a href="mailto:'.$email.'?subject='.CalName.' - '.$hc_lang_admin['AdminSubject'].'" class="icon"><img src="'.AdminRoot.'/img/icons/email_open.png" width="16" height="15" alt=""></a>
		</span>':'').'
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_admin['EmailNotices'].'</legend>
		<label for="noteEmail">'.$hc_lang_admin['NoticeEvent'].'</label>
		<input name="notices[]" id="noteEmail" type="checkbox" value="0"'.((in_array(0, $notices)) ? ' checked="checked"':'').' />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip01n'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="noteLogin">'.$hc_lang_admin['NoticeLogin'].'</label>
		<input name="notices[]" id="noteLogin" type="checkbox" value="1"'.((in_array(1, $notices)) ? ' checked="checked"':'').'/>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip02n'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_admin['Permissions'].'</legend>
		'.(($active != '') ? '<p>'.$hc_lang_admin['NoSelf'].'</p>':'').'
		<label>'.$hc_lang_admin['EventEdit'].'</label>
		<span class="frm_ctrls_opts">
			<label for="editEventAllow"><input'.$active.(($editEvent == 1) ? ' checked="checked"':'').' name="editEvent" id="editEventAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="editEventLocked"><input'.$active.(($editEvent == 0) ? ' checked="checked"':'').' name="editEvent" id="editEventLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip01p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<div class="hl">
		<label>'.$hc_lang_admin['EventApp'].'</label>
		<span class="frm_ctrls_opts">
			<label for="eventPendingAllow"><input'.$active.(($eventPending == 1) ? ' checked="checked"':'').' name="eventPending" id="eventPendingAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="eventPendingLocked"><input'.$active.(($eventPending == 0) ? ' checked="checked"':'').' name="eventPending" id="eventPendingLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip02p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		</div>
		<label>'.$hc_lang_admin['Category'].'</label>
		<span class="frm_ctrls_opts">
			<label for="eventCategoryAllow"><input'.$active.(($eventCategory == 1) ? ' checked="checked"':'').' name="eventCategory" id="eventCategoryAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="eventCategoryLocked"><input'.$active.(($eventCategory == 0) ? ' checked="checked"':'').' name="eventCategory" id="eventCategoryLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip03p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<div class="hl">
		<label>'.$hc_lang_admin['LocEdit'].'</label>
		<span class="frm_ctrls_opts">
			<label for="locEditAllow"><input'.$active.(($locEdit == 1) ? ' checked="checked"':'').' name="editLoc" id="locEditAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="locEditLocked"><input'.$active.(($locEdit == 0) ? ' checked="checked"':'').' name="editLoc" id="locEditLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip04p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		</div>
		<label>'.$hc_lang_admin['PageEdit'].'</label>
		<span class="frm_ctrls_opts">
			<label for="pagesAllow"><input'.$active.(($pages == 1) ? ' checked="checked"':'').' name="pages" id="pagesAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="pagesLocked"><input'.$active.(($pages == 0) ? ' checked="checked"':'').' name="pages" id="pagesLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip11p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<div class="hl">
		<label>'.$hc_lang_admin['RecEdit'].'</label>
		<span class="frm_ctrls_opts">
			<label for="userEditAllow"><input'.$active.(($userEdit == 1) ? ' checked="checked"':'').' name="userEdit" id="userEditAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="userEditLocked"><input'.$active.(($userEdit == 0) ? ' checked="checked"':'').' name="userEdit" id="userEditLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip05p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		</div>
		<label>'.$hc_lang_admin['AdminEdit'].'</label>
		<span class="frm_ctrls_opts">
			<label for="adminEditAllow"><input'.$active.(($adminEdit == 1) ? ' checked="checked"':'').' name="adminEdit" id="adminEditAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="adminEditLocked"><input'.$active.(($adminEdit == 0) ? ' checked="checked"':'').' name="adminEdit" id="adminEditLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip06p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<div class="hl">
		<label>'.$hc_lang_admin['EventNews'].'</label>
		<span class="frm_ctrls_opts">
			<label for="newsletterAllow"><input'.$active.(($newsletter == 1) ? ' checked="checked"':'').' name="newsletter" id="newsletterAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="newsletterLocked"><input'.$active.(($newsletter == 0) ? ' checked="checked"':'').' name="newsletter" id="newsletterLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip07p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		</div>
		<label>'.$hc_lang_admin['Settings'].'</label>
		<span class="frm_ctrls_opts">
			<label for="settingsAllow"><input'.$active.(($settings == 1) ? ' checked="checked"':'').' name="settings" id="settingsAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="settingsLocked"><input'.$active.(($settings == 0) ? ' checked="checked"':'').' name="settings" id="settingsLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip08p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<div class="hl">
		<label>'.$hc_lang_admin['Tools'].'</label>
		<span class="frm_ctrls_opts">
			<label for="toolsAllow"><input'.$active.(($tools == 1) ? ' checked="checked"':'').' name="tools" id="toolsAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="toolsLocked"><input'.$active.(($tools == 0) ? ' checked="checked"':'').' name="tools" id="toolsLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip09p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		</div>
		<label>'.$hc_lang_admin['Reports'].'</label>
		<span class="frm_ctrls_opts">
			<label for="reportsAllow"><input'.$active.(($reports == 1) ? ' checked="checked"':'').' name="reports" id="reportsAllow" type="radio" value="1" /> '.$hc_lang_admin['Allow'].'</label>
			<label for="reportsLocked"><input'.$active.(($reports == 0) ? ' checked="checked"':'').' name="reports" id="reportsLocked" type="radio" value="0" /> '.$hc_lang_admin['Locked'].'</label>
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip10p'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>';
	
	if($aID > 0){
		echo '
	<fieldset>
		<legend>'.$hc_lang_admin['Summary'].'</legend>
		<label>'.$hc_lang_admin['LocStatus'].'</label>
		<span class="output">
			'.(($fails >= $hc_cfg[80]) ? '<span style="color:#E40000;">'.$hc_lang_admin['Locked'].'</span>' : $hc_lang_admin['Unlocked']).'
		</span>
		<label>'.$hc_lang_admin['LogCount'].'</label>
		<span class="output">
			'.$logins.'
		</span>
		<label>'.$hc_lang_admin['LogFCount'].'</label>
		<span class="output">
			'.$fails.'
		</span>
		<label>'.$hc_lang_admin['Login'].'</label>
		<span class="output">
			'.stampToDate($lastlogin,$hc_cfg[24].' '.$hc_cfg[23]).'
		</span>
		<label>'.$hc_lang_admin['PasswordAge'].'</label>
		<span class="output">
			'.(($passAge != '') ? (daysDiff($passAge, date("Y-m-d")) - 1) : $hc_lang_admin['Unavailable']).'
			<a class="tooltip" data-tip="'.$hc_lang_admin['Tip01'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label>'.$hc_lang_admin['Recent'].'</label>
		<div id="logins" class="output">
			'.(($login_history != '') ? $login_history : $hc_lang_admin['Unavailable']).'
		</div>
	</fieldset>';
	}
	
	echo '
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_admin['Save'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("firstname"),"'.$hc_lang_admin['Valid02'].'\n");
		err +=reqField(document.getElementById("lastname"),"'.$hc_lang_admin['Valid03'].'\n");
		err +=reqField(document.getElementById("email"),"'.$hc_lang_admin['Valid04'].'\n");
		if(document.getElementById("email").value != "")
			err +=validEmail(document.getElementById("email"),"'.$hc_lang_admin['Valid05'].'\n");
		
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