<?php 

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

#if(!session_id()){
#	session_start();
#}

