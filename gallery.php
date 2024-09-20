<?php include ("partials/header.php");
    if(!isset($_SESSION["auth"]) || $_SESSION["auth"]!="true" || !isset($_SESSION["verified"]) || $_SESSION["verified"]==0){
        exit("Activate your account !");
    }

    if($_GET["user"] != $_SESSION["id"] && isset($_SESSION["type"]) && $_SESSION["type"]=="user") { //do not check if its your gallery or if i'm admin
        if($_SESSION["blocked"]==1){ exit("404 1"); } //if i'm blocked by admin

        include ("partials/conn.php");
        $d=mysqli_query ($c, "select friends,blocked from users WHERE id='".$_GET["user"]."'");
        $data= mysqli_fetch_array($d);

        if($data["blocked"]==1){ exit("404 2"); } //if user is blocked by admin

        $friendsArray = json_decode($data["friends"], true);
        mysqli_close($c);
        if (!isset($friendsArray[$_SESSION["id"]]) || $friendsArray[$_SESSION["id"]]==1) {
            exit("404 3"); //if not friend or friend blocked you
        }
    }

    $query ="select count(id) from gallery WHERE user = '". $_GET["user"] ."'";
    $date1 = ""; $defdate1="";
    $date2 = ""; $defdate2="";
    if(isset($_POST["date1"]) && !empty($_POST["date1"])){
        $query = $query . " and time >= '" . $_POST["date1"]  . " 00:00:00'";
        $date1 = "&date1=".$_POST["date1"];
        $defdate1= date( "Y-m-d" , strtotime($_POST["date1"]) );
    }
    elseif(isset($_GET["date1"]) && !empty($_GET["date1"])){
        $query = $query . " and time >= '" . $_GET["date1"] . " 00:00:00'";
        $date1 = "&date1=".$_GET["date1"];
        $defdate1= date( "Y-m-d" , strtotime($_GET["date1"]) );
    }
    
    if(isset($_POST["date2"]) && !empty($_POST["date2"])){
        $query = $query . " and time <= '" . $_POST["date2"] . " 23:59:59'";
        $date2 = "&date2=".$_POST["date2"];
        $defdate2= date( "Y-m-d" , strtotime($_POST["date2"]) );
    }
    elseif(isset($_GET["date2"]) && !empty($_GET["date2"])){
        $query = $query . " and time <= '" . $_GET["date2"] . " 23:59:59'";
        $date2 = "&date2=".$_GET["date2"];
        $defdate2= date( "Y-m-d" , strtotime($_GET["date2"]) );
    }

    include ("partials/conn.php");
    $d = mysqli_query ($c, $query) ;
    mysqli_close($c);
    
    $perpage = 5;
    if(isset($_GET["perpage"]) && is_numeric($_GET["perpage"])){
        $perpage = $_GET["perpage"];
    }
    $pagesnbr = ceil(mysqli_fetch_array($d)[0]/$perpage);

    $debut = 0;
    if(isset($_GET["page"]) && is_numeric($_GET["page"])){
        $currentpage = ceil($_GET["page"]);
        if($currentpage>0) {$debut = ($currentpage-1)*$perpage;}
    }
    elseif(isset($_GET["last"])){
        $currentpage = $pagesnbr ;
        $debut = ($currentpage-1)*$perpage;
    }
    else{
        $currentpage = 1;
    }
?>

<h1>Gallery</h1>
<div class="row">
    <p class="col" style="text-align:center;" >
        <nobr>
            <?php if($pagesnbr==0){ ?>
                <p style="text-align:center;">No Media !</p>
            <?php  }else{ ?> 
                <img src="files/prevB.png" width="50" height="50" onclick="next(-1)"/>
                <img id="136" class="rounded my-2" onclick="popupf()" width="350" height="350" style="border:solid;user-select: none;object-fit:contain;" alt="medled"/> 
                <img src="files/nextB.png" width="50" height="50" onclick="next(1)"/>
            <?php } ?>
        </nobr>
    </p>
   <!-- <div class="col align-items-center d-flex" >
        <div class="col align-items-center d-flex" id="galltext" style="overflow:auto;height:300px;"></div>
    </div> -->
