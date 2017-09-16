<?php 
// This script searches for a book in the database
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');

$userID=$_SESSION['userID'];
$userType=$_SESSION['userType'];
$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];

getHeader($userType);
$page_title = 'Search Books';

echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		
	$errors = array(); // Initialize an error array.
	
	// Check for a title:
	if (empty($_POST['search'])) {
		$errors[] = 'You forgot to enter the title.';
	} else {
		$search = mysqli_real_escape_string($dbc, trim($_POST['search']));
	}


	if (empty($errors)) { // If everything's OK.
	
	// Determine the sort...
// Default is by title.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'g':
		$order_by = 'genre ASC';
		break;
	case 'b':
		$order_by = 'quantity ASC';
		break;
	case 'isbn':
		$order_by = 'isbn ASC';
		break;
	case 'rd':
		$order_by = 'title ASC';
		break;
	default:
		$order_by = 'title ASC';
		$sort = 'rd';
		break;
}
		// search for book in the database...
		// Make the query:
		$q="SELECT title, authorfirstname, authorlastname, bookID, isbn, genre, quantity FROM book WHERE  title LIKE '%" . $search . "%' OR authorfirstname LIKE '%" . $search . "%' OR authorlastname LIKE '%" . $search  ."%' OR isbn LIKE '%" . $search . "%' OR genre LIKE '%" . $search . "%'"; 		
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
		
			// Print a message:
			echo '<p></p><p><h1>Search Results:</h1></p>
		<p>Here Are Your Search Results:</p><p><br /></p>';	
		
		// Table header:
echo '<table align="center" cellspacing="5" cellpadding="5" width="100%">
<tr>
	<td align="left"><b>Checkout</b></td>
	<td align="left"><b>Details</b></td>
	<td align="left"><b>Author Name</b></td>
	<td align="left"><b><a href="search_book.php?sort=rd">Book Title</a></b></td>
	<td align="left"><b><a href="search_book.php?sort=isbn">Book ISBN</a></b></td>
	<td align="left"><b><a href="search_book.php?sort=g">Book Genre</a></b></td>
	<td align="left"><b><a href="search_book.php?sort=b">Book Quantity</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="checkout.php?id=' . $row['bookID'] . '"> Check-Out </a></td>
		<td align="left"><a href="book_details.php?id=' . $row['bookID'] . '">Details </a></td>
		<td align="left">' . $row['authorlastname'] . ', '. $row['authorfirstname'] . '</td>
		<td align="left">' . $row['title'] . '</td>
		<td align="left">' . $row['isbn'] . '</td>
		<td align="left">' . $row['genre'] . '</td>
		<td align="left">' . $row['quantity'] . '</td>
	</tr>
	';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);
		
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h1>
			<p class="error">Book could not be searched due to a system error. We apologize for any inconvenience.</p>'; 
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
			mysqli_close($dbc); // Close the database connection.			
		} // End of if ($r) IF.
		
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
<p></p><p><h1>Search Books</h1></p>
<form action="search_book.php" method="post">
	<p>Search Books: <input type="text" placeholder="title or author first/last name or isbn or genre" name="search" size="35" maxlength="60"  value="<?php if (isset($_POST['search'])) echo $_POST['search']; ?>" /> &nbsp&nbsp&nbsp&nbsp<input type="submit" name="submit" value="Search Book" /></p>
</form>
<p></p>
<?php include ('includes/footer.html');?>