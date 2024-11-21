<?php
/**
 * Created by PhpStorm.
 * User: felipebhz
 * Date: 2019-08-31
 * Time: 20:08
 */

session_start();

require 'init.php';

if ((!isset($_SESSION['user']) == true) and (!isset($_SESSION['pass']) == true)) {
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    header('location:login.php');
}

$anunciantes = (new BD())->query("SELECT * FROM anunciantes a");

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

    <!-- Arquivos do DataTable -->
    <!-- DataTable CSS -->
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Scripts do DataTables MD -->
    <!-- JQuery -->
    <script type=" text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Data Table JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>


    <title>comuREDE</title>
</head>

<body>
<header class="header">
    <img class="header__logo" src="images/logo-comuREDE_02.png" alt="Logo comuREDE">
</header>
<main class="container">
    <a class="btn__default formRegister__itemButton" style="text-decoration: none;" href="setup.php">Setup</a>
    <br>
    <h2 class="formRegister__title">
        Lista de Anunciantes Cadastrados:
    </h2>
    <div style="width: 100%;" class="table-bg p-20">
        <table id="myTable" class="table table-striped table-bordered table-sm display compact" style="width:100%"" cellspacing=" 0" width="100%">
        <thead>
        <tr>
            <th class="th-sm">Nome</th>
            <th class="th-sm">Celular</th>
            <th class="th-sm">Email</th>
            <th class="th-sm">CEP</th>
            <th class="th-sm">Saldo</th>
            <th class="th-sm">Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($anunciantes as $anunciante) : ?>

            <tr>
                <td><?= $anunciante['nome'] ?></td>
                <td><?= $anunciante['celular'] ?></td>
                <td><?= $anunciante['email'] ?></td>
                <td><?= $anunciante['cep'] ?></td>
                <td><?= $anunciante['saldo'] ?></td>
                <td>
                    <a href="edit-anunciante.php?id=<?=$anunciante['id']?>">Editar</a>
                    <a href="anuncios.php?id=<?=$anunciante['id']?>">Anúncios</a>
                    <a href="cadastro-anuncio.php?id=<?=$anunciante['id']?>">Cadastrar Anúncio</a>
                </td>
            </tr>

        <?php endforeach ?>


        </tbody>
        </table>
    </div>
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

<!-- Configuracao da Lib DataTable -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>