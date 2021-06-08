<?php

/**
 * Project: raspprod
 * Date: 26.07.2017
 * Time: 17:04
 */
class TaskManagerController
{
    public static function renderManager () {
        $tasks = Task::listTasks();
        $page = Registry::get('template')->render('task_manager', ['tasks' => $tasks]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function killProcess ($id) {
        $tasks = Task::listTasks();
        $process = Task::killProcess((int)$id);
        if ($process) {
            $msg = "Процесс #$id завершен.";
            $msg_type = 'success';
        } else {
            $msg = "Ошибка завершения процесса - он уже завершен ранее или нет доступа (#$id).";
            $msg_type = 'warning';
        }
        $page = Registry::get('template')->render('task_manager', ['tasks' => $tasks, 'msg' => $msg, 'msg_type' => $msg_type, 'inj_refresh' => true]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function restartProcess ($id) {
        $tasks = Task::listTasks();
        $process = Task::restartProcess((int)$id);
        if ($process) {
            $msg = "Процесс #$id перезапущен.";
            $msg_type = 'success';
        } else {
            $msg = "Ошибка перезапуска процесса - он уже завершен ранее или нет доступа (#$id).";
            $msg_type = 'warning';
        }
        $page = Registry::get('template')->render('task_manager', ['tasks' => $tasks, 'msg' => $msg, 'msg_type' => $msg_type, 'inj_refresh' => true]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function checkRevisionProcessing () {
        $rev = get_http_value('rev', $_GET, 'alnum');
        $revs = Task::returnMetainfo();
        Registry::get('response')
            ->write(json_encode( array('result' => (@in_array($rev, $revs['revs']) || $revs['revs'][0] === 'null'))))
            ->header('Content-Type', 'application/json')
            ->send();
    }

    public static function restartRevision () {
        $rev = get_http_value('rev', $_GET, 'alnum');
        $kill_result = true;
        $result = false;
        if (ScheduleActions::isExists($rev)) {
            if ($process = Task::searchIDbyRevision($rev)) {
                $kill_result = Task::killProcess($process);
                sleep(2);
            }
            $start = Task::startRevisionBuild($rev);
            $result = $start && $kill_result;
        }
        Registry::get('response')
            ->write(json_encode( array('result' => $result)))
            ->header('Content-Type', 'application/json')
            ->send();
    }
}