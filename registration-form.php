<?php 

// Include validate.php which contains functions for verifying the POST data.
include 'validate.php';

{
	// Determine whether or not the form has been submitted.
	$is_submitted = isset($_POST['submit']);

	// Initialize error check booleans
	$b_is_first_name_valid = true;
	$b_is_last_name_valid = true;
	$b_is_address_valid = true;
	$b_is_city_valid = true;
	$b_is_state_valid = true;
	$b_is_zip_valid = true;
	$b_is_country_valid = true;

	// Initialize variables used to store POST data
	$first_name = "";
	$last_name = "";
	$address1 = ""; 
	$address2 = "";
	$city = "";
	$state = ""; 
	$zip = "";
	$country = ""; 

	// If the form has not been submitted...
	if($is_submitted == false)
	{
		// Create key/pair array containing the POST data and validity 
		// and pass it to display_registration_form().
		$post_data = array
			(
			 array($first_name, $b_is_first_name_valid),
			 array($last_name, $b_is_last_name_valid),
			 array($address1, $b_is_address_valid),
			 array($address2, $b_is_address_valid),
			 array($city, $b_is_city_valid),
			 array($state, $b_is_state_valid),
			 array($zip, $b_is_zip_valid),
			 array($country, $b_is_country_valid)
			);

		// Display the registration form.
		display_registration_form($post_data);
	}
	// If the form has been submitted...
	else
	{
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

		// Check POST data
		if($b_is_first_name_valid == true &&
		   $b_is_last_name_valid == true &&
		   $b_is_address_valid == true &&
		   $b_is_city_valid == true &&
		   $b_is_state_valid == true &&
		   $b_is_zip_valid == true &&
		   $b_is_country_valid == true)
		{
			// The inputted data is valid, submit it to server.
			submit_data($first_name,
				    $last_name,
				    $address1,
				    $address2,
				    $city,
				    $state,
				    $zip,
				    $country);
		}
		else
		{
			// The inputted data is not valid, show the form again with
			// indicated errors.

			// Create key/pair array containing the POST data and validity 
			// and pass it to display_registration_form().
			$post_data = array
				(
				 array($first_name, $b_is_first_name_valid),
				 array($last_name, $b_is_last_name_valid),
				 array($address1, $b_is_address_valid),
				 array($address2, $b_is_address_valid),
				 array($city, $b_is_city_valid),
				 array($state, $b_is_state_valid),
				 array($zip, $b_is_zip_valid),
				 array($country, $b_is_country_valid)
				);

			display_registration_form($post_data);
		}
	}
}

// submit_data() sends the user inputted data to the server for 
// server-side validation and entry into the database.
//
// Arguments: POST data strings
// Returns: nothing
function submit_data($first_name,
		     $last_name,
		     $address1,
		     $address2,
		     $city,
		     $state,
		     $zip,
		     $country)
{
	// Set confirmation page URL.
	$confirmation_page="http://helloworld.cookingfor20somethings.com/confirmation.php";

	// Create array of fields to submit.
	$fields = array('first_name' => $first_name,
			'last_name' => $last_name,
			'address1' => $address1,
			'address2' => $address2,
			'city' => $city,
			'state' => $state,
			'zip' => $zip,
			'country' => $country);

	// Generate URL-encoded query (POST) string.
	$post_data = http_build_query($fields);

	/*
	 * Use cURL library for interacting with the confirmation page.
	 */

	// Initialize our cURL session.
	$curl_handle = curl_init();

	// Set the URL to use.
	curl_setopt($curl_handle, CURLOPT_URL, $confirmation_page);

	// Set the CURLOPT_POST option to 1 to indicate that we will perform a POST operation.
	curl_setopt($curl_handle, CURLOPT_POST, 1);

	// Set the POST data.
	curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post_data);

	// Execute POST.
	$result = curl_exec($curl_handle);

	// Close our cURL session.
	curl_close($curl_handle);
}

