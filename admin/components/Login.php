<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
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
			feedback(2,"Invalid Login.");
			break;
		
		case "3" :
			feedback(1,"Password Reset Request Sent. Please Check Your Email For Further Instructions.");
			break;
		
		case "4" :
			feedback(1,"Password Reset Successfully. You Can Now Login Using Your New Password.");
			break;
		
		case "5" :
			feedback(2,"Unable to Reset Password. Passwords Did Not Match.");
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
		A password change request will be emailed to you. 
		To change your password follow the instructions in the email.
		<br /><br />
		<label for="email">Email Address:</label><br />
		<input name="email" id="email" type="text" value="" maxlength="100" class="loginInput" />
		<br /><br />
		<input type="submit" name="submit" id="submit" value="Submit Request" class="loginButton" />
		<br /><br />
		<div align="right"><a href="<?php echo CalAdminRoot;?>/" class="main">&laquo; Return to Login</a></div>
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
						warn = warn + 'Please Enter a New Password\n'
					}//end if
					if(document.frm.pass2.value == ''){
						dirty = 1;
						warn = warn + 'Repeat Password For Verification\n'
					}//end if
					if(document.frm.pass1.value != '' && document.frm.pass2.value != ''){
						if(document.frm.pass1.value != document.frm.pass2.value){
							dirty = 1;
							warn = warn + 'Passwords Do Not Match\n'
						}//end if
					}//end if
					if(dirty > 0){
						alert(warn + '\nPlease make the required changes and try again.');
						return false;
					} else {
						return true;
					}//end if
				}//end if
			//-->
			</script>
			<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot;?>/components/ResetPassword.php" onsubmit="return chkFrm();">
			<div class="loginform">
			Create a new password using the form below.
			<br /><br />
			<label for="pass1">New Password:</label><br />
			<input name="pass1" id="pass1" type="password" value="" maxlength="15" class="loginInput" />
			<br /><br />
			<label for="pass2">Repeat Password:</label><br />
			<input name="pass2" id="pass2" type="password" value="" maxlength="15" class="loginInput" />
			<br /><br />
			<input type="submit" name="submit" id="submit" value="Change Password" class="loginButton" />
			<br /><br />
			<div align="right"><a href="<?php echo CalAdminRoot;?>/" class="main">&laquo; Return to Login</a></div>
			</div>
			<input type="hidden" name="aID" value="<?php echo mysql_result($result,0,0);?>" />
			</form>
<?php 	} else {	?>
		<form name="frm" id="frm" method="post" action="<?php echo CalAdminRoot;?>/components/LoginAction.php">
		<div class="loginform">
		<label for="username">Username:</label><br />
		<input name="username" id="username" type="text" value="<?php if(isset($_GET['username'])){echo $_GET['username'];}//end if?>" maxlength="100" class="loginInput" />
		<br /><br />
		<label for="password">Password:</label><br />
		<input name="password" id="password" type="password" maxlength="15" value="" class="loginInput" />
		<br /><br />
		<input type="submit" name="submit" id="submit" value="Login" class="loginButton" />
		<br /><br />
		<div align="right"><a href="<?php echo CalAdminRoot;?>/index.php?lp=1" class="main">Lost Your Password?</a></div>
		</div>
		<input name="com" id="com" type="hidden" value="<?php if(!isset($_GET['com'])){echo "home";}else{echo $_GET['com'];}//end if?>" />
		</form>
<?php
	}//end if	?>
	</div>