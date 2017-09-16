<?php
// This script retrieves the records from the logged in user from the users table.
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');
$page_title = 'Account Information';
$userID=$_SESSION['userID'];
$userType=$_SESSION['userType'];
$bookID = $_GET['id'];

$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

getHeader($userType);

echo '<p></p><p><h1>View Book Details</h1></p>';


// Define the query:
$q ="SELECT authorlastname, authorfirstname, title, isbn, genre, quantity, bookID FROM book WHERE bookID='$bookID'";		
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:

echo '<table align="center" cellspacing="10" cellpadding="5" width="100%">';
// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
<tr bgcolor="#eeeeee"><td align="left"><b>Author Last Name:</b></td><td align="left">' . $row['authorlastname'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>Author First Name:</b><td align="left">' . $row['authorfirstname'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Title:</b></td><td align="left">' . $row['title'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>ISBN:</b></td><td align="left">' . $row['isbn'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Genre:</b></td><td align="left">' . $row['genre'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>Quantity:</b></td><td align="left">' . $row['quantity'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Book ID:</b></td><td align="left">' . $row['bookID'] . '</td></tr>

	';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);
include ('includes/footer.html');
?>