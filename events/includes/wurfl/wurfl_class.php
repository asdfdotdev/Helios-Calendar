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
 * Portions created by the Initial Developer are Copyright (C) 2004-2005
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s): Herouth Maoz.
 *
 * ***** END LICENSE BLOCK ***** */

/*
 * $Id: wurfl_class.php,v 1.10 2005/05/03 14:28:47 atrasatti Exp $
 * $RCSfile: wurfl_class.php,v $ v2.1 beta2 (Apr, 16 2005)
 * Author: Andrea Trasatti ( atrasatti AT users DOT sourceforge DOT net )
 * Multicache implementation: Herouth Maoz ( herouth AT spamcop DOT net )
 *
*/

if ( !defined('WURFL_CONFIG') )
	@require_once('./wurfl_config.php');

if ( !defined('WURFL_CONFIG') )
	die("NO CONFIGURATION");

if ( defined('WURFL_PARSER_FILE') )
	require_once(WURFL_PARSER_FILE);
else
	require_once("./wurfl_parser.php");

class wurfl_class {
	/**
	 * associative array created by wurfl_parser.php
	 * @var associative array
	 */
	var $_wurfl="";

	/**
	 * associative array user_agent=>id
	 * @var associative array
	 */
	var $_wurfl_agents="";

	/**
	 * device's complete user agent (just in case)
	 * @var string
	 */
	var $user_agent="";

	/**
	 * best fitting user agent found in the xml
	 * @var string
	 */
	var $wurfl_agent="";

	/**
	 * wurfl_id
	 * @var string
	 */
	var $id="";

	/**
	 * if true, Openwave's GUI (mostly wml 1.3) is supported
	 * @var bool
	 */
	var $GUI=false;

	/**
	 * device brand (manufacturer)
	 * @var string
	 */
	var $brand='';

	/**
	 * device model
	 * @var string
	 */
	var $model='';

	/**
	 * if this is a WAP device, this is set to true
	 * @var bool
	 */
	var $browser_is_wap=false;

	/**
	 * associative array with all the device's capabilities.
	 * 
	 * Example :
	 * $this->capabilities['downloadfun']['downloadfun_support'] 
	 *	true if downloadfun is supported, otherwise false
	 *
	 * @var associative array
	 */
	var $capabilities=array();

	/**
	 * Constructor, checks the user agent and sets the variables.
	 *
	 * @param $_ua	device's user_agent
	 * @param $wurfl	wurfl in array format as provided by wurfl_parser
	 * @param $wurfl_agents	array set by wurfl_parser
	 * @param $_check_accept	if true will check accept headers for wml, wap, xhtml.
	 *					Note: any i-mode device might be cut out
	 *
	 * @access public
	 *
	 */
	function wurfl_class($wurfl=Array(), $wurfl_agents=Array()) {

		$this->_wurfl = $wurfl;
		$this->_wurfl_agents = $wurfl_agents;
		$this->_toLog('constructor', 'Class Initiated', LOG_NOTICE);
	}

	/**
	 * Given the device's id reads all its capabilities
	 *
	 * @param $_id	wurfl_id di un telefonino
	 *
	 * @access private
	 *
	 */
	function _GetFullCapabilities($_id) {
		$this->_toLog('_GetFullCapabilities', "searching for $_id", LOG_INFO);
		$$_id = $this->_GetDeviceCapabilitiesFromId($_id);
		$_curr_device = $$_id;
		$_fallback_list[] = $_id;
		while ( $_curr_device['fall_back'] != 'generic' && $_curr_device['fall_back'] != 'root' ) {
			$_fallback_list[] = $_curr_device['fall_back'];
			$this->_toLog('_GetFullCapabilities', 'parent device:'.$_curr_device['fall_back'].' now going to read its capabilities', LOG_INFO);
			$$_curr_device['fall_back'] = $this->_GetDeviceCapabilitiesFromId($_curr_device['fall_back']);
			$_curr_device = $$_curr_device['fall_back'];
		}
		$this->_toLog('_GetFullCapabilities', 'reading capabilities of \'generic\' device', LOG_INFO);
		$generic = $this->_GetDeviceCapabilitiesFromId('generic');
		$_fallback_list[] = 'generic';

		end($_fallback_list);

		$_final = $generic;
		for ( $i=sizeof($_fallback_list)-2; $i>= 0; $i-- ) {
			$curr_device = $_fallback_list[$i];
//echo "capabilities di $curr_device<br>\n";
			while ( list($key, $val) = each($$curr_device) ) {
				if ( is_array($val) ) {
//echo "array_merge per $key:<br>";
//echo "<pre>\n";
//var_export($_final[$key]);
//var_export($val);
//echo "</pre>\n";
					$_final[$key] = array_merge($_final[$key], $val);
				} else {
//echo "scrivo $key=$val<br>\n";
					$_final[$key] = $val;
				}
			}
		}

		$this->capabilities = $_final;
	}

