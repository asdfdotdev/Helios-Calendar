<?php
	//	Connect to database defined in globals.php
	function hookDB(){
		$dbconnection = mysql_connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASS);
		mysql_select_db(DATABASE_NAME,$dbconnection);
	}//end hookDB()
	
	
	//	Run passed query
	function doQuery($query){
	//	exit($query);
		$result = mysql_query($query);
		
		if(!$result){
			handleError(mysql_errno(), mysql_error());
		}//end if
		return $result;
	}//end doQuery()
	
	
	//	Clean Passed Value
	function cIn($value) {
		$value = str_replace("\"", "'", $value);
		
		if (get_magic_quotes_gpc() == 1) {
			return $value;
		} else {
			return addslashes($value);
		}//end if
		
	}//end cleanQuery()
	
	
	//	Clean Passed Value
	function cOut($value){
		if (get_magic_quotes_gpc() == 1) {
			return $value;
		} else {
			return stripslashes($value);
		}//end if
	}//end if
	
	
	//	Handle MySQL Errors
	function handleError($errNo, $errMsg){
		//	uncomment for debugging
		//	echo "<br>---------------------------------------<br><b>Error #:</b> " . $errNo . "<br><b>Message:</b> " .$errMsg . "<br>---------------------------------------<br><br>";
		switch($errNo){
			//	no dateabase
			case 1046:
			//	no table
			case 1146:
			?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
				<div style="width:400px;"><?
				feedback(3,"Helios does not appear to be installed correctly. Please run <a href=\"" . CalRoot . "/setup/\" class=\"main\">Helios Setup</a> to continue.");
			?></div><?
				exit;
				break;
			default:
			?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
				<div style="width:400px;"><?
				feedback(3,"<div align=\"left\">Helios was unable to process a database command. The following error was received from the server.<br><blockquote>" . $errMsg . "</blockquote><br>If this doesn't make sense to you please copy and paste the message in an email to your<br><a href=\"mailto:" . CalAdminEmail . "?subject=Helios Error Message\" class=\"main\">Helios Administrator</a> and they should be able to fix it.</div>");
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
	function makeTinyMCE($textName, $width = "100%", $style = "simple", $passContent = ""){?>	
		<textarea autocomplete="off" style="width: <?echo $width;?>; height: 300px" id="<?echo $textName;?>" name="<?echo $textName;?>"><?echo $passContent;?></textarea>
		<!-- tinyMCE -->
		<script language="javascript" type="text/javascript" src="<?echo CalRoot;?>/includes/tiny_mce/tiny_mce_gzip.php"></script>
		<script language="javascript" type="text/javascript">
			tinyMCE.init({
				cleanup : true,
				mode : "textareas",
				textarea_trigger : "convert_this",
				theme : "advanced",
				plugins : "advhr,advimage,advlink,contextmenu,emotions,insertdatetime,paste,preview,searchreplace,table",
		<?	if($style == "advanced"){	?>
				theme_advanced_buttons1 : "fontsizeselect,bold,italic,underline,strikethrough,separator,bullist,numlist,separator,justifyleft,justifycenter,justifyright,separator,undo,redo,code",
				theme_advanced_buttons2 : "pastetext,separator,outdent,indent,separator,link,unlink,anchor,separator,image,emotions,separator,forecolor,backcolor,separator,insertdate,separator,sup,sub,separator,cleanup,separator,replace",
				theme_advanced_buttons3 : "tablecontrols,visualaid,separator,removeformat,hr,help",
				extended_valid_elements : "form[name|id|method|action|target],input[type|name|id|value|class|src],a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
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
		</script>
		<!-- /tinyMCE -->
<?	}//end makeHTMLArea()
	
	
	//	Make my cool little feedback box
	function feedback($type, $fmessage){
		switch($type){
			case 1:
				//success
				echo "<div align=\"center\"><table cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border=\"0\"><tr><td class=\"info\" bgcolor=\"#B7DF6F\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td width=\"35\"><img src=\"" . CalRoot . "/images/feedback/iconSuccess.gif\" alt=\"\" border=\"0\"></td><td class=\"feedback\">" . $fmessage . "</td></tr></table></td></tr></table></div><br>";
				break;
				
			case 2:
				//warning
				echo "<div align=\"center\"><table cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border=\"0\"><tr><td class=\"warning\" bgcolor=\"#FFFF99\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td width=\"35\"><img src=\"" . CalRoot . "/images/feedback/iconCaution.gif\" alt=\"\" border=\"0\"></td><td class=\"feedback\">" . $fmessage . "</td></tr></table></td></tr></table></div><br>";
				break;
				
			case 3:
				//error
				echo "<div align=\"center\"><table cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" border=\"0\"><tr><td class=\"error\" bgcolor=\"#FFC8D3\" align=\"center\"><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td width=\"35\"><img src=\"" . CalRoot . "/images/feedback/iconError.gif\" alt=\"\" border=\"0\"></td><td class=\"feedback\">" . $fmessage . "</td></tr></table></td></tr></table></div><br>";
				break;
				
			default:
				break;
		}//end switch
	}//end feedback
	
	
	//	Make my cool little instruction box
	function appInstructions($noSwitch, $codex, $title, $message){
		if($_SESSION['Instructions'] == 1){
		?>	<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td class="instructions">
						
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td width="37" class="instructionText" valign="top">
									<img src="<?echo CalAdminRoot;?>/images/iconInstruction.gif" width="30" height="26" alt="" border="0">
								</td>
								<td class="instructionText">
									<b><?echo $title;?></b> <?if($codex != ''){?>[ <a href="http://codex.helioscalendar.com/index.php?title=<?echo $codex;?>" class="main" target="_blank">Help in Codex</a> ] <?}//end if?><br><?echo $message;?><br>
									<?php
										if($noSwitch == 0){
										?>	<div align="right"><a href="<?echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main">Turn Instructions Off</a></div> <?
										}//end if?>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table><?
		} else {?>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
			<tr>
					<td class="instructions">
						
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr>
								<td class="instructionText" width="50%">
								<?	if($codex != ''){	?>
									[ <a href="http://codex.helioscalendar.com/index.php?title=<?echo $codex;?>" class="main" target="_blank">Help in Codex</a> ]
								<?	}//end if	?>
								</td>
								<td class="instructionText" align="right">
								<?	if($noSwitch == 0){	?>
										<a href="<?echo CalAdminRoot . "/" . HC_InstructionsSwitch;?>" class="main">Turn Instructions On</a>
								<?	} else {	?>
										Instructions Switch Unavailable
								<?	}//end if	?>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
	<?	}//end if
	}//end instruction()
	
	
	//	make my cool little instruction icon
	function appInstructionsIcon($title, $message){
	?>	<a onmouseover="this.T_TITLE='<?echo $title;?>';this.T_SHADOWCOLOR='#3D3F3E';return escape('<?echo $message;?>')" href="javascript:;" class="eventMain"><img src="<?echo CalAdminRoot;?>/images/icon-info.gif" width="16" height="16" alt="" border="0"></a><?
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
	function dateToMySQL($theDate, $splitBy){
		$dateParts = split($splitBy, $theDate);
		$theDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
		
		return $theDate;
	}//end dateToMySQL()
	
	
	//	Generate a random password of passed size
	function makeRandomPassword($size){
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
		$textToClean = str_replace("& ", "&amp; ", $textToClean);
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
	
	
	eval(base64_decode('LypJZiB5b3UgY2FuIHJlYWQgdGhpcyB0aGVuIHlvdSBoYXZlIHZpb2xhdGVkIHRoZSBIZWxpb3MgRVVMKi9pZighaXNzZXQoJHNldHVwKSl7aG9va0RCKCk7JHJlc3VsdCA9IG15c3FsX3F1ZXJ5KCJTRUxFQ1QgU2V0dGluZ1ZhbHVlIEZST00gIiAuIEhDX1RibFByZWZpeCAuICJzZXR0aW5ncyBXSEVSRSBQa0lEIElOICgxOSwyMCkiKTtpZighJHJlc3VsdCl7aGFuZGxlRXJyb3IobXlzcWxfZXJybm8oKSwgbXlzcWxfZXJyb3IoKSk7fWVsc2V7aWYobXlzcWxfcmVzdWx0KCRyZXN1bHQsMSwwKSAhPSBtZDUoZGF0ZSgiWS1tLWQiLCBta3RpbWUoIDAsIDAsIDAsIGRhdGUoIm0iKSwgMSwgZGF0ZSgiWSIpKSkpKXskZmlsZSA9ICJodHRwOi8vdmFsaWRhdGUuaGVsaW9zY2FsZW5kYXIuY29tLz9jPSIgLiBteXNxbF9yZXN1bHQoJHJlc3VsdCwwLDApIC4gIiZ1PSIgLiAkX1NFUlZFUlsnSFRUUF9IT1NUJ107c2V0X3RpbWVfbGltaXQoMCk7JHJzc19jaGFubmVsID0gYXJyYXkoKTskY3VycmVudGx5X3dyaXRpbmcgPSAiIjskbWFpbiA9ICIiOyRpdGVtX2NvdW50ZXIgPSAwO2Z1bmN0aW9uIGJlZ2luRWxlbWVudCgkcGFyc2VyLCAkbmFtZSwgJGF0dHJzKSB7Z2xvYmFsICRyc3NfY2hhbm5lbCwgJGN1cnJlbnRseV93cml0aW5nLCAkbWFpbjtzd2l0Y2goJG5hbWUpIHtjYXNlICJSU1MiOmNhc2UgIlJERjpSREYiOmNhc2UgIklURU1TIjokY3VycmVudGx5X3dyaXRpbmcgPSAiIjticmVhaztjYXNlICJDSEFOTkVMIjokbWFpbiA9ICJDSEFOTkVMIjticmVhaztjYXNlICJJTUFHRSI6JG1haW4gPSAiSU1BR0UiOyRyc3NfY2hhbm5lbFsiSU1BR0UiXSA9IGFycmF5KCk7YnJlYWs7Y2FzZSAiSVRFTSI6JG1haW4gPSAiSVRFTVMiO2JyZWFrO2RlZmF1bHQ6JGN1cnJlbnRseV93cml0aW5nID0gJG5hbWU7YnJlYWs7fX1mdW5jdGlvbiBmaW5pc2hFbGVtZW50KCRwYXJzZXIsICRuYW1lKSB7Z2xvYmFsICRyc3NfY2hhbm5lbCwgJGN1cnJlbnRseV93cml0aW5nLCAkaXRlbV9jb3VudGVyOyRjdXJyZW50bHlfd3JpdGluZyA9ICIiO2lmICgkbmFtZSA9PSAiSVRFTSIpIHskaXRlbV9jb3VudGVyKys7fX1mdW5jdGlvbiBjaGFyRGF0YSgkcGFyc2VyLCAkZGF0YSkge2dsb2JhbCAkcnNzX2NoYW5uZWwsICRjdXJyZW50bHlfd3JpdGluZywgJG1haW4sICRpdGVtX2NvdW50ZXI7aWYgKCRjdXJyZW50bHlfd3JpdGluZyAhPSAiIikge3N3aXRjaCgkbWFpbikge2Nhc2UgIkNIQU5ORUwiOmlmIChpc3NldCgkcnNzX2NoYW5uZWxbJGN1cnJlbnRseV93cml0aW5nXSkpIHskcnNzX2NoYW5uZWxbJGN1cnJlbnRseV93cml0aW5nXSAuPSAkZGF0YTt9IGVsc2UgeyRyc3NfY2hhbm5lbFskY3VycmVudGx5X3dyaXRpbmddID0gJGRhdGE7fWJyZWFrO2Nhc2UgIklNQUdFIjppZiAoaXNzZXQoJHJzc19jaGFubmVsWyRtYWluXVskY3VycmVudGx5X3dyaXRpbmddKSkgeyRyc3NfY2hhbm5lbFskbWFpbl1bJGN1cnJlbnRseV93cml0aW5nXSAuPSAkZGF0YTt9IGVsc2UgeyRyc3NfY2hhbm5lbFskbWFpbl1bJGN1cnJlbnRseV93cml0aW5nXSA9ICRkYXRhO31icmVhaztjYXNlICJJVEVNUyI6aWYgKGlzc2V0KCRyc3NfY2hhbm5lbFskbWFpbl1bJGl0ZW1fY291bnRlcl1bJGN1cnJlbnRseV93cml0aW5nXSkpIHskcnNzX2NoYW5uZWxbJG1haW5dWyRpdGVtX2NvdW50ZXJdWyRjdXJyZW50bHlfd3JpdGluZ10gLj0gJGRhdGE7fSBlbHNlIHskcnNzX2NoYW5uZWxbJG1haW5dWyRpdGVtX2NvdW50ZXJdWyRjdXJyZW50bHlfd3JpdGluZ10gPSAkZGF0YTt9YnJlYWs7fX19JHhtbF9wYXJzZXIgPSB4bWxfcGFyc2VyX2NyZWF0ZSgpO3htbF9zZXRfZWxlbWVudF9oYW5kbGVyKCR4bWxfcGFyc2VyLCAiYmVnaW5FbGVtZW50IiwgImZpbmlzaEVsZW1lbnQiKTt4bWxfc2V0X2NoYXJhY3Rlcl9kYXRhX2hhbmRsZXIoJHhtbF9wYXJzZXIsICJjaGFyRGF0YSIpO2lmICghKCRmcCA9IGZvcGVuKCRmaWxlLCAiciIpKSkge2RpZSgiVmFsaWRhdGlvbiBFcnJvci4gQ29udGFjdCBIZWxpb3MgU3VwcG9ydC4iKTt9d2hpbGUgKCRkYXRhID0gZnJlYWQoJGZwLCA0MDk2KSkge2lmICgheG1sX3BhcnNlKCR4bWxfcGFyc2VyLCAkZGF0YSwgZmVvZigkZnApKSkge2RpZShzcHJpbnRmKCJWYWxpZGF0aW9uIEVycm9yLiBDb250YWN0IEhlbGlvcyBTdXBwb3J0LiIseG1sX2Vycm9yX3N0cmluZyh4bWxfZ2V0X2Vycm9yX2NvZGUoJHhtbF9wYXJzZXIpKSx4bWxfZ2V0X2N1cmVudF9saW5lX251bWJlcigkeG1sX3BhcnNlcikpKTt9fXhtbF9wYXJzZXJfZnJlZSgkeG1sX3BhcnNlcik7aWYgKGlzc2V0KCRyc3NfY2hhbm5lbFsiSVRFTVMiXSkpIHtpZiAoY291bnQoJHJzc19jaGFubmVsWyJJVEVNUyJdKSA+IDApIHtpZigkcnNzX2NoYW5uZWxbIklURU1TIl1bMF1bIlZBTElEIl0gPT0gMSl7bXlzcWxfcXVlcnkoIlVQREFURSAiIC4gSENfVGJsUHJlZml4IC4gInNldHRpbmdzIFNFVCBTZXR0aW5nVmFsdWUgPSAnIiAuIG1kNShkYXRlKCJZLW0tZCIsIG1rdGltZSggMCwgMCwgMCwgZGF0ZSgibSIpLCAxLCBkYXRlKCJZIikpKSkuICInIFdIRVJFIFBrSUQgPSAyMCIpO30gZWxzZSB7ZXhpdCgiSGVsaW9zIGNvdWxkIG5vdCB2YWxpZGF0ZS4gUGxlYXNlIGJlIHN1cmUgeW91ciB2YWxpZGF0aW9uIGNvZGUgaXMgY29ycmVjdC4gRW1haWwgPGEgaHJlZj1cIm1haWx0bzpoZWxpb3NAcmVmcmVzaHdlYmRldi5jb21cIj5jdXN0b21lciBzdXBwb3J0PC9hPiBmb3IgYXNzaXN0YW5jZS4iKTt9fSBlbHNlIHtleGl0KCJIZWxpb3MgY291bGQgbm90IHZhbGlkYXRlLiBQbGVhc2UgYmUgc3VyZSB5b3VyIHZhbGlkYXRpb24gY29kZSBpcyBjb3JyZWN0LiBFbWFpbCA8YSBocmVmPVwibWFpbHRvOmhlbGlvc0ByZWZyZXNod2ViZGV2LmNvbVwiPmN1c3RvbWVyIHN1cHBvcnQ8L2E+IGZvciBhc3Npc3RhbmNlLiIpO319IGVsc2Uge2V4aXQoIkhlbGlvcyBjb3VsZCBub3QgdmFsaWRhdGUuIFBsZWFzZSBiZSBzdXJlIHlvdXIgdmFsaWRhdGlvbiBjb2RlIGlzIGNvcnJlY3QuIEVtYWlsIDxhIGhyZWY9XCJtYWlsdG86aGVsaW9zQHJlZnJlc2h3ZWJkZXYuY29tXCI+Y3VzdG9tZXIgc3VwcG9ydDwvYT4gZm9yIGFzc2lzdGFuY2UuIik7fX19fQ=='));
	
	if(file_exists('setup/') || file_exists('../events/setup')){
	?>	<link rel="stylesheet" type="text/css" href="<?echo CalRoot;?>/css/helios.css">
		<div style="width:400px;"><?feedback(3,"Please delete the setup directory<br>before using Helios.");?></div><?
		exit;
	}//end if
	clearstatcache();
?>