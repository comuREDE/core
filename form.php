<?

include"conecta.php";
$name = $_POST['nome'];
$email = $_POST['email'];
$celular = $_POST['celular'];
$tamname = strlen($name);
$tamcel = strlen($celular);
$cont = 0;
if($cont < 3)
{
	//NOME
	try
	 { 
		if ($name == "")
		{
			
		throw new Exception('Nome nao pode ser nulo');
			
		}
		
		if ($tamname < 3)
		{
			
		throw new Exception('Nome muito curto');
			
		}
		else 
			
		$cont+=1;
		
	} 
	catch (Exception $e)
	{
		var_dump($e->getMessage());
	}


	//EMAIl
	try
	{
		if(empty($email))
			throw new Exception('Email invalido');
		else
			$cont+=1;
	}

	catch (Exception $e)
	{
			var_dump($e->getMessage());

		
		
	}

	//Telefone
	try
	{
		if ($tamcel < 9 ||  $tamcel > 11)
		{
			
		throw new Exception('Preencha um numero dentro de 9 a 11 digitos');
			
		}
		else
			$cont+=1;

		
		
	}

	catch (Exception $e)
	{
			var_dump($e->getMessage());

		
		
	}
	
	
}if($cont ==3)
{
	$sql = "
	INSERT INTO cadastros(celular,email,nome) 
	VALUES('{$_POST['celular']}', '{$_POST['email']}','{$_POST['nome']}'
        )";
	$query = $mysqli->query($sql);
	
}
?>