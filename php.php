<?php
if (session_id()=="") session_start();

if(isset($_POST["signup"]) || isset($_POST["update"])) {

    if($_POST["page"] != "create_user.php" && $_POST["page"] != "edit.php"){
        exit("404 1");
    }

    //name starts with a letter and has only english letters(no spaces) / digits / points(.) / underscores(_) / hifens(-)
    // if (!preg_match('/^[a-z][a-z0-9.\-_]+$/i', $_POST["name"])) {
    //     exit("nom invalid!");
    // }

    $name = trim($_POST["name"]);
    $mail = trim($_POST["mail"]);

    if(empty($name) || empty($mail) || empty($_POST["pass"]) || empty($_POST["pass2"])){
        exit("404 empty !");
    }
    elseif (mb_strlen($name,"UTF-8") < 5 || mb_strlen($name,"UTF-8") > 20) {
        exit("404 name 5 to 20");
    }
    elseif (mb_strlen($_POST["pass"],"UTF-8") < 5 || mb_strlen($_POST["pass"],"UTF-8") > 20) {
        exit("404 pass 5 to 20");
    }
    elseif (mb_strlen($_POST["pass2"],"UTF-8") < 5 || mb_strlen($_POST["pass2"],"UTF-8") > 20) {
        exit("404 pass2 5 to 20");
    }
    elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        exit("404 Email not valid!");
    }
    elseif($_POST["pass"] !== $_POST["pass2"]){
        header("Location: ".$_POST["page"]."?error=Password and Confirmation not identical");
        exit;
    }
    elseif(!$_POST["conditions"] && isset($_POST["signup"])){
        header("Location: ".$_POST["page"]."?error=Accept conditions");
        exit;
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        $name = mysqli_real_escape_string ($c , $name) ;
        $mail = mysqli_real_escape_string ($c , $mail) ;
        $pass = mysqli_real_escape_string ($c , $_POST["pass"] ) ;

        if(trim($_POST["mail"]) != $_SESSION["mail"]){
            $d = mysqli_query ($c, "select id from users where mail = '".$mail."' limit 1");
            if(mysqli_num_rows($d)>0)
            {
                header("Location: ".$_POST["page"]."?error=Email already used");
                exit;
            }
            $_SESSION["verified"]=0;
        }
        if(trim($_POST["name"]) != $_SESSION["name"]){
            $d = mysqli_query ($c, "select id from users where name = '".$name."' limit 1");
            if(mysqli_num_rows($d)>0) {
                header("Location: ".$_POST["page"]."?error=Already existing name");
                exit;
            }
        }

        $token = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $newimgname = ($_POST["page"]=="create_user.php") ? "136.jpg" : $_SESSION["photo"];
        if(isset($_FILES["photo"]) && $_FILES["photo"]["error"]===0 && $_FILES["photo"]["size"]<11048576){
            $ext= strtolower(pathinfo( $_FILES["photo"]["name"], PATHINFO_EXTENSION));
            $exts=["png","jpg","jpeg","bmp","gif"];
            if(in_array($ext,$exts))
            {
                $newimgname=uniqid("IMG-",true).".".$ext;
                move_uploaded_file($_FILES["photo"]["tmp_name"], "uploads/profiles/".$newimgname);
            }
        }

        if($_POST["page"]=="create_user.php"){
            $query="insert into users(name,pass,mail,img,token)values('".$name."','".$pass."','".$mail."','".$newimgname."','$token')";
        }
        else{
            $query="update users set name='".$name."', pass='".$pass."', mail='".$mail."', img='".$newimgname."', verified='".$_SESSION["verified"]."', token='".$token."' where id='".$_SESSION["id"]."'";
        }
        mysqli_query($c, $query);
        mysqli_close($c);

        if($_POST["page"]=="create_user.php"){
            session_unset();
            session_destroy();
            header("Location: signup0.html?name=".$_POST["name"]."&pass=".$_POST["pass"]."&mail=".$_POST["mail"]);
        }
        else{
            $_SESSION["name"]=$_POST["name"];
            $_SESSION["mail"]=$_POST["mail"];
            $_SESSION["pass"]=$_POST["pass"];
            $_SESSION["token"]=$token;
            $_SESSION["photo"]=$newimgname;
    
            if($_SESSION["verified"]==0)
                header("Location: signup1.php");
            else
                header("Location: index.php");
        }

        exit;
    }
}

if(isset($_POST["create_gallery"])){

    $text = trim($_POST["text"]);
    $newimgname = "";
    $filecount = count($_FILES["img"]["name"]);
    
    for ($i=0; $i<$filecount ; $i++) { 
        if(isset($_FILES["img"]["name"][$i]) && $_FILES["img"]["error"][$i]===0 && $_FILES["img"]["size"][$i]<11048576){
            $name=pathinfo($_FILES["img"]["name"][$i], PATHINFO_FILENAME);
            $ext= strtolower(pathinfo($_FILES["img"]["name"][$i], PATHINFO_EXTENSION));
            $exts=["png","jpg","jpeg","bmp","gif"];
            if(in_array($ext,$exts))
            {
                $newimgname=$name."_".rand(1000, 9999).".".$ext;
                move_uploaded_file($_FILES["img"]["tmp_name"][$i], "uploads/gallery/".$newimgname);
            }
        }
        else{
            header("Location: create_gallery.php");
            exit;
        }
    
        include ("partials/conn.php");
        $text = mysqli_real_escape_string($c , $text) ;
        $query="insert into gallery(img, time, text, user)values('".$newimgname."', now(), '". $text."', '". $_SESSION["id"] ."')";
        mysqli_query($c, $query);
        mysqli_close($c);
    }

    header("Location: gallery.php?user=".$_SESSION["mail"]);
    exit;
}

