<?php

/**
 * Project: raspprod
 * Date: 04.04.2017
 * Time: 5:21
 */
class ScheduleController
{
    public static function renderJournal ($id = false) {
        if ($id) {
            $syslog = false;
            $rev = ScheduleActions::getByID($id);
            $log = Utility::getRevisionBuildLog($rev);
        } else {
            $rev = $log = false;
            $syslog = Utility::getSystemLog();
        }
        $page = Registry::get('template')->render('journal', ['rev' => $rev, 'log' => $log, 'syslog' => $syslog]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function renderJournalByRev ($rev) {
        $log = Utility::getRevisionBuildLog($rev);
        $page = Registry::get('template')->render('journal', ['rev' => $rev, 'log' => $log, 'syslog' => false]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function downloadBuildLog ($id) {
        $rev = ScheduleActions::getByID($id);
        $log = Utility::getRevisionBuildLog($rev);
        Registry::get('response')
            ->header('Content-Disposition: attachment; filename='.$rev.'-'.date('d.m.Y-H.i.s').'-build.log;')
            ->write($log['log'])
            ->send();
    }

    public static function renderDashboard () {
        $msg = get_http_value('msg', $_GET, 'bool');
        $key = intval(substr(md5(date('H w y').CLI_KEY), 0, 8), 16);
        $files = ScheduleActions::getAll();
        if ($files) {
            for ($i = 0, $iMax = count($files); $i < $iMax; $i++) {
                $files[$i]['buildlog'] = Utility::checkIfRevisionHaveBuildLog($files[$i]['revision']);
                $files[$i]['check'] = Utility::checkRevision($files[$i]['revision']);
                $files[$i]['buildprogress'] = Utility::checkRevisionBuildProgress($files[$i]['revision']);
            }
        }
        $page = Registry::get('template')->render('dashboard', ['msg' => $msg, 'files' => $files, 'key' => $key]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function handleXMLUpload ()
    {
        if (!empty($_FILES['xml'])) {
            $move = Schedule::processFromFile($_FILES['xml']['tmp_name']);
            if ($move) {
                _redirect(ROOT_URL . 'admin/dashboard?msg=1&result=' . mt_rand(1, 1000), 302);
            } else {
                trigger_error('Ошибка в момент старта обработки', E_USER_ERROR);
                exit();
            }
        } else {
            trigger_error('Ошибка при загрузке файла', E_USER_ERROR);
            exit();
        }
    }

    public static function disableByID ($id) {
        ScheduleActions::disableByID($id);
        _redirect(ROOT_URL. 'admin/dashboard?result=' .mt_rand(1,1000), 307);
    }

    public static function enableByID ($id) {
       ScheduleActions::enableByID($id);
        _redirect(ROOT_URL. 'admin/dashboard?result=' .mt_rand(1,1000), 307);
    }

    public static function removeByID ($id) {
        ScheduleActions::removeByID($id);
        _redirect(ROOT_URL. 'admin/dashboard?result=' .mt_rand(1,1000), 307);
    }
}