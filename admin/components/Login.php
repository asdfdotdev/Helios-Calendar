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
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/login.php');
	
	$lp = (isset($_GET['lp']) && is_numeric($_GET['lp'])) ? cIn($_GET['lp']) : 0;
	$k = (isset($_GET['k'])) ? cIn(strip_tags($_GET['k'])) : '';

	if($lp == 2 && $k != ''){
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "admin WHERE PCKey = '" . $k . "' AND PCKey IS NOT NULL");
	}//end if
	
	echo '<br /><br />';
	if(isset($_GET['lmsg'])){
		echo '<div style="width:330px;margin:auto;">';
		switch ($_GET['lmsg']){
			case "1" :
				feedback(2,$hc_lang_login['Feedback1']);
				break;
			case "2" :
				feedback(2,$hc_lang_login['Feedback2']);
				break;
			case "3" :
				feedback(1,$hc_lang_login['Feedback3']);
				break;
			case "4" :
				feedback(1,$hc_lang_login['Feedback4']);
				break;
			case "5" :
				feedback(2,$hc_lang_login['Feedback5']);
				break;
		}//end switch
		echo '</div>';
	}//end if
	echo '<br /><div id="login">';
	
	if($lp == 1) {?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
			function chkFrm(){
				if(document.getElementById('email').value == ''){
					alert('<?php echo $hc_lang_login['Valid5']?>');
					document.getElementById('email').focus();
					return false;
				} else {
					document.getElementById('submit').disabled = true;
					document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
					return true;
				}//end if
			}//chkFrm()
		//-->
		</script>
<?php
		echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/LostPasswordAction.php" onsubmit="return chkFrm();">';
		echo '<div class="loginform">';
		echo $hc_lang_login['Instruct1'];
		echo '<br /><br />';
		echo '<label for="email">' . $hc_lang_login['Email'] . '</label><br />';
		echo '<input name="email" id="email" type="text" value="" maxlength="100" class="loginInput" />';
		echo '<br /><br />';
		echo '<input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button2'] . '" class="loginButton" />';
		echo '<div class="lostPwd"><a href="' . CalAdminRoot . '/" class="main">' . $hc_lang_login['Link2'] . '</a></div>';
		echo '</div></form>';
	} elseif($lp == 2 && $k != '' && hasRows($result)){?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
			function chkFrm(){
			var dirty = 0;
			warn = '';
				if(document.getElementById('pass1').value == ''){
					dirty = 1;
					warn = warn + '<?php echo $hc_lang_login['Valid1'];?>\n'
				}//end if
				if(document.getElementById('pass2').value == ''){
					dirty = 1;
					warn = warn + '<?php echo $hc_lang_login['Valid2'];?>\n'
				}//end if
				if(document.getElementById('pass1').value != '' && document.getElementById('pass2').value != ''){
					if(document.getElementById('pass1').value != document.getElementById('pass2').value){
						dirty = 1;
						warn = warn + '<?php echo $hc_lang_login['Valid3'];?>\n'
					}//end if
				}//end if
				if(dirty > 0){
					alert(warn + '\n<?php echo $hc_lang_login['Valid4'];?>');
					return false;
				} else {
					document.getElementById('submit').disabled = true;
					document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
					return true;
				}//end if
			}//chkFrm()
		//-->
		</script>
<?php	echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/ResetPassword.php" onsubmit="return chkFrm();">';
		echo '<div class="loginform">';
		echo $hc_lang_login['Instruct2'];
		echo '<br /><br /><label for="pass1">' . $hc_lang_login['Passwrd1'] . '</label><br />';
		echo '<input name="pass1" id="pass1" type="password" value="" maxlength="15" class="loginInput" />';
		echo '<br /><br /><label for="pass2">' . $hc_lang_login['Passwrd2'] . '</label><br />';
		echo '<input name="pass2" id="pass2" type="password" value="" maxlength="15" class="loginInput" />';
		echo '<br /><br /><input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button3'] . '" class="loginButton" />';
		echo '<div class="lostPwd"><a href="' . CalAdminRoot . '/" class="main">' . $hc_lang_login['Link2'] . '</a></div>';
		echo '</div>';
		echo '<input type="hidden" name="a" value="' . mysql_result($result,0,0) . '" />';
		echo '<input type="hidden" name="k" value="' . $k . '" />';
		echo '</form>';
	} else {?>
		<script language="JavaScript" type="text/JavaScript">
		//<!--
			function chkFrm(){
			var dirty = 0;
			warn = '';
				if(document.getElementById('username').value == ''){
					dirty = 1;
				}//end if
				if(document.getElementById('password').value == ''){
					dirty = 1;
				}//end if
				if(dirty > 0){
					return false;
				} else {
					document.getElementById('submit').disabled = true;
					document.getElementById('submit').value = '<?php echo $hc_lang_core['Sending'];?>';
					return true;
				}//end if
			}//chkFrm()
		//-->
		</script>
<?php	echo '<form name="frm" id="frm" method="post" action="' . CalAdminRoot . '/components/LoginAction.php" onsubmit="return chkFrm();">';
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
					
					echo ($langOption == $_SESSION['LangSet']) ?
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
		
		echo '<br /><input type="submit" name="submit" id="submit" value="' . $hc_lang_login['Button1'] . '" class="loginButton" />';
		echo '<div class="lostPwd"><a href="' . CalAdminRoot . '/index.php?lp=1" class="main">' . $hc_lang_login['Link1'] . '</a></div>';
		echo '</div>';
		echo '<input name="com" id="com" type="hidden" value="';
		echo (!isset($_GET['com'])) ? 'home' : cIn(strip_tags($_GET['com']));
		echo '" /></form>';
	}//end if
	echo '</div>';?>