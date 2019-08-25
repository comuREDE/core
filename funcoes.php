<?php

// funcao chamada pelo sms_a.php ao atualizar o status dos sensores para T e a tabela relatorio para SMS
function atualizaFlags(array $flags, string $flag,string $tabela){
	$model = (new Model())->setTable($tabela);
	foreach ($flags as $k => $id) {
		//echo $tabela;
		$post=['status'=>$flag,'id'=>$id]; #id sempre no final
		//var_dump($post);
		$res = $model->upd($post);
	}

}

//funcao chamada pelo sms_a após triagem
function saveTriagem(array $regs,string $tipo){
	$total = count($regs);
	foreach ($regs as $k => $reg) {
		extract($reg);
		$sensores_id = $id;
		$info = compact('sensores_id','data_hora','cep','sensor','tipo');
		$res = (new Model())->setTable('triagem')->save($info);
	}
}

function saveRelatorios(array $relats, string $tipo)
{
	$total = count($relats);
	foreach ($relats as $k => $relat) {
		extract($relat);
		$triagem_id = $id;
		$data_envio = date('Y-m-d H:i:s');
		$info = compact('triagem_id', 'data_hora', 'cep', 'sensor', 'tipo', 'data_envio');
		$res = (new Model())->setTable('relatorios')->save($info);
	}
}


function filtroSecundario(string $tipo){
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
}

//Função que chama na model do database a função para alterar o status 'enviado' da tabela relatorios para '1'
function setSMSEnviado()
{
	$updateSMS = (new BD())->updateSMSStatus();
	return $updateSMS;
}


