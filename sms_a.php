<?php

require("init.php");
require("funcoes.php");


//Loop para manter o script PHP rodando indefinidamente, com intervalos de 30 segundos
#principal_A();
while(1){
	inicio:
	//echo "\n----------- agua -----------<br>";
	principal_A();
	//echo "final de um ciclo<br>";
	sleep(5);
	goto inicio;	
}

// funcao principal que controla as chamadas das funcoes de tratamento de filtro das msgs MQTT
function principal_A(){
	//echo "\n<h1>inicio - ".date('d/m/Y H:i:s')."</h1>";
	filtroPrimarioAgua();
	#sleep(120);
	//echo "\n<h1>passou pelo primario - ".date('d/m/Y H:i:s')."</h1>";
	filtroSecundario('A');
	#sleep(60);
	//echo "\n<h1>passou pelo secundario - ".date('d/m/Y H:i:s')."</h1>";
    //validaEnvioSMS();
    alertaSMS('A');
	//echo "\n<h1>recebeu os sms - ".date('d/m/Y H:i:s')."</h1>";
	#sleep(30);
	//echo "\n<h1>fim Agua - ".date('d/m/Y H:i:s')."</h1>";
}


function filtroPrimarioAgua(){
	$q="SELECT id, dia_hora,
	DATE_FORMAT(dia_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	status,created_at, updated_at,
	estado,cep,sensor, day(dia_hora) as dia
	FROM sensores_agua 
	WHERE (status='' OR status IS NULL OR status <> 'T') 
	ORDER BY dia_hora ASC 
	LIMIT 1000
	;";

	$res = (new BD())->query($q);

	$loop=false;
	$regs=[];
	$flags=[];
	$count=count($res);
	if($count > 2){
		for($i=0; $i<$count-2; $i++){
			$id_davez = $res[$i]['id'];
			$atual_estado=$res[$i]['estado'];
			$proximo_estado=$res[$i+1]['estado'];
			$proximo_prox_estado=$res[$i+2]['estado'];
			if($atual_estado==='D'){
				$loop=false;
			}
			
			$atual_cep=$res[$i]['cep'];
	        $proximo_cep=$res[$i+1]['cep'];
	        $proximo_prox_cep=$res[$i+2]['cep'];

	        $atual_sensor=$res[$i]['sensor'];
	        $proximo_sensor=$res[$i+1]['sensor'];
	        $proximo_prox_sensor=$res[$i+2]['sensor'];

			$cond1 = ($atual_estado==='L' && $proximo_estado==='L' && $proximo_prox_estado==='L');
	        $cond2 = ($atual_cep == $proximo_cep) && ($atual_cep == $proximo_prox_cep);
	        $cond3 = ($atual_sensor == $proximo_sensor) && ($atual_sensor == $proximo_prox_sensor);

			if($cond1 && $cond2 && $cond3){
				if(!$loop){
					$regs[]=$res[$i];
				}
				$loop=true;
			} else {
				#echo "<br>NAAAOOO entrou ". implode(" - ",$res[$i]);
			}
			$flags[]=$id_davez;
		}

		#aqui neste caso vamos dar um tratamento para os 2 ultimos
		$penultimo = $count-1;
		$ultimo = $count-2;
		$flags[]= $res[$ultimo]['id'];
		$flags[]= $res[$penultimo]['id'];

		atualizaFlags($flags,'T','sensores_agua');

		if(is_array($regs)){
			saveTriagem($regs,"A");
			//var_dump($regs);
		} else {
			echo "Sem registros Triagem Agua";
		}


	} else {
		echo "Aguardando Novas MSGS via MQTT".PHP_EOL;
		//echo "Total Menor que 3 registros";
	}
	

}
