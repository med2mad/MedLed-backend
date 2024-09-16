<?php  include ("partials/header.php"); ?>

<h1>Welcome</h1>

<p style="text-align: center; font-size:2em; font-style: italic; font-weight: bold">
    Welcome to "MedLed" , Where you can gather your friends and share posts and media.
</p>
<ul class="list-group" style="font-size:1.5em; font-weight: bold; border:1px solid black">
    <li class="list-group-item list-group-item-action" id="liwhitecss">- Start by building a list of friends from a <a href="users.php?title=Users" style="color:blue;">list of users</a>.</li>
    <li class="list-group-item list-group-item-action list-group-item-dark">- Then <a href="users.php?title=Friends" style="color:blue;">send them a post</a>, for them to add you as a friend.</li>
    <li class="list-group-item list-group-item-action" id="liwhitecss">- Create a  Gallery and fill it with images.</li>
    <li class="list-group-item list-group-item-action list-group-item-dark">- Your friends can access your gallery, and you can <a href="users.php?title=Friends" style="color:blue;">access theirs too</a>.</li>
</ul>

<?php include ("partials/footer.html"); ?>