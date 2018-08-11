<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	set_time_limit(600);

	$isAction = 1;
	include('../includes/include.php');
     include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/tools.php');
	checkIt(1);
	
	$catID = $_POST['catID'];
	$catIDWhere = '0,' . implode(',',$_POST['catID']);
	$tID = (isset($_POST['tID']) && is_numeric($_POST['tID'])) ? cIn($_POST['tID']) : 0;
	$mID = (isset($_POST['mID']) && is_numeric($_POST['mID'])) ? cIn($_POST['mID']) : 0;
	
	$expVars = array(
		1 => array('tag'=>'[event_id]', 'field'=>'0'),
		2 => array('tag'=>'[event_title]', 'field'=>'1'),
		3 => array('tag'=>'[event_desc]', 'field'=>'8'),
		4 => array('tag'=>'[event_date]', 'field'=>'9'),
		5 => array('tag'=>'[event_time_start]', 'field'=>'10'),
		6 => array('tag'=>'[event_time_end]', 'field'=>'12'),
		7 => array('tag'=>'[event_cost]', 'field'=>'36'),
		8 => array('tag'=>'[event_billboard]', 'field'=>'18'),
		9 => array('tag'=>'[contact_name]', 'field'=>'13'),
		10 => array('tag'=>'[contact_email]', 'field'=>'14'),
		11 => array('tag'=>'[contact_phone]', 'field'=>'15'),
		12 => array('tag'=>'[contact_url]', 'field'=>'24'),
		13 => array('tag'=>'[space]', 'field'=>'26'),
		14 => array('tag'=>'[loc_name]', 'field'=>'X'),
		15 => array('tag'=>'[loc_address]', 'field'=>'X'),
		16 => array('tag'=>'[loc_address2]', 'field'=>'X'),
		17 => array('tag'=>'[loc_city]', 'field'=>'X'),
		18 => array('tag'=>'[loc_region]', 'field'=>'X'),
		19 => array('tag'=>'[loc_postal]', 'field'=>'X'),
		20 => array('tag'=>'[loc_country]', 'field'=>'X'),
		21 => array('tag'=>'[loc_url]', 'field'=>'48'),
		22 => array('tag'=>'[cal_url]', 'field'=>'X'),
		23 => array('tag'=>'[date_series]', 'field'=>'20'),
		24 => array('tag'=>'[date_unique]', 'field'=>'9'),
		25 => array('tag'=>'[category_unique]', 'field'=>'58'),
		26 => array('tag'=>'[desc_notags]', 'field'=>'8'));
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "templates WHERE IsActive = 1 AND PkID ='" . $tID . "'");
	if(hasRows($result)){
		$content = mysql_result($result,0,2);
		$header = mysql_result($result,0,3);
		$footer = mysql_result($result,0,4);
		$ext = mysql_result($result,0,5);
		$groupBy = mysql_result($result,0,7);
		$sortBy = mysql_result($result,0,8);
		$cleanUp = explode("\n",mysql_result($result,0,9));
		$dateFormat = mysql_result($result,0,10);
		$curDate = '';
		$curCategory = '';
		
		header('Content-Type:text/plain; charset=' . $hc_lang_config['CharSet']);
		if($mID == 2){
			header('Content-Disposition:attachment; filename=' . date("YmdGis") . '_HeliosCalendarOutput.' . $ext);
		}//end if
	
		$query = 	'SELECT e.*, l.*, c.CategoryName, ';
		$query .= 	($groupBy == 3) ? 'MIN(e.StartDate), MAX(e.StartDate)' : 'NULL, NULL';
		$query .=	" FROM " . HC_TblPrefix . "events e
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
						LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
						LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
					 WHERE e.IsActive = 1 AND e.IsApproved = 1 AND
						(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], "/", $hc_cfg24) . "' AND '" . dateToMySQL($_POST['endDate'], "/", $hc_cfg24) . "')
						AND c.IsActive = 1";
		switch($groupBy){
			case 0:
				$query .= " ";
				break;
			case 1:
				$query .= " GROUP BY e.PkID";
				break;
			case 2:
				$query .= " GROUP BY e.Title";
				break;
			case 3:
				$query .= " GROUP BY c.CategoryName, e.Title";
				break;
		}//end switch
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
		}//end switch
		
		$resultE = doQuery($query);
		if(hasRows($resultE)){
			$export = buildIt($header,NULL);
			while($row = mysql_fetch_row($resultE)){
				$export .= buildIt($content,$row);
			}//end while
			$export .= buildIt($footer,NULL);

               $clean = str_replace($cleanUp,"",$export);
               $clean = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $clean);
               $clean = str_replace("|N", "\n", $clean);
               
			echo $clean;
		} else {
			exit($hc_lang_tools['NoExport']);
		}//end if
	} else {
		exit($hc_lang_tools['NoExport']);
	}//end if
	
	function buildIt($content,$row){
		global $expVars, $hc_cfg14, $hc_cfg23, $hc_cfg24, $ext, $curDate, $curCategory, $sortBy, $dateFormat;
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
								$replace = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2]));
								break;
							case 2:
								$replace = timeToAP($row[$expVars[$i]['field']]);
								break;
						}//end switch
					}//end if
					break;
				case '[loc_name]':
					$replace = ($row[35] == 0) ? $row[2] : $row[41];
					break;
				case '[loc_address]':
					$replace = ($row[35] == 0) ? $row[3] : $row[42];
					break;
				case '[loc_address2]':
					$replace = ($row[35] == 0) ? $row[4] : $row[43];
					break;
				case '[loc_city]':
					$replace = ($row[35] == 0) ? $row[5] : $row[44];
					break;
				case '[loc_region]':
					$replace = ($row[35] == 0) ? $row[6] : $row[45];
					break;
				case '[loc_postal]':
					$replace = ($row[35] == 0) ? $row[7] : $row[47];
					break;
				case '[loc_country]':
					$replace = ($row[35] == 0) ? $row[37] : $row[46];
					break;
				case '[loc_url]':
				case '[contact_url]':
					if($row[$expVars[$i]['field']] != 'http://'){
						$replace = $row[$expVars[$i]['field']];
					}//end if
					break;
				case '[cal_url]':
					$replace = CalRoot;
					break;
				case '[date_series]':
					if($row[59] != ''){
                              $replace = ($row[59] != $row[60] && $row[60] != '') ?  stampToDate($row[59],$hc_cfg24) . ' - ' . stampToDate($row[60],$hc_cfg24) : stampToDate($row[59],$hc_cfg24);
					}//end if
					break;
				case '[event_date]':
					if($row[$expVars[$i]['field']] != ''){
						$replace = stampToDate($row[$expVars[$i]['field']],$hc_cfg24);
					}//end if
					break;
				case '[date_unique]':
					if($curDate != $row[$expVars[$i]['field']] && $row[$expVars[$i]['field']] != ''){
						$curDate = $row[$expVars[$i]['field']];
						$curCategory = ($sortBy == 1) ? '' : $curCategory;
						switch($dateFormat){
							case 0:
							case 1:
								$timepart = explode(":", $row[$expVars[$i]['field']]);
								$replace = ($dateFormat == 0) ? stampToDate($row[$expVars[$i]['field']],$hc_cfg14) : stampToDate($row[$expVars[$i]['field']],$hc_cfg24);
								break;
							case 2:
								$replace = stampToDateAP($row[$expVars[$i]['field']],1);
								break;
						}//end switch
					}//end if
					break;
				case '[category_unique]':
					if($curCategory != $row[$expVars[$i]['field']]){
						$curCategory = $row[$expVars[$i]['field']];
						$replace = $row[$expVars[$i]['field']];
						$curDate = ($sortBy == 0) ? '' : $curDate;
					}//end if
					break;
				case '[desc_notags]':
					$replace = strip_tags(str_replace("\t"," ",str_replace("\r","",str_replace("\n","",$row[$expVars[$i]['field']]))));
					break;
				default:
					if($row[$expVars[$i]['field']] != ''){
						$replace = $row[$expVars[$i]['field']];
					}//end if
					break;
			}//end switch
			$built = ($ext == '.csv') ? str_replace($expVars[$i]['tag'],str_replace(",","",$replace),$built) : str_replace($expVars[$i]['tag'],$replace,$built);
			++$i;
		}//end while
		
		return $built;
	}//end buildIt?>