	/**
	 * Given a device id reads its capabilities
	 *
	 * @param $_id	device's wurfl_id
	 *
	 * @access private
	 *
	 */
	function _GetDeviceCapabilitiesFromId($_id) {
		$this->_toLog('_GetDeviceCapabilitiesFromId', "reading id:$_id", LOG_INFO);
		if ( $_id == 'upgui_generic' ) {
			$this->GUI = true;
		}
		if ( in_array($_id, $this->_wurfl_agents) ) {
			$this->_toLog('_GetDeviceCapabilitiesFromId', 'I have it in wurfl_agents cache, done', LOG_INFO);
			// If the device for this id does not exist, and we use multicache,
			// attempt to load the cache entry that matches the current id.
			if ( ! isset( $this->_wurfl['devices'][$_id] ) && WURFL_USE_MULTICACHE ) {
				for ($i=0;$i<3;$i++) {
					if ( is_file(MULTICACHE_TOUCH) )
						sleep(5);
					else
						break;
				}
				if ( $i>=3 ) {
					$this->_toLog('_GetDeviceCapabilitiesFromId', "CACHE CORRUPTED! ".MULTICACHE_TOUCH." on my way", LOG_WARNING);
					die("Updating cache stuck");
				}
				$fname = MULTICACHE_DIR . "/" . urlencode( $_id ) . MULTICACHE_SUFFIX;
				$genericfname = MULTICACHE_DIR . "/generic" . MULTICACHE_SUFFIX;
				if ( !is_file($fname) && is_file($genericfname) ) {
					$this->_toLog('_GetDeviceCapabilitiesFromId', "the id $_id is not present in Multicache files, using the generic: CACHE CORRUPTED!", LOG_WARNING);
					$fname = $genericfname;
				} else if ( !is_file($fname) && !is_file($genericfname) ) {
					$this->_toLog('_GetDeviceCapabilitiesFromId', "the id $_id is not present in Multicache files, nor the generic: CACHE CORRUPTED!", LOG_ERR);
					die("the id $_id is not present in Multicache");
				}
				@include( $fname );
				$this->_wurfl['devices'][$_id] = $_cached_devices[$_id];
			}
			return $this->_wurfl['devices'][$_id];
		}
		$this->_toLog('_GetDeviceCapabilitiesFromId', "the id $_id is not present in wurfl_agents", LOG_ERR);
		die("the id $_id is not present in wurfl_agents");
		// I should never get here!!
		return false;
	}

