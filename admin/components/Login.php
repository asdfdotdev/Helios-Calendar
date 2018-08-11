<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/login.php');
	
	$lp = (isset($_GET['lp']) && is_numeric($_GET['lp'])) ? cIn($_GET['lp']) : 0;
	
	$k = '';
	if($lp == 2 && isset($_GET['k'])){
		$k = $_GET['k'];
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "admin WHERE PCKey = '" . cIn($k) . "' AND PCKey IS NOT NULL");
	}//end if
	
	echo '<br /><br />';
	if (isset($_GET['msg'])){
		echo '<div style="width:330px;margin:auto;">';

		switch ($_GET['msg']){
			case "1" :
			case "2" :
				feedback(2,$hc_lang_login['Feedback1']);
				break;
			case "3" :
				feedback(1,$hc_lang_login['Feedback2']);
				break;
			case "4" :
				feedback(1,$hc_lang_login['Feedback3']);
				break;
			case "5" :
				feedback(2,$hc_lang_login['Feedback4']);
				break;
		}//end switch
		echo '</div>';
	}//end if
	echo '<br /><div id="login">';
	
	if($lp == 1) {
		echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/LostPasswordAction.php" onsubmit="if(document.frm.email.value == \'\'){alert(\'' . $hc_lang_login['Valid5'] . '\');document.frm.elements[0].focus();return false;}">';
		echo '<div class="loginform">';
		echo $hc_lang_login['Instruct1'];
		echo '<br /><br />';
		echo '<label for="email">' . $hc_lang_login['Email'] . '</label><br />';
		echo '<input name="email" id="email" type="text" value="" maxlength="100" class="loginInput" />';
		echo '<br /><br />';
		echo '<input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button2'] . '" class="loginButton" />';
		echo '<br /><br />';
		echo '<div align="right"><a href="' . CalAdminRoot . '/" class="main">' . $hc_lang_login['Link2'] . '</a></div>';
		echo '</div></form>';
	} elseif($lp == 2 && $k != '' && hasRows($result)){?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
			function chkFrm(){
			var dirty = 0;
			warn = '';
				if(document.frm.pass1.value == ''){
					dirty = 1;
					warn = warn + '<?php echo $hc_lang_login['Valid1'];?>\n'
				}//end if
				if(document.frm.pass2.value == ''){
					dirty = 1;
					warn = warn + '<?php echo $hc_lang_login['Valid2'];?>\n'
				}//end if
				if(document.frm.pass1.value != '' && document.frm.pass2.value != ''){
					if(document.frm.pass1.value != document.frm.pass2.value){
						dirty = 1;
						warn = warn + '<?php echo $hc_lang_login['Valid3'];?>\n'
					}//end if
				}//end if
				if(dirty > 0){
					alert(warn + '\n<?php echo $hc_lang_login['Valid4'];?>');
					return false;
				} else {
					return true;
				}//end if
			}//chkFrm()
		//-->
		</script>
<?php
		echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/ResetPassword.php" onsubmit="return chkFrm();">';
		echo '<div class="loginform">';
		echo $hc_lang_login['Instruct2'];
		echo '<br /><br /><label for="pass1">' . $hc_lang_login['Passwrd1'] . '</label><br />';
		echo '<input name="pass1" id="pass1" type="password" value="" maxlength="15" class="loginInput" />';
		echo '<br /><br /><label for="pass2">' . $hc_lang_login['Passwrd2'] . '</label><br />';
		echo '<input name="pass2" id="pass2" type="password" value="" maxlength="15" class="loginInput" />';
		echo '<br /><br /><input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button3'] . '" class="loginButton" />';
		echo '<br /><br /><div align="right"><a href="' . CalAdminRoot . '/" class="main">' . $hc_lang_login['Link2'] . '</a></div>';
		echo '</div>';
		echo '<input type="hidden" name="a" value="' . mysql_result($result,0,0) . '" />';
		echo '<input type="hidden" name="k" value="' . k . '" />';
		echo '</form>';
	} else {
		echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/LoginAction.php">';
		echo '<div class="loginform">';
		echo '<label for="username">' . $hc_lang_login['Username'] . '</label><br />';
		echo '<input name="username" id="username" type="text" value="" maxlength="100" class="loginInput" />';
		echo '<br /><br /><label for="password">' . $hc_lang_login['Password'] . '</label><br />';
		echo '<input name="password" id="password" type="password" maxlength="100" value="" class="loginInput" />';
		echo '<br /><br /><label for="langType">' . $hc_lang_login['Language'] . '</label><br />';
	
		$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
		$langDir = realpath($pathparts['dirname'] . "/" . $hc_langPath);
		if(file_exists($langDir)){
			echo '<select name="langType" id="langType" class="loginInput">';
			$dir = dir($langDir);
			$x = 0;
			while(($file = $dir->read()) != false){
				if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
					$langOption = $file;
					
					echo ($langOption == $_SESSION[$hc_cfg00 . 'LangSet']) ?
						'<option selected="selected" value="' . $langOption . '">' . ucwords($langOption) . '</option>':
						'<option value="' . $langOption . '">' . ucwords($langOption) . '</option>';
					++$x;
				}//end if
			}//end while
			if($x == 0){
				echo '<option value="">' . $hc_lang_login['NoLang'] . '</option>';
			}//end if
			echo '</select>';
		} else {
			echo '<b>' . $hc_lang_settings['NoLangDir'] . '</b>';
		}//end if
		
		echo '<br /><br /><input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button1'] . '" class="loginButton" />';
		echo '<br /><br /><div align="right"><a href="' . CalAdminRoot . '/index.php?lp=1" class="main">' . $hc_lang_login['Link1'] . '</a></div>';
		echo '</div>';
		echo '<input name="com" id="com" type="hidden" value="';
		echo (!isset($_GET['com'])) ? 'home' : cIn($_GET['com']);
		echo '" /></form>';
	}//end if
	
	echo '</div>';?>