<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	post_only();
	
	$target = CalRoot;
	$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn(strip_tags($_POST['uID'])) : 0;
	
	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "users WHERE PkID = '".$uID."'");
	
	if(!user_check_status() || !hasRows($result)){
		session_destroy();
	} else {
		$email = (isset($_POST['email'])) ? cIn(htmlentities(strip_tags($_POST['email']))) : '';
			$email = (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$email) == 1) ? $email : '';
		$birthdate = (isset($_POST['birthdate'])) ? cIn(dateToMySQL(htmlentities(strip_tags($_POST['birthdate'])),$hc_cfg[24])) : '';
			$birthdate = (strtotime($birthdate) <= strtotime('-13 years')) ? $birthdate : '';
		$location = (isset($_POST['user_loc'])) ? cIn(htmlentities(strip_tags($_POST['user_loc']))) : '';
		$api_key = (isset($_POST['regen_apik'])) ? ", APIKey = '" . cIn(md5(sha1($email.$birthdate.$location.(rand()*date("U"))))) . "' " : '';
		
		doQuery("UPDATE " . HC_TblPrefix . "users SET Email = '".$email."', Birthdate = '".$birthdate."', Location = '".$location."'$api_key WHERE PkID = '".$uID."'");

		if($email != '' && $birthdate != ''){
			if(isset($_SESSION['new_user']))
				unset($_SESSION['new_user']);
			if(isset($_SESSION['new_user_bday']))
				unset($_SESSION['new_user_bday']);
			if(isset($_SESSION['new_user_email']))
				unset($_SESSION['new_user_email']);
		}
		
		$target = CalRoot.'/index.php?com=acc&sec=edit&msg=1';
	}
	
	header('Location: ' . $target);
?>