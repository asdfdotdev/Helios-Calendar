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
	$isAction = 1;
	include('../includes/include.php');	
	include('overhead.php');

echo "<?xml version=\"1.0\"?>";	?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.2//EN"
	"http://www.openmobilealliance.org/tech/DTD/xhtml-mobile12.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/mobile.css" />
	<title><?php echo CalName . " " . $hc_lang_mobile['Mobile'];?></title>
</head>
<body>
	<div class="menu">
		<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['Browse'];?></a>
	</div>
	<form method="post" action="<?php echo MobileRoot;?>/results.php">
	<div class="content">
	<?php
		if(file_exists(realpath("../includes/lang/"))){
			$dir = dir(realpath("../includes/lang/"));
			$x = 0;
			while(($file = $dir->read()) != false){
				if(is_dir($dir->path.'/'.$file) && $file != "." && $file != ".."){
					$langOption = $file;
					echo "<a href=\"" . MobileRoot . "/langAction.php?l=" . $langOption . "\" title=\"" . $langOption . "\">" . ucwords($langOption) . "</a><br />";
					$x++;
				}//end if
			}//end while
		} else {
			echo "no folder";
		}//end if
	?>
	</div>
	</form>
	<div class="menu">
		<a href="<?php echo MobileRoot;?>/browse.php" class="mnu"><?php echo $hc_lang_mobile['Browse'];?></a>
	</div>
	<div class="footer">
		<div>helios&nbsp;<img src="<?php echo CalRoot;?>/images/favicon.png" width="16" height="16" alt="" style="vertical-align:middle;" />&nbsp;calendar</div>
		<a accesskey="1" href="<?php echo MobileRoot;?>/browse.php"></a>
		<a accesskey="2" href="<?php echo MobileRoot;?>/search.php"></a>
		<a accesskey="3" href="<?php echo MobileRoot;?>/lang.php"></a>
		<a accesskey="0" href="<?php echo MobileRoot;?>/help.php"></a>
	</div>
</body>
</html>