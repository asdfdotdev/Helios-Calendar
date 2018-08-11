<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$mySeries = series_fetch();
	
	get_header();?>

</head>
<body>
	<?php my_menu(0);?>
	
	<nav class="sub">
		<ul>
			<li><a href="<?php echo cal_url();?>/index.php?com=search" class="">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit" class="">Submit</a></li>
		</ul>
	</nav>
	<section>
		<?php series_list($mySeries);?>
	
	</section>
	
	<?php get_footer(); ?>