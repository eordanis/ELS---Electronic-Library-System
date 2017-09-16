<?php 
// This script performs an INSERT query to add a record to the book table.

$page_title = 'Add Book';
include ('includes/librarian_header.html');
session_start(); // Access the existing session.
$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";
// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require ('mysqli_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize an error array.
	
	// Check for a title:
	if (empty($_POST['title'])) {
		$errors[] = 'You forgot to enter the title.';
	} else {
		$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
	}
	
	// Check for author first name:
	if (empty($_POST['authorfirstname'])) {
		$errors[] = 'You forgot to enter the authors first name.';
	} else {
		$authorfirstname = mysqli_real_escape_string($dbc, trim($_POST['authorfirstname']));
	}
	
	// Check for author last name:
	if (empty($_POST['authorlastname'])) {
		$errors[] = 'You forgot to enter the authors last name.';
	} else {
		$authorlastname = mysqli_real_escape_string($dbc, trim($_POST['authorlastname']));
	}
	
	// Check for an isbn:
	if (empty($_POST['isbn'])) {
		$errors[] = 'You forgot to enter the ISBN.';
	} else {
		$isbn = mysqli_real_escape_string($dbc, trim($_POST['isbn']));
	}
	
	// Check for a quantity:
	if (empty($_POST['quantity'])) {
		$errors[] = 'You forgot to the quantity.';
	} else {
		$quantity = mysqli_real_escape_string($dbc, trim($_POST['quantity']));
	}
	
	// Check for a genre:
	if (empty($_POST['genre'])) {
		$errors[] = 'You forgot to the genre.';
	} else {
		$genre = mysqli_real_escape_string($dbc, trim($_POST['genre']));
	}

	if (empty($errors)) { // If everything's OK.
	
		// Register the user in the database...

		// Make the query:
		$q = "INSERT INTO book (title, authorfirstname, authorlastname, isbn, quantity, genre) VALUES ('$title', '$authorfirstname', '$authorlastname', '$isbn', '$quantity', '$genre')";		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<h1>Thank you!</h1>
		<p>You now added the book.</p><p><br /></p>';	
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Book could not be added due to a system error. We apologize for any inconvenience.</p>'; 
			
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
<p></p><p><h1>Add Book</h1></p>
<form action="add_book.php" method="post">
	<p>Book Title: <input type="text" placeholder="Book Title" name="title" size="30" maxlength="60" required="required" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" /></p>
	<p>Author First Name: <input type="text" placeholder="Author First Name" name="authorfirstname" size="15" maxlength="30" required="required" value="<?php if (isset($_POST['authorfirstname'])) echo $_POST['authorfirstname']; ?>" /></p>
	<p>Author Last Name: <input type="text" placeholder="Author Last Name" name="authorlastname" size="15" maxlength="30" required="required" value="<?php if (isset($_POST['authorlastname'])) echo $_POST['authorlastname']; ?>" /></p>
	<p>ISBN: <input type="text" placeholder="1198840241" name="isbn" size="15" maxlength="20" required="required" value="<?php if (isset($_POST['isbn'])) echo $_POST['isbn']; ?>" /></p>
	<p>Quantity: <input type="text" placeholder="20" name="quantity" size="10" maxlength="10" required="required" value="<?php if (isset($_POST['quantity'])) echo $_POST['quantity']; ?>"  /> </p>
	<p>Genre: <input type="text" placeholder="Science Fiction" name="genre" size="20" maxlength="60" required="required" value="<?php if (isset($_POST['genre'])) echo $_POST['genre']; ?>"  /></p>
	<p><input type="submit" name="submit" value="Add Book" /></p>
</form>
<?php include ('includes/footer.html'); ?>