<div class="text-center">

<?php if(isset($_SESSION["auth"]) && $_SESSION["auth"]=="true") { ?>
    <p>
        <a href="/page/edit">
            <img src="/uploads/profiles/<?= $_SESSION["photo"] ?>" alt="" width="100" height="100" class="rounded-circle" id="profile">
        </a>
    </p>

    <p class="mb-0">Name :</p>
    <p style="overflow:auto;">
        <a class="text-primary" href="/page/edit">
            <?= htmlspecialchars($_SESSION["name"]) ?>
        </a>
    </p>

    <p class="mb-0">E-mail :</p>
    <p style="overflow:auto;">
        <a class="text-primary" href="/page/edit">
            <?= $_SESSION["mail"] ?>
        </a>
    </p>

    <hr>

    <?php if($_SESSION["verified"]==0) { ?>
        <p><a class="text-primary" href="/page/signup1">
            Activate your account !
        </a></p>
    <?php }else{ ?>
        <p style="position:relative;"><?php if($_SESSION["notif"]>0) { ?>
            <span class="notif"><img src="/files/bell-svgrepo-com.svg" width="25" alt="Nouveau Message"></span>
            <span class="notifnum"><?= $_SESSION["notif"] ?></span>
        <?php } ?>
        <a href="/page/posts" class="text-primary">
            Posts 
        </a></p>
        <p><a href="/page/users?title=Friends" class="text-primary">
            Friends
        </a></p>
        <p><a href="/page/users?title=Users&name=" class="text-primary">
            Look for Users
        </a></p>
    <?php } ?>
    
    <hr>

    <p><a href="/page/edit" class="text-primary">Edit Profile</a></p>
    <p><a href="/logout" class="text-danger">Logout</a></p>
    
<?php }else{ ?>

    <p class="border-bottom pb-1 fs-2">
        Login
    </p>
    
    <img src="/uploads/profiles/136.jpg" width="100px" height="100px" alt="" class="rounded-circle">
    
    <form method="post" action="/login">
        @csrf
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

    <p><a href="/page/create_user" style="font-style:italic" class="text-primary">SignUp</a></p>

<?php } ?>

</div>