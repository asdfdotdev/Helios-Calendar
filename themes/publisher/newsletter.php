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
		<article>
		<div class="newsTools">
			<?php news_link_archive();?>
		</div>
		<?php echo news_lang('Welcome');?>

		<fieldset style="text-align:center;">
			<br />
			<?php news_link_signup();?>
				<span style="margin:0px 25px 0px 25px;">|</span>
			<?php news_link_edit();?>
			<br /><br />
		</fieldset>		
		</article>
		
		<aside>
	<?php 
		mini_cal_month();	
		get_side();?>
				
		</aside>
	</section>
	
	<?php get_footer(); ?>