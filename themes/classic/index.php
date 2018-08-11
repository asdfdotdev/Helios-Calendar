<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}

	event_browse_valid();
	
	get_header();?>

</head>
<body>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
	
		<div id="browse"><?php event_browse(1);?></div>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>