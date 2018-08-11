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
	$pathparts = pathinfo($_SERVER['SCRIPT_FILENAME']);
	$langDir = $pathparts['dirname'] . "/includes/lang/";
	
	if(!file_exists(realpath('cache/lang.php'))){
		if(file_exists(realpath($langDir))){
			ob_start();
			$fp = fopen('cache/lang.php', 'w');
			fwrite($fp, "<?php\n//\tHelios Language Pack Links Cache - Delete this file when upgrading.?>\n");
			$langOpt = array();
			$dir = dir(realpath($langDir));
			$x = 0;
			while(($file = $dir->read()) != false){
				if(is_dir($dir->path.'/'.$file) && strpos($file,'.') === false && strpos($file,'_') === false){
					$langOpt[] = $file;
				}//end if
			}//end while

			sort($langOpt);
			foreach($langOpt as $val){
				if($x > 0){
					echo $x % 8 == 0 ? '<br /><br />' : '&nbsp;&nbsp;';
				}//end if
				echo "\n" . '<a href="' . CalRoot . '/switchLang.php?l=' . $val . '" title="' . $val . '" rel="nofollow"><img src="' . CalRoot . '/includes/lang/' . $val . '/icon.png" width="16" height="11" alt="' . $val . '" style="vertical-align:middle;" /></a>';
				++$x;
			}//end foreach
			
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		} else {
			echo 'lang folder not found';
		}//end if
	}//end if
	
	if(file_exists(realpath('cache/lang.php'))){include('cache/lang.php');}?>