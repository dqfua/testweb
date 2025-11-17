<?php
$user = array( array(5, "user1") , array(10, "user2") );
echo "<script>var userj = " . json_encode( $user ) . ";</script>";
?>

<script>
    document.writeln(userj.length);
    for(var i = 0 ; i < userj.length ; ++i)
    {
        var userInfo = userj[i];
        document.writeln(userInfo[0] + " : " + userInfo[1]);
    }
</script>
