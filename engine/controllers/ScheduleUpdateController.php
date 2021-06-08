<?php
/**
 * Project: raspprod
 * Date: 19.07.2017
 * Time: 10:06
 */

class ScheduleUpdateController
{
    public static function renderSettings () {
        $msg = get_http_value('msg', $_GET, 'bool');
        $logs =  \Models\Update::getEventLog();
        if ($logs) {
            foreach ($logs as &$log) {
                $log['time'] = $log['time'] ? date('d.m.Y H:i:s', $log['time']) : '-';
                $log['filemtime'] = $log['filemtime'] ? date('d.m.Y H:i:s', $log['filemtime']) : '-';
            }
        }
        $page = Registry::get('template')->render('schedule_update', ['settings'=> \Models\Update::getSettings(), 'msg' => $msg, 'logs' => $logs]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function saveAUSettings () {
        \Models\Update::saveSettings($_POST);
        _redirect(ROOT_URL . 'admin/scheduleupdate?msg=1&result=' . mt_rand(1, 1000), 302);
    }

    public static function testConnection () {
        $response = [];
        $url = get_http_value('url', $_POST);
        $login = get_http_value('login', $_POST);
        $password = get_http_value('password', $_POST);
        $file_path = get_http_value('filepath', $_POST);
        $strategy = get_http_value('strategy', $_POST);
        if ($url && $login && $password && $file_path && $strategy) {
            $settings = array ('au_url' => $url, 'au_login' => $login, 'au_password' => $password, 'au_path' => $file_path, 'au_strategy' => $strategy);
            $ftp = new \Helpers\AutoUpdate\UpdateManager($settings);
            $response = $ftp->testConnect();
        } else {
            $response['result'] = false;
            $response['msg'] = 'Не переданы необходимые данные';
        }
        Registry::get('response')
            ->write(json_encode($response))
            ->header('Content-Type', 'application/json')
            ->send();
    }

    public static function startUpdate () {
        Registry::get('response')
            ->write(json_encode(array('result' => json_encode(\Helpers\AutoUpdate\UpdateManager::startManualUpdate()))))
            ->header('Content-Type', 'application/json')
            ->send();
    }
}