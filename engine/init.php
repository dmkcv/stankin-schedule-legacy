<?php
use go\DB\DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: text/html; charset=utf-8');

require_once ROOT_DIR . '/vendor/autoload.php';
require_once CORE_DIR . '/functions.php';
require_once CORE_DIR . '/config.php';

Registry::set('db', DB::create($dbconfig, 'mysql'));
Registry::set('response', new Response());
if (!CLI) { Registry::set('template', new League\Plates\Engine(VIEWS_DIR)); }

$logger = new Logger('raspprod');
$logger->pushHandler(new StreamHandler(STORAGE_DIR.'/app.log', Logger::DEBUG));
Registry::set('log', $logger);

function dbdebug($query, $duration) {
    echo 'Debug: query: "'.$query.'", duration='.$duration.'<br />';
}

function init_file_db ($db) {
    $params = array(
        'filename' => $db,
        'mysql_quot' => true,
    );
    $db = go\DB\DB::create($params, 'sqlite');
    Registry::set('dbfiller', $db);
}

if (file_exists(STORAGE_DIR.'/latest')) {
    $latest = file_get_contents(STORAGE_DIR.'/latest');
    if (!empty($latest) && file_exists(STORAGE_RAW_DIR.'/'.$latest.'/checked.txt')) {
        define('LATEST_REV', $latest);
        define('REV_PATH', STORAGE_RAW_DIR. '/' .$latest);
        $settings = json_decode(file_get_contents(REV_PATH. '/settings.json'), true);
        define('REV_BEGIN', strtotime($settings['begin_date']));
        define('REV_END', strtotime($settings['end_date']));
        define('REV_BEGIN_HUMAN', $settings['begin_date']);
        define('REV_END_HUMAN', $settings['end_date']);
        define('REV_MTIME', @$settings['mtime']);
    } else {
        define('LATEST_REV', false);
    }
} else {
    define('LATEST_REV', false);
}
