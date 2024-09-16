<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'files/PHPMailer-master/src/Exception.php';
  require 'files/PHPMailer-master/src/PHPMailer.php';
  require 'files/PHPMailer-master/src/SMTP.php';

function send_mail($recipient,$subject,$message)
{

  $mail = new PHPMailer();
  $mail->IsSMTP();

  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 587;
  $mail->Host       = "smtp.gmail.com";
  //$mail->Host       = "smtp.mail.yahoo.com";
  $mail->Username   = "mohamed.leghdaich@gmail.com";
  $mail->Password   = "okmmtxxaflfsfcra"; //got from "Manage your Google Account" > Security > Signing in to Google > App Password

  $mail->IsHTML(true);
  $mail->AddAddress($recipient, "recipient-name");
  $mail->SetFrom("your-email@gmail.com", "MedLed"); //sender name
  //$mail->AddReplyTo("reply-to-email", "reply-to-name");
  //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = $subject;
  $content = $message;

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    //echo "Error while sending Email.";
    //var_dump($mail);
    return false;
  } else {
    //echo "Email sent successfully";
    return true;
  }

}

?>