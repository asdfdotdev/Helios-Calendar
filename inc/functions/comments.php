<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
		
	/**
	 * Output comments for current page.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @param string $ID comment identifier. Used to identify unique page threads in some comment systems (Disqus).
	 * @param string $URL page URL. URL of the current page (or common page, for shared comments - event series).
	 * @param string $title page title. Used to label comments thread for some comment systems (Disqus).
	 * @param integer $isDev [optional] required for development on "inaccessable" (internal) sites for some comment systems, 0 = public install (production), 1 = secure/private/inaccessable install (development) (Default:0)
	 * @return void
	 */
	function get_comments($ID,$URL,$title,$isDev = 0){
		global $hc_cfg;
		
		switch($hc_cfg[56]){
			case 1:
				get_disqus_comments($ID,$URL,$title,$isDev);
				break;
			case 2:
				get_facebook_comments($URL);
				break;
			case 3:
				get_livefyre_comments($URL);
				break;
				break;
			default:
				//	Comments Off
				break;
		}
	}
	/**
	 * Output in-page link to comment thread w/optional comment count.
	 * @since 2.0.0
	 * @version 2.1.0
	 * @param string $ID comment identifier. Used to identify unique page threads in some comment systems (Disqus).
	 * @param string $URL page URL. URL of the current page (or common page, for shared comments - event series).
	 * @param integer $doCount [optional] include comment count, 0 = link only, 1 = link with comment count (Default:1)
	 * @return void
	 */
	function get_comments_link($ID,$URL,$doCount = 1){
		global $hc_cfg;
		
		switch($hc_cfg[56]){
			case 1:
				get_disqus_comments_link($ID,$URL,$doCount);
				break;
			case 2:
				get_facebook_comments_link($URL,$doCount);
				break;
			case 3:
				get_livefyre_comments_link($URL,$doCount);
				break;
			default:
				//	Comments Off
				break;
		}
	}
	/**
	 * Output Disqus Comments.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $ID disqus_identifier
	 * @param string $URL disqus_url
	 * @param string $title disqus_title
	 * @param integer $isDev [optional] required for development on "inaccessable" (internal) sites, 0 = public install (production), 1 = secure/private/inaccessable install (development) (Default:0)
	 * @return void
	 */
	function get_disqus_comments($ID,$URL,$title,$isDev = 0){
		global $hc_cfg;
		
		if($hc_cfg[56] != 1 || $hc_cfg[25] == '')
			return 0;
			
		$dev = ($isDev == 1) ? '
			var disqus_developer = "1";' : '';
			
		echo '
		<div id="disqus_thread"></div>
		<script>
			var disqus_shortname = \''.$hc_cfg[25].'\';'.$dev.'
			var disqus_url = \''.$URL.'\';
			var disqus_identifier = \''.$ID.' '.$URL.'\';
			var disqus_title = \''.cIn($title).'\';
			var disqus_container_id = \'disqus_thread\';
			(function() {
				var dsq = document.createElement(\'script\'); dsq.type = \'text/javascript\'; dsq.async = true;
				dsq.src = \'http://\' + disqus_shortname + \'.disqus.com/embed.js\';
				(document.getElementsByTagName(\'head\')[0] || document.getElementsByTagName(\'body\')[0]).appendChild(dsq);
			})();
		</script>
		<a href="http://disqus.com" class="dsq-brlink" rel="nofollow">Comments powered by Disqus</a>';
	}
	/**
	 * Output in-page link to Disqus comment thread w/optional comment count.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $ID disqus_identifier
	 * @param string $URL disqus_url
	 * @param integer $doCount [optional] include comment count for thread, 0 = link only, 1 = link with comment count (Default:1)
	 * @return void
	 */
	function get_disqus_comments_link($ID,$URL,$doCount = 1){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[56] != 1 || $hc_cfg[25] == '')
			return 0;
		
		echo ($doCount > 0) ? '
		<a href="#disqus_thread" data-disqus-identifier="'.$ID.' '.$URL.'">'.$hc_lang_core['Comments'].'</a>
		<script type="text/javascript">
		var disqus_shortname = \''.$hc_cfg[25].'\';
		(function () {
		var s = document.createElement(\'script\'); s.async = true;
		s.type = \'text/javascript\';
		s.src = \'http://\' + disqus_shortname + \'.disqus.com/count.js\';
		(document.getElementsByTagName(\'HEAD\')[0] || document.getElementsByTagName(\'BODY\')[0]).appendChild(s);}());
		</script>' : '
		<a href="#disqus_thread">'.$hc_lang_core['Comments'].'</a>';
	}	
	/**
	 * Output Facebook Comments.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $URL comment URL. URL of the current page (or common page, for multi-page comments - event series).
	 * @return void
	 */
	function get_facebook_comments($URL){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[56] != 2)
			return 0;
		
		echo '<div id="fb-comments" class="fb-comments" data-href="'.$URL.'" data-num-posts="'.$hc_cfg[100].'" data-width="'.$hc_cfg[101].'"></div>';
	}
	/**
	 * Output in-page link to Facebook Comments thread w/optional comment count.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $URL comment URL. URL of the current page (or common page, for multi-page comments - event series).
	 * @param integer $doCount [optional] include comment count for thread, 0 = link only, 1 = link with comment count (Default:1)
	 * @return void
	 */
	function get_facebook_comments_link($URL,$doCount = 1){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[56] != 2)
			return 0;
		
		echo '<a href="#fb-comments">'.(($doCount > 0) ? '<fb:comments-count href="'.$URL.'"></fb:comments-count> ':'').''.$hc_lang_core['Comments'].'</a>';
	}
	/**
	 * Output Livefyre Comments.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $URL comment URL. URL of the current page (or common page, for multi-page comments - event series).
	 * @return void
	 */
	function get_livefyre_comments($URL){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[56] != 3 || $hc_cfg[102] == '')
			return 0;
		
		echo '
		<div id="livefyre" style="min-height:0;"></div>
		<script src="http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js"></script>
		<script>
			var lf_config = LF({site_id: "'.$hc_cfg[102].'",article_id: "'.$URL.'"});
			var conv = lf_config;
		</script>';
	}
	/**
	 * Output in-page link to Livefyre Comments thread w/optional comment count.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $URL comment URL. URL of the current page (or common page, for multi-page comments - event series).
	 * @param integer $doCount [optional] include comment count for thread, 0 = link only, 1 = link with comment count (Default:1)
	 * @return void
	 */
	function get_livefyre_comments_link($URL,$doCount = 1){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[56] != 3 || $hc_cfg[102] == '')
			return 0;
		
		echo ($doCount > 0) ? '
		<script src="http://zor.livefyre.com/wjs/v1.0/javascripts/CommentCount.js"></script>
		<a href="#livefyre"><span class="livefyre-commentcount" data-lf-site-id="'.$hc_cfg[102].'" data-lf-article-id="'.$URL.'">'.$hc_lang_core['Comments'].'</span></a>':'
		<a href="#livefyre">'.$hc_lang_core['Comments'].'</a>';
	}
	/**
	 * Set comments system used for the current page. Some options require additional settings to have been saved in the admin console to function properly.
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param integer $type comments system to use. (0 = OFF, 1 = Disqus, 2 = Facebook, 3 = Livefyre).
	 * @return void
	 */
	function set_comments($type){
		global $hc_cfg;
		if(!is_numeric($type) || $type < 0)
			return 0;
		
		$hc_cfg[56] = $type;
	}
?>
