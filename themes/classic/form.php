<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();
	
	get_form_validation();
	
	//	Set Category Output to 3 Columns
	set_cat_cols(3);?>

</head>
<body>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>

		<?php get_form();?>

		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>