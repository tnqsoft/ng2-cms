# ng2-cms
Angular JS 2 and Symfony 3 Admin

## Fix Error Symfony.phar in Windows
Error: Fix symfony.phar cURL error 60: ssl certification issue when attempting to use symfony
Download https://curl.haxx.se/ca/cacert.pem
Save D:\projects\cacert.pem
Search and change in php.ini
curl.cainfo = "D:\projects\cacert.pem"