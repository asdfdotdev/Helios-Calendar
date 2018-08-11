<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	include_once(HCPATH.'/themes/mobile/functions/hacks.php');
?>
<!doctype html>
<html lang="<?php echo get_lang_config('HTMLTemplate');?>">
<head>
	<meta charset="<?php echo get_lang_config('MobileCharSet');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/core.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/style.css" />
	<link rel="shortcut icon" href="<?php echo cal_url();?>/favicon.ico">
	<?php hc_header();?>

	<meta name=viewport content="width=device-width, initial-scale=1.0, minimum-scale=1.0 maximum-scale=1.0">
	<link rel="apple-touch-icon-precomposed" href="<?php echo mobile_icon();?>" type="image/png" />
	<script type="text/javascript">
	//<!--
	setTimeout(function () {
	  window.scrollTo(0, 1);
	}, 1000);
	//-->
	</script>