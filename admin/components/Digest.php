<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/pages.php');
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_pages['Feed01']);
				break;
		}
	}
	
	appInstructions(0, "Digest", $hc_lang_pages['TitleDigest'], $hc_lang_pages['InstructDigest']);
	
	$aID = (isset($_GET['aID']) && is_numeric($_GET['aID'])) ? cIn($_GET['aID']) : 0;
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN (97,98,99)");
	
	$status = 0;
	$newFor = 1;
	$welcomeMsg = '';
	
	if(hasRows($result)){
		$status = cOut(mysql_result($result,0,1));
		$welcomeMsg = cOut(mysql_result($result,1,1));
		$newFor = cOut(mysql_result($result,2,1));
	}
	
	echo '
	<form name="frmDigest" id="frmDigest" method="post" action="'.AdminRoot.'/components/DigestAction.php" onsubmit="return validate();">
	<fieldset>
		<legend>'.$hc_lang_pages['Settings'].'</legend>
		<label for="status">'.$hc_lang_pages['StatusD'].'</label>
		<select name="status" id="status">
			<option'.(($status == 0) ? ' selected="selected"':'').' value="0">'.$hc_lang_pages['StatusD0'].'</option>
			<option'.(($status == 1) ? ' selected="selected"':'').' value="1">'.$hc_lang_pages['StatusD1'].'</option>
		</select>
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_pages['Tip01'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
		<label for="newFor">'.$hc_lang_pages['NewFor'].'</label>
		<input name="newFor" id="newFor" type="number" min="1" max="99" size="3" maxlength="2" value="'.$newFor.'" />
		<span class="output">
			<a class="tooltip" data-tip="'.$hc_lang_pages['Tip02'].'" href="javascript:;" tabindex="-1"><img src="'.AdminRoot.'/img/icons/info.png" width="16" height="16" alt="" /></a>
		</span>
	</fieldset>
	<fieldset>
		<legend>'.$hc_lang_pages['Welcome'].'</legend>
		<textarea name="welcomeMsg" id="welcomeMsg" rows="15">'.$welcomeMsg.'</textarea>
	</fieldset>';
		
	echo '
	<input type="submit" name="submit" id="submit" value="'.$hc_lang_pages['SaveD'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";

		err += reqField(document.getElementById("newFor"),"'.$hc_lang_pages['Valid01'].'\n");
		if(document.getElementById("newFor").value != ""){
			err +=validNumber(document.getElementById("newFor"),"'.$hc_lang_pages['Valid02'].'\n");
			err +=validGreater(document.getElementById("newFor"),0,"'.$hc_lang_pages['Valid02'].'\n");
		}
		
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
	
	makeTinyMCE('welcomeMsg',1,0,0);
?>