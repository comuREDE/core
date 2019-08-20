<?php
session_start();

require 'init.php';

if ((!isset($_SESSION['user']) == true) and (!isset($_SESSION['pass']) == true)) {
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    header('location:login.php');
} else {

    if ((isset($_POST['sms']))) {
        $sms = (new BD())->setOnOffSMS($_POST['sms']);
    }

    $sms_on = (new BD())->query("SELECT s.dado FROM sistema s WHERE info = 'sms_on'");
    if($sms_on[0]['dado'] == '1'){
        $sms_status = "Ligado";
    }else{
        $sms_status = "Desligado";
    }
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

        <h2 class="formRegister__title">
            Status do Serviço de SMS: <?=$sms_status?>
        </h2>

        <form class="container__formRegister" method="POST" action="">
            <button class="formRegister__itemButton btn__default">Ligar SMS</button>
            <input type="hidden" name="sms" value="1">
        </form>

        <form class="container__formRegister" method="POST" action="">
            <button class="formRegister__itemButton btn__default">Desligar SMS</button>
            <input type="hidden" name="sms" value="0">
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