// display_registration_form() displays the registration form.
//
// Arguments: multi-dimensional array containing POST data and boolean
//	      indicating whether or not the POST data is valid.
// Returns: nothing
function display_registration_form($post_data)
{
	// Initialize validation failure flag
	$b_validation_failure = false;

	// Redirect submit action back to this page in case we need to show errors.
	$action = htmlspecialchars($_SERVER[PHP_SELF]);

	// Print html and body tags.
	echo "<html>\n";
	echo "<body>\n";

 	// Print form header.
	echo "<h2>HelloWorld Registration Form</h2>\n";
	echo "<h4>All fields are required.</h4>\n";

	// Print form.
	echo "<form action=$action method=\"POST\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"submit\">\n";

	// Assign post data value so we can print it below.
	$value = $post_data[0][0];

	echo "<input type=\"text\" name=\"first_name\" placeholder=\"First Name\" value=\"$value\">\n";
	
	// If POST data failed verification append "*" to the input field.
	if($post_data[0][1] == false)
	{
		echo "<span style=\"color:red\">*</span>\n";

		// Set validation failure flag
		$b_validation_failure = true;
	}

	echo "<br><br>\n";

	// Assign post data value so we can print it below.
	$value = $post_data[1][0];

	echo "<input type=\"text\" name=\"last_name\" placeholder=\"Last Name\" value=\"$value\">\n";
	
	// If POST data failed verification append "*" to the input field.
	if($post_data[1][1] == false)
	{
		echo "<span style=\"color:red\">*</span>\n";

		// Set validation failure flag
		$b_validation_failure = true;
	}

	echo "<br><br>\n";

	// Assign post data value so we can print it below.
	$value = $post_data[2][0];

	echo "<input type=\"text\" name=\"address1\" placeholder=\"Address 1\" value=\"$value\">\n";

	// If POST data failed verification append "*" to the input field.
	if($post_data[2][1] == false)
	{
		echo "<span style=\"color:red\">*</span>\n";

		// Set validation failure flag
		$b_validation_failure = true;
	}

	echo "<br>\n";

	// Assign post data value so we can print it below.
	$value = $post_data[3][0];

	echo "<input type=\"text\" name=\"address2\" placeholder=\"Address 2\" value=\"$value\">\n";
	
	echo "<br><br>\n";

	// Assign post data value so we can print it below.
	$value = $post_data[4][0];

	echo "<input type=\"text\" name=\"city\" placeholder=\"City\" value=\"$value\">\n";
	
	// If POST data failed verification append "*" to the input field.
	if($post_data[4][1] == false)
	{
		echo "<span style=\"color:red\">*</span>\n";

		// Set validation failure flag
		$b_validation_failure = true;
	}
	// Switch to printing HTML with embedded PHP since it will be easier to read.
	?>
	
	<br><br>
	<select name="state">
		<option <?php if(empty($post_data[5][0]) == true) { echo "selected"; } ?> disabled>Choose State</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "AL") == 0) { echo "selected"; } ?> value="AL">Alabama</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "AK") == 0) { echo "selected"; } ?> value="AK">Alaska</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "AZ") == 0) { echo "selected"; } ?> value="AZ">Arizona</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "AR") == 0) { echo "selected"; } ?> value="AR">Arkansas</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "CA") == 0) { echo "selected"; } ?> value="CA">California</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "CO") == 0) { echo "selected"; } ?> value="CO">Colorado</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "CT") == 0) { echo "selected"; } ?> value="CT">Connecticut</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "DE") == 0) { echo "selected"; } ?> value="DE">Delaware</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "DC") == 0) { echo "selected"; } ?> value="DC">District Of Columbia</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "FL") == 0) { echo "selected"; } ?> value="FL">Florida</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "GA") == 0) { echo "selected"; } ?> value="GA">Georgia</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "HI") == 0) { echo "selected"; } ?> value="HI">Hawaii</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "ID") == 0) { echo "selected"; } ?> value="ID">Idaho</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "IL") == 0) { echo "selected"; } ?> value="IL">Illinois</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "IN") == 0) { echo "selected"; } ?> value="IN">Indiana</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "IA") == 0) { echo "selected"; } ?> value="IA">Iowa</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "KS") == 0) { echo "selected"; } ?> value="KS">Kansas</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "KY") == 0) { echo "selected"; } ?> value="KY">Kentucky</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "LA") == 0) { echo "selected"; } ?> value="LA">Louisiana</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "ME") == 0) { echo "selected"; } ?> value="ME">Maine</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MD") == 0) { echo "selected"; } ?> value="MD">Maryland</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MA") == 0) { echo "selected"; } ?> value="MA">Massachusetts</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MI") == 0) { echo "selected"; } ?> value="MI">Michigan</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MN") == 0) { echo "selected"; } ?> value="MN">Minnesota</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MS") == 0) { echo "selected"; } ?> value="MS">Mississippi</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MO") == 0) { echo "selected"; } ?> value="MO">Missouri</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "MT") == 0) { echo "selected"; } ?> value="MT">Montana</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NE") == 0) { echo "selected"; } ?> value="NE">Nebraska</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NV") == 0) { echo "selected"; } ?> value="NV">Nevada</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NH") == 0) { echo "selected"; } ?> value="NH">New Hampshire</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NJ") == 0) { echo "selected"; } ?> value="NJ">New Jersey</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NM") == 0) { echo "selected"; } ?> value="NM">New Mexico</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NY") == 0) { echo "selected"; } ?> value="NY">New York</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "NC") == 0) { echo "selected"; } ?> value="NC">North Carolina</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "ND") == 0) { echo "selected"; } ?> value="ND">North Dakota</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "OH") == 0) { echo "selected"; } ?> value="OH">Ohio</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "OK") == 0) { echo "selected"; } ?> value="OK">Oklahoma</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "OR") == 0) { echo "selected"; } ?> value="OR">Oregon</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "PA") == 0) { echo "selected"; } ?> value="PA">Pennsylvania</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "RI") == 0) { echo "selected"; } ?> value="RI">Rhode Island</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "SC") == 0) { echo "selected"; } ?> value="SC">South Carolina</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "SD") == 0) { echo "selected"; } ?> value="SD">South Dakota</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "TN") == 0) { echo "selected"; } ?> value="TN">Tennessee</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "TX") == 0) { echo "selected"; } ?> value="TX">Texas</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "UT") == 0) { echo "selected"; } ?> value="UT">Utah</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "VT") == 0) { echo "selected"; } ?> value="VT">Vermont</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "VA") == 0) { echo "selected"; } ?> value="VA">Virginia</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "WA") == 0) { echo "selected"; } ?> value="WA">Washington</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "WV") == 0) { echo "selected"; } ?> value="WV">West Virginia</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "WI") == 0) { echo "selected"; } ?> value="WI">Wisconsin</option>
		<option <?php if($post_data[5][1] == true && strcmp($post_data[5][0], "WY") == 0) { echo "selected"; } ?> value="WY">Wyoming</option>
	</select>
	<?php
		// If POST data failed verification append "*" to the input field.
		if($post_data[5][1] == false)
		{
			echo "<span style=\"color:red\">*</span>\n";

			// Set validation failure flag
			$b_validation_failure = true;
		}

	echo "<br><br>\n";

	// Assign post data value so we can print it below.
	$value = $post_data[6][0];

	echo "<input type=\"text\" name=\"zip\" placeholder=\"Zip (5 or 9 digit)\" value=\"$value\">\n";
	
		// If POST data failed verification append "*" to the input field.
		if($post_data[6][1] == false)
		{
			echo "<span style=\"color:red\">*</span>\n";

			// Set validation failure flag
			$b_validation_failure = true;
		}
	// Switch to printing HTML with embedded PHP since it will be easier to read.
	?>	
	<br><br>
	<select name="country">
		<option <?php if(empty($post_data[7][0]) == true) { echo "selected"; } ?> disabled>Choose Country</option>
		<option <?php if($post_data[7][1] == true && strcmp($post_data[7][0], "United States") == 0) { echo "selected"; } ?> value="United States">United States</option>
	</select>
	<?php
		// If POST data failed verification append "*" to the input field.
		if($post_data[7][1] == false)
		{
			echo "<span style=\"color:red\">*</span>\n";

			// Set validation failure flag
			$b_validation_failure = true;
		}
	
	echo "<br><br><br>\n";
	echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">\n";
	
	// If one or more of the POST data entries is invalid display
	// a message to the user indicating that errors occurred and to
	// try again.
	if($b_validation_failure == true)
	{
		echo "<br><br>\n";
		echo "<span style=\"color:red\">Validation error(s) occurred. Please confirm the fields marked with an asterisk (*) and submit again.</span>\n";
	}

	// Close form.
	echo "</form>\n";

	// Close body and html tags.
	echo "</body>\n";
	echo "</html>\n";
}
