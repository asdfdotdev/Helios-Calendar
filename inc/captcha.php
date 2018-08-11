<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(isset($_GET['admin']) && $_GET['admin'] == 1){
		define('hcAdmin',true);
		include('../admin/loader.php');
	} else {
		define('isHC',true);
		define('isAction',true);
		include('../loader.php');
	}
	action_headers();
	
	if(isset($_SESSION['hc_cap']))
		unset($_SESSION['hc_cap']);
	
	$width = 300;
	$height = 75;
	
	$image = imagecreatetruecolor($width, $height);
	$imageLetters = imagecreatefrompng('capsource/LetterMap.png');
	
	$bgSrc = imagecreatefrompng("capsource/background" . rand(1,10) . ".png");
	imagecopy($image,$bgSrc,0,0,0,0,$width,$height);
	
	$next = ($hc_lang_config['Direction'] == 0) ? 250 : 0;
	$x = 0;
	$capString = "";
	while($x < 6 ){
		$letter = (rand(1,1000) % 35) + 1;
		$imgRow = 75 * ($letter - 1);
		$imgCol = 50 * (rand(0,100) % 6);
		imagecopy($image, $imageLetters, $next, 0, $imgCol, $imgRow, 50, 75);
		
		$next = ($hc_lang_config['Direction'] == 0) ? $next - 50 : $next + 50;
		
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
		}
		++$x;
	}
	
	$_SESSION['hc_cap'] = md5($capString);
		
	header('Content-type: image/png');
	imagepng($image);
	
	imagedestroy($image);
	imagedestroy($bgSrc);
?>