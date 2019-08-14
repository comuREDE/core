<?php

//inicia a sessao php para manter controle de usuario e sessão de login
session_start();


if($_POST['logout'] == 1){
    session_destroy();
    header('Location: login.php');
}

require 'init.php';

//faz a verificação no banco comparando o POST recebido da pagina login.php com o que existe no banco.
if (isset($_POST['user']) and isset($_POST['pass'])) {

    //define as variaveis user e pass de acordo com o que veio no POST
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);

    // chamada SQL ao banco para saber se tem registros com esses dados
    $sql = "SELECT id FROM users WHERE username = '$user' AND senha = '$pass'";
    //retorno do banco com os valores da query acima, usando o objeto da classe BD, no pdo.php
    $auth = (new BD())->query($sql);

    //se não vier vazio o retorno do banco, o usuario é enviado para a pagina setup.php
    if(count($auth) > 0){
        $_SESSION['user'] = $user;
        $_SESSION['pass'] = $pass;
        header('Location: setup.php');
    }else{
        //se o retorno do banco vier vazio, a sessão é eliminada do browser/memória
        unset($_SESSION['user']);
        unset($_SESSION['pass']);
        //Se o login falhar, redireciona para login e seta f (failed) como true para usar futuramente.
        header('Location: login.php?f=true');
    }

}

