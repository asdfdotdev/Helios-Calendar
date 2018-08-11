<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	function my_event_browse_nav($prev,$next,$window,$location){
		global $lID, $hc_cfg, $hc_lang_event;
		
		$m = ($window == 0) ? '&amp;m=1' : '';
		$pLink = ($window > 518400) ? date("U", mktime(0,0,0,HCMONTH-1,1,HCYEAR)) : $prev - ($window + 86400);
		$fltr = (isset($_SESSION['hc_favCat']) || isset($_SESSION['hc_favCity'])) ? 'R' : '';
		$bak = ($hc_cfg['First'] >= $prev || (HCDATE <= SYSDATE && $hc_cfg[11] == 0)) ? 
				'<a href="#" class="mnu">&nbsp;</a>':
				'<a href="'.CalRoot.'?d='.date("Y-m-d",$pLink).$location.$m.'" class="mnu">&laquo;Prev</a>';
		$fwd = ($hc_cfg['Last'] > $next) ? 
				'<a href="'.CalRoot.'?d='.date("Y-m-d",($next+86400)).$location.$m.'" class="mnu">Next&raquo;</a>':
				'<a href="#" class="mnu">&nbsp;</a>';
		$loc = ($lID > 0) ? '<a href="'.CalRoot.'/index.php?com=location&lID='.$lID.'" rel="nofollow"><img src="'.CalRoot.'/img/icons/card.png" /></a>' : '';
		
		return '
		<div class="nav">
			'.$bak.'
			'.$fwd.'
		</div>';
	}
?>