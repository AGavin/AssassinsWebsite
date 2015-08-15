<?php //unknown whether this is the correct way of doing things
	$Done = 'not yet submitted';
	if (isset($_POST["login"])) 
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		$Done='This works';
	}
?>

<!DOCTYPE html>
<html><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="All.css">
  </head><body>
    <!-- Make sure the navbar is only altered here and copied into the other
    files afterward. There will need to be a seperate navbar for if you are logged in -->
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <ul class="nav nav-pills">
              <li class="active">
                <a href="Homepage.html">Home</a>
              </li>
              <li>
                <a href="Rules.html">Rules<br></a>
              </li>
              <ul class="nav navbar-nav navbar-right">
                <form class="navbar-form navbar-left" role="form" method="post" action="Homepage.php"><!-- Homepage.php needs changing for each different page???-->
                  <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" id="password" id="password" placeholder="Password">
                  </div>
				  <div class="form-group">
                    <button id ="login" name="login" type="submit" class="btn btn-default">Log In</button>
				  </div>
				  <div class="form-group">
                    <input type="text" class="form-control" id="test" id="test" placeholder= <?php echo $Done ?> >
				  </div>
                </form>
              </ul>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <p></p>
            <img src="logo.jpg" class="center-block img-responsive">
            <p class="text-center">&nbsp;</p>
            <p <p="" class="text-center">&nbsp;Ever thought that life at Oxford would be so much more fun if people
              were trying to kill you?
              <br>Ever wondered if your post might be full of attack animals, ready to rip
              you to shreds?
              <br>Ever wanted to have a gun battle at 2AM in somebody else's college?</p>
            <p class="text-center">Then the Oxford Guild of Assassins is for you...</p>
              <p class="text-center">If you are already a member, login above to join in games.</p>
              <p class="text-center">If you would like to register, please register here, but please read the
                rules first</p>
              <p></p>
          </div>
        </div>
      </div>
    </div>
  

</body></html>