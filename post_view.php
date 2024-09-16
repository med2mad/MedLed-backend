<?php include ("partials/header.php");
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("Activate your account !");
    }
?>

<h1>Post</h1>

<?php
    include ("partials/conn.php");
    $d=mysqli_query ($c, "select * from posts where id='".$_GET["id"]."'" ) ;
    if(mysqli_num_rows($d)==1)
    {
        $r= mysqli_fetch_array ($d);
        
        if($_SESSION["id"]!=$r["users_id_w"] && $_SESSION["id"]!=$r["users_id_r"])
        {exit("404");}
        
        $name_w = htmlspecialchars($r["users_name_w"]);
        $name_r = htmlspecialchars($r["users_name_r"]);
        $message = nl2br(htmlspecialchars($r["message"]));
    ?>
        <h2 class="bg-white fst-italic mt-4 rounded-pill"> <?= date("d/m/Y" , strtotime($r["time"])) ?> - <?= date("H:i" , strtotime($r["time"]))?></h2>
        
        <div class="row">
            <div style="background-color:rgb(2, 51, 48);text-align:center; border:solid white" class="rounded col m-1">
                <p style="font-weight:bold; font-size:2em; font-style:italic; color:cornflowerblue; border-bottom:solid">FROM</p>
                <p>
                    <a href="uploads/profiles/<?= $r["users_img_w"] ?>">
                        <img src="uploads/profiles/<?= $r["users_img_w"] ?>" width="100" height="100" style="border:solid" class="rounded" alt="<?= $name_w ?>">
                    </a>
                </p>
                <p style="overflow:auto; font-size:1.2em;"><?= $name_w ?></p>
            </div>
        
            <div style="background-color:rgb(74, 88, 117); text-align:center; border:solid white" class="rounded col m-1">
                <p style=" font-weight:bold; font-size:2em;font-style:italic; color:cornflowerblue; border-bottom:solid ">TO</p>
                <p>
                    <a href="uploads/profiles/<?= $r["users_img_r"] ?>">
                        <img src="uploads/profiles/<?= $r["users_img_r"] ?>" width="100" height="100" style="border:solid" class="rounded" alt="<?= $name_r ?>">
                    </a>
                </p>
                <p style="overflow:auto; font-size:1.2em;"><?= $name_r ?></p>
            </div>
        </div>

        <div style="background-color:rgb(54, 54, 54); padding:10px;  font-size:1em; border:solid white" class="rounded my-2">
            <p style="font-weight:bold; font-size:2em; font-style:italic; color:cornflowerblue; text-align:center; border-bottom:solid">BODY</p>
            <textarea name="message" id="message" maxlength="500" cols="30" rows="6" class="form-control"><?= $message ?></textarea>
        </div>

        <?php if($r["file"]!=""){ ?>
            <div style="background-color:rgb(0, 0, 54); padding:10px;  font-size:1em; border:solid white" class="rounded my-2">
                <p style="font-weight:bold; font-size:2em; font-style:italic; color:cornflowerblue; text-align:center; border-bottom:solid">FILE RECEIVED</p>
                <div style="text-align:center;">
                    <p><a href="uploads/posts/<?= $r["file"] ?>" class="text-primary"><img src="files/file.png" width="50" height="50" alt="file"><br><?= $r["file"] ?></a></p>
                </div>
            </div>
        <?php } ?>

        <div style="margin:15px; padding:15px;">
            <p style="text-align:center;">
                <?php if($r["users_id_w"]!=$_SESSION["id"]){ ?>
                    <a class="btn btn-primary" href="create_post.php?id=<?=$r["users_id_w"]?>&name=<?= urlencode($r["users_name_w"]) ?>&mail=<?= urlencode($r["users_mail_w"]) ?>&img=<?=$r["users_img_w"]?>">Respond</a> | 
                <?php } ?>
                <a class="btn btn-danger" href="php.php?deletemessage=<?= $r["id"] ?>" onclick="return confirm('Delete this post ?');">Delete</a>
            </p>
        </div>
    <?php } ?>

    <script src="tinymce/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#message',
            menubar: false,
            toolbar: false,
            readonly: true,
            content_style: 'body{line-height:1rem;} p{margin:1px;}',

        });
    </script>

<?php include ("partials/footer.html"); ?>