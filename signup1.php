<?php include ("partials/header.php");
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404");
    }

    include "partials/mail.php";

    $headers = 'From: mohamed.leghdaich@gmail.com\r\n'.
    'Reply-To: mohamed.leghdaich@gmail.com\r\n'.
    'X-Mailer: PHP/' . phpversion();

    send_mail($_SESSION["mail"], "MedLed account activation", "Username:".$_SESSION["name"]." - Email: ".$_SESSION["mail"]." - Password:".$_SESSION["pass"]."<br><a href=\"localhost/medled/signup2.php?token=".$_SESSION["token"]."&mail=". $_SESSION["mail"] ."\">Click here to activate your MedLed account</a>", $headers);
    // send_mail($_SESSION["mail"], "MedLed account activation", "Username:".$_SESSION["name"]." - Email: ".$_SESSION["mail"]." - Password:".$_SESSION["pass"]."<br><a href=\"medtest.cf/signup2.php?token=".$_SESSION["token"]."&mail=". $_SESSION["mail"] ."\">Click here to activate your MedLed account</a>", $headers);
?>

<h1>Welcome</h1>

<div class="alert alert-primary" style="font-size:2em; font-style:italic;">
   <p>A verification email has been sent with a link to :</p>
    <p style="overflow:auto; text-decoration:underline;">
        <?= $_SESSION["mail"] ?>
    </p>
    <p>Click the link to confirm your email address and activate your account.</p>
</div>
<p>
    If you do not see the email, <a class="text-primary" style="font-size:1.2em; text-decoration:underline;font-style:italic" href="signup1.php">Click here to re-send verification message</a>.
</p>

<?php include ("partials/footer.html"); ?>