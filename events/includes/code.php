<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	//	Run passed query
	function doQuery($query){
		$result = mysql_query($query);
		
		if(!$result){
			handleError(mysql_errno(), mysql_error());
		}//end if
		return $result;
	}//end doQuery()
	
	
	//	Clean Passed Value for Insert
	function cIn($value) {
		$value = str_replace("\"", "'", $value);
		
		if (get_magic_quotes_gpc() == 1) {
			return $value;
		} else {
			return addslashes($value);
		}//end if
	}//end cIn()
	
	
	//	Clean Passed Value for Output
	function cOut($value){
		if (get_magic_quotes_gpc() == 1) {
			return $value;
		} else {
			return stripslashes($value);
		}//end if
	}//end cOut()
	
	
	//	Action Page Check
	function checkIt($admin = 0){
		if($admin == 1){
			if(isset($_SESSION['AdminLoggedIn'])) {
				return;
			}//end if
			header("Location: " . CalRoot);
			exit;
		} else {
			if(isset($_SERVER['HTTP_REFERER'])){
				if(strpos($_SERVER['HTTP_REFERER'], CalRoot) !== FALSE) {
					return;
				}//end if
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
			//	no dateabase
			case 1046:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to the Helios database is unavailable.<br /><br />Please verify your globals.php file settings are correct. Specifically the name of the database you are using. If you have not yet done so, please run <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit;
				break;
			//	no table
			case 1146:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"Connection to a required Helios datatable is unavailable.<br /><br />If you have not yet done so, please run <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?php
				exit;
				break;
			default:
			?>	<link rel="stylesheet" type="text/css" href="<?php echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?php
				feedback(3,"<div align=\"left\">Helios was unable to process a database command. The following error was received from the MySQL server.<br /><blockquote>" . $errMsg . "</blockquote><br />If this doesn't make sense to you please copy and paste the message in an email to your<br /><a href=\"mailto:" . CalAdminEmail . "?subject=Helios Error Message\" class=\"main\">Helios Administrator</a>.</div>");
			?></div><?php
				exit;
				break;
		}//end switch
	}//end handleError()
	
	
	//	Check if query result has any rows
	function hasRows($result){
		$chk_row_cnt = mysql_num_rows($result);
		
		if($chk_row_cnt > 0){
			return 1;
		} else {
			return 0;
		}//end if
	}//end hasRows()
	
	
	//	Make textarea with Tiny MCE controls
	//	$style sets the TinyMCE template to use. 
	//		Options: simple (used for public), advanced (used for admin)
	function makeTinyMCE($textName, $hc_WYSIWYG = 1, $width = "435px", $style = "simple", $passContent = ""){	?>
		<textarea name="<?php echo $textName;?>" id="<?php echo $textName;?>" style="width: <?php echo $width;?>; <?php if($hc_WYSIWYG == 0){echo "height: 200px;\" convert_this=\"false\"";}else{echo "height: 300px;\"";}?>><?php echo $passContent;?></textarea>
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="<?php echo CalRoot;?>/includes/tiny_mce/tiny_mce_gzip.php"></script>
		<script language="javascript" type="text/javascript">
		//<!--
			tinyMCE.init({
				cleanup : false,
				mode : "textareas",
				textarea_trigger : "convert_this",
				theme : "advanced",
				plugins :  "spellchecker,advhr,advimage,advlink,emotions,fullscreen,insertdatetime,layer,media,paste,preview,print,searchreplace,style,table,xhtmlxtras",
	<?php 	if($style == "advanced"){	?>
				theme_advanced_buttons1 : "fontsizeselect,bold,italic,underline,strikethrough,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,outdent,indent,|,undo,redo",
				theme_advanced_buttons2 : "pastetext,pasteword,|,link,unlink,anchor,|,image,emotions,media,|,forecolor,backcolor,|,insertdate,|,sup,sub,|,cleanup,|,replace",
				theme_advanced_buttons3 : "tablecontrols,visualaid,|,removeformat,advhr,charmap,|,code",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,print,fullscreen,preview,|,spellchecker,|,help",
				extended_valid_elements : "form[name|id|method|action|target],input[type|name|id|value|class|src],a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],object[classid|codebase|width|height],param[name|value],embed[quality|type|pluginspage|width|height|src|align],iframe[src|width|height|scrolling|border|marginwidth|style|frameborder]",
	<?php 	} else {	?>
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,",
				theme_advanced_buttons2 : "link,unlink,image,emotions,separator,undo,redo,code,spellchecker",
				theme_advanced_buttons3 : "",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
	<?php 	}//end if	?>
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_path_location : "bottom",
				theme_advanced_resizing : true,
				theme_advanced_resize_horizontal : false,
				//content_css : "<?php echo CalRoot;?>/css/helios.css",
			    plugin_insertdate_dateFormat : "%m/%d/%Y",
				apply_source_formatting : true,
				spellchecker_languages : "+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv"
			});
		//-->
		</script>
		<!-- /tinyMCE -->
<?php
	}//end makeHTMLArea()
	
	
	//	Make feedback box
	function feedback($type, $fmessage){
		
		$fType = "info";
		$fIcon = "iconSuccess.gif";
		switch($type){
			case 2:
				$fType = "warning";
				$fIcon = "iconCaution.gif";
				break;
			case 3:
				$fType = "error";
				$fIcon = "iconError.gif";
				break;
		}//end switch	?>
		<br />
		<div class="<?php echo $fType;?>">
			<img src="<?php echo CalRoot;?>/images/feedback/<?php echo $fIcon;?>" alt="" border="0" align="absmiddle" />&nbsp;<?php echo $fmessage;?>
		</div>
		<br />
<?php
	}//end feedback
	
	
	//	Make instruction box
	function appInstructions($noSwitch, $codex, $title, $message){
		if($_SESSION['Instructions'] == 1){
		?>	<div class="instructions">
				<div style="width:90%;float:left;vertical-align:middle;"><b><?php echo $title;?></b></div>
				<div style="width:10%;float:left;text-align:right;">
		<?php 	if($codex != ''){	?>
				<a href="http://www.helioscalendar.com/documentation/index.php?title=<?php echo $codex;?>" class="main" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconHelp.gif" width="16" height="16" alt="" border="0" /></a>
		<?php 	}//end if
			
				if($noSwitch == 0){	?>
					&nbsp;&nbsp;<a href="<?php echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconCollapse.gif" width="16" height="16" alt="" border="0" /></a>
		<?php 	}//end if	?>
				</div>
				<div style="padding: 5px 0px 5px 10px;"><?php echo $message;?></div>
			</div>
<?php } else {	?>
			<div class="instructions" style="height: 16px;">
				<div style="width:90%;float:left;vertical-align:middle;"><b><?php echo $title;?></b></div>
				<div style="width:10%;float:left;text-align:right;">
		<?php 	if($codex != ''){	?>
				<a href="http://www.helioscalendar.com/documentation/index.php?title=<?echo $codex;?>" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconHelp.gif" width="16" height="16" alt="" border="0" /></a>
		<?php 	}//end if
			
				if($noSwitch == 0){	?>
					&nbsp;&nbsp;<a href="<?php echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconExpand.gif" width="16" height="16" alt="" border="0" /></a>
		<?php 	}//end if	?>
				</div>
			</div>
<?php 	}//end if
	}//end instruction()
	
	
	//	Make instruction icon
	function appInstructionsIcon($title, $message){
	?>	<a onmouseover="this.T_TITLE='<?php echo $title;?>';this.T_SHADOWCOLOR='#3D3F3E';return escape('<?php echo $message;?>')" href="javascript:;" class="eventMain"><img src="<?php echo CalAdminRoot;?>/images/icons/iconInfo.gif" width="16" height="16" alt="" border="0" /></a><?php
	}//end calInstructionsIcon()
	
	
	//	Convert timestamp to passed formated date
	function stampToDate($timeStamp, $dateFormat){
		$stampParts = explode(" ", $timeStamp);
		$dateParts = explode("-", $stampParts[0]);
		
		if(isset($stampParts[1])){
			$timeParts = explode(":", $stampParts[1]);
			$theDate = date($dateFormat, mktime($timeParts[0], $timeParts[1], $timeParts[2], $dateParts[1], $dateParts[2], $dateParts[0]));
		} else {
			$theDate = date($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
		}//end if
		
		return $theDate;
	}//end stampToDate()
	
	
	//	Convert date to MySQL format
	function dateToMySQL($theDate, $splitBy, $dateFormat){
		$dateParts = explode($splitBy, $theDate);
		switch(strToLower($dateFormat)){
			case 'd/m/y':
				$theDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[0], $dateParts[2]));
				break;
				
			case 'm/d/y' :
				$theDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
				break;
				
			case 'y/m/d':
				$theDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
				break;
		}//end switch
		
		return $theDate;
	}//end dateToMySQL()
	
	
	//	Retrieve Category List
	function getCategories($frmName, $columns, $query = NULL){
		if(!isset($query)){	
			$query = "SELECT " . HC_TblPrefix . "categories.*, NULL as EventID FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName";
		}//end if	?>
		<table cellpadding="0" cellspacing="0" border="0"><tr>
	<?php 	$result = doQuery($query);
			$cnt = 0;
			
			while($row = mysql_fetch_row($result)){
				if(($cnt % $columns == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
				<td><label for="catID_<?php echo $row[0];?>" class="category"><input <?php if($row[6] != ''){echo "checked=\"checked\"";}//end if?> name="catID[]" id="catID_<?php echo $row[0];?>" type="checkbox" value="<?php echo $row[0];?>" class="noBorderIE" /><?php echo cOut($row[1]);?></label></td>
		<?php 	$cnt = $cnt + 1;
			}//end while?>
		</tr></table>
<?php	if($cnt > 1){	?>
			<div style="text-align:right;padding:10px 0px 10px 0px;">
			[ <a class="eventMain" href="javascript:;" onclick="checkAllArray('<?php echo $frmName;?>', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="eventMain" href="javascript:;" onclick="uncheckAllArray('<?php echo $frmName;?>', 'catID[]');">Deselect All Categories</a> ]
			</div>
<?php	}//end if
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
			$curCity = $row[0] . $row[1];
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
								GROUP BY LocationCity, City");
		$postal = array();
		while($row = mysql_fetch_row($result)){
			$curCity = $row[0] . $row[1];
			if($curCity != ''){
				$postal[strtolower($curCity)] = $curCity;
			}//end if
		}//end while
		
		ksort($postal);
		$postal = array_unique($postal);
		
		return $postal;
	}//end getpostal()
	
	
	//	Generate a random string of passed size
	function makeRandomPassword($size = 6){
	  $pass = "";
	  $salt = "abchefghjkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
	  srand((double)microtime()*1000000);
      for($i = 1; $i <= $size; $i++){
        $num = rand(0, strlen($salt));
        $tmp = substr($salt, $num, 1);
        $pass = $pass . $tmp;
      }//end for
      return $pass;
	}//end makeRandomPassword()
		
	
	//	Escape special characters
	function cleanSpecialChars($textToClean){
		$textToClean = str_replace("\\", "\\\\", $textToClean);
		$textToClean = str_replace("'", "\\'", $textToClean);
		$textToClean = str_replace("\"", "\\\"", $textToClean);
		$textToClean = str_replace(",", "\,", $textToClean);
		
		return $textToClean;
	}//end cleanSpecialChars()
	
	
	//	Substitute special WML characters with proper replacement
	function cleanWMLChars($textToClean){
		$textToClean = str_replace("&", "&amp;", $textToClean);
		$textToClean = str_replace("\"", "&quot;", $textToClean);
		$textToClean = str_replace("'", "&#39;", $textToClean);
		$textToClean = str_replace("<", "&lt;", $textToClean);
		$textToClean = str_replace(">", "&gt;", $textToClean);
		$textToClean = str_replace("-", "&shy;", $textToClean);
		$textToClean = str_replace("$", "$$", $textToClean);
		
		return $textToClean;
	}//end cleanSpecialChars()
	
	
	//	Clean email form from fields (prevents additional recipients & urls)
	function cleanEmailHeader($textToClean){
		$find = array('/\r/', '/\n/');
		$textToClean = preg_replace($find, "", $textToClean);
		
		return $textToClean;
	}//end cleanEmailHeader()
	
	
	//	Make teaser text out of passed larger text
	function makeTeaser($text) {
		$chars = 200;
		
        $text = $text." ";
        $text = substr($text,0,$chars);
        $text = substr($text,0,strrpos($text,' '));
        $text = $text."...";
		
        return $text;
	}//end makeTeaser()
	
	
	//	Return the number of days between two passed dates
	function daysDiff($date1, $date2){
		$days = 0;
		
		$stampParts1 = explode(" ", $date1);
		$dateParts1 = explode("-", $stampParts1[0]);
		
		$stampParts2 = explode(" ", $date2);
		$dateParts2 = explode("-", $stampParts2[0]);
		
		$date1 = date("z", mktime(0, 0, 0, $dateParts1[1], $dateParts1[2], $dateParts1[0]));
		$date2 = date("z", mktime(0, 0, 0, $dateParts2[1], $dateParts2[2], $dateParts2[0]));
		
		if($dateParts1[0] != $dateParts2[0]){
			$days = 365 * ($dateParts1[0] % $dateParts2[0]);
		}//end if
		
		$days = $days + (($date1 - $date2) + 1);
		
		return $days;
	}//end daysDiff()
	
	
	//	Output hidden spam target fields
	function fakeFormFields(){	?>
		<input name="name" id="name" type="text" value="" style="display:none;" />
		<input name="subject" id="subject" type="text" value="" style="display:none;" />
		<input name="email" id="email" type="text" value="" style="display:none;" />
		<input name="phone" id="phone" type="text" value="" style="display:none;" />
		<input name="message" id="message" type="text" value="" style="display:none;" />
<?php
	}//end fakeFormFields()	
	
	
	//	Build CAPTCHA Image
	function buildCaptcha(){
		if(function_exists('imagecreate')){	?>
			<img src="includes/captcha.php" width="250" border="0" alt="" />
	<?php	
		} else {
			echo "<span style=\"font-weight:bold;color:#DC143C;\">GD Library Unavailable. Image Cannot Be Created.</span>";
		}//end if
	}//end buildCaptcha()
	
	
	//	CAPTCHA Test Comparison
	function spamIt($proof, $checkMe){
		$spam = 0;
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID = 32");
		$active = explode(",", mysql_result($result,0,1));
		
		if(in_array($checkMe, $active)){	
			if($_SESSION['hc_cap'] != $proof){
				$spam = 1;
			}//end if
			
			if($spam != 0){
				exit("Your authentication was entered incorrectly. Please use your browsers back button and try again.");
			}//end if
		}//end if
		return;
	}//end spamIt()	?>