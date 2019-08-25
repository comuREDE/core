<?php

require("init.php");
require("funcoes.php");

#principal_E();
while(1){
	inicio:
	//echo "\n----------- luz (Energ) -----------<br>";
	principal_E();
	//echo "\n----final de um ciclo<br>";
	sleep(10);
	goto inicio;
}


####################################################


function principal_E(){
	//echo "\n<h1>inicio - ".date('d/m/Y H:i:s')."</h1>";
	filtroPrimarioLuz();
	#sleep(120);
	//echo "\n<h1>passou pelo primario - ".date('d/m/Y H:i:s')."</h1>";
	filtroSecundario('E');
	#sleep(60);
	//echo "\n<h1>passou pelo secundario - ".date('d/m/Y H:i:s')."</h1>";
	alertaSMS('E');
	//echo "\n<h1>recebeu os sms - ".date('d/m/Y H:i:s')."</h1>";
	#sleep(30);
	//echo "\n<h1>fim da luz - ".date('d/m/Y H:i:s')."</h1>";
}



function filtroPrimarioLuz(){
	//echo "<h3>".__FUNCTION__."</h3>";

	$sql="SELECT id, dia_hora,
	DATE_FORMAT(dia_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	status,estado,cep,sensor, day(dia_hora) as dia
	FROM sensores_luz 
	WHERE (status='' OR status IS NULL OR status <> 'T') 
	ORDER BY dia_hora ASC 
	LIMIT 1000
	;";
	$res = (new BD())->query($sql);

	//print_r($res);
	#die;
	$loop=false;
	$regs=[];
	$flags=[];
	$count=count($res);

	if($count > 2){

		for($i=0; $i<$count-1; $i++){
			$id_davez = $res[$i]['id'];
			$atual_estado=$res[$i]['estado'];
			$proximo_estado=$res[$i+1]['estado'];
			#$proximo_prox_estado=$res[$i+2]['estado'];

			$atual_cep=$res[$i]['cep'];
			$proximo_cep=$res[$i+1]['cep'];
			#$proximo_prox_cep=$res[$i+2]['cep'];

			#$atual_sensor=$res[$i]['sensor'];
			#$proximo_sensor=$res[$i+1]['sensor'];
			#$proximo_prox_sensor=$res[$i+2]['sensor'];

			if($atual_estado==='L'){
				$loop=false;
			}
			$cond1 = ($atual_estado==='D' && $proximo_estado==='D');
			$cond2 = ($atual_cep == $proximo_cep);
			#$cond3 = ($atual_sensor == $proximo_sensor && $proximo_sensor == $proximo_prox_sensor);

			if($cond1 && $cond2){
				if(!$loop){
					$regs[]=$res[$i];
				}
				$loop=true;
			} else {
				#echo "Sem registros Triagem Luz";
			}
			$flags[]=$id_davez;

		}
		#print_r($regs);

		#$penultimo = $count-1;
		$ultimo = $count-1;
		$flags[]= $res[$ultimo]['id'];
		#$flags[]= $res[$penultimo]['id'];

		#print_r($flags);

		atualizaFlags($flags,'T','sensores_luz');

		if(is_array($regs)){
			saveTriagem($regs,'E');
		} else {
			echo "Sem registros triagem E";
		}

	} else {
		echo "Aguardando Novas MSGS via MQTT" . PHP_EOL;
	}

}
