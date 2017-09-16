<?php # Script 12.4 - loggedin.php
// The user is redirected here from login.php.
session_start(); // acess the session.
// If no session is present, redirect the user:
if (!isset($_SESSION['userID'])) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	

}

// Set the page title and include the HTML header:
	$page_title = 'Logged In!';
	$userType= $_SESSION['userType'];
	$firstname= $_SESSION['firstname'];
	$lastname= $_SESSION['lastname'];
	
		if ($userType=='l'){
		//include librarian header if librarian user type
		include ('includes/librarian_header.html');	
		echo "<p></p><p><h1>Welcome</h1></p>Hello " . $firstname . ' '. $lastname . ",</p>";
		echo "We have you in our records as a Librarian.";
		echo "You are now logged in, have a great day at work.";
		}//end if userType==l

		if ($userType=='r'){
		//include reguser header if regular user type
		include ('includes/reguser_header.html');
		echo "<p></p><p><h1>Welcome</h1></p>Hello " . $firstname . ",</p>";
		echo "You are now logged in, feel free to browse the books.";
		}//end if userType==r

include ('includes/footer.html');
?>