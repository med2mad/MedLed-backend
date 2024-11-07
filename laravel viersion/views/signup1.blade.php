@include( 'partials.header' )

<?php
    if (session_id()=="") session_start();

    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404");
    }

    include ("mail.php");
    $headers = 'From: mohamed.leghdaich@gmail.com\r\n'.
    'Reply-To: mohamed.leghdaich@gmail.com\r\n'.
    'X-Mailer: PHP/' . phpversion();

    send_mail($_SESSION["mail"], "MedLed social account activation", "Username:".$_SESSION["name"]." - Password:".$_SESSION["pass"]." - Email: ".$_SESSION["mail"]."<br><a href=\"http://localhost:8000/page/signup2?token=".$_SESSION["token"]."&mail=". $_SESSION["mail"] ."\">Click here to activate your MedLed social account</a>", $headers);
?>

<h1>Welcome</h1>

<div class="alert alert-primary" style="font-size:2em; font-style:italic;">
   <p>A verification email holding your credentials has been sent with a link to :</p>
    <p style="overflow:auto; text-decoration:underline;">
        "<?= $_SESSION["mail"] ?>"
    </p>
    <p>Click the link to confirm your email address and activate your account.</p>
</div>
<p>
    If you do not see the email, <a class="text-primary" style="font-size:1.2em; text-decoration:underline;font-style:italic" href="/page/signup1">Click here to re-send verification message</a>.
</p>

@include( 'partials.footer' )