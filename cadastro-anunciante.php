<?php
/**
 * Created by PhpStorm.
 * User: felipebhz
 * Date: 2019-08-31
 * Time: 20:43
 */
session_start();

require 'init.php';

if ((!isset($_SESSION['user']) == true) and (!isset($_SESSION['pass']) == true)) {
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    header('location:login.php');
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$celular = $_POST['celular'];
$cep = $_POST['cep'];
$saldo = $_POST['saldo'];

if ($_POST['cadastrar'] == '1') {
    $novoUser = (new BD())->insertAnunciante($celular, $email, $nome, $cep, $saldo);
    unset($_POST);
    header("Location: anunciantes.php");
    exit;
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
    <a class="btn__default formRegister__itemButton" style="text-decoration: none;" href="setup.php">Setup</a>
    <br>
    <main class="container">
        <h2 class="formRegister__title">
            Cadastrar Anunciante
        </h2>
        <form class="container__formRegister" method="POST" action="">

            <label class="formRegister__item" for="nome">
                nome
                <input class="formRegister__itemInput" type="text" name="nome">
            </label>
            <label class="formRegister__item" for="email">
                email
                <input class="formRegister__itemInput" type="email" name="email">
            </label>
            <label class="formRegister__item" for="celular">
                celular
                <input class="formRegister__itemInput" type="text" maxlength="11" name="celular">
            </label>
            <label class="formRegister__item" for="cep">
                cep
                <input class="formRegister__itemInput" type="text" maxlength="8" name="cep">
            </label>
            <label class="formRegister__item" for="saldo">
                saldo
                <input class="formRegister__itemInput" type="text" name="saldo">
            </label>

            <button class="formRegister__itemButton btn__default">Cadastrar</button>

            <input type="hidden" id="" name="cadastrar" value="1">

        </form>
        <form class="container__formRegister" method="POST" action="auth.php">
            <button class="formRegister__itemButton btn__default">Sair</button>
            <input type="hidden" name="logout" value="1">
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