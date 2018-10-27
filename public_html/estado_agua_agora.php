<html>
<head>
  <meta http-equiv="refresh" content="1.5">
</head>
<body>


<?php
	include "conecta.php";

        $sql="SELECT * FROM sensores_agua ORDER BY dia_hora DESC LIMIT 1 ";

        $q = $mysqli->query($sql);

        $estadoAguaAgora = $q->fetch_array();

	$estadoAguaAgora = $estadoAguaAgora['estado'];

		if ($estadoAguaAgora == "D") echo "<img src='imagens/vermelho_200x200.png'>";
        	else echo "<img src='imagens/azul_200x200.png'>";
                ?>

</body>
</html>


