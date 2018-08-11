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
	
	include(HCLANG.'/admin/newsletter.php');
	
	header('Content-type: application/csv');
	header('Content-Disposition: inline; filename="'.CalName.' Newsletter Subscribers '.SYSDATE.'.csv"');
	echo "ID,FirstName,LastName,Email,Postal Code,Registered Date,Registered Time,Registered From,Birth Year,Gender";
	echo "\n";
	
	$result = doQuery("SELECT PkID, FirstName, LastName, Email, Zip, RegisteredAt, RegisterIP, BirthYear, Gender
					FROM hc_subscribers
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
