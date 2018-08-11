<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));
	include('../includes/include.php');

	header('Content-type: image/png');

	if(isset($_GET['a'])){
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Views = Views + 1 WHERE MD5(PkID) = '" . cIn(strip_tags($_GET['a'])) . "'");
	}//end if

	$image = imagecreatetruecolor(1,1);
	imagecolortransparent($image,imagecolorallocate($image,0,0,0));
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);
?>