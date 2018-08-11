<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	if(!check_form_token($token))
		go_home();
	
	$target = AdminRoot.'/index.php?com=user';
	
	if(!isset($_GET['dID']) && !isset($_GET['bID'])){
		$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn(strip_tags($_POST['uID'])) : 0;
		$email = (isset($_POST['email'])) ? cIn(strip_tags($_POST['email'])) : '';
		$level = (isset($_POST['level']) && is_numeric($_POST['level'])) ? cIn(strip_tags($_POST['level'])) : 0;
		$banned = (isset($_POST['banned']) && is_numeric($_POST['banned'])) ? cIn(strip_tags($_POST['banned'])) : 0;
		
		$api = (isset($_POST['api']) && is_numeric($_POST['api'])) ? cIn(strip_tags($_POST['api'])) : 0;
		
		
		$location = (isset($_POST['location'])) ? cIn(strip_tags($_POST['location'])) : '';
		$birthdate = isset($_POST['birthdate']) ? dateToMySQL(cIn($_POST['birthdate']), $hc_cfg[24]) : '';
		$catID = (isset($_POST['catID'])) ? array_filter($_POST['catID'],'is_numeric') : '';
		$cats = (isset($catID[0])) ? implode(',',$catID) : '';
				
		if($banned == 1){
			$level = 0;
			doQuery("UPDATE " . HC_TblPrefix . "events SET OwnerID = 0 WHERE OwnerID = '" . $uID . "'");
		}
		
		doQuery("UPDATE " . HC_TblPrefix . "users SET
					Email = '" . $email . "',
					Level = '" . $level . "',
					IsBanned = '" . $banned . "',
					Location = '" . $location . "',
					Birthdate = ".(($birthdate != '') ? "'".$birthdate."'":"NULL").",
					Categories = '" . $cats . "',
					APIAccess = '" . $api . "'
				WHERE PkID = '" . $uID . "'");
		
		
		$target = AdminRoot.'/index.php?com=useredit&uID='.$uID.'&msg=1';
	} else {
		if(isset($_GET['dID'])){
			$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(strip_tags($_GET['dID'])) : 0;
			$b = (isset($_GET['b']) && is_numeric($_GET['b']) && $_GET['b'] <= 1) ? cIn(strip_tags($_GET['b'])) : 0;
			
			doQuery("DELETE FROM " . HC_TblPrefix . "users WHERE PkID = '" . $dID . "'");
			doQuery("UPDATE " . HC_TblPrefix . "events SET OwnerID = 0 WHERE OwnerID = '" . $dID . "'");
			
			$target = AdminRoot.'/index.php?com=user&msg=1'.(($b == 1) ? '&b=1':'');
		} elseif(isset($_GET['bID'])){
			$bID = (isset($_GET['bID']) && is_numeric($_GET['bID'])) ? cIn(strip_tags($_GET['bID'])) : 0;
			$b = (isset($_GET['b']) && is_numeric($_GET['b']) && $_GET['b'] <= 1) ? cIn(strip_tags($_GET['b'])) : 0;
			
			doQuery("UPDATE " . HC_TblPrefix . "users SET IsBanned = '" . $b . "' AND Level = 0 WHERE PkID = '" . $bID . "'");
			doQuery("UPDATE " . HC_TblPrefix . "events SET OwnerID = 0 WHERE OwnerID = '" . $bID . "'");
			
			$msg = ($b == 1) ? 2 : 3;
			$target = AdminRoot.'/index.php?com=user&msg='.$msg.(($b == 0) ? '&b=1':'');
		}
	}
	
	header('Location: ' . $target);
?>