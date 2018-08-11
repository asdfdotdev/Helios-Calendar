<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body id="top" itemscope itemtype="http://schema.org/WebPage">
	<a name="top"></a>
<?php
	$crumbs = array_merge(array(cal_url().'/index.php?com=digest' => 'Home', cal_url() => 'Calendar'),$crmbAdd);
	build_breadcrumb($crumbs);?>
	
	<section>	
		<?php
			user_menu();
			
			user_account();?>
		
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>