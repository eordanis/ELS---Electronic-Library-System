<?php
// This page is for deleting a user record.
// This page is accessed through view_users.php.
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');
$userType=$_SESSION['userType'];
getHeader($userType);


$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

echo '<p></p><p><h1>Delete a User</h1></p>';

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

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM users WHERE userID=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The user has been deleted.</p>';	

		} else { // If the query did not run OK.
			echo '<p class="error">The user could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The user has NOT been deleted.</p>';	
	}

} else { // Show the form.

	// Retrieve the user's information:
	$q = "SELECT CONCAT(lastname, ', ', firstname) FROM users WHERE userID=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid user ID, show the form.

		// Get the user's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3><p>Name: $row[0]</p></h3>";
		
		// Create the form:
		//echo '<p><form action="delete_user.php" method="post">
	//<input type="radio" name="sure" value="Yes" /> Yes <input type="radio" name="sure" value="No" checked="checked" /> No
	//<input type="submit" name="submit" value="Submit" />
	//<input type="hidden" name="id" value="' . $id . '" />
	//</form></p>';
	
	echo '<form action="delete_user.php" method="post">
	<p>Are you sure you want to delete this user?&nbsp&nbsp<input type="radio" name="sure" value="Yes">&nbsp&nbspYes&nbsp&nbsp<input type="radio" name="sure" value="No" checked="checked">&nbsp&nbspNo&nbsp&nbsp<input type="submit" name="submit" value="Submit" /><input type="hidden" name="id" value="' . $id . '" /></p>
	</form>';
	} else { // Not a valid user ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);	
include ('includes/footer.html');
?>