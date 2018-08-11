<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('include.php');
	
	unset($_SESSION[$hc_cfg00 . 'hc_cap']);
	
	$width = 300;
	$height = 75;
	
	$image = imagecreatetruecolor($width, $height);
	$imageLetters = $imgSrc = imagecreatefrompng('capsource/LetterMap.png');
	
	srand((double)microtime()*1000000);
	$useBG = "capsource/background" . rand(1,9) . ".png";
	$bgSrc = imagecreatefrompng($useBG);
	imagecopy($image,$bgSrc,0,0,0,0,$width,$height);
	
	$x = 0;
	$capString = "";
	while($x < 6 ){
		$letter = (rand(1,1000) % 35) + 1;
		$imgRow = 75 * ($letter - 1);
		$imgCol = 50 * (rand(0,100) % 6);
		imagecopy($image, $imageLetters, 50 * $x, 0, $imgCol, $imgRow, 50, 75);
		
		switch($letter){
			case 1: $capString .= "a"; break;
			case 2: $capString .= "b"; break;
			case 3: $capString .= "c"; break;
			case 4: $capString .= "d"; break;
			case 5: $capString .= "e"; break;
			case 6: $capString .= "f"; break;
			case 7: $capString .= "g"; break;
			case 8: $capString .= "h"; break;
			case 9: $capString .= "i"; break;
			case 10: $capString .= "j"; break;
			case 11: $capString .= "k"; break;
			case 12: $capString .= "l"; break;
			case 13: $capString .= "m"; break;
			case 14: $capString .= "n"; break;
			case 15: $capString .= "o"; break;
			case 16: $capString .= "p"; break;
			case 17: $capString .= "q"; break;
			case 18: $capString .= "r"; break;
			case 19: $capString .= "s"; break;
			case 20: $capString .= "t"; break;
			case 21: $capString .= "u"; break;
			case 22: $capString .= "v"; break;
			case 23: $capString .= "w"; break;
			case 24: $capString .= "x"; break;
			case 25: $capString .= "y"; break;
			case 26: $capString .= "z"; break;
			case 27: $capString .= "1"; break;
			case 28: $capString .= "2"; break;
			case 29: $capString .= "3"; break;
			case 30: $capString .= "4"; break;
			case 31: $capString .= "5"; break;
			case 32: $capString .= "6"; break;
			case 33: $capString .= "7"; break;
			case 34: $capString .= "8"; break;
			case 35: $capString .= "9"; break;
		}//end switch

		++$x;
	}//end while

	$_SESSION[$hc_cfg00 . 'hc_cap'] = md5($capString);
		
	header('Content-type: image/png');
	imagepng($image);
	
	imagedestroy($image);
	imagedestroy($bgSrc);?>