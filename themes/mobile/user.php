<?php
/**
 * @package Helios Calendar
 * @subpackage Default Theme
 */
	if(!defined('isHC')){exit(-1);}
	
	get_header();?>

</head>
<body>
	<?php my_menu(0);?>
	
	<nav class="sub">
		<ul>
			<li>&nbsp;</li>
			<li><a href="<?php echo cal_url();?>/index.php?com=acc&amp;sec=edit" class="user_menu">Edit Acc.</a></li>
			<li><a href="<?php echo cal_url();?>/index.php?com=acc&amp;sec=list" class="user_menu">My Events</a></li>
			<li><a href="<?php echo cal_url();?>/signout.php" class="user_menu">Sign Out</a></li>
		</ul>
	</nav>

	<section>	
		<?php
			user_account();?>
		
	</section>
	
	<?php get_footer(); ?>