<?php

require "init.php";

if($_POST){
	$nome = $_POST['name'];
	$email = $_POST['email'];
	$celular = $_POST['phone'];
	$cep = $_POST['cep'];


	$cadastro = compact('nome','email','celular','cep'); 
	$res = (new Model())->setTable('cadastros')->save($cadastro);

	if($res){
		echo "<h1>Cadastro realizado com sucesso</h1>";
	}
	#echo "<h2><a href='services.html'>Acessar Comurede</a>";
	

	#var_dump($res);
	
} else {
	echo "form nao recebido";

}
