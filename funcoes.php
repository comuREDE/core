<?php

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);


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


//Funcao que reseta os envios de anuncios
function resetAnuncios()
{
    $rstAnuncios = (new BD())->resetAnuncios();
    return $rstAnuncios;
}

//Função que chama na model do database a função para alterar o status 'enviado' da tabela relatorios para '1'
function setAnuncioEnviado($id_anuncio)
{
    $updateAnuncio = (new BD())->updateAnuncioStatus($id_anuncio);
    return $updateAnuncio;
}

//Função que chama na model do database a função para alterar o status 'enviado' da tabela relatorios para '1'
function setAnuncioDataEnviado($id_anuncio)
{
    $updateAnuncio = (new BD())->updateAnuncioDataEnvio($id_anuncio);
    return $updateAnuncio;
}

//funcao para validar se a chamada para o alertaSMS deve ser realizada
//function validaEnvioSMS()
//{
///////////Recupera os cadastros da tabela
//    $sql = "SELECT ID, DATE_FORMAT(DATA_HORA,'%Y/%m/%d %H:%i:%s') AS DATA_HORA, CEP
//	FROM RELATORIOS
//	WHERE (STATUS='' OR STATUS IS NULL OR STATUS <> 'SMS')
//	ORDER BY DATA_HORA ASC;";
//    $res = (new BD())->query($sql);
//    $count = count($res);
//    $ceps1 = array_column($res, 'cep');
//    $flags = array_column($res, 'id');
//    $ceps = array_unique($ceps1);
//    $flags_txt = implode("','", $flags);
//    //var_dump($flags_txt);
//    $ceps_txt = implode("','", $ceps1);
//    $ceps = implode("','", $ceps);
//////////fim coleta cadastros tabela
//
//    //Verifica se houve envio de SMS de água hoje
//    $aguaHoje = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.tipo = 'A' AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");
//
//    if(count($aguaHoje) <= 0){
//        alertaSMS('A');
//    }
//
//    //Verifica se houve envio de SMS de luz hoje
//    $luzHoje = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.tipo = 'E' AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");
//    if(count($luzHoje) <= 0){
//        alertaSMS('E');
//    }
//
//
//
//
//}


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

	if($total > 0) {
		echo 'Total de Cadastros deste CEP: ' . $total . PHP_EOL;
	}
	

	$counter = 0;
	$runSetSMS = true;

	// ***

	while($counter < $total){

	foreach ($cadastros as $cadastro) {
		extract($cadastro);
		$ddd = substr($celular, 0,2);
		$celular = substr($celular, 2,9);


		//$envioRelatorio = (new BD())->query("SELECT DISTINCT r.cep, r.status, r.sensor, r.updated_at, r.data_envio FROM relatorios r WHERE sensor = 99 AND status = 'SMS' OR status = '0' AND r.cep IN ('$ceps') ORDER BY r.data_envio DESC");
		//$prazoEnvio = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) < (SELECT DATE_SUB(NOW(), INTERVAL 1 DAY)) AND r.enviado = 1 AND r.cep IN ('$ceps')");

		// Verifica se o CEP que tem em $ceps se encontra na tabela triagem com status R - sensor 99 setado para melhorar validacao
		$cepTriagem = (new BD())->query("SELECT DISTINCT t.cep, t.status, t.sensor, t.updated_at FROM triagem t WHERE sensor = 99 AND status = 'R' AND cep IN ('$ceps')");

		// Verifica se o CEP que tem em $ceps se encontra na tabela relatorios com status SMS - sensor 99 setado para melhorar validacao
		//$cepRelatorio = (new BD())->query("SELECT DISTINCT r.cep, r.status, r.sensor, r.updated_at FROM relatorios r WHERE sensor = 99 AND status = 'SMS' AND r.cep IN ('$ceps')");

		//$envioAnterior = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) <= SUBDATE(CURDATE(),1) AND r.enviado = 1 AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

		//$envioHoje =     (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

		$sms_on = (new BD())->query("SELECT s.dado FROM sistema s WHERE info = 'sms_on'");
		$sms_ligado = $sms_on[0]['dado'];

        $demo_on = (new BD())->query("SELECT s.dado FROM sistema s WHERE info = 'demo_on'");
        $demo_ligado = $demo_on[0]['dado'];

        $dadosAnunciante = (new BD())->query("SELECT a.id AS id_anunciante, a.nome AS anunciante, a.cep AS cep_anunciante,
                                                  an.id AS id_anuncio, an.texto AS texto, an.cep AS cep_anuncio, an.enviado AS enviado, an.data_cadastro AS data_cadastro, data_envio AS data_envio
                                                    FROM anunciantes a
                                                  JOIN anuncios an ON a.id = an.id_anunciante
                                                  WHERE an.cep = ".$cepTriagem[0]['cep']." AND an.enviado = 0
                                                  ORDER BY data_cadastro ASC
                                                  LIMIT 1");
		// echo $sms_ligado.PHP_EOL;

//		****** USAR ESTE BLOCO PARA VALIDAR SMS A ou E.

        //Verifica se houve envio de SMS de água hoje
        $aguaHoje = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.tipo = 'A' AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

        //Verifica se houve envio de SMS de luz hoje
        $luzHoje = (new BD())->query("SELECT r.cep, r.data_envio, r.enviado FROM relatorios r WHERE DATE(r.data_envio) = DATE(NOW()) AND r.enviado = 1 AND r.tipo = 'E' AND r.cep IN('$ceps') ORDER BY r.data_envio DESC LIMIT 1");

        //Seta a variável de envio de SMS hoje para ambos os tipos (A | E)
        //if(( count($aguaHoje) > 0  && $tipo == 'A' )  && count($luzHoje) > 0){

        if( ( count($aguaHoje) <= 0  && $tipo == 'A' ) && (count($luzHoje) > 0 && $tipo == 'E') ){

            $envioHoje = false;

        }elseif (( count($aguaHoje) > 0  && $tipo == 'A' ) && (count($luzHoje) <= 0 && $tipo == 'E') ) {

            $envioHoje = false;

        }elseif ( ( count($aguaHoje) <= 0 && $tipo == 'A' ) && (count($luzHoje) <= 0 && $tipo == 'E') ){

            echo 'Nenhum tipo enviado hoje'.PHP_EOL;
            $envioHoje = false;

        }elseif ( ( count($aguaHoje) <= 0 && $tipo == 'A' ) || (count($luzHoje) <= 0 && $tipo == 'E') ){

            echo 'Apenas um tipo enviado hoje'.PHP_EOL;
            $envioHoje = false;

        }
        else{

            echo 'Ambos já enviados'.PHP_EOL;
            $envioHoje = true;
        }


		//se luz enviado, envia agua. Se agua enviado, envia luz. Se enviado agua e luz, nao envia.
		
		
        //Checa se há anuncios na fila, senao, zera a fila para recomeçar

        $anuncioFila = (new BD())->query("SELECT id FROM anuncios WHERE enviado = 0");
        if(count($anuncioFila) == 0){
            resetAnuncios();
        }




		// Se o retorno do count de CEP for maior que 0, significa que está nas duas tabelas e já foi enviado.
		//if( (count($envioHoje) > 0) && ($sms_ligado == '1')){
		if( $envioHoje == true && $sms_ligado == '1'){

			echo "CEP: " . $cepTriagem[0]['cep'] . " JÁ FOI notificado - Aguardando novos registros.".PHP_EOL;
			atualizaFlags($flags, 'SMS', 'relatorios');
			$runSetAnuncioEnviado = false;

		//}elseif ( count($envioHoje) <= 0 && ($sms_ligado == '1')){
		}elseif ( $envioHoje == false && $sms_ligado == '1' ){

			$textoFooter = "\nApoio: ".$dadosAnunciante[0]['anunciante']."\n".$dadosAnunciante[0]['texto'] ."\n" ;

            //echo $textoFooter;
            echo "CEP: " . $cepTriagem[0]['cep'] . " NÃO FOI notificado - SMS será enviado!" . PHP_EOL;
            
            if($demo_ligado == '0'){
                 $runSetAnuncioEnviado = true;
		      }else{
				  $runSetAnuncioEnviado = false;
			  }
			  
			if($tipo=="A"){
				$header = "Ola! \n Ta caindo Agua! \n[$flags_txt] \n";
				$footer = $textoFooter;

			} else {
                $header = "Ola! Faltou Luz! \n[$flags_txt]\n";
                $footer = $textoFooter;
			}

	// Checagem de horários permitidos  -- descomentar em produção: if((int) date('H') >= 8 and (int) date('H') < 19){
			if (true) {

				if($tipo=="A"){
					$texto = " \n \n comuREDE \n\n " ;
				} else {
					$texto = " \n \n comuREDE \n\n " ;
				}

				$msg = $header . $texto . $footer;
				enviaSMS($ddd,$celular,$msg,'rapido');
				//echo $celular.PHP_EOL;
				atualizaFlags($flags, 'SMS', 'relatorios');
				//setSMSEnviado();

			} else {
			########################## SMS FILA - Fora do horario de 8 as 19
				if($tipo=="A"){
					$texto = "CAIU ÁGUA. \n comuREDE \n\n";
					$msg = $header . $texto . $footer;
					enviaSMS($ddd,$celular,$msg,'fila');
					atualizaFlags($flags, 'SMS', 'relatorios');
                    $runSetAnuncioEnviado = true;

                } else {
					# Nao faz nada na LUZ
				}	

			}
		}else{
			
			echo 'SMS Desligado - Verifique a configuração.'.PHP_EOL;

		}


		//Contador de envios de SMS para um CEP específico
		$counter++;
		//echo 'Contador após loooping: '.$counter.PHP_EOL;

		if( ($counter >= $total) ){

		    if($demo_ligado == '0'){
                setSMSEnviado();
            }

		    if($runSetAnuncioEnviado == true){

                $id_anuncio = $dadosAnunciante[0]['id_anuncio'];
                setAnuncioEnviado($id_anuncio);
                setAnuncioDataEnviado($id_anuncio);
            }

            //echo 'SET SMS ENVIADO()'.PHP_EOL;
		}

			//$tipo retorna A ou E de acordo com o tipo de MQTT recebido
		//var_dump($tipo);

		}

	}
	
	}

// Informacoes do gateway de SMS
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

