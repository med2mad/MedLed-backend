<!-- send this file to the internet doubles the text on it -->

<?php if (session_id()=="") session_start(); ?>

<?php
    $stylechoice="styleBlack.css";

    if(isset($_GET["cssstyle"])){
        $stylechoice=$_GET["cssstyle"];
        if($stylechoice=="styleBlack.css" || $stylechoice=="stylePink.css" || $stylechoice=="styleGreen.css" || $stylechoice=="styleBlue.css"){
            setcookie("sitestyle", $stylechoice);
            header("Location: index.php");
            exit();
        }
    }
    else{
        if(isset($_COOKIE["sitestyle"])) {$stylechoice=$_COOKIE["sitestyle"];}
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <title>MedLed social</title>
        <link rel="stylesheet" href="files/bootstrap/css/bootstrap.min.css">
        <!-- for icons (<i>) --><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        
        <link rel="stylesheet" href="style/style.css">
        <?php
            if($stylechoice=="stylePink.css" || $stylechoice=="styleBlue.css" || $stylechoice=="styleGreen.css"){
                ?><link rel="stylesheet" href="style/stylePkBeGn.css"><?php
            }
        ?>
        <link rel="stylesheet" href="style/<?php echo $stylechoice; ?>">

        <style>
            .notif{
                background-color:gray;
                border-radius:50%;
                padding:6px;
            }
            .notifnum{
                background-color: red;
                width:25px;
                height:25px;
                border-radius:50%;
                position:absolute;
                left:40px;
                top:-10px;
            }
            .postsTinyMCE{padding:0 !important;}
            .postsTinyMCE .tox-tinymce{height:200px !important; border-radius:0;}

            #content table td:last-child{
                font-weight:lighter !important;
                font-size:1rem !important;
            }
        </style>

    </head>
    <body class="container-fluid">
        
        <a href="index.php">
            <div id="cover" class="row rounded bgdeco">
                <img src="files/medled2.png" alt="">
            </div>
        </a>

        <nav id="nav" style="display:flex; justify-content: space-evenly;">
            <div><a href="index.php" class="text-primary">Home</a></div>
            <div><a href="contacts.php" class="text-primary">Contacts</a></div>
            <?php if(isset($_SESSION["auth"]) && $_SESSION["auth"]=="true" && isset($_SESSION["verified"]) && $_SESSION["verified"]==1){ ?>
            <div><a href="gallery.php?user=<?= $_SESSION["id"] ?>" class="text-primary">Gallery</a></div>
            <?php } ?>
            <div><a href="login_mobile.php" class="text-primary">Login/Profile</a></div>
            <div><a href="create_user.php" class="text-primary">SignUp</a></div>
        </nav>

        <div class="row" id="side&content">
            
            <div id="side" class="col-md-2 rounded d-md-block d-none me-1 bgdeco">
                <?php include ("partials/profil.php");?>
			</div>
            
            <div id="content" class="col rounded bgdeco">