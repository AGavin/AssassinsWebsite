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
	
	?>
	  <div class="section">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              <ul class="nav nav-pills">
                <li class="active">
                  <a href="index.php">Home</a>
                </li>
                <li>
                  <a href="Rules.html">Rules<br></a>
                </li>
				<li>
                  <a href="">Archives<br></a>
                </li>
				<li>
                  <a href="">Team<br></a>
                </li>
				<li>
                  <a href="">Targets<br></a>
                </li>
				<li>
                  <a href="">Send a  message to your team<br></a>
                </li>
				<li>
                  <a href="">Change your details<br></a>
                </li>
				<li>
                  <a href="">Change password<br></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
	<?php
	
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

				<ul class="nav navbar-nav navbar-right">
                  <form class="navbar-form navbar-left" role="form" method="post" action="Homepage.php">
                    <div class="form-group">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" id="password" id="password" placeholder="Password">
                    </div>
				    <div class="form-group">
                      <button id ="login" name="login" type="submit" class="btn btn-default">Log In</button>
	  			    </div>
                  </form>
                </ul>
