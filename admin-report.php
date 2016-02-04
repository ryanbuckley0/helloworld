<?php

// This file queries the MySQL database for all entries.

// Include database.php so we can communicate with MySQL.
include "database.php";

echo "<html>\n";
echo "<body>\n";

$registered_users = array();

// Query the database for the list of registered users
// sorted in descending order by date of registration. 
list ($b_rc, $query_results) = query_registered_users();

// Proceed if the query was successful.
if($b_rc == true)
{
	// Print table header.
	echo "<table border=1 cellpadding=5>\n";
		echo "<tr>\n";
			echo "<th>First Name</th>\n";
			echo "<th>Last Name</th>\n";
			echo "<th>Address 1</th>\n";
			echo "<th>Address 2</th>\n";
			echo "<th>City</th>\n";
			echo "<th>State</th>\n";
			echo "<th>Zip</th>\n";
			echo "<th>Country</th>\n";
			echo "<th>Date</th>\n";
		echo "</tr>\n";

	// Print table entries.

	// Determine the number of entries returned by the query.
	$num_entries = count($query_results);

	for($i = 0; $i < $num_entries; $i++)
	{
		// Assign the row for printing below.
		$row = $query_results[$i];

		// Print the row.
		echo "<tr>\n";
			echo "<td>$row[0]</td>\n";
			echo "<td>$row[1]</td>\n";
			echo "<td>$row[2]</td>\n";
			echo "<td>$row[3]</td>\n";
			echo "<td>$row[4]</td>\n";
			echo "<td>$row[5]</td>\n";
			echo "<td>$row[6]</td>\n";
			echo "<td>$row[7]</td>\n";
			echo "<td>$row[8]</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	
}

echo "</body>\n";
echo "</html>\n";
?>
