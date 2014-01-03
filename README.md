ipcamera_webui
==============

A very simple bootstrap, javascript and PHP app for viewing and managing multiple IP cameras.  Integration with Motion coming soon!

raspian install instructions

sudo apt-get install apache2

sudo apt-get install mysql-server

sudo mysql_secure_installation

sudo apt-get install php5 php-pear php5-mysql

sudo a2enmod rewrite

sudo nano /etc/apache2/sites-available/default

<Directory /var/www/>
	Options Indexes FollowSymLinks MultiViews
	AllowOverride All
	Order allow,deny
	allow from all
</Directory>

sudo service apache2 restart

sudo apt-get install git

cd /var/www

sudo mkdir cameras

sudo git clone https://github.com/jamespcole/ipcamera_webui.git cameras

sudo apt-get install motion