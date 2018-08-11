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
 * $Id: wurfl_config.php,v 1.1 2005/04/16 16:04:21 atrasatti Exp $
 * $RCSfile: wurfl_config.php,v $ v2.1 beta2 (Apr, 16 2005)
 * Author: Andrea Trasatti ( atrasatti AT users DOT sourceforge DOT net )
 * Multicache implementation: Herouth Maoz ( herouth AT spamcop DOT net )
 *
*/

/*
 *
 * This is the configuration file for WURFL PHP libraries. You may use this file
 * or include these defines into your main configuration file.
 *
 * Defines used by this library:
 * WURFL_CONFIG	used in other libraries if the configuration was done
 * DATADIR			where all data is stored (wurfl.xml, cache file, logs, etc)
 * WURFL_FILE		string, Full path and filename of wurfl.xml
 * WURFL_PARSER_FILE	string, Full path and filename of wurfl_parser.php
 * WURFL_CLASS_FILE	string, Full path and filename of wurfl_class.php
 * WURFL_USE_CACHE	boolean, true if I want to use a cache file
 * WURFL_USE_MULTICACHE	boolean, true if you want to use Multicache files
 *				instead of a single BIG cache file
 * MULTICACHE_DIR	string, used only if you enabled Multicache, defines where
 *				the cache files will be stored. WARNING: while cache.php will grow
 *				in size but remain a single file, here the files will grow in
 *				number. Expect more than 5000 tiny files.
 * MULTICACHE_SUFFIX string, suffix for the files generated using Multicache.
 *				Useful if you use a caching system and don't want to load your
 *				shared memory with a ton of tiny files.
 * CACHE_FILE		string, with full path and filename of the cache file to
 *				use
 * WURFL_CACHE_AUTOUPDATE	boolean, tells the class to automatically update the
 *				cached files with a new XML is found. This is NOT suggested when
 *				using MULICACHE because of the high number of files to be updated.
 *				Race conditions are highly possible to happen.
 * WURFL_PATCH_FILE	string, optional patch file for WURFL
 * WURFL_AGENT2ID_FILE	string, used by wurfl_class.php. needs to be removed
 *				when a new WURFL is found
 * MAX_UA_CACHE	integer, max number of user agents to store in
 *				WURFL_AGENT2ID_FILE. Too high limits might give the opposite effect.
 * WURFL_LOG_FILE string, defines full path and filename for logging
 * WURFL_AUTOLOAD	boolean, true if you want the XML to be loaded at every
 *				startup. If not, the XML will be loaded when needed.
 * LOG_LEVEL	integer, desired logging level. Use the same constants as for PHP
 *				logging
 *
 * More info can be found here in the PHP section:
 * http://wurfl.sourceforge.net/
 *
 * Questions or comments can be sent to
 * "Andrea Trasatti" <atrasatti AT users DOT sourceforge DOT net>
 *
 * Please, support this software, send any suggestion and improvement to me
 * or the mailing list and we will try to keep it updated and make it better
 * every day.
 *
 * If you like it and use it, please let me know or contact the wmlprogramming
 * mailing list: wmlprogramming@yahoogroups.com
 *
 */

// Single define to be checked when starting the parser and/or the class
define("WURFL_CONFIG", true);

// Where all data is stored (wurfl.xml, cache file, logs, etc)
define("DATADIR", '../data/');

// Path and filename of wurfl_parser.php
define("WURFL_PARSER_FILE", './wurfl_parser.php');

// Path and filename of wurfl_class.php
define("WURFL_CLASS_FILE", './wurfl_class.php');

// Set this true if you want to use cache. Strongly suggested
define ("WURFL_USE_CACHE", true);

// Set this true if you want to avoid using cache.php file, and
// generate a single file for each device user agent and use that as a cache
// NOTICE: using Multicache will still generate cache.php and agent2id.php,
//  but will not dump the entire XML as PHP into cache.php. As a result
//  cache.php will be MUCH smaller and load WAY faster; as a drawback you will
//  have many more I/O accesses to the single tiny files.
define ("WURFL_USE_MULTICACHE", true);

// Path and name of the cache file
define ("CACHE_FILE", DATADIR."cache.php");

// Set path of the Multicache directory where all the little caches will be
// stored. Should be a directory that contains *only* these files, so that its
// content can be safely cleaned when a new cache is created.
// If not using Multicache, this parameter will not be used.
define ("MULTICACHE_DIR", DATADIR."multicache/");

// Temporary directory for manual updates.
define ("MULTICACHE_TMP_DIR", DATADIR."multicache_tmp/");
// This file is created and removed when manually updating multicache files
define ("MULTICACHE_TOUCH", DATADIR."multicache.lockfile");

// File suffix for Multicache files
define ("MULTICACHE_SUFFIX",".php");

// Autoload set to false, I will load it when needed
define ("WURFL_AUTOLOAD", false);

// This parameter tells the class to automatically update cache files when a
// new XML is found.
// Using the multicache is not suggested to automatically update it. Use the
// external scripts.
define ("WURFL_CACHE_AUTOUPDATE", false);

// Path and name of the wurfl
define ("WURFL_FILE", DATADIR."wurfl.xml");

// Path and name of the log file
define ("WURFL_LOG_FILE", DATADIR."wurfl.log");

// Path and name of the file to store user_agent->id relation
// (ignored if caching is disabled)
define ("WURFL_AGENT2ID_FILE", DATADIR."agent2id.php4");

// Set the maximum number of user_agents to cache
define ("MAX_UA_CACHE", 30);

// suggested log level for normal use (default PHP logging constants)
//define ("LOG_LEVEL", LOG_ERROR );
// suggested log level for debug use
define ("LOG_LEVEL", 'LOG_INFO');

?>
