<?php
require_once('/srv/oxfordassassinsguild.org.uk/recaptcha/recaptchalib.php');

$publickey = "6LdyL9cSAAAAAE8_cRqnrnCRG9005qzgX62jJ1zS";
$privatekey = "6LdyL9cSAAAAAI4scv58ntnHKu1uefe8cgR9QsZf";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$recaptcha_error = null;

$recaptcha_success=false;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
                $recaptcha_success=true;
        } else {
                # set the error code so that we can display it
                $recaptcha_error = $resp->error;
        }

}

$mnth_lookup['Jan']=1;
$mnth_lookup['Feb']=2;
$mnth_lookup['Mar']=3;
$mnth_lookup['Apr']=4;
$mnth_lookup['May']=5;
$mnth_lookup['Jun']=6;
$mnth_lookup['Jul']=7;
$mnth_lookup['Aug']=8;
$mnth_lookup['Sep']=9;
$mnth_lookup['Oct']=10;
$mnth_lookup['Nov']=11;
$mnth_lookup['Dec']=12;

include("connect.php");
$sql="SELECT * FROM players";
$result=mysql_query($sql,$link) or die("functions:5: ".mysql_error());

while ($line = mysql_fetch_array($result)) {
	if($line['disclaimer']<date("Y-m-d H:i:s",mktime(date("H"),date("i"),0,date("m"),date("d"),date("Y")-1))) { $line['disclaimer']=""; }
	$users[$line['id']]=$line;
	$usernames[$line['email']]=$line;
	if(!is_null($line['team'])) {
	    $teams[$line['team']][]=$line;
	}
}

$ingame=false;
$repoll=false;

    if ((member_type()=='umpire') && ($_POST['action']=='team_update')) {
        print "<p>Updating teams...\n<ul>\n";
        foreach($_POST as $k => $v) {
            list($kk,$kid)=explode("_",$k);
            $uid=intval($kid);
            if($kk=='team' && $users[$uid]['team']!=$v) {
                print "<li>Updating ".$users[$uid]['realname']." (AKA ".$users[$uid]['alias'].") from ".$users[$uid]['team']." to $v...\n";
                include("src/connect.php");
                $sql="update players set team='$v' where id=".$uid;
                $result=mysql_query($sql,$link) or die("team_update:1: ".mysql_error());
                $sql="update players set target='".$teams[$v][0]['target']."' where id=".$uid;
                $result=mysql_query($sql,$link) or die("team_update:2: ".mysql_error());
                $repoll=true;
            }
            if($kk=='targets' && $users[$uid]['target']!=$v) {
                print "<li>Updating targets for team ".$users[$uid]['team']." from ".$users[$uid]['target']." to $v...\n";
                include("src/connect.php");
                $sql="update players set target='$v' where team='".$users[$uid]['team']."'";
                $result=mysql_query($sql,$link) or die("team_update:1: ".mysql_error());
                $repoll=true;
            }
        }
        print "</ul>\n";
    }

    if (!$ingame && (member_type()=='nc') && ($_POST['action']=='gameregister')) {
        print "<p>Registering you for the game...\n";
        $uid=$_SESSION['uid'];
        include("src/connect.php");
        $sql="update players set team='NOTEAM' where id=".$uid;
        $result=mysql_query($sql,$link) or die("gameregister:1: ".mysql_error());
        $repoll=true;
    }

    if($repoll) {
        include("src/connect.php");
        $sql="SELECT * FROM players";
        $result=mysql_query($sql,$link) or die("repoll:1: ".mysql_error());

        unset ($users,$usernames,$teams);
        while ($line = mysql_fetch_array($result)) {
            $users[$line['id']]=$line;
            $usernames[$line['email']]=$line;
            if(!is_null($line['team'])) {
                $teams[$line['team']][]=$line;
            }
        }
    }

function selector($name,$valarray,$cur) {
	print '<select name="'.$name.'">';
	print '<option value=""></option>';
	foreach($valarray as $val => $text) {
	    print '<option value="'.$val.'"';
	    if ($val == $cur) { print " SELECTED"; }
	    print '>'.$text.'</option>';
	}
	print '</select>';
}

function dropdown_date($name,$date) {
	global $mnth_lookup;
	if (!isset($date)) { $date=date('Y-m-d H:i:s'); }
	list($y,$m,$d,$h,$i,$s)=preg_split('/[ :-]/',$date);
	$i=intval($i/15)*15;
	if($i==0) {$i='00';}

	selector($name.'_day',array(1 => 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31),intval($d));
	selector($name.'_month',array_flip($mnth_lookup),intval($m));
	print '<input type="text" name="'.$name.'_year" value="'.$y.'" size=4>';
	selector($name.'_hour',array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23),intval($h));
	selector($name.'_minute',array('00' => '00','15' => '15','30' => '30', '45' => '45'),$i);
}

function get_realname($uid) {
    global $users;
    if ($uid=="") {
	return "Unknown";
    } else {
	if(is_null($users[$uid]['realname'])) {
	    return $users[$uid]['username'];
	} else {
	    return $users[$uid]['realname'];
	}	    
    }
}

function is_member() {
    return is_logged_in();
}

function member_type() {
    global $users;
    if(is_logged_in()) {
	if($users[$_SESSION['uid']]['disclaimer']=="") {
	    return 'unregistered';
	} elseif ($users[$_SESSION['uid']]['team']=="") {
	    return 'nc';
	} elseif ($users[$_SESSION['uid']]['team']=="NOTEAM") {
	    return 'noteam';
	} elseif ($users[$_SESSION['uid']]['team']=="Umpire") {
	    return 'umpire';
	} else {
	    return 'playing';
	}
    } else {
	return 'nobody';
    }
}

function is_admin() {
    global $users,$aclev,$user_level;
    return (is_logged_in() && ($aclev==$user_level['Administrator']));
}

function is_committee() {
    global $users,$aclev,$user_level;
    return (is_logged_in() && (is_admin() || ($aclev==$user_level['&Uuml;berOULE'])));
}

function is_logged_in() {
    return ($_SESSION['uid']>0);
}

function get_college($uid,$year,$term) {
    global $users;
    if($term=="Hilary" || $term=="Trinity") { $year--;}
    $colleges=explode('/',stripcslashes($users[$uid]['college']));
    sort($colleges);

    $rv='';
    foreach($colleges as $college) {
	list($when,$coll)=explode(':',$college);
	if($when!="" && $when<=$year) {$rv=$coll;}
    }
    return $rv;
}

function print_name_now($name) {
    $entry['name']=$name;
    $entry['year']=date('Y');
    $entry['term']="Trinity";
    if (date('n')>9) {$entry['term']="Michaelmas"; }
    if (date('n')<4) {$entry['term']="Hilary"; }
    print_name($entry);
}

function print_name($entry) {
    global $users;
    if(substr($entry['name'],0,1)=="=") {
	$uid=intval(str_replace('=','',$entry['name']));
	if($users[$uid]['privacy']>=4 || (is_logged_in() && $users[$uid]['privacy']>=3)) {
	    $rv="<a href=\"me.php?id=$uid\" class=\"hidden\">".get_realname($uid)."</a>";
	} else {
	    $rv=get_realname($uid);
	}
	$college=get_college($uid,$entry['year'],$entry['term']);
        if($college!='') { $rv.=' <span class="college">('.$college.')</span>'; }
    } else {
	$rv=$entry['name'];
        if($entry['college']!='') { $rv.=' <span class="college">('.$entry['college'].')</span>'; }
    }
    print $rv;
}

?>
