<?php
/**
 * @package Helios Calendar API SDK
 * @copyright 2014- Chris Carlevato (https://github.com/chrislarrycarl)
 * @copyright 2004-2014 Refresh Web Development
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

/**
 * Provides access to Helios Calendar APIs for retrival of various calendar data.
 */
class HeliosCalendar
{
	const VERSION = '1.0.0';
	
	/**
	 * The API URL
	 * @var string
	 */
	protected $apiURL;
	
	/**
	 * The API Username.
	 * @var string
	 */
	protected $apiUser;
	
	/**
	 * The API Key
	 * @var string
	 */
	protected $apiKey;
	
	/**
	 * Initialize API access.
	 * 
	 * Configuration should include:
	 * - apiURL: URL of the Helios Calendar whose API we are connecting to.
	 * - apiUser: Username credential for the connected API.
	 * - apiKey: API key credential for the connected API.
	 * 
	 * @param array $config API Configuration
	 */
	public function __construct($config){
		if(!isset($config['apiURL']) || $config['apiURL'] == '')
			$this->apiError('Config requires API URL',1);
		if(!isset($config['apiUser']) || $config['apiUser'] == '')
			$this->apiError('Config requires API User',1);
		if(!isset($config['apiKey']) || $config['apiKey'] == '')
			$this->apiError('Config requires API Key',1);
		
		$this->setURL($config['apiURL']);
		$this->setUser($config['apiUser']);
		$this->setKey($config['apiKey']);
	}	
	/**
	 * Set the API URL
	 * @param string $apiURL URL of the API being accessed
	 */
	protected function setURL($apiURL){
		$this->apiURL = $apiURL;
	}
	/**
	 * Get the API URL
	 * @return string URL of the API being accessed
	 */
	protected function getURL(){
		return $this->apiURL;
	}
	/**
	 * Set the API Username
	 * @param string $apiUser Username for the API
	 */
	protected function setUser($apiUser){
		$this->apiUser = $apiUser;
	}
	/**
	 * Get the API Username
	 * @return string Username for the API
	 */
	protected function getUser(){
		return $this->apiUser;
	}
	/**
	 * Set the API Key
	 * @param string $apiKey Key for the API being accessed
	 */
	protected function setKey($apiKey){
		$this->apiKey = $apiKey;
	}
	/**
	 * Get the API Key
	 * @return string Key for the API
	 */
	protected function getKey(){
		return $this->apiKey;
	}
	/**
	 * Get the sdk version.
	 * @return string SDK Version Number 
	 */
	public function getVersion(){
		return $this::VERSION;
	}
	/**
	 * Retrive event feed from the calendar API.
	 * @param integer $type Type of event list to retrieve, 1 = Current Events, 2 = Billboard, 3 = Most Popular, 4 = Newest DEFAULT:1
	 * @param string $format Format to return event data, array = array(), object = standard object, raw = json encoded string DEFAULT: raw
	 * @return array/object/string Event Data in the requested format.
	 */
	public function getEvents($type = 1,$format = 'raw'){
		switch($type){
			case 2:
				$method = 'events_b';
				break;
			case 3:
				$method = 'events_p';
				break;
			case 4:
				$method = 'events_n';
				break;
			case 1:
			default:
				$method = 'events_c';
				break;
		}
		
		$response = file_get_contents($this->apiURL.'/?u='.urlencode($this->apiUser).'&k='.urlencode($this->apiKey).'&data='.urlencode($method));
		
		if(preg_match('/error_id/', $response))
			   $this->apiError($response);
		
		switch(strtolower($format)){
			case 'array':
				return json_decode($response,true);
			case 'object':
				return json_decode($response,false);
			default:
			case 'raw':
				return $response;
		}
	}
	/**
	 * Retrive category feed from the calendar API.
	 * @param integer $type Type of category list to retrieve, 1 = Hierarchical, 2 = Alphabetical (By Category Name), 3 = Event Count (Highest First) DEFAULT:1
	 * @param string $format Format to return event data, array = array(), object = standard object, raw = json encoded string DEFAULT: raw
	 * @return array/object/string Event Data in the requested format.
	 */
	public function getCategories($type = 1,$format = 'raw'){
		switch($type){
			case 2:
				$method = 'categories_a';
				break;
			case 3:
				$method = 'categories_e';
				break;
			case 1:
			default:
				$method = 'categories_h';
				break;
		}
		
		$response = file_get_contents($this->apiURL.'/?u='.urlencode($this->apiUser).'&k='.urlencode($this->apiKey).'&data='.urlencode($method));
		
		if(preg_match('/error_id/', $response))
			   $this->apiError($response);
		
		switch(strtolower($format)){
			case 'array':
				return json_decode($response,true);
			case 'object':
				return json_decode($response,false);
			default:
			case 'raw':
				return $response;
		}
	}
	/**
	 * Retrive newsletter feed from the calendar API.
	 * @param integer $type Type of newsletter list to retrieve, 1 = Current (Newest First), 2 = Most Popular (By Average Combined Inbox/Archive View Count), DEFAULT:1
	 * @param string $format Format to return event data, array = array(), object = standard object, raw = json encoded string DEFAULT: raw
	 * @return array/object/string Event Data in the requested format.
	 */
	public function getNewsletters($type = 1,$format = 'raw'){
		switch($type){
			case 2:
				$method = 'newsletters_p';
				break;
			case 1:
			default:
				$method = 'newsletters_c';
				break;
		}
		
		$response = file_get_contents($this->apiURL.'/?u='.urlencode($this->apiUser).'&k='.urlencode($this->apiKey).'&data='.urlencode($method));
		
		if(preg_match('/error_id/', $response))
			   $this->apiError($response);
		
		switch(strtolower($format)){
			case 'array':
				return json_decode($response,true);
			case 'object':
				return json_decode($response,false);
			default:
			case 'raw':
				return $response;
		}
	}
	/**
	 * Throw exception with API error response.
	 * @param string $error_response Response error message.
	 * @param integer $type The type of error produced 1 = Local, 0 = API, DEFAULT:0
	 * @throws Exception
	 */
	protected function apiError($error_response,$type = 0){
		if($type == 1){
			$msg = $error_response;	
		} else {
			$error = json_decode($error_response,true);
			$msg = "Error ".$error['error']['error_id']." -- ".$error['error']['msg']." ".$error['error']['help'];
		}
		
		throw new Exception($msg,NULL,NULL);
	}
}
?>