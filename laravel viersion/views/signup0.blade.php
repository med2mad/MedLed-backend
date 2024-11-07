<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <script>
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/login';
        form.style.display = 'none';

        const nameInput = document.createElement('input');
        nameInput.name = 'profilname';
        nameInput.value = "{{$name}}";

        const passInput = document.createElement('input');
        passInput.name = 'profilpass';
        passInput.value = "{{$pass}}";

        const mailInput = document.createElement('input');
        mailInput.name = 'profilmail';
        mailInput.value = "{{$mail}}";

        const hiddenInput = document.createElement('input');
        hiddenInput.name = 'login';
        hiddenInput.value = 1;

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = csrfToken;
        form.appendChild(input);

        form.appendChild(nameInput);
        form.appendChild(passInput);
        form.appendChild(mailInput);
        form.appendChild(hiddenInput);
        document.body.appendChild(form);
        form.submit();
    </script>
</body>
</html>