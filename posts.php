<?php
if (session_id()=="") session_start();
include ("partials/conn.php");
mysqli_query ($c, "update posts set red=1 where users_id_r='".$_SESSION["id"]."'" ) ; $_SESSION["notif"]=0;
mysqli_close($c);

include ("partials/header.php"); 
if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
    exit("Activate your account !");
}

$name = isset($_GET["name"]) ? trim($_GET["name"]):''; //if research happend
?>

<h1>Posts</h1>

<div style="display:flex; gap:16px;" class="mb-2">
    <form method="get">
        <div style="display:flex; gap:2px;">
        <div>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input id="name" name="name" type="text" class="form-control" value="<?=$name?>" placeholder="Name">
            </div> 
            <?php if(isset($_SESSION["type"]) && $_SESSION["type"]=="admin"){ ?>
                <input type="checkbox" name="include" id="include" <?php if(isset($_GET["include"])){ ?> checked <?php } ?>> <label for="include">All Users Posts</label>
            <?php } ?>
        </div>
        <div>
            <input type="submit" name="search" value="Search" class="btn btn-info" style="align-self:stretch;">
        </div>
        </div>
    </form>
    
    <div><a class="btn btn-secondary" href="posts.php?include=1" role="button">All</a></div>
</div>

<?php 
include ("partials/conn.php");
$q = "select count(id) from posts where (users_id_w='".$_SESSION["id"]."' or users_id_r='".$_SESSION["id"]."')";
if($name!='') $q .= " AND (users_name_w = '".$name."' or users_name_r='".$name."')";
$d=mysqli_query ($c, $q);
mysqli_close($c);
$perpage = 5;
$pagesnbr = ceil(mysqli_fetch_array($d)[0]/$perpage);

if(isset($_GET["page"]) && is_numeric($_GET["page"])){
    $currantpage = ceil($_GET["page"]);
    if($currantpage>0) {$debut = ($currantpage-1)*$perpage;}
    else {$debut = 0;}
}
else{
    $currantpage = 1;
    $debut = 0;
}
?>
<p style="text-align:center; background-color:rgb(60,60,60); border:solid 1px white; font-size:20px">
    <a class="text-primary" href="posts.php?page=1&name=<?= $name ?>"> &nbsp; << &nbsp; </a> <?php
    for ($i=1; $i<=$pagesnbr; $i++){ 
        if($currantpage!=$i){
        ?><a class="text-primary" href="posts.php?page=<?= $i ?>&name=<?= $name ?>"> &nbsp; <?= $i ?> &nbsp; </a>
        <?php }else{ ?>
        &nbsp; <?= $i ?> &nbsp; 
    <?php }}?> 
    <a class="text-primary" href="posts.php?page=<?= $pagesnbr ?>&name=<?= $name ?>"> &nbsp; >> &nbsp; </a>
