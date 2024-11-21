<?php

$meses = array(
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta http-equiv="refresh" content="3"> -->
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.1/css/all.css' integrity='sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz' crossorigin='anonymous'>
    <link href="fonts/fonts.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>comuREDE</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- 
		Link Guide https://developers.google.com/chart/interactive/docs/gallery/areachart 
	-->
</head>

<body class="bodyServices">
    <header class="header">
        <h1 class="header__title header__title--services">Relatório Mensal</h1>
    </header>
    <main class="container container--services">

        <form class="container__formRegister container__formSubscribe" method="POST" action="md-report.php">
            <input type="hidden" name="cep" value="31170220">
            <h2 class="formRegister__title">
                COBRE pelos seus direitos com nossos relatórios. Selecione um período, entre com seu e-mail e saiba quando o serviço foi ou não entregue.
            </h2>
            <select name="mes" id="mes">
                <option value="" disabled>Selecione um mês...</option>

                    <?php foreach($meses as $mes => $meses):?>             
                        <option value"<?= $mes ?>"><?= $meses?></option>
                    <?php endforeach ?>



            </select>
            <label for="tipo" class="formRegister__item">Água
                <input type="radio" id="tipo" name="tipo" value="A">
            </label>
            <label for="tipo" class="formRegister__item">Luz
                <input type="radio" id="tipo" name="tipo" value="E">
            </label>
            <label class="formRegister__item" for="email">
                E-mail
                <input class="formRegister__itemInput" type="email" name="email">
            </label>
            <button class="formRegister__itemButton btn__default">Solicitar</button>
        </form>
    </main>
    <footer class="footer footer--service ">
        <a class="footer__linkHome " href="index.html ">
            <img class="footer__logoService " src="images/logo-comuREDE_02.png " alt=" ">
        </a>
    </footer>

</body>

</html>