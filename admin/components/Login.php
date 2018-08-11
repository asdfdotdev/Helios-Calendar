<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include(HCLANG.'/admin/login.php');
	
	$lp = (isset($_GET['lp']) && is_numeric($_GET['lp'])) ? cIn($_GET['lp']) : 0;
	$k = (isset($_GET['k'])) ? cIn(strip_tags($_GET['k'])) : '';
	$link = '<a href="'.AdminRoot.'/index.php" rel="nofollow" class="footer">'.$hc_lang_login['SignIn'].'</a>';

	if($lp == 2 && $k != '')
		$result = doQuery("SELECT PkID, Email, LoginCnt FROM " . HC_TblPrefix . "admin WHERE PCKey = '" . $k . "' AND PCKey IS NOT NULL");
	
		echo '
	<div id="login">
		<img src="'.AdminRoot.'/img/logo.png" id="logo" />';
		
	if(isset($_GET['lmsg'])){
		switch ($_GET['lmsg']){
			case "1" :
				feedback(2,$hc_lang_login['Feed01']);
				break;
			case "2" :
				feedback(2,$hc_lang_login['Feed02']);
				break;
			case "3" :
				feedback(1,$hc_lang_login['Feed03']);
				break;
			case "4" :
				feedback(1,$hc_lang_login['Feed04']);
				break;
			case "5" :
				feedback(2,$hc_lang_login['Feed05']);
				break;
			case "6" :
				feedback(2,$hc_lang_login['Feed06']);
				break;
		}
	}
	
	if($lp == 1) {
		echo '
		<form name="frm" id="frm" method="post" action="' . AdminRoot . '/components/LostPasswordAction.php" onsubmit="return valid_request();">';
		
		if($hc_cfg[65] > 0)
			buildCaptcha(1);
		
		echo '
		<label for="email">'.$hc_lang_login['Username'].'</label>
		<input name="email" id="email" type="email" maxlength="100" value="" required="required" autofocus="autofocus" />		
		<label>&nbsp;</label><input name="submit" id="submit" type="submit" value="'.$hc_lang_login['Button2'].'" />
		</form>';	
	} elseif($lp == 2 && $k != '' && hasRows($result)){
		if(isset($_GET['msg']) && $_GET['msg'] == '1')
			feedback(2,$hc_lang_login['Feed06']);
		echo '
		<form name="frm" id="frm" method="post" action="' . AdminRoot . '/components/ResetPassword.php" onsubmit="return valid_reset();">
		'.(($hc_cfg[91] == 1) ? '<label>&nbsp;</label><span>'.$hc_lang_login['PasswordReq'].'</span>' : '').'
		<label for="pass1">' . $hc_lang_login['Passwrd1'] . '</label>
		<input name="pass1" id="pass1" type="password" maxlength="15" value="" required="required" autofocus="autofocus" />
		<label for="pass2">' . $hc_lang_login['Passwrd2'] . '</label>
		<input name="pass2" id="pass2" type="password" maxlength="15" value="" required="required" />
		<label>&nbsp;</label><input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button3'] . '" />
		<input type="hidden" name="a" value="' . md5(mysql_result($result,0,0).mysql_result($result,0,1).mysql_result($result,0,2)) . '" />
		<input type="hidden" name="b" value="' . $k . '" />
		</form>';
	} else {
		echo '
		<form name="frm" id="frm" method="post" action="' . AdminRoot . '/components/LoginAction.php" onsubmit="return valid_login();">
		<label for="username">' . $hc_lang_login['Username'] . '</label>
		<input name="username" id="username" type="email" maxlength="100" value="" required="required" autofocus="autofocus" />		
		<label for="password">' . $hc_lang_login['Password'] . '</label>
		<input name="password" id="password" type="password" maxlength="100" value="" required="required" />
		<label for="langType">' . $hc_lang_login['Language'] . '</label><br />';
		
		if(file_exists(HCPATH.HCINC.'/lang')){
			echo '
		<select name="langType" id="langType" class="loginInput">';
			$dir = dir(HCPATH.HCINC.'/lang');
			$x = 0;
			while(($file = $dir->read()) != false){
				if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
					$langOption = $file;
					echo ($langOption == $_SESSION['LangSet']) ? '
			<option selected="selected" value="' . $langOption . '">' . ucwords($langOption) . '</option>' : '
			<option value="' . $langOption . '">' . ucwords($langOption) . '</option>';
					++$x;
				}
			}
			if($x == 0)
				echo '<option value="">' . $hc_lang_login['NoLang'] . '</option>';
			
			echo '</select>';
		} else {
			echo '<b>' . $hc_lang_settings['NoLangDir'] . '</b>';}
		
		echo '
		<label>&nbsp;</label><input type="submit" name="submit" id="submit" value="'.$hc_lang_login['Button1'].'" />
		<input name="com" id="com" type="hidden" value="'.((!isset($_GET['com'])) ? 'home' : cIn(strip_tags($_GET['com']))).'" />
		</form>';
		
		$link = '<a href="'.AdminRoot.'/index.php?lp=1" rel="nofollow" class="footer">'.$hc_lang_login['Link1'].'</a>';
	}
	
	echo '
	</div>
	<div id="links">
		<a href="'.CalRoot.'" rel="nofollow" class="footer">&larr; '.$hc_lang_login['To'].' '.CalName.'</a> | '.$link.'
	</div>';
?>