// chamada pelo sms_a.php após ciclos de filtros primarios e secundarios
function alertaSMS($tipo){
	$sql="SELECT id, DATE_FORMAT(data_hora,'%Y/%m/%d %H:%i:%s') as data_hora, cep
	FROM relatorios 
	WHERE (status='' OR status IS NULL OR status <> 'SMS') 
	ORDER BY data_hora ASC;";

	$res = (new BD())->query($sql);	

	$count=count($res);
	$ceps1=array_column($res, 'cep');
	$flags=array_column($res, 'id');
	$ceps = array_unique($ceps1);

	$flags_txt = implode("','",$flags);
	//var_dump($flags_txt);
	$ceps_txt = implode("','", $ceps1);

	$ceps = implode("','",$ceps);

	//******POSSIVEL LOCAL DO INICIO DO BUG - CRIAR JOIN PARA VALIDAR DADOS QUE JA FORAM ENVIADOS ? */
	$sql = "SELECT DISTINCT cad.id, cad.celular, cad.email, cad.nome, cad.cep, rel.status, rel.cep
			FROM cadastros AS cad
			JOIN relatorios AS rel ON cad.cep = rel.cep
			WHERE rel.cep IN ('$ceps') AND rel.status <> 'SMS';";
	
	$cadastros = (new BD())->query($sql);	
	$total = count($cadastros);

	#$flags = [];
	foreach ($cadastros as $cadastro) {
		extract($cadastro);
		$ddd = substr($celular, 0,2);
		$celular = substr($celular, 2,9);

		// Verifica se o CEP que tem em $ceps se encontra na tabela triagem com status R - sensor 99 setado para melhorar validacao
		$cepTriagem = (new BD())->query("SELECT DISTINCT t.cep, t.status, t.sensor, t.updated_at FROM triagem t WHERE sensor = 99 AND status = 'R' AND cep IN ('$ceps')");

		// Verifica se o CEP que tem em $ceps se encontra na tabela relatorios com status SMS - sensor 99 setado para melhorar validacao
		$cepRelatorio = (new BD())->query("SELECT DISTINCT r.cep, r.status, r.sensor, r.updated_at FROM relatorios r WHERE sensor = 99 AND status = 'SMS' AND r.cep IN ('$ceps')");

		//$envioRelatorio = (new BD())->query("SELECT DISTINCT r.cep, r.status, r.sensor, r.updated_at, r.data_envio FROM relatorios r WHERE sensor = 99 AND status = 'SMS' OR status = '0' AND r.cep IN ('$ceps') ORDER BY r.data_envio DESC");

		//$prazoEnvio = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) < (SELECT DATE_SUB(NOW(), INTERVAL 1 DAY)) AND r.enviado = 1 AND r.cep IN ('$ceps')");


		$envioAnterior = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) <= SUBDATE(CURDATE(),1) AND r.enviado = 1 AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

		$envioHoje = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

		$sms_on = (new BD())->query("SELECT s.dado FROM sistema s WHERE info = 'sms_on'");
		$sms_status = $sms_on[0]['dado'];

		// Se o retorno do count de CEP for maior que 0, significa que está nas duas tabelas e já foi enviado.
		if( (count($cepTriagem) > 0) && count($cepRelatorio) > 0 && count($envioHoje) > 0 && $sms_status == '1'){
			
			echo "CEP: " . $cepTriagem[0]['cep'] . " JÁ FOI notificado - Aguardando novos registros.".PHP_EOL;
			atualizaFlags($flags, 'SMS', 'relatorios');
			

		}elseif ( (count($cepTriagem) <= 0) || count($cepRelatorio) <= 0 || ( count($envioAnterior) > 0 && count($envioHoje) <= 0 )  && $sms_status == '1'){
			
			echo "CEP: " . $cepTriagem[0]['cep'] . " NÃO FOI notificado - SMS será enviado!" . PHP_EOL;
	
			if($tipo=="A"){
				$header = "Morador do CEP: [# $ceps_txt ] ";
				$footer = "Apoio: Red Bull";

			} else {
				$header = "Olá! \n";
				$footer = "\nAPOIO\n\nYYYYYYYYYY";	
			}

			########################## SMS RAPIDO
	// Checagem de horários permitidos  -- descomentar em produção: if((int) date('H') >= 8 and (int) date('H') < 19){
			if (true == true) {

				if($tipo=="A"){
					$texto = " Tá caindo água! " . date('d/m/y - H:i:s').PHP_EOL;
				} else {
					$texto = "FALTOU LUZ. \n\n[# $flags_txt]";
				}	

				$msg = $header . $texto . $footer;
				enviaSMS($ddd,$celular,$msg,'rapido');
				atualizaFlags($flags, 'SMS', 'relatorios');
				setSMSEnviado();

			} else {
			########################## SMS FILA
				if($tipo=="A"){
					$texto = "CAIU ÁGUA. \n\n[# $flags_txt]";
					$msg = $header . $texto . $footer;
					enviaSMS($ddd,$celular,$msg,'fila');
					atualizaFlags($flags, 'SMS', 'relatorios');
					setSMSEnviado();
					
				} else {
					# Nao faz nada na LUZ
				}	

			}
		}else{
			
			echo 'SMS Desligado - Verifique a configuração.'.PHP_EOL;

			}
		}
	}
		
// monta o corpo do SMS, pega os dados do cadastro do CEP a ser enviado, 
	function enviaSMS($ddd,$celular,$msg,$tipo='rapido'){
	$campos = [
		'strUsuario' => urlencode('comurede_dev'),
		'strSenha' => urlencode(SMS_S),
		'intDDD' => urlencode($ddd),
		'intCelular' => urlencode($celular),
		'memMensagem' => urlencode($msg),
		'sem_retorno' => urlencode('sim'),  //Não Altere este Campo
		'sms_marketing' => urlencode('sim')  //Não Altere este Campo
	];

	$urlSMS = 'http://www.phpsms.com.br/sms/';
	$urlSMS .= ($tipo=="rapido")?'envio_sms_rapido.asp' : 'envio_sms_long.asp';
		
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
	curl_close($ch);
	
}