<?php
/**
 * @package Helios Calendar
 * @subpackage Classic Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$active_tool = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(strip_tags($_GET['t'])) : 0;
	/*	Add Tool Options
	$add_tools = array(10 => 'New Tool');*/
	$add_tools = array();
	$crmbAdd = tool_crumb($active_tool,$add_tools);

	get_header();
	
	get_tool_validation($active_tool);
	
	//	Set Category Output to 3 Columns
	set_cat_cols(4);?>

</head>
<body>
<div id="container">
	<div id="content">
		<div id="menu"><?php cal_menu();?></div>
<?php 	
		switch($active_tool){
			case 1:
				tool_rss();
				break;
			case 2:
				tool_syndication();
				break;
			case 3:
				tool_mobile();
				break;
			case 4:
				tool_search();
				break;
			case 5:
				tool_api();
				break;
			case 10:
				//	Add Your Own Here!
				//	break;
			default:
				tool_menu($add_tools);
		}?>
		
		<?php theme_links();?>
		
	</div>
	
	<?php get_side(); ?>
	
	<?php get_footer(); ?>
</div>
</body>
</html>