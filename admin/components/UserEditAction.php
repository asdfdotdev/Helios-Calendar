<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$target = AdminRoot.'/index.php?com=user';
	
	if(!isset($_GET['dID']) && !isset($_GET['bID'])){
		$uID = (isset($_POST['uID']) && is_numeric($_POST['uID'])) ? cIn(strip_tags($_POST['uID'])) : 0;
		$email = (isset($_POST['email'])) ? cIn(strip_tags($_POST['email'])) : '';
		$level = (isset($_POST['level']) && is_numeric($_POST['level'])) ? cIn(strip_tags($_POST['level'])) : 0;
		$banned = (isset($_POST['banned']) && is_numeric($_POST['banned'])) ? cIn(strip_tags($_POST['banned'])) : 0;
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
					Categories = '" . $cats . "'
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