	/**
	 * Given the user_agent reads the device's capabilities
	 *
	 * @param $_user_agent	device's user_agent
	 *
	 * @access private
	 *
	 * @return boolean
	 *
	 */
	function GetDeviceCapabilitiesFromAgent($_user_agent, $_check_accept=false) {
		// Would be cool to log user agent and headers to future use to feed WURFL
		// Resetting properties
		$this->user_agent = '';
		$this->wurfl_agent = '';
		$this->id = '';
		$this->GUI = false;
		$this->brand = '';
		$this->model = '';
		$this->browser_is_wap = false;
		$this->capabilities = array();

		// removing the possible Openwave MAG tag
		$_user_agent = trim(ereg_replace("UP.Link.*", "", $_user_agent));
		if (	( stristr($_user_agent, 'Opera') && stristr($_user_agent, 'Windows') )
			|| ( stristr($_user_agent, 'Opera') && stristr($_user_agent, 'Linux') )
			|| stristr($_user_agent, 'Gecko')
			|| ( (stristr($_user_agent, 'MSIE 6') || stristr($_user_agent, 'MSIE 5') ) && !stristr($_user_agent, 'MIDP') && !stristr($_user_agent, 'Windows CE') && !stristr($_user_agent, 'Symbian') )
			) {
			// This is a web browser. Not even searching
			$this->_toLog('constructor', 'Web browser', LOG_INFO);
			$this->browser_is_wap=false;
			$this->capabilities['product_info']['brand_name'] = 'Generic Web browser';
			$this->capabilities['product_info']['model_name'] = '1.0';
			$this->capabilities['product_info']['is_wireless_device'] = false;
			$this->capabilities['product_info']['device_claims_web_support'] = true;
			return true;
		} else if ( $_check_accept == true ) {
			if (
			     !eregi('wml', $_SERVER['HTTP_ACCEPT'])
			     && !eregi('wap', $_SERVER['HTTP_ACCEPT'])
			     && !eregi('xhtml', $_SERVER['HTTP_ACCEPT'])
			     ) {
				$this->_toLog('constructor', 'This browser does not support wml, nor wap, nor xhtml, we will never know if it was an i-mode browser', LOG_WARNING);
				$this->browser_is_wap=false;
			}
		}
		$this->_toLog('GetDeviceCapabilitiesFromAgent', 'searching for '.$_user_agent, LOG_INFO);
		if ( trim($_user_agent) == '' || !$_user_agent ) {
			// NO USER AGENT??? This is not a WAP device
			$this->_toLog('GetDeviceCapabilitiesFromAgent', 'No user agent', LOG_ERR);
			$this->browser_is_wap=false;
			return false;
		}
		if ( WURFL_USE_CACHE === true ) {
			$this->_ReadFastAgentToId($_user_agent);
			// if I find the device in my cache I'm done
			if ( $this->browser_is_wap === true ) {
				$this->_toLog('GetDeviceCapabilitiesFromAgent', 'Device found in local cache, the id is '.$this->id, LOG_INFO);
				if ( count($this->capabilities) == 0 )
					$this->_GetFullCapabilities($this->id);
				else
					$this->_toLog('GetDeviceCapabilitiesFromAgent', 'capabilities found in cache', LOG_INFO);
				return true;
			} else if ( count($this->_wurfl) == 0 ) {
				$this->_toLog('GetDeviceCapabilitiesFromAgent', 'cache enabled, WURFL is not loaded, now loading', LOG_INFO);
				if ( $this->_cacheIsValid() ) {
					$this->_toLog('GetDeviceCapabilitiesFromAgent', 'loading WURFL from cache', LOG_INFO);
					list($cache_stat, $this->_wurfl, $this->_wurfl_agents) = load_cache();
				} else {
					$this->_toLog('GetDeviceCapabilitiesFromAgent', 'loading WURFL from XML', LOG_INFO);
					$xml_info = parse();
					$cache_stat = $xml_info[0];
					$this->_wurfl = $xml_info[1];
					$this->_wurfl_agents = $xml_info[2];
				}
			}
		} else if ( WURFL_AUTOLOAD === false ) {
			// if not using cache and for some reason AUTOLOAD is off, I need to load it
			if ( count($this->_wurfl) == 0 ) {
				$this->_toLog('GetDeviceCapabilitiesFromAgent', 'WURFL is not loaded, now loading', LOG_INFO);
				$xml_info = parse();
				$cache_stat = $xml_info[0];
				$this->_wurfl = $xml_info[1];
				$this->_wurfl_agents = $xml_info[2];
			}
		} else {
				// If I'm here it means cache is disabled and autoload is on
				global $wurfl, $wurfl_agents;
				$this->_wurfl = $wurfl;
				$this->_wurfl_agents = $wurfl_agents;
		}
		
		$_ua = $_user_agent;
		$_ua_len = strlen($_ua);
		$_wurfl_user_agents = array_keys($this->_wurfl_agents);
		// Searching in wurfl_agents
		// The user_agent should not become shorter than 4 characters
		$this->_toLog('GetDeviceCapabilitiesFromAgent', 'Searching in the agent database', LOG_INFO);
		// I request to set a short list of UA's among which I should search an unknown user agent
		$_short_ua_len = 4;
		$_set_short_wurfl_ua = true;
		$_last_good_short_ua = array();
		while ( $_ua_len > 4 ) {
			$_short_wurfl_ua = array();
			$_tmp_short_ua = substr($_ua, 0, $_short_ua_len); // The current user agent's first chars
			// DEBUG fast search echo "_tmp_short_ua=$_tmp_short_ua ";
			foreach ( $_wurfl_user_agents as $_x ) {
				// If it was requested to generate a short list of user agents AND the first
				//  characters of the searched user agent and the user agent in WURFL match,
				//  I add the current ID to the short list
				if ( $_set_short_wurfl_ua === true && substr($_x, 0, $_short_ua_len) == $_tmp_short_ua )
					$_short_wurfl_ua[] = $_x;

				if ( substr($_x, 0, $_ua_len) == $_ua ) {
					$this->user_agent = $_user_agent;
					$this->wurfl_agent = $_x;
					$this->id = $this->_wurfl_agents[$_x];
					// calling FullCapabilities to define $this->capabilities
					$this->_GetFullCapabilities($this->id);
					$this->browser_is_wap = true;
					$this->brand = $this->capabilities['product_info']['brand_name'];
					$this->model = $this->capabilities['product_info']['model_name'];
					reset($this->_wurfl_agents);
					reset($_wurfl_user_agents);
					if ( WURFL_USE_CACHE ) {
						$this->_WriteFastAgentToId();
					}
					return true;
				}
			} 
			// If the list of user agents that match the first 4 chars of the current user
			//  agent is empty I can quit searching
			if ( $_short_ua_len == 4 && count($_short_wurfl_ua) == 0 ) {
				// DEBUG fast search echo "no match even for the first 4 chars<br>\n";
				break;
			} else if ( count($_short_wurfl_ua) == 0 ) {
				// I restore the last good list of short user agents
				$_wurfl_user_agents = $_last_good_short_ua;
				// DEBUG fast search echo "restoring last_good_short_ua";
				// I won't continue building a new short user agent list (longer
				//  than this)
				$_set_short_wurfl_ua = false; 
			} else {
				// This is the last list of user agents that matched the first part of
				//  the agent
				$_last_good_short_ua = $_short_wurfl_ua;
				// Next round I search for a short_ua 1 char longer
				$_short_ua_len++;
				// I will search the user agent among a shorter list at the next round!!
				$_wurfl_user_agents = $_short_wurfl_ua;
				// DEBUG fast search echo "short list has ".count($_short_wurfl_ua)." elements";
			}

			// shortening the agent by one each time
			$_ua = substr($_ua, 0, -1);
			$_ua_len--;
			reset($_wurfl_user_agents);
			// DEBUG fast search echo "<br>\n";
		}

		$this->_toLog('GetDeviceCapabilitiesFromAgent', "I couldn't find the device in my list, the headers are my last chance", LOG_WARNING);
		if ( strstr($_user_agent, 'UP.Browser/') && strstr($_user_agent, '(GUI)') ) {
			$this->browser_is_wap = true;
			$this->user_agent = $_user_agent;
			$this->wurfl_agent = 'upgui_generic';
			$this->id = 'upgui_generic';
		} else if ( strstr($_user_agent, 'UP.Browser/') ) {
			$this->browser_is_wap = true;
			$this->user_agent = $_user_agent;
			$this->wurfl_agent = 'uptext_generic';
			$this->id = 'uptext_generic';
		} else if ( eregi('wml', $_SERVER['HTTP_ACCEPT']) || eregi('wap', $_SERVER['HTTP_ACCEPT']) ) {
			$this->browser_is_wap = true;
			$this->user_agent = $_user_agent;
			$this->wurfl_agent = 'generic';
			$this->id = 'generic';
		} else {
			$this->_toLog('GetDeviceCapabilitiesFromAgent', 'This should not be a WAP device, quitting', LOG_WARNING);
			$this->browser_is_wap=false;
			$this->user_agent = $_user_agent;
			$this->wurfl_agent = 'generic';
			$this->id = 'generic';
			return true;
		}
		if ( WURFL_USE_CACHE ) {
			$this->_WriteFastAgentToId($_user_agent);
		}
		// FullCapabilities defines $this->capabilities
		$this->_GetFullCapabilities($this->id);
		return true;
	}

