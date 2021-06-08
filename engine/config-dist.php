<?php

date_default_timezone_set('Europe/Moscow');

define ( 'CLI_KEY', '');
define ( 'BASEPATH', '/');
define ( 'DB_PREFIX', '');

define ( 'NOTIFY_EMAIL_TO', 'mail@gmail.com');
define ( 'NOTIFY_EMAIL_FROM', 'mail@mail.com');
define ( 'LOG_SMTP_SERVER', 'smtp.mail.com');
define ( 'LOG_SMTP_LOGIN', 'mail@mail.com');
define ( 'LOG_SMTP_PASSWORD', '');
define ( 'LOG_SMTP_PORT', '465');

$dbconfig = array(
    'host'     => '127.0.0.1',
    'username' => 'root',
    'password'   => '',
    'dbname'   => DB_PREFIX.'',
    'charset'  => 'utf8'
);