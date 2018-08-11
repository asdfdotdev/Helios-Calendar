<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/register.php');
	
	$rID = (isset($_GET['rID']) && is_numeric($_GET['rID'])) ? cIn(strip_tags($_GET['rID'])) : 0;
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	$instTitle = $hc_lang_register['TitleRegisterA'];
	$instText = $hc_lang_register['InstructRegisterA'];
	$name = $email = $phone = $address = $address2 = $city = $postal = '';
	$state = $hc_cfg[21];
	
	if(isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(2, $hc_lang_register['Feed01']);
				break;
		}
	}

	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE PkID = '" . $rID . "'");
	if(hasRows($result)){
		$instTitle = $hc_lang_register['TitleRegisterE'];
		$instText = $hc_lang_register['InstructRegisterE'];
		$name = mysql_result($result,0,1);
		$email = mysql_result($result,0,2);
		$phone = mysql_result($result,0,3);
		$address = mysql_result($result,0,4);
		$address2 = mysql_result($result,0,5);
		$city = mysql_result($result,0,6);
		$state = mysql_result($result,0,7);
		$postal = mysql_result($result,0,8);
	}
	
	$inputs = array(1 => array('City','city',$city),2 => array('Postal','zip',$postal));
	$first = ($hc_lang_config['AddressType'] == 1) ? 1 : 2;
	$second = ($first == 1) ? 2 : 1;
	
	appInstructions(0, "", $instTitle, $instText);

	if($eID > 0){
		echo '
		<form id="frm" name="frm" method="post" action="'.AdminRoot.'/components/RegisterAddAction.php" onsubmit="return validate();">
		<input name="oldemail" id="oldemail" type="hidden" value="'.$email.'" />
		<input name="eventID" id="eventID" type="hidden" value="'.$eID.'" />
		<input name="rID" id="rID" type="hidden" value="'.$rID.'" />
		<fieldset>
			<legend>'.$hc_lang_register['RegLabel'].'</legend>
			<label for="name">'.$hc_lang_register['Name'].'</label>
			<input name="name" id="name" type="text" size="20" maxlength="50" value="'.$name.'" required="required" />
			<label for="name">'.$hc_lang_register['Email'].'</label>
			<input name="email" id="email" type="email" size="40" maxlength="75" value="'.$email.'" required="required" />
			<label for="phone">'.$hc_lang_register['Phone'].'</label>
			<input name="phone" id="phone" type="text" size="20" maxlength="25" value="'.$phone.'" />
			<label for="address">'.$hc_lang_register['Address'].'</label>
			<input name="address" id="address" type="text" size="25" maxlength="75" value="'.$address.'" />
			<label for="address2">'.$hc_lang_register['Address2'].'</label>
			<input name="address2" id="address2" type="text" size="25" maxlength="75" value="'.$address2.'" />
			<label for="' . $inputs[$first][1] . '">' . $hc_lang_register[$inputs[$first][0]] . '</label>
			<input name="' . $inputs[$first][1] . '" id="' . $inputs[$first][1] . '" type="text" size="20" maxlength="50" value="'.$inputs[$first][2].'" /><span class="output req2">*</span>';

			if($hc_lang_config['AddressRegion'] != 0){	
				echo '<label for="locState">' . $hc_lang_config['RegionLabel'] . '</label>';
				$regSelect = $state;
				include(HCLANG.'/'.$hc_lang_config['RegionFile']);
				echo '<span class="output req2">*</span>';}

			echo '
			<label for="'.$inputs[$second][1].'">'.$hc_lang_register[$inputs[$second][0]].'</label>
			<input name="'.$inputs[$second][1].'" id="'.$inputs[$second][1].'" type="text" size="20" maxlength="50" value="'.$inputs[$second][2].'" /><span class="output req2">*</span>
		</fieldset>
		
		<input type="submit" name="submit" id="submit" value="'.$hc_lang_register['SaveReg'].'" />
		<input name="cancel" id="cancel" type="button" value="  '.$hc_lang_register['Cancel'].'  " onclick="window.location.href=\''.AdminRoot.'/index.php?com=eventedit&amp;eID='.$_GET['eID'].'\';" />
		</form>

		<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
		<script>
		//<!--
		function validate(){
			var err = "";

			err +=reqField(document.getElementById("name"),"'.$hc_lang_register['Valid02'].'\n");
			err +=reqField(document.getElementById("email"),"'.$hc_lang_register['Valid03'].'\n");
			if(document.getElementById("email").value != "")
				err +=validEmail(document.getElementById("email"),"'.$hc_lang_register['Valid04'].'\n");

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
	} else {
		echo '<p>'.$hc_lang_register['EditWarning'].'</p>';
	}
?>