<html>
<head>
  <meta http-equiv="refresh" content="3">
</head>
<body>


<?php
	include "conecta.php";

        $sql="SELECT * FROM sensores_luz ORDER BY dia_hora DESC LIMIT 1 ";

        $q = $mysqli->query($sql);

        $estadoLuzAgora = $q->fetch_array();

	$estadoLuzAgora = $estadoLuzAgora['estado'];

        if ($estadoLuzAgora == "D") echo "<img src='imagens/vermelho_200x200.png'>";
        else echo "<img src='imagens/azul_200x200.png'>";



?>
</body>
</html>


