<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
/*
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying or in anyway altering source code contained in this file is 	|
	|	not permitted and violates the Helios Calendar Software License Agreement	|
	|	DO NOT edit or reverse engineer any source code or files with this notice	|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
*/
	define('isHC',true);
	define('HCPATH', realpath('../'));
	define('HCSETUP', dirname(__FILE__));
	if(!file_exists(HCPATH.'/inc/config.php')){
		echo 'Config file missing. Cannot continue.';
		exit(-1);}
	require(HCSETUP.'/includes/functions.php');
	error_reporting(-1);
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="ISO-8859-1" />
	<meta name="robots" content="noindex, nofollow" />
	<meta name="GOOGLEBOT" content="noindex, nofollow" />
	<meta http-equiv="author" content="Refresh Web Development LLC" />
	<meta http-equiv="copyright" content="2004-<?php echo date("Y");?> All Rights Reserved" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="description" content="Helios Setup" />
	<meta name="MSSmartTagsPreventParsing" content="yes" />
	<link rel="icon" href="../images/favicon.png" type="image/png" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<!--[if IE 8]>
		<script src="js/ie8.js"></script>
	<![endif]-->
	<title>Helios Calendar <?php echo $curVersion;?> Setup</title>
<body>
	<section>
		<article>
			<div id="progress">
				<?php progress();?>
				
			</div>
		<?php
			get_step();
		?>
		
		</article>
		<aside>
			<img src="../admin/img/logo.png" width="235" alt="" border="0" />

			<h2>Support Links</h2>
			<ul>
				<li><a href="http://www.refreshmy.com/documentation/?title=Helios:_Setup" target="_blank">Setup Instructions</a></li>
				<li><a href="http://www.refreshmy.com/documentation/?title=Helios" target="_blank">Helios Calendar Documentation</a></li>
				<li><a href="https://www.refreshmy.com/members/" target="_blank">Refresh Members Site</a></li>
				<li><a href="http://www.refreshmy.com/forum/" target="_blank">Refresh Community Forum</a></li>
			</ul>
<?php		if((isset($_SESSION['code_valid']) && $_SESSION['code_valid'] == true)){?>
			<h2>Server Profile</h2>
			<ul>
				<li><span>PHP</span><?php echo phpversion();?></li>
				<li><span>MySQL</span><?php echo (isset($_SESSION['mysql_version'])) ? $_SESSION['mysql_version'] : 'Checking...';?></li>
				<li><span>OS</span><?php echo PHP_OS;?></li>
				<li><span>Web Server</span><?PHP echo strip_tags($_SERVER['SERVER_SOFTWARE']);?></li>
			</ul>
<?php		}?>
		</aside>
		<footer>
			<a href="http://www.helioscalendar.com" target="_blank">Helios Calendar <?php echo $curVersion;?></a> Copyright &copy; 2004-<?php echo date("Y");?> <a href="http://www.refreshmy.com" target="_blank">Refresh Web Development</a>
		</footer>
	</section>
</body>
</html>