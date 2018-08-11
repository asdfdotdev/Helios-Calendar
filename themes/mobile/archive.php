<?php
/**
 * @package Helios Calendar
 * @subpackage Default Mobile Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body>
	<?php my_menu(3);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=newsletter">Newsletter:</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=archive">Archive</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=signup">Subscribe</a></li>
		</ul>
	</nav>
	<section>	
		<?php news_archive('%a. %d','my_news_archive_nav'); ?>
		
	</section>
	
	<?php get_footer(); ?>