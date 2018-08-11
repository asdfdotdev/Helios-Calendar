<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	include_once(HCPATH.'/themes/mobile/functions/browse_nav.php');
	event_browse_valid('my_event_browse_nav');
	
	get_header();?>

</head>
<body>
	<?php my_menu(1);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=search">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit">Submit</a></li>
			<li>
				<select name="hcBrowse" id="hcBrowse" onchange="window.location.href=this.value;">
					<option<?php echo ($_SESSION['BrowseType'] == 1 && !isset($_GET['m'])) ? ' selected="selected"' : '';?> value="<?php echo cal_url();?>/index.php?b=1">Monthly</option>
					<option<?php echo ($_SESSION['BrowseType'] == 0 && !isset($_GET['m'])) ? ' selected="selected"' : '';?> value="<?php echo cal_url();?>/index.php?b=0">Weekly</option>
					<option<?php echo ($_SESSION['BrowseType'] == 2 || isset($_GET['m'])) ? ' selected="selected"' : '';?> value="<?php echo cal_url();?>/index.php?b=2">Daily</option>
				</select>
			</li>
		</ul>
	</nav>
	
	<section>
		<?php event_browse();?>

	</section>
	
	<?php get_footer(); ?>