elseif(isset($_POST["post"])) {
    //do not send the message if reader is blocked by admin / if reader is not a friend / reader is a friend that blocked you
    if(isset($_SESSION["type"]) && $_SESSION["type"]=="user") { //do not check if i'm admin
        include ("partials/conn.php");
        $d=mysqli_query ($c, "select friends,blocked from users WHERE id='".$_POST["id_r"]."'");
        $data= mysqli_fetch_array($d);

        if($data["blocked"]==1){ exit("server error #2"); } //if user is blocked by admin

        $friendsArray = json_decode($data["friends"], true);
        mysqli_close($c);
        if (!isset($friendsArray[$_SESSION["id"]]) || $friendsArray[$_SESSION["id"]]==1) {
            exit("server error #3"); //if not friend or friend blocked you
        }
    }

    if(!strlen($_POST["id_r"]) || !is_numeric($_POST["id_r"])){
        exit("404 id");
    }
    if (mb_strlen($_POST["message"],"UTF-8") > 500) {
        exit("404 message to 500");
    }

    $message = trim($_POST["message"]);
    if(!strlen($message) && empty($_FILES["file"]["name"])){
        header("Location: posts.php");
        exit;
    }
    
    include ("partials/conn.php");
    if(!$c){
        mysqli_close($c); exit(mysqli_connect_error());
    }

    $id_r = mysqli_real_escape_string ($c , $_POST["id_r"]) ;
    $d = mysqli_query ($c, "select name,mail,img from users where id = '".$id_r."'");
    if(mysqli_num_rows($d)!=1){
        exit("404 post");
    }
    $r= mysqli_fetch_array($d);

    $message = mysqli_real_escape_string($c , $message) ;
    $name_w = mysqli_real_escape_string ($c , $_SESSION["name"]) ;
    $mail_w = mysqli_real_escape_string ($c , $_SESSION["mail"]) ;
    $name_r = mysqli_real_escape_string ($c , $r["name"]) ;
    $mail_r = mysqli_real_escape_string ($c , $r["mail"]) ;
    $img_r = mysqli_real_escape_string ($c , $r["img"]) ;

    $newfilename ="" ;
    if(isset($_FILES["file"]) && $_FILES["file"]["error"]===0){
        $name=pathinfo($_FILES["file"]["name"], PATHINFO_FILENAME);
        $ext= pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
        $newfilename=$name."_".rand(1000, 9999).".".$ext;
        move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/posts/".$newfilename);
    }

    $query="insert into posts(message,file,users_id_w,users_name_w,users_mail_w,users_img_w,users_id_r,users_name_r,users_mail_r,users_img_r)
                        values('".$message."','".$newfilename."','".$_SESSION["id"]."','".$name_w."','".$mail_w."','".$_SESSION["photo"]."','".$id_r."','".$name_r."','".$mail_r."','".$img_r."')";
    mysqli_query ($c, $query);
    mysqli_close($c);
    header("Location: posts.php");
    exit;
}

