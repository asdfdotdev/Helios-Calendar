<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	if(!defined('isHC')){exit(-1);}
	
	/**
	 * Wrapper to call required function(s) to generate JavaScript validation for current active tool.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function get_tool_validation($which = 0){
		switch($which){
			case 1:
				tool_rss_valid();
				break;
		}
	}
	/**
	 * Generate breadcrumb array for active tool page.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param integer $tool active tool id
	 * @param array $add array of custom tool options
	 * @return array of breadcrumb navigation items
	 */
	function tool_crumb($tool,$add){
		global $hc_lang_tools;
		
		$crmbs[CalRoot.'/index.php?com=tools'] = $hc_lang_tools['Tools'];
		
		switch($tool){
			case 0:
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$crmbs[CalRoot.'/index.php?com=tools&amp;t='.$tool] = $hc_lang_tools['Tools'.$tool];
				break;
			default:
				if(isset($add[$tool]))
					$crmbs[CalRoot.'/index.php?com=tools&t='.$tool] = $add[$tool];
				break;
		}
		return $crmbs;
	}
	/**
	 * Retrieves interface text entry from tools language file.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @param string $which language file array key
	 * @return string language file entry
	 */
	function tool_lang($which){
		global $hc_lang_tools;
		
		if(!array_key_exists($which,$hc_lang_tools))
			return;
		
		return $hc_lang_tools[$which];
	}
	/**
	 * Output tool options list.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @param array $add array of custom tool options
	 * @return void
	 */
	function tool_menu($add = array()){
		global $hc_cfg, $hc_lang_tools;
		
		echo '
	<ul class="tools">
		'.(($hc_cfg[127] == 1) ? '<li><a href="'.CalRoot.'/index.php?com=tools&amp;t=5">'.$hc_lang_tools['Tools5'].'</a>'.$hc_lang_tools['Tools5About'].'</li>':'').'
		'.(($hc_cfg[106] == 1) ? '<li><a href="'.CalRoot.'/index.php?com=tools&amp;t=1">'.$hc_lang_tools['Tools1'].'</a>'.$hc_lang_tools['Tools1About'].'</li>':'').'
		<li><a href="'.CalRoot.'/index.php?com=tools&amp;t=2">'.$hc_lang_tools['Tools2'].'</a>'.$hc_lang_tools['Tools2About'].'</li>
		<li><a href="'.CalRoot.'/index.php?com=tools&amp;t=3">'.$hc_lang_tools['Tools3'].'</a>'.$hc_lang_tools['Tools3About'].'</li>
		<li><a href="'.CalRoot.'/index.php?com=tools&amp;t=4">'.$hc_lang_tools['Tools4'].'</a>'.$hc_lang_tools['Tools4About'].'</li>';
		
		foreach($add as $id => $name)
			echo '
		<li><a href="'.CalRoot.'/index.php?com=tools&amp;t='.$id.'">'.$name.'</a></li>';
		
		echo '
	</ul>';
	}
	/**
	 * Output RSS Feed Builder JavaScript validation.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function tool_rss_valid(){
		global $eID, $lID, $hc_cfg, $hc_lang_sendtofriend, $hc_lang_core;
		echo '
	<script src="'.CalRoot.'/inc/javascript/validation.js"></script>
	<script>
	//<!--
		function updateLink(){
			var catStr = cityStr = "";
			var doBoth = false;
			
			if(validCheckArray("frmEventRSS","catID[]",1,"error") == ""){
				catStr = "l=" + checkUpdateString("frmEventRSS", "catID[]");
				doBoth = true;
			}
			if(validCheckArray("frmEventRSS","cityName[]",1,"error") == ""){
				cityStr = doBoth == true ? "&" : "";
				cityStr += "c=" + checkUpdateString("frmEventRSS","cityName[]");
			}
			document.frmEventRSS.rssLink.value = "'.CalRoot.'/rss/feed.php?" + catStr + cityStr;
		}
	//-->
	</script>';
	}
	/**
	 * Output RSS Feed Builder tool.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function tool_rss(){
		global $hc_cfg, $hc_lang_core, $hc_lang_tools;
		
		if($hc_cfg[106] == 0)
			return 0;
		
		$cmnts = ($hc_cfg[56] == 1 && $hc_cfg[25] != '') ? '<li><a href="http://' . $hc_cfg[25] . '.disqus.com/latest.rss" class="icon rss" rel="nofollow">'.$hc_lang_core['Comments'].'</a></li>': '';
		$cnt = 1;
		$cities = getCities();
		$colWidth = number_format((100 / $hc_cfg['CatCols']), 0);
		$colLimit = ceil(count($cities) / $hc_cfg['CatCols']);
		$city = $category = '';
		$city .= '
		<div class="catCol">';
		foreach($cities as $val){
			if($cnt > ceil(count($cities) / $hc_cfg['CatCols']) && $cnt > 1){
				$city .= '
		</div>
		<div class="catCol">';
				$cnt = 1;}
			if($val != ''){
				$city .= '
			<label for="cityName_'.str_replace(' ','_',$val).'"><input onclick="updateLink();" name="cityName[]" id="cityName_'.str_replace(' ','_',$val).'" type="checkbox" value="'.$val.'" />'.cOut($val).'</label>';
			++$cnt;}
		}
		$city .= '
		</div>';
		
		$result = doQuery("SELECT c.PkID, c.CategoryName, c.ParentID, c.CategoryName as Sort, NULL as Selected
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
						ORDER BY Sort, ParentID, CategoryName");
		if(hasRows($result)){
			$cnt = 1;
			$category .= '
			<div class="catCol">';
			while($row = mysql_fetch_row($result)){
				if($cnt > ceil(mysql_num_rows($result) / $hc_cfg['CatCols']) && $row[2] == 0){
					$category .= ($cnt > 1) ? '
			</div>' : '';
					$category .= '
			<div class="catCol">';
					$cnt = 1;}
				$sub = ($row[2] != 0) ? ' class="sub"' : '';
				$check = ($row[4] != '') ? 'checked="checked" ' : '';
				$category .= '
				<label for="catID_' . $row[0] . '"'.$sub.'><input onclick="updateLink();" name="catID[]" id="catID_'.$row[0].'" type="checkbox" value="'.$row[0].'" />'.cOut($row[1]).'</label>';
				++$cnt;
			}
			$category .= '
			</div>';
		}
		echo '
		<h3>'.$hc_lang_tools['RSSPreset'].'</h3>';
		
		theme_links();
		
		echo '
		<h3>'.$hc_lang_tools['RSSBuilder'].'</h3>
		<p>'.$hc_lang_tools['CreateInstruct'].'</p>
		
		<form name="frmEventRSS" id="frmEventRSS" method="post" action="'.CalRoot.'/index.php?com=rss" target="_blank" onsubmit="return false;">
		<fieldset>
			<legend>'.$hc_lang_tools['Cities'].'</legend>
			'.$city.'
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_tools['Categories'].'</legend>
			'.$category.'
		</fieldset>
		<fieldset>
			<legend>'.$hc_lang_tools['PasteInstruct'].'</legend>
			<input onclick="this.focus();this.select();" readonly="readonly" name="rssLink" id="rssLink" type="text" style="width:95%;" maxlength="200" value="'.CalRoot.'/rss/feed.php" />
		</fieldset>
		<input name="reset" id="reset" type="reset" value="'.$hc_lang_tools['StartOver'].'" />
		</form>';
	}
	/**
	 * Output JavaScript Syndication tool.
	 * @since 2.0.0
	 * @version 2.2.1
	 * @return void
	 */
	function tool_syndication(){
		global $hc_cfg, $hc_lang_tools;
		
		echo '
		<h3>'.$hc_lang_tools['Events'].'</h3>
		<ol>
			<li>'.$hc_lang_tools['Synd1'].'</li>
		</ol>
		
		<h4>'.$hc_lang_tools['Code'].'</h4>
		<textarea style="width:95%;" onfocus="this.select();" wrap="off" rows="10" readonly="readonly">&lt;script src="'.CalRoot.'/js/e.php?s=0&amp;z=10&amp;t=1&amp;c=0"&gt;
//<!--
/*	'.$hc_lang_tools['CodeComment'].'
	s: '.$hc_lang_tools['CodeS'].', 0 = '.$hc_lang_tools['CodeS0'].', 1 = '.$hc_lang_tools['CodeS1'].', 2 = '.$hc_lang_tools['CodeS2'].', 3 = '.$hc_lang_tools['CodeS3'].'
	z: '.$hc_lang_tools['CodeZ'].' '.$hc_cfg[2].'
	t: '.$hc_lang_tools['CodeT'].', 1 = '.$hc_lang_tools['CodeT1'].', 0 = '.$hc_lang_tools['CodeT0'].'
	c: '.$hc_lang_tools['CodeC'].'
		0 = '.$hc_lang_tools['CodeC0'];
		
		$result = doQuery("SELECT PkID, CategoryName FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 ORDER BY CategoryName");
		if(hasRows($result)){
			while($row = mysql_fetch_row($result)){
				echo '
		'.$row[0].' = '.$row[1];
			}
		}
		
		
		echo '
	
	'.$hc_lang_tools['CodeCSS'].' ul, li, li.date, a, time */
//-->
&lt;/script&gt;</textarea>';
		
		if($hc_cfg[69] == 0)
			return 0;
		echo '
		<h3>'.$hc_lang_tools['Map'].'</h3>
		<p>'.$hc_lang_tools['MapNotice']. ' <a href="https://developers.google.com/maps/terms" target="_blank" rel="nofollow">'.$hc_lang_tools['TermsOfService'].'</a>.</p>
		<ol>
			<li>'.$hc_lang_tools['SyndM1'].'</li>
			<li>'.$hc_lang_tools['SyndM2'].'</li>
			<li>'.$hc_lang_tools['SyndM3'].'</li>
		</ol>

		<h4>'.$hc_lang_tools['HeaderCode'].'</h4>
		<textarea style="width:95%;height:55px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">
<link rel="stylesheet" href="'.CalRoot.'/js/m.css" />
<script src="http://maps.google.com/maps/api/js?v='.$hc_cfg[61].'&amp;amp;sensor=false"></script>
<script src="'.CalRoot.'/js/m.php"></script></textarea>

		<h4>'.$hc_lang_tools['BodyCode'].'</h4>
		<textarea style="width:95%;height:30px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">
<body onload="initialize()"></textarea>

		<h4>'.$hc_lang_tools['MapCode'].'</h4>
		<textarea style="width:95%;height:30px;" onfocus="this.select();" wrap="off" rows="15" cols="55" readonly="readonly">
<div id="map_canvas"></div></textarea>';
	}
	/**
	 * Output Mobile tool.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function tool_mobile(){
		global $hc_cfg, $hc_lang_tools;
		echo '
	<p>
		'.$hc_lang_tools['Mobile'].'
	</p>
	<p>
		'.$hc_lang_tools['Mobile2'].'
	</p>
	<p>
		<a href="'.CalRoot.'/?theme='.$hc_cfg[84].'" rel="nofollow">'.$hc_lang_tools['MobileLink'].'</a>
	</p>';
	}
	/**
	 * Output Browser Search tool.
	 * @since 2.0.0
	 * @version 2.0.0
	 * @return void
	 */
	function tool_search(){
		global $hc_lang_tools;
		
		echo '
	<h3>Internet Explorer &amp; Mozilla Firefox</h3>
	<p>'.$hc_lang_tools['SearchIEFox'].'
		<br /><input name="button" id="button" type="button" onclick="window.external.AddSearchProvider(\''.CalRoot.'/opensearch.php\');" value="'.$hc_lang_tools['SearchIEButton'].'" /><br />
	</p>
	<h3>Google Chrome</h3>
	<p>'.$hc_lang_tools['SearchChrome'].'</p>
	'.$hc_lang_tools['SearchChromeList'];
	}
	/**
	 * Output API tool.
	 * @since 2.2.0
	 * @version 2.2.0
	 * @return void
	 */
	function tool_api(){
		global $hc_lang_tools;
		
		echo '
	<h3>'.$hc_lang_tools['OurAPI'].'</h3>
	<p>'.$hc_lang_tools['OurAPIDesc'].'</p>
	<h3>'.$hc_lang_tools['APIAccess'].'</h3>
	<p>'.$hc_lang_tools['APIAccessDesc'].'</p>';
	}
?>