	/**
	 * Given a capability name returns the value (true|false|<anythingelse>)
	 *
	 * @param $capability	capability name as a string
	 *
	 * @access public
	 *
	 */
	function getDeviceCapability($capability) {
		$this->_toLog('_GetDeviceCapability', 'Searching for '.$capability.' as a capability', LOG_INFO);
		$deviceCapabilities = $this->capabilities;
		foreach ( $deviceCapabilities as $group ) {
			if ( !is_array($group) ) {
				continue;
			}
			while ( list($key, $value)=each($group) ) {
				if ($key==$capability) {
					$this->_toLog('_GetDeviceCapability', 'I found it, value is '.$value, LOG_INFO);
					return $value;
				}
			}
		}
		$this->_toLog('_GetDeviceCapability', 'I could not find the requested capability, returning false', LOG_WARNING);
		return false;
	}

	/**
	 * Saves to file the correspondence between user_agent and wurfl_id
	 *
	 * @access private
	 *
	 */
	function _WriteFastAgentToId() {
		$_ua = $this->user_agent;
		if ( is_file(WURFL_AGENT2ID_FILE) && !is_writeable(WURFL_AGENT2ID_FILE) ) {
			$this->_toLog('_WriteFastAgentToId', 'Unable to write '.WURFL_AGENT2ID_FILE, LOG_ERR);
			return;
		} else if ( !is_writeable(dirname(WURFL_AGENT2ID_FILE)) ) {
			$this->_toLog('_WriteFastAgentToId', 'Unable to create file in '.dirname(WURFL_AGENT2ID_FILE), LOG_ERR);
			return;
		}
		$_ua = trim(ereg_replace("UP.Link.*", "", $_ua));
		if ( !is_readable(WURFL_AGENT2ID_FILE) ) {
			if ( is_file(WURFL_AGENT2ID_FILE) ) {
				$this->_toLog('_WriteFastAgentToId', 'Unable to read '.WURFL_AGENT2ID_FILE, LOG_WARNING);
			}
			$cached_agents = Array();
		} else {
			// $cached_agents[0]['user_agent'] = 'SIE-S45/00'; //ua completo
			// $cached_agents[0]['wurfl_agent'] = 'SIE-S45/00'; //ua nel WURFL
			// $cached_agents[0]['id'] = 'sie_s45_ver1';
			// $cached_agents[0]['is_wap'] = true;
			include(WURFL_AGENT2ID_FILE);
		}
		// check if the device is already cached
		foreach($cached_agents as $one) {
			if ( $one['user_agent'] == $_ua ) {
				$this->_toLog('_WriteFastAgentToId', $_ua.' is already cached', LOG_INFO);
				return;
			}
		}
		$new_item_id = count($cached_agents);
		$cached_agents[$new_item_id]['user_agent'] = $_ua; // full UA
//		$cached_agents[$new_item_id]['wurfl_agent'] = $this->wurfl_agent; // corresponding UA stored in WURFL
//		$cached_agents[$new_item_id]['id'] = $this->id; // WURFL unique id
		$cached_agents[$new_item_id]['is_wap'] = true;
		$cached_agents[$new_item_id]['capabilities'] = $this->capabilities;
		$new_item_id++; // increment by one so that it still reflects the array count

		// cache resize in case it gets bigger than MAX_UA_CACHE
		if ( $new_item_id > MAX_UA_CACHE ) {
			$resized_agents = array_slice($cached_agents, ($new_item_id-MAX_UA_CACHE), MAX_UA_CACHE);
			$cached_agents = $resized_agents;
			$this->_toLog('_WriteFastAgentToId', 'Cache resized to '.MAX_UA_CACHE.' elements', LOG_INFO);
		}
		// store in cache file
		$filename = uniqid(WURFL_AGENT2ID_FILE, true);
		$fp_cache = fopen($filename, 'w');
		if ( !$fp_cache ) {
			$this->_toLog('_WriteFastAgentToId', 'Unable to open temp file '.$filename.' for writing', LOG_WARNING);
			return;
		} else {
			$this->_toLog('_WriteFastAgentToId', 'Created temp file '.$filename.' ', LOG_INFO);
		}
		fwrite($fp_cache, "<?php \n");
		fwrite($fp_cache, '$cached_agents = '.var_export($cached_agents, true));
		// If you like serialization better comment the above line, uncomment
		// the following and the line in _ReadFastAgentToId
		//fwrite($fp_cache, '$cached_agents = \''.rawurlencode(serialize($cached_agents))."';\n");
		fwrite($fp_cache, "?>");
		fclose($fp_cache);
		$rv = @rename($filename,WURFL_AGENT2ID_FILE);
		if( !$rv ){
			$this->_toLog('_WriteFastAgentToId', 'Unable to rename '.$filename.' to '. WURFL_AGENT2ID_FILE, LOG_WARNING);
			return;
		}
		$this->_toLog('_WriteFastAgentToId', 'Done caching user_agent to wurfl_id', LOG_INFO);
		return;
	}

