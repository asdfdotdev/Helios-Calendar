<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$mySeries = series_fetch();

	get_header();?>

</head>
<body id="top" itemscope itemtype="http://schema.org/WebPage">
<?php
	$crumbs = array_merge(array('/' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>
		<?php series_list($mySeries);?>
		
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>