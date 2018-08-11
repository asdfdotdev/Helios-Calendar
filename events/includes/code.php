<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	//  Run Passed Query
	function doQuery($query){
		$result = mysql_query($query);
		
		if(!$result){
               handleError(mysql_errno(), mysql_error());
		}//end if
		return $result;
	}//end doQuery()

	
	//  Clean "Smart Quotes" and Annoying Dash
	function cleanQuotes($value){
		$badChars = array('/' . chr(145) . '/','/' . chr(146) . '/','/' . chr(147) . '/','/' . chr(148) . '/','/' . chr(151). '/',"/\\" . chr(92) . "/");
		$goodChars = array("'", "'", "'", "'", '-', "");
		$value = preg_replace($badChars,$goodChars,$value);

		return $value;
	}//end if

	
	//  Clean Passed Value for Insert
	function cIn($value) {
		$value = str_replace("\"", "'", $value);
          return mysql_real_escape_string("$value");
	}//end cIn()
	
	
	//  Clean Passed Value for Output
	function cOut($value){
		return stripslashes($value);
	}//end cOut()
	
	
	//	Unset All Admin Session Data
	function killAdminSession(){
		global $hc_cfg00;

		//	Purge Known Session
		if(isset($_SESSION[$hc_cfg00 . 'AdminPkID'])){
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = NULL WHERE PkID = '" . cIn($_SESSION[$hc_cfg00 . 'AdminPkID']) . "'");
		}//end if

		//	Kill Admin Session Variables
		unset($_SESSION[$hc_cfg00 . 'AdminLoggedIn']);
		unset($_SESSION[$hc_cfg00 . 'AdminPkID']);
		unset($_SESSION[$hc_cfg00 . 'AdminFirstName']);
		unset($_SESSION[$hc_cfg00 . 'AdminLastName']);
		unset($_SESSION[$hc_cfg00 . 'AdminEmail']);
		unset($_SESSION[$hc_cfg00 . 'Instructions']);
		unset($_SESSION[$hc_cfg00 . 'hc_SessionReset']);
		unset($_SESSION[$hc_cfg00 . 'hc_whoami']);
		
		//	Kill MCImageManager Session Variables
		unset($_SESSION['isLoggedIn']);
		unset($_SESSION['imagemanager.preview.wwwroot']);
		unset($_SESSION['imagemanager.preview.urlprefix']);
		unset($_SESSION['imagemanager.filesystem.rootpath']);
	}//end killAdminSession()
	
	
	function killOIDSession(){
		global $hc_cfg00;
		unset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']);
		unset($_SESSION[$hc_cfg00 . 'hc_OpenIDShortName']);
		unset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn']);
		unset($_SESSION[$hc_cfg00 . 'hc_OpenID']);
	}//end killOIDSession()
	
	
	//	Creates New Session ID
	function startNewSession(){
		global $hc_cfg00;
		$aUser = (isset($_SESSION[$hc_cfg00 . 'AdminPkID'])) ? cIn($_SESSION[$hc_cfg00 . 'AdminPkID']) : 0;
		
		$resultAS = doQuery("SELECT Access FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $aUser . "'");
		$knownSession = (hasRows($resultAS)) ? mysql_result($resultAS,0,0) : NULL;
		if($knownSession != md5(session_id())){
			killAdminSession();
		} else {
			$_SESSION[$hc_cfg00 . 'hc_SessionReset'] = (date("U") + mt_rand(60,600));
		}//end if
		
		$old_session = session_id();
		session_regenerate_id();
		$new_session = session_id();
		session_write_close();
		session_id($new_session);
		session_start();
		$_SESSION[$hc_cfg00 . 'hc_whoami'] = md5($_SERVER['REMOTE_ADDR'] . session_id());

		if(isset($_COOKIE[$old_session])) {
		    setcookie($old_session, '', time()-86400, '/');
		}//end if

		doQuery("UPDATE " . HC_TblPrefix . "admin SET Access = '" . cIn(md5(session_id())) . "' WHERE PkID = '" . $aUser . "'");
	}//end createNewSession()
	
	
	//	Action Page Check
	function checkIt($admin = 0){
		global $hc_cfg00;
		
		if($admin == 1){
			if(isset($_SESSION[$hc_cfg00 . 'AdminLoggedIn'])) {
				return;
			}//end if
			header("Location: " . CalRoot);
			exit;
		} else {
			if(isset($_SERVER['HTTP_REFERER']) && stristr($_SERVER['HTTP_REFERER'], CalRoot)){
				return;
			}//end if
			header("Location: ../");
			exit;
		}//end if
	}// checkIt()
	
	
	//	Handle MySQL Errors
	function handleError($errNo, $errMsg){
		//	uncomment next line for debugging
		//	echo "<br />---------------------------------------<br /><b>Error #:</b> " . $errNo . "<br /><b>Message:</b> " .$errMsg . "<br />---------------------------------------<br /><br />";
		switch($errNo){
			//	no database
			case 1046:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to the Helios database is unavailable.<br /><br />Please verify your globals.php file settings are correct. Specifically the name of the database you are using. If you have not yet done so, please run the <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Calendar Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit();
				break;
			//	no table
			case 1146:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to a required Helios datatable is unavailable.<br /><br />If you have not yet done so, please run the <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Calendar Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit();
				break;
			default:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"<div align=\"left\">Helios was unable to process a database command. The following error was received from the MySQL server.<br /><blockquote>" . $errMsg . "</blockquote><br />If this doesn't make sense to you please copy and paste the message in an email to your<br /><a href=\"mailto:" . CalAdminEmail . "?subject=Helios Error Message\" class=\"main\">Helios Calendar Administrator</a>.</div>");
			?></div><?php
				exit();
				break;
		}//end switch
	}//end handleError()
	
	
	//	Check if query result has any rows
	function hasRows($result){
		$chk_row_cnt = mysql_num_rows($result);
		return ($chk_row_cnt > 0) ? 1 : 0;
	}//end hasRows()
	
	
	//	Make textarea with Tiny MCE controls
	//	Style: simple (used for public, less tools), advanced (used for admin, more tools)
	function makeTinyMCE($textName, $width = "550px", $style = "simple", $passContent = ""){
		global $hc_cfg24, $hc_cfg30;

		echo '<textarea name="' . $textName . '" id="' . $textName . '" style="width:' . $width . ';';
		echo ($hc_cfg30 == 0) ? 'height: 200px;"' : 'height: 350px;"';
		echo ' rows="15" cols="55">' . $passContent . '</textarea>';
	
		if($hc_cfg30 == 1){	?>
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="<?php echo CalRoot;?>/includes/tiny_mce/tiny_mce_gzip.js"></script>
		<script type="text/javascript">
		tinyMCE_GZ.init({
			plugins :  "imagemanager,spellchecker,advhr,advimage,advlink,contextmenu,emotions,fullscreen,inlinepopups,insertdatetime,layer,media,paste,preview,safari,searchreplace,style,table,wordcount,xhtmlxtras",
			themes : "advanced",
			languages : "en",
			disk_cache : true,
			debug : false
		});
		</script>
		<script language="javascript" type="text/javascript">
		//<!--
			tinyMCE.init({
				setup : function(ed) {
				   ed.onInit.add(function() {
					  ed.settings.file_browser_callback = null;
				   });
				},
				cleanup : true,
				mode : "exact",
				editor_selector : "<?php echo $textName;?>",
				theme : "advanced",
				skin : "o2k7",
				skin_variant : "silver",
				elements : "<?php echo $textName;?>",
				entity_encoding : "raw",
				plugins :  "imagemanager,spellchecker,advhr,advimage,advlink,contextmenu,emotions,fullscreen,inlinepopups,insertdatetime,layer,media,paste,preview,safari,searchreplace,style,tabfocus,table,wordcount,xhtmlxtras",
	<?php 	if($style == "advanced"){	?>
				theme_advanced_buttons1 : "fontsizeselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,outdent,indent,|,undo,redo,|,sup,sub,|,cleanup,|,replace",
				theme_advanced_buttons2 : "pastetext,pasteword,|,link,unlink,anchor,|,image,insertimage,media,insertdate,|,forecolor,backcolor,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,removeformat,advhr,charmap",
				theme_advanced_buttons3 : "tablecontrols,visualaid,insertlayer,moveforward,movebackward,absolute,|,code,|,fullscreen,preview,|,spellchecker,|,help,",
				theme_advanced_buttons4 : "",
	<?php 	} else {	?>
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,image,separator,undo,redo,code,spellchecker,pastetext,pasteword",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	<?php 	}//end if	?>
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				theme_advanced_resize_horizontal : false,
				plugin_insertdate_dateFormat : "<?php echo $hc_cfg24;?>",
				apply_source_formatting : false,
				relative_urls : false,
				remove_script_host : false,
				spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"

				
			});
		//-->
		</script>
		<!-- /tinyMCE -->
	<?php
		}//end if
	}//end makeTinyMCE()
	
	
	//	Make feedback box
	function feedback($type, $fmessage){
		switch($type){
			case 2:
				$fType = "warning";
				$fIcon = "iconCaution.png";
				break;
			case 3:
				$fType = "error";
				$fIcon = "iconError.png";
				break;
			default:
				$fType = "info";
				$fIcon = "iconSuccess.png";
		}//end switch	?>
		<div class="<?php echo $fType;?>">
			<img src="<?php echo CalRoot;?>/images/feedback/<?php echo $fIcon;?>" alt="" border="0" style="vertical-align:middle;" />&nbsp;<?php echo $fmessage;?>
		</div>
		<br />
<?php
	}//end feedback
	
	
	//	Make instruction box
	function appInstructions($noSwitch, $codex, $title, $message){
		global $hc_cfg00;
		
		if($_SESSION[$hc_cfg00 . 'Instructions'] == 1){
			$mvIcon = 'iconCollapse';
			$optStyle = '';
			$msgTxt = '<div style="padding: 5px 0px 5px 10px;">' . $message . '</div>';
		} else {
			$mvIcon = 'iconExpand';
			$optStyle = 'style="height: 16px;"';
			$msgTxt = '';
		}//end if
		echo '<div class="instructions" ' . $optStyle . '>';
		echo '<div style="width:90%;float:left;vertical-align:middle;"><b>' . $title . '</b></div>';
		echo '<div style="width:10%;float:left;text-align:right;">';
		echo ($codex != '') ? '<a href="http://www.refreshmy.com/documentation/index.php?title=' . $codex . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconHelp.png" width="16" height="16" alt="" border="0" /></a>' : '';
		echo ($noSwitch == 0) ? '&nbsp;&nbsp;<a href="' . CalAdminRoot . '/' . HC_InstructionsSwitch . '" class="main"><img src="' . CalAdminRoot . '/images/icons/' . $mvIcon . '.png" width="16" height="16" alt="" border="0" /></a>' : '';
		echo '</div>';
		echo $msgTxt;
		echo '</div>';
	}//end instruction()
	
	
	//	Make instruction icon
	function appInstructionsIcon($title, $message){
	?>	<a onmouseover="this.T_TITLE='<?php echo $title;?>';this.T_SHADOWCOLOR='#3D3F3E';return escape('<?php echo $message;?>')" href="javascript:;" class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/icons/iconInfo.png" width="16" height="16" alt="" border="0" style="vertical-align:top;" /></a><?php
	}//end calInstructionsIcon()
	
	
	//	Convert timestamp to passed formated date
	function stampToDate($timeStamp, $dateFormat){
          $stampParts = explode(" ", $timeStamp);
		$dateParts = explode("-", $stampParts[0]);
		
		$theDate = strftime($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
		if(isset($stampParts[1])){
			$timeParts = explode(":", $stampParts[1]);
			if(isset($timeParts[1])){
				$theDate = strftime($dateFormat, mktime($timeParts[0], $timeParts[1], $timeParts[2], $dateParts[1], $dateParts[2], $dateParts[0]));
			}//end if
		}//end if
		
		return $theDate;
	}//end stampToDate()


	//	Convert timestamp to AP Style date
	function stampToDateAP($timeStamp,$useYear = 1){
          $stampParts = explode(" ", $timeStamp);
		$dateParts = explode("-", $stampParts[0]);
		$dateFormat = ($useYear == 1) ? ' %#d, %Y' : ' %#d';

		switch($dateParts[1]){
			case 1:
			case 2:
			case 8:
			case 9:
			case 10:
			case 11:
			case 12:
				$dateFormat = '%b.' . $dateFormat;
				break;
			default:
				$dateFormat = '%B' . $dateFormat;
				break;
		}//end switch

		switch(stampToDate($timeStamp,'%w')){
			case 0:
				$dateFormat = 'Sun., ' . $dateFormat;
				break;
			case 1:
				$dateFormat = 'Mon., ' . $dateFormat;
				break;
			case 2:
				$dateFormat = 'Tues., ' . $dateFormat;
				break;
			case 3:
				$dateFormat = 'Wed., ' . $dateFormat;
				break;
			case 4:
				$dateFormat = 'Thurs., ' . $dateFormat;
				break;
			case 5:
				$dateFormat = 'Fri., ' . $dateFormat;
				break;
			case 6:
				$dateFormat = 'Sat., ' . $dateFormat;
				break;
		}//end if

		$theDate = strftime($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));

		if(isset($stampParts[1])){
			$theDate .= ' ' . timeToAp($stampParts[1]);
		}//end if
		
		return $theDate;
	}//end stampToDate()


	//	Convert time to Associated Press Format
	function timeToAP($timeStamp){
		global $hc_cfg31;
		$timeFormat = ($hc_cfg31 == 12) ? '%#I:%M' : '%H:%M';
		$timeParts = explode(":", $timeStamp);
		switch($timeParts[0]){
			case 12:
			case 00:
				if($timeParts[1] == 00){
					$time = ($timeParts[0] == 12) ? 'Noon' : 'Midnight';
					break;
				}//end if
			default:
				$time = strftime($timeFormat, mktime($timeParts[0], $timeParts[1], 0, date("m"), date("d"), date("Y")));
				if($hc_cfg31 == 12){
					$time .= ($timeParts[0] >= 12) ? ' p.m.' : ' a.m.';
				}//end if
		}//end switch
		return $time;
	}//end timeToAP()
	
	//	Convert date to MySQL format
	function dateToMySQL($theDate, $splitBy, $dateFormat){
		$theDate = str_replace("%","",$theDate);
		$dateParts = explode($splitBy, $theDate);
		switch(strToLower($dateFormat)){
			case '%d/%m/%y':
				$theDate = strftime("%Y-%m-%d", mktime(0, 0, 0, $dateParts[1], $dateParts[0], $dateParts[2]));
				break;
				
			case '%m/%d/%y' :
				$theDate = strftime("%Y-%m-%d", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
				break;
				
			case '%y/%m/%d':
				$theDate = strftime("%Y-%m-%d", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
				break;
		}//end switch
		
		return $theDate;
	}//end dateToMySQL()
	
	
	//	Retrieve Category List
	function getCategories($frmName, $columns, $query = NULL, $showLinks = NULL){
		global $hc_cfg00;
		include('lang/' . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/core.php');
		
		if(!isset($query)){	
			$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
						WHERE c.ParentID = 0 AND c.IsActive = 1
						GROUP BY c.PkID
						UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
						FROM " . HC_TblPrefix . "categories c 
							LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
							LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
						WHERE c.ParentID > 0 AND c.IsActive = 1
						GROUP BY c.PkID 
						ORDER BY Sort, ParentID, CategoryName";
		}//end if
		
		$result = doQuery($query);
		$cnt = 1;
		$colWidth = number_format((100 / $columns), 0);
		echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td valign="top" width="' . $colWidth . '%">';
		while($row = mysql_fetch_row($result)){
			if($cnt > ceil(mysql_num_rows($result) / $columns) && $row[2] == 0){
				echo '</td><td valign="top" width="' . $colWidth . '%">';
				$cnt = 1;
			}//end if
			echo '<label for="catID_' . $row[0] . '" ';
			echo ($row[2] == 0) ? 'class="category"' : 'class="subcategory"';
			echo '><input ';
			echo ($row[4] != '') ? 'checked="checked" ' : '';
			echo 'name="catID[]" id="catID_' . $row[0] . '" type="checkbox" value="' . $row[0] . '" class="noBorderIE" />' . cOut($row[1]) . '</label>';
			++$cnt;
		}//end while
		echo '</td></tr></table>';
		
		if($cnt > 1 && !isset($showLinks)){
			echo '<div style="text-align:right;padding:10px 0px 10px 0px;">';
			echo '[ <a class="eventMain" href="javascript:;" onclick="checkAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['DeselectAll'] . '</a> ]';
			echo '</div>';
		}//end if
	}//end getCategories()
	
	
	function getCities($admin = 0){
		$sqlWhere = "";
		if($admin == 0){
			$sqlWhere = " e.StartDate >= NOW() AND ";
		}//end if
		$result = doQuery("	SELECT e.LocationCity, l.City
							FROM " . HC_TblPrefix . "events as e
								LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
							WHERE " . $sqlWhere . "
								(e.IsActive = 1 AND 
								e.IsApproved = 1)
								OR (l.IsActive = 1)
								GROUP BY LocationCity, City");
		$cities = array();
		while($row = mysql_fetch_row($result)){
			if($row[0] == $row[1]){
				$curCity = $row[0];
			} else {
				$curCity = $row[0] . $row[1];
			}//end if
			if($curCity != ''){
				$cities[strtolower($curCity)] = $curCity;
			}//end if
		}//end while
		ksort($cities);
		$cities = array_unique($cities);
		return $cities;
	}//end getCities()
	
	
	function getPostal($admin = 0){
		$sqlWhere = "";
		if($admin == 0){
			$sqlWhere = " e.StartDate >= NOW() AND ";
		}//end if
		$result = doQuery("	SELECT e.LocationZip, l.Zip
							FROM " . HC_TblPrefix . "events as e
								LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
							WHERE " . $sqlWhere . "
								(e.IsActive = 1 AND 
								e.IsApproved = 1)
								OR (l.IsActive = 1)
								GROUP BY LocationZip, Zip");
		$postal = array();
		while($row = mysql_fetch_row($result)){
			$curPostal = $row[0] . $row[1];
			if($curPostal != ''){
				$postal[strtolower($curPostal)] = $curPostal;
			}//end if
		}//end while
		ksort($postal);
		$postal = array_unique($postal);
		return $postal;
	}//end getpostal()
	
		
	//	Escape special characters
	function cleanSpecialChars($textToClean){
		$textToClean = str_replace("&nbsp;", " ", $textToClean);
		$textToClean = str_replace("\\", "\\\\", $textToClean);
		$textToClean = str_replace("'", "\\'", $textToClean);
		$textToClean = str_replace("\"", "\\\"", $textToClean);
		$textToClean = str_replace(",", "\,", $textToClean);
		return $textToClean;
	}//end cleanSpecialChars()
	
	
	//	Substitute special XML characters with proper replacement
	function cleanXMLChars($textToClean, $remove = 0){
		if($remove == 0){
			$textToClean = str_replace("&ndash;", "-", $textToClean);
			$textToClean = str_replace("&nbsp;", " ", $textToClean);
			$textToClean = str_replace(" & ", " &#38; ", $textToClean);
			$textToClean = str_replace("&", "&#38;", $textToClean);
			$textToClean = str_replace("&amp;", "&#38;", $textToClean);
			$textToClean = str_replace("\"", "&quot;", $textToClean);
			$textToClean = str_replace("'", "&#39;", $textToClean);
			$textToClean = str_replace("<", "&amp;lt;", $textToClean);
			$textToClean = str_replace(">", "&amp;gt;", $textToClean);
			$textToClean = str_replace("$", "&#36;", $textToClean);
			$textToClean = str_replace("?", "&#63;", $textToClean);
			$textToClean = str_replace("&ldquo;", "&quot;", $textToClean);
			$textToClean = str_replace("&lsquo;", "&#39;", $textToClean);
			$textToClean = str_replace("&rdquo;", "&quot;", $textToClean);
			$textToClean = str_replace("&rsquo;", "&#39;", $textToClean);
		} else {
			$textToClean = str_replace("&ndash;", "", $textToClean);
			$textToClean = str_replace(" & ", "", $textToClean);
			$textToClean = str_replace("&", "", $textToClean);
			$textToClean = str_replace("\"", "", $textToClean);
			$textToClean = str_replace("'", "", $textToClean);
			$textToClean = str_replace("<", "", $textToClean);
			$textToClean = str_replace(">", "", $textToClean);
			$textToClean = str_replace("$", "", $textToClean);
			$textToClean = str_replace("?", "", $textToClean);
			$textToClean = str_replace(";", "", $textToClean);
			$textToClean = str_replace(":", "", $textToClean);
			$textToClean = str_replace("&nbsp;", " ", $textToClean);
			$textToClean = str_replace("&ldquo;", "", $textToClean);
			$textToClean = str_replace("&lsquo;", "", $textToClean);
			$textToClean = str_replace("&rdquo;", "", $textToClean);
			$textToClean = str_replace("&rsquo;", "", $textToClean);
		}//end if
		return $textToClean;
	}//end cleanXMLChars()
	
	
	//	Clean email form from fields (prevents additional recipients & urls)
	function cleanEmailHeader($textToClean){
		$find = array('/\r/', '/\n/');
		$textToClean = preg_replace($find, "", $textToClean);
		return $textToClean;
	}//end cleanEmailHeader()
	
	
	//	Clean breaklines & return characters
	function cleanBreaks($textToClean, $html = 0){
		if($html == 0){
			$textToClean = str_replace("\r", "\\n", $textToClean);
			$textToClean = str_replace("\n", "\\n", $textToClean);
			$textToClean = str_replace("<p>", "", $textToClean);
			$textToClean = str_replace("</p>", "\\n", $textToClean);
			$textToClean = str_replace("<br />", "\\n", $textToClean);
			$textToClean = str_replace("<br>", "\\n", $textToClean);
		} else {
			$textToClean = str_replace("\r", "<br />", $textToClean);
			$textToClean = str_replace("\n", "<br />", $textToClean);
			$textToClean = str_replace("<p>", "", $textToClean);
			$textToClean = str_replace("</p>", "<br />", $textToClean);
		}//end if
		return $textToClean;
	}//end cleanBreaks()
	
	
	//	Return the number of days between two passed dates
	function daysDiff($date1, $date2){
		$stampParts1 = explode(" ", $date1);
		$dateParts1 = explode("-", $stampParts1[0]);		
		$stampParts2 = explode(" ", $date2);
		$dateParts2 = explode("-", $stampParts2[0]);
          $date1 = date("U", mktime(0, 0, 0, $dateParts1[1], $dateParts1[2], $dateParts1[0]));
		$date2 = date("U", mktime(0, 0, 0, $dateParts2[1], $dateParts2[2], $dateParts2[0]));

          $secs = ($date1 > $date2) ? $date1 - $date2 : $date2 - $date1;
          $days = number_format(((($secs / 60) / 60) / 24) + 1,0,',','');
		return $days;
	}//end daysDiff()
	
	
	//	Output hidden spam target fields
	function fakeFormFields(){
		echo '<input name="name" id="name" type="text" value="" style="display:none;" />';
		echo '<input name="subject" id="subject" type="text" value="" style="display:none;" />';
		echo '<input name="email" id="email" type="text" value="" style="display:none;" />';
		echo '<input name="phone" id="phone" type="text" value="" style="display:none;" />';
		echo '<input name="message" id="message" type="text" value="" style="display:none;" />';
	}//end fakeFormFields()
	
	
	//	Build CAPTCHA Image
	function buildCaptcha(){
		echo (function_exists('imagecreate')) ?
			'<img src="' . CalRoot . '/includes/captcha.php" width="250" border="0" alt="" />':
			'<span style="font-weight:bold;color:#DC143C;">GD Library Unavailable. Image Cannot Be Created.</span>';
	}//end buildCaptcha()
	
	
	//	CAPTCHA Test Comparison
	function spamIt($proof, $checkMe){
		global $hc_cfg00;
		global $hc_cfg32;
		$spam = 0;
		$active = explode(",", $hc_cfg32);
		
		if(in_array($checkMe, $active)){	
			if(!isset($_SESSION[$hc_cfg00 . 'hc_cap'])){
				$spam = 1;
			} else if($_SESSION[$hc_cfg00 . 'hc_cap'] != md5($proof)){
				$spam = 1;
			}//end if
			
			if($spam != 0){
				exit("Your authentication was entered incorrectly. Please use your browsers back button and try again.");
			}//end if
		}//end if
		return;
	}//end spamIt()


	//	Convert Fetched API XML to Array
	function xml2array($xml) {
		$theArry = array();
		$regElem = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
		$regAtt = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';
		preg_match_all($regElem, $xml, $elem);
		foreach($elem[1] as $key => $val) {
			$theArry[$key]["name"] = $elem[1][$key];
			if($attributes = trim($elem[2][$key])){
				preg_match_all($regAtt, $attributes, $att);
				foreach ($att[1] as $key => $val){$theArry[$key]["attributes"][$att[1][$key]] = $att[2][$key];}
			}//end if
			$end = strpos($elem[3][$key], "<");
			if($end > 0){
			    $theArry[$key]["value"] = substr($elem[3][$key], 0, $end);
			}//end if
			if(preg_match($regElem, $elem[3][$key])){
			    $theArry[$key]["elements"] = xml2array($elem[3][$key]);
			} else if ($elem[3][$key]) {
			    $theArry[$key]["value"] = $elem[3][$key];
			}//end if
		}//end foreach

		return $theArry;
	}//end xml2array()

	
	//	Build Cache Files
	function rebuildCache($cID = 0, $a = 0){
		$prfx = $a == 0 ? '' : '../events/';
		if(!is_writable(realpath($prfx.'cache/'))){
			exit("/events/cache/ cannot be written to. CHMOD cache directory to 0777");
		}//end if
		
		switch($cID){
			case 1:
				//	Tawc Cache Removed
				break;
			case 2:
				if(file_exists(realpath($prfx.'cache/censored.php'))){
					unlink($prfx.'cache/censored.php');
				}//end if
				
				$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 55");
				if(hasRows($result)){
					ob_start();
					$fp = fopen($prfx.'cache/censored.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Censored Words Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$hc_censored_words = array(\n");
					$wordList = explode("\n",mysql_result($result,0,0));
					foreach($wordList as $key => $val){
						fwrite($fp, "$key\t\t=>\t'" . trim($val, "\r\n") . '\',' . "\n");
					}//end foreach
					fwrite($fp, ");?>");
					fclose($fp);
					ob_end_clean();
				}//end if
				break;
			case 3:
				if(file_exists(realpath($prfx.'cache/meta.php'))){
					unlink($prfx.'cache/meta.php');
				}//end if

				ob_start();
				$fp = fopen('cache/meta.php', 'w');
				fwrite($fp, "<?php\n//\tHelios Meta Cache - Delete this file when upgrading.\n");
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settingsmeta");
				while($row = mysql_fetch_row($result)){
					fwrite($fp, "\n\$hc_mk" . $row[0] . " = \"" . $row[1] . "\";");
					fwrite($fp, "\n\$hc_md" . $row[0] . " = \"" . $row[2] . "\";");
					fwrite($fp, "\n\$hc_pt" . $row[0] . " = \"" . $row[3] . "\";");
				}//end while
				fwrite($fp, "\n?>");
				fwrite($fp, ob_get_contents());
				fclose($fp);
				ob_end_clean();
				break;
			default:
				if(file_exists(realpath($prfx.'cache/config.php'))){
					unlink($prfx.'cache/config.php');
				}//end if
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings
									WHERE PkID IN (1,2,3,4,7,8,9,10,11,12,13,14,15,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,40,41,42,43,44,45,49,50,51,52,53,56,59,62)
									ORDER BY PkID");
				if(hasRows($result)){
					ob_start();
					$fp = fopen($prfx.'cache/config.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Config Cache - Delete this file when upgrading.\n\n");
					
					while($row = mysql_fetch_row($result)){
						fwrite($fp, "\$hc_cfg" . $row[0] . " = \"" . $row[1] . "\";\n");
					}//end while
					fwrite($fp, "\$hc_cfg00 = \"" . md5(CalRoot) . "\";\n");
					fwrite($fp, "?>");
					fclose($fp);
					ob_end_clean();
				} else {
					exit(handleError(0, "Helios Settings Data Missing. You will need to run the Helios Setup at /events/setup"));
				}//end if
				break;
		}//end switch
	}//end rebuildCache()	
	
	
	//	Output Safe Mailto Link
	function cleanEmailLink($email){
		$eParts = explode("@", $email);
		echo "\n" . '<script language="JavaScript" type="text/JavaScript">';
		echo "\n" . '//<!--';
		echo "\n" . 'var huh = "' . $eParts[0] . '";';
		echo "\n" . 'var what = "' . $eParts[1] . '";';
		echo "\n" . 'document.write(\'<a href="mail\' + \'to:\' + huh + \'@\' + what + \'" class="eventMain">\' + huh + \'@\' + what + \'</a><br />\');';
		echo "\n" . '//-->';
		echo "\n" . '</script>';
	}//end cleanEmailLink()


	//	Create Properly Formatted Address
	function buildAddress($add,$add2,$city,$region,$postal,$country,$doMicro,$addType){
		$address = ($addType == 1) ? '[add][add2][city][region] [postal][country]' : '[add][add2][postal] [city][country]';

		if($add != ''){
			$address = ($doMicro == 1) ? str_replace('[add]','<div class="street-address">' . $add . '</div>',$address) : str_replace('[add]',$add . '<br />',$address);
		}//end if
		if($add2 != ''){
			$address = str_replace('[add2]',$add2 . '<br />',$address);
		}//end if
		if($city != ''){
			$address = ($doMicro == 1) ? str_replace('[city]','<span class="locality">' . $city . '</span>',$address) : str_replace('[city]',$city,$address);
		}//end if
		if($region != ''){
			$address = ($doMicro == 1) ? str_replace('[region]',', <span class="region">' . $region . '</span> ',$address) : str_replace('[region]',', ' . $region,$address);
		}//end if
		if($postal != ''){
			$address = ($doMicro == 1) ? str_replace('[postal]','<span class="postal-code">' . $postal . '</span>',$address) : str_replace('[postal]',$postal,$address);
		}//end if
		if($country != ''){
			$address = ($doMicro == 1) ? str_replace('[country]','<br /><span class="country-name">' . $country . '</span> ',$address) : str_replace('[country]','<br />' . $country . ' ',$address);
		}//end if

		$address = preg_replace('/\[+[(a-z|0-9)]*\]+/','',$address);
		return $address;
	}//end buildAddress()

	
	//	Censored Words Filter
	function censorWords($text, $badWords){
		return str_replace($badWords, '', $text);
	}//end censorWords()?>