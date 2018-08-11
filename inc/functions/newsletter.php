<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Output link to newsletter signup form.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function news_link_signup(){
		global $hc_lang_news;
		echo '<a href="'.CalRoot.'/index.php?com=signup" class="icon news_su">'.$hc_lang_news['NewsOpt1'].'</a>';
	}
	/**
	 * Output link to newsletter edit form.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function news_link_edit(){
		global $hc_lang_news;
		echo '<a href="'.CalRoot.'/index.php?com=edit" class="icon news_e">'.$hc_lang_news['NewsOpt2'].'</a>';
	}
	/**
	 * Output link to newsletter archive.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function news_link_archive(){
		global $hc_lang_news;
		echo '
		<a href="'.CalRoot.'/index.php?com=archive" class="icon news_a">'.$hc_lang_news['NewsOpt0'].'</a>';
	}
	/**
	 * Retrieves interface text entry from newsletter language file.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which language file array key
	 * @return string language file entry
	 */
	function news_lang($which){
		global $hc_lang_news;
		
		if(!array_key_exists($which,$hc_lang_news))
			return;
		
		return $hc_lang_news[$which];
	}
	/**
	 * Generates newsletter archive navigation links.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param date $prev previous link date (Format: YYYY-MM-DD)
	 * @param date $next next link date (Format: YYYY-MM-DD)
	 * @param date $start start date for newsletter list window (Format: YYYY-MM-DD)
	 * @return string Navigation HTML Markup
	 */
	function news_archive_nav($prev,$next,$start){
		global $hc_cfg, $hc_lang_news;
		
		$bak = (strtotime($hc_cfg['News']) <= strtotime($start)) ?
			'<a href="'.CalRoot.'/index.php?com=archive&amp;n='.$prev.'" class="hc_left_arch" title="'.$hc_lang_news['Back'].'" /></a>':
			'<a href="#" class="hc_left_archb" title="' . $hc_lang_news['Back'] . '" /></a>';
		$fwd = ($next <= SYSDATE && strtotime($hc_cfg['News']) <= strtotime($next)) ?
			'<a href="'.CalRoot.'/index.php?com=archive&amp;n='.$next.'" class="hc_right_arch" title="'.$hc_lang_news['Forward'].'" /></a>':
			'<a href="#" class="hc_right_archb" title="'.$hc_lang_news['Forward'].'" /></a>';
		return '
		<div class="nav_arch">
			<a href="'.CalRoot.'/index.php?com=archive" title="'.$hc_lang_news['Home'].'" class="hc_home_arch" title="'.$hc_lang_news['Home'].'" /></a>
			'.$bak.'
			'.$fwd.'
		</div>';
	}
	/**
	 * Output newsletter archive list.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param string $header [optional] header date format, accepts string of strftime() format parameters (Default: %B %Y)
	 * @param string $format [optional] date output format, accepts string of strftime() format parameters (Default: %A, %B %d)
	 * @param string $nav_function [optional] navigation output function to use (Default: news_archive_nav)
	 * @return void
	 */
	function news_archive($header = '%B %Y',$format = '%A, %B %d', $nav_function = 'news_archive_nav'){
		global $hc_lang_news;
		
		$date = (isset($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : SYSDATE;
		$d = explode('-',$date);
		$year = (isset($d[0]) && is_numeric($d[0])) ? $d[0] : NULL;
		$month = (isset($d[1]) && is_numeric($d[1])) ? $d[1] : NULL;
		
		if(!checkdate($month, 1, $year)){
			$month = date('m', strtotime(SYSDATE));
			$year = date('Y', strtotime(SYSDATE));}
		
		$start = date("Y-m-d",mktime(0,0,0,$month,1,$year));
		$end = date("Y-m-d",mktime(0,0,0,$month+1,0,$year));
		$next = date("Y-m-d",mktime(0,0,0,$month+1,1,$year));
		$last = date("Y-m-d",mktime(0,0,0,$month-1,1,$year));
		$cnt = 1;
	
		$nav = call_user_func($nav_function,$last,$next,$start);
		
		echo $nav.'
		<header>' . stampToDate($start, $header) . '</header>';
		
		$result = doQuery("SELECT PkID, Subject, SentDate FROM " . HC_TblPrefix . "newsletters WHERE Status > 0 AND IsArchive = 1 AND IsActive = 1 AND ArchiveContents != '' AND SentDate Between '" . $start . "' AND '" . $end . "' ORDER BY SentDate");
		if(!hasRows($result)){
			echo '
		<ul>
			<li><p>' . $hc_lang_news['NoNewsletter'] . '</p></li>
		</ul>';
			return 0;}
		
		echo '
		<ul>';
		while($row = mysql_fetch_row($result)){
			$hl = ($cnt % 2 == 0) ? ' class="hl"' : '';
			echo '<li'.$hl.'><time datetime="'.stampToDate($row[2],'%Y-%m-%d').'">'.stampToDate($row[2],$format).'</time><a href="'.CalRoot.'/newsletter/index.php?n='.md5($row[0]).'" target="_blank">'.cOut($row[1]).'</a></li>';
			++$cnt;
		}
		echo '
		</ul>';
	}
?>