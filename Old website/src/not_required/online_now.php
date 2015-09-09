        <tr><td class="login_head">Online now</td></tr>
<?php
    include("connect.php");
    $sql="select users.id,users.level from sessions left join users on sessions.uid=users.id where level>".$user_level['Guest']." order by level desc";
    $result=mysql_query($sql,$link) or die("login:20: ".mysql_error());
    $num_mem=mysql_num_rows($result);
    $sql="select users.realname from sessions left join users on sessions.uid=users.id where level=".$user_level['Guest'];
    $guests=mysql_query($sql,$link) or die("login:20: ".mysql_error());
    $num_guests=mysql_num_rows($guests);

    if($num_mem>0) {
    	$oldlevel=-1;
	$entry['year']=date('Y');
	$entry['term']="Trinity";
	if (date('n')>9) {$entry['term']="Michaelmas"; }
	if (date('n')<4) {$entry['term']="Hilary"; }

    	while ($line=mysql_fetch_array($result)) {
	    $entry['name']="=".$line['id'];
            if($oldlevel==$line['level']) {
            	print "<br>\n";
		print_name($entry);
            } else {
            	if($oldlevel!=-1) { print "</td></tr>\n"; }
            	print '<tr><td class="login_head">'.$user_class[$line['level']].'s</td></tr>';
            	print '<tr><td class="login">';
		print_name($entry);
            }
            $oldlevel=$line['level'];
    	}
    	print "</td></tr>\n";
    }
    print '<tr><td class="login">';
    if($num_mem>0) {print "...and "; }
    print "$num_guests guest";
    if($num_guests!=1) { print "s"; }
    print ".</td></tr>\n";
?>
