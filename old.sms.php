<?php

require("init.php");
echo "<pre>";

#principal_E();
#principal_A();
while(1){
	inicio:
	echo "\n----------- agua -----------<br>";
	principal_A();
	echo "\n----------- luz (Energ) -----------<br>";
	principal_E();
	echo "final de um ciclo<br>";
	sleep(60);
	goto inicio;	
}


function principal_A(){
	echo "\n<h1>inicio - ".date('d/m/Y H:i:s')."</h1>";
	filtroPrimarioAgua();
	#sleep(120);
	echo "\n<h1>passou pelo primario - ".date('d/m/Y H:i:s')."</h1>";
	filtroSecundario('A');
	#sleep(60);
	echo "\n<h1>passou pelo secundario - ".date('d/m/Y H:i:s')."</h1>";
	alertaSMS('A');
	echo "\n<h1>recebeu os sms - ".date('d/m/Y H:i:s')."</h1>";
	#sleep(30);
	echo "\n<h1>fim Agua - ".date('d/m/Y H:i:s')."</h1>";
}
####################################################
####################################################


function principal_E(){
	echo "\n<h1>inicio - ".date('d/m/Y H:i:s')."</h1>";
	filtroPrimarioLuz();
	#sleep(120);
	echo "\n<h1>passou pelo primario - ".date('d/m/Y H:i:s')."</h1>";
	filtroSecundario('E');
	#sleep(60);
	echo "\n<h1>passou pelo secundario - ".date('d/m/Y H:i:s')."</h1>";
	alertaSMS('E');
	echo "\n<h1>recebeu os sms - ".date('d/m/Y H:i:s')."</h1>";
	#sleep(30);
	echo "\n<h1>fim da luz - ".date('d/m/Y H:i:s')."</h1>";
}


function filtroPrimarioAgua(){
	echo "<h3>".__FUNCTION__."</h3>";

	$q="SELECT id, dia_hora,
	DATE_FORMAT(dia_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	status,created_at, updated_at,
	estado,cep,sensor, day(dia_hora) as dia
	FROM sensores_agua 
	WHERE (status='' OR status IS NULL OR status <> 'T') 
	ORDER BY dia_hora ASC 
	;";

	#echo $q;

	$res = (new BD())->query($q);

	$loop=false;
	$regs=[];
	$flags=[];
	$count=count($res);
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
	atualizaFlags($flags,'T','sensores_agua');

	if(is_array($regs)){
		saveTriagem($regs,"A");
	} else {
		echo "Sem registros Triagem Agua";
	}
	echo "<h1>Triagem Agua ". date('d/m/Y H:m:i')."</h1>";
}


function atualizaFlags(array $flags, string $flag,string $tabela){
	$model = (new Model())->setTable($tabela);
	foreach ($flags as $k => $id) {
		$post=['status'=>$flag,'id'=>$id]; #id sempre no final
		$res = $model->upd($post);
		var_dump($res);
		echo $res?"<br>atualizou $id":"naaaaaaaaaao at $id";
	}

}


function filtroPrimarioLuz(){
	echo "<h3>".__FUNCTION__."</h3>";

	$sql="SELECT id, dia_hora,
	DATE_FORMAT(dia_hora,'%Y/%m/%d %H:%i:%s') as data_hora,

	status,estado,cep,sensor, day(dia_hora) as dia

	FROM sensores_luz 
	WHERE (status='' OR status IS NULL OR status <> 'T') 
	ORDER BY dia_hora ASC 

	;";
	$res = (new BD())->query($sql);

	#print_r($res);die;
	$loop=false;
	$regs=[];
	$flags=[];
	$count=count($res);
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
	atualizaFlags($flags,'T','sensores_luz');

	if(is_array($regs)){
		saveTriagem($regs,'E');
	} else {
		echo "Sem registros triagem E";
	}
	echo "<h1>Triagem Luz ". date('d/m/Y H:m:i')."</h1>";
}

function saveTriagem(array $regs,string $tipo){
	echo "<h3>".__FUNCTION__."</h3>";
	$total = count($regs);
	#print_r($regs);die;
	foreach ($regs as $k => $reg) {
		extract($reg);
		$sensores_id = $id;
		$info = compact('sensores_id','data_hora','cep','sensor','tipo');
				
		$res = (new Model())->setTable('triagem')->save($info);
		var_dump($res);
	}
	echo "<h3>$total registros ($tipo) upds em triagem</h3>";
}

function filtroSecundario(string $tipo){
	echo "<h3>".__FUNCTION__."</h3>";

	$sql="SELECT id, 
	DATE_FORMAT(data_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	DATE_FORMAT(data_hora,'%d/%m/%Y') as diames,
	status,sensores_id,
	cep,sensor
	FROM triagem 
	WHERE (status='' OR status IS NULL OR status <> 'R') 
	AND tipo='$tipo'
	ORDER BY data_hora ASC;";
	$res = (new BD())->query($sql);

	$relats=[];
	$flags=[];
	$diames = array_column($res, "diames");
	$diames2 = array_unique($diames); #preserva a chave
	$count=count($res);
	for($j=0; $j<$count; $j++){
		$flags[] = $res[$j]['id'];
		if(array_key_exists($j, $diames2)){
			$relats[]=$res[$j];
		}
	}

	atualizaFlags($flags,'R','triagem');
	if(is_array($relats)){
		saveRelatorios($relats,$tipo);
	} else {
		echo "sem registros Relatorio";
	}
	echo "<h2>Relatorio ". date('d/m/Y H:m:i')."</h2>";
}

function saveRelatorios(array $relats, string $tipo){
	echo "<h3>".__FUNCTION__."</h3>";
	$total = count($relats);
	foreach ($relats as $k => $relat) {
		extract($relat);
		$triagem_id = $id;
		$info = compact('triagem_id','data_hora','cep','sensor','tipo');
				
		$res = (new Model())->setTable('relatorios')->save($info);
		var_dump($res);
	}
	echo "<h3>$total registros upds em relatorios</h3>";
}

function alertaSMS($tipo){
	echo "<h3>".__FUNCTION__."</h3>";
	$sql="SELECT id, 
	DATE_FORMAT(data_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	cep
	FROM relatorios 
	WHERE (status='' OR status IS NULL OR status <> 'SMS') 
	ORDER BY data_hora ASC;";
	$res = (new BD())->query($sql);

	#print_r($res);
	$count=count($res);
	$ceps1=array_column($res, 'cep');
	$flags=array_column($res, 'id');
	$ceps = array_unique($ceps1);
	#print_r($ceps);

	$ceps = implode("','",$ceps);
	$sql="SELECT DISTINCT * FROM cadastros WHERE cep IN ('$ceps');";
	#echo $sql;
	$cadastros = (new BD())->query($sql);	
	#print_r($cadastros);
	$total = count($cadastros);	
	#$flags = [];
	foreach ($cadastros as $cadastro) {
		extract($cadastro);
		$msg="##caiu $tipo";
		$ddd = substr($celular, 0,2);
		$celular = substr($celular, 2,9);
		$assunto = "##assunto caiu $tipo";

		$header = "##header caiua $tipo<br>";
		$footer = "##footer $tipo<br>";
		#echo "enviaSMS - $id - $nome $celular<br>";
		if((int) date('H') != 8 and (int) date('H') != 19){
			$texto = "##vai la ver agora q --- $tipo";
			$msg = $header . $texto . $footer;
			enviaSMS($ddd,$celular,$msg,'rapido');
		} else {
			$texto = "##caiu agua essa noite --- $tipo";
			$msg = $header . $texto . $footer;
			enviaSMS($ddd,$celular,$msg,'fila');
			#enviaSMS($ddd,$celular,$msg,'rapido');
		}
	}

	#print_r($flags);die;
	atualizaFlags($flags,'SMS','relatorios');
	echo "<h1>SMS - ". date('d/m/Y H:m:i')."</h1>";
}

function enviaSMS($ddd,$celular,$msg,$tipo='rapido'){
	#echo "<hr>",$ddd,"<hr>",$celular,"<hr>",$msg,"<br>";
	$campos = [
		'strUsuario' => urlencode('comurede_dev'),
		'strSenha' => urlencode(SENHA),
		'intDDD' => urlencode($ddd),
		'intCelular' => urlencode($celular),
		'memMensagem' => urlencode($msg),
		'sem_retorno' => urlencode('sim'),  //Não Altere este Campo
		'sms_marketing' => urlencode('sim')  //Não Altere este Campo
	];

	$urlSMS = 'http://www.phpsms.com.br/sms/';
	$urlSMS .= ($tipo=="rapido")?'envio_sms_rapido.asp' : 'envio_sms_long.asp';
	#$string_campos = http_build_query($campos);
		
	#echo $tipo,'<hr>',$urlSMS,'<hr>';
	$string_campos = '';
	foreach($campos as $name => $valor) :
		$string_campos .= $name.'='.$valor.'&';
	endforeach;
	$string_campos = rtrim($string_campos,'&');
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $urlSMS);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_POST,count($campos));
	curl_setopt($ch, CURLOPT_POSTFIELDS,$string_campos);
	$result = curl_exec($ch);
	echo($result); //Retorna ENVIADO se Enviado com Sucesso!
	curl_close($ch);
	
}