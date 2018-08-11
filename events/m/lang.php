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
	include('../includes/include.php');
	
	include('includes/header.php');?>
</head>
<body>
<div id="header"><?php echo $hc_lang_mobile['Language'];?></div>
	
<div id="content">
	<ul>
<?php
	if(file_exists(realpath("../includes/lang/"))){
		$dir = dir(realpath("../includes/lang/"));
		$x = 0;
		while(($file = $dir->read()) != false){
			if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
				$langOption = $file;
				echo '<li><a href="' . MobileRoot . '/langAction.php?l=' . $langOption . '" title="' . $langOption . '\">' . ucwords($langOption) . '</a></li>';
				$x++;
			}//end if
		}//end while
	} else {
		echo "no folder";
	}//end if
?>
	</ul>
</div>
<?php
	include('includes/footer.php');?>
</body>
</html>