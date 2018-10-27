<?php 
	$mysqli=new mysqli("127.0.0.1","root","712306Ma","prototipo_funcional");
        if($mysqli->connect_errno)
        {
                echo "Falha de conexão ao MySQL:(" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
        echo $mysqli->host_info . "\n";
       }

?>
