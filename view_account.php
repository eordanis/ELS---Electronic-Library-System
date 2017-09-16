<?php
// This script retrieves the records from the logged in user from the users table.
session_start(); // Access the existing session.
require ('mysqli_connect.php');
include ('includes/functions.php');

$userID=$_SESSION['userID'];
$userType=$_SESSION['userType'];
$firstname=$_SESSION['firstname'];
$lastname=$_SESSION['lastname'];

getHeader($userType);
$page_title = 'Account Information';
echo "<p id='currname'>Hello $firstname, $lastname. <p id='currname2'>If this is not you, please logout and log back in</p></p>";
echo '<p><h1>View Your Account Information</h1></p>';



echo '<p><a href="edit_user.php?id=' . $userID . '">Click Here To Edit Your Account Information</a></p><p></p>';
// Define the query:
$q = "SELECT userID, lastname, firstname, username, email, age, gender, street, city, state, zipCode, DATE_FORMAT(registrationDate, '%M %d, %Y') AS dr, userType, userStatus, userbookCount  FROM users WHERE userID='$userID'";		
$r = @mysqli_query ($dbc, $q); // Run the query.
if ($r) {
// Table header:

echo '<table align="center" cellspacing="10" cellpadding="5" width="100%">';
// Fetch and print all the records....
$bg = '#eeeeee'; 
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee');
		echo '<tr bgcolor="' . $bg . '">
<tr bgcolor="#eeeeee"><td align="left"><b>Last Name:</b></td><td align="left">' . $row['lastname'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>First Name:</b><td align="left">' . $row['firstname'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Username:</b></td><td align="left">' . $row['username'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>Email:</b></td><td align="left">' . $row['email'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Age:</b></td><td align="left">' . $row['age'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>Gender:</b></td><td align="left">' . $row['gender'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>Address:</b></td><td align="left">' . $row['street'] . ", " .$row['city'] . ", " .  $row['state'] . ", " . $row['zipCode'] .'</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>Date Registered:</b></td><td align="left">' . $row['dr'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>User ID:</b></td><td align="left">' . $row['userID'] . '</td></tr>
<tr bgcolor="#ffffff"><td align="left"><b>User Type:</b></td><td align="left">' . $row['userType'] . '</td></tr>
<tr bgcolor="#eeeeee"><td align="left"><b>User Status:</b></td><td align="left">' . $row['userStatus'] . '</td></tr>
';
} // End of WHILE loop.
echo '</table>';
}//end if $r is not empty
if( !$r ){    echo mysqli_error($dbc);}
echo '<p></p><p><h1>View Books You Currently Have Checked Out</h1></p>';

echo '<p><a href="bookhistory.php?id=' . $userID . '">Click Here To View Your Book Checkout History</a></p><p></p>';
// Determine the sort...
// Default is by loanDate
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'rd';

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
$q = "SELECT bookID, loanID, loanStatus, DATE_FORMAT(loanDate, '%M %d, %Y') AS ld  FROM loan WHERE userID='$userID' and loanStatus='out'";		
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
	} // End of inner WHILE loop.
}//end of outer while loop
echo '</table>';
}//end if $result is not empty

 	// Count the number of records:
	$qcount = "SELECT COUNT(userID) FROM loan WHERE loanStatus='out'";
	$rcount = @mysqli_query ($dbc, $qcount);
	$rowcount = @mysqli_fetch_array ($rcount, MYSQLI_NUM);
	$recordscount = $rowcount[0];
	if($recordscount<1){
		echo'<p class="error" align="center">You Currently Do Not Have Any Books Checked Out</p>';
	}
	
mysqli_free_result ($result);
mysqli_close($dbc);
include ('includes/footer.html');
?>