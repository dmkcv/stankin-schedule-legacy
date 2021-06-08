<?php
set_time_limit(1000);
ini_set('display_errors', 0);

define ( 'ROOT_DIR', __DIR__);
define ( 'CORE_DIR', ROOT_DIR . '/engine' );
define ( 'STORAGE_DIR', ROOT_DIR . '/storage' );
define ( 'STORAGE_RAW_DIR', ROOT_DIR . '/storage/raw' );
define ( 'UPLOAD_DIR', STORAGE_DIR . '/temp/' );
define ( 'ASSETS_DIR', ROOT_DIR . '/assets' );
define ( 'CLI', true);

require_once CORE_DIR.'/init.php';

function argv2request($argv) {
    if ($argv !== NULL && count($_REQUEST) == 0) {
        $argv0 = array_shift($argv);
        foreach ($argv as $pair) {
            list ($k, $v) = explode('=', $pair);
            $_REQUEST[$k] = $v;
        }
    }
}
argv2request($argv);

$key = get_http_value('key', $_REQUEST, 'alnum');
$act = get_http_value('act', $_REQUEST, 'alnum');

if ($key == CLI_KEY) {
    switch ($act) {
        case 'build':
            $key = get_http_value('rev', $_REQUEST, 'alnum');
            if (StaticGenerator::generateFromFile($key)) {
                echo ('build ok');
                http_response_code(200);
            } else {
                echo ('build failed for some reason');
                http_response_code(500);
            }
            break;
        case 'upd':
            $upd = new \Helpers\AutoUpdate\UpdateManager();
            if ($upd->launchUpdateProcess()) {
                echo ('update ok');
                http_response_code(200);
            } else {
                echo ('update failed');
                http_response_code(500);
            }
            break;
        default:
            break;
        }
} else {
    die ('All DBs deleted. Thank you.');
}