</div>

<div style="margin:0px; padding:0px; text-align:right">
    <div class="col"> - <a class="text-primary" href="create_gallery.php">Add to Gallery</a></div>
</div>

<p class="pagenum" >
    <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=1&perpage=<?= $perpage ?><?= $date1.$date2 ?>#ppp"> &nbsp; << &nbsp; </a> 
    <?php for ($i=1; $i<=$pagesnbr; $i++){ 
        if($currentpage!=$i){?>
            <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=<?= $i ?>&perpage=<?= $perpage ?><?= $date1.$date2 ?>#ppp"> &nbsp; <?= $i ?> &nbsp; </a>
        <?php }else{ ?>
            &nbsp; <?= $i ?> &nbsp; 
    <?php }}?> 
    <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=<?= $pagesnbr ?>&perpage=<?= $perpage ?><?= $date1.$date2 ?>&last=yes#ppp"> &nbsp; >> &nbsp; </a>
</p>

<div class="row" style="text-align:center">
    <?php include ("partials/conn.php");
        $query = "select * from gallery WHERE user = '". $_GET["user"] ."'";

        if(isset($_POST["date1"]) && !empty($_POST["date1"])){
            $query = $query . " and time >= '" . $_POST["date1"] . " 00:00:00'";
        }
        elseif(isset($_GET["date1"]) && !empty($_GET["date1"])){
            $query = $query . " and time >= '" . $_GET["date1"] . " 00:00:00'";
        }

        if(isset($_POST["date2"]) && !empty($_POST["date2"])){
            $query = $query . " and time <= '" . $_POST["date2"] . " 23:59:59'";
        }
        elseif(isset($_GET["date2"]) && !empty($_GET["date2"])){
            $query = $query . " and time <= '" . $_GET["date2"] . " 23:59:59'";
        }
        $query = $query . " order by time desc";
        $query = $query . " limit $debut,$perpage";
        $d=mysqli_query ($c, $query);
        mysqli_close($c);

        if(mysqli_num_rows($d)==0){?>
            <p style="text-align:center;">No Media !</p>
        <?php  }else{
            $i=0;
            while($r= mysqli_fetch_array ($d))
            { ?>
                <div class="col">
                    <div>
                        <img id="img<?= ++$i ?>" onclick='document.getElementById("136").src="uploads/gallery/<?= $r["img"] ?>"; /* document.getElementById("galltext").innerHTML="<?= $r["text"] ?>"; */ currentimg=<?= $i ?>' class="rounded my-2 gimg" width="100" height="100" src="uploads/gallery/<?= $r["img"] ?>" alt="gallery">
                        <br><span style="font-family:'Courier New', Courier, monospace;"><?= date ( "d/m/Y"  , strtotime($r["time"]) ) ?></span>
                        <?php if(isset($_SESSION["id"]) && $_SESSION["id"]==$_GET["user"] || isset($_SESSION["type"]) && $_SESSION["type"]=="admin"){ ?>
                            <p><a class="btn btn-danger" href="php.php?deletegallery=<?= $r["id"] ?>&user=<?= $_SESSION["id"] ?>&img=<?= $r["img"] ?>&page=<?= $currentpage ?>&perpage=<?= $perpage ?>" onclick="return confirm('Delete this image ?');">Delete</a></p>
                        <?php } ?>
                    </div>
                </div>
    <?php   } 
        echo "<script type='text/javascript'>let perpage = $i;</script>";
    }?>
</div>



