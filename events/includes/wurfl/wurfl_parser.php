<?php
/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is WURFL PHP Libraries.
 *
 * The Initial Developer of the Original Code is
 * Andrea Trasatti.
 * Portions created by the Initial Developer are Copyright (C) 2005
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s): Herouth Maoz.
 *
 * ***** END LICENSE BLOCK ***** */

/*
 * $Id: wurfl_parser.php,v 1.16 2005/04/16 16:04:21 atrasatti Exp $
 * $RCSfile: wurfl_parser.php,v $ v2.1 beta2 (Apr, 16 2005)
 * Author: Andrea Trasatti ( atrasatti AT users DOT sourceforge DOT net )
 * Multicache implementation: Herouth Maoz ( herouth AT spamcop DOT net )
 *
*/

if ( !defined('WURFL_CONFIG') )
	@require_once('./wurfl_config.php');

if ( !defined('WURFL_CONFIG') )
	die("NO CONFIGURATION");

$wurfl = array();
$wurfl_agents = array();
$patch_params = Array();

// Author: Herouth Maoz
// Check if var_export has the bug that eliminates empty string keys. Returns
// true if the bug exists
function var_export_bug()
{
    if ( ! function_exists( 'var_export' ) ) {
        return false;
    }
    $a = array( '' => '!' );
    $export_a = var_export( $a, true );
    eval ( "\$b = $export_a;" );
    
    return count( $b ) != count( $a );
}
// this function check WURFL patch integrity/validity
function checkpatch($name, $attr) {
	global $wurfl, $patch_params, $checkpatch_result;

	if ( $name == 'wurfl_patch' ) {
		$checkpatch_result['wurfl_patch'] = true;
		return true;
	} else if ( !$checkpatch_result['wurfl_patch'] ) {
		$checkpatch_result['wurfl_patch'] = false;
		wurfl_log('checkpatch', "no wurfl_patch tag! Patch file ignored.");
		return false;
	}
	if ( $name == 'devices' ) {
		$checkpatch_result['devices'] = true;
		return true;
	} else if ( !$checkpatch_result['devices'] ) {
		$checkpatch_result['devices'] = false;
		wurfl_log('checkpatch', "no devices tag! Patch file ignored.");
		return false;
	}
	if ( $name == 'device' ) {
		if ( isset($wurfl['devices'][$attr['id']]) ) {
			if ( $wurfl['devices'][$attr['id']]['user_agent'] != $attr['user_agent'] ) {
				$checkpatch_result['device']['id'][$attr["id"]]['patch'] = false;
				$checkpatch_result['device']['id'][$attr["id"]]['reason'] = 'user agent mismatch, orig='.$wurfl['devices'][$attr['id']]['user_agent'].', new='.$attr['user_agent'].', id='.$attr['id'].', fall_back='.$attr['fall_back'];
			}
		}
		/*
		 * checking of the fall_back is disabled. I might define a device's fall_back which will be defined later in the patch file.
		 * fall_backs checking could be done after merging.
		if ( $attr['id'] == 'generic' && $attr['user_agent'] == '' && $attr['fall_back'] == 'root' ) {
			// generic device, everything's ok.
		} else if ( !isset($wurfl['devices'][$attr['fall_back']]) ) {
			$checkpatch_result['device']['id'][$attr["id"]]['patch'] = false;
			$checkpatch_result['device']['id'][$attr["id"]]['reason'] .= 'wrong fall_back, id='.$attr['id'].', fall_back='.$attr['fall_back'];
		}
		 */
		if ( isset($checkpatch_result['device']['id'][$attr["id"]]['patch'])
			&& !$checkpatch_result['device']['id'][$attr["id"]]['patch'] ) {
			wurfl_log('checkpatch', "ERROR:".$checkpatch_result['device']['id'][$attr["id"]]['reason']);
			return false;
		}
	}
	return true;
}

