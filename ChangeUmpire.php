<?php function page_contents() {
	global $users,$teams,$publickey,$error,$ingame,$userid;//Don't know if these are needed or not
	include("src/connect.php");
	
// $sql="SELECT * FROM players WHERE team='umpire'";//To show the current umpire is necessary
                  // $result=mysql_query($sql,$link) or die($mysql_error());
				  // $resArray = mysql_fetch_array($result);
				  // print $resArray['alias'];

$sql="UPDATE players SET team='NOTEAM' WHERE team='umpire'";
                      $result=mysql_query($sql,$link) or die($mysql_error());
					  
$sql="UPDATE players SET team='umpire' WHERE id=".$_SESSION['uid'];
                      $result=mysql_query($sql,$link) or die($mysql_error());
					  
}
include("src/main.php");
?>