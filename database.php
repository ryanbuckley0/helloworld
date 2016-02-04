<?php
// This file contains functions that access the MySQL database.

// submit_to_database() will submit entries to the MySQL database.
//
// Arguments: Array containing POST data strings
// Returns: Boolean
function submit_to_database($data)
{
	// Initialize the return code.
	$b_rc = false;

	// Open connection to the database.
	list ($b_rc, $db_handle) = connect_to_database();

	// If return code is true proceed with data submission.
	if($b_rc)
	{
		// Parse data from input array.
		$first_name = $data['first_name'];
		$last_name = $data['last_name'];
		$address1 = $data['address1'];
		$address2 = $data['address2'];
		$city = $data['city'];
		$state = $data['state'];
		$zip = $data['zip'];
		$country = $data['country'];

		// Set MySQL table to use.
		$sql_table = "data_submission";

		// Create MySQL insert query.
		$sql_query = "INSERT INTO $sql_table (first_name, last_name, address1, address2, city, state, zip, country) VALUES ('$first_name', '$last_name', '$address1', '$address2', '$city', '$state', '$zip', '$country')";

		// Perform insertion into table.
		$result = mysql_query($sql_query, $db_handle) or trigger_error("SQL", E_USER_ERROR);

		// Check the return value from mysql_query().
		check_result($result);

		// Free MySQL result memory.
		mysql_free_result($result);
	}
	else
	{
		// Print error that database could not be found.
		echo "Database not found.";
		echo "<br>";
	}

	// Close connection to MySQL database.
	mysql_close($db_handle);

	return $b_rc;
}

// query_registered_users() requests registered user entries from
// the database sorted in descending order by date of registration.
//
// Arguments: None
// Returns: Boolean, Array containing query results.
function query_registered_users()
{
	// Initialize the return code.
	$b_rc = false;

	// Initialize array to store query results in.
	$registered_users = array();

	// Open connection to the database.
	list ($b_rc, $db_handle) = connect_to_database();

	// If return code is true proceed with data query.
	if($b_rc)
	{
		// Set MySQL table to use.
		$sql_table = "data_submission";

		// Create MySQL query to request all registered user entries in descending
		// order by timestamp.
		$sql_query = "SELECT * FROM $sql_table ORDER BY timestamp DESC";

		// Perform database query.
		$result = mysql_query($sql_query, $db_handle);

		// Validate return code from query.
		check_result($result);


		// Fetch each registered user's information from the query results
		// and add it to an array that can be returned to the caller.
		while ($row = mysql_fetch_row($result))
		{
			//echo "timestamp = $row[8]<br>";
			// Add user's information to array.
			$registered_users[] = $row;
		}

		// Free the query results.
		mysql_free_result($result);
	}

	// Return array consisting of status boolean and query data.
	return array ($b_rc, $registered_users);
}

// connect_to_database() opens a connection to the MySQL dataabase
// and returns a handle to it.
//
// Arguments: None
// Returns: Boolean, Database handle
function connect_to_database()
{
	// Set hostname for connecting to database.
	$hostname = "localhost";

	// Set log-in credentials.
	$username = "helloworld201602";
	$password = "Tx,kcURD&v+V";

	// Connect to MySQL.
	$db_handle = mysql_connect($hostname, $username, $password)
		or die("Connecting to MySQL failed: " . mysql_error());

	// Select database to use.
	$database = "helloworld20160202";
	$db_found = mysql_select_db($database, $db_handle);

	// Return the boolean value from mysql_select_db() and the database handle.
	return array ($db_found, $db_handle);
}

// check_result() checks the return value from mysql_query().
//
// Arguments: Boolean
// Returns: Nothing
function check_result($result)
{
	// Print error.
	if(!$result)
	{
		print "Error: " . mysql_error() . "<br>";
		exit(1);
	}
}

?>
