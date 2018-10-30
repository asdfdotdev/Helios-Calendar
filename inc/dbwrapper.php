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
	 * introducing parameterised queries to Helios. Rather than
	 * passing full query strings replace unsafe params with ?
	 * in the query string and pass the params in the $params array
	 * @since 4.0.0
	 * @version 4.0.0
	 * @param string $query query string to pass to MySQL server
	 * @param array $params (optional) to pass as parameters
	 * @return resource MySQL result set
	 */
	function DoQuery($query, $params = array())
	{
		global $dbc;
		$statement = mysqli_prepare($dbc, $query) or handleError(hc_mysql_errno(), hc_mysql_error());

		// Get the string of data types
		$types = hc_gettypes($params);

		// How many params were we actually expecting?
		$expect = substr_count($query, '?');

		if ($expect <> sizeof($params))
		{
			die("Expecting $expect params but received " . sizeof($params) . " for query $query");
		}

		if (strlen($types) > 0)
		{
		    # make the references
		    $bind_arguments = [];

		    // Param one for call is the types of params (there may be none?)
		    $bind_arguments[] = $types;
		    foreach ($params as $pkey => $pvalue)
		    {
		    	// Cannot allow undefined params ie NULL in some
		    	// fields but empty string is OK. Fudge all such things?
		        $bind_arguments[] = &$params[$pkey]; # bind to array ref
		    }

		    # Bind
		    call_user_func_array(array($statement, 'bind_param'), $bind_arguments);
		}
	    
	    // Execute    
	    $statement->execute() or handleError(hc_mysql_errno(), "Query $query failed: " . hc_mysql_error());

	    // Get result(s)
	    $result = $statement->get_result(); # get results

	    // Destroy now un-needed statement?
	    $statement->close();

	    return $result;
	}

	/**
	 * Auto-Determines the likely 'types' of the passed
	 * paramters for a query. Note order of determination
	 * was selected (quite) carefully.
	 * @since 4.0.0
	 * @version 4.0.0
	 * @param string $query query string to pass to MySQL server
	 * @param array $params (optional) to pass as parameters
	 * @return resource MySQL result set
	 */
	function hc_gettypes($params = array())
	{
		$types = '';
		if (sizeof($params)>0) {                        
			foreach($params as $param) {        
			    if(is_int($param)) {
			        $types .= 'i';              //integer
			    } elseif (is_float($param)) {
			        $types .= 'd';              //double
			    } elseif (is_string($param)) {
			        $types .= 's';              //string
			    } else {
			        $types .= 'b';              //blob and unknown
			    }
			}
		}
		return $types;
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