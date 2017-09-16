<?php
// This page is for deleting a book record.
// This page is accessed through view_book.php.
$page_title = 'Delete Book';
include ('includes/librarian_header.html');
echo '<p></p><p><h1>Delete Book</h1></p>';
session_start(); // Access the existing session.
$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

// Check for a book ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_books.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html'); 
	exit();
}

require ('mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM book WHERE bookID=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>The book has been deleted.</p>';	

		} else { // If the query did not run OK.
			echo '<p class="error">The book could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>The book has NOT been deleted.</p>';	
	}

} else { // Show the form.

	// Retrieve the book information:
	$q = "SELECT CONCAT(title, ' by ', authorlastname, ', ', authorfirstname) FROM book WHERE bookID=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) { // Valid book ID, show the form.

		// Get the book information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3><p>Name: $row[0]</p></h3>";
		
		// Create the form:
		echo '<form action="delete_book.php" method="post">
			<p>Are you sure you want to delete this book?&nbsp&nbsp<input type="radio" name="sure" value="Yes">&nbsp&nbspYes&nbsp&nbsp<input type="radio" name="sure" value="No" checked="checked">&nbsp&nbspNo&nbsp&nbsp<input type="submit" name="submit" value="Submit" /><input type="hidden" name="id" value="' . $id . '" /></p>
		</form>';
		
		//echo '<form action="delete_book.php" method="post">
	//<input type="radio" name="sure" value="Yes" /> Yes 
	//<input type="radio" name="sure" value="No" checked="checked" /> No
	//<input type="submit" name="submit" value="Submit" />
	//<input type="hidden" name="id" value="' . $id . '" />
	//</form>';
	
	} else { // Not a valid book ID.
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.

mysqli_close($dbc);
		
include ('includes/footer.html');
?>