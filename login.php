<?php # Script 12.3 - login.php
// This page processes the login form submission.
// Upon successful login, the user is redirected.
// Two included files are necessary.
// Send NOTHING to the Web browser prior to the setting session variables line!
session_start(); // Start the session.
// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// For processing the login:
	require ('includes/login_functions.inc.php');
	
	// Need the database connection:
	require ('mysqli_connect.php');
		
	// Check the login:
	list ($check, $data) = check_login($dbc, $_POST['username'], $_POST['password']);
	
	if ($check) { // OK!
		
		// Set the session ID:
		
			$_SESSION['userID'] = $data['userID'];
			$_SESSION['userType']= $data['userType'];
			$_SESSION['firstname']= $data['firstname'];
			$_SESSION['lastname']= $data['lastname'];

		
		// Redirect:
		redirect_user('loggedin.php');
			
	} else { // Unsuccessful!

		// Assign $data to $errors for error reporting
		// in the login_page.inc.php file.
		$errors = $data;

	}
		
	mysqli_close($dbc); // Close the database connection.

} // End of the main submit conditional.

// Create the page:
include ('index.php');
?>