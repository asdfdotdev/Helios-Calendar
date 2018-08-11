<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Output core head tag content: metadata, page title, links, etc.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function hc_header(){
		global $com, $hc_cfg, $hc_lang_core, $meta, $expire, $last_mod;
		
		echo ($expire != '') ? '<meta name="expires" content="'.date("r",strtotime($expire)).'">
	' : '';
		echo ($last_mod != '') ? '<meta name="last-modified" content="'.date("r",strtotime($last_mod)).'">
	' : '';
		
		$cmnts = ($hc_cfg[106] == 1 && $hc_cfg[56] == 1 && $hc_cfg[25] != '') ? '<link rel="alternate" type="application/rss+xml" title="'.CalName.' '.$hc_lang_core['Comments'].'" href="http://' . $hc_cfg[25] . '.disqus.com/latest.rss" />' : '';
		$can = (!defined('HCCanURL')) ? '' : '
	<link rel="canonical" href="'.HCCanURL.'" />';
		
		$rss = ($hc_cfg[106] == 1) ? '
	<link rel="alternate" type="application/rss+xml" title="'.CalName.' '.$hc_lang_core['All'].'" href="' . CalRoot . '/rss/" />
	<link rel="alternate" type="application/rss+xml" title="'.CalName.' '.$hc_lang_core['Newest'].'" href="' . CalRoot . '/rss/?s=1" />
	<link rel="alternate" type="application/rss+xml" title="'.CalName.' '.$hc_lang_core['Featured'].'" href="' . CalRoot . '/rss/?s=3" />
	<link rel="alternate" type="application/rss+xml" title="'.CalName.' '.$hc_lang_core['Popular'].'" href="' . CalRoot . '/rss/?s=2" />':'';
		
		echo ($hc_cfg[7] == 1) ? '<meta name="robots" content="all, index, follow" />' : '<meta name="robots" content="noindex, nofollow" />';
		echo '
	<meta name="description" content="'.substr(str_replace("\"","'",strip_tags($meta['desc'])),0,150).'" />
	<meta name="keywords" content="'.$meta['keywords'].'" />
	<title>'.$meta['title'].' - '.CalName. '</title>	
	<meta name="generator" content="Helios Calendar ' . $hc_cfg[49] . '" /> <!-- leave this for stats -->
	<link rel="search" type="application/opensearchdescription+xml" href="'.CalRoot.'/opensearch.php" title="'.CalName.'" />'.$rss.$cmnts.$can;
	}
	/**
	 * Output public calendar menu default markup.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function cal_menu(){
		$menu = cal_menu_items();
		
		echo '<nav>
		<ul>';
		foreach($menu as $id => $opts){
			echo '
			<li><a href="'.$opts[0].'" class="menu '.$opts[2].'">'.$opts[1].'</a></li>';}
		echo '
		</ul>
		</nav>';
	}
	/**
	 * Generate public calendar menu option array. Structure: Item Name => array(link url,link text,default core css classes)
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return array menu
	 */
	function cal_menu_items(){
		global $hc_cfg, $hc_lang_core;
		
		$menu_items['home'] = array(CalRoot.'/index.php', $hc_lang_core['Events'], 'hc_mnu_e');
		if($hc_cfg[45] == 1 && ($hc_cfg[42] != '' && $hc_cfg[43] != ''))
			$menu_items['venues'] = array(CalRoot.'/index.php?com=location', $hc_lang_core['Locations'],'hc_mnu_v');
		if($hc_cfg[1] == 1)
			$menu_items['submit'] = array(CalRoot.'/index.php?com=submit', $hc_lang_core['Submit'],'hc_mnu_sb');
		$menu_items['search'] = array(CalRoot.'/index.php?com=search', $hc_lang_core['Search'], 'hc_mnu_se');
		if($hc_cfg[54] == 1)
			$menu_items['newsletter'] = array(CalRoot.'/index.php?com=newsletter', $hc_lang_core['Newsletter'],'hc_mnu_n');
		$menu_items['tools'] = array(CalRoot.'/index.php?com=tools', $hc_lang_core['Tools'],'hc_mnu_t');
		if($hc_cfg[97] == 1)
			$menu_items['digest'] = array(CalRoot.'/index.php?com=digest', $hc_lang_core['WhatsNew'],'hc_mnu_d');
		if(user_check_status()){
			$menu_items['user_account'] = array(CalRoot.'/index.php?com=acc', $hc_lang_core['Account'],'hc_mnu_usr hc_mnu_usr_ac');
			$menu_items['user_signout'] = array(CalRoot.'/signout.php', $hc_lang_core['SignOut'],'hc_mnu_usr hc_mnu_usr_so');
		} elseif(($hc_cfg[113]+$hc_cfg[114]+$hc_cfg[115]) > 0 && !user_check_status()){
			$menu_items['user_signin'] = array(CalRoot.'/index.php?com=signin', $hc_lang_core['SignIn'],'hc_mnu_usr hc_mnu_usr_si');
		}
		
		return $menu_items;
		
	}
	/**
	 * Output filter menu & checkboxes.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $mnu [optional] 0 = do NOT output menu, 1 = include menu in output (Default:1)
	 * @param integer $cols [optional] number of output columns (Default:1)
	 * @return void
	 */
	function cal_filter($mnu = 1, $cols = 1){
		global $hc_cfg, $hc_lang_core;
		
		require_once(HCLANG.'/public/filter.php');
		
		$cQuery = (isset($_SESSION['hc_favCat'])) ? " AND c.PkID IN (".cIn($_SESSION['hc_favCat']).") " : '';
		
		$result = doQuery("SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, c2.PkID as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.PkID = c2.PkID ".$cQuery.")
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID = 0 AND c.IsActive = 1
						GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.PkID
						UNION 
						SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, c3.PkID as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
							LEFT JOIN " . HC_TblPrefix . "categories c3 ON (c.PkID = c3.PkID ".$cQuery.")
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID > 0 AND c.IsActive = 1
						GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName, c3.PkID
						ORDER BY Sort, ParentID, CategoryName");
		if(!hasRows($result))
			return 0;
		
		$cols = ($cols > 0) ? $cols : 1;
		$cnt = 1;
		echo (($mnu == 1) ? '<span>
			[ <a href="javascript:;" onclick="checkAllArray(\'hcFilter\', \'catID[]\');document.getElementById(\'hcFilter\').submit();return false;" rel="nofollow">'.$hc_lang_core['AllLink'].'</a>
			| <a href="javascript:;" onclick="uncheckAllArray(\'hcFilter\', \'catID[]\');document.getElementById(\'hcFilter\').submit();return false;" rel="nofollow">'.$hc_lang_core['NoneLink'].'</a> ]
		</span>':'').'
		<form name="hcFilter" id="hcFilter" method="post" action="'.CalRoot.'/filter.php">
		<input name="r" id="r" type="hidden" value="1" />
		<div class="catCol">';
		while($row = mysql_fetch_row($result)){
			if($cnt > ceil(mysql_num_rows($result) / $cols) && $row[2] == 0){
				echo ($cnt > 1) ? '
			</div>' : '';
				echo '
			<div class="catCol">';
				$cnt = 1;}
			
			$sub = ($row[2] != 0) ? ' class="sub"' : '';
			$check = ($row[4] != '') ? 'checked="checked" ' : '';
			
			echo '
			<label for="catID_' . $row[0] . '"'.$sub.'><input onclick="document.getElementById(\'hcFilter\').submit();return false;" name="catID[]" id="catID_'.$row[0].'" type="checkbox" '.$check.'value="'.$row[0].'" />'.cOut($row[1]).'</label>';
			++$cnt;
		}
		echo '
		</div>
		</form>';
	}
	/**
	 * Output wall calendar style monthly mini-calendar. Writes cache files if not currently available.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $date [optional] calendar month to create (Default: current browse date)
	 * @return void
	 */
	function mini_cal_month($date = ''){
		global $lID, $favQ1, $favQ2, $hc_cfg, $hc_lang_config;
		
		$year = HCYEAR;
		$month = HCMONTH;
		if($date != ''){
			$year = date("Y",strtotime($date));
			$month = date("m",strtotime($date));
		}
		
		if(!file_exists(HCPATH.'/cache/mcal_'.$year.'_'.$month)){
			ob_start();
			$fp = fopen(HCPATH.'/cache/mcal_'.$year.'_'.$month, 'w');
			
			$stopDay = date("t", mktime(0,0,0,$month,1,$year));
			$locSaver = $lQuery = $opts = $dow = '';
			$events = array();
			$result = doQuery("SELECT DISTINCT e.StartDate
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							WHERE e.StartDate BETWEEN '" . date("Y-m-d", mktime(0,0,0,$month,1,$year)) . "' AND '" . date("Y-m-d", mktime(0,0,0,$month+1,0,$year)) . "'
							AND e.IsActive = 1 AND e.IsApproved = 1
							ORDER BY e.StartDate");
			
			if(hasRows($result))
				while($row = mysql_fetch_row($result)){
					$events[] = stampToDate($row[0], $hc_cfg[93]);
				}
			
			$navBack = date("Y-m-d", mktime(0,0,0,$month-1,1,$year));
			$navFrwd = date("Y-m-d", mktime(0,0,0,$month+1,1,$year));
			$bak = ($hc_cfg['First'] > strtotime(HCDATE) || (HCDATE <= SYSDATE && $hc_cfg[11] == 0)) ?
				'<a href="#" rel="nofollow">&lt;</a>' :
				'<a href="'.CalRoot . '/?d='.$navBack.$locSaver.'" rel="nofollow">&lt;</a>';
			$fwd = ($hc_cfg['Last'] > strtotime($navFrwd)) ? 
					'<a href="'.CalRoot.'/?d='.$navFrwd.$locSaver.'" rel="nofollow">&gt;</a>':
					'<a href="#" rel="nofollow">&gt;</a>';
			$jmp = ($hc_cfg[11] == 1) ? 12 : 0;
			$stop = $jmp + 12;
			$jumpMonth = date("n", mktime(0,0,0,$month-$jmp,1,$year));
			$jumpYear = date("Y", mktime(0,0,0,$month-$jmp,1,$year));
			$sysDay = date("d", strtotime(SYSDATE));
			$sysMonth = date("m", strtotime(SYSDATE));
			$sysYear = date("y", strtotime(SYSDATE));
			$actJump = date("Y-m-d",mktime(0,0,0,$month,1,$year));
			
			$i = 0;
			while($i <= $stop){
				$jmpDate = date("Y-m-d", mktime(0,0,0,$jumpMonth+$i,1,$jumpYear));
				$select = ($jmpDate == $actJump) ? ' selected="selected"' : '';
				$opts .= '
						<option value="'.CalRoot.'/?d='.$jmpDate.$locSaver.'&amp;b=1"'.$select.'>'.strftime($hc_cfg[92], mktime(0,0,0,$jumpMonth + $i,1,$jumpYear)).'</option>';
				++$i;
			}
			$i = 0;
			while($i < 7){
				$dow .= '
					<td class="dow">' . $hc_lang_config['MiniCalDays'][($hc_cfg[22] + $i) % 7] . '</td>';
				++$i;
			}
			echo '
			<form name="frmJump" id="frmJump" action="#">
			<table class="mini-cal">
				<tr>
					<td class="nav">'.$bak.'</td>
					<td class="title" colspan="5">
					<select name="jumpMonth" id="jumpMonth" onchange="window.location.href=this.value;">'.$opts.'	
					</select>
					</td>
					<td class="nav">'.$fwd.'</td>
				</tr>
				<tr>
					'.$dow.'
				</tr>
				<tr>';
			$i = 0;
			$fillCnt = (((date("w", mktime(0,0,0,$month,1,$year)) - $hc_cfg[22]) + 7) % 7);
			while($i < $fillCnt){
				echo '
					<td class="blank">&nbsp;</td>';
				++$i;
			}
			$i = 1;
			while($i <= $stopDay){
				echo ($i > 1 && ($i + $fillCnt) % 7 == 1) ? '
				</tr>
				<tr>' : '';
				if(in_array($i, $events)) {
					$cell = (SYSDATE == date("Y-m-d",mktime(0,0,0,$month,$i,$year))) ? 'today' : 'events';
					echo '
					<td class="'.$cell.'"><a href="' . CalRoot . '/index.php?d='.$year.'-'.$month.'-'.$i.'&amp;m=1'.$locSaver.'" rel="nofollow">'.strftime($hc_cfg[93], mktime(0,0,0,$month,$i,$year)).'</a></td>';
				} else {
					$cell = (SYSDATE == date("Y-m-d",mktime(0,0,0,$month,$i,$year))) ? 'today' : 'empty';
					echo '
					<td class="'.$cell.'">'.strftime($hc_cfg[93], mktime(0,0,0,$month,$i,$year)).'</td>';
				}
				++$i;
			}
			$i = ($i + $fillCnt - 1) % 7;
			while($i < 7 && $i != 0){
				echo '
					<td class="blank">&nbsp;</td>';
				++$i;
			}
			echo '
				</tr>
			</table>
			</form>';
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/mcal_'.$year.'_'.$month);
	}
	/**
	 * Output theme selector. Writes cache files if not currently available.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function select_theme() {
		global $hc_lang_core;
		
		if(!file_exists(HCPATH.'/cache/theme.php')){
			
			if(file_exists(HCPATH.'/themes/')){
				ob_start();
				$fp = fopen(HCPATH.'/cache/theme.php', 'w');
				fwrite($fp, "<?php\n//\tHelios Theme Select Cache - Delete this file when upgrading.\n");
				$langOpt = array();
				$dir = dir(HCPATH.'/themes/');
				$x = 0;
				
				while(($file = $dir->read()) != false){
					if(is_dir($dir->path.'/'.$file) && strpos($file,'.') === false && strpos($file,'_') === false)
						$langOpt[] = $file;
				}

				sort($langOpt);
				
				fwrite($fp, "echo '
		<select name=\"hc_theme\" id=\"hc_theme\" onchange=\"window.location.href=this.value;\">");
				foreach($langOpt as $val){
					fwrite($fp,"
			<option'.((\$_SESSION['Theme'] == '$val') ? ' selected=\"selected\"' : '').' value=\"".CalRoot."/?theme=".$val."\">".ucwords($val)."</option>");
					++$x;
				}
				fwrite($fp, "
		</select>';");

				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
			} else {
				echo $hc_lang_core['ThemeError'];
			}
		}
		include(HCPATH.'/cache/theme.php');
	}
	/**
	 * Output language pack selector. Writes cache file if not currently available.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type [optional] 0 = flag icons, 1 = select list (Default:0)
	 * @return void
	 */
	function select_language($type = 0){
		global $hc_lang_core;
		
		if(!file_exists(HCPATH.'/cache/lang.php')){
			
			if(file_exists(HCPATH.HCINC.'/lang/')){
				ob_start();
				$fp = fopen(HCPATH.'/cache/lang.php', 'w');
				fwrite($fp, "<?php\n//\tHelios Language Pack Links Cache - Delete this file when upgrading.?>\n");
				$langOpt = array();
				$dir = dir(HCPATH.HCINC.'/lang/');
				$x = 0;
				
				while(($file = $dir->read()) != false){
					if(is_dir($dir->path.'/'.$file) && strpos($file,'.') === false && strpos($file,'_') === false)
						$langOpt[] = $file;
				}

				sort($langOpt);
				switch($type){
					case 0:
						foreach($langOpt as $val){
							if($x > 0)
								echo $x % 8 == 0 ? '<br /><br />' : '&nbsp;&nbsp;';
							echo '
				<a href="'.CalRoot.'/lang.php?l='.$val.'" title="'.$val.'" rel="nofollow"><img src="'.CalRoot.'/inc/lang/'.$val.'/icon.png" width="16" height="11" alt="'.$val.'" /></a>';
							++$x;
						}
						break;
					case 1:
						echo '
				<select name="hc_lang" id="hc_lang" onchange="window.location.href=this.value;">';
						foreach($langOpt as $val){
							$sel = ($_SESSION['LangSet'] == $val) ? ' selected="selected"' : '';
							echo '
					<option'.$sel.' value="'.CalRoot.'/lang.php?l='.$val.'">'.ucwords($val).'</option>';
							++$x;
						}
						echo '
				</select>';
						break;
				}

				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
			} else {
				echo $hc_lang_cor['LangError'];
			}
		}
		include(HCPATH.'/cache/lang.php');
	}
	/**
	 * Output an event list. Writes list cache file if not currently available.
	 * @since 2.0.0
	 * @version 2.0.2
	 * @param integer $type list to output, 0 = Billboard Events, 1 = Most Popular Events, 2 = Newest Events (Default:0)
	 * @param integer $eTime [optional] 0 = do NOT include end time, 1 = include end time in list (Default:0)
	 * @return void
	 */
	function event_list($type = 0,$eTime = 0){
		global $hc_cfg, $hc_lang_event;
		
		$bQuery = $uQuery = '';
		switch($type){
			case 0:
				$cf = 'list'.SYSDATE.'_0';
				$noList = $hc_lang_event['NoBillboard'];
				$sQuery = 'e.PkID, e.Title, e.StartDate, e.StartTime, e.IsBillboard, e.SeriesID, e.TBD, e.EndTime';
				$bQuery = ($hc_cfg[13] == 0) ? ' AND e.IsBillboard = 1 ' : '';
				$oQuery = ' ORDER BY IsBillboard DESC, StartDate, StartTime, Title LIMIT '.$hc_cfg[12];
				break;
			case 1:
				$cf = 'list'.SYSDATE.'_1';
				$noList = $hc_lang_event['NoPopular'];
				$sQuery = 'e.PkID, e.Title, e.StartDate, e.StartTime, e.IsBillboard, e.SeriesID, e.TBD, e.EndTime, (e.Views / (DATEDIFF(\''.SYSDATE.'\', e.PublishDate)+1)) as Ave';
				$oQuery = ' ORDER BY AVE DESC, StartDate LIMIT '.$hc_cfg[10];
				break;
			case 2:
				$cf = 'list'.SYSDATE.'_2';
				$noList = $hc_lang_event['NoNewest'];
				$sQuery = 'e.PkID, e.Title, e.StartDate, e.StartTime, e.IsBillboard, e.SeriesID, e.TBD, e.EndTime, e.PublishDate';
				$oQuery = ' ORDER BY PublishDate DESC, StartDate LIMIT '.$hc_cfg[66];
				break;
			default:
				return 0;
		}
		
		if(!file_exists(HCPATH.'/cache/'.$cf)){
			purge_cache_list($type);

			ob_start();
			$fp = fopen(HCPATH.'/cache/'.$cf, 'w');
			$uQuery = ($hc_cfg[33] == 0) ? "  AND SeriesID IS NULL 
					UNION 
					SELECT ".$sQuery." FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID AND e2.StartDate > '".SYSDATE."' AND e.StartDate > e2.StartDate)
					WHERE
						e2.StartDate IS NULL AND 
						e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '".SYSDATE."'  AND e.SeriesID IS NOT NULL ".$bQuery."
					GROUP BY e.SeriesID, e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.IsBillboard, e.Views, e.PublishDate" : '';
			$curDate = $cnt = 0;
			$showHeader = ($type == 0) ? 0 : 1;
			$result = doQuery("SELECT ".$sQuery." FROM " . HC_TblPrefix . "events e WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "'".$bQuery.$uQuery.$oQuery);
			
			if(!hasRows($result)){
				echo $noList;
			} else {	
			echo '
			<ul>';
			while($row = mysql_fetch_row($result)){
				if($row[4] == 0 && $showHeader == 0){
					$showHeader = 1;
					echo '
				<li class="upcoming">' . $hc_lang_event['Upcoming'] . '</li>';
				}

				if($curDate != $row[2]){
					$curDate = $row[2];
					echo '
				<li class="date">'.stampToDate($row[2], $hc_cfg[14]).'</li>';
				}
				
				if($row[6] == 0 && $hc_cfg[15] == 1){
					$time = ($row[3] != '') ? stampToDate($row[3], $hc_cfg[23]) : '';
					$time .= ($eTime == 1 && $row[7] != '') ? ' - ' . stampToDate($row[7], $hc_cfg[23]) : '';
				} elseif($row[6] > 0 && $hc_cfg[15] == 1) {
					$time = ($row[6] == 1) ? $hc_lang_event['AllDay'] : $hc_lang_event['TBA'];}

				echo '
				<li><a href="'.CalRoot . '/index.php?eID='.$row[0].'">'.cOut($row[1]).'</a> '.$time.'</li>';
				++$cnt;
			}
			echo '
			</ul>';
			}
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_clean();
		}
		include(HCPATH.'/cache/'.$cf);
	}
	/**
	 * Delete selected event list cache file, forces file recreation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type cache file to delete, 0 = Billboard Events, 1 = Most Popular Events, 2 = Newest Events (Default:0)
	 * @return void
	 */
	function purge_cache_list($type){
		if(count(glob(HCPATH.'/cache/list*_'.$type)) > 0)
			foreach(glob(HCPATH.'/cache/list*_'.$type) as $file){
				unlink($file);
			}
		purge_mini_cal();
	}
	/**
	 * Delete mini-calendar cache files, forces file recreation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function purge_mini_cal(){
		if(count(glob(HCPATH.'/cache/mcal_*')) > 0)
			foreach(glob(HCPATH.'/cache/mcal_*') as $file){
				unlink($file);
			}
	}
	/**
	 * Get public calendar name (global).
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string calendar name
	 */
	function cal_name(){
		return CalName;
	}
	/**
	 * Get public calendar url (global).
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string calendar url
	 */
	function cal_url(){
		return CalRoot;
	}
	/**
	 * Get active theme URL.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string active theme URL
	 */
	function theme_dir(){
		return CalRoot . '/themes/' . $_SESSION['Theme'];
	}
	/**
	 * Output breadcrumb navigation for current page.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param array $crumbs array of breadcrumbs ($link => $text)
	 * @param integer $limit [optional] text length limit, crumb text truncated to not exceed this length (Default:50)
	 * @return datatype description
	 */
	function build_breadcrumb($crumbs,$limit = 50){		
		if(!is_array($crumbs))
			return 0;
		echo '
	<ul class="breadbox">
		<li id="bread_top"><a href="#top" rel="nofollow">&uarr;</a></li>';
		foreach($crumbs as $link => $text){
			echo ($link != 'NULL') ? '
		<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.$link.'" itemprop="url"><span itemprop="title">'.clean_truncate($text,$limit).'</span></a><span class="arrow"><span>&nbsp;</span></span></li>' : '
		<li class="empty">'.$text.'</li>';
		}
		echo '
	</ul>';
	}
	/**
	 * Include theme header file (header.php)
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $title [optional] page title (only used for location & event page, all other pages use SEO settings)
	 * @param string $desc [optional] meta tag description (only used for location & event page, all other pages use SEO settings)
	 * @return void
	 */
	function get_header(){
		global $eID, $sID, $lID, $hc_meta, $meta, $hc_lang_core, $title, $desc;
		
		switch(HCCOM){
			case 'searchresult':
			case 'newsletter':
			case 'archive':
			case 'tools':
			case 'signup':
			case 'filter':
			case 'search':
			case 'submit':
			case 'digest':
			case 'signin':
			case 'acc':
				$meta = array_map('cOut',$hc_meta[HCCOM]);
				break;
			case 'send':
				$meta = array_map('cOut',$hc_meta[HCCOM]);
				$meta['title'] .= $title;
				break;
			case 'rsvp':
				$meta = array_map('cOut',$hc_meta[HCCOM]);
				$meta['title'] .= $title;
				break;
			case 'edit':
				$meta = array_map('cOut',$hc_meta['signup']);
				break;
			case 'location':
				array_map('cOut',$hc_meta[2]);
				if($lID > 0)
					$meta = array('title' => strip_tags($title), 'keywords' => $hc_meta[2]['keywords'], 'desc' => str_replace('"',"'",cleanBreaks(strip_tags($desc))));
				else
					$meta = $hc_meta[2];
				break;
			case 'serieslist':
			case 'detail':
			default:
				array_map('cOut',$hc_meta[1]);
				if($eID > 0 || $sID != '')
					$meta = array('title' => strip_tags($title), 'keywords' => $hc_meta[1]['keywords'], 'desc' => str_replace('"',"'",cleanBreaks(strip_tags($desc))));
				else
					$meta = $hc_meta[1];
		}
		
		if(!file_exists(HCPATH . '/themes/' . $_SESSION['Theme'].'/header.php')){
			echo '<b>'.$_SESSION['Theme'].'</b> '.$hc_lang_core['Missing'].' <i>header.php</i>';
			exit(-1);}
		include_once(HCPATH . '/themes/' . $_SESSION['Theme'] . '/header.php');
	}
	/**
	 * Include theme footer file (footer.php)
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function get_footer(){
		global $hc_lang_core;
		
		if(!file_exists(HCPATH . '/themes/' . $_SESSION['Theme'].'/footer.php')){
			echo '<b>'.$_SESSION['Theme'].'</b> '.$hc_lang_core['Missing'].' <i>footer.php</i>';
			exit(-1);}
		include_once(HCPATH . '/themes/' . $_SESSION['Theme'] . '/footer.php');
	}
	/**
	 * Include theme side file (side.php, side{$which}.php)
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which [optional] file name addition for themes with multiple side files
	 * @return void
	 */
	function get_side($which = ''){
		global $hc_lang_core;
		
		$which = cIn(strip_tags($which));
		if(!file_exists(HCPATH . '/themes/' . $_SESSION['Theme'].'/side'.$which.'.php')){
			echo '<b>'.$_SESSION['Theme'].'</b> '.$hc_lang_core['Missing'].' <i>side'.$which.'.php</i>';
			exit(-1);}
		include_once(HCPATH . '/themes/' . $_SESSION['Theme'] . '/side'. $which . '.php');
	}
	/**
	 * Include theme file
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $file [required] filename of theme file to include
	 * @return void
	 */
	function load_theme_page($file){
		global $crmbAdd, $hc_cfg, $hc_lang_core, $hc_cfg_named;
		
		if(!file_exists(HCPATH . '/themes/' . $_SESSION['Theme'].'/'.$file)){
			echo '<b>'.$_SESSION['Theme'].'</b> '.$hc_lang_core['Missing'].' <i>'.$file.'</i>';
			return 0;}
		include_once(HCPATH . '/themes/' . $_SESSION['Theme'].'/'.$file);
	}
	/**
	 * Set number of columns for category output.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $num number of columns
	 * @return void
	 */
	function set_cat_cols($num){
		global $hc_cfg;
		if(!is_numeric($num) || $num < 1)
			return 0;
		$hc_cfg['CatCols'] = $num;
	}
	/**
	 * Set switch to show/hide Select/Deselect All Category link options.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $show 0 = Hide Links, 1 = Show Links (Default:1)
	 * @return void
	 */
	function set_cat_links($show = 1){
		global $hc_cfg;
		if(!is_numeric($show) || $show > 1 || $show < 0)
			return 0;
		$hc_cfg['CatLinks'] = $show;
	}
	/**
	 * Set active CAPTCHA type, overrides CAPTCHA setting for current page execution.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type 0 = turn CAPTCHA off, 1 = Helios Native, 2 = reCAPTCHA
	 * @return void
	 */
	function set_captcha($type){
		global $hc_cfg;
		if(!is_numeric($type) || $type > 2 || $type < 0)
			return 0;
		$hc_cfg[65] = $type;
	}
	/**
	 * Set editor status, overrides WYSIWYG editor setting for current page execution.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $status 0 = disable editor (plain textarea), 1 = activate TinyMCE
	 * @return void
	 */
	function set_editor($status){
		global $hc_cfg;
		if(!is_numeric($status) || $status > 1 || $status < 0)
			return 0;
		$hc_cfg[30] = $status;
	}
	/**
	 * Set RSVP/Registration inputs status for event submission form. When hidden users will be unable to select registration options for their event submissions.
	 * @since 2.0.2
	 * @version 2.0.2
	 * @param integer $status 0 = disabled (registration options hidden), 1 = activate (registration options visible)
	 * @return void
	 */
	function set_RSVP($status){
		global $hc_cfg;
		if(!is_numeric($status) || $status > 1 || $status < 0)
			return 0;
		$hc_cfg['IsRSVP'] = $status;
	}
	/**
	 * Get authorized twitter username.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string currently authorized twitter account username
	 */
	function get_twitter(){
		global $hc_cfg;
		return $hc_cfg[63];
	}
	/**
	 * Output RSS feed and iCalendar subscription links.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function theme_links(){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[106] == 0 && $hc_cfg[108] == 0)
			return 0;
		
		$cmnt = ($hc_cfg[106] == 1 && $hc_cfg[56] == 1 && $hc_cfg[25] != '') ? '<li><a href="http://' . $hc_cfg[25] . '.disqus.com/latest.rss" class="icon rss" rel="nofollow">'.$hc_lang_core['Comments'].'</a></li>': '';
		$feeds = ($hc_cfg[106] == 1) ? '
			<li><a href="' . CalRoot . '/rss/" class="icon rss" rel="nofollow">'.$hc_lang_core['All'].'</a></li>
			<li><a href="' . CalRoot . '/rss/?s=1" class="icon rss" rel="nofollow">'.$hc_lang_core['Newest'].'</a></li>
			<li><a href="' . CalRoot . '/rss/?s=3" class="icon rss" rel="nofollow">'.$hc_lang_core['Featured'].'</a></li>
			<li><a href="' . CalRoot . '/rss/?s=2" class="icon rss" rel="nofollow">'.$hc_lang_core['Popular'].'</a></li>':'';		
		$ical = ($hc_cfg[108] == 1) ? '
			<li><a href="webcal://'.substr(CalRoot, 7).'/link/ical.php" class="icon ical" rel="nofollow">'.$hc_lang_core['Subscribe'].'</a></li>':'';
		
		echo '
		<ul class="links">
			'.$feeds.'
			'.$cmnt.'
			'.$ical.'
		</ul>';
	}
	/**
	 * Get mobile touch icon appropriate for user device (iPhone/Other).
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string URL of icon
	 */
	function mobile_icon(){
		return preg_match('/iP[hone|ad|od]/i', $_SERVER['HTTP_USER_AGENT']) ? theme_dir().'/img/touch/iOS.png' : theme_dir().'/img/touch/other.png';
	}
	/**
	 * Output mini search form (keyword only search).
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param string $holder placeholder for input textbox
	 * @param integer $button [optional] 0 = do NOT output button, 1 = include "search events" form submit button (Default:0)
	 * @return void
	 */
	function mini_search($holder = '',$button = 0){
		global $hc_lang_core;
		echo '
		<form name="hc_search" id="hc_search" method="post" action="'.CalRoot.'/index.php?com=searchresult">
			<span><a href="'.CalRoot.'/index.php?com=search" rel="nofollow">'.$hc_lang_core['AdvSearch'].'</a></span>
			<input type="search" name="hc_search_keyword" id="hc_search_keyword" value=""'.(($holder != '') ? ' placeholder="'.$holder.'"':'').' required="required" speech x-webkit-speech />
			'.(($button == 1) ? '<input type="submit" name="hc_search_submit" id="hc_search_submit" value="'.$hc_lang_core['GoSearch'].'" />':'').'
		</form>';
	}
	/**
	 * Get currently active com (component) to identify what page the user is viewing.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return string unfiltered com URL argument value
	 */
	function get_com(){
		global $com;
		
		if(isset($com))
			return $com;
		else 
			return '';
	}
	/**
	 * Get config file option for active language pack (/inc/lang/<language>/config.php)
	 * @since 2.0.2
	 * @version 2.0.2
	 * @param string $which config array index to fetch.
	 * @return string config file setting
	 */
	function get_lang_config($which){
		global $hc_lang_config;
		
		if(!array_key_exists($which,$hc_lang_config))
			return;
		
		return $hc_lang_config[$which];
	}
	
	
	/**
	 * Deprecated. Use clean_truncate instead.
	 */
	function crumb_limit($txt,$size){
		if(strlen($txt) <= $size)
			return $txt;
		
		$space = strpos($txt," ",($size-5));
		return (strlen($txt) > $size) ? substr($txt,0,$space).'...' : $txt;
	}
?>