<?php


//Setar timezone para Brasil
date_default_timezone_set('america/sao_paulo');

error_reporting(E_ALL);
ini_set('display_startup_errors',TRUE);
ini_set('display_errors',TRUE);
set_time_limit(0); #ignore
ini_set('memory_limit','128M');
setlocale(LC_ALL,'pt_BR');


@require 'config/config.prod.php';


require "core/pdo.php";
require "core/phpMQTT.php";
require "core/PHPMailer.php";
require "core/SMTP.php";

//Chamada ao banco de dados para obter dados de forma mais segura
$s = (new BD())->query("SELECT dado FROM sistema WHERE info = 'sms_s'");
//var_dump($s[0]['dado']);
DEFINE('SMS_S' , $s[0]['dado']);

#if(!session_id()){
#	session_start();
#}
