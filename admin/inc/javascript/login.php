<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	include_once(HCLANG . '/admin/login.php');

	echo '
	var err = "";
	function valid_request(){err += reqField(document.getElementById("email"),"'.$hc_lang_login['Valid05'].'\n");err += validEmail(document.getElementById("email"),"'.$hc_lang_login['Valid06'].'\n");';
	captchaValidation(0);
	echo 'if(err != ""){alert(err);return false;} else {valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");return true;}}
	function valid_reset(){err= "";'.(($hc_cfg[91] == 1) ? 'err += validPassword();':'').'err += reqField(document.getElementById("pass1"),"'.$hc_lang_login['Valid01'].'\n");err += reqField(document.getElementById("pass2"),"'.$hc_lang_login['Valid02'].'\n");err += validEqual(document.getElementById("pass1"),document.getElementById("pass2"),"'.$hc_lang_login['Valid03'].'\n");if(err != ""){alert(err);return false;} else {valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");return true;}}
	function valid_login(){err += reqField(document.getElementById("username"),"error");err += validEmail(document.getElementById("username"),"error");err += reqField(document.getElementById("password"),"error");if(err != ""){return false;} else {valid_ok(document.getElementById("submit"),"'.$hc_lang_core['Sending'].'");return true;}}
	function validPassword(){var passErr = "";var which = document.getElementById("pass1");if(validMinLength(which,6,"error") != ""){which.className = "error";passErr += "'.$hc_lang_login['Valid07'].'\n";}var filter  = /[0-9]/;if(!filter.test(which.value)){which.className = "error";passErr += "'.$hc_lang_login['Valid08'].'\n";}var filter = /[A-Z]/;if(!filter.test(which.value)){which.className = "error";passErr += "'.$hc_lang_login['Valid09'].'\n";}var filter = /[^a-zA-Z0-9_]/;if(!filter.test(which.value)){which.className = "error";passErr += "'.$hc_lang_login['Valid10'].'\n";}return passErr;}';
?>