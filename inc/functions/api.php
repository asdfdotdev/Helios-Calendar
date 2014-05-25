<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Verify API active. If invalid terminate execution and redirect to public calendar homepage with a 301 response.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @return void
	 */
	function api_active(){
		global $hc_cfg;
		
		if($hc_cfg[127] != '1')
			go_home();
	}
	/**
	 * Verify API user authentication. If invalid terminate execution.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $user Username passed to the API via URL argument
	 * @param string $key API Key passed to the API via URL argument
	 * @return void
	 */
	function api_user_authenticate($user = '',$key = ''){
		global $hc_cfg;
		
		$valid = 0;
		$api_users = array();
		
		if(!isset($user) || !isset($key))
			return api_error(2);
		
		if($hc_cfg[128] == 2){
			$api_users_age = (apc_exists(HC_APCPrefix.'users_age')) ? apc_fetch(HC_APCPrefix.'users_age') : 0;
			
			if(apc_exists(HC_APCPrefix.'users'))
				$api_users = apc_fetch(HC_APCPrefix.'users');

			if($api_users_age <= date("U") && count($api_users) > 0){
				apc_user_write_cache($api_users);
				$api_users = array();
			}
			if(array_key_exists($key,$api_users) && $api_users[$key][1] == $user){
				++$api_users[$key][0];
				$count = $api_users[$key];
				unset($api_users[$key]);
				$api_users[$key] = $count;
				$valid = 1;
			} else {
				$result = doQuery("SELECT PkID, NetworkName, APIKey FROM ".HC_TblPrefix."users WHERE NetworkName = '".cIn($user)."' AND APIKey = '".cIn($key)."' AND APIAccess = 1 AND IsBanned = 0");
				
				if(hasRows($result)){
					$api_users[mysql_result($result,0,2)][0] = '1';
					$api_users[mysql_result($result,0,2)][1] = mysql_result($result,0,1);
					$valid = 1;
				}
			}
			if(count($api_users) > $hc_cfg[130]){
				
				$user_keys = array_keys($api_users);
				$remove = array_shift($user_keys);
				$store_cnt = $api_users[$remove][0];
				$store_user = $api_users[$remove][1];
				
				doQuery("UPDATE ".HC_TblPrefix."users SET APICnt = (APICnt + '".cIn($store_cnt)."') WHERE APIKey = '".cIn($remove)."' AND NetworkName = '".cIn($store_user)."'");
				array_shift($api_users);
			}
			
			apc_store(HC_APCPrefix.'users',$api_users);
		} else {
			$result = doQuery("SELECT PkID, NetworkName, APIKey FROM ".HC_TblPrefix."users WHERE NetworkName = '".cIn($user)."' AND APIKey = '".cIn($key)."' AND APIAccess = 1 AND IsBanned = 0");
				
			if(hasRows($result)){
				$valid = 1;
				doQuery("UPDATE ".HC_TblPrefix."users SET APICnt = (APICnt + 1) WHERE APIKey = '".cIn($key)."' AND NetworkName = '".cIn($user)."'");
			}
		}
		
		if($valid == 0)
			return api_error(2);
		else
			return null;
	}
	/**
	 * Create API header for data feed.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $what The API being created (label for API output).
	 * @return array API response header.
	 */
	function api_get_header($what = ''){
		global $hc_lang_config;
		
		return array(	'version' => APIVersion,
					'cal_url'	=> CalRoot,
					'encoding' => $hc_lang_config['CharSet'],
					'generated' => date("c"),
					'contents' => $what);
	}
	/**
	 * Retrieves list of events for API data feed. If cached returned cached data, if not build cache & return it.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $type Type of event list to return, 1 = Current Events, 2 = Billboard, 3 = Most Popular, 4 = Newest DEFAULT:1
	 * @return string JSON encoded string of event data for API response.
	 */
	function api_get_events($type = 0){
		global $hc_cfg, $hc_lang_api;
		
		if($hc_cfg[128] == 2 && apc_exists(HC_APCPrefix.'events'.$type)){
			$events = apc_fetch(HC_APCPrefix.'events'.$type);
		} elseif ($hc_cfg[128] == 1 && file_exists(HCPATH.'/cache/api_events_'.$type.'.php')) {
			include_once(HCPATH.'/cache/api_events_'.$type.'.php');
		} else {
			$x = 0;
			$event_build = array();
			$events = $oQuery = $bQuery = '';
			
			$sQuery = 'e.PkID, e.Title, e.StartDate, e.StartTime, e.EndTime, e.ContactName, e.ContactEmail, e.ContactPhone, 
					e.LocID, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
					e.LocationZip, e.LocCountry, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.Lat, l.Lon,
					e.IsBillboard, e.SeriesID, en.NetworkID, e.TBD, e.PublishDate, e.Image, (e.Views / (DATEDIFF(\''.SYSDATE.'\', e.PublishDate)+1)) as Ave,
					er.Type,
					(SELECT GROUP_CONCAT(c.PkID,\'|\',c.CategoryName ORDER BY c.CategoryName)
						FROM ' . HC_TblPrefix . 'events e2
						    LEFT JOIN ' . HC_TblPrefix . 'eventcategories ec ON (e2.PkID = ec.EventID)
						    LEFT JOIN ' . HC_TblPrefix . 'categories c ON (ec.CategoryID = c.PkID)
						WHERE e2.PkID = e.PkID AND c.IsActive = 1 AND c.CategoryName IS NOT NULL
						GROUP BY e2.PkID) as Categories';
			
			switch($type){
				case 2:
					$oQuery = ' ORDER BY IsBillboard DESC, StartDate, StartTime, Title ';
					$bQuery = ' AND e.IsBillboard = 1 ';
					$event_build['api'] = api_get_header('events_billboard');
					break;
				case 3:
					$oQuery = ' ORDER BY AVE DESC, StartDate ';
					$event_build['api'] = api_get_header('events_popular');
					break;
				case 4:
					$oQuery = ' ORDER BY PublishDate DESC, StartDate ';
					$event_build['api'] = api_get_header('events_new');
					break;
				case 1:
				default:
					$type = 1;
					$oQuery = ' ORDER BY StartDate, TBD, StartTime, Title ';
					$event_build['api'] = api_get_header('events_current');
					break;
			}
						
			$result = doQuery("SELECT " . $sQuery . "
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
								LEFT JOIN " . HC_TblPrefix . "eventnetwork en ON (e.PkID = en.EventID AND en.NetworkType = 2)
							WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "' AND SeriesID IS NULL $bQuery
							UNION
							SELECT $sQuery
							FROM " . HC_TblPrefix . "events e
								LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
								LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
								LEFT JOIN " . HC_TblPrefix . "eventnetwork en ON (e.PkID = en.EventID AND en.NetworkType = 2)
								LEFT JOIN " . HC_TblPrefix . "events e2 ON (e.SeriesID = e2.SeriesID AND e2.StartDate > '".cIn(SYSDATE)."' AND e.StartDate > e2.StartDate)
							WHERE
								e2.StartDate IS NULL AND 
								e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '".cIn(SYSDATE)."'  AND e.SeriesID IS NOT NULL $bQuery $oQuery
							LIMIT ".$hc_cfg[129]);
			
			while($row = mysql_fetch_row($result)){
				$reg_url = '';
				if($row[32] == 1)
					$reg_url = CalRoot.'/index.php?com=rsvp&eID='.$row[0];
				else if($row[32] == 2)
					$reg_url = ($row[27] != '') ? 'http://www.eventbrite.com/event/'.$row[27] : '';
				
				$event_build[$x] = array(
									'id'				=> $row[0],
									'title'			=> $row[1],
									'date_start'		=> date("c", strtotime($row[2].' '.$row[3])),
									'date_end'		=> date("c", strtotime($row[2].' '.$row[4])),
									'venue_id'		=> ($row[8] > 0) ? $row[8] : '0',
									'venue_name'		=> ($row[8] > 0) ? $row[16] : $row[9],
									'venue_add'		=> ($row[8] > 0) ? $row[17] : $row[10],
									'venue_add2'		=> ($row[8] > 0) ? $row[18] : $row[13],
									'venue_city'		=> ($row[8] > 0) ? $row[19] : $row[12],
									'venue_region'		=> ($row[8] > 0) ? $row[20] : $row[13],
									'venue_postal'		=> ($row[8] > 0) ? $row[21] : $row[14],
									'venue_country'	=> ($row[8] > 0) ? $row[22] : $row[15],
									'venue_lat'		=> ($row[8] > 0) ? $row[23] : '',
									'venue_lon'		=> ($row[8] > 0) ? $row[24] : '',
									'contact'			=> $row[5],
									'contact_email'	=> $row[6],
									'contact_phone'	=> $row[7],
									'image'			=> ($row[30] != '') ? $row[30] : '',
									'billboard'		=> ($row[25] == 1) ? 'true' : 'false',
									'series_id'		=> $row[26],
									'registration'		=> ($row[32] != '') ? $hc_lang_api['EventReg'.$row[32]] : $hc_lang_api['EventReg0'],
									'registration_url'	=> $reg_url,
									'categories'		=> catstring_to_array($row[33]),
								    );
				++$x;
			}
			$events = count($event_build) > 1 ? json_encode($event_build) : '';
			
			if($hc_cfg[128] == 2 && $events != ''){
				apc_store(HC_APCPrefix.'events'.$type,$events);
			} elseif($hc_cfg[128] == 1 && $events != '') {
				if(!file_exists(HCPATH.'/cache/api_events_'.$type.'.php')){
					ob_start();
					$fp = fopen(HCPATH.'/cache/api_events_'.$type.'.php', 'w');
					fwrite($fp, "<?php\n//\tHelios API Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$events = \"".str_replace("\"","\\\"",$events)."\";");
					fwrite($fp, "\n?>");
					fclose($fp);
					ob_end_clean();
				}
			}
		}
		
		if($events != '')
			return $events;
		else
			return api_error(3);
	}
	/**
	 * Generate API feed with error message.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $type Type of error message to display, 0 = "Unknown" Catchall, 1 = No Data Selected Default:0
	 * @return string JSON encoded error message for API response.
	 */
	function api_error($type = 0){
		global $hc_cfg, $hc_lang_api;
		
		$error_build = array();
		
		switch($type){
			case 0:
				$error_build['api'] = api_get_header('error_no_selection');
				$error_build['error'] = array('error_id' => '0000', 'msg' => $hc_lang_api['Error000'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			case 1:
				$error_build['api'] = api_get_header('error_invalid_selection');
				$error_build['error'] = array('error_id' => '0001', 'msg' => $hc_lang_api['Error001'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			case 2:
				$error_build['api'] = api_get_header('error_invalid_user');
				$error_build['error'] = array('error_id' => '0002', 'msg' => $hc_lang_api['Error002'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			case 3:
				$error_build['api'] = api_get_header('error_no_event_data');
				$error_build['error'] = array('error_id' => '0003', 'msg' => $hc_lang_api['Error003'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			case 4:
				$error_build['api'] = api_get_header('error_no_category_data');
				$error_build['error'] = array('error_id' => '0004', 'msg' => $hc_lang_api['Error004'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			case 5:
				$error_build['api'] = api_get_header('error_no_newsletter_data');
				$error_build['error'] = array('error_id' => '0005', 'msg' => $hc_lang_api['Error005'], 'help' => $hc_lang_api['ErrorHelpLink'].'');
				break;
			default:
				$error_build['api'] = api_get_header('error_unknown');
				$error_build['error'] = array('error_id' => '0000', 'msg' => $hc_lang_api['ErrorU']);
				break;
		}
		$error = json_encode($error_build);
		
		return $error;
	}
	/**
	 * Retrieves list of categories for API data feed. If cached returned cached data, if not build cache & return it.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $type Type of category list to return, 1 = Hierarchical, 2 = Alphabetical (By Category Name), 3 = Event Count (Highest First) DEFAULT:1
	 * @return string JSON encoded string of category data for API response.
	 */
	function api_get_categories($type = 0){
		global $hc_cfg, $hc_lang_api;
		
		if($hc_cfg[128] == 2 && apc_exists(HC_APCPrefix.'cats'.$type)){
			$categories = apc_fetch(HC_APCPrefix.'cats'.$type);
		} elseif ($hc_cfg[128] == 1 && file_exists(HCPATH.'/cache/api_cats_'.$type.'.php')) {
			include_once(HCPATH.'/cache/api_cats_'.$type.'.php');
		} else {
			$x = 0;
			$y = 1;
			$category_build = array();
			$categories = $oQuery = '';
			
			switch($type){
				case 2:
					$oQuery = ' ORDER BY c.CategoryName';
					$category_build['api'] = api_get_header('categories_alphabetical');
					break;
				case 3:
					$oQuery = ' ORDER BY EventCnt DESC';	
					$category_build['api'] = api_get_header('categories_event_count');
					break;
				case 1:
				default:
					$type = 1;
					$oQuery = ' ORDER BY Path, c.CategoryName';	
					$category_build['api'] = api_get_header('categories_hierarchical');
					break;
			}
			
			$result = doQuery("SELECT c.PkID, c.CategoryName, c.ParentID,
								(SELECT COUNT(eb.PkID)
								    FROM " . HC_TblPrefix . "events eb
								    LEFT JOIN " . HC_TblPrefix . "eventcategories ecb ON (eb.PkID = ecb.EventID)
								    WHERE eb.IsActive = 1 AND eb.IsApproved = 1 AND (eb.StartDate >= '".SYSDATE."') AND ecb.CategoryID = c.PkID) AS EventCnt,
								IF(c.ParentID = 0,CONCAT(c.CategoryName),CONCAT(c2.CategoryName,'_',c.CategoryName)) as Path
							 FROM " . HC_TblPrefix . "categories c
								LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID)
								LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.EventID)
							 WHERE c.IsActive = 1
							 GROUP BY c.PkID, c.CategoryName, c.ParentID, Path $oQuery");
			
			while($row = mysql_fetch_row($result)){
				$reg_url = '';
				
				$y = ($row[2] == 0 && $type <= 1) ? 0 : $y;
								
				$category_build[$x] = array(
									'id'				=> $row[0],
									'name'			=> $row[1],
									'parent_id'		=> $row[2],
									'event_count'		=> $row[3],
									'rank'			=> $y,
								    );
				++$x;
				++$y;
			}
			$categories = count($category_build) > 1 ? json_encode($category_build) : '';
			
			if($hc_cfg[128] == 2 && $categories != ''){
				apc_store(HC_APCPrefix.'cats'.$type,$categories);
			} elseif($hc_cfg[128] == 1 && $categories != '') {
				if(!file_exists(HCPATH.'/cache/api_cats_'.$type.'.php')){
					ob_start();
					$fp = fopen(HCPATH.'/cache/api_cats_'.$type.'.php', 'w');
					fwrite($fp, "<?php\n//\tHelios API Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$categories = \"".str_replace("\"","\\\"",$categories)."\";");
					fwrite($fp, "\n?>");
					fclose($fp);
					ob_end_clean();
				}
			}
		}
				
		if($categories != '')
			return $categories;
		else
			return api_error(4);
	}
	/**
	 * Retrieves list of newsletters for API data feed. If cached returned cached data, if not build cache & return it.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $type Type of category list to return, 1 = Current (Newest First), 2 = Most Popular (By Average Combined Inbox/Archive View Count) DEFAULT:1
	 * @return string JSON encoded string of newsletter data for API response.
	 */
	function api_get_newsletters($type = 0){
		global $hc_cfg, $hc_lang_api;
		
		$newsletters = '';
		
		if($hc_cfg[128] == 2 && apc_exists(HC_APCPrefix.'news')){
			$newsletters = apc_fetch(HC_APCPrefix.'news');
		} elseif ($hc_cfg[128] == 1 && file_exists(HCPATH.'/cache/api_news.php')) {
			include_once(HCPATH.'/cache/api_news.php');
		} else {
			$x = 1;
			$newsletter_build = array();
			$newsletter = '';
			
			switch($type){
				case 2:
					$oQuery = ' ORDER BY AveDaily DESC';
					$newsletter_build['api'] = api_get_header('newsletter_popular');
					break;
				case 1:
				default:
					$oQuery = ' ORDER BY n.SentDate DESC';	
					$newsletter_build['api'] = api_get_header('newsletter_current');
					break;
			}
			
			$result = doQuery("SELECT n.PkID, n.Subject, n.StartDate, n.EndDate, n.SentDate, ((n.Views + n.ArchViews)/DATEDIFF(NOW(),SentDate)) as AveDaily
								FROM " . HC_TblPrefix . "newsletters n
							WHERE n.Status = 3 AND n.IsActive = 1 AND IsArchive = 1 $oQuery
							LIMIT ".$hc_cfg[132]);
			
			while($row = mysql_fetch_row($result)){
				
				$newsletter_build[$x] = array(
									'id'				=> md5($row[0]),
									'subject'			=> $row[1],
									'sent'			=> date("Y-m-d",strtotime($row[4])),
									'events_start'		=> date("Y-m-d",strtotime($row[2])),
									'events_end'		=> date("Y-m-d",strtotime($row[3])),
								    );
				if($type == 2)
					$newsletter_build[$x]['rank'] = $x;
				++$x;
			}
			
			$newsletters = count($newsletter_build) > 1 ? json_encode($newsletter_build) : '';
			
			if($hc_cfg[128] == 2 && $newsletters != ''){
				apc_store(HC_APCPrefix.'news'.$type,$newsletters);
			} elseif($hc_cfg[128] == 1 && $newsletters != '') {
				if(!file_exists(HCPATH.'/cache/api_news_'.$type.'.php')){
					ob_start();
					$fp = fopen(HCPATH.'/cache/api_news_'.$type.'.php', 'w');
					fwrite($fp, "<?php\n//\tHelios API Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$newsletters = \"".str_replace("\"","\\\"",$newsletters)."\";");
					fwrite($fp, "\n?>");
					fclose($fp);
					ob_end_clean();
				}
			}
		}

		if($newsletters != '')
			return $newsletters;
		else
			return api_error(5);
	}
	/**
	 * Build array of category ids/names for use in event API data.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $str string of categories and category ids: (ID1|NAME1,ID2|NAME2,IDN|NAMEN...)
	 * @return array (array index is category id value)
	 */
	function catstring_to_array($str){
		$cat_arr = explode(',',$str);
		$cat_build = array();
		
		foreach($cat_arr as $val){
			if($val != ''){
				$entry = explode('|',$val);
				$cat_build[$entry[0]] = $entry[1];
			}
		}
		
		return $cat_build;
	}
?>