	/**
	 * Reads the file with the correspondence between user_agent and wurfl_id
	 *
	 * @param $_ua	device's user_agent
	 *
	 * @access private
	 *
	 */
	function _ReadFastAgentToId($_ua) {
		// check cache validity
		if ( !$this->_cacheIsValid() ) {
			return false;
		}
		// Load cache file
		if ( is_file(WURFL_AGENT2ID_FILE) || is_link(WURFL_AGENT2ID_FILE) ) {
			include(WURFL_AGENT2ID_FILE);
			// unserialization
			//$a = rawurldecode(unserialize($cache_agents));
		} else {
			return false;
		}
		foreach ( $cached_agents as $device ) {
			if ( $device['user_agent'] == $_ua ) {
				$this->user_agent = $device['user_agent'];
				$this->wurfl_agent = $device['capabilities']['user_agent'];
				$this->id = $device['capabilities']['id'];
				$this->browser_is_wap = $device['is_wap'];
				$this->capabilities = $device['capabilities'];
				$this->brand = $device['capabilities']['product_info']['brand_name'];
				$this->model = $device['capabilities']['product_info']['model_name'];
				$this->_toLog('_ReadFastAgentToId', 'Found '.$_ua.' with id='.$device['capabilities']['id'], LOG_INFO);
				break;
			}
		}
		return true;
	}

