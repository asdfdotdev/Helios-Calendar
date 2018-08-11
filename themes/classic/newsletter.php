<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
		
		<div class="newsTools">
			<?php news_link_archive();?>
		</div>
		<?php echo news_lang('Welcome');?>

		<fieldset style="text-align:center;margin-top:35px;">
			<legend>Our Events. Your Inbox.</legend>
			<br />
			<?php news_link_signup();?>
				<span style="margin:0px 25px 0px 25px;">|</span>
			<?php news_link_edit();?>
			<br /><br />
		</fieldset>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>