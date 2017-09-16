<?php //edit_user.php
// This page is for editing a user record.
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');
$page_title = 'Edit Users';
$userID=$_SESSION['userID'];
$userType=$_SESSION['userType'];

$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

getHeader($userType);

echo '<h1>Edit User</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}


// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = array();
	
	// Check for a first name:
	if (empty($_POST['firstname'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
	}
	
	// Check for a last name:
	if (empty($_POST['lastname'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
	}
	
	// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
	}
	
	// Check for a password and match against the confirmed password:
	if (!empty($_POST['password'])) {
		if ($_POST['password'] != $_POST['passwordc']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$password = mysqli_real_escape_string($dbc, trim($_POST['password']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	// Check for an street:
	if (empty($_POST['street'])) {
		$errors[] = 'You forgot to enter your street.';
	} else {
		$street = mysqli_real_escape_string($dbc, trim($_POST['street']));
	}
	// Check for an city:
	if (empty($_POST['city'])) {
		$errors[] = 'You forgot to enter your city.';
	} else {
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
	}	
	// Check for an state:
	if (empty($_POST['state'])) {
		$errors[] = 'You forgot to enter your state.';
	} else {
		$state = mysqli_real_escape_string($dbc, trim($_POST['state']));
	}
	// Check for an zipcode:
	if (empty($_POST['zipcode'])) {
		$errors[] = 'You forgot to enter your zipcode.';
	} else {
		$zipcode = mysqli_real_escape_string($dbc, trim($_POST['zipcode']));
	}
	if (empty($errors)) { // If everything's OK.
	
		//  Test for unique email address:
		$q = "SELECT userID FROM users WHERE email='$email' AND userID!= $id";
		$r = @mysqli_query($dbc, $q);
		if (mysqli_num_rows($r) == 0) {

			// Make the query:
			$q = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email' , password='$password', street='$street', city='$city', state='$state', zipcode='$zipcode' WHERE userID=$id LIMIT 1";
			$r = @mysqli_query ($dbc, $q);
			if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

				// Print a message:
				echo '<p>The user has been edited.</p>';	
				
			} else { // If it did not run OK.
				echo '<p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
			}
				
		} else { // Already registered.
			echo '<p class="error">The email address has already been registered.</p>';
		}
		
	} else { // Report the errors.

		echo '<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p>';
	
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form...

// Retrieve the user's information:
$q = "SELECT firstname, lastname, username, email, password, street, city, state, zipcode  FROM users WHERE userID=$id";		
$r = @mysqli_query ($dbc, $q);

if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

	// Get the user's information:
	$row = mysqli_fetch_array ($r, MYSQLI_NUM);
	
	// Create the form:
	echo '<form action="edit_user.php" method="post">
<p>First Name: <input type="text" name="firstname" size="15" maxlength="20" value="' . $row[0] . '" /></p>
<p>Last Name: <input type="text" name="lastname" size="15" maxlength="40" value="' . $row[1] . '" /></p>
<p>Username: <input type="text" name="username" size="15" maxlength="30"  value="' . $row[2] . '"  /> </p>
<p>Email Address: <input type="text" name="email" size="10" maxlength="20" value="' . $row[3] . '"  /> </p>
<p>Password: <input type="password" name="password" size="10" maxlength="20" value="' . $row[4] . '"  /> </p>
<p>Confirm Password: <input type="password" name="passwordc" size="10" maxlength="20" value="' . $row[4] . '"  /> </p>
<p>Street: <input type="text" name="street" size="20" maxlength="40" value="' . $row[5] . '"  /> </p>
<p>City: <input type="text" name="city" size="15" maxlength="30" value="' . $row[6] . '"  /> </p>
<p>State: <input type="text" name="state" size="2" maxlength="2" value="' . $row[7] . '"  /> </p>
<p>Zipcode: <input type="text" name="zipcode" size="5" maxlength="5" value="' . $row[8] . '"  /> </p>
<p><input type="submit" name="submit" value="Submit" /></p>
<input type="hidden" name="id" value="' . $id . '" />
</form>';


} else { // Not a valid user ID.
	echo '<p class="error">This page has been accessed in error.</p>';
}

mysqli_close($dbc);
		
include ('includes/footer.html');
?>