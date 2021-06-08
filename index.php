<?php
ini_set('display_errors', 0);

define ( 'ROOT_DIR', __DIR__);
define ( 'CORE_DIR', ROOT_DIR . '/engine' );
define ( 'STORAGE_DIR', ROOT_DIR . '/storage' );
define ( 'STORAGE_RAW_DIR', ROOT_DIR . '/storage/raw' );
define ( 'ASSETS_DIR', ROOT_DIR . '/assets' );
define ( 'VIEWS_DIR', CORE_DIR . '/views' );
define ( 'UPLOAD_DIR', STORAGE_DIR . '/temp/' );
define ( 'PROTOCOL', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) === 'on') ? 'https://' : 'http://',true);
define ( 'DOMAIN', $_SERVER['HTTP_HOST']);
define ( 'ROOT_URL', preg_replace("/\/$/",'', PROTOCOL.DOMAIN.str_replace(array('\\', 'index.php', 'index.html'), '', dirname(htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES))),1).'/',true);
define ( 'CLI', false);

require_once CORE_DIR.'/init.php';
require_once CORE_DIR.'/router.php';