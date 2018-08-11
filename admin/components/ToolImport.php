<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}

	include(HCLANG.'/admin/tools.php');
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, $hc_lang_tools['Feed02']);
				break;
			case "2" :
				feedback(2, $hc_lang_tools['Feed01']);
				break;
		}
	}
	
	appInstructions(0, "Event_Import", $hc_lang_tools['TitleImport'], $hc_lang_tools['InstructImport']);
	
	$token = set_form_token(1);
	$hc_Side[] = array(AdminRoot.'/components/ToolImportAction.php?samp=1&tkn='.$token,'download_csv.png',$hc_lang_tools['LinkTemplate'],0);
	
	echo '
	<form name="frmEventImport" id="frmEventImport" method="post" action="'.AdminRoot.'/components/ToolImportAction.php" onsubmit="return validate();">
	<input type="hidden" name="token" id="token" value="'.$token.'" />
	<fieldset>
		<legend>'.$hc_lang_tools['ImportLabel'].'</legend>
		<label for="impType">'.$hc_lang_tools['Import'].'</label>
		<select name="impType" id="impType" onchange="toggleMe(document.getElementById(\'csv\'));">
			<option value="0">'.$hc_lang_tools['Import0'].'</option>
			<option value="1">'.$hc_lang_tools['Import1'].'</option>
		</select>
	</fieldset>
	<div id="csv">
		<fieldset>
			<legend>'.$hc_lang_tools['DataLabel'].'</legend>
			<label for="enclChar">'.$hc_lang_tools['Enclosed'].'</label>
			<select name="enclChar" id="enclChar">
				<option value="2">"</option>
				<option value="1">\'</option>
			</select>
			<label for="termChar">'.$hc_lang_tools['Terminated'].'</label>
			<select name="termChar" id="termChar">
				<option value=",">&nbsp;,&nbsp;</option>
			</select>
		</fieldset>
	</div>
	<fieldset>
		<legend>'.$hc_lang_tools['EventData'].'</legend>
		<label for="eventIn">'.$hc_lang_tools['Data'].'</label>
		<textarea name="import" id="import" rows="15" required="required"></textarea>
		<label>'.$hc_lang_tools['Categories'].'</label>';
		getCategories('frmEventImport', 3);
		
	echo '
	</fieldset>
	<input name="submit" id="submit" type="submit" value="'.$hc_lang_tools['ImportButton'].'" />
	</form>
	
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	function validate(){
		var err = "";

		err +=reqField(document.getElementById("import"),"'.$hc_lang_tools['Valid10'].'\n");
		err +=validCheckArray("frmEventImport","catID[]",1,"'.$hc_lang_tools['Valid11'].'\n");
		
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