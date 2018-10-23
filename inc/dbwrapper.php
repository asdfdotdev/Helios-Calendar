<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */

	/**
	 * Function to replace mysql_connect
	 * @since 3.0.0
	 * @version 4.0.0
	 */
	function hc_mysql_connect($host, $user, $pass, $dbase)
	{ 
    	return mysqli_connect($host, $user, $pass, $dbase); 
	} 

	/**
	 * Function to replace mysql_result	
	 * @since 3.0.0
	 * @version 4.0.0
	 */
	function hc_mysql_result($res, $row=0, $field=0) 
	{ 
    	$res->data_seek($row); 
    	$datarow = $res->fetch_array(); 
    	return $datarow[$field]; 
	} 

	/**
	 * Function to replace mysql_fetch_row
	 * @since 3.0.0
	 * @version 4.0.0
	 */
	function hc_mysql_fetch_row($res)
	{
		return mysqli_fetch_row($res);
	}

	/**
	 * Function to replace mysql_num_rows
	 * @since 3.0.0
	 * @version 4.0.0
	 */
	function hc_mysql_num_rows($res)
	{
		return mysqli_num_rows($res);
	}

	/**
	 * Function to replace mysql_num_rows
	 * @since 3.0.0
	 * @version 4.0.0
	 */	
	function hc_mysql_fetch_assoc($res)
	{
		return mysqli_fetch_assoc($res);
	}

	/**
	 * Function to replace mysql_data_seek
	 * @since 3.0.0
	 * @version 4.0.0
	 */	
	function hc_mysql_data_seek($res, $num)
	{
		return mysqli_data_seek($res, $num);
	}	

	/**
	 * Function to replace mysql_errno
	 * @since 3.0.0
	 * @version 4.0.0
	 */	
	function hc_mysql_errno()
	{
		global $dbc;
		return mysqli_errno($dbc);
	}

	/**
	 * Function to replace mysql_error
	 * @since 3.0.0
	 * @version 4.0.0
	 */	
	function hc_mysql_error()
	{
		global $dbc;
		return mysqli_error($dbc);
	}

	/**
	 * Function to replace mysql_real_escape_string
	 * @since 3.0.0
	 * @version 4.0.0
	 */	
	function hc_mysql_real_escape_string($val)
	{
		global $dbc;
		return mysqli_real_escape_string($dbc, $val);
	}

	/**
	 * Wrapper for doQuery() with custom error handling.
	 * @since 2.0.0
	 * @version 4.0.0
	 * @param string $query query string to pass to MySQL server
	 * @return resource MySQL result set
	 */
	function doQuery($query)
	{
		global $dbc;
		$result = mysqli_query($dbc, $query);
		
		if(!$result){
			handleError(hc_mysql_errno(), hc_mysql_error());
		}//end if
		return $result;
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