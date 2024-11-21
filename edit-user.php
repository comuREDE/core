<?php

/**
 * Created by PhpStorm.
 * User: felipebhz
 * Date: 2019-08-31
 * Time: 18:56
 */

session_start();

require 'init.php';

if( (!isset($_GET['id'])) && (empty($_GET['id'])) ) {
    header('location:setup.php');
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$celular = $_POST['celular'];
$cep = $_POST['cep'];
$id = $_POST['id'];

if ($_POST['editar'] == '1') {
    $editUser = (new BD())->editCadastro($id, $celular, $email, $nome, $cep);

    //echo $editUser;
    //unset($_POST);
    header("Location: " . $_SERVER['PHP_SELF']);
}


if ((!isset($_SESSION['user']) == true) and (!isset($_SESSION['pass']) == true)) {
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    header('location:login.php');
} else {
    $usuarioId = $_GET['id'];
    $usuarios = (new BD())->query("SELECT * FROM cadastros c WHERE id = {$usuarioId}");
}

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

    <form class="container__formRegister" method="POST" action="">
        <h2 class="formRegister__title">
            Editar Usuário
        </h2>
        <label class="formRegister__item" for="name">
            Nome
            <input class="formRegister__itemInput" type="text" name="nome" value="<?=$usuarios[0]['nome']?>">
        </label>
        <label class="formRegister__item" for="email">
            email
            <input class="formRegister__itemInput" type="email" name="email" value="<?=$usuarios[0]['email']?>">
        </label>
        <label class="formRegister__item" for="phone">
            celular
            <input class="formRegister__itemInput" type="number" maxlength="11" name="celular" value="<?=$usuarios[0]['celular']?>">
        </label>
        <label class="formRegister__item" for="cep">
            cep
            <input class="formRegister__itemInput" type="text" maxlength="8" name="cep" value="<?=$usuarios[0]['cep']?>">
        </label>
        <button class="formRegister__itemButton btn__default">Editar</button>

        <input type="hidden" name="editar" value="1">
        <input type="hidden" name="id" value="<?=$usuarios[0]['id']?>">

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
