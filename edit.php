<?php  include ("partials/header.php"); 
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404");
    }
?>

<h1>Edit Profile</h1>

<?php if(isset($_GET["error"])){ ?>
    <p class="alert alert-danger"><?= $_GET["error"] ?></p>
<?php } ?>

<form method="post" action="php.php" enctype="multipart/form-data">
    <input name="page" type="hidden" value="edit.php">
    <table>
        <?php
        $name = htmlspecialchars($_SESSION["name"]);
        $pass = htmlspecialchars($_SESSION["pass"]);
        ?>
        <tr><td class="rounded"><label for="name">Name</label></td><td style="width:500px;"><input name="name" id="name" type="text" value="<?= $name ?>" required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded"><label for="mail">E-mail</label></td><td style="width:500px;"><input name="mail" id="mail" type="email" value="<?= $_SESSION['mail'] ?>" required class="form-control"></td></tr>
        <tr><td class="rounded"><label for="pass">Password</label></td><td style="width:500px;"><input name="pass" id="pass" value="<?= $pass ?>" type="password" required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded" style="font-size:0.8em;padding:0"><label for="pass2">Confirm<br>Password</label></td><td><input name="pass2" id="pass2" value="<?= $pass ?>" type="password" required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr>
            <td class="rounded"><label for="img">Photo</label></td>
            <td style="text-align:center">
                <input type="button" value="Browse..." onclick="document.getElementById('img').click();" />
                <input name="img" id="img" type="file" accept=".jpg,.jpeg,.png,.bmp,.gif" class="form-control" style="display:none;"><label for="img"><img id="imgfile" width="100" height="100" style="margin-top:10px; object-fit:contain" src="<?= 'uploads/profiles/' . $_SESSION["img"] ?>" alt="<?= $name ?>"></label>
            </td>
        </tr>
    </table>
    <br>
    <input type="submit" name="update" class="btn btn-primary btn-lg mb-1" value="OK">
</form>
<script type="text/javascript">
    document.getElementById("img").onchange=function(){
        document.getElementById("imgfile").setAttribute('src',URL.createObjectURL(img.files[0]));
    }
</script>

<?php include ("partials/footer.html"); ?>