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
		$cID = (isset($_POST['cID']) && is_numeric($_POST['cID'])) ? cIn($_POST['cID']) : 0;
		$name = (isset($_POST['catName'])) ? cIn($_POST['catName']) : '';
		$parentID = (isset($_POST['parentID']) && is_numeric($_POST['parentID'])) ? cIn($_POST['parentID']) : 0;
		
		$result = DoQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE PkID = ?", array($cID));
		if(hasRows($result)){
			if(hc_mysql_result($result,0,2) == 0 && $parentID != 0)
				DoQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = ? WHERE ParentID = ?", array(0, $cID));
			
			DoQuery("UPDATE " . HC_TblPrefix . "categories SET CategoryName = ?, ParentID = ? WHERE PkID = ?", array($name, $parentID, $cID));
			$msg = 1;
		} else {
			DoQuery("INSERT INTO " . HC_TblPrefix . "categories(CategoryName, ParentID, IsActive) VALUES(?, ?, 1)", array($name, $parentID));
			$result = DoQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "categories");
			$cID = hc_mysql_result($result,0,0);
			$msg = 2;
		}
		
		header('Location: ' . AdminRoot . '/index.php?com=categorymanage&msg=' . $msg . '&cID=' . $cID);
	} else {
		$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn($_GET['dID']) : 0;
		DoQuery("UPDATE " . HC_TblPrefix . "categories SET ParentID = 0 WHERE ParentID = ?", array($dID));
		DoQuery("UPDATE " . HC_TblPrefix . "categories SET IsActive = ? WHERE PkID = ?", array(0, $dID));
		DoQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE CategoryID = ?", array($dID));
		
		header('Location: ' . AdminRoot . '/index.php?com=categorymanage&msg=3');
	}
?>