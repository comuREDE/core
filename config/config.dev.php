<?php

DEFINE ('USER', 'root');
DEFINE ('HOST', '127.0.0.1');
DEFINE ('NAME', 'comurede');
DEFINE ('PASS', '123');

############################################
DEFINE ();
DEFINE ();

############################################
/*
sudo nano mariadb.conf.d/50-server.cnf

bind-address=0.0.0.0

GRANT ALL PRIVILEGES ON *.* TO 'remote'@'%' IDENTIFIED BY '712306Ma' WITH GRANT OPTION;

ALTER TABLE `comurede`.`sensores_agua` 
ADD COLUMN `sensor` INT NULL AFTER `status`;


ALTER TABLE `comurede`.`sensores_agua` 
CHANGE COLUMN `cep` `cep` VARCHAR(8) NULL DEFAULT NULL ;


update sensores_agua set cep='24130400', sensor=1 where id>0

update sensores_luz set cep='24130400', sensor=1 where id>0

ALTER TABLE `comurede`.`triagem` 
CHANGE COLUMN `cep` `cep` VARCHAR(8) NULL DEFAULT NULL ;

ALTER TABLE `comurede`.`relatorios` 
CHANGE COLUMN `cep` `cep` VARCHAR(8) NULL DEFAULT NULL ;


############# p zerar as triagens e relatorios

delete from relatorios where id>0;
delete from triagem where id>0;
update sensores_agua set status='' where id>0;
update sensores_luz set status='' where id>0;

rodar sms.php comentar agua luz alternativamente

http://localhost/trilojas/public_html/comurede/sms.php?param=A
http://localhost/trilojas/public_html/comurede/sms.php?param=E
*/