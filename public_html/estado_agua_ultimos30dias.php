<?php
	include "conecta.php";
	$EstadoAgua30Dias="";
	$EstadoAgua30Dias="SELECT * FROM sensores_luz ";
	echo $EstadoAgua30Dias;
	
	/*
	$where="";
		if(isset($_GET['busca'])){
			$busca=$_GET['busca'];
			$where.= "WHERE last_name LIKE '%$busca%' ";
			$where.= "OR first_name LIKE '%$busca%' ";
			$where.= "OR emp_no LIKE '$busca' ";
			$where.= "OR birth_date LIKE '$busca' ";
		}
	$sql.=$where."LIMIT 50";
	$sqlCount.=$where;
	
	//echo $sql;
	
	
	$q=$mysqli->query($sql);
	$qCount=$mysqli->query($sqlCount);
	
	//var_dump($qCount->fetch_array(),$sql,$sqlCount);
	
	$qtRegistros=$qCount->fetch_array();
	$qtRegistros=$qtRegistros[0];
	
	$qtPaginas=ceil($qtRegistros/50);
	
		echo "<form method='GET'>";
		echo "<input type='text' name='busca' value='".(isset($_GET['busca'])?$_GET['busca']:"")."'>";
		echo "<input type='submit' value='Buscar'>";
		echo "</form>";
		echo "Foram encontrados ".$qtRegistros." registros.<br>";
		echo "Exibindo página 1 de ".$qtPaginas.".<br>";
		echo "Foram encontrados $q->num_rows registrados. <br>";
		
	echo "<table border=1>";
	echo "<tr>";
	echo "<th> ID </th>";
	echo "<th> Sobrenome </th>";
	echo "<th> Nome </th>";
	echo "<th> Sexo </th>";
	echo "<th> Data de Nascimento </th>";
	echo "</tr>";
			while($dados=$q->fetch_array()){
				echo "<tr>";
				echo "<td>" . $dados['emp_no'] . "</td>";
				echo "<td>" . $dados['last_name'] . "</td>";
				echo "<td>" . $dados['first_name'] . "</td>";
				echo "<td>" . $dados['gender'] . "</td>";
				echo "<td>" . $dados['birth_date'] . "</td>";
				echo "</tr>";
	}
	echo "</table>";
	*/
?>
