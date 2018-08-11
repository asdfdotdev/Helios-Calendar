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
		
		<?php news_archive(); ?>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>