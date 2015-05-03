<?php
/**
 * @package cl_session Class
 * @copyright 2015 Chris Carlevato (https://github.com/chrislarrycarl)
 * @license http://www.gnu.org/licenses/lgpl-2.1.html
 * 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public 
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 */

class cl_session
{
	const VERSION = '0.1.2';
	
	protected $key, $path, $secure, $decoy, $min_time, $max_time;
	protected $failmsg = 'Session generation failed.';
	
	/**
	 * Config should include:
	 * - name(Required): Name of the session
	 * - path: Server path the cookie is available on
	 * - domain: Domain the cookie is available to
	 * - secure: Only transmit the cookie over https
	 * - hash: 0 = MD5(128 bits), 1 = SHA1(160 bits)
	 * - decoy: True/False to generate fake PHPSESSID cookie
	 * - min: Minimum time, in seconds, to regenerate session (Default: 60)
	 * - max: Maximum time, in seconds, to regenerate session (Default: 600)
	 * 
	 * @param array $config Session Configuration
	 */
	public function __construct($config){
		if(!isset($config['name']) || $config['name'] == '')
			$this->clError($failmsg,1);
			
		if(function_exists('ini_get') && ini_get('date.timezone') == '')
			date_default_timezone_set('UTC');
			
		$this->setName($config['name']);
		$this->setPath($config['path']);
		$this->setDomain($config['domain']);
		$this->setSecure($config['secure']);
		$this->setHash($config['hash']);
		$this->min_time = (isset($config['min'])) ? $config['min'] : 60;
		$this->max_time = (isset($config['max'])) ? $config['max'] : 600;
		$this->decoy = (isset($config['decoy'])) ? $config['decoy'] : 1;
	}
	/**
	 * Set session name
	 * @param string $name Session Name
	 */
	protected function setName($name){
		$this->name = $name;
	}
	/**
	 * Get session name
	 * @return string Session Name
	 */
	protected function getName(){
		return $this->name;
	}
	/**
	 * Set path on the domain where the cookies will work
	 * Use a single slash (default) for all paths on the domain
	 * @param string $path Cookie Path
	 */
	protected function setPath($path = "/"){
		$this->path = $path;
	}
	/**
	 * Get cookie path
	 * @return string Cookie Path
	 */
	protected function getPath(){
		return $this->path;
	}
	/**
	 * Set cookie domain. To make cookie visible on all subdomains 
	 * then the domain must be prefixed with a dot like '.christopherl.com'
	 * @param string $domain Cookie Domain
	 */
	protected function setDomain($domain = ""){
		$domain = ($domain == "") ? $_SERVER['SERVER_NAME'] : $domain;
		$this->domain = $domain;
	}
	/**
	 * Get session cookie domain
	 * @return string Cookie Domain
	 */
	protected function getDomain(){
		return $this->domain;
	}
	/**
	 * Set cookie secure status. If TRUE cookie will only be sent over secure connections
	 * @param boolean $secure Cookie Secure Status
	 */
	protected function setSecure($secure = false){
		$this->secure = $path;
	}
	/**
	 * Get cookie secure status
	 * @return boolean Cookie Secure Status
	 */
	protected function getSecure(){
		return $this->secure;
	}
	/**
	 * Set cookie id hash
	 * @param int $hash 0 = MD5, 1 = SHA1 (Default: 1)
	 */
	protected function setHash($hash = 1){
		$this->hash = $hash;
	}
	/**
	 * Get cookie id hash setting
	 * @return int Cookie Hash Setting
	 */
	protected function getHash(){
		return $this->hash;
	}
	/**
	 * Create PHPSESSID decoy cookie if it hasn't been set
	 */
	protected function setDecoyCookie(){
		if(!isset($_COOKIE["PHPSESSID"])){
			$this->setValue('decoy_value',md5(mt_rand()));
			setcookie("PHPSESSID",$this->getValue('decoy_value'),0,$this->getPath(),$this->getDomain(),$this->getSecure,0);
		}
	}
	/**
	 * Destroy PHPSESSID decoy cookie
	 */
	protected function killDecoyCookie(){
		if(isset($_COOKIE["PHPSESSID"])){
			unset($_COOKIE['PHPSESSID']);
		}
	}
	/**
	 * Create session fingerprint from user agent, ip and session id
	 */
	protected function generateFingerprint(){
		$this->setValue('fingerprint', sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . session_id()));
	}
	/**
	 * Compare current user agent, ip and session id against stored session fingerprint
	 * If compared value doesn't match stored value session is ended
	 */
	protected function validateFingerprint(){
		if($this->getValue('fingerprint') == '')
			$this->generateFingerprint();
		elseif($this->getValue('fingerprint') != sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . session_id()))
			$this->end();
	}
	/**
	 * Reset session lifespan time using random value between min_time and max_time
	 */
	protected function resetLifespan(){
		$this->setValue('lifespan', date("U") + mt_rand($this->min_time,$this->max_time));
	}
	/**
	 * Compare session lifespan time to current time
	 * If current time is beyond session lifespan regenerate session id
	 */
	protected function checkLifespan(){
		if($this->getValue('lifespan') == ''){
			$this->resetLifespan();
		} elseif($this->getValue('lifespan') < date("U")) {
			$this->regenerate();
		}
	}
	/**
	 * Start Session
	 * @param boolean $restart Force session id regeneration
	 */
	public function start($restart = false){
		if($restart){		
			$kill_id = session_id();
			session_regenerate_id();
			$new_id = session_id();
			session_write_close();
			session_id($new_id);
		}
		
		if(function_exists('ini_set') && !$restart){
			ini_set("session.hash_function", $this->getHash());
			ini_set("session.use_strict_mode",1);
			ini_set("session.cookie_secure",1);
			ini_set("session.use_only_cookies",1);
		}
		
		session_set_cookie_params(0, $this->getPath(), $this->getDomain(), $this->getSecure(), true);
		session_name($this->getName());
		session_start();
		$_SESSION['clValues'] = (isset($_SESSION['clValues'])) ? $_SESSION['clValues'] : array();
		
		if($restart){
				$this->generateFingerprint();
				$this->resetLifespan();
		}
		
		if($this->decoy)
			$this->setDecoyCookie();
		else
			$this->dropValue('decoy_value');
		
		$this->validateFingerprint($this->getValue('fingerprint'));
		$this->checkLifespan($this->getValue('lifespan'));
		$this->setValue('session_load',date("U"));
	}
	/**
	 * Get session variable value
	 * @param string $key Name of the session variable value to retrieve
	 * @return mixed Value of the variable requested
	 */
	public function getValue($key){
		return $_SESSION['clValues'][$key];
	}
	/**
	 * Set session value
	 * If $key name is not present in the session array value is created
	 * If $key name is present in the session array value is updated
	 * @param string $key Name of the session variable to create/update
	 * @param string $value Value of the session variable to create/update
	 * @param int $hash 0 = store $value in session array as plain text, 1 = store SHA1 hash of $value in session array
	 */
	public function setValue($key, $value, $hash = false){
		$value = (!$hash) ? $value : sha1($value);
		$_SESSION['clValues'][$key] = $value;
	}
	/**
	 * Append session value
	 * @param string $key Name of the session variable to create/update
	 * @param string $value String to append to the end of the current value
	 */
	public function appValue($key, $value){
		if(isset($_SESSION['clValues'][$key]))
			$_SESSION['clValues'][$key] = ($this->getValue($key).$value);
		else
			$this->setValue($key, $value);
	}
	/**
	 * Increment session value
	 * @param string $key Name of the session variable to create/increment
	 * @param int $amount Amount to add to the current value
	 */
	public function incValue($key, $amount){
		if(isset($_SESSION['clValues'][$key]))
			$_SESSION['clValues'][$key] = ($this->getValue($key)+$amount);
		else
			$this->setValue($key, $amount);
	}
	/**
	 * Drop session value
	 * @param string $key Name of the session variable to drop
	 */
	public function dropValue($key){
		unset($_SESSION['clValues'][$key]);
	}
	/**
	 * Regenerate session id
	 */
	public function regenerate(){
		$this->start(true);
	}
	/**
	 * End session
	 */
	public function end(){
		session_unset();
		session_destroy();
	}
	/**
	 * Output session contents for debugging
	 */
	public function dump(){
		echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';
	}
	/**
	 * Throw exception on error.
	 * @param string $error_response Response error message.
	 * @throws Exception
	 */
	protected function clError($error_response){
		throw new Exception($error_response,NULL,NULL);
	}
}