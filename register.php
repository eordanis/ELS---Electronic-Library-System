<?php 
// This script performs an INSERT query to add a record to the users table.

$page_title = 'Register User';
include ('includes/login_header.html');

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
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
	
	// Check for a username:
	if (empty($_POST['username'])) {
		$errors[] = 'You forgot to enter your username.';
	} else {
		// search users to make sure username is not taken
		$usernamequery="SELECT username FROM users WHERE username='username'"; 		
		$usernameresults = @mysqli_query ($dbc, $usernamequery); // Run the query.
		$usernamecount = mysqli_num_rows($usernameresults);
		if ($usernamecount==1) { // If username is taken
		$errors[] = 'The username you entered is taken, please enter a new one';
		}//end username is taken
		if ($usernamecount==0) { // If username is not taken
		$username = mysqli_real_escape_string($dbc, trim($_POST['username']));
		}//end if username is not taken
	}//end username is unique
		
		// Check for an email address:
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		// search users to make sure email is not taken
		// Make the query:
		$emailquery="SELECT email FROM users WHERE email='email'"; 		
		$emailresults = @mysqli_query ($dbc, $emailquery); // Run the query.
		$emailcount = mysqli_num_rows($emailresults);
		if ($emailcount==1) { // If email is taken
		$errors[] = 'The email address you entered is already linked with an existing account, please enter a new one';
		}//end email is taken
		if ($emailcount==0) { // If email is not taken
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		}//end if email is not taken
	}//end email is unique
	
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
	// Check for an age:
	if (empty($_POST['age'])) {
		$errors[] = 'You forgot to enter your age.';
	} else {
		$age = mysqli_real_escape_string($dbc, trim($_POST['age']));
	}
	// Check for a gender:
	if (empty($_POST['gender'])) {
		$errors[] = 'You forgot to enter your gender.';
	} else {
		$gender = mysqli_real_escape_string($dbc, trim($_POST['gender']));
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
	
		// Register the user in the database...

		// Make the query:
		$q = "INSERT INTO users (firstname, lastname, username, email, password, age, gender, street, city, state, zipcode) VALUES ('$firstname', '$lastname', '$username', '$email', SHA1('$password'), '$age', '$gender', '$street', '$city', '$state', '$zipcode')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
		<p>You are now registered.</p><p><br /></p>';	
		
		} else { // If it did not run OK.
			
			//Public message:
			echo '<h1>System Error</h1>
			<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
						
		} // End of if ($r) IF.
		
		mysqli_close($dbc); // Close the database connection.

		// Include the footer and quit the script:
		include ('includes/footer.html'); 
		exit();
		
	} else { // Report the errors.
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>
<h1>Register</h1>
<form action="register.php" method="post">
	<p>First Name: <input type="text" pattern="^[A-Z]{1}[a-z]{1,49}$" placeholder="First Name" name="firstname" size="15" maxlength="20" required="required" autocomplete="on" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" /></p>
	<p>Last Name: <input type="text" pattern="^[A-Z]{1}[a-z]{1,49}$" placeholder="Last Name" name="lastname" size="15" maxlength="40" required="required" autocomplete="on" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" /></p>
	<p>Username: <input type="text" pattern="[A-Za-z0-9]{6,20}$" placeholder="username" name="username" size="15" maxlength="30" required="required" autocomplete="on" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></p>
	<p>Email Address: <input type="text" pattern="^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$" placeholder="name@domain.com" name="email" size="20" maxlength="40" autocomplete="on" required="required" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" pattern="[A-Za-z0-9]{6,20}$" placeholder="password" name="password" size="10" maxlength="20" required="required" autocomplete="on" value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"  /></p>
	<p>Confirm Password: <input type="password" pattern="[A-Za-z0-9]{6,20}$" placeholder="password" name="passwordc" size="10" maxlength="20" required="required" autocomplete="on" value="<?php if (isset($_POST['passwordc'])) echo $_POST['passwordc']; ?>"  /></p>
	<p>Age: <input type="text" pattern="^[0-9]{1,3}$" placeholder="Age" name="age" size="3" maxlength="3" required="required" autocomplete="on" value="<?php if (isset($_POST['age'])) echo $_POST['age']; ?>" /></p>
	<p>Gender:&nbsp&nbsp<input type="radio" name="gender" value="male" checked>Male&nbsp&nbsp<input type="radio" name="gender" autocomplete="on" value="female">Female</p>
	<p>Street: <input type="text" pattern="^[0-9]{1,5}[\s]{1,2}[A-Za-z]{1,20}[\s]{1,2}[A-Za-z]{1,20}$" placeholder="123 Street Avenue" name="street" size="20" maxlength="40" required="required" autocomplete="on" value="<?php if (isset($_POST['street'])) echo $_POST['street']; ?>" /></p>
	<p>City: <input type="text" name="city" pattern="[A-Za-z]{3,30}$" placeholder="City" size="15" maxlength="30" required="required" autocomplete="on" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" /></p>
	<p>State: <input type="text" pattern="^[A-Za-z]{2}$" name="state" placeholder="NJ" size="2" maxlength="2" required="required" autocomplete="on" value="<?php if (isset($_POST['state'])) echo $_POST['state']; ?>" /></p>
	<p>Zipcode: <input type="text" pattern="^[0-9]{5}" name="zipcode" placeholder="00075" size="5" maxlength="5" required="required" autocomplete="on" value="<?php if (isset($_POST['zipcode'])) echo $_POST['zipcode']; ?>" /></p>
	<p><input type="submit" name="submit" value="Register" /></p>
</form>
<?php include ('includes/footer.html'); ?>