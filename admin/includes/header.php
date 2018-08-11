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
	ini_set("include_path",ini_get('include_path').";".dirname($_SERVER['SCRIPT_FILENAME']));

	if(!file_exists('includes/include.php')){
		exit();
	}//end if
	include('includes/include.php');

	if(file_exists('../events/setup')){
		echo '<link rel="stylesheet" type="text/css" href="' . CalAdminRoot . '/css/admin.css">';
		echo '<div style="width:60%;">';
		feedback(3,"You must delete the setup directory before accessing your Helios Calendar admin.");
		echo '</div>';
		exit();
	}//end if

	$hc_Side = array();
	$hc_timeInput = cOut($hc_cfg31) == 12 ? 12 : 23;
	
	define("HC_AdminMenu", "components/AdminMenu.php");
	define("HC_Core", "components/Core.php");
	define("HC_LogOut", "components/SignOut.php");
	define("HC_InstructionsSwitch", "components/InstructionSwitch.php");
	define("HC_Login", "components/Login.php");
	define("HC_Side", "components/Side.php");

	include($hc_langPath . $_SESSION['LangSet'] . '/admin/core.php');
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/menu.php');
	include($hc_langPath . $_SESSION['LangSet'] . '/config.php');
	setlocale(LC_TIME, $hc_lang_config['LocaleOptions']);?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
	echo "\n" . '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $hc_lang_config['HTMLTemplate'] . '" lang="' . $hc_lang_config['HTMLTemplate'] . '"';
	echo ($hc_lang_config['Direction'] == 1) ? '>' : ' dir="rtl">';?>
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> Refresh Web Development All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $hc_lang_config['CharSet'];?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/css/admin.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/dateSelect.css" />
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="<?php echo CalAdminRoot;?>/css/adminIE.css" /><![endif]-->
	<link rel="icon" href="<?php echo CalRoot;?>/images/favicon.png" type="image/png" />
	<title><?php echo CalName . ', powered by Helios Calendar';?></title>
	<script language="JavaScript" type="text/javascript">
	//<!--
		if(top.location != location)
			top.location.href = document.location.href;
	//-->
	</script>