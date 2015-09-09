<?php
global $login_status,$login_fail;

switch($login_status) {
case $login_fail['No Session']:
    ?>
	<p>Session could not be found or was destroyed.
    <?php
    break;
case $login_fail['Wrong Password']:
    ?>
	<p>Wrong Password.
    <?php
    break;
}

if($login_status>0) {
	print "<p>Logged in as: ".$users[$userid]['name']." (AKA ".$users[$userid]['alias'].")";
	if($users[$userid]['team']!="") {
	    print " | ".$users[$userid]['team'];
	}
	if($users[$userid]['role']!="") {
	    print " | ".$users[$userid]['role'];
	} 

	print '<br><a href="index.php">Home Page</a>';
	print ' | <a href="team.php">Your Team</a>';
	print ' | <a href="target.php">Your Targets</a>';
	print ' | <a href="mail.php">Send a message to your team</a>';
//	print ' | <a href="report.php">Make a Kill Report</a>';
	print ' | <a href="passwd.php?action=details">Change Details</a>';
	print ' | <a href="passwd.php?action=change">Change password</a> | <a href="?logout=1';

    	foreach($_GET as $k => $v) {
	    print "&$k=$v";
    	}

	print '">Logout</a>'."\n";
} else {
    ?>
            <form method="POST" action="<?php print $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>">
		<p>Login E-mail:<input type="text" name="username" size=10>
		   Password:</td><td><input type="password" name="password" size=10>
		   <input type="submit" value="Log in"> | 
		   <a href="index.php">Home Page</a> |
		   <a href="passwd.php?action=retrieve">Forgotten Password?</a> | 
		   <a href="passwd.php?action=newuser">Register</a>
            </form>
    <?php
}

?>
