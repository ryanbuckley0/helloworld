<?php
// This file contains functions that validate POST data.

// validate_string() determines whether or not the user has entered valid
// string data.
//
// Arguments: POST data string
// Returns: boolean
function validate_string($string)
{
	// Initialize boolean return value
	$b_return = false;

	// If the string is empty, return true.
	if(empty($string) == false)
	{
		// Use regular expression matching to determine if the user
		// entered a string with only letters and whitespace.
		if(preg_match("/^[a-zA-Z ]*$/", $string) == 1)
		{
			// The string is valid, return true.
			$b_return = true;
		}
	}

	return $b_return;
}

// validate_address() determines whether or not the user has entered a valid
// address.
//
// Arguments: 2 POST data strings
// Returns: boolean
function validate_address($address1, $address2)
{
	// Initialize boolean return value
	$b_return = false;

	// If address1 is populated...
	if(empty($address1) == false)
	{
		// Use regular expression matching to determine if the user
		// entered a string for address1 containing only letters, 
		// numbers, and whitespace.
		if(preg_match("/^[a-zA-Z0-9 .\-]+$/i", $address1) == 1)
		{
			// Check if address2 is populated...
			if(empty($address2) == false)
			{
				// Verify address2 using the same regular expression
				// as address1 verification.
				if(preg_match("/^[a-zA-Z0-9 .\-]+$/i", $address2) == 1)
				{
					// Address1 and address2 are valid, return true.
					$b_return = true;
				}
			}
			else
			{
				// Address1 is valid and address2 is blank which is
				// acceptable, return true.
				$b_return = true;
			}
		}
	}

	return $b_return;
}

// validate_city() determines whether or not the user has entered valid
// string data for the city.
//
// Arguments: POST data string
// Returns: boolean
function validate_city($string)
{
	// Initialize boolean return value
	$b_return = false;

	// If the string is empty, return true.
	if(empty($string) == false)
	{
		// Use regular expression matching to determine if the user
		// entered a string with only letters and whitespace. A dash
		// is also allowed.
		if(preg_match("/^[a-zA-Z .\-]+$/i", $string) == 1)
		{
			// The string is valid, return true.
			$b_return = true;
		}
	}

	return $b_return;
}

// validate_zip() determines whether or not the user has entered a valid
// 5 or 9 digit zip code.
//
// Arguments: POST data string representing a zip code
// Returns: boolean
function validate_zip($string)
{
	// Initialize boolean return value
	$b_return = false;

	if(empty($string) == false)
	{
		// Use regular expression matching to determine if the user
		// entered a valid 5 or 9 digit zip code.
		if(preg_match("/^([0-9]{5})(-[0-9]{4})?$/i", $string) == 1)
		{
			// The zip is valid, return true.
			$b_return = true;
		}
	}

	return $b_return;
}

?>
