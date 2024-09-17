<?php  include ("partials/header.php"); ?>

<h1>SignUp</h1>

<?php if(isset($_GET["error"])){ ?>
    <p class="alert alert-danger"><?= $_GET["error"] ?></p>
<?php } ?>

<form method="post" action="php.php" enctype="multipart/form-data">
    <input name="page" type="hidden" value="create_user.php">
    <table>
        <!-- <tr><td class="rounded"><label for="name">Nom</label></td><td><input name="name" id="name" type="text" required minlength="5" maxlength="20" pattern="[a-zA-Z][a-zA-Z0-9.\-_]+" title="entrez uniquement des lettres/nombres/points(.)/tirets(-_) Et le nom commence par une lettre" class="form-control"><div style="font-size:0.6em; color:red;"><img id="infoimg" src="files/info.png" width="14" height="14" alt=""> lettres/nombres/./-/_ Uniquement, Et ça commence par une lettre</div></td></tr> -->
        <tr><td class="rounded"><label for="name">Name</label></td><td style="width:500px;"><input name="name" id="name" type="text" required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded"><label for="mail">E-mail</label></td><td style="width:500px;"><input name="mail" id="mail" type="email" required class="form-control"></td></tr>
        <tr><td class="rounded"><label for="pass">Password</label></td><td style="width:500px;"><input name="pass" id="pass" type="password" required required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded" style="font-size:0.8em;padding:0"><label for="pass2">Confirm<br>Password</label></td><td style="width:500px;"><input name="pass2" id="pass2" type="password" required required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr>
            <td class="rounded"><label for="img">Photo</label></td>
            <td style="text-align:center">
                <input type="button" value="Browse..." onclick="document.getElementById('img').click();" />
                <input name="img" id="img" type="file" accept=".jpg,.jpeg,.png,.bmp,.gif" class="form-control" style="display:none;"><label for="img"><img id="imgfile" width="100" height="100" style="margin-top:10px" src="uploads/profiles/136.jpg" alt="photo profile"></label>
            </td>
        </tr>
    </table>
    <div style="margin-top:4px;">
        <input type="checkbox" name="conditions" required> <label> Accept <a href="conditions.php" style="text-decoration:underline;">Terms &amp; Conditions</a></label>
    </div>
    <br>
    <input type="submit" name="signup" class="btn btn-primary btn-lg mb-1" value="OK">
</form>
<script type="text/javascript">
    document.getElementById("img").onchange=function(){
        document.getElementById("imgfile").setAttribute("src",URL.createObjectURL(img.files[0]));
    }
</script>

<?php include ("partials/footer.html"); ?>