elseif(isset($_GET["befriend"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404 3");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{ 
        $d = mysqli_query ($c, "select friends from users where id = '".$_SESSION["id"]."'");
        if(mysqli_num_rows($d)!=1){
            exit("404 block");
        }
    
        $r= mysqli_fetch_array($d);
        $phpArray = json_decode($r["friends"], true);
        $phpArray[$_GET["befriend"]] = 0;

        mysqli_query ($c, "update users set friends='".json_encode($phpArray)."' where id=" . $_SESSION["id"]) ;
        mysqli_close($c);
        header("Location: users.php?title=Users");
        exit;
    }
}

elseif(isset($_GET["unfriend"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404 3");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{ 
        $d = mysqli_query ($c, "select friends from users where id = '".$_SESSION["id"]."'");
        if(mysqli_num_rows($d)!=1){
            exit("404 block");
        }
    
        $r= mysqli_fetch_array($d);
        $phpArray = json_decode($r["friends"], true);
        unset($phpArray[$_GET["unfriend"]]);

        mysqli_query ($c, "update users set friends='".json_encode($phpArray)."' where id=" . $_SESSION["id"]) ;
        mysqli_close($c);
        header("Location: users.php?title=".$_GET["title"]);
        exit;
    }
}

elseif(isset($_GET["blockuser"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["type"]) || $_SESSION["type"]!="admin"){
        exit("404 blockuser");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        mysqli_query ($c, "update users set blocked='1' where id=" . $_GET["blockuser"]) ;
        mysqli_close($c);
        header("Location: users.php?title=Users");
        exit;
    }
}

elseif(isset($_GET["unblockuser"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["type"]) || $_SESSION["type"]!="admin"){
        exit("404 unblockuser");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        mysqli_query ($c, "update users set blocked='0' where id=" . $_GET["unblockuser"]) ;
        mysqli_close($c);
        header("Location: users.php?title=Users");
        exit;
    }
}

elseif(isset($_GET["blockfriend"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404 4");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        $d = mysqli_query ($c, "select friends from users where id = '".$_SESSION["id"]."'");
        if(mysqli_num_rows($d)!=1){
            exit("404 block");
        }

        $r= mysqli_fetch_array($d);
        $phpArray = json_decode($r["friends"], true);
        $phpArray[$_GET["blockfriend"]]=1;

        mysqli_query ($c, "update users set friends='".json_encode($phpArray)."' where id=" . $_SESSION["id"]) ;
        mysqli_close($c);
        header("Location: users.php?title=Friends");
        exit;
    }
}

elseif(isset($_GET["unblockfriend"])) {
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true"){
        exit("404 5");
    }

    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        $d = mysqli_query ($c, "select friends from users where id = '".$_SESSION["id"]."'");
        if(mysqli_num_rows($d)!=1){
            exit("404 unblock");
        }

        $r= mysqli_fetch_array($d);
        $phpArray = json_decode($r["friends"], true);
        $phpArray[$_GET["unblockfriend"]]=0;

        mysqli_query ($c, "update users set friends='".json_encode($phpArray)."' where id=" . $_SESSION["id"]) ;
        mysqli_close($c);
        header("Location: users.php?title=Friends");
        exit;
    }
}

elseif(isset($_GET["deletemessage"])){
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("404 6");
    }

    include ("partials/conn.php");
     if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        mysqli_query ($c, "delete from posts where id=" . $_GET["deletemessage"] . " and (users_id_w=".$_SESSION["id"]." or users_id_r=".$_SESSION["id"].")") ;
        if(mysqli_affected_rows($c)<1){mysqli_close($c);exit("404 7");} 
        mysqli_close($c);
        unlink("uploads/posts/".$_GET["file"]);
        header("Location: posts.php");
        exit;
    }
}

elseif(isset($_GET["deletegallery"])){
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("404 8");
    }
    if(isset($_SESSION["type"]) && $_SESSION["type"]=="user"){ //if user is not admin he cannot delete others gallery
        if($_GET["user"]!=$_SESSION["id"]){
            exit("404 9");
        }
    }

    include ("partials/conn.php");
     if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else{
        mysqli_query ($c, "delete from gallery where id=" . $_GET["deletegallery"]) ;
        if(mysqli_affected_rows($c)<1){mysqli_close($c);exit("404 10");} 
        mysqli_close($c);
        unlink("uploads/gallery/".$_GET["img"]);
        header("Location: gallery.php?user=".$_GET["user"]."&page=".$_GET["page"]."&perpage=".$_GET["perpage"]."#ppp");
        exit;
    }
}

elseif(isset($_POST["login"]))
{
    include ("partials/conn.php");
    if(!$c){mysqli_close($c); 
        exit(mysqli_connect_error());
    }
    else
	{
        $name = mysqli_real_escape_string ($c , trim($_POST["profilname"])) ;
        $mail = mysqli_real_escape_string ($c , trim($_POST["profilmail"])) ;
        $pass = mysqli_real_escape_string ($c , $_POST["profilpass"] ) ;

        $query="select * from users where name='".$name."' and pass = '".$pass."' and mail = '".$mail."' limit 1";
        
        $d = mysqli_query ($c, $query);
		if(mysqli_num_rows($d)==1)
		{
            $r= mysqli_fetch_array ($d);

            $_SESSION["auth"]="true";
            $_SESSION["id"]=$r["id"];
            $_SESSION["name"]=$r["name"];
            $_SESSION["mail"]=$r["mail"];
            $_SESSION["pass"]=$r["pass"];
            $_SESSION["photo"]=$r["img"];
            $_SESSION["type"]=$r["type"];
            $_SESSION["token"]=$r["token"];
            $_SESSION["blocked"]=$r["blocked"];
            $_SESSION["verified"]=$r["verified"];
            $_SESSION["notif"]=0;

            if($r["verified"]==0){
                header("Location: signup1.php");
                exit;
            }
            
            $d = mysqli_query ($c, "select count(red) from posts where users_id_r='".$r["id"]."' and red=0");
            $_SESSION["notif"]=mysqli_fetch_array($d)[0];
            
            mysqli_close($c);
            header("Location: index.php");
			exit;
		}
		else
		{
            mysqli_close($c);
			header("Location: login_mobile.php?errorlogin=Incorrect Name, Email or Password");
			exit;
		}
	}
}

elseif(isset($_GET["logout"]))
{
    session_unset();
    session_destroy();
	header("Location: index.php");
	exit;
}

else
    //header("Location: index.php");
    exit("404 (no routes)");

?>