<?php

require 'init.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css' integrity='sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz' crossorigin='anonymous'>
    <link href="fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>comuREDE</title>
</head>
<body>
<header class="header">
    <img class="header__logo" src="images/logo-comuREDE_02.png" alt="Logo comuREDE">
</header>
<main class="container">
    <form class="container__formRegister" method="POST" action="auth.php">
        <label class="formRegister__item" for="user">
            username
            <input class="formRegister__itemInput" type="text" name="user">
        </label>
        <label class="formRegister__item" for="pass">
            senha
            <input class="formRegister__itemInput" type="password" name="pass">
        </label>
       
        <button class="formRegister__itemButton btn__default">Login</button>
    </form>
</main>
<footer class="footer">
    <h2 class="footer__title">Ajude a propagar essa ideia ;)</h2>
    <i class="footer__iconsSocial fab fa-facebook-f"></i>
    <i class="footer__iconsSocial fab fa-instagram"></i>
    <i class="footer__iconsSocial fab fa-twitter"></i>
</footer>

</body>

</html>