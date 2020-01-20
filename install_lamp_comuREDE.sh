#!/usr/bin/bash

REMOTE_NAME="remote";
REMOTE_PASSWORD="remote";
DB_NAME="comurede_dev";

#UPDATE REPOS
sudo apt-get update -y;

#UPDATE INSTALLED PACKAGES
sudo apt-get upgrade -y;

#INSTALL APACHE
sudo apt-get install apache2 git -y;
sudo systemctl enable apache2.service;
sudo systemctl start apache2.service;

#INSTALL MARIADB
sudo apt-get install mariadb-server -y;

#EDIT CONF FILE
sudo sed -i 's/bind-address.*127.0.0.1/#bind-address = 127.0.0.1/g' /etc/mysql/mariadb.conf.d/50-server.cnf;
#RESTART MARIADB SERVICE
sudo /etc/init.d/mysql stop && sudo /etc/init.d/mysql start;

#REMOVE, IF ANY, PHP PACKAGE
sudo apt-get remove '^php.*' -y;
sudo apt -y install lsb-release apt-transport-https ca-certificates;
sudo wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg;
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php7.3.list;
sudo apt update;
sudo apt-get install php7.1 php7.1-cli php7.1-curl php7.1-json php7.1-opcache php7.1-common php7.1-readline -y;
#sudo apt-get install php7.1 php7.1-cli php7.1-curl php7.1-json php7.1-opcache php7.1-common php7.1-readline php7.1-mysql -y;

#SQL COMMANDS
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION";
sudo mysql -u root -e "create user $REMOTE_NAME identified by '$REMOTE_PASSWORD'";
sudo mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO '$REMOTE_NAME'@'%' IDENTIFIED BY '$REMOTE_PASSWORD' WITH GRANT OPTION";
sudo mysql -u root -e "create database $DB_NAME";
echo ""
echo ""
echo ""
echo "****************************************************"
echo "**************** APACHE VERSION ********************"
echo "****************************************************"
sudo apache2 -v;
echo ""
echo ""
echo ""
echo "****************************************************"
echo "**************** MARIA-DB VERION *******************"
echo "****************************************************"
sudo mariadb -V;
echo ""
echo ""
echo ""
echo "****************************************************"
echo "**************** PHP VERSION ***********************"
echo "****************************************************"
sudo php -v;
echo ""
echo ""
echo ""
#cd /var/www/html/ && sudo rm -rf * && sudo git clone https://github.com/comuREDE/core.git . && echo "***COMU REDE CLONADO COM SUCESSO***"
cd /var/www/html/ && sudo rm -rf * && sudo git clone https://github.com/comuREDE/core.git . && echo "***COMUREDE CLONADO COM SUCESSO***"
echo ""
echo ""
echo ""
cat /etc/mysql/mariadb.conf.d/50-server.cnf | grep "bind-address"
