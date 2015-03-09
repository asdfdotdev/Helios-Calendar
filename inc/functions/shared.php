<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Writes cache files.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param int $cID Cache file to be created 0 = settings.php, 1 = settings_named.php, 2 = locList(a).php, 3 = meta.php, 4 = selCity(a).php, 5 = selPostal(a).php, 6 = Cache Age File (Default:0)
	 * @param int $a [optional] Version of file to create. 0 = public calendar, 1 = admin console (Default:0, Used for $cID = 2/4/5 only.)
	 * @return void
	 */
	function buildCache($cID = 0, $a = 0){
		global $hc_cfg, $hc_lang_search;
		
		if(!is_writable(HCPATH.'/cache/'))
			exit("Cache directory cannot be written to.");
		
		$f = ($a == 1) ? 'a' : '';
		$hc_fetch_settings = '1,2,3,4,7,8,9,10,11,12,13,14,15,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,40,41,42,43,44,45,48,49,';
		$hc_fetch_settings .= '50,51,52,54,53,55,56,59,60,61,62,63,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,';
		$hc_fetch_settings .= '90,91,92,93,94,95,96,97,98,99,100,101,102,103,104,105,106,107,108,109,110,113,114,115,126,127,128,129,130,131,132,133,134';
		
		switch($cID){
			case 1:
				if(defined('HC_Named')){
					if(!file_exists(HCPATH . '/cache/settings_named.php')){
						$result = doQuery("SELECT PkID, Name FROM " . HC_TblPrefix . "settings WHERE PkID IN (".$hc_fetch_settings.") AND SettingValue != '' AND Name IS NOT NULL ORDER BY Name"); 
						if(hasRows($result)){
							ob_start();
							$fp = fopen(HCPATH . '/cache/settings_named.php', 'w');
							fwrite($fp, "<?php\n//\tHelios Named Config Cache - Delete this file when upgrading.\n\n");

							fwrite($fp, "\$hc_cfg_named = array(\n");

							while($row = mysql_fetch_row($result)){
								fwrite($fp, "'" . $row[1] . "'\t=>\t\$hc_cfg[".$row[0]."],\n");
							}
							fwrite($fp, "'category_columns'\t=>\t\$hc_cfg['CatCols'],\n");
							
							fwrite($fp, ");\n?>");
							fclose($fp);
							ob_end_clean();
						} else {
							exit(handleError(0, "Setting Data Missing."));
						}
					}
				}
				break;
			case 2:
				if(!file_exists(HCPATH.'/cache/locList'.$f.'.php')){
					$q = ($a == 0) ? 'AND IsPublic = 1' : '';
					$result = doQuery("SELECT PkID, Name FROM " . HC_TblPrefix . "locations WHERE IsActive = 1 " . $q . " ORDER BY Name");
					ob_start();
					$fp = fopen(HCPATH.'/cache/locList'.$f.'.php', 'w');

					fwrite($fp, "<?php\n//\tHelios Location List - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$NewAll = (isset(\$NewAll)) ? \$NewAll : '';?>\n\n");
					echo '<select name="locListI" id="locListI" onchange="if(isNaN(this.options[this.selectedIndex].value)){splitLocation(this.options[this.selectedIndex].value);}">';
					if(hasRows($result)){
						echo "\n\t" . '<option value="0|"><?php echo $NewAll;?></option>';
						echo "\n\t" . '<option value="-1">-------------------------</option>';
						while($row = mysql_fetch_row($result)){
							echo "\n\t" . '<option value="'.$row[0].'|'.cOut($row[1]).'">' . cOut($row[1]) . '</option>';
						}
					} else {
						echo "\n\t" . '<option value="0">' . $hc_lang_core['NoLocList'] . '</option>';
					}
					echo "\n" . '</select><br />';

					fwrite($fp, ob_get_contents());
					fclose($fp);
					ob_end_clean();
				}
				break;
			case 3:
				if(!file_exists(HCPATH . '/cache/meta.php')){
					ob_start();
					$fp = fopen(HCPATH . '/cache/meta.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Meta Cache - Delete this file when upgrading.\n\n");
					fwrite($fp, "\$hc_meta = array(\n");
					
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settingsmeta");
					$x = 1;
					$pairs = array(1 => 1,2 => 2,3 => 'submit',4 => 'search',5 => 'searchresult',6 => 'signup',7 => 'send',8 => 'rsvp',9 => 'tools',10 => 'rss',
								11 => 'newsletter',12 => 'archive',13 => 'filter', 14 => 'digest', 15 => 'signin', 16 => 'acc');
					
					while($row = mysql_fetch_row($result)){
						fwrite($fp, "\n'".$pairs[$x]."'\t=>\tarray('title' => '".cIn($row[3])."', 'keywords' => '".cIn($row[1])."', 'desc' => '".cIn($row[2])."'),");
						++$x;
					}
					fwrite($fp, "\n);\n?>");
					fwrite($fp, ob_get_contents());
					fclose($fp);
					ob_end_clean();
				}
				break;
			case 4:
				if(!file_exists(HCPATH . '/cache/selCity'.$f.'.php')){
					ob_start();
					$fp = fopen(HCPATH . '/cache/selCity'.$f.'.php', 'w');
					fwrite($fp, "<?php\n//\tHelios City Select List Cache - Delete this file when upgrading.\n?>\n");
					fwrite($fp, "\n<select name=\"city\" id=\"city\" disabled=\"disabled\">");
					fwrite($fp, "\n<option value=\"\">" . $hc_lang_search['City0'] . "</option>");
					
					$cities = getCities($a);
					foreach($cities as $val){
						if($val != '')
							fwrite($fp, "\n<option value=\"" . $val . "\">" . $val . "</option>");
					}
					fwrite($fp, "\n</select>");
					fwrite($fp, ob_get_contents());
					fclose($fp);
					ob_end_clean();
				}
				break;
			case 5:
				if(!file_exists(HCPATH . '/cache/selPostal'.$f.'.php')){
					ob_start();
					$fp = fopen(HCPATH . '/cache/selPostal'.$f.'.php', 'w');
					fwrite($fp, "<?php\n//\tHelios Postal Code Select List Cache - Delete this file when upgrading.\n?>\n");
					fwrite($fp, "\n<select name=\"postal\" id=\"postal\" disabled=\"disabled\">");
					fwrite($fp, "\n<option value=\"\">" . $hc_lang_search['Postal0'] . "</option>");
					
					$codes = getPostal($a);
					foreach($codes as $val){
						if($val != '')
							fwrite($fp, "\n<option value=\"" . $val . "\">" . $val . "</option>");
					}
					fwrite($fp, "\n</select>");
					fwrite($fp, ob_get_contents());
					fclose($fp);
					ob_end_clean();
				}
				break;
			case 6:
				$cache_date = date("Ymd");
				if(!file_exists(HCPATH . '/cache/'.$cache_date.'.php')){
					clearCache();
					ob_start();
					$fp = fopen(HCPATH . '/cache/'.$cache_date.'.php', 'w');
					fwrite($fp, "<?php\n/*\n\tHelios Cache Age File. - Delete this file when upgrading.\n*/\n?>");
					fclose($fp);
					ob_end_clean();
				}
				break;
			default:
				if(!file_exists(HCPATH . '/cache/settings.php')){
					$result = doQuery("SELECT PkID, SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (".$hc_fetch_settings.") ORDER BY PkID");
					if(hasRows($result)){
						ob_start();
						$fp = fopen(HCPATH . '/cache/settings.php', 'w');
						fwrite($fp, "<?php\n//\tHelios Config Cache - Delete this file when upgrading.\n\n");

						fwrite($fp, "\$hc_cfg = array(\n");
						
						while($row = mysql_fetch_row($result)){
							fwrite($fp, $row[0] . " => \"" . str_replace("\"","'",$row[1]) . "\",\n");
						}
						fwrite($fp, "200 => \"hc_" . sha1(md5(CalRoot) . HC_Rando) . "\",\n");
						fwrite($fp, "201 => \"helios_" . md5(CalRoot . HC_Rando) . "\",\n");
						fwrite($fp, "\"CatCols\" => \"3\",\n");
						fwrite($fp, "\"CatLinks\" => \"1\",\n");
						fwrite($fp, "\"IsRSVP\" => \"1\",\n");
						fwrite($fp, "\"OLImages\" => \"".CalRoot."/img/ol/\",\n");
						
						$resultE = doQuery("SELECT MIN(StartDate), MAX(StartDate) FROM " . HC_TblPrefix . "events WHERE IsApproved = 1 AND IsActive = 1");
						if(hasRows($resultE)){
							$first = (strtotime(mysql_result($resultE,0,0)) < date("U",mktime(0,0,0,date("m"),date("d"),date("Y")))) ? strtotime(mysql_result($resultE,0,0)) : date("U",mktime(0,0,0,date("m"),date("d"),date("Y")));
							fwrite($fp, "\"First\" => \"" . $first . "\",\n");
							fwrite($fp, "\"Last\" => \"" . strtotime(mysql_result($resultE,0,1)) . "\",\n");
						}
						
						$news = date("Y-m-d");
						$resultN = doQuery("SELECT MIN(SentDate) FROM " . HC_TblPrefix . "newsletters WHERE STATUS > 0 AND IsArchive = 1 AND IsActive = 1 AND ArchiveContents != ''");
						if(hasRows($resultN) && mysql_result($resultN,0,0) != ''){
							$news = mysql_result($resultN,0,0);
						}
						fwrite($fp, "\"News\" => \"" . $news . "\",\n");

						fwrite($fp, ");\n?>");
						fclose($fp);
						ob_end_clean();
					} else {
						exit(handleError(0, "Setting Data Missing."));
					}
				}
				break;
		}
	}
	/**
	 * Delete cache files. Filters for filenames starting with a period. Generates redirect index.html file to prevent cache directory browsing.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function clearCache(){
		global $hc_cfg;
		
		foreach(glob(HCPATH.'/cache/*') as $filename) {
			if(substr(basename($filename), 0, 1) != '.')
				unlink($filename);
		}
		
		$fp = fopen(HCPATH . '/cache/index.html', 'w');
		fwrite($fp, "<html><head><title></title><META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=../\"></head><body></body></html>");
		fclose($fp);
		
		if(function_exists('apc_clear_cache') && $hc_cfg[128] == 2){
			$api_users = (apc_exists(HC_APCPrefix.'users')) ? apc_fetch(HC_APCPrefix.'users') : array();
			apc_user_write_cache($api_users);
			
			$iterator = new APCIterator('user', NULL, APC_ITER_KEY);
			foreach ($iterator as $key => $data) {
				if(preg_match('/^'.HC_APCPrefix.'/',$key))
					apc_delete($key);
			}
		}
	}
	/**
	 * Output headers to prevent browser caching of action pages.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function action_headers(){
		header('Expires: Wed, 3 May 1979 12:34:56 GMT');
		header('Last-Modified: '.date("D, d M Y H:i:s").'GMT');
		header('Pragma: no-cache');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	}
	/**
	 * Require post only access method.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function post_only(){
		if($_SERVER['REQUEST_METHOD'] != 'POST'){
			if(function_exists('http_response_code'))
				http_response_code(405);
			else
				header('HTTP/1.1 405 Method Not Allowed');
			exit;
		}
	}
	/**
	 * Output tweet with current hashtag setting.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $tweet tweet string
	 * @return void
	 */
	function build_tweet($tweet){
		global $hc_cfg;
		
		echo cIn($tweet.' '.$hc_cfg[59]);
	}
	/**
	 * Wrapper for mysql_query() with custom error handling.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $query query string to pass to MySQL server
	 * @return resource MySQL result set
	 */
	function doQuery($query){
		$result = mysql_query($query);
		
		if(!$result)
			handleError(mysql_errno(), mysql_error());
			
		return $result;
	}
	/**
	 * Replace "Smart Quotes", em dash and other web unsafe characters with web safe equivalent.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $value string to be filtered
	 * @param integer $filter [optional] 0 = Maintain Double Quotes, 1 = Replace Double Quotes w/Single Quotes (Default:1)
	 * @return string filtered string
	 */
	function cleanQuotes($value,$filter = 1){
		$badChars = array('/' . chr(145) . '/','/' . chr(146) . '/','/' . chr(147) . '/','/' . chr(148) . '/','/' . chr(151). '/',"/\\" . chr(92) . "/");
		$goodChars = ($filter == 1) ? array("'", "'", "'", "'", '-', "") : array("'", "'", "\"", "\"", '-', "");
		$value = preg_replace($badChars,$goodChars,$value);

		return $value;
	}
	/**
	 * Remove all line breaks and carriage returns, condense string to single line.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $value string to be filtered
	 * @return string filtered string
	 */
	function cleanBreaks($value){
		$badChars = array('/' . chr(10) . '/','/' . chr(13) . '/','/\\\n/','/\\\r/');
		$goodChars = array("", "", "", "");
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}
	/**
	 * Filter special characters that break CSV files in most editors.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $value string to be filtered
	 * @return string filtered string
	 */
	function cleanCSV($value){
		$value = cleanQuotes($value,1);
		
		$badChars = array('/\'/','/,/');
		$goodChars = array("\\'", "\\,");
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}
	/**
	 * Filter value for use in MySQL query to protect against SQL injection and convert text to proper character encoding (for international support). Wrapper for mysql_real_escape_string().
	 * @since 2.0.0
	 * @version 2.0.2
	 * @param string $value string to be filtered
	 * @param integer $filter [optional] 0 = Maintain Double Quotes, 1 = Replace Double Quotes w/Single Quotes (Default:1)
	 * @return string filtered (character converted, if required) string
	 */
	function cIn($value,$filter = 1) {
		global $hc_lang_config;
		
		$value = ($filter == 1) ? str_replace("\"", "'", $value) : $value;
		
		if(defined('CONVERT_CHRSET') && function_exists('mb_convert_encoding'))
			$value = mb_convert_encoding($value, CONVERT_CHRSET, $hc_lang_config['CharSet']);
		
		return mysql_real_escape_string($value);
	}
	/**
	 * Removes escape backslash when present for output to page, wrapper for stripslashes().
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $value string to be filtered
	 * @return string filtered string
	 */
	function cOut($value){
		return stripslashes($value);
	}
	/**
	 * Used by doQuery() to output a more user friendly error dialog (CSS).
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $errNo MySQL error code
	 * @param string $errMsg MySQL error message
	 * @return void
	 */
	function handleError($errNo, $errMsg){
		$report = error_reporting();
		
		if($report == -1){
			switch($errNo){
				case 1046:	//	no database
					echo '
				<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/core.css">
				<div style="width:500px;">';
					feedback(3,'Database is unavailable.<p>Please verify your config file settings are correct. If you have not yet done so, please run the <a href="'.CalRoot.'/setup/">Helios Calendar Setup</a>.</p><hr>MySQL Server Response: '.$errMsg);
					echo '
				</div>';
					exit();
					break;
				case 1146:	//	no table
					echo '
				<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/core.css">
				<div style="width:500px;">';
					feedback(3,'Data table is unavailable.<p>If you have not yet done so, please run the <a href="'.CalRoot.'/setup/">Helios Calendar Setup</a>.</p><hr>MySQL Server Response: '.$errMsg);
					echo '
				</div>';
					exit();
					break;
				default:		//	everything else
					echo '
				<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/core.css">
				<div style="width:500px;">';
					feedback(3,'Unable to process database request.<p>For assistance with this error please contact your Helios Calendar administrator.</p><hr>MySQL Server Response: '.$errMsg);
					echo '
				</div>';
					exit();
					break;
			}
		} else {
			switch($errNo){
				case 1046:	//	no database
				case 1146:	//	no table
				echo '
					<link rel="stylesheet" type="text/css" href="'.CalRoot.'/themes/core.css">
					<p>Please verify your config file settings are correct. If you have not yet done so, please run the <a href="'.CalRoot.'/setup/">Helios Calendar Setup</a>.</p>';
				exit();
			}
		}
	}
	/**
	 * Check MySQL result set for minimum row count, wrapper for mysql_num_rows().
	 * @since 2.0.0
	 * @version 2.1.0
	 * @param resource $result MySQL result set
	 * @param integer $min result set must have at least one more than this many rows (Default:0)
	 * @return boolean row count is greater than $min
	 */
	function hasRows($result,$min = 0){
		if(!$result)
			return;
		
		$chk_row_cnt = mysql_num_rows($result);
		return ($chk_row_cnt > $min) ? true : false;
	}
	/**
	 * Wrapper for TinyMCE. Creates TinyMCE editor for the passed textarea IDs.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param string $width width of the editor. Variable width is recommended. Pass empty string to use CSS override. (TinyMCE v4 Only, use theme CSS classes for v3)
	 * @param integer $admin [optional] 0 = create public style editor, 1 = create admin style editor (Default:0)
	 * @param integer $override [optional] 0 = use default editor setting, 1 = override default setting, force plian textarea & disable editor (Default:0)
	 * @param string $textName comma separated list of textarea ids to convert to TinyMCE editors. (TinyMCE v3 Only, use mce_edit CSS class for v4)
	 * @return void
	 */
	function makeTinyMCE($width, $admin = 0, $override = 0, $textName = ''){
		global $hc_cfg, $hc_lang_config;
		
		if($hc_cfg[30] == 0 || $override == 1)
			return -1;
		
		$style = ($admin == 0) ? CalRoot.'/themes/tinymce.css' : AdminRoot.'/css/tinymce.css';
		
		if($hc_cfg[30] == 2){
		echo '
	<script type="text/javascript" src="'.CalRoot.'/inc/tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
	tinymce.init({
		selector : "textarea.mce_edit",
		browser_spellcheck : true,
		theme: "modern",
		toolbar_items_size : "small",'.($width != '' ? '
		width: "'.$width.'",':'').'
		plugins: [
			"'.($admin == 1 ? 'media ':'').'advlist anchor autolink autoresize charmap code colorpicker contextmenu directionality emoticons fullscreen fullpage",
			"hr image insertdatetime layer link lists nonbreaking noneditable pagebreak paste preview print searchreplace tabfocus table template textcolor textpattern",
			"visualblocks visualchars wordcount"
		],
		toolbar1: "'.($admin == 1 ? 'insertfile ':'').'image | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | emoticons",
		extended_valid_elements: ""
			+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name|rel|rev|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
			+"abbr[class|dir<ltr?rtl|id|lang|style|title],"
			+"acronym[class|dir<ltr?rtl|id|id|lang|style|title],"
			+"blockquote[cite|class|dir<ltr?rtl|id|lang|style|title],"
			+"br[class|clear<all?left?none?right|id|style|title],"
			+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|style|title],"
			+"center[class|dir<ltr?rtl|id|lang|style|title],"
			+"cite[class|dir<ltr?rtl|id|lang|style|title],"
			+"dd[class|dir<ltr?rtl|id|lang|style|title],"
			+"del[cite|class|datetime|dir<ltr?rtl|id|lang|style|title],"
			+"dfn[class|dir<ltr?rtl|id|lang|style|title],"
			+"dir[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"dl[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"dt[class|dir<ltr?rtl|id|lang|style|title],"
			+"em/i[class|dir<ltr?rtl|id|lang|style|title],"
			+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
			+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|size|style|title|width],"
			+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height|hspace|id|ismap<ismap|lang|longdesc|name|src|style|title|usemap|vspace|width],"
			+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang|style|title],"
			+"li[class|dir<ltr?rtl|id|lang|style|title|type|value],"
			+"menu[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"ol[class|compact<compact|dir<ltr?rtl|id|lang|start|style|title|type],"
			+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|style|title|width],"
			+"q[cite|class|dir<ltr?rtl|id|lang|style|title],"
			+"small[class|dir<ltr?rtl|id|lang|style|title],"
			+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"strike[class|class|dir<ltr?rtl|id|lang|style|title],"
			+"strong/b[class|dir<ltr?rtl|id|lang|style|title],"
			+"style[dir<ltr?rtl|lang|media|title|type],"
			+"sub[class|dir<ltr?rtl|id|lang|style|title],"
			+"sup[class|dir<ltr?rtl|id|lang|style|title],"
			+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class|dir<ltr?rtl|frame|height|id|lang|rules|style|summary|title|width],"
			+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|rowspan|scope<col?colgroup?row?rowgroup|style|title|valign<baseline?bottom?middle?top|width],"
			+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class|rowspan|dir<ltr?rtl|id|lang|style|title|valign<baseline?bottom?middle?top],"
			+"tt[class|dir<ltr?rtl|id|lang|style|title],"
			+"u[class|dir<ltr?rtl|id|lang|style|title],"
			+"ul[class|compact<compact|dir<ltr?rtl|id|lang|style|title|type],"
			+"var[class|dir<ltr?rtl|id|lang|style|title]",
		relative_urls : false,
		remove_script_host : false,
	});
	</script>
	<link rel="stylesheet" type="text/css" href="'.$style.'">';
	} elseif ($hc_cfg[30] == 1) {
		echo '
	<script src="'.CalRoot.'/inc/tiny_mce/tiny_mce_gzip.js"></script>
	<script>
	//<!--
	tinyMCE_GZ.init({
		plugins :  "advhr,advimage,advlink,advlist,autolink,autoresize,contextmenu,fullscreen,inlinepopups,insertdatetime,layer,media,paste,preview,searchreplace,spellchecker,style,tabfocus,table,visualblocks,visualchars,wordcount,xhtmlxtras",
		theme : "advanced",language : "'.$hc_lang_config['TinyMCELang'].'",disk_cache : true,debug : false});
	tinyMCE.init({
		setup : function(ed) {ed.onInit.add(function() {ed.settings.file_browser_callback = null;});},
		cleanup : true,
		'.(($hc_lang_config['Direction'] == 0) ? 'directionality : "rtl",' : '').
		'mode : "exact",
		editor_selector : "'.$textName.'",
		theme : "advanced",
		language : "'.$hc_lang_config['TinyMCELang'].'",
		skin : "o2k7",
		skin_variant : "silver",
		elements : "'.$textName.'",
		entity_encoding : "raw",
		plugins :  "advhr,advimage,advlink,advlist,autolink,autoresize,contextmenu,emotions,fullscreen,inlinepopups,layer,media,paste,preview,searchreplace,spellchecker,style,tabfocus,table,visualblocks,visualchars,wordcount,xhtmlxtras",
		'.(($admin == 1) ? 'theme_advanced_buttons1 : "fontsizeselect,|,fontselect,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,outdent,indent,sup,sub,|,replace,|,visualchars",
		theme_advanced_buttons2 : "pastetext,pasteword,|,link,unlink,anchor,|,image,insertimage,media,charmap,emotions,|,forecolor,backcolor,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,removeformat,cleanup,|,undo,redo",
		theme_advanced_buttons3 : "tablecontrols,visualaid,insertlayer,moveforward,movebackward,absolute,|,advhr,|,visualblocks,code,|,fullscreen,preview,|,spellchecker,|,help",
		theme_advanced_buttons4 : "",':
		'theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,image,separator,undo,redo,code,spellchecker,pastetext,pasteword,|,preview",
		theme_advanced_buttons2 : "",
		valid_elements : ""
			+"a[accesskey|charset|class|coords|dir<ltr?rtl|href|hreflang|id|lang|name|rel|rev|shape<circle?default?poly?rect|style|tabindex|title|target|type],"
			+"abbr[class|dir<ltr?rtl|id|lang|style|title],"
			+"acronym[class|dir<ltr?rtl|id|id|lang|style|title],"
			+"blockquote[cite|class|dir<ltr?rtl|id|lang|style|title],"
			+"br[class|clear<all?left?none?right|id|style|title],"
			+"caption[align<bottom?left?right?top|class|dir<ltr?rtl|id|lang|style|title],"
			+"center[class|dir<ltr?rtl|id|lang|style|title],"
			+"cite[class|dir<ltr?rtl|id|lang|style|title],"
			+"dd[class|dir<ltr?rtl|id|lang|style|title],"
			+"del[cite|class|datetime|dir<ltr?rtl|id|lang|style|title],"
			+"dfn[class|dir<ltr?rtl|id|lang|style|title],"
			+"dir[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"div[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"dl[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"dt[class|dir<ltr?rtl|id|lang|style|title],"
			+"em/i[class|dir<ltr?rtl|id|lang|style|title],"
			+"font[class|color|dir<ltr?rtl|face|id|lang|size|style|title],"
			+"hr[align<center?left?right|class|dir<ltr?rtl|id|lang|noshade<noshade|size|style|title|width],"
			+"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height|hspace|id|ismap<ismap|lang|longdesc|name|src|style|title|usemap|vspace|width],"
			+"legend[align<bottom?left?right?top|accesskey|class|dir<ltr?rtl|id|lang|style|title],"
			+"li[class|dir<ltr?rtl|id|lang|style|title|type|value],"
			+"menu[class|compact<compact|dir<ltr?rtl|id|lang|style|title],"
			+"ol[class|compact<compact|dir<ltr?rtl|id|lang|start|style|title|type],"
			+"p[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"pre/listing/plaintext/xmp[align|class|dir<ltr?rtl|id|lang|style|title|width],"
			+"q[cite|class|dir<ltr?rtl|id|lang|style|title],"
			+"small[class|dir<ltr?rtl|id|lang|style|title],"
			+"span[align<center?justify?left?right|class|dir<ltr?rtl|id|lang|style|title],"
			+"strike[class|class|dir<ltr?rtl|id|lang|style|title],"
			+"strong/b[class|dir<ltr?rtl|id|lang|style|title],"
			+"style[dir<ltr?rtl|lang|media|title|type],"
			+"sub[class|dir<ltr?rtl|id|lang|style|title],"
			+"sup[class|dir<ltr?rtl|id|lang|style|title],"
			+"table[align<center?left?right|bgcolor|border|cellpadding|cellspacing|class|dir<ltr?rtl|frame|height|id|lang|rules|style|summary|title|width],"
			+"td[abbr|align<center?char?justify?left?right|axis|bgcolor|char|charoff|class|colspan|dir<ltr?rtl|headers|height|id|lang|nowrap<nowrap|rowspan|scope<col?colgroup?row?rowgroup|style|title|valign<baseline?bottom?middle?top|width],"
			+"tr[abbr|align<center?char?justify?left?right|bgcolor|char|charoff|class|rowspan|dir<ltr?rtl|id|lang|style|title|valign<baseline?bottom?middle?top],"
			+"tt[class|dir<ltr?rtl|id|lang|style|title],"
			+"u[class|dir<ltr?rtl|id|lang|style|title],"
			+"ul[class|compact<compact|dir<ltr?rtl|id|lang|style|title|type],"
			+"var[class|dir<ltr?rtl|id|lang|style|title]",').'
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		apply_source_formatting : false,
		relative_urls : false,
		remove_script_host : false,
		spellchecker_languages : "'.$hc_lang_config['TinyMCESpell'].'"
	});
	//-->
	</script>';
		}
	}
	/**
	 * Output feedback dialog.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type 1 = Success, 2 = Warning, 3 = Error (Default: Success).
	 * @param string $fmessage feedback message to ouput in dialog.
	 * @return void
	 */
	function feedback($type, $fmessage){
		$fType = "success";
		$fIcon = "success.png";
		switch($type){
			case 2:
				$fType = "warning";
				break;
			case 3:
				$fType = "error";
				break;
		}
		echo '
		<div'.($type == 1 ? ' id="hc_dialog"':'').' class="feedback '.$fType.'" style="opacity:1;"><img src="'.CalRoot.'/img/feedback/'.$fType.'.png" alt="" />&nbsp;'.$fmessage.'</div>';
	}
	/**
	 * Convert MySQL date (YYYY-MM-DD) or timestamp (YYYY-MM-DD HH:MM:SS) to passed format.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param datetime $timeStamp MySQL timestamp
	 * @param string $dateFormat date format string, accepts string of strftime format parameters.
	 * @return string formatted date.
	 */
	function stampToDate($timeStamp, $dateFormat){
		$theDate = ($timeStamp != '') ? strftime($dateFormat, strtotime($timeStamp)) : '';
		return $theDate;
	}
	/**
	 * Convert Helios date to MySQL timestamp.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $theDate date string
	 * @param string $dateFormat format of passed date, accepts: %d/%m/%y, %m/%d/%y or %y/%m/%d
	 * @return string date in MySQL date format (YYYY-MM-DD)
	 */
	function dateToMySQL($theDate, $dateFormat){
		$theDate = str_replace("%","",$theDate);
		$dateParts = explode('/',$theDate);
		
		if(!isset($dateParts[2]))
			return NULL;
		
		$theDate = NULL;
		switch(strToLower($dateFormat)){
			case '%d/%m/%y':
				if(checkdate($dateParts[1],$dateParts[0],$dateParts[2]))
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[1],$dateParts[0],$dateParts[2]));
				break;
			case '%m/%d/%y' :
				if(checkdate($dateParts[0],$dateParts[1],$dateParts[2]))
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[0],$dateParts[1],$dateParts[2]));
				break;
			case '%y/%m/%d':
				if(checkdate($dateParts[1],$dateParts[2],$dateParts[0]))
					$theDate = strftime("%Y-%m-%d", mktime(0,0,0,$dateParts[1],$dateParts[2],$dateParts[0]));
				break;
		}
		
		return $theDate;
	}
	/**
	 * Output event category checkbox inputs.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $frmName name attribute of form the inputs are to be output for
	 * @param integer $columns number of columns to output
	 * @param string $query [optional] MySQL query to generate category list (Default: NULL = List all categories, unchecked)
	 * @param integer $showLinks [optional] 0 = Do NOT Show Select/Deselect Links, 1 = Show Links (Default:1)
	 * @return void
	 */
	function getCategories($frmName, $columns, $query = NULL, $showLinks = 1){
		global $hc_cfg, $hc_lang_config, $hc_lang_core;
		
		if(!isset($query))
			$query = "SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID)
					WHERE c.ParentID = 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID
					UNION SELECT c.PkID, c.CategoryName, c.ParentID, c2.CategoryName as Sort, NULL as Selected
					FROM " . HC_TblPrefix . "categories c 
						LEFT JOIN " . HC_TblPrefix . "categories c2 ON (c.ParentID = c2.PkID) 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (c.PkID = ec.CategoryID) 
					WHERE c.ParentID > 0 AND c.IsActive = 1
					GROUP BY c.PkID, c.CategoryName, c.ParentID, c2.CategoryName
					ORDER BY Sort, ParentID, CategoryName";
		$result = doQuery($query);
		if(!hasRows($result))
			return 0;
		
		$cnt = 1;
		echo '
			<div class="catCol">';
		while($row = mysql_fetch_row($result)){
			if($cnt > ceil(mysql_num_rows($result) / $columns) && $row[2] == 0){
				echo ($cnt > 1) ? '
			</div>' : '';
				echo '
			<div class="catCol">';
				$cnt = 1;}
			
			$sub = ($row[2] != 0) ? ' class="sub"' : '';
			$check = ($row[4] != '') ? 'checked="checked" ' : '';
			
			echo '
				<label for="catID_' . $row[0] . '"'.$sub.'><input name="catID[]" id="catID_'.$row[0].'" type="checkbox" '.$check.'value="'.$row[0].'" />'.cOut($row[1]).'</label>';
			++$cnt;
		}
		echo '
			</div>';
		
		if($cnt == 0 || $showLinks == 0)
			return 0;
		
		echo '
			<div class="catCtrl">
				[ <a href="javascript:;" onclick="checkAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['SelectAll'] . '</a>
				&nbsp;|&nbsp; <a href="javascript:;" onclick="uncheckAllArray(\'' . $frmName . '\', \'catID[]\');">' . $hc_lang_core['DeselectAll'] . '</a> ]
			</div>';
	}
	/**
	 * Retrieve all location cities. Includes both Custom & Saved Location cities.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type [optional] 0 = cities for active events only, 1 = cities for all events (Default:0)
	 * @return array sorted alphabetically
	 */
	function getCities($type = 0){
		$sqlWhere = ($type == 0) ? " e.StartDate >= '". cIn(SYSDATE) . "' AND " : "";
		$result = doQuery("SELECT e.LocationCity, l.City
						FROM " . HC_TblPrefix . "events as e
							LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
						WHERE " . $sqlWhere . " (e.IsActive = 1 AND e.IsApproved = 1) OR (l.IsActive = 1) GROUP BY LocationCity, City");
		$cities = array();
		while($row = mysql_fetch_row($result)){
			if($row[1] == '')
				$cities[strtolower($row[0] . $row[1])] = $row[0] . $row[1];
			else 
				$cities[strtolower($row[1])] = $row[1];
		}
		array_filter($cities);
		ksort($cities);
		array_unique($cities);
		return $cities;
	}
	/**
	 * Retrieve all location postal codes. Includes both Custom & Saved Location postal codes.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $type [optional] 0 = postal codes for active events only, 1 = postal codes for all events (Default:0)
	 * @return array sorted numerically
	 */
	
	function getPostal($type = 0){
		$sqlWhere = ($type == 0) ? " e.StartDate >= '" . cIn(SYSDATE) . "' AND " : "";
		$result = doQuery("SELECT e.LocationZip, l.Zip
						FROM " . HC_TblPrefix . "events as e
							LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
						WHERE " . $sqlWhere . " (e.IsActive = 1 AND e.IsApproved = 1) OR (l.IsActive = 1) GROUP BY LocationZip, Zip");
		$postal = array();
		while($row = mysql_fetch_row($result)){
			if($row[1] == '')
				$postal[strtolower($row[0] . $row[1])] = $row[0] . $row[1];
			else
				$postal[strtolower($row[1])] = $row[1];
		}

		array_filter($postal);
		ksort($postal);
		array_unique($postal);
		return $postal;
	}
	/**
	 * Escape, using a backslash, common special characters, including: \ ' " and ,
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $value string to filter
	 * @return string filtered string
	 */
	function cleanSpecialChars($value){
		$badChars = array('/&nbsp;/','/\\\\/','/\'/','/"/','/,/');
		$goodChars = array(" ","\\\\\\","\\'","\\\"","\,");
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}
	/**
	 * Filter XML special characters with HTML entity number, including: ndash nbsp & " ' < > $ ? ldquo lsquo rdquo rsquo
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $value string to filter
	 * @param integer $purge [optional] 0 = replace special characters with safe alternative, 1 = remove special characters (Default:0)
	 * @return string filtered string
	 */
	function cleanXMLChars($value, $purge = 0){
		$badChars = array('/&ndash;/','/&nbsp;/','/&(?=[A-Z|a-z|0-9|\s])/','/\"/','/\'/','/</','/>/','/\$/','/\?/','/&ldquo;/','/&lsquo;/','/&rdquo;/','/&rsquo;/');
		$goodChars = ($purge == 0) ?
					array("-"," ","&#38;","&#34;","&#39;","&#60;","&#62;","&#36;","&#63;","&#34;","&#39;","&#34;","&#39;") :
					array("","","","","","","","","","","","","");
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}
	/**
	 * Generate localized address format.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $add address part one
	 * @param string $add2 [optional] address part two (suite/apt./etc.)
	 * @param string $city city
	 * @param string $region [optional] region (state/province/etc.)
	 * @param string $postal zip/postal/post code
	 * @param string $country country
	 * @param integer $addType [optional] 1 = US/Can/Aus format, 0 = European format (Default:1)
	 * @return string formatted address string
	 */
	function buildAddress($add,$add2,$city,$region,$postal,$country,$addType = 1){
		$address = ($addType == 1) ? '[add][add2][city], [region] [postal] [country]' : '[add][add2][postal] [city][country]';
		$address = ($add != '') ? str_replace('[add]',$add.'<br />',$address) : $address;
		$address = ($add2 != '') ? str_replace('[add2]',$add2.'<br />',$address) : $address;
		$address = ($city != '') ? str_replace('[city]',$city,$address) : $address;
		$address = ($region != '') ? str_replace('[region]',$region,$address) : $address;
		$address = ($postal != '') ? str_replace('[postal]',$postal,$address) : $address;
		$address = ($country != '') ? str_replace('[country]','<br />'.$country,$address) : $address;
		$address = preg_replace('/\[+[(a-z|0-9)]*\]+/','',$address);
		
		return $address;
	}
	/**
	 * Retrieve array of XML tags (elements).
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $root_tag XML document tag (element).
	 * @param string $xml XML data to be parsed.
	 * @return array array of tags (elements) and their contents.
	 */
	function xml_elements($root_tag, $xml){
		$found = preg_match_all('#<'.$root_tag.'(?:\s+[^>]+)?>(.*?)</'.$root_tag.'>#s',$xml, $matches, PREG_PATTERN_ORDER);
		
		if ($found != false)
			return $matches[1];
	}
	/**
	 * Retrieve value of an xml tag (element).
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $tag xml document tag (element). Contents of this tag will be returned within the array elements.
	 * @param string $xml xml data to be parsed.
	 * @return array array of sub tags (elements) and their contents.
	 */
	function xml_tag_value($tag, $data) {
		$found = preg_match('#<'.$tag.'(?:\s+[^>]+)?>(.*?)</'.$tag.'>#s', $data, $matches);
		
		if ($found != false)
			return $matches[1];
	}
	/**
	 * Convert XML document to array.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $xml xml document
	 * @return array array of document contents
	 */
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
			}
			$end = strpos($elem[3][$key], "<");
			if($end > 0)
			    $theArry[$key]["value"] = substr($elem[3][$key], 0, $end);
			
			if(preg_match($regElem, $elem[3][$key]))
			    $theArry[$key]["elements"] = xml2array($elem[3][$key]);
			else if ($elem[3][$key])
			    $theArry[$key]["value"] = $elem[3][$key];
		}

		return $theArry;
	}
	/**
	 * Master email function, use for all emails (public calendar & admin console) except newsletter mailings, wrapper for PHPMailer.
	 * @since 2.0.0
	 * @version 2.2.0
	 * @param string $toName name of email recipient
	 * @param string|array $toAddress email address of recipient OR array of recipients array($name => $address)
	 * @param string $subj subject line of the email
	 * @param string $msg message body/contents of the email
	 * @param string $fromName name of email sender
	 * @param string $fromAddress email Address of email sender
	 * @param array $attach Files to attach to email. (Data String, Filename, MIME Type)
	 * @param boolean $debug [optional] true = output PHPMailer() SMTP debug info, false = no output (Default:false)
	 * @return void
	 */
	function reMail($toName,$toAddress,$subj,$msg,$fromName = '',$fromAddress = '',$attach = NULL,$debug = false){
		global $hc_cfg, $hc_lang_core, $hc_lang_config;
		
		if(emailStatus() != 1)
			return -1;

		emailStop();
		
		if($hc_cfg[78] == '' || $hc_cfg[79] == '')
			exit($hc_lang_core['NoEmail']);

		include_once(HCPATH.HCINC.'/phpmailer/class.phpmailer.php');

		$fromName = ($fromName == '') ? $hc_cfg[79] : $fromName;
		$fromAddress = ($fromAddress == '') ? $hc_cfg[78] : $fromAddress;
		$mail = new PHPMailer();
		$host = gethostbynamel('');
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-Version',$hc_cfg[49]));
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-ID',md5($hc_cfg[19])));
		$mail->AddCustomHeader($mail->HeaderLine('X-Helios_Calendar-IP',$host[0]));
		/*	End Edit Restriction
		 */
		$mail->CreateHeader();
		$mail->IsHTML(true);
		$mail->CharSet = (defined('CONVERT_CHRSET') && function_exists('mb_convert_encoding')) ? CONVERT_CHRSET : $hc_lang_config['CharSet'];

		if($attach != NULL){
			$mail->AddStringAttachment("$attach[0]","$attach[1]","base64","$attach[2]");
		}
		
		if(is_array($toAddress)){
			$mail->SingleToArray = $toAddress;
			foreach($toAddress as $name => $address){
				$mail->AddAddress($address,$name);
			}
		} else {
			$mail->AddAddress($toAddress,$toName);
		}

		if($hc_cfg[71] == 1){
			$mail->IsSMTP();
			$mail->SMTPKeepAlive = false;
			$mail->SMTPDebug = $debug;
			$mail->Host = $hc_cfg[72];
			$mail->Port = $hc_cfg[73];
			if($hc_cfg[77] == 1){
				$mail->SMTPAuth = true;
				$mail->Username = $hc_cfg[75];
				$mail->Password = base64_decode($hc_cfg[76]);
			}
			if($hc_cfg[74] == 1){
				$mail->SMTPSecure = "tls";
			} elseif($hc_cfg[74] == 2){
				$mail->SMTPSecure = "ssl";
			}
		} else {
			$mail->IsMail();
		}

		$mail->Sender = $hc_cfg[78];
		$mail->From = $fromAddress;
		$mail->FromName = cOut($fromName);
		$mail->AddReplyTo($fromAddress, $fromName);
		$mail->Subject = $subj;
		$mail->Body = $msg;
		$mail->AltBody = strip_tags(str_replace("<br />", "\n", $msg));
				
		try{
			$mail->Send();
		} catch (phpmailerException $e) {
			exit($e);
		} catch (Exception $e) {
			exit($e);
		}
		if($hc_cfg[71] == 1){$mail->SmtpClose();}
		
		emailGo();
	}
	/**
	 * Output CAPTCHA JavaScript validation based on CAPTCHA setting type and active form.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param datatype $where form to generate CAPTCHA for: 1 = Event Submission, 2 = Email to Friend, 3 = Event RSVP, 4 = Newsletter Sign-up/Edit
	 * @return void
	 */
	function captchaValidation($where){
		global $hc_captchas, $hc_cfg, $hc_lang_core;

		if($hc_cfg[65] == 0 || !in_array($where, $hc_captchas))
			   return 0;
		
		$which = ($hc_cfg[65] == 1) ? 'proof' : 'recaptcha_response_field';
		echo '
		err += reqField(document.getElementById("'.$which.'"),"'.$hc_lang_core['Authentication'].'\n");';
	}
	/**
	 * Output CAPTCHA Challenge based on CAPTCHA setting type.
	 * @since 2.0.0
	 * @version 2.2.0
	 * @param integer $admin Where is the CAPTCHA being used? admin console 1 = hide notice, use proper session & force reCAPTCHA theme, 0 = output notice, use proper session & use reCAPTCHA theme settings (Default:0)
	 * @return void
	 */
	function buildCaptcha($admin = 0){
		global $hc_cfg, $hc_lang_core;
		
		if($hc_cfg[65] == 1){
			if(!function_exists('imagecreate')){
				echo 'imagecreate function unavailable';
				return 0;}
			
			echo ($admin == 0) ? '<label class="blank">&nbsp;</label><p>'.$hc_lang_core['CapNotice'].'</p>' : '';
			echo '
			<label class="blank">&nbsp;</label>
			<img src="'.CalRoot.'/inc/captcha.php'.(($admin == 1) ? '?admin=1':'').'" width="250" alt="" id="cap_img" />
			<br /><br />
			<label for="proof">'.$hc_lang_core['ImageText'].'</label>
			<input onblur="testCAPTCHA();" name="proof" id="proof" type="text" maxlength="8" size="8" value="" required="required" />
			<div id="capChk"><a href="javascript:;" onclick="testCAPTCHA();" tabindex="-1" rel="nofollow">'.$hc_lang_core['Confirm'].'</a></div>';
		} elseif($hc_cfg[65] == 2) {
			if($hc_cfg[67] == ''){
				echo '<b>reCAPTCHA API Key Missing.</b>';
				return 0;}
			
			echo '<label class="blank">&nbsp;</label>';
			include(HCPATH . HCINC .'/api/recaptcha/recaptchalib.php');
			$error = '';
			echo '<script type="text/javascript">
			var RecaptchaOptions = {
				theme : \''.(($admin > 0) ? 'white' : $hc_cfg[90]).'\'
				,lang : \'en\'
			};
			</script>
			'.recaptcha_get_html($hc_cfg[67], $error);
		}
	}
	/**
	 * Test user CAPTCHA response. If response fails exit with notice.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $proof users submitted response to the CAPTCHA
	 * @param string $challenge required response generated by CAPTCHA
	 * @param integer $checkMe form being checked, ensures CAPTCHA is active & required for the submission to be processed, 1 = Event Submission, 2 = Email to Friend, 3 = Event RSVP, 4 = Newsletter Sign-up/Edit
	 * @return void
	 */
	function spamIt($proof, $challenge, $checkMe){
		global $hc_cfg, $hc_lang_core;
		$spam = 0;
		$active = explode(",", $hc_cfg[32]);
		
		if(in_array($checkMe, $active)){
			if($hc_cfg[65] == 1){
				$spam = ($challenge != md5($proof)) ? 1 : 0;
			} elseif($hc_cfg[65] == 2){
				include(HCPATH . HCINC .'/api/recaptcha/recaptchalib.php');
				$resp = recaptcha_check_answer ($hc_cfg[68],$_SERVER["REMOTE_ADDR"],$challenge,$proof);
				if(!$resp->is_valid)
					$spam = 1;
			}
			if($spam != 0)
				exit($hc_lang_core['CAPTCHAFail']);
		}
		return;
	}
	/**
	 * Output JavaScript function permitting user verification of response for Helios Native CAPTCHA.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function testCaptcha(){
		global $hc_lang_core;
		echo '
	function testCAPTCHA(){
		if(document.getElementById("proof").value != "")
			ajxOutput("'.CalRoot.'/inc/captcha_chk.php?capEntered=" + document.getElementById("proof").value, "capChk", "'.CalRoot.'");
		else 
			alert("'.$hc_lang_core['Warn'].'");
	}';
	}
	/**
	 * Verify password meets minimum complexity requirements: 6 characters, 1 numeric, 1 capitalized and 1 non-alphanumeric character.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $pswd password string
	 * @return boolean password meets requirements
	 */
	function validPassword($pswd){
		if(strlen($pswd) < 6)
			return false;
		if(!preg_match('/[0-9]/',$pswd))
			return false;			   
		if(!preg_match('/[A-Z]/',$pswd))
			return false;
		if(!preg_match('/[^a-zA-Z0-9_]/',$pswd))
			return false;
		
		return true;
	}
	/**
	 * Terminate execution and redirect to public calendar homepage with a 301 response.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function go_home(){
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".CalRoot);
		exit();
	}
	/**
	 * Truncate passed string to space nearest limit when size exceeds limit.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param string $txt string to be inspected
	 * @param integer $limit maximum length of $txt before it is truncated
	 * @return string truncated string
	 */
	function clean_truncate($txt,$limit){
		if(strlen($txt) <= $limit)
			return $txt;
		
		$space = strpos($txt," ",($limit-5));
		return ($space > 0) ? substr($txt,0,$space).'...' : substr($txt,0,$limit).'...';
	}
	/**
	 * Filter accented characters from passed string replace with ASCII alternative, wrapper for iconv().
	 * @since 2.1.0
	 * @version 2.1.0
	 * @param string $txt string to be filtered
	 * @return string string with accented characters removed
	 */
	function clean_accents($txt){
		global $hc_lang_config;
		
		if(function_exists('iconv'))
			$txt = str_replace("'", '',iconv($hc_lang_config['CharSet'], 'ASCII//TRANSLIT//IGNORE', $txt));
		
		return $txt;
	}
	/**
	 * Used by reMail(), do not call this function directly.
	 */
	function emailStop(){
		$_SESSION['EmailHalt'] = true;
	}
	/**
	 * Used by reMail(), do not call this function directly.
	 */
	function emailGo(){
		if(isset($_SESSION['EmailHalt']))
			unset($_SESSION['EmailHalt']);
	}
	/**
	 * Used by reMail(), do not call this function directly.
	 */
	function emailStatus(){
		return (isset($_SESSION['EmailHalt'])) ? 0 : 1;
	}
	/**
	 * Write APC user access cache to the database.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @return void
	 */
	function apc_user_write_cache($users = array()){
		global $hc_cfg;
		
		if(count($users) > 0){
			$cases = '';
			foreach($users as $save){
				$cases .= " WHEN '".cIn($save[1])."' THEN (APICnt + ".cIn($save[0]).")";
			}

			doQuery("UPDATE " . HC_TblPrefix . "users SET APICnt = CASE NetworkName $cases ELSE APICnt END");

			$new_age = date("U")+($hc_cfg[131]*60);
			apc_store(HC_APCPrefix.'users_age',$new_age);
			apc_delete(HC_APCPrefix.'users');
		}
	}
	/**
	 * Generate and output form token to reduce CSRF risk.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $return Action to perform with the generated token. 0 = Echo as hidden form input, 1 = Return token value as string. DEFAULT:0
	 * @return void|string If $return is 1 the token string will be returned. Otherwise no return occurs.
	 */
	function set_form_token($return = 0){
		global $hc_cfg;
		
		$token = md5(sha1(AdminRoot.session_id().rand()));
		$_SESSION['CurrentToken'] = $token;
		
		echo ($return == 0) ? '
	<input type="hidden" name="token" id="token" value="'.$token.'" />' : '';
		
		return ($return == 0) ? NULL : $token;
	}
	/**
	 * Check submitted form token against current assigned token.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $chk_token The submitted token, will be checked against the user's current token.
	 * @return boolean Does the token match the user's current assigned token?
	 */
	function check_form_token($chk_token){
		return ($chk_token == $_SESSION['CurrentToken']) ? true : false;
	}
	/**
	 * Create CSV of RSVPs for a given event.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param integer $event_id ID # of the event to retrieve RSVPs for.
	 * @return string RSVP data in CSV format.
	 */
	function fetch_event_rsvp($event_id = 0,$header){
		
		if(!is_numeric($event_id) || $event_id <= 0)
			return NULL;
		
		$result = doQuery("SELECT r.Name, r.Email, r.Phone, r.Address, r.Address2, r.City, r.State, r.Zip, r.RegisteredAt, r.GroupID
						FROM " . HC_TblPrefix . "registrants r
						WHERE EventID = '" . cIn($event_id) . "'
						GROUP BY r.PkID, r.Name, r.Email, r.Phone, r.Address, r.Address2, r.City, r.State, r.Zip, r.RegisteredAt, r.GroupID
						ORDER BY RegisteredAt, GroupID");
		$rsvps = "";
		if(hasRows($result)){
			$rsvps = $header;
			
			while($row = mysql_fetch_row($result)){
				$rsvps .= "\n".'"'.clean_csv($row[0]).'","'.clean_csv($row[1]).'","'.clean_csv($row[2]).'","'.clean_csv($row[3]).'","'.clean_csv($row[4]).'","'.
							clean_csv($row[5]).'","'.clean_csv($row[6]).'","'.clean_csv($row[7]).'","'.clean_csv($row[8]).'","'.clean_csv($row[9]).'"';
			}
		}
		
		return $rsvps;
	}
	/**
	 * Escape special characters for use as CSV content.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $value Content string to be filtered.
	 * @return string filtered string.
	 */
	function clean_csv($value){
		$badChars = array('/"/','/,/','/\'/',);
		$goodChars = array("\\\"", "\\\'", "\\\'");
		$value = preg_replace($badChars,$goodChars,$value);
		
		return $value;
	}	
	/**
	 * Removes illegal characters from a string for use as filename.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @param string $value Filename string to be filtered.
	 * @return string filtered file name safe for use.
	 */
	function clean_filename($value){		
		$badChars = array('/\\\/','/\//','/:/','/[*]/','/\?/','/"/','/</','/>/','/\|/','/\./','/\'/');
		$value = preg_replace($badChars,"",$value);
		
		return $value;
	}
?>