<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	set_time_limit(600);
	
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	include(HCLANG.'/admin/tools.php');
	
	$catID = $_POST['catID'];
	$catIDWhere = '0,' . implode(',',$_POST['catID']);
	$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? cIn(strip_tags($_POST['tID'])) : 0;
	$mID = (isset($_POST['mID']) && is_numeric($_POST['mID'])) ? cIn(strip_tags($_POST['mID'])) : 0;
	
	$expVars = array(
		1 => array('tag'=>'[event_id]', 'field'=>'29'),
		2 => array('tag'=>'[event_title]', 'field'=>'0'),
		3 => array('tag'=>'[event_desc]', 'field'=>'1'),
		4 => array('tag'=>'[event_date]', 'field'=>'30'),
		5 => array('tag'=>'[event_time_start]', 'field'=>'2'),
		6 => array('tag'=>'[event_time_end]', 'field'=>'3'),
		7 => array('tag'=>'[event_cost]', 'field'=>'4'),
		8 => array('tag'=>'[event_billboard]', 'field'=>'5'),
		9 => array('tag'=>'[contact_name]', 'field'=>'6'),
		10 => array('tag'=>'[contact_email]', 'field'=>'7'),
		11 => array('tag'=>'[contact_phone]', 'field'=>'8'),
		12 => array('tag'=>'[contact_url]', 'field'=>'9'),
		13 => array('tag'=>'[space]', 'field'=>'10'),
		14 => array('tag'=>'[loc_name]', 'field'=>'X'),
		15 => array('tag'=>'[loc_address]', 'field'=>'X'),
		16 => array('tag'=>'[loc_address2]', 'field'=>'X'),
		17 => array('tag'=>'[loc_city]', 'field'=>'X'),
		18 => array('tag'=>'[loc_region]', 'field'=>'X'),
		19 => array('tag'=>'[loc_postal]', 'field'=>'X'),
		20 => array('tag'=>'[loc_country]', 'field'=>'X'),
		21 => array('tag'=>'[loc_url]', 'field'=>'26'),
		22 => array('tag'=>'[cal_url]', 'field'=>'X'),
		23 => array('tag'=>'[date_series]', 'field'=>'X'),
		24 => array('tag'=>'[date_unique]', 'field'=>'30'),
		25 => array('tag'=>'[category_unique]', 'field'=>'31'),
		26 => array('tag'=>'[desc_notags]', 'field'=>'1'));
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE IsActive = 1 AND PkID = '" . $tID . "'");
	if(hasRows($result)){
		$content = mysql_result($result,0,2);
		$header = mysql_result($result,0,3);
		$footer = mysql_result($result,0,4);
		$ext = mysql_result($result,0,5);
		$groupBy = mysql_result($result,0,7);
		$sortBy = mysql_result($result,0,8);
		$cleanUp = explode("\n",mysql_result($result,0,9));
		$dateFormat = mysql_result($result,0,10);
		$curDate = $curCategory = '';
		
		header('Content-Type:text/plain; charset=' . $hc_lang_config['CharSet']);
		if($mID == 2)
			header('Content-Disposition:attachment; filename=' . date("YmdGis") . '_HeliosCalendarOutput' . $ext);
		
		$query = 	'SELECT e.Title, e.Description, e.StartTime, e.EndTime, e.Cost, e.IsBillboard, e.ContactName, e.ContactEmail, e.ContactPhone, e.ContactURL, 
						er.Space, e.LocID, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, e.LocationZip, e.LocCountry, 
						l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, ';
		$query .= ($groupBy >= 2) ? 'MIN(e.StartDate), MAX(e.StartDate), NULL, NULL' : 'NULL, NULL, e.PkID, e.StartDate';
		$query .= ($groupBy == 0 || $groupBy == 3) ? ', c.CategoryName ':', NULL';
		$query .=	" FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
					WHERE e.IsActive = 1 AND e.IsApproved = 1 AND
						(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], $hc_cfg[24]) . "' AND '" . dateToMySQL($_POST['endDate'], $hc_cfg[24]) . "')
						AND c.IsActive = 1 AND e.Title IS NOT NULL
					GROUP BY e.Title, e.Description, e.StartTime, e.EndTime, e.Cost, e.IsBillboard, e.ContactName, e.ContactEmail, e.ContactPhone, e.ContactURL, 
							er.Space, e.LocID, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, e.LocationZip, e.LocCountry, 
							l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL";
		switch($groupBy){
			case 0:
				$query .= ", e.PkID, e.StartDate, c.CategoryName";
				break;
			case 1:
				$query .= ", e.PkID, e.StartDate";
				break;
			case 2:
			case 3:
				$query .= ", c.CategoryName";
				break;
		}
		switch($sortBy){
			case 0:
				$query .= " ORDER BY c.CategoryName, e.StartDate, e.Title";
				break;
			case 1:
				$query .= " ORDER BY e.StartDate, c.CategoryName, e.Title";
				break;
			case 2:
				$query .= " ORDER BY e.StartDate, e.Title";
				break;
		}
		
		$resultE = doQuery($query);
		if(hasRows($resultE)){
			$export = buildIt($header,NULL);
			while($row = mysql_fetch_row($resultE)){
				$export .= buildIt($content,$row);
			}
			$export .= buildIt($footer,NULL);

               $clean = str_replace($cleanUp,"",$export);
               $clean = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $clean);
               $clean = str_replace("|N", "\n", $clean);
               
			echo $clean;
		} else {
			exit($hc_lang_tools['NoExport']);
		}
	} else {
		exit($hc_lang_tools['NoExport']);
	}
	
	function buildIt($content,$row){
		global $expVars, $hc_cfg, $ext, $curDate, $curCategory, $sortBy, $dateFormat;
		$built = "";
		$built = $content;
		$i = 1;
		$stop = count($expVars);
		while($i <= $stop){
			$replace = 'BLANK';
			switch($expVars[$i]['tag']){
				case '[event_time_start]':
				case '[event_time_end]':
					if($row[$expVars[$i]['field']] != ''){
						switch($dateFormat){
							case 0:
							case 1:
								$timepart = explode(":", $row[$expVars[$i]['field']]);
								$replace = strftime($hc_cfg[23], mktime($timepart[0], $timepart[1], $timepart[2]));
								break;
							case 2:
								$replace = timeToAP($row[$expVars[$i]['field']]);
								break;
						}
					}
					break;
				case '[loc_name]':
					$replace = ($row[11] == 0) ? $row[12] : $row[19];
					break;
				case '[loc_address]':
					$replace = ($row[11] == 0) ? $row[13] : $row[20];
					break;
				case '[loc_address2]':
					$replace = ($row[11] == 0) ? $row[14] : $row[21];
					break;
				case '[loc_city]':
					$replace = ($row[11] == 0) ? $row[15] : $row[22];
					break;
				case '[loc_region]':
					$replace = ($row[11] == 0) ? $row[16] : $row[23];
					break;
				case '[loc_postal]':
					$replace = ($row[11] == 0) ? $row[17] : $row[24];
					break;
				case '[loc_country]':
					$replace = ($row[11] == 0) ? $row[18] : $row[25];
					break;
				case '[loc_url]':
				case '[contact_url]':
					if($row[$expVars[$i]['field']] != 'http://')
						$replace = $row[$expVars[$i]['field']];
					break;
				case '[cal_url]':
					$replace = CalRoot;
					break;
				case '[date_series]':
					if($row[27] != '')
						$replace = ($row[27] != $row[28] && $row[28] != '') ?  stampToDate($row[27],$hc_cfg[24]) . ' - ' . stampToDate($row[28],$hc_cfg[24]) : stampToDate($row[27],$hc_cfg[24]);
					break;
				case '[event_date]':
					if($row[$expVars[$i]['field']] != ''){
						switch($dateFormat){
							case 0:
							case 1:
								$timepart = explode(":", $row[$expVars[$i]['field']]);
								$replace = ($dateFormat == 0) ? stampToDate($row[$expVars[$i]['field']],$hc_cfg[14]) : stampToDate($row[$expVars[$i]['field']],$hc_cfg[24]);
								break;
							case 2:
								$replace = stampToDateAP($row[$expVars[$i]['field']],1);
								break;
						}
					}
					break;
				case '[date_unique]':
					if($curDate != $row[$expVars[$i]['field']] && $row[$expVars[$i]['field']] != ''){
						$curDate = $row[$expVars[$i]['field']];
						$curCategory = ($sortBy == 1) ? '' : $curCategory;
						switch($dateFormat){
							case 0:
							case 1:
								$timepart = explode(":", $row[$expVars[$i]['field']]);
								$replace = ($dateFormat == 0) ? stampToDate($row[$expVars[$i]['field']],$hc_cfg[14]) : stampToDate($row[$expVars[$i]['field']],$hc_cfg[24]);
								break;
							case 2:
								$replace = stampToDateAP($row[$expVars[$i]['field']],1);
								break;
						}
					}
					break;
				case '[category_unique]':
					if($curCategory != $row[$expVars[$i]['field']]){
						$curCategory = $row[$expVars[$i]['field']];
						$replace = $row[$expVars[$i]['field']];
						$curDate = ($sortBy == 0) ? '' : $curDate;
					}
					break;
				case '[desc_notags]':
					if($row[$expVars[$i]['field']] != '')
						$replace = strip_tags(cleanBreaks($row[$expVars[$i]['field']]));
					break;
				default:
					if($row[$expVars[$i]['field']] != '')
						$replace = cleanBreaks($row[$expVars[$i]['field']]);
					break;
			}
			$built = ($ext == '.csv') ? str_replace($expVars[$i]['tag'],str_replace(",","",$replace),$built) : str_replace($expVars[$i]['tag'],$replace,$built);
			++$i;
		}
		
		return $built;
	}
?>