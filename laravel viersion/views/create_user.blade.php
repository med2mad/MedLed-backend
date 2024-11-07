@include( 'partials.header' )

<h1>SignUp</h1>

@isset($error)
    <p class="alert alert-danger">{{$error}}</p>
@endisset

<form method="post" action="/signup" enctype="multipart/form-data">
    @csrf
    <input name="page" type="hidden" value="create_user">
    <table>
        <!-- <tr><td class="rounded"><label for="name">Nom</label></td><td><input name="name" id="name" type="text" required minlength="5" maxlength="20" pattern="[a-zA-Z][a-zA-Z0-9.\-_]+" title="entrez uniquement des lettres/nombres/points(.)/tirets(-_) Et le nom commence par une lettre" class="form-control"><div style="font-size:0.6em; color:red;"><img id="infoimg" src="/files/info.png" width="14" height="14" alt=""> lettres/nombres/./-/_ Uniquement, Et ça commence par une lettre</div></td></tr> -->
        <tr><td class="rounded"><label for="name">Name</label></td><td style="width:500px;"><input name="name" id="name" type="text" required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded"><label for="mail">E-mail</label></td><td style="width:500px;"><input name="mail" id="mail" type="email" required class="form-control"></td></tr>
        <tr><td class="rounded"><label for="pass">Password</label></td><td style="width:500px;"><input name="pass" id="pass" type="password" required required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr><td class="rounded" style="font-size:0.8em;padding:0"><label for="pass2">Confirm<br>Password</label></td><td style="width:500px;"><input name="pass2" id="pass2" type="password" required required minlength="5" maxlength="20" class="form-control"></td></tr>
        <tr>
            <td class="rounded"><label for="photo">Photo</label></td>
            <td style="text-align:center; display:flex; gap:10px; align-items:center;">
                <div>
                    <input type="button" style="width: 110px; margin:2px;" value="Pick Photo" onclick="document.getElementById('photo').click();" /> <br>
                    <input type="button" style="width: 110px; margin:2px;" value="No Photo" id="nophoto">
                </div>
                <div>
                    <input name="photo" id="photo" type="file" accept=".jpg,.jpeg,.png,.bmp,.gif" class="form-control" style="display:none;" />
                    <label for="photo"><img id="img" width="100" height="100" src="/uploads/profiles/136.jpg" alt="photo profile" /></label>
                </div>
            </td>
        </tr>
    </table>
    <div style="margin-top:4px;">
        <input type="checkbox" name="conditions" required> <label> Accept <a href="/page/conditions" style="text-decoration:underline;">Terms &amp; Conditions</a></label>
    </div>
    <br>
    <input type="submit" name="signup" class="btn btn-primary btn-lg mb-1" value="OK">
</form>

<script type="text/javascript">
    document.getElementById("photo").onchange=function() {
        document.getElementById("img").setAttribute("src",URL.createObjectURL(document.getElementById("photo").files[0]));
    }
    document.getElementById("nophoto").onclick=function() {
        document.getElementById("photo").value= null;
        document.getElementById("img").setAttribute("src","/uploads/profiles/136.jpg");
    }
</script>

@include( 'partials.footer' )