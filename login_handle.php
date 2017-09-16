
<?php
// This is the login page for the site.
$page_title = 'Login';
session_start(); // Starting Session
$error=''; // Variable To Store Error Message

if (isset($_POST['submit'])) {
if (empty($_POST['username']) || empty($_POST['password'])) {
$error = "Username or Password is invalid";
}//end if empty username passord
else{
// Define $username and $password
$username=$_POST['username'];
$password=$_POST['password'];

// Query the database:
$q = "SELECT userID, firstname, username FROM users WHERE username='$username' AND password='$password'";		
$r = @mysqli_query ($dbc, $q); // Run the query.
if (mysqli_num_rows($r) == 1) { // A match was made.
}//end else if
}//end submit statement
?>

