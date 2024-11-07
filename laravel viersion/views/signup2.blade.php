<?php
if (session_id()=="") session_start();

if(isset($_GET["token"]))
{
    include ("conn.blade.php");
    if(!$c){
        echo mysqli_connect_error();
    }
    else{
        $query = "UPDATE users SET verified = 1 , token = 0 WHERE mail = '" . $_GET["mail"] . "' AND token = '" . $_GET["token"] . "'";
        mysqli_query($c, $query);
        if(mysqli_affected_rows($c)>0)
        {$_SESSION["verified"]=1;}
    }
    mysqli_close($c);
}
?>

@include( 'partials.header' )

<?php

if($_SESSION["verified"]==1 && isset($_GET["token"])){ ?>
    <h1>Welcome</h1>

    <div class="alert alert-success" style="text-align:center;">
        <p style="font-size:2em; font-style:italic;">Account Activated !</p>
    </div>
<?php }else{
    exit("404");
}?>

@include( 'partials.footer' )