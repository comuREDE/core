<?php

include "conecta.php";

require("phpMQTT.php");

$mqtt = new phpMQTT("192.168.43.252", 1883, "phpMQTT"); //Change client name to something unique

if(!$mqtt->connect()){
        exit(1);
}

/* $topics['ferries/IOW/#'] = array("qos"=>0, "function"=>"procmsg");*/
$topics['TESTE'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);



while($mqtt->proc()){

}


$mqtt->close();

function procmsg($topic,$msg){
                global $mysqli; 
                echo "Msg Recebida: ".date("r")."\nTopic:{$topic}\n$msg\n";
                $data = date("Y-m-d H:i:s");
                $sql = "INSERT INTO sensores_luz VALUES(null, '$data', '$msg', null)";
                echo $sql . "\n"; 
                $result =  $mysqli->real_query($sql);
                var_dump($result);
}



?>
