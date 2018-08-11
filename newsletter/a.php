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
	error_reporting(0);
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	header('Content-type: image/png');

	if(isset($_GET['a']))
		doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Views = Views + 1 WHERE MD5(PkID) = '" . cIn(strip_tags($_GET['a'])) . "'");

	$image = imagecreatetruecolor(1,1);
	imagecolortransparent($image,imagecolorallocate($image,0,0,0));
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);
?>