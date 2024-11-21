<?php


//Setar timezone para Brasil
date_default_timezone_set('america/sao_paulo');

//error_reporting(E_ALL);
//error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL ^ E_WARNING);
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

//Chamada ao banco de dados para obter dados de forma mais segura
$s2 = (new BD())->query("SELECT dado FROM sistema WHERE info = 'email_s'");
//var_dump($s[0]['dado']);
DEFINE('SMS_S' , $s[0]['dado']);
DEFINE('EMAIL_S', $s2[0]['dado']);

#if(!session_id()){
#	session_start();
#}
