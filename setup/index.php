<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
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
			
<?php		if((isset($_SESSION['code_valid']) && $_SESSION['code_valid'] == true)){?>
			<h2>Server Profile <span></h2>
			<ul>
				<li><span>PHP</span><?php echo phpversion();?></li>
				<li><span>MySQL</span><?php echo (isset($_SESSION['mysql_version'])) ? $_SESSION['mysql_version'] : 'Checking...';?></li>
				<li><span>OS</span><?php echo PHP_OS;?></li>
				<li><span>Web Server</span><?PHP echo strip_tags($_SERVER['SERVER_SOFTWARE']);?></li>
			</ul>
<?php		}?>
		</aside>
		<footer>
			Helios Calendar <?php echo $curVersion;?> Setup
		</footer>
	</section>
</body>
</html>