function startElement($parser, $name, $attr) {
	global $wurfl, $curr_event, $curr_device, $curr_group, $fp_cache, $check_patch_params, $checkpatch_result;

	if ( $check_patch_params ) {
		// if the patch file checks fail I don't merge info retrived
		if ( !checkpatch($name, $attr) ) {
			wurfl_log('startElement', "error on $name, ".$attr['id']);
			$curr_device = 'dump_anything';
			return;
		} else if ( $curr_device == 'dump_anything' && $name != 'device' ) {
			// this capability is referred to a device that was erroneously defined for some reason, skip it
			wurfl_log('startElement', $name." cannot be merged, the device was skipped because of an error");
			return;
		}
	}

	switch($name) {
		case "ver":
		case "last_updated":
		case "official_url":
		case "statement":
			//cdata will take care of these, I'm just defining the array
			$wurfl[$name]="";
			//$curr_event=$wurfl[$name];
			break;
		case "maintainers":
		case "maintainer":
		case "authors":
		case "author":
		case "contributors":
		case "contributor":
			if ( sizeof($attr) > 0 ) {
				// dirty trick: author is child of authors, contributor is child of contributors
				while ($t = each($attr)) {
					// example: $wurfl["authors"]["author"]["name"]="Andrea Trasatti";
					$wurfl[$name."s"][$name][$attr["name"]][$t[0]]=$t[1];
				}
			}
			break;
		case "device":
			if ( ($attr["user_agent"] == "" || ! $attr["user_agent"]) && $attr["id"]!="generic" ) {
				die("No user agent and I am not generic!! id=".$attr["id"]." HELP");
			}
			if ( sizeof($attr) > 0 ) {
				while ($t = each($attr)) {
					// example: $wurfl["devices"]["ericsson_generic"]["fall_back"]="generic";
					$wurfl["devices"][$attr["id"]][$t[0]]=$t[1];
				}
			}
			$curr_device=$attr["id"];
			break;
		case "group":
			// this HAS NOT to be executed or we will define the id as string and then reuse it as array: ERROR
			//$wurfl["devices"][$curr_device][$attr["id"]]=$attr["id"];
			$curr_group=$attr["id"];
			break;
		case "capability":
			if ( $attr["value"] == 'true' ) {
				$value = true;
			} else if ( $attr["value"] == 'false' ) {
				$value =  false;
			} else {
				$value = $attr["value"];
				$intval = intval($value);
				if ( strcmp($value, $intval) == 0 ) {
					$value = $intval;
				}
			}
			$wurfl["devices"][$curr_device][$curr_group][$attr["name"]]=$value;
			break;
		case "devices":
			// This might look useless but it's good when you want to parse only the devices and skip the rest
			if ( !isset($wurfl["devices"]) )
				$wurfl["devices"]=array();
			break;
		case "wurfl_patch":
			// opening tag of the patch file
		case "wurfl":
			// opening tag of the WURFL, nothing to do
			break;
		case "default":
			// unknown events are not welcome
			die($name." is an unknown event<br>");
			break;
	}
}


function endElement($parser, $name) {
	global $wurfl, $curr_event, $curr_device, $curr_group;
	switch ($name) {
		case "group":
			break;
		case "device":
			break;
		case "ver":
		case "last_updated":
		case "official_url":
		case "statement":
			$wurfl[$name]=$curr_event;
			// referring to $GLOBALS to unset curr_event because unset will not destroy 
			// a global variable unless called in this way
			unset($GLOBALS['curr_event']);
			break;
		default:
			break;
	}

}

function characterData($parser, $data) {
	global $curr_event;
	if (trim($data) != "" ) {
		$curr_event.=$data;
		//echo "data=".$data."<br>\n";
	}
}

function load_cache() {
	// Setting default values
	$cache_stat = 0;
	$wurfl = $wurfl_agents = array();

	if ( WURFL_USE_CACHE && file_exists(CACHE_FILE) ) {
		include(CACHE_FILE);
	}
	return Array($cache_stat, $wurfl, $wurfl_agents);
}

function stat_cache() {
	$cache_stat = 0;
	if ( WURFL_USE_CACHE && file_exists(CACHE_FILE) ) {
		$cache_stat = filemtime(CACHE_FILE);
	}
	return $cache_stat;
}

