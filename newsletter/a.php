<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
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