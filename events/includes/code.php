<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	//	Connect to database defined in globals.php
	function hookDB(){
		$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
		mysql_select_db(DATABASE_NAME,$dbconnection);
	}//end hookDB()
	
	
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
			?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?
				feedback(3,"Connection to the Helios database is unavailable.<br /><br />Please verify your globals.php file settings are correct. Specifically the name of the database you are using. If you have not yet done so, please run <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?
				exit;
				break;
			//	no table
			case 1146:
			?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?
				feedback(3,"Connection to a required Helios datatable is unavailable.<br /><br />If you have not yet done so, please run <a href=\"" . CalRoot . "/setup/\" class=\"eventMain\">Helios Setup</a>.<br /><br /><hr>MySQL Server Response: '" . $errMsg . "'");
			?></div><?
				exit;
				break;
			default:
			?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
				<div style="width:500px;"><?
				feedback(3,"<div align=\"left\">Helios was unable to process a database command. The following error was received from the MySQL server.<br /><blockquote>" . $errMsg . "</blockquote><br />If this doesn't make sense to you please copy and paste the message in an email to your<br /><a href=\"mailto:" . CalAdminEmail . "?subject=Helios Error Message\" class=\"main\">Helios Administrator</a>, or support@helioscalendar.com.</div>");
			?></div><?
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
	function makeTinyMCE($textName, $width = "75%", $style = "simple", $passContent = ""){?>	
		<textarea name="<?echo $textName;?>" id="<?echo $textName;?>" rows="30" cols="40" style="width: <?echo $width;?>; height: 300px;"><?echo $passContent;?></textarea>
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="<?echo CalRoot;?>/includes/tiny_mce/tiny_mce_gzip.php"></script>
		<script language="javascript" type="text/javascript">
		//<!--
			tinyMCE.init({
				cleanup : true,
				mode : "textareas",
				textarea_trigger : "convert_this",
				theme : "advanced",
				plugins : "advhr,advimage,advlink,contextmenu,emotions,insertdatetime,paste,preview,searchreplace,table",
		<?	if($style == "advanced"){	?>
				theme_advanced_buttons1 : "fontsizeselect,bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,undo,redo,code,separator,preview,",
				theme_advanced_buttons2 : "pastetext,separator,outdent,indent,separator,link,unlink,anchor,separator,image,emotions,separator,forecolor,backcolor,separator,insertdate,separator,sup,sub,separator,cleanup,separator,replace",
				theme_advanced_buttons3 : "tablecontrols,visualaid,separator,removeformat,hr,help",
				extended_valid_elements : "form[name|id|method|action|target],input[type|name|id|value|class|src],a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],object[classid|codebase|width|height],param[name|value],embed[src|quality|width|height|type|pluginspage|bgcolor]",
		<?	} else {	?>
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,",
				theme_advanced_buttons2 : "link,unlink,image,emotions,separator,undo,redo,code",
				theme_advanced_buttons3 : "",
				extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		<?	}//end if	?>
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_path_location : "bottom",
				theme_advanced_resizing : false,
				theme_advanced_resize_horizontal : false,
				content_css : "<?echo CalRoot;?>/css/helios.css",
			    plugin_insertdate_dateFormat : "%m/%d/%Y",
				
				external_link_list_url : "example_link_list.js",
				external_image_list_url : "example_image_list.js"
			});
		//-->
		</script>
		<!-- /tinyMCE -->
<?	}//end makeHTMLArea()
	
	
	//	Make feedback box
	function feedback($type, $fmessage){
		
		$fType = "info";
		switch($type){
			case 2:
				$fType = "warning";
				
			case 3:
				$fType = "error";
				break;
		}//end switch	?>
		<br />
		<div class="<?echo $fType;?>">
			<img src="<?echo CalRoot;?>/images/feedback/iconSuccess.gif" alt="" border="0" align="middle" />&nbsp;<?echo $fmessage;?>
		</div>
		<br />
<?	}//end feedback
	
	
	//	Make instruction box
	function appInstructions($noSwitch, $codex, $title, $message){
		if($_SESSION['Instructions'] == 1){
		?>	<div class="instructions">
				<div style="width:90%;float:left;vertical-align:middle;"><b><?echo $title;?></b></div>
				<div style="width:10%;float:left;text-align:right;">
			<?	if($codex != ''){	?>
				<a href="http://codex.helioscalendar.com/index.php?title=<?echo $codex;?>" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconHelp.gif" width="16" height="16" alt="" border="0" /></a>
			<?	}//end if
			
				if($noSwitch == 0){	?>
					&nbsp;&nbsp;<a href="<?echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconCollapse.gif" width="16" height="16" alt="" border="0" /></a>
			<?	}//end if	?>
				</div>
				<div style="padding: 25px 0px 5px 10px;"><?echo $message;?></div>
			</div>
	<?	} else {	?>
			<div class="instructions">
				<div style="text-align: right;">
			<?	if($codex != ''){	?>
				<a href="http://codex.helioscalendar.com/index.php?title=<?echo $codex;?>" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconHelp.gif" width="16" height="16" alt="" border="0" /></a>
			<?	}//end if
			
				if($noSwitch == 0){	?>
					&nbsp;&nbsp;<a href="<?echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconExpand.gif" width="16" height="16" alt="" border="0" /></a>
			<?	}//end if	?>
				</div>
			</div>
	<?	}//end if
	}//end instruction()
	
	
	//	Make instruction icon
	function appInstructionsIcon($title, $message){
	?>	<a onmouseover="this.T_TITLE='<?echo $title;?>';this.T_SHADOWCOLOR='#3D3F3E';return escape('<?echo $message;?>')" href="javascript:;" class="eventMain"><img src="<?echo CalAdminRoot;?>/images/icons/iconInfo.gif" width="16" height="16" alt="" border="0" /></a><?
	}//end calInstructionsIcon()
	
	
	//	Convert timestamp to passed formated date
	function stampToDate($timeStamp, $dateFormat){
		$stampParts = split(" ", $timeStamp);
		$dateParts = split("-", $stampParts[0]);
		
		if(isset($stampParts[1])){
			$timeParts = split(":", $stampParts[1]);
			$theDate = date($dateFormat, mktime($timeParts[0], $timeParts[1], $timeParts[2], $dateParts[1], $dateParts[2], $dateParts[0]));
		} else {
			$theDate = date($dateFormat, mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
		}//end if
		
		return $theDate;
	}//end stampToDate()
	
	
	//	Convert date to MySQL format
	function dateToMySQL($theDate, $splitBy, $dateFormat){
		$dateParts = split($splitBy, $theDate);
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
		<?	$result = doQuery($query);
			$cnt = 0;
			
			while($row = mysql_fetch_row($result)){
				if(($cnt % $columns == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
				<td><label for="catID_<?echo $row[0];?>" class="category"><input <?if($row[6] != ''){echo "checked=\"checked\"";}//end if?> name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
			<?	$cnt = $cnt + 1;
			}//end while?>
		</tr></table>
	<?	if($cnt > 1){	?>
			<div style="text-align:right;padding:10px 0px 10px 0px;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('<?echo $frmName;?>', 'catID[]');">Select All Categories</a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('<?echo $frmName;?>', 'catID[]');">Deselect All Categories</a> ]
			</div>
	<?	}//end if
	}//end getCategories()
	
	//	Generate a random password of passed size
	function makeRandomPassword($size = 6){
	  $pass = "";
	  $letters = "abchefghjkmnpqrstuvwxyz0123456789";
	  srand((double)microtime()*1000000);
	      $i = 1;
	      while ($i <= $size) {
	            $num = rand() % strlen($letters);
	            $new = substr($letters, $num, 1);
	            $pass = $pass . $new;
	            $i++;
	      }
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
		$textToClean = str_replace("'", "&apos;", $textToClean);
		$textToClean = str_replace("<", "&lt;", $textToClean);
		$textToClean = str_replace(">", "&gt;", $textToClean);
		$textToClean = str_replace("-", "&shy;", $textToClean);
		$textToClean = str_replace("$", "$$", $textToClean);
		
		return $textToClean;
	}//end cleanSpecialChars()
	
	
	//	Clean email form from fields (prevents spam)
	function cleanEmailHeader($textToClean){
		$find = array('/\r/', '/\n/');
		$textToClean = preg_replace($find, "", $textToClean);
		
		return $textToClean;
	}//end cleanFromAddress()
	
	
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
		
		$stampParts1 = split(" ", $date1);
		$dateParts1 = split("-", $stampParts1[0]);
		
		$stampParts2 = split(" ", $date2);
		$dateParts2 = split("-", $stampParts2[0]);
		
		$date1 = date("z", mktime(0, 0, 0, $dateParts1[1], $dateParts1[2], $dateParts1[0]));
		$date2 = date("z", mktime(0, 0, 0, $dateParts2[1], $dateParts2[2], $dateParts2[0]));
		
		if($dateParts1[0] != $dateParts2[0]){
			$days = 365 * ($dateParts1[0] % $dateParts2[0]);
		}//end if
		
		$days = $days + (($date1 - $date2) + 1);
		
		return $days;
	}//end daysDiff()
	
	
/*	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|	Modifying the code below this point is not permitted and violates the Helios EUL.	|
	|	Please do not edit or decode any code with this notice.								|
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~	*/
	
	eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB0aGVuIHlvdSBoYXZlIHZpb2xhdGVkIHRoZSBIZWxpb3MgRVVMKi8NCglpZighaXNzZXQoJHNldHVwKSl7DQoJCWhvb2tEQigpOw0KCQkkcmVzdWx0ID0gbXlzcWxfcXVlcnkoIlNFTEVDVCBTZXR0aW5nVmFsdWUgRlJPTSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFdIRVJFIFBrSUQgSU4gKDE5LDIwKSIpOw0KCQkNCgkJaWYoISRyZXN1bHQpew0KCQkJaGFuZGxlRXJyb3IobXlzcWxfZXJybm8oKSwgbXlzcWxfZXJyb3IoKSk7DQoJCX1lbHNlew0KCQkJaWYoJF9TRVJWRVJbJ0hUVFBfSE9TVCddID09ICdsb2NhbGhvc3QnKXsNCgkJCQkvLyBkbyBub3RoaW5nDQoJCQl9IGVsc2VpZihteXNxbF9yZXN1bHQoJHJlc3VsdCwxLDApICE9IG1kNShkYXRlKCJZLW0tZCIsIG1rdGltZSggMCwgMCwgMCwgZGF0ZSgibSIpLCAxLCBkYXRlKCJZIikpKSkpew0KCQkJCQ0KCQkJCSRob3N0ID0gInZhbGlkYXRlLmhlbGlvc2NhbGVuZGFyLmNvbSI7DQoJCQkJJGZpbGUgPSAiL3YyLnBocD9jPSIgLiBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107DQoJCQkJDQoJCQkJaWYoISgkZnAgPSBmc29ja29wZW4oJGhvc3QsIDgwLCAkZXJybm8sICRlcnJzdHIsIDEpKSApew0KCQkJCQkvLwljb25uZWN0aW9uIGZhaWxlZCByZXBvcnQgaXQNCgkJCQkJbXlzcWxfcXVlcnkoIlVQREFURSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFNFVCBTZXR0aW5nVmFsdWUgPSAnIiAuIG1kNShkYXRlKCJZLW0tZCIsIG1rdGltZSggMCwgMCwgMCwgZGF0ZSgibSIpLCAxLCBkYXRlKCJZIikpKSkuICInIFdIRVJFIFBrSUQgPSAyMCIpOw0KCQkJCQkNCgkJCQkJJG1lc3NhZ2UgPSBiYXNlNjRfZW5jb2RlKCJSZWdpc3RyYXRpb246ICIgLiBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIC4gIi8vSG9zdDogIiAuICRfU0VSVkVSWydIVFRQX0hPU1QnXSAuICIvL1BIUF9TRUxGOiAiIC4gJF9TRVJWRVJbJ1BIUF9TRUxGJ10gLiAiLy9SRU1PVEVfQUREUjogIiAuICRfU0VSVkVSWydSRU1PVEVfQUREUiddIC4gIi8vUkVNT1RFX0hPU1Q6ICIgLiAkX1NFUlZFUlsnUkVNT1RFX0hPU1QnXSAuICIvL1BBVEhfVFJBTlNMQVRFRDogIiAuICRfU0VSVkVSWydQQVRIX1RSQU5TTEFURUQnXSAuICIvL09TOiAiIC4gJF9TRVJWRVJbJ09TJ10gLiAiLy9TRVJWRVJfU09GVFdBUkU6ICIgLiAkX1NFUlZFUlsnU0VSVkVSX1NPRlRXQVJFJ10gLiAiLy9QSFBfVmVyc2lvbjogIiAuIHBocHZlcnNpb24oKSAuICIvL1NFUlZFUl9OQU1FOiAiIC4gJF9TRVJWRVJbJ1NFUlZFUl9OQU1FJ10pOw0KCQkJCQltYWlsKCJzdXBwb3J0QGhlbGlvc2NhbGVuZGFyLmNvbSIsICAicG9sbHkgc2luZ3MgLS0gY29ubmVjdGlvbiBmYWlsZWQiLCAiJG1lc3NhZ2UiKTsNCgkJCQkJDQoJCQkJfSBlbHNlIHsNCgkJCQkJJHJlYWQgPSAiIjsNCgkJCQkJJHJlcXVlc3QgPSAiR0VUICRmaWxlIEhUVFAvMS4xXHJcbiI7DQoJCQkJCSRyZXF1ZXN0IC49ICJIb3N0OiAkaG9zdFxyXG4iOw0KCQkJCQkkcmVxdWVzdCAuPSAiQ29ubmVjdGlvbjogQ2xvc2VcclxuXHJcbiI7DQoJCQkJCWZ3cml0ZSgkZnAsICRyZXF1ZXN0KTsNCgkJCQkJDQoJCQkJCXdoaWxlICghZmVvZigkZnApKSB7DQoJCQkJCQkkcmVhZCAuPSBmcmVhZCgkZnAsMTAyNCk7DQoJCQkJCX0vL2VuZCB3aGlsZQ0KCQkJCQkNCgkJCQkJJG91dHB1dCA9IGV4cGxvZGUoImhlbGlvcy8vIiwgJHJlYWQpOw0KCQkJCQlpZihpc3NldCgkb3V0cHV0WzFdKSl7DQoJCQkJCQkvL3VwZGF0ZSBjYWNoZQ0KCQkJCQkJbXlzcWxfcXVlcnkoIlVQREFURSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFNFVCBTZXR0aW5nVmFsdWUgPSAnIiAuIG1kNShkYXRlKCJZLW0tZCIsIG1rdGltZSggMCwgMCwgMCwgZGF0ZSgibSIpLCAxLCBkYXRlKCJZIikpKSkuICInIFdIRVJFIFBrSUQgPSAyMCIpOw0KCQkJCQkJDQoJCQkJCX0gZWxzZSB7DQoJCQkJCQlteXNxbF9xdWVyeSgiVVBEQVRFICIgLiBIQ19UYmxQcmVmaXggLiAic2V0dGluZ3MgU0VUIFNldHRpbmdWYWx1ZSA9ICciIC4gbWQ1KGRhdGUoIlktbS1kIiwgbWt0aW1lKCAwLCAwLCAwLCBkYXRlKCJtIiksIDEsIGRhdGUoIlkiKSkpKS4gIicgV0hFUkUgUGtJRCA9IDIwIik7DQoJCQkJCQkNCgkJCQkJCSRtZXNzYWdlID0gYmFzZTY0X2VuY29kZSgiUmVnaXN0cmF0aW9uOiAiIC4gbXlzcWxfcmVzdWx0KCRyZXN1bHQsMCwwKSAuICIvL0hvc3Q6ICIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ10gLiAiLy9QSFBfU0VMRjogIiAuICRfU0VSVkVSWydQSFBfU0VMRiddIC4gIi8vUkVNT1RFX0FERFI6ICIgLiAkX1NFUlZFUlsnUkVNT1RFX0FERFInXSAuICIvL1JFTU9URV9IT1NUOiAiIC4gJF9TRVJWRVJbJ1JFTU9URV9IT1NUJ10gLiAiLy9QQVRIX1RSQU5TTEFURUQ6ICIgLiAkX1NFUlZFUlsnUEFUSF9UUkFOU0xBVEVEJ10gLiAiLy9PUzogIiAuICRfU0VSVkVSWydPUyddIC4gIi8vU0VSVkVSX1NPRlRXQVJFOiAiIC4gJF9TRVJWRVJbJ1NFUlZFUl9TT0ZUV0FSRSddIC4gIi8vUEhQX1ZlcnNpb246ICIgLiBwaHB2ZXJzaW9uKCkgLiAiLy9TRVJWRVJfTkFNRTogIiAuICRfU0VSVkVSWydTRVJWRVJfTkFNRSddKTsNCgkJCQkJCW1haWwoInN1cHBvcnRAaGVsaW9zY2FsZW5kYXIuY29tIiwgICJwb2xseSBzaW5ncyAtLSBpbnZhbGlkIHJlZ2lzdHJhdGlvbiIsICIkbWVzc2FnZSIpOw0KCQkJCQkJDQoJCQkJCX0vL2VuZCBpZg0KCQkJCQlmY2xvc2UoJGZwKTsNCgkJCQl9Ly9lbmQgaWYNCgkJCQkNCgkJCQkNCgkJCX0vL2VuZCBpZg0KCQl9Ly9lbmQgaWYNCgl9Ly9lbmQgaWY='));
?>