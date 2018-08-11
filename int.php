<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include_once(dirname(__FILE__).'/loader.php');
	require_once(HCPATH . HCINC . '/functions/theme.php');

	/**
	 * Output wall calendar style monthly mini-calendar to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $date [optional] calendar month to create (Default: current system date)
	 * @return void
	 */
	function int_mini_calendar($date = SYSDATE){
		mini_cal_month(SYSDATE);
	}
	/**
	 * Output an event list to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type list to output, 0 = Billboard Events, 1 = Most Popular Events, 2 = Newest Events (Default:0)
	 * @param integer $eTime [optional] 0 = do NOT include start time, 1 = include event start time in list (Default:0)
	 * @return void
	 */
	function int_event_list($type, $eTime){
		event_list($type, $eTime);
	}
	/**
	 * Output RSS feed and iCalendar subscription links to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function int_links(){
		theme_links();
	}
	/**
	 * Output mini search form (keyword only search) to a page outside of Helios Calendar.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $holder placeholder for input textbox
	 * @param integer $button [optional] 0 = do NOT output button, 1 = include "search events" form submit button (Default:0)
	 * @return void
	 */
	function int_mini_search($holder = '',$button = 0){
		mini_search($holder, $button);
	}
	/**
	 * Output weekly dashboard to a page outside of Helios Calendar.
	 * @since 2.0.1
	 * @version 2.0.1
	 * @param binary $submit include submit event link, 0 = hide , 1 = show (Default:1)
	 * @param binary $ical include iCalendar subscription link, 0 = hide, 1 = show (Default:1)
	 * @param binary $rss include All Events rss feed link, 0 = hide, 1 = show (Default:1)
	 * @param binary $end_time include end time in event lists, 0 = hide, 1 = show (Default:1)
	 * @param string $menu_format menu format string, accepts any supported strftime() format parameters (Default:%a %m/%d)
	 * @return void
	 */
	function int_week_dashboard($submit = 1,$ical = 1,$rss = 1, $end_time = 1, $menu_format = '%a %m/%d'){
		global $hc_cfg, $hc_lang_core;
		
		include(HCLANG.'/public/integration.php');
		
		if(!file_exists(HCPATH.'/cache/int7_'.SYSDATE.'.php')){
			if(count(glob(HCPATH.'/cache/int7_*.php')) > 0)
				foreach(glob(HCPATH.'/cache/int7_*.php') as $file){
					unlink($file);
				}
			
			ob_start();
			$fp = fopen(HCPATH.'/cache/int7_'.SYSDATE.'.php', 'w');
			fwrite($fp, "<?php\n//\tHelios Dashboard Integration Events Cache - Delete this file when upgrading.\n");
			
			$result = doQuery("SELECT PkID, Title, StartDate, StartTime, EndTime, TBD FROM " . HC_TblPrefix . "events 
							WHERE IsActive = 1 AND IsApproved = 1 AND StartDate Between '".SYSDATE."' AND ADDDATE('".SYSDATE."', INTERVAL 6 DAY)
							ORDER BY StartDate, TBD, StartTime, Title");
			if(hasRows($result)){
				$cur_day = -1;
				$cur_date = '';
								
				fwrite($fp, "\$hc_next7 = array(\n");
				
				while($row = mysql_fetch_row($result)){
					if($cur_date != $row[2]){
						++$cur_day;
						$cur_date = $row[2];
						if($cur_day > 0)
							fwrite($fp, "\t),\n");
						fwrite($fp, $cur_day." => array(\n");
					}
					
					if($row[5] == 0){
						$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
						$time .= ($row[4] != '' && $end_time == 1) ? ' - ' . stampToDate($row[4], $hc_cfg[23]) : '';
					} else {
						$time = ($row[5] == 1) ? $hc_lang_int['AllDay'] : $hc_lang_int['TimeTBA'];}
					
					
					fwrite($fp, "\t".$row[0] . " => array(\"".$time."\",\"".stampToDate($row[2], $hc_cfg[14])."\",\"".str_replace("\"","'",cOut($row[1]))."\"),\n");
					
				}
				fwrite($fp, "\t),");
			}
			fwrite($fp, "\n)\n?>");
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/int7_'.SYSDATE.'.php');
		
		echo '
		<script>
		//<!--
		function hc_tog_dash(show){var i = 0;while(i <= 6){document.getElementById("hc_dashboard_day"+i).style.display = (i == show) ? "block" : "none";i++;}}
		//-->
		</script>
		<div id="hc_dashboard">
			<ul id="menu">
				<li><a href="javascript:;" onclick="hc_tog_dash(0);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 0))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(1);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 1))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(2);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 2))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(3);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 3))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(4);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 4))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(5);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 5))).'</a></li>
				<li><a href="javascript:;" onclick="hc_tog_dash(6);return false;">'.strftime($menu_format,(strtotime(SYSDATE) + (86400 * 6))).'</a></li>
				'.(($rss == 1 && $hc_cfg[106] == 1) ? '<li class="icon"><a href="'.CalRoot.'/rss/" title="'.$hc_lang_int['TitleRSS'].'" rel="nofollow" target="_blank"><img src="'.CalRoot.'/img/feed.png" width="16" height="16" alt="" /></a></li>':'').'
				'.(($ical == 1 && $hc_cfg[108] == 1) ? '<li class="icon"><a href="webcal://'.substr(CalRoot, 7).'/link/ical.php" title="'.$hc_lang_int['TitleiCal'].'" rel="nofollow"><img src="'.CalRoot.'/img/icons/ical.png" width="16" height="16" alt="" /></a></li>':'').'
			</ul>';
		
		$date = '';
		foreach($hc_next7 as $day => $arr){
			foreach($arr as $id => $value){
				if($date != $value[1]){
					$date = $value[1];
					echo '
			<div id="hc_dashboard_day'.$day.'" class="hc_dashboard_day"'.(($day > 0) ? ' style="display:none;"':'').'>
			<ul>
				<li>'.$value[1].'</li>';
				}
				echo '
				<li><div class="time">'.$value[0].'</div><a href="'.CalRoot.'/index.php?eID='.$id.'" rel="nofollow">'.cOut($value[2]).'</a></li>';
			}
			echo ($date != '') ? '
			</ul>
			</div>':'';
			
		}
		echo ($date == '') ? $hc_lang_int['NoEvent'] : '';
		echo '
			<a href="'.CalRoot.'/" rel="nofollow">'.$hc_lang_int['Browse'].'</a>
			'.(($submit == 1) ? '| <a href="'.CalRoot.'/index.php?com=submit" rel="nofollow">'.$hc_lang_int['Submit'].'</a>':'').'
		</div>';
	}
?>