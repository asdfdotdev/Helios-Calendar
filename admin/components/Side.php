<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!defined('hcAdmin')){header("HTTP/1.1 403 No Direct Access");exit();}
	
	if(count($hc_Side) > 0){
		echo '
			<ul id="ql_main">';
		foreach($hc_Side as $val){
			echo '
				<li><a href="'.$val[0].'"'.(($val[3] == 1) ? ' target="_blank"' : '').''.((isset($val[4]) && $val[4] != '') ? ' onclick="'.$val[4].'"' : '').'><img src="'.AdminRoot.'/img/icons/'.$val[1].'" width="16" height="16" alt="" />'.$val[2].'</a></li>';
		}
		echo '
			</ul>';
	}
	if($hc_cfg[50] != '0'){
		$hc_QL = explode(',',$hc_cfg[50]);
		echo '
			<ul id="ql_apis">
				'.((in_array('1',$hc_QL)) ? '<li><a href="https://disqus.com" target="_blank"><img src="'.AdminRoot.'/img/logos/disqus_icon.png" width="16" height="16" alt="" />'.$hc_lang_core['QLDisqus'].'</a></li>' : '').'
				'.((in_array('2',$hc_QL)) ? '<li><a href="https://twitter.com/'.$hc_cfg[63].'" target="_blank"><img src="'.AdminRoot.'/img/logos/twitter_icon.png" width="16" height="16" alt="" />'.$hc_lang_core['QLTwitter'].'</a></li>' : '').'
				'.((in_array('3',$hc_QL)) ? '<li><a href="http://eventful.com" target="_blank"><img src="'.AdminRoot.'/img/logos/eventful_icon.png" width="16" height="16" alt="" />'.$hc_lang_core['QLEventful'].'</a></li>' : '').'
				'.((in_array('4',$hc_QL)) ? '<li><a href="https://www.eventbrite.com/login" target="_blank"><img src="'.AdminRoot.'/img/logos/eventbrite_icon.png" width="16" height="16" alt="" />'.$hc_lang_core['QLEventbrite'].'</a></li>' : '').'
				'.((in_array('5',$hc_QL)) ? '<li><a href="https://bit.ly" target="_blank"><img src="'.AdminRoot.'/img/logos/bitly_icon.png" width="16" height="16" alt="" />'.$hc_lang_core['QLBitly'].'</a></li>' : '').'
			</ul>';
	}
?>