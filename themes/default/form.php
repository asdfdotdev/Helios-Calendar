<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	set_cat_cols(3);
	
	get_header();
	
	get_form_validation();?>

</head>
<body id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
<?php
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>
		<?php get_form();?>
	
	</section>

	<?php get_side(); ?>
	
	<?php get_footer(); ?>