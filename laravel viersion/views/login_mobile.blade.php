@include( 'partials.header' )

<h1>Profile</h1>

@isset($errorlogin) 
    <p style="color:red;margin:0px;padding:0px;">
        {{$errorlogin}}
    </p>
@endisset

@include( 'partials.profil' )

@include( 'partials.footer' )