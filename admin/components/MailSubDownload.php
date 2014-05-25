<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = (isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : '';
	if(!check_form_token($token))
		go_home();
	
	include(HCLANG.'/admin/newsletter.php');
	
	header('Content-type: application/csv');
	header('Content-Disposition: inline; filename="'.CalName.' Newsletter Subscribers '.SYSDATE.'.csv"');
	echo "SUBID,FirstName,LastName,Email,Postal Code,Registered Date,Registered Time,Registered From,Birth Year,Gender";
	echo "\n";
	
	$result = doQuery("SELECT PkID, FirstName, LastName, Email, Zip, RegisteredAt, RegisterIP, BirthYear, Gender
					FROM " . HC_TblPrefix . "subscribers
					WHERE IsConfirm = 1
					ORDER BY LastName, FirstName");
	if(hasRows($result)){
		while($row = mysql_fetch_row($result)){
			$gender = ($row[8] == 1) ? $hc_lang_news['GenderF'] : $hc_lang_news['GenderM'];
			$reg = explode(' ',$row[5]);
			echo $row[0].",".$row[1].",".$row[2].",".$row[3].",".$row[4].",".$reg[0].",".$reg[1].",".$row[6].",".$row[7].",".$gender."\n";
		}
	}
?>
