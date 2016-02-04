<?php

// This file performs the server side validation of the user input.
// If the input is valid it will be submitted to the database and the
// user will be notified that their registration was successful.
// Otherwise, an error message will be displayed.

// Include validate.php which contains functions for verifying the POST data.
include 'validate.php';
include 'database.php';

// Assign POST data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zip = $_POST['zip'];
$country = $_POST['country'];

// Validate POST data
$b_is_first_name_valid = validate_string($first_name);
$b_is_last_name_valid = validate_string($last_name);
$b_is_address_valid = validate_address($address1, $address2);
$b_is_city_valid = validate_city($city);
$b_is_state_valid = validate_string($state);
$b_is_zip_valid = validate_zip($zip);
$b_is_country_valid = validate_string($country);

// Verify required fields have been populated with valid input.
if($b_is_first_name_valid == true &&
   $b_is_last_name_valid == true &&
   $b_is_address_valid == true &&
   $b_is_city_valid == true &&
   $b_is_state_valid == true &&
   $b_is_zip_valid == true &&
   $b_is_country_valid == true)
{
	// Create array of data to submit.
	$data = array('first_name' => $first_name,
			'last_name' => $last_name,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			'country' => $country);

	// Submit data to database.
	$b_rc = submit_to_database($data);

	if($b_rc == true)
	{
		// Print html and body tags.
		echo "<html>\n";
		echo "<body>\n";

		// Show registration confirmation message.
		echo "Thanks for registering!<br>\n";

		// Close body and html tags.
		echo "</body>\n";
		echo "</html>\n";
	}
	else
	{
		// An error occurred during submission.  Inform user.
		print_submission_error();
	}
}
else
{
	// An error occurred during submission.  Inform user.
	print_submission_error();
}

// print_submission_error() displays an error message to the user
// containing a link to the form page.
//
// Arguments: None
// Returns: Nothing
function print_submission_error()
{
	// POST did not validate.  Inform user and allow them to try again.
	echo "An error was encountered during submission.  Please try <a href=\"http://helloworld.cookingfor20somethings.com/registration-form-test.php\">again</a>.\n";
}
?>
