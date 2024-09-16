<div class="text-center">

<?php if(isset($_SESSION["auth"]) && $_SESSION["auth"]=="true") { ?>
    <p>
        <a href="edit.php">
            <img src="uploads/profiles/<?= $_SESSION["img"] ?>" alt="" width="100" height="100" class="rounded-circle" id="profile">
        </a>
    </p>

    <p class="mb-0">Name :</p>
    <p style="overflow:auto;">
        <a class="text-primary" href="edit.php">
            <?= htmlspecialchars($_SESSION["name"]) ?>
        </a>
    </p>

    <p class="mb-0">E-mail :</p>
    <p style="overflow:auto;">
        <a class="text-primary" href="edit.php">
            <?= $_SESSION["mail"] ?>
        </a>
    </p>

    <hr>

    <?php if($_SESSION["verified"]==0) { ?>
        <p><a class="text-primary" href="signup1.php">
            Activate your account !
        </a></p>
    <?php }else{ ?>
        <p style="position:relative;"><?php if($_SESSION["notif"]>0) { ?>
            <span class="notif"><img src="files/bell-svgrepo-com.svg" width="25" alt="Nouveau Message"></span>
            <span class="notifnum"><?= $_SESSION["notif"] ?></span>
        <?php } ?>
        <a href="posts.php" class="text-primary">
            Posts 
        </a></p>
        <p><a class="text-primary" href="users.php?title=Friends">
            Friends
        </a></p>
        <p><a class="text-primary" href="users.php?title=Users&name=">
            Look for Users
        </a></p>
    <?php } ?>
    
    <hr>

    <p><a class="text-primary" href="edit.php">Edit Profile</a></p>
    <p><a class="text-danger" href="php.php?logout=1">Logout</a></p>
    
<?php }else{ ?>

    <p class="border-bottom pb-1 fs-2">
        Login
    </p>

    <img src="uploads/profiles/136.jpg" width="100px" height="100px" alt="" class="rounded-circle">
    <?php if(isset($_GET["errorlogin"])){ ?> <p style="color:red;margin:0px;padding:0px;"><?= $_GET["errorlogin"] ?></p> <?php } ?>
    <form method="post" action="php.php">
        <label for="profilname">Name :</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input id="profilname" name="profilname" type="text" class="form-control">
        </div> 

        <label for="profilmail">E-mail :</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input id="profilmail" name="profilmail" type="email" class="form-control">
        </div>
        
        <p>
            <label for="profilpass">Password :</label> 
            <input id="profilpass" name="profilpass" type="password" class="form-control">
        </p> 

        <input type="submit" name="login" value="Login" class="btn btn-info"><br><br>
    </form>
<!-- 
    <hr>

    <p><a href="resetpassword0.php" style="font-style:italic" class="text-primary">Forgot password ?</a></p> -->

    <p><a href="create_user.php" style="font-style:italic" class="text-primary">SignUp</a></p>

<?php } ?>

</div>