<?php
/**
 * @package Helios Calendar
 * @subpackage Mobile Theme Hacks
 */
	function my_menu($active_p){
		$url_root = cal_url();
		$primary = array('/index.php?com=digest' => '<img src="'.theme_dir().'/img/home.png'.'" width="16" height="16" alt="" />', '/index.php' => 'Events','/index.php?com=location' => 'Venues','/index.php?com=tools' => 'Tools');
		$x = 0;
		echo '
	<nav>
		<ul>';
		foreach($primary as $link => $label){
			echo '
			<li><a href="'.$url_root.$link.'" class="'.(($x == $active_p) ? 'on' : 'off').'">'.$label.'</a></li>';
			++$x;
		}
		echo '
		</ul>
	</nav>';
	}
	function my_menu_user(){
		echo (!user_check_status()) ? '
		<li><a href="'.cal_url().'/index.php?com=signin">Sign In</a></li>' : '
		<li><a href="'.cal_url().'/index.php?com=acc&amp;sec=edit" class="user_menu">Edit Acc.</a></li>
		<li><a href="'.cal_url().'/index.php?com=acc&amp;sec=list" class="user_menu">My Events</a></li>
		<li><a href="'.cal_url().'/signout.php" class="user_menu">Sign Out</a></li>';
	}
	function my_news_archive_nav($prev,$next,$start){
		global $hc_cfg, $hc_lang_news;
		
		$bak = (strtotime($hc_cfg['News']) <= strtotime($start)) ?
			'<a href="'.CalRoot.'/index.php?com=archive&amp;n='.$prev.'" class="mnu">&laquo;Prev</a>':
			'<a href="#" class="mnu">&nbsp;</a>';
		$fwd = ($next <= SYSDATE && strtotime($hc_cfg['News']) <= strtotime($next)) ?
			'<a href="'.CalRoot.'/index.php?com=archive&amp;n='.$next.'" class="mnu">Next&raquo;</a>':
			'<a href="#" class="mnu">&nbsp;</a>';
		return '
		<div class="nav">
			'.$bak.'
			'.$fwd.'
		</div>';
	}
	
	function my_tools_submenu(){
		global $hc_cfg;
		
		if($hc_cfg[54] == 1){
			echo '
				<li><a href="<?php echo cal_url();?>/index.php?com=newsletter">Newsletter:</a></li>
				<li><a href="<?php echo cal_url();?>/index.php?com=archive">Archive</a></li>
				<li><a href="<?php echo cal_url();?>/index.php?com=signup">Subscribe</a></li>
			';
		}
	}
?>