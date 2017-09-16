<?php
// This script retrieves all the records from the users table.
// This new version allows the results to be sorted in different ways.

$page_title = 'View Registered Users';
include ('includes/librarian_header.html');
echo '<p></p><p><h1>View Registered Users</h1></p>';

require ('mysqli_connect.php');
session_start(); // Access the existing session.
$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";
// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(userID) FROM users";
	$r = @mysqli_query ($dbc, $q);
	$row = @mysqli_fetch_array ($r, MYSQLI_NUM);
	$records = $row[0];
	// Calculate the number of pages...
	if ($records > $display) { // More than 1 page.
		$pages = ceil ($records/$display);
	} else {
		$pages = 1;
	}
} // End of p IF.

// Determine where in the database to start returning results...
if (isset($_GET['s']) && is_numeric($_GET['s'])) {
	$start = $_GET['s'];
} else {
	$start = 0;
}

// Determine the sort...
// Default is by registration date.
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

// Determine the sorting order:
switch ($sort) {
	case 'ln':
		$order_by = 'lastname ASC';
		break;
	case 'id':
		$order_by = 'userID ASC';
		break;
	case 'un':
		$order_by = 'username ASC';
		break;
	case 'fn':
		$order_by = 'firstname ASC';
		break;
	case 'rd':
		$order_by = 'registrationDate ASC';
		break;
	default:
		$order_by = 'registrationDate ASC';
		$sort = 'rd';
		break;
}
	
// Define the query:
$q = "SELECT lastname, firstname, username, DATE_FORMAT(registrationDate, '%M %d, %Y') AS dr, userID FROM users ORDER BY $order_by LIMIT $start, $display";		
$r = @mysqli_query ($dbc, $q); // Run the query.

// Table header:
echo '<table align="center" cellspacing="10" cellpadding="5" width="100%">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="view_users.php?sort=ln">Last Name</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=fn">First Name</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=rd">Date Registered</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=id">User ID</a></b></td>
	<td align="left"><b><a href="view_users.php?sort=un">Username</a></b></td>
</tr>
';

// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_user.php?id=' . $row['userID'] . '">Edit</a></td>
		<td align="left"><a href="delete_user.php?id=' . $row['userID'] . '">Delete</a></td>
		<td align="left">' . $row['lastname'] . '</td>
		<td align="left">' . $row['firstname'] . '</td>
		<td align="left">' . $row['dr'] . '</td>
		<td align="left">' . $row['userID'] . '</td>
		<td align="left">' . $row['username'] . '</td>
	</tr>
	';
} // End of WHILE loop.

echo '</table>';
mysqli_free_result ($r);
mysqli_close($dbc);

// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="view_users.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="view_users.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="view_users.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>'; // Close the paragraph.
	
} // End of links section.
	
include ('includes/footer.html');
?>