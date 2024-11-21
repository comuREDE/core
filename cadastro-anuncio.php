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

$texto = $_POST['texto'];
$cep = $_POST['cep'];
$dataCadastro = $_POST['dataCadastro'];


$anuncianteId = $_GET['id'];
$anunciante = (new BD())->query("SELECT id, nome, email, celular, saldo, cep FROM anunciantes WHERE id = {$anuncianteId}");

if ($_POST['cadastrarAnuncio'] == '1') {
    $novoAnuncio = (new BD())->insertAnuncio($anuncianteId, $texto, $cep, $dataCadastro);
    unset($_POST);
    header("Location: anuncios.php");
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
            Cadastrar Anúncio para: <?=$anunciante[0]['nome']?>
        </h2>

        <form class="" method="POST" action="">
            <div>
            <label class="formRegister__item__alt" for="texto">
                texto
                <br>
                <textarea class="" type="text" name="texto" maxlength="100" cols="60" rows="4" onkeyup="countChar(this)"></textarea>
            </label>
                <div style="text-align: right;" class="formRegister__item__alt" id="charNum"></div>
            </div>
            <div>
            <label class="formRegister__item__alt" for="cep">
                cep
                <br>
                <input style="width: 150px;" class="" type="text" maxlength="8" name="cep">
            </label>
            </div>
            <button class="formRegister__itemButton btn__default">Cadastrar Anúncio</button>

            <input type="hidden" id="" name="dataCadastro" value="<?=date('Y-m-d H:i:s');?>">
            <input type="hidden" id="" name="cadastrarAnuncio" value="1">

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

<script src="http://code.jquery.com/jquery-1.5.js"></script>
<script>
function countChar(val) {
        let len = val.value.length;
        if (len >= 100) {
            val.value = val.value.substring(0, 100);
        } else {
            $('#charNum').text('caracteres restantes: '+  (100 - len));
        }
    };
</script>