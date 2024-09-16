<?php include ("partials/header.php");
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("Activate your account !");
    }

    $name = isset($_GET["name"]) ? trim($_GET["name"]):''; //if research happend
?>

<h1><?= $_GET["title"] ?></h1>

<div style="display:flex; gap:16px;" class="mb-2">
    <form method="get">
        <input name="title" type="hidden" value="<?= $_GET["title"] ?>">
        <div style="display:flex; gap:2px;">
        <div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="name" name="name" value="<?= $name ?>" type="text" class="form-control" placeholder="Name">
            </div> 
            <?php if($_GET["title"]=="Users") { ?>
                <input type="checkbox" name="include" id="include" <?php if(isset($_GET["include"])){ ?> checked <?php } ?>> <label for="include">Include Friends</label>
            <?php } ?>
        </div>
        <div>
            <input type="submit" name="search" value="Search" class="btn btn-info" style="align-self:stretch;">
        </div>
        </div>
    </form>
    
    <div><a class="btn btn-secondary" href="users.php?title=<?= $_GET["title"] ?>&include=1" role="button">All</a></div>
</div>

<?php include ("partials/conn.php");
    $q = "select id,name,img,mail,friends,blocked,type from users where id<>'".$_SESSION["id"]."'";
    $q .= " AND name like '%".$name."%'";
    $d1 = mysqli_query ($c, $q." ORDER BY id DESC");

    $d2=mysqli_query ($c, "select friends from users WHERE id='".$_SESSION["id"]."'");
    $data= mysqli_fetch_array($d2);
    $MyfriendsArray = json_decode($data["friends"], true);

    mysqli_close($c);

    while($r= mysqli_fetch_array ($d1))
    {
        $notShow = $_GET["title"]=="Friends" && !isset($MyfriendsArray[$r["id"]]); //in Friends page if not in friends list
        $notShow = $notShow || $_GET["title"]=="Users" && isset($MyfriendsArray[$r["id"]]) && !isset($_GET["include"]); //in Users page if in friends list but include is not checked
        $notShow = $notShow || $r["blocked"]==1 && isset($_SESSION["type"]) && $_SESSION["type"]=="user"; //in users/friends pages if it's blocked and i'm not admin 
        if($notShow) continue;

        $name = htmlspecialchars($r["name"]);
        $mail = htmlspecialchars($r["mail"]);
        $colorRed = $_GET["title"]=="Friends" && isset($MyfriendsArray) && $MyfriendsArray[$r["id"]]==1 || $r["blocked"]==1 ? 'bg-danger bg-gradient' : '';
        $username = urlencode($r["name"]);
        $imAdmin = isset($_SESSION["type"]) && $_SESSION["type"]=="admin";
        $user_friends = json_decode($r["friends"], true);
            $is_friend_not_blocking_me = isset($user_friends[$_SESSION["id"]]) ? $user_friends[$_SESSION["id"]]==0:true;
?>
        <div class="row mb-2 ms-1" style="border:solid 1px; border-left:10px solid white; text-align:center" >

            <div class="col-2" style="background-color:cadetblue; width:120px;">
                <a href="<?= "uploads/profiles/" . $r["img"] ?>">
                    <img class="rounded my-2" width="100" height="100" style="border:solid;object-fit:contain; background-color:black"src="<?= "uploads/profiles/" . $r["img"] ?>" alt="<?= $name ?>">
                </a>
            </div>

            <div class="col">
                <div class="py-1 <?= $colorRed ?>" style="font-size:20px; --bs-bg-opacity: .5;"><div style="font-weight:bold;"><?= $name.($r['type']=='admin'?' (Admin)':'') ?></div> <div class="text-info" style="overflow:auto;"><?= $mail ?></div> </div>

                <div style="border-top: solid 1px white;" class="py-1">

                    <?php if(($_GET["title"]=="Friends") || $imAdmin) { ?>
                        <?php if(($_SESSION["blocked"]==0 && $is_friend_not_blocking_me) || $imAdmin) { ?>
                            <a class="btn btn-primary" href="create_post.php?id_r=<?=$r["id"]?>&name_r=<?=$username?>">Post</a> | 
                        <?php }else{ ?>
                            <a class="btn btn-secondary disabled">Post</a> | 
                        <?php } ?>
                    <?php } ?>

                    <?php if($_GET["title"]=="Friends" || $imAdmin) { ?>
                        <a class="btn btn-success" href="posts.php?name=<?=$username?>">All Posts</a> | 
                    <?php } ?>
                    
                    <?php if(($_GET["title"]=="Friends") || $imAdmin) { ?>
                        <?php if(($_SESSION["blocked"]==0 && $is_friend_not_blocking_me) || $imAdmin) { ?>
                            <a class="btn btn-warning" href="gallery.php?user=<?= $r["id"] ?>">Gallery</a> | 
                        <?php }else{ ?>
                            <a class="btn btn-secondary disabled">Gallery</a> | 
                        <?php } ?>
                    <?php } ?>

                    <?php if($_GET["title"]=="Users" && $imAdmin) { ?>
                        <?php if($r["blocked"]==1) { ?>
                            <a class="btn" style="background-color:purple; color:white;" href="php.php?unblockuser=<?= $r["id"] ?>">UnBlock</a> | 
                        <?php }else{ ?>
                            <a class="btn" style="background-color:purple; color:white;" href="php.php?blockuser=<?= $r["id"] ?>">Block</a> | 
                        <?php } ?>
                    <?php } else if($_GET["title"]=="Friends") { ?>
                        <?php if(isset($MyfriendsArray) && $MyfriendsArray[$r["id"]]==1) { ?>
                            <a class="btn" style="background-color:purple; color:white;" href="php.php?unblockfriend=<?= $r["id"] ?>">UnBlock</a> | 
                        <?php }else{ ?>
                            <a class="btn" style="background-color:purple; color:white;" href="php.php?blockfriend=<?= $r["id"] ?>">Block</a> | 
                        <?php } ?>
                    <?php } ?>

                    <?php if(isset($MyfriendsArray[$r["id"]])) { ?>
                        <a class="btn btn-danger" href="php.php?title=<?= $_GET["title"] ?>&unfriend=<?= $r["id"] ?>&img=<?= $r["img"] ?>"  onclick="return confirm('Remove from Friends list ?');">Unfriend</a>
                    <?php }else{ ?>
                        <a class="btn btn-success" href="php.php?befriend=<?= $r["id"] ?>">Befriend</a>
                    <?php } ?>

                </div>
            </div>
            
        </div>
<?php } ?>


<?php include ("partials/footer.html"); ?>