<?php
// This script retrieves the records from the logged in user from the loan table.
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');

$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";

$userID=$_SESSION['userID'];
$userType=$_SESSION['userType'];

getHeader($userType);
$page_title = 'Checkout History';

echo '<p></p><p><h1>View Your Book Checkout History</h1></p>';


// Number of records to show per page:
$display = 10;

// Determine how many pages there are...
if (isset($_GET['p']) && is_numeric($_GET['p'])) { // Already been determined.
	$pages = $_GET['p'];
} else { // Need to determine.
 	// Count the number of records:
	$q = "SELECT COUNT(userID) FROM loan";
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
// Default is by loanDate
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'ld';

// Determine the sorting order:
switch ($sort) {
	case 'ln':
		$order_by = 'authorlastname ASC';
		break;
	case 'isbn':
		$order_by = 'isbn ASC';
		break;
	case 't':
		$order_by = 'title ASC';
		break;
	case 'fn':
		$order_by = 'authorfirstname ASC';
		break;
	case 'lrd':
		$order_by = 'loanReturnDate ASC';
		break;
	case 'ls':
		$order_by = 'loanStatus ASC';
		break;
	case 'ld':
		$order_by = 'loanDate ASC';
		break;
	default:
		$order_by = 'loanDate ASC';
		$sort = 'ld';
		break;
}


// Define the query:
$q = "SELECT bookID, loanID, loanStatus, DATE_FORMAT(loanDate, '%M %d, %Y') AS ld  FROM loan WHERE userID='$userID'";		
$result = @mysqli_query ($dbc, $q); // Run the query.
if($result){


// Table header:
echo '<table align="center" cellspacing="10" cellpadding="5" width="100%">
<tr>
	<td align="left"><b>Checkin</b></td>
	<td align="left"><b>Details</b></td>
	<td align="left"><b><a href="view_account.php?sort=ln">Author Last Name</a></b></td>
	<td align="left"><b><a href="view_account.php?sort=fn">Author First Name</a></b></td>
	<td align="left"><b><a href="view_account.php?sort=t">Book Title</a></b></td>
	<td align="left"><b><a href="view_account.php?sort=isbn">Book ISBN</a></b></td>
	<td align="left"><b><a href="view_account.php?sort=ld">Loan Date</a></b></td>
	<td align="left"><b><a href="view_account.php?sort=ls">Loan Status</a></b></td>

</tr>
';

// Fetch and print all the records....
while ($rowz = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
$q = "SELECT authorlastname, authorfirstname, title, isbn  FROM book WHERE bookID='$rowz[bookID]'";		
$r = @mysqli_query ($dbc, $q); // Run the query.
if($r){
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="checkin.php?id=' . $rowz['bookID'] . '"> Checkin </a></td>
		<td align="left"><a href="book_details.php?id=' . $rowz['bookID'] . '">Details </a></td>
		<td align="left">' . $row['authorlastname'] . '</td>
		<td align="left">' . $row['authorfirstname'] . '</td>
		<td align="left">' . $row['title'] . '</td>
		<td align="left">' . $row['isbn'] . '</td>
		<td align="left">' . $rowz['ld'] . '</td>
		<td align="left">' . $rowz['loanStatus'] . '</td>

	</tr>
	';
} // End of WHILE loop.
}
}//end bookID while loop
echo '</table>';

}//end if $result is not empty

mysqli_free_result ($r);
mysqli_close($dbc);


// Make the links to other pages, if necessary.
if ($pages > 1) {
	
	echo '<br /><p>';
	$current_page = ($start/$display) + 1;
	
	// If it's not the first page, make a Previous button:
	if ($current_page != 1) {
		echo '<a href="bookhistory.php?s=' . ($start - $display) . '&p=' . $pages . '&sort=' . $sort . '">Previous</a> ';
	}
	
	// Make all the numbered pages:
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="bookhistory.php?s=' . (($display * ($i - 1))) . '&p=' . $pages . '&sort=' . $sort . '">' . $i . '</a> ';
		} else {
			echo $i . ' ';
		}
	} // End of FOR loop.
	
	// If it's not the last page, make a Next button:
	if ($current_page != $pages) {
		echo '<a href="bookhistory.php?s=' . ($start + $display) . '&p=' . $pages . '&sort=' . $sort . '">Next</a>';
	}
	
	echo '</p>'; // Close the paragraph.
	
} // End of links section.
	

include ('includes/footer.html');
?>