<?php
/**
 * @package Helios Calendar
 * @subpackage Publisher Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	
	get_header();?>

</head>
<body itemscope itemtype="http://schema.org/WebPage">

	<header>
		<span>
			<?php echo cal_name();?>
			
			<div id="tag">Publishing awesome events.</div>
		</span>
		<aside>
			<?php mini_search('Search Events by Keyword',0);?>
		
		</aside>
	</header>
	<nav>
		<?php build_breadcrumb($crumbs);?>
	</nav>
	<section>
		<article id="events">
		<?php news_archive('%A, %B %d - %I:%M %p'); ?>
		
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>