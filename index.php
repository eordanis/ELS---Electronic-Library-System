<?php
$page_title = 'Welcome to the Electronic Library!';
include ('includes/login_header.html');


// Print any error messages, if they exist:
if (isset($errors) && !empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Display the form:

echo'<p></p><p><h1>Welcome</h1></p>';
echo'<p>Please first login or register before continuing</p>';

echo'<h1>Login</h1>
<form action="login.php" method="post">
	<p>Username: <input type="text" placeholder="username" name="username" size="15" maxlength="30" required="required" /></p>
	<p>Password: <input type="password" placeholder="password" name="password" size="10" maxlength="20" required="required"/></p>
	<p><input type="submit" name="submit" value="Login" /></p>
</form>
';

echo'<p><a href="register.php">Click Here To Register</a></p>';


include ('includes/footer.html');
?>