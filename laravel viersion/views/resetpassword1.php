@include( 'partials.header' )

<?php
if(isset($_POST["resetpass"]))
{
    include ("conn.blade.php");
    if(!$c){
        echo mysqli_connect_error();
    }
    else
    {
        $name = mysqli_real_escape_string ($c , trim($_POST["name"])) ;
        $mail = mysqli_real_escape_string ($c , trim($_POST["mail"])) ;

        $d = mysqli_query ($c, "select mail,pass from users where name='".$name."' and mail = '".$mail."' limit 1");
        if(mysqli_num_rows($d)==1)
        {
            $r = mysqli_fetch_array ($d);

            mysqli_close($c);
            
            include ("mail.php");
            send_mail($r["mail"], "Your MedLed Password", "Your MedLed Password is : ".$r["pass"]);
        }
        else
        {
            mysqli_close($c);
            header("Location: resetpassword0.php?error=Name or E-mail incorrect");
            exit;
        }
    }
}
?>

<h1>Retrieve Password</h1>

<div class="alert alert-primary" style="font-size:2em; font-style:italic;">
   <p>A message has been sent to : </p>
    <p style="overflow:auto; text-decoration:underline;">
        <?= $r["mail"] ?>
    </p>
    <p>Open this last message to recover your Password.</p>
</div>
<p>
    <a class="text-primary" style="font-size:1.5em; text-decoration:underline;font-style:italic" href="resetpassword0.php">Re-send message</a>
</p>

@include( 'partials.footer' )