<p id="ppp" class="pagenum">
    <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=1&perpage=<?= $perpage ?><?= $date1.$date2 ?>#ppp"> &nbsp; << &nbsp; </a> <?php
    for ($i=1; $i<=$pagesnbr; $i++){ 
        if($currentpage!=$i){?>
            <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=<?= $i ?>&perpage=<?= $perpage ?><?= $date1.$date2 ?>#ppp"> &nbsp; <?= $i ?> &nbsp; </a>
        <?php }else{ ?>
            &nbsp; <?= $i ?> &nbsp; 
    <?php }}?> 
    <a class="text-primary" href="gallery.php?user=<?= $_GET["user"] ?>&page=<?= $pagesnbr ?>&perpage=<?= $perpage ?><?= $date1.$date2 ?>&last=yes#ppp"> &nbsp; >> &nbsp; </a>
</p>

<div style="display:flex; align-items:center;" class="pb-2 pt-0">
    <div class="col" >
        Images per page : 
        <select id="perpage" onchange="refresh()">
            <option value ="5" <?php if($perpage==5) {echo "selected";} ?> >5</option>
            <option value="10" <?php if($perpage==10){echo "selected";} ?> >10</option>
            <option value="20" <?php if($perpage==20){echo "selected";} ?> >20</option>
            <option value="30" <?php if($perpage==30){echo "selected";} ?> >30</option>
        </select>
    </div>

    <div class="col" style="margin:0px; padding:0px;overflow:auto; text-align:right"> <nobr>
        <form method="POST" action="gallery.php#ppp">
        From : <input type="date" name="date1" id="date1" value = "<?= $defdate1 ?>">
        To : <input type="date" name="date2" id="date2" value = "<?= $defdate2 ?>">
        <input type="submit" value="Filter">
        </form></nobr>
    </div>
</div>



<script type="text/javascript">
    let currentimg = 1;
    let poped = false;
    next(0);
    document.addEventListener("keydown", e=>{ if(e.key.toLowerCase()==="arrowleft")next(-1);if(e.key.toLowerCase()==="arrowright")next(1);if(e.key.toLowerCase()==="escape")popupremove();})
    
    <?php if(isset($_GET["last"])){?> 
        currentimg = perpage;
        let nextsrc = document.getElementById("img" + currentimg).src;
        document.getElementById("136").src=nextsrc;
    <?php } ?>

    function popupf(){
        let popup = document.createElement("div");
        document.body.appendChild(popup);
        popup.setAttribute("class", "popup");
        popup.setAttribute("id", "popupid");
        
        let popupimg = document.createElement("img");
        popup.appendChild(popupimg);
        popupimg.setAttribute("src", event.currentTarget.getAttribute("src"));
        popupimg.setAttribute("id", "popupimg");
        
        let prevB = document.createElement("a");
        let prevBText = document.createTextNode("<");
        prevB.appendChild(prevBText);
        popup.appendChild(prevB);
        prevB.setAttribute("class", "prevB");
        prevB.setAttribute("onclick", "next(-1)");

        let nextB = document.createElement("a");
        let nextBText = document.createTextNode(">");
        nextB.appendChild(nextBText);
        popup.appendChild(nextB);
        nextB.setAttribute("class", "nextB");
        nextB.setAttribute("onclick", "next(1)");

        let close = document.createElement("a");
        let closeText = document.createTextNode("X");
        close.appendChild(closeText);
        popup.appendChild(close);
        close.setAttribute("class", "close");
        close.setAttribute("onclick", "popupremove()");

        poped=true;
    }

    function popupremove(){
        poped=false;
        document.getElementById("popupid").remove();
    }
    
    function next(i){
        if(!(i==1 && currentimg==perpage || i==-1 && currentimg==1))
        {
            currentimg = currentimg+i;
            let nextsrc = document.getElementById("img" + currentimg).src;
            document.getElementById("136").src=nextsrc;
            if(poped)document.getElementById("popupimg").src=document.getElementById("136").src;
        }
    }

    function refresh(){
        const perpage = document.getElementById("perpage").value;
        if(perpage!=0){ window.location.href = "gallery.php?perpage=" + perpage + "<?= $date1.$date2 ?>#ppp"; }
    }
</script>

<?php include ("partials/footer.html"); ?>