<?php include ("partials/header.php"); 
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("Activate your account !");
    }
    if(!isset($_GET["id_r"]) || !isset($_GET["name_r"])){
        exit("404 1");
    }
    if($_SESSION["blocked"]==1){ exit("404 2"); }
?>

<h1 style="overflow:auto;">Send a Message to <?= htmlspecialchars($_GET["name_r"]) ?></h1>

<form method="post" action="php.php" enctype="multipart/form-data">
    <input name="id_r" type="hidden" value="<?= $_GET["id_r"] ?>">
    <table>
        <tr><td class="rounded"><label for="message">Message</label></td><td><textarea name="message" id="message" maxlength="500" cols="30" rows="6" class="form-control"></textarea></td></tr>
        <tr>
            <td class="rounded"><label for="file">File<div style="font-size:0.6em; color:red;"><img id="infoimg" src="files/info.png" width="14" height="14" alt="">Size up to 40 MB</div></label></td>
            <td><input name="file" id="file" type="file" class="form-control"></td>
        </tr>
    </table>
    
    <br>
    <input type="submit" name="post" class="btn btn-primary btn-lg mb-1" value="OK">
</form>
    
<?php include ("partials/footer.html"); ?>