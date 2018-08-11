<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	$active_tool = (isset($_GET['t']) && is_numeric($_GET['t'])) ? cIn(strip_tags($_GET['t'])) : 0;
	/*	Add Tool Options
		$add_tools = array(10 => 'New Tool',11 => 'New Tool 2');*/
	$add_tools = array();
	$crmbAdd = tool_crumb($active_tool,$add_tools);

	get_header();
	
	get_tool_validation($active_tool);
	
	//	Set Category Output to 3 Columns
	set_cat_cols(2);?>

</head>
<body>
	<?php my_menu(2);?>
	
	<nav class="sub">
		<ul>
			<li><a href="<?php echo cal_url();?>/index.php?com=newsletter">Newsletter:</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=archive">Archive</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=signup">Subscribe</a></li>
		</ul>
	</nav>
	
	<section>
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
			case 10:
				echo '<div style="margin:0;padding:0;height:300px;"><p><b>Internet Explorer 9 Users:</b></p><p>You can pin our calendar to your taskbar for easy access. Once pinned right click on the icon to easily access different parts of our calendar through the custom jumplist.</p></div>';
				break;
			default:
				tool_menu($add_tools);
		}?>
	</section>
	
	<?php get_footer(); ?>