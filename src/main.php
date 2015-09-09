<?php 
session_start();

include("functions.php");
include("tidy.php");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>The Oxford Guild of Assassins</title>
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="All.css">
  </head>
  <body>
    <a name="top"></a>
    <?php include("login.php"); ?>
    <hr>
    <?php page_contents(); ?>
    <hr>
    <p>&copy; 2012, pilly and the Oxford Guild of Assassins. The site is coded, maintained and hosted by <a href="mailto:webmaster@oxfordassassinsguild.org.uk">pilly</a>, contact me if you have any comments or questions about it, or if you find a broken link. This website best viewed using your eyes.
  </body>
</html>