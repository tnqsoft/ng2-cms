server
======

A Symfony project created on January 5, 2017, 3:51 pm.

1) Setup
2) Command

## 1. Setup
Download and run composer
```
composer update
```

### Gen RSA Key on Windows by Xampp
```
mkdir -p var/jwt # For Symfony3+, no need of the -p option
D:\xampp\apache\bin\openssl.exe genrsa -out var/jwt/private.pem -aes256 4096
D:\xampp\apache\bin\openssl.exe rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```

### Gen RSA Key on Linux
```
mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```

Migrations data example
customer / 123456
admin / 123456
```
php bin/console doctrine:migrations:migrate 20160923141633
```

### Virtual Host
```
<VirtualHost *:80>
    ServerName ng2-cms-api.local
    ServerAlias ng2-cms-api.local
	SetEnv sfEnv dev

	#For Linux
    DocumentRoot /home/nntuan/labs/ng2-cms/server/web
	#For Windows
    #DocumentRoot D:/projects-3/ng2-cms/server/web

	#For Linux
    <Directory /home/nntuan/labs/ng2-cms/server/web>
	#For windows
    #<Directory D:/projects-3/ng2-cms/server/web>
        #Options Indexes FollowSymLinks
        AllowOverride all
        Require all granted
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
            RewriteCond %{HTTP:Authorization} ^(.*)
            RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
        </IfModule>
    </Directory>
    #For Ubuntu apache config
    ErrorLog ${APACHE_LOG_DIR}/error-ng2-cms-api.log
    CustomLog ${APACHE_LOG_DIR}/access-ng2-cms-api.log combined

    #For Xampp windows config
    #ErrorLog "logs/error-ng2-cms-api.log"
    #CustomLog "logs/access-ng2-cms-api.log" combined
</VirtualHost>

<VirtualHost *:80>
    ServerName ng2-cms.local
    ServerAlias ng2-cms.local

	#For Linux
    DocumentRoot /home/nntuan/labs/ng2-cms/client/dist
	#For Windows
    #DocumentRoot D:/projects-3/ng2-cms/client/dist

	#For Linux
    <Directory /home/nntuan/labs/ng2-cms/client/dist>
	#For windows
    #<Directory D:/projects-3/ng2-cms/client/dist>
            #Options Indexes FollowSymLinks
            AllowOverride all
            Require all granted
    </Directory>

    #For Ubuntu apache config
    ErrorLog ${APACHE_LOG_DIR}/error-ng2-cms.log
    CustomLog ${APACHE_LOG_DIR}/access-ng2-cms.log combined

    #For Xampp windows config
    #ErrorLog "logs/error-ng2-cms.log"
    #CustomLog "logs/access-ng2-cms.log" combined
</VirtualHost>
```

### Add Hosts File
```
127.0.0.1	ng2-cms-api.local
127.0.0.1	ng2-cms.local
```

## 2. Command Use
```
#View Log File
tail -f -n 100 /var/log/apache2/access-ng2-cms.log
tail -f -n 100 /var/log/apache2/error-ng2-cms.log

php bin/console

php bin/console cache:clear --env=dev

php bin/console assets:install --symlink web

php bin/console assetic:dump --env=dev

php bin/console doctrine:migrations:diff --env=dev

php bin/console doctrine:migrations:migrate 20160923141633 --env=dev

php bin/console generate:bundle --namespace=ApiBundle --dir=src --format=annotation --no-interaction

php bin/console doctrine:generate:entities AppBundle/Entity/User

php bin/console doctrine:schema:update --force

php bin/console app:add-user
```

### Full Status Code
```
*  statusCodes={
*    200="Returned when get list and detail successful",
*    201="Returned when create successful",
*    204="Returned when update or delete successful",
*    400="Returned if not validated",
*    401="Returned when not have token or token expired",
*    403="Returned when not permission accepting",
*    404="Returned when can not find item",
*    405="Returned when used wrong method call",
*    409="Returned when add item existed",
*    500="Returned when Server error",
*    501="Returned when can not implemented",
*    503="Returned when service unavailable",
*  }
```

### Reference
[http://symfony.com/doc/current/security.html](http://symfony.com/doc/current/security.html)

[https://github.com/lexik/LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle)

[https://github.com/FriendsOfSymfony/FOSRestBundle](https://github.com/FriendsOfSymfony/FOSRestBundle)

[https://github.com/nelmio/NelmioApiDocBundle](https://github.com/nelmio/NelmioApiDocBundle)

[https://github.com/nelmio/NelmioCorsBundle](https://github.com/nelmio/NelmioCorsBundle)

[http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html](http://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html)