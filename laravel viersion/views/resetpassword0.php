@include( 'partials.header' )

<h1>Retrieve Password</h1>

<?php if(isset($_GET["error"])){ ?>
    <p class="alert alert-danger"><?= $_GET["error"] ?></p>
<?php } ?>

<form method="post" action="resetpassword1.php">
    <table>
        <tr><td class="rounded"><label for="name">Name</label></td><td><input name="name" id="name" type="text" required   minlength="4" class="form-control"></td></tr>
        <tr><td class="rounded"><label for="mail">E-mail</label></td><td><input name="mail" id="mail" type="email" required class="form-control"></td></tr>
    </table>
    <br>
    <input type="submit" name="resetpass" class="btn btn-primary btn-lg mb-1" value="OK">
</form>

@include( 'partials.footer' )