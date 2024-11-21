<?php
session_start();
require 'init.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$celular = $_POST['celular'];
$cep = $_POST['cep'];

if ($_POST['cadastrar'] == '1') {
    $novoUser = (new BD())->insertCadastro($celular, $email, $nome, $cep);
    unset($_POST);
    header("Location: " . $_SERVER['PHP_SELF']);
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
    <main class="container">
        <h2 class="container__title">Atenção</h2>
        <p class="container__textTerms">
            Ao acessar a internet por esse link, através de uma rede livre e aberta, suas informações NÃO SERÃO CRIPTOGRAFADAS e podem ser interceptadas por usuários maliciosos. Logo, ao avançar na navegação, estará expressamente declarando que está ciente de tal risco e também poderá ser responsablizado pelas autoridades locais, caso proceda com qualquer conduta ilícita ou ilegal.
        </p>
        <label for="acept" class="container__labelTerms">
            <input type="checkbox" name="acept" onchange="acceptCheck(this)" class="container__checkboxTerms">
            Li e concordo com os termos acima.
        </label>
        <div class="container__boxButtons">
            <!-- <button class="boxButtons__btn btn__default">Acessar serviço</button> -->
            <a href="services.php" class="boxButtons__btn btn__default">Acessar serviço</a>
            <button class="boxButtons__btn btn__default" id="internet">Navegar na Internet</button>
        </div>
        <form class="container__formRegister" method="POST" action="">
            <h2 class="formRegister__title">
                Cadastre-se abaixo e receba notificações por SMS quando cair água na sua rua ou faltar luz.
            </h2>
            <h2 class="formRegister__title">
                Entre em "ACESSAR SERVIÇO" e saiba mais.
            </h2>
            <label class="formRegister__item" for="name">
                Nome
                <input class="formRegister__itemInput" type="text" name="nome">
            </label>
            <label class="formRegister__item" for="email">
                email
                <input class="formRegister__itemInput" type="email" name="email">
            </label>
            <label class="formRegister__item" for="phone">
                celular
                <input class="formRegister__itemInput" type="text" maxlength="11" name="celular">
            </label>
<!--
            <label class="formRegister__item" for="cep">
                cep
                <input class="formRegister__itemInput" type="text" maxlength="8" name="cep">
            </label>
-->
		<input type="hidden" name="cep" value="24130400">
<!--
-->

            <button class="formRegister__itemButton btn__default">Cadastrar</button>

            <input type="hidden" id="" name="cadastrar" value="1">

        </form>
    </main>
    <footer class="footer">
        <h2 class="footer__title">Ajude a propagar essa ideia ;)</h2>
        <i class="footer__iconsSocial fab fa-facebook-f"></i>
        <i class="footer__iconsSocial fab fa-instagram"></i>
        <i class="footer__iconsSocial fab fa-twitter"></i>
    </footer>
    <script>
        const acceptCheck = (e) => {
            let internet;
            // let link = document.querySelector("#internet a");
            let img = document.querySelector("#internet")
            // let hrefData = link.getAttribute("href");

            console.log('img: ', img);
            console.log('e.target', e.checked)
            if (e.checked === true) {
                internet = true
            } else {
                internet = false
            }

            if (internet === false) {
                // link.removeAttribute("href");
                img.style.backgroundColor = "#E5E5E5";
                img.style.color = '#878787'
            } else {
                img.style.backgroundColor = "#FF8760";
                img.style.color = '#FFF'
                // link.setAttribute("href", hrefData);
            }
        }
    </script>
</body>

</html>