	/**
	 * Check filemtimes to see if the cache should be updated
	 *
	 * @access private
	 *
	 */
	function _cacheIsValid() {

		// First of all check configuration. If autoupdate is set to false always
		// return true, otherwise check
		if ( WURFL_CACHE_AUTOUPDATE === false )
			return true;

		// WURFL hasn't been loaded into memory, I'll do it now
		$wurfl_stat = filemtime(WURFL_FILE);
		if ( defined('WURFL_PATCH_FILE') && file_exists(WURFL_PATCH_FILE) ) {
			$patch_stat = filemtime(WURFL_PATCH_FILE);
			if ( $patch_stat > $wurfl_stat ) {
				// if the patch file is newer than the WURFL I set wurfl_stat to that time
				$wurfl_stat = $patch_stat;
			}
		}
		$cache_stat = stat_cache();
		if ( $wurfl_stat <= $cache_stat ) {
			return true;
		} else {
			$this->_toLog('_cacheIsValid', 'cache file is outdated', LOG_INFO);
			return false;
		}
	}

	/**
	 * This function checks and prepares the text to be logged
	 *
	 * @access private
	 */
	function _toLog($func, $text, $requestedLogLevel=LOG_NOTICE){
		if ( !defined('LOG_LEVEL') || LOG_LEVEL == 0 || ($requestedLogLevel-1) >= LOG_LEVEL ) {
			return;
		}
		if ( $requestedLogLevel == LOG_ERR ) {
			$warn_banner = 'ERROR: ';
		} else if ( $requestedLogLevel == LOG_WARNING ) {
			$warn_banner = 'WARNING: ';
		} else {
			$warn_banner = '';
		}
		// Thanks laacz
		$_textToLog = date('r')." [".php_uname('n')." ".getmypid()."]"."[$func] ".$warn_banner . $text;
		$_logFP = fopen(WURFL_LOG_FILE, "a+");
		fputs($_logFP, $_textToLog."\n");
		fclose($_logFP);
		return;
	}

} 
?>
