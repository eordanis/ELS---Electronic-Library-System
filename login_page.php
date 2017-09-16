<?php
// This is the login page for the site.
$page_title = 'Login';

require ('mysqli_connect.php'); // Connect to the db.

// If the form is submitted or not.
if (isset($_POST['username']) and isset($_POST['password'])){
	
// Assigning posted values to variables.
$username = $_POST['username'];
$password = $_POST['password'];

// Checking the values are existing in the database or not
$q = "SELECT userID, firstname, username FROM users WHERE username='$username' AND password='$password'";	
$r = mysql_query($q) or die(mysql_error());
$count = mysql_num_rows($r);

// If the posted values are equal to the database values, then session will be created for the user.
if ($count == 1){
	
$_SESSION['username'] = $username;
}else{
// If the login credentials doesn't match, user will be shown with an error message.
echo "Invalid Login Credentials.";
}
}
//if the user is logged in Greet the user with message
if (isset($_SESSION['username'])){
$username = $_SESSION['username'];
echo "Hi " . $username . "
";

echo "<a href='logout.php'>Logout</a>";
 
}
?>

<h1>Login</h1>
<form action="login_page.php" method="post">
	<p>Username: <input type="text" placeholder="username" name="username" size="15" maxlength="30" required="required" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /></p>
	<p>Password: <input type="password" placeholder="password" name="password" size="10" maxlength="20" required="required"value="<?php if (isset($_POST['password'])) echo $_POST['password']; ?>"  /></p>
	<p><input type="submit" name="submit" value="Login" /></p>
</form>

