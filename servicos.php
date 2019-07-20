<?php
require 'init.php';
#echo "<pre>";
#pegar o param cep

#$cep = $_GET['cep'];
$cep="24130400";

$param = $_GET['param'];
switch ($param) {
	case 'estado_agua_agora':
		#$d1="2019/02/13 11:59:10";
		$d1=date('Y/m/d H:i:s');
		#echo $d1,'<hr>';
		$data = new DateTime($d1);
		
		$hora = $data->format('H');
		$min = $data->format('i');

		$dia = $data->format('d');
		$mes = $data->format('m');
		$ano = $data->format('Y');

		$q="SELECT id, estado, status, dia_hora
		FROM sensores_agua 
		WHERE 
		cep='$cep' 
		AND day(updated_at)='$dia' 
		AND month(updated_at)='$mes' 
		AND year(updated_at)='$ano'
		ORDER BY dia_hora desc
		LIMIT 1

		;";

		#echo $q;

		$res = (new BD())->query($q);
		#print_r($res);
		
		# neste caso trocar um pelo outro
		#####################################################
		#echo json_encode($res[0],true);
		echo json_encode($res[0]['estado'],true);
		#####################################################
	break;
	
	case 'estado_agua_grafico':
		#$d1="2019/02/28";
		$d1=date('Y/m/d');

		$data1 = new DateTime($d1);
		$data_str_1 = $data1->format('Y/m/d');
		
		$data2 = $data1->modify('-7 day');
		$data_str_2 = $data2->format('Y/m/d');

		$tipo='A';
		$res = montaJSONsemanal($data_str_2,$data_str_1,$cep,$tipo);
		echo json_encode($res,true);


	break;	


	case 'estado_luz_agora':

		#$d1="2019/02/13 11:59:10";
		$d1=date('Y/m/d H:i:s');
		#echo $d1,'<hr>';
		$data = new DateTime($d1);
		
		$hora = $data->format('H');
		$min = $data->format('i');

		$dia = $data->format('d');
		$mes = $data->format('m');
		$ano = $data->format('Y');

		$q="SELECT id, estado, status, dia_hora
		FROM sensores_luz 
		WHERE 
		cep='$cep' 
		AND day(updated_at)='$dia' 
		AND month(updated_at)='$mes' 
		AND year(updated_at)='$ano'
		ORDER BY dia_hora desc
		LIMIT 1
		;";

		#echo $q;

		$res = (new BD())->query($q);
		#print_r($res);

		#####################################################
		#echo json_encode($res[0],true);
		echo json_encode($res[0]['estado'],true);
		#####################################################

	break;
	
	case 'estado_luz_grafico':
		#$d1="2019/02/28";
		$d1=date('Y/m/d');

		$data1 = new DateTime($d1);
		$data_str_1 = $data1->format('Y/m/d');
		
		$data2 = $data1->modify('-7 day');
		$data_str_2 = $data2->format('Y/m/d');

		$tipo='E';
		$res = montaJSONsemanal($data_str_2,$data_str_1,$cep,$tipo);
		echo json_encode($res,true);

	break;	

	default:
		echo "Informe um parametro";
	break;
}


# BETWEEN '2019/02/25' AND '2019/03/02'
function montaJSONsemanal($data2,$data1,$cep,$tipo){
	#echo "<pre>";
	$q="SELECT 
	DATE_FORMAT(data_hora,'%Y/%m/%d') as `data`
	
	FROM relatorios 
	WHERE (CAST(data_hora AS DATE) BETWEEN '$data2' AND '$data1') 
	AND cep='$cep'
	AND tipo='$tipo'
	ORDER BY data_hora ASC;";

	#echo $q; 
	#die;

	$res = (new BD())->query($q);

	$datas = array_column($res, 'data');
	#print_r($datas);

	$d1 = DateTime::createFromFormat('Y/m/d', $data2);
	$d2 = DateTime::createFromFormat('Y/m/d', $data1);

	$diff = $d2->diff($d1)->format("%a");	
	$dias_sem = ['D','S','T','Q','Q','S','S',];

	#echo $diff;
	$json=[];
	$data = DateTime::createFromFormat('Y/m/d', $data2);

	#echo '<hr>',$data1,'<hr>',$data2,'<hr>';
	
	$data->modify('-1 day');

	for($i=0;$i<=$diff;$i++){

		$data->modify('+1 day');
		$data_str = $data->format('Y/m/d');
		$data_str2 = $data->format('d/m');

		#print_r($data_str);
		#print_r($datas);
		#echo "<hr>";
		$json[]=
				['data'=>$data_str,
				 'dia'=> $dias_sem[date('w',strtotime($data_str))] ." ". $data_str2,
				 'caiu'=>in_array($data_str,$datas)?1:0
				 #'caiu'=>in_array($data_str,$datas)?0:1
				]; 	
	}

	#print_r($json);
	return $json;
}
