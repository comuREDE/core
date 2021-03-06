<?php
require 'init.php';

$mqtt = new phpMQTT("m16.cloudmqtt.com", 18479, "phpDevCmr"); 
if(!$mqtt->connect()){
    echo "Erro ao conectar MQTT";die;
}else{
	echo "Escutando CloudMQTT";
	echo PHP_EOL;
}

$topics['AGUA'] = array("qos"=>0, "function"=>"procmsg_agua");
$topics['LUZ'] = array("qos"=>0, "function"=>"procmsg_luz");

$mqtt->subscribe($topics,0);

while($mqtt->proc()){

}
$mqtt->close();


function processaTopico($msg){
	# $topic AGUA LUZ
	#1 $msg (L/D)__12345-Y
	#2 $msg (L/D)__12345789-XXXX
	//echo "<br>",$msg," : ";
	if(preg_match("/(L|D)__(\d{8})-(\d{1,2})/",$msg,$matches)){
		$estado = $matches[1];
		$cep = $matches[2];
		$sensor = (int) $matches[3];
		$arr = compact("estado","cep","sensor");
		echo " -- Recebida MSG do MQTT --";
		echo PHP_EOL;
		echo " -- CEP: ".$cep." --";
		echo PHP_EOL;
		return $arr;
	} 
	return null;
}

function procmsg_luz($topic,$msg){

    $data = date("Y-m-d H:i:s");
	$dados = processaTopico($msg);#"estado","cep","sensor"
	extract($dados);#$estado $cep $sensor
	$info = ['dia_hora'=>$data,'estado'=>$estado,'cep'=>$cep,'sensor'=>$sensor,];              

	$res = (new Model())->setTable('sensores_luz')->save($info);

}

function procmsg_agua($topic,$msg){
    $data = date("Y-m-d H:i:s");
	$dados = processaTopico($msg);#"estado","cep","sensor"
	extract($dados);#$estado $cep $sensor
	$info = ['dia_hora'=>$data,'estado'=>$estado,'cep'=>$cep,'sensor'=>$sensor,];              

	$res = (new Model())->setTable('sensores_agua')->save($info);

}