function parse() {
	global $wurfl, $wurfl_stat, $check_patch_params, $checkpatch_result;
	$wurfl = Array();

	$xml_parser = xml_parser_create();
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler($xml_parser, "characterData"); 
	if ( !file_exists(WURFL_FILE) ) {
		wurfl_log('parse', WURFL_FILE." does not exist");
		die(WURFL_FILE." does not exist");
	}
	if (!($fp = fopen(WURFL_FILE, "r"))) {
		wurfl_log('parse', "could not open XML input");
		die("could not open XML input");
	}

	//$count = 0;
	while ($data = fread($fp, 4096)) {
		//$count++;
		if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("XML error: %s at line %d",
			    xml_error_string(xml_get_error_code($xml_parser)),
			    xml_get_current_line_number($xml_parser)));
		}
		//if ( $count > 30 )
			//return;
	}

	fclose($fp);
	xml_parser_free($xml_parser);

	$check_patch_params = false;
	if ( defined('WURFL_PATCH_FILE') && file_exists(WURFL_PATCH_FILE) ) {
		wurfl_log('parse', "Trying to load XML patch file: ".WURFL_PATCH_FILE);
		$check_patch_params = true;
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_element_handler($xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($xml_parser, "characterData"); 
		if (!($fp = fopen(WURFL_PATCH_FILE, "r"))) {
			wurfl_log('parse', "could not open XML patch file: ".WURFL_PATCH_FILE);
		}
		while ($data = fread($fp, 4096)) {
		    if (!xml_parse($xml_parser, $data, feof($fp))) {
			die(sprintf("XML error: %s at line %d",
				    xml_error_string(xml_get_error_code($xml_parser)),
				    xml_get_current_line_number($xml_parser)));
		    }
		}
		fclose($fp);
		xml_parser_free($xml_parser);
		// logging? $checkpatch_result['device']['id']
	} else if ( defined('WURFL_PATCH_FILE') && !file_exists(WURFL_PATCH_FILE) ) {
		wurfl_log('parse', WURFL_PATCH_FILE." does not exist");
	} else {
		wurfl_log('parse', "No XML patch file defined");
	}


	//reset($wurfl);
	//echo "<pre>";
	//print_r($wurfl);
	//echo "</pre>";

	reset($wurfl);
	$devices = $wurfl["devices"];

	// I check if var_export loses any empty key, in this case I force the generic
	// device.
	if ( var_export_bug() ) {
		$wurfl_agents['generic'] = 'generic';
	}
	foreach($devices as $one) {
		$wurfl_agents[$one['user_agent']] = $one['id'];
	}

	reset($wurfl);
	reset($wurfl_agents);
	if ( WURFL_USE_CACHE ) {
		if ( defined("WURFL_AGENT2ID_FILE") && file_exists(WURFL_AGENT2ID_FILE) && !is_writeable(WURFL_AGENT2ID_FILE) ) {
			wurfl_log('parse', "ERROR: Unable to remove ".WURFL_AGENT2ID_FILE);
			//die ('Unable to remove '.WURFL_AGENT2ID_FILE);
			return;
		}
		if ( isset($wurfl_stat) ) {
			$cache_stat = $wurfl_stat;
		} else {
			$cache_stat = $wurfl_stat = filemtime(WURFL_FILE);
			if ( defined('WURFL_PATCH_FILE') && file_exists(WURFL_PATCH_FILE) ) {
				$patch_stat = filemtime(WURFL_PATCH_FILE);
				if ( $patch_stat > $wurfl_stat ) {
					// if the patch file is newer than the WURFL I set wurfl_stat to that time
					$wurfl_stat = $patch_stat;
				}
			}
		}
		if ( WURFL_USE_MULTICACHE ) {
			// If using Multicache remove old cache files
			$wurfl_temp_devices = $wurfl['devices'];
			$wurfl['devices'] = array();
			//Attempt to remove all existing multicache files
			if ( defined("MULTICACHE_DIR") && is_dir(MULTICACHE_DIR) && !is_writeable(MULTICACHE_DIR) ) {
				wurfl_log('parse', "ERROR: Unable to remove files from".MULTICACHE_DIR);
				return;
			}
			// Get all the agent file names in the multicache directory. Use
			// glob if available
			if ( function_exists( 'glob' ) ) {
				$filelist = glob( MULTICACHE_DIR . "/*" . MULTICACHE_SUFFIX );
			} else {
				if ( $dh = @opendir( MULTICACHE_DIR ) ) {
					$filelist = array();
					while (false !== ($file = @readdir($dh))) {
						$filename = MULTICACHE_DIR . "/$file";
						if ( is_file( $filename ) ) {
							$filelist[] = $filename;
						}
					}
					@closedir( $dh );
				}
			}
			foreach ( $filelist as $filename ) {
				@unlink( $filename );
			}
		}
		$php_version = PHP_VERSION;
		list($php_main_version, $php_subversion, $php_subsubversion) = explode('.', $php_version);
		$fp_cache= fopen(CACHE_FILE, "w");
		fwrite($fp_cache, "<?php\n");
		// it seems until PHP 4.3.2 var_export had a problem with apostrophes in array keys
		if ( ($php_main_version == 4 && $php_subversion > 2 && $php_subsubversion > 2) || $php_main_version > 4 ) {
			if ( !WURFL_USE_MULTICACHE ) {
				$wurfl_to_file = var_export($wurfl, true);
			}
			$wurfl_agents_to_file = var_export($wurfl_agents, true);
			$cache_stat_to_file = var_export($cache_stat, true);
			fwrite($fp_cache, "\$cache_stat=$cache_stat_to_file;\n");
			if ( !WURFL_USE_MULTICACHE ) {
				fwrite($fp_cache, "\$wurfl=$wurfl_to_file;\n");
			}
			fwrite($fp_cache, "\$wurfl_agents=$wurfl_agents_to_file;\n");
		} else {
			if ( !WURFL_USE_MULTICACHE ) {
				$wurfl_to_file = urlencode(serialize($wurfl));
			}
			$wurfl_agents_to_file = urlencode(serialize($wurfl_agents));
			$cache_stat_to_file = urlencode(serialize($cache_stat));
			fwrite($fp_cache, "\$cache_stat=unserialize(urldecode(\"". $cache_stat_to_file ."\"));\n");
			if ( !WURFL_USE_MULTICACHE ) {
				fwrite($fp_cache, "\$wurfl=unserialize(urldecode(\"". $wurfl_to_file ."\"));\n");
			}
			fwrite($fp_cache, "\$wurfl_agents=unserialize(urldecode(\"". $wurfl_agents_to_file ."\"));\n");
		}
		fwrite($fp_cache, "?>\n");
		fclose($fp_cache);
		if ( defined("WURFL_AGENT2ID_FILE") && file_exists(WURFL_AGENT2ID_FILE) ) {
			@unlink(WURFL_AGENT2ID_FILE);
		}
		if ( WURFL_USE_MULTICACHE ) {
			// Return the capabilities to the wurfl structure
			$wurfl['devices'] = &$wurfl_temp_devices;
			// Write multicache files
			if ( FORCED_UPDATE === true )
				$path = MULTICACHE_TMP_DIR;
			else
				$path = MULTICACHE_DIR;
			if ( !is_dir($path) )
				@mkdir($path);
			foreach ( $wurfl_temp_devices as $id => $capabilities ) {
				$fname = urlencode( $id );
				$varname = addcslashes( $id, "'\\" );

				$fp_cache = fopen( $path . "/$fname" . MULTICACHE_SUFFIX, 'w' );

				fwrite($fp_cache, "<?php\n");
                                if ( ($php_main_version == 4 && $php_subversion > 2) || $php_main_version > 4 ) {
					$wurfl_to_file = var_export($capabilities, true);
					fwrite($fp_cache, "\$_cached_devices['$varname']=$wurfl_to_file;\n");
				} else {
					$wurfl_to_file = urlencode(serialize($capabilities));
					fwrite($fp_cache, "\$_cached_devices['$varname']=unserialize(urldecode(\"". $wurfl_to_file ."\"));\n");
				}
				fwrite($fp_cache, "?>\n");
				fclose($fp_cache);
			}
		}
		// It's probably not really worth encoding cache.php if you're using Multicache
		if ( 0 && function_exists('mmcache_encode') ) {
			$empty= '';
			set_time_limit(60);
			$to_file = mmcache_encode(CACHE_FILE, $empty);
			$to_file = '<?php if (!is_callable("mmcache_load") && !@dl((PHP_OS=="WINNT"||PHP_OS=="WIN32")?"TurckLoader.dll":"TurckLoader.so")) { die("This PHP script has been encoded with Turck MMcache, to run it you must install <a href=\"http://turck-mmcache.sourceforge.net/\">Turck MMCache or Turck Loader</a>");} return mmcache_load(\''.$to_file."');?>\n";
			$fp_cache= fopen(CACHE_FILE, "wb");
			fwrite($fp_cache, $to_file);
			fclose($fp_cache);
		}
		//echo "cache written";
	} else {
		// not using WURFL cache
		$cache_stat = 0;
	}

	return Array($cache_stat, $wurfl, $wurfl_agents);

} // end of function parse

