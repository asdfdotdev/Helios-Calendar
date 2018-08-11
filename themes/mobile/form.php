<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	//	Set Category Output to 3 Columns
	set_cat_cols(2);
	//	Override CAPTCHA Setting
	set_captcha(1);
	//	Override WYSIWYG Editor Setting
	set_editor(0);

	get_header();
	
	get_form_validation();?>
	<script>
	//<!--
	calx.offsetX = -75;
	calx.offsetY = 20;
	//-->
	</script>
</head>
<body>
<?php
	if(get_com() == 'signup' || get_com() == 'edit'){
		my_menu(2);?>
	<nav class="sub">
		<ul>
			<li><a href="<?php echo cal_url();?>/index.php?com=newsletter">Newsletter:</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=archive">Archive</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=signup">Subscribe</a></li>
		</ul>
	</nav>
<?php
	} else {
		my_menu(0);?>
	<nav class="sub">
		<ul>
			<li><a href="<?php echo cal_url();?>/index.php?com=search" class="">Search</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=submit" class="">Submit</a></li>
		</ul>
	</nav>
<?php
	}?>
	
	<section>
		<?php get_form();?>
	
	</section>
	
	<?php get_footer(); ?>