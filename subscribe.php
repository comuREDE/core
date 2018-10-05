<?php

include "conecta.php";

require("phpMQTT.php");

$mqtt = new phpMQTT("10.62.63.50", 1883, "phpMQTT"); //Change client name to something unique

if(!$mqtt->connect()){
        exit(1);
}


$topics['AGUA'] = array("qos"=>0, "function"=>"procmsg_agua");
$topics['LUZ'] = array("qos"=>0, "function"=>"procmsg_luz");
$mqtt->subscribe($topics,0);



while($mqtt->proc()){

}


$mqtt->close();

function procmsg_luz($topic,$msg){
                global $mysqli;
                echo "Msg Recebida: ".date("r")."\nTopic:{$topic}\n$msg\n";
                $data = date("Y-m-d H:i:s");
                $sql = "INSERT INTO sensores_luz VALUES(null, '$data', '$msg', null)";
                echo $sql . "\n";
                $result =  $mysqli->real_query($sql);
                var_dump($result);
}


function procmsg_agua($topic,$msg){
                global $mysqli; 
                echo "Msg Recebida: ".date("r")."\nTopic:{$topic}\n$msg\n";
                $data = date("Y-m-d H:i:s");
                $sql = "INSERT INTO sensores_agua VALUES(null, '$data', '$msg', null)";
                echo $sql . "\n"; 
                $result =  $mysqli->real_query($sql);
                var_dump($result);
}

?>