function wurfl_log($func, $msg, $logtype=3) {
	// Thanks laacz
	$_textToLog = date('r')." [".php_uname('n')." ".getmypid()."]"."[$func] ".$msg;

	if ( $logtype == 3 && is_file(WURFL_LOG_FILE) ) {
		if ( !@error_log($_textToLog."\n", 3, WURFL_LOG_FILE) ) {
			error_log("Unable to log to ".WURFL_LOG_FILE." log_message:$_textToLog"); // logging in the webserver's log file
		}
	} else {
		error_log($_textToLog); // logging in the webserver's log file
	}
}

if ( !file_exists(WURFL_FILE) ) {
	wurfl_log('main', WURFL_FILE." does not exist");
	die(WURFL_FILE." does not exist");
}

if ( WURFL_AUTOLOAD === true ) {
	$wurfl_stat = filemtime(WURFL_FILE);
	$cache_stat = stat_cache();
	if ( defined('WURFL_PATCH_FILE') && file_exists(WURFL_PATCH_FILE) ) {
		$patch_stat = filemtime(WURFL_PATCH_FILE);
	} else {
		$patch_stat = $wurfl_stat;
	}
	if (WURFL_USE_CACHE && $wurfl_stat <= $cache_stat && $patch_stat <= $cache_stat) {
		// cache file is updated
		//echo "wurfl date = ".$wurfl_stat."<br>\n";
		//echo "patch date = ".$patch_stat."<br>\n";
		//echo "cache date = ".$cache_stat."<br>\n";

		list($cache_stat, $wurfl, $wurfl_agents) = load_cache();

		// echo "cache loaded";
	} else {
		list($cache_stat, $wurfl, $wurfl_agents) = parse();
	}
}
?>