</p>
<?php
    include ("partials/conn.php");
    $q = "select * from posts";
    if(isset($_GET["include"]) && isset($_SESSION["type"]) && $_SESSION["type"]=="admin") { $q .= " where 1"; } //if admin checks to include all
    else { $q .= " where (users_id_w='".$_SESSION["id"]."' or users_id_r='".$_SESSION["id"]."')"; }
    if($name!='') $q .= " AND (users_name_w = '".$name."' or users_name_r='".$name."')";
    $d=mysqli_query ($c, $q." ORDER BY id DESC limit $debut,$perpage");
    mysqli_close($c);
    if(mysqli_num_rows($d)==0){?>
    <p style="text-align:center;">No Posts !</p>
<?php }else{
    while($r= mysqli_fetch_array ($d))
    { 
    $name_w = htmlspecialchars($r["users_name_w"]);
    $name_r = htmlspecialchars($r["users_name_r"]);
    $message = nl2br(htmlspecialchars($r["message"]));
    ?>

    <p class="h5" style="margin:0; font-family:'Times New Roman', Times, serif; font-weight: normal;font-style: italic;" > <?= date("d/m/Y" , strtotime($r["time"])) ?> - <?= date("H:i" , strtotime($r["time"]))?></p>

    <table class="table table-bordered mb-5" cellspacing="0" style="font-size:1em;" >

        <tr>
            <th width="10" style="border-left: 10px solid white;">From</th>
            <th>Body</th>
            <th width="10" class="d-none d-md-table-cell">File</th>
            <th width="10">To</th>
        </tr>

        <tr>
            <td class="rounded" style="border-right:none; overflow:auto;">
                <a href="post_view.php?id=<?= $r["id"] ?>">
                    <img src="uploads/profiles/<?= $r["users_img_w"] ?>" width="100" height="100" style="border:solid;object-fit:contain; background-color:black" class="rounded" alt="<?= $name_w ?>">
                </a>
                <div style="width:100px;overflow:auto; margin:auto;line-height:15pt;"><a href="post_view.php?id=<?= $r["id"] ?>"><?= $name_w ?></a></div>
            </td>
            
            <td style="border-left:none; max-width:150px;overflow:auto; vertical-align: top;"><p style="max-height:50px; font-size:1.5em;"><a href="post_view.php?id=<?= $r["id"] ?>"><?= $message ?></a></p></td>
            
            <td class="d-none d-md-table-cell"> <?php if($r["file"]!=""){ ?> <a href="uploads/posts/<?= $r["file"] ?>"><img src="files/file.png" width="50" height="50" alt="file"></a> <?php } ?></td>
            
            <td class="bg-secondary rounded" >
                <a href="post_view.php?id=<?= $r["id"] ?>">
                    <img src="uploads/profiles/<?= $r["users_img_r"] ?>" width="100" height="100" style="border:solid;object-fit:contain; background-color:black" class="rounded" alt="<?= $name_r ?>">
                </a>
                <div style="width:100px;overflow:auto; margin:auto; line-height:15pt;"><a href="post_view.php?id=<?= $r["id"] ?>"><?= $name_r ?></a></div>
            </td>
        </tr>
        
        <tr> 
            <td colspan="4" style="border: solid 1px; border-left: 10px solid white;">
                <a class="btn btn-success" href="post_view.php?id=<?= $r["id"] ?>">View</a> | 
                <?php if($r["users_id_w"]!=$_SESSION["id"]){ 
                    $name_w = urlencode($r["users_name_w"]);?>
                    <a class="btn btn-primary" href="create_post.php?id_r=<?=$r["users_id_w"]?>&name_r=<?=$name_w?>">Respond</a> | 
                <?php } ?>
                <a class="btn btn-danger" href="php.php?deletemessage=<?= $r["id"] ?>&file=<?= $r["file"] ?>" onclick="return confirm('Delete this post ?');">Delete</a>
            </td>
        </tr>

        <tr style="background-color:black;border: solid black; border-top:solid white 1px">
            <td style="background-color:black; border-top:solid white 1px; border-left:none"></td>
            <td class="d-none d-md-table-cell"></td>
            <td class="d-none d-md-table-cell" style="background-color:black;"></td>
            <td style="background-color:black;"></td>
        </tr>

    </table>
<?php }
}

?>
<p style="text-align:center; background-color:rgb(60,60,60); border:solid 1px white; font-size:20px">
    <a class="text-primary" href="posts.php?page=1&name=<?= $name ?>"> &nbsp; << &nbsp; </a> <?php
    for ($i=1; $i<=$pagesnbr; $i++){ 
        if($currantpage!=$i){
        ?><a class="text-primary" href="posts.php?page=<?= $i ?>&name=<?= $name ?>"> &nbsp; <?= $i ?> &nbsp; </a>
        <?php }else{ ?>
        &nbsp; <?= $i ?> &nbsp; 
    <?php }}?> 
    <a class="text-primary" href="posts.php?page=<?= $pagesnbr ?>&name=<?= $name ?>"> &nbsp; >> &nbsp; </a>
</p>
<?php

include ("partials/footer.html"); ?>