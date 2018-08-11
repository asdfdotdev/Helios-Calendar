<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/login.php');
	
	$lp = 0;
	if(isset($_GET['lp']) && is_numeric($_GET['lp'])){
		$lp = $_GET['lp'];
	}//end if
	$k = '';
	if($lp == 2 && isset($_GET['k'])){
		$k = $_GET['k'];
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "admin WHERE PCKey = '" . cIn($k) . "' AND PCKey IS NOT NULL");
	}//end if	?>
	<br /><br />
<?php
	if (isset($_GET['msg'])){	?>
	<div style="width:330px;margin:auto;">
<?php
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
	}//end switch	?>
	</div>
<?php
	}//end if	?>
	<br />
	<div id="login">
<?php
	if($lp == 1) {	?>
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot;?>/components/LostPasswordAction.php" onsubmit="if(document.frm.email.value == ''){alert('Please enter your email address and try again.');document.frm.elements[0].focus();return false;}">
		<div class="loginform">
		<?php echo $hc_lang_login['Instruct1'];?>
		<br /><br />
		<label for="email"><?php echo $hc_lang_login['Email'];?></label><br />
		<input name="email" id="email" type="text" value="" maxlength="100" class="loginInput" />
		<br /><br />
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_login['Button2'];?>" class="loginButton" />
		<br /><br />
		<div align="right"><a href="<?php echo CalAdminRoot;?>/" class="main"><?php echo $hc_lang_login['Link2'];?></a></div>
		</div>
		</form>
<?php 	} elseif($lp == 2 && $k != '' && hasRows($result)) {	?>
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
				}//end if
			//-->
			</script>
			<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot;?>/components/ResetPassword.php" onsubmit="return chkFrm();">
			<div class="loginform">
			<?php echo $hc_lang_login['Instruct2'];?>
			<br /><br />
			<label for="pass1"><?php echo $hc_lang_login['Passwrd1'];?></label><br />
			<input name="pass1" id="pass1" type="password" value="" maxlength="15" class="loginInput" />
			<br /><br />
			<label for="pass2"><?php echo $hc_lang_login['Passwrd2'];?></label><br />
			<input name="pass2" id="pass2" type="password" value="" maxlength="15" class="loginInput" />
			<br /><br />
			<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_login['Button3'];?>" class="loginButton" />
			<br /><br />
			<div align="right"><a href="<?php echo CalAdminRoot;?>/" class="main"><?php echo $hc_lang_login['Link2'];?></a></div>
			</div>
			<input type="hidden" name="aID" value="<?php echo mysql_result($result,0,0);?>" />
			</form>
<?php 	} else {	?>
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot;?>/components/LoginAction.php">
		<div class="loginform">
		<label for="username"><?php echo $hc_lang_login['Username'];?></label><br />
		<input name="username" id="username" type="text" value="" maxlength="100" class="loginInput" />
		<br /><br />
		<label for="password"><?php echo $hc_lang_login['Password'];?></label><br />
		<input name="password" id="password" type="password" maxlength="15" value="" class="loginInput" />
		<br /><br />
		<label for="langType"><?php echo $hc_lang_login['Language'];?></label><br />
	<?php
		$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
		$langDir = realpath($pathparts['dirname'] . "/" . $hc_langPath);
		if(file_exists($langDir)){
			echo "<select name=\"langType\" id=\"langType\" class=\"loginInput\">";
			$dir = dir($langDir);
			$x = 0;
			while(($file = $dir->read()) != false){
				if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
					$langOption = $file;
					if($langOption == $_SESSION['LangSet']){
						echo "<option selected=\"selected\" value=\"" . $langOption . "\">" . ucwords($langOption) . "</option>";
					} else {
						echo "<option value=\"" . $langOption . "\">" . ucwords($langOption) . "</option>";
					}//end if
					$x++;
				}//end if
			}//end while
			if($x == 0){
				echo "<option value=\"\">" . $hc_lang_login['NoLang'] . "</option>";
			}//end if
			echo "</select>";
		} else {
			echo "<b>" . $hc_lang_settings['NoLangDir'] . "</b>";
		}//end if	?>
		
		<br /><br />
		<input type="submit" name="submit" id="submit" value="<?php echo $hc_lang_login['Button1'];?>" class="loginButton" />
		<br /><br />
		<div align="right"><a href="<?php echo CalAdminRoot;?>/index.php?lp=1" class="main"><?php echo $hc_lang_login['Link1'];?></a></div>
		</div>
		<input name="com" id="com" type="hidden" value="<?php if(!isset($_GET['com'])){echo "home";}else{echo $_GET['com'];}//end if?>" />
		</form>
<?php
	}//end if	?>
	</div>