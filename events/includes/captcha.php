<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

	include('include.php');
	$image = imagecreate(150, 30);
	
	$white    = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
	$gray    = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
	$darkgray = imagecolorallocate($image, 0x50, 0x50, 0x50);
	
	srand((double)microtime()*1000000);
	
	for ($i = 0; $i < 15; $i++) {
	  $x1 = rand(0,150);
	  $y1 = rand(0,30);
	  $x2 = rand(0,150);
	  $y2 = rand(0,30);
	  imageline($image, $x1, $y1, $x2, $y2 , $gray);  
	}//end for
	
	$imgText = makeRandomPassword(8);
	$_SESSION['captchas'] = $imgText;
	
	$fInt = rand(4,5);
	$x = rand(0,50);
	$y = rand(0,14); 
	imagestring($image, $fInt, $x, $y, $imgText , $darkgray); 
	
	header('Content-type: image/png');
	imagepng($image);
	imagedestroy($image);	?>