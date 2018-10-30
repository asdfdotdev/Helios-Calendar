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
		$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? cIn(strip_tags($_POST['tID'])) : '';
		$name = isset($_POST['name']) ? cIn($_POST['name']) : '';
		$content = isset($_POST['ex_data']) ? cIn($_POST['ex_data']) : '';
		$header = isset($_POST['ex_header']) ? cIn($_POST['ex_header']) : '';
		$footer = isset($_POST['ex_footer']) ? cIn($_POST['ex_footer']) : '';
		$ext = isset($_POST['ext']) ? cIn($_POST['ext']) : '';
		$typeID = isset($_POST['typeID']) ? cIn($_POST['typeID']) : '';
		$groupBy = isset($_POST['group']) ? cIn($_POST['group']) : '';
		$sortBy = isset($_POST['sort']) ? cIn($_POST['sort']) : '';
		$dateFormat = isset($_POST['dateFormat']) ? cIn($_POST['dateFormat']) : '';
		$cleanup = isset($_POST['cleanup']) ? cIn($_POST['cleanup']) : '';
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE PkID = ? AND IsActive = 1", array($tID));
		if(hasRows($result)){
			$msgID = 1;
			doQuery("UPDATE " . HC_TblPrefix . "templates
						SET Name = ?,
							Content = ?,
							Header = ?,
							Footer = '?,
							Extension = ?,
							TypeID = ?,
							GroupBy = ?,
							SortBy = ?,
							CleanUp = ?,
							DateFormat = ?
						WHERE PkID = ?", array($name, $content, $header, $footer, $ext, $typeID, $groupBy, $sortBy, $cleanup, $dateFormat, $tID));
		} else {
			$msgID = 2;
			doQuery("INSERT INTO " . HC_TblPrefix . "templates(Name, Content, Header, Footer, Extension, TypeID, GroupBy, SortBy, DateFormat, CleanUp, IsActive)
					VALUES(?,?,?,?,?,?,?,?,?,?,1)", array($name,$content,$header,$footer,$ext,$typeID,$groupBy,$sortBy,$dateFormat,$cleanup));
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = (hasRows($result)) ? hc_mysql_result($result,0,0) : 0;
		}
	} else {
		$msgID = 3;
		$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn(strip_tags($_GET['dID'])) : 0;
		doQuery("UPDATE " . HC_TblPrefix . "templates SET IsActive = ? WHERE PkID = ?", array(0, $dID));
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocationName = 'Unknown', LocID = 0 WHERE LocID = ?", array($dID));
	}
	
	header('Location: ' . AdminRoot . '/index.php?com=exporttmplts&msg=' . $msgID);
?>