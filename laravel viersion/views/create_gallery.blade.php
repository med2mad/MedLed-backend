@include( 'partials.header' )

<h1>Add to Gallery</h1>

<?php if(isset($_GET["error"])){ ?>
    <p class="alert alert-danger"><?= $_GET["error"] ?></p>
<?php } ?>

<form method="post" action="/create_gallery?user=<?= $_SESSION["id"] ?>" style="text-align:center" enctype="multipart/form-data">
    @csrf
    <table style="margin:auto;">
        <tr>
            <td class="rounded"><label id="label" for="img">0 Images Selected</label></td>
            <td style="text-align:center">
                <input type="button" value="Browse..." onclick="document.getElementById('img').click();" />
                <input name="img[]" multiple id="img" type="file" accept=".jpg,.jpeg,.png,.bmp,.gif" class="form-control" style="display:none;" >
            </td>
            <tr class="d-none"><td class="rounded"><label for="text">Text</label></td><td><textarea name="text" id="text" maxlength="500" cols="30" rows="6" class="form-control"></textarea></td></tr>
        </tr>
    </table>
    <br><br>
    <input type="submit" name="create_gallery" class="btn btn-primary btn-lg mb-1" value="Upload">
</form>
<script type="text/javascript">
    document.getElementById("img").onchange=function(){
        let filecount = document.getElementById("img").files.length;
        document.getElementById("label").innerHTML =  filecount + " Images Selected";
    }
</script>

@include( 'partials.footer' )