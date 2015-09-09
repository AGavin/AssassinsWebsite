<?php
$login_status=0;

$login_fail['Member']=2;
$login_fail['Guest']=1;
$login_fail['Not Logged In']=0;
$login_fail['Wrong Password']=-1;
$login_fail['No Session']=-2;

include("connect.php");
if ($_GET['logout']) {
    $sql="DELETE FROM sessions WHERE sid='".session_id()."'";
    $result=mysql_query($sql,$link) or die("pgheader: ".mysql_error());
    session_unset();
    session_destroy();
    $_SESSION=array();
    $_SERVER['QUERY_STRING']=str_replace('logout=1','',$_SERVER['QUERY_STRING']);
}

$sql="DELETE FROM sessions WHERE last<".(time()-20*60);
$result=mysql_query($sql,$link) or die("pgheader: ".mysql_error());

if ($_SESSION['uid']) {
    $sql="SELECT * FROM sessions WHERE uid=".$_SESSION['uid']." AND sid='".session_id()."'";
    $result=mysql_query($sql,$link) or die("login:20: ".mysql_error());
    $numrows=mysql_num_rows($result);

    if($numrows!=0) {
	$login_status=$login_fail['Member'];
    } else {
    	session_unset();
    	session_destroy();
    	$_SESSION=array();
	$login_status=$login_fail['No Session'];
    }
} else {
    if ($_POST['username']) {
	if (md5($_POST['password'])!=$usernames[$_POST['username']]['password']) {
	    $login_status=$login_fail['Wrong Password'];
	} else {
            $_SESSION['uid'] = $usernames[$_POST['username']]['id'];
	    $userid=$_SESSION['uid'];

	    $sql="INSERT INTO sessions (uid,sid,created,last,ip) VALUES ($userid,'".session_id()."',".time().",".time().",'".$_SERVER['REMOTE_ADDR']."')";
	    $result2=mysql_query($sql,$link) or die("user_admin:34: ".mysql_error());

	    $sql="INSERT INTO logins (uid,login_time,ip) VALUES ($userid,'".date("Y-m-d H:i:00",time())."','".$_SERVER['REMOTE_ADDR']."')";
	    $result3=mysql_query($sql,$link) or die("user_admin:39: ".mysql_error());

	    $login_status=$login_fail['Member'];
        }
    } else {
	$login_status=$login_fail['Not Logged In'];
    }
}

if($login_status>0) {
    $sql="UPDATE sessions SET last=".time()." WHERE sid='".session_id()."'";
    $result=mysql_query($sql,$link) or die("pgheader: ".mysql_error());
}

$userid=$_SESSION['uid'];
$aclev=$users[$userid]['level'];

?>
