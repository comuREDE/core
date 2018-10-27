<?php
	$mysqli=new mysqli("localhost","admin","comurede","prototipo_funcional");
        if($mysqli->connect_errno)
        {
                echo "Falha de conexão ao MySQL:(" . $mysqli->connect_errno . ")" . $mysqli->connect_error;
        echo $mysqli->host_info . "\n";
       }

?>
