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
	</section>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>