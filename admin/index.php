<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin', true);
	include('loader.php');
	
	$hc_Side[] = array(CalRoot.'/','view_public.png',$hc_lang_core['Link'],1);
	
	include(HCLANG.'/admin/core.php');
	include(HCLANG.'/admin/menu.php');
	
	echo '<!doctype html>
<html lang="'.$hc_lang_config['HTMLTemplate'].'">
<head>
	<meta charset="'.$hc_lang_config['CharSet'].'">
	<meta name="robots" content="noindex, nofollow">
	
	<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin.css">
	<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" href="'.AdminRoot.'/css/admin_ie8.css">
		<script src="'.AdminRoot.'/inc/javascript/ie8.js"></script>
	<![endif]-->
	
	<link rel="shortcut icon" href="'.CalRoot.'/admin/favicon'.((has_pending() > 0) ? '_e':'').'.ico">
	<title>'.CalName.', powered by Helios Calendar</title>
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
	if(top.location != location)
		top.location.href = document.location.href;
';
	
	if(!isset($_SESSION['AdminLoggedIn']))
		include_once(HCADMIN.'/inc/javascript/login.php');
	else
		include_once(HCADMIN.'/inc/javascript/core.php');
	
	echo '
	//-->
	</script>
</head>
'.((isset($_SESSION['AdminLoggedIn'])) ? '<body>' : '<body class="login">');
	
	if(isset($_SESSION['AdminLoggedIn'])){
		echo '
	<div id="menu">
	<nav>';
		include(HCADMIN.'/components/AdminMenu.php');

		echo '
	</nav>
	</div>
	<div id="content">
		<section>
		<div id="date">' . strftime($hc_cfg[14]) . '</div>';
		include(HCADMIN.'/inc/core.php');
		
		echo '
		</section>
		<aside>
			<div id="side">
			<img src="' . AdminRoot . '/img/logo.png" width="235" height="56" alt="" id="logo" /><br /><br />';

			include(HCADMIN.'/components/Side.php');
			echo '
			</div>
		</aside>
	</div>';
	} else {
		include(HCADMIN.'/components/Login.php');}
		
	echo '
	<div id="footer">
		'.CalName.'<br /><a href="http://helioscalendar.org" target="_blank">Powered by Helios Calendar '.HCVersion.'</a>
	</div>
</body>
</html>';
?>