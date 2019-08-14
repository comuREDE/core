<?php

require_once('init.php');


$sms_on = (new BD())->query("SELECT s.dado FROM sistema s WHERE info = 'sms_on'");

var_dump($sms_on);

if($sms_on[0]['dado'] == '0'){
	echo 'desligado';
}
die();

$ceps = "31170220,31255780";

// $cepTriagem = (new BD())->query("SELECT DISTINCT t.cep, t.status, t.sensor FROM triagem t WHERE sensor = 99 AND status = 'R' AND cep IN ('$ceps')");

$cepTriagem = (new BD())->query("SELECT DISTINCT r.cep, r.status, r.sensor FROM relatorios r WHERE sensor = 99 AND status = 'SMS' AND r.cep = '31170220'");


// $app = (new BD())->query("SELECT DISTINCT t.cep, t.status, t.sensor FROM triagem t WHERE sensor = 99 AND status = 'R' AND cep IN ('$ceps')");

var_dump($cepTriagem[0]['cep']);

die();

echo "-- Tabela JOIN --" . PHP_EOL;

$ultimoCEP = (new BD())->query("SELECT DISTINCT t.cep, t.status, t.sensor FROM triagem t
								JOIN relatorios r ON t.cep = r.cep
								WHERE t.sensor = 99 AND t.status = '0'");

echo count($ultimoCEP);
echo gettype($ultimoCEP);
die();


// $sql = "SELECT cad.id, cad.celular, cad.email, cad.nome, cad.cep, rel.status
// 			FROM cadastros AS cad
// 			JOIN relatorios AS rel ON cad.cep = rel.cep
//             WHERE rel.cep IN ('$ceps') AND rel.status = 'SMS'
// 			LIMIT 5;";

// $cadastros = (new BD())->query($sql);

// var_dump($cadastros).PHP_EOL;

// var_dump(count($cadastros));


// //echo count($cadastros).PHP_EOL;

// //print_r($cadastros);

// //var_dump($cadastros);

// $ultimoCEP = (new BD())->query("SELECT DISTINCT cep, status FROM relatorios WHERE status = 'SMS'");

// var_dump($ultimoCEP);

// echo "-- Tabela Triagem --".PHP_EOL;

// $cepJaEnviado = (new BD())->query("SELECT DISTINCT cep, status FROM triagem WHERE status = 'R'");

// var_dump($cepJaEnviado);