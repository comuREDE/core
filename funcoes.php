<?php

function atualizaFlags(array $flags, string $flag,string $tabela){
	$model = (new Model())->setTable($tabela);
	foreach ($flags as $k => $id) {
		$post=['status'=>$flag,'id'=>$id]; #id sempre no final
		$res = $model->upd($post);
		var_dump($res);
		echo $res?"<br>atualizou $id":"naaaaaaaaaao at $id";
	}

}

function saveTriagem(array $regs,string $tipo){
	echo "<h3>".__FUNCTION__."</h3>";
	$total = count($regs);
	print_r($regs);
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
	echo "<h2>Relatorio ". date('d/m/Y H:i:s')."</h2>";
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
	echo "<h3>!!!".__FUNCTION__."</h3>";
	$sql="SELECT id, 
	DATE_FORMAT(data_hora,'%Y/%m/%d %H:%i:%s') as data_hora,
	cep
	FROM relatorios 
	WHERE (status='' OR status IS NULL OR status <> 'SMS') 
	ORDER BY data_hora ASC;";
	$res = (new BD())->query($sql);


	print_r($res);
	$count=count($res);
	$ceps1=array_column($res, 'cep');
	$flags=array_column($res, 'id');
	$ceps = array_unique($ceps1);
	print_r($flags);
	print_r($ceps);
	#die;

	$flags_txt = implode("','",$flags);

	$ceps = implode("','",$ceps);
	$sql="SELECT DISTINCT * FROM cadastros WHERE cep IN ('$ceps');";
	#echo $sql;
	$cadastros = (new BD())->query($sql);	
	#print_r($cadastros);
	$total = count($cadastros);	
	#$flags = [];
	foreach ($cadastros as $cadastro) {
		extract($cadastro);
		$ddd = substr($celular, 0,2);
		$celular = substr($celular, 2,9);

		if($tipo=="A"){
			$header = "Olá! \n";
			$footer = "\nAPOIO\n\nXXXXXXXXXXXXXXXXXX";

		} else {
			$header = "Olá! \n";
			$footer = "\nAPOIO\n\nYYYYYYYYYYYYYYYYYY";
			
		}

		########################## SMS RAPIDO
		if((int) date('H') >= 8 and (int) date('H') < 19){
			if($tipo=="A"){
				$texto = "TÁ CAINDO ÁGUA. \n\n[# $flags_txt]";
			} else {
				$texto = "FALTOU LUZ. \n\n[# $flags_txt]";
			}	

			$msg = $header . $texto . $footer;
			enviaSMS($ddd,$celular,$msg,'rapido');
		} else {
		########################## SMS FILA
			if($tipo=="A"){
				$texto = "CAIU ÁGUA. \n\n[# $flags_txt]";
				$msg = $header . $texto . $footer;
				enviaSMS($ddd,$celular,$msg,'fila');
			} else {
				# Nao faz nada na LUZ
			}	

		}
	}

	#print_r($flags);die;
	atualizaFlags($flags,'SMS','relatorios');
	echo "<h1>SMS - ". date('d/m/Y H:i:s')."</h1>";
}

function enviaSMS($ddd,$celular,$msg,$tipo='rapido'){
	#echo "<hr>",$ddd,"<hr>",$celular,"<hr>",$msg,"<br>";
	$campos = [
		'strUsuario' => urlencode('comurede_dev'),
		'strSenha' => urlencode(''),
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