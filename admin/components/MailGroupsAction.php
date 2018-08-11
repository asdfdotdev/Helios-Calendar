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
	
	if(!isset($_GET['dID'])){
		$gID = (isset($_POST['gID']) && is_numeric($_POST['gID'])) ? cIn($_POST['gID']) : 0;
		$name = isset($_POST['name']) ? cIn($_POST['name']) : '';
		$description = isset($_POST['description']) ? cIn(cleanQuotes($_POST['description']),1) : '';
		$status = isset($_POST['status']) ? cIn($_POST['status']) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "mailgroups WHERE PkID = '" . $gID . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "mailgroups
					SET Name = '" . $name . "',
						Description = '" . $description . "',
						IsPublic = '" . $status . "'
					WHERE PkID = '" . $gID . "'");
			header("Location: " . AdminRoot . "/index.php?com=subgrps&msg=2");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "mailgroups(Name,Description,IsPublic,IsActive)
					Values(	'" . $name . "',
							'" . $description . "',
							'" . $status . "',1)");
			header("Location: " . AdminRoot . "/index.php?com=subgrps&msg=3");
		}
	} else {
		if($_GET['dID'] != 1){
			doQuery("UPDATE " . HC_TblPrefix . "mailgroups SET IsActive = 0 WHERE PkiD = '" . cIn(strip_tags($_GET['dID'])) . "'");
		}
		header("Location: " . AdminRoot . "/index.php?com=subgrps&msg=1");
	}
?>