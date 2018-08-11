<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}?>
<!doctype html>
<html lang="<?php echo get_lang_config('HTMLTemplate');?>">
<head>
	<meta charset="<?php echo get_lang_config('CharSet');?>">
	<meta http-equiv="preview-refresh" content="3600" />
	<link rel="stylesheet" type="text/css" href="<?php echo cal_url();?>/themes/core.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo theme_dir();?>/css/style.css" />
	<link rel="shortcut icon" href="<?php echo cal_url();?>/favicon.ico">
	<?php hc_header();?>
	