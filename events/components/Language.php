<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	$langDir = $pathparts['dirname'] . "/includes/lang/";
		
	if(file_exists(realpath($langDir))){
		$dir = dir(realpath($langDir));
		$x = 0;
		while(($file = $dir->read()) != false){
			if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
				$langOption = $file;
				if($x > 0){
					echo $x % 5 == 0 ? "<br /><br />" : "&nbsp;&nbsp;";
				}//end if
				echo "<a href=\"" . CalRoot . "/switchLang.php?l=" . $langOption . "\" title=\"" . $langOption . "\" rel=\"nofollow\"><img src=\"" . CalRoot . "/includes/lang/" . $langOption . "/icon.png\" width=\"16\" height=\"11\" alt=\"" . $langOption . "\" style=\"vertical-align:middle;\" /></a>";
				$x++;
			}//end if
		}//end while
	} else {
		echo "lang folder not found";
	}//end if	?>