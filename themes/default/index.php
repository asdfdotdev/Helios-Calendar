<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

	<script src="<?php echo cal_url();?>/inc/javascript/validation.js"></script>
</head>
<body id="top" itemscope itemtype="http://schema.org/WebPage">
<?php
	$crumbs = array_merge(array('/' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section id="events">		
		<?php event_browse();?>
		
	</section>
	
	<div id="filter">
		<?php mini_search('Search Events by Keyword',0);?>
		
		<?php cal_filter();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>