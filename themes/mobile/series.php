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
	<?php my_menu(1);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=search">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit">Submit</a></li>
		</ul>
	</nav>
	<section>
		<?php series_list($mySeries);?>
	
	</section>
	
	<?php get_footer(); ?>