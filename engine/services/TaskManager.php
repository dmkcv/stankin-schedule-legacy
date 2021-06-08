<?php
/**
 * Project: raspprod
 * Date: 28.07.2017
 * Time: 22:39
 */

class TaskManager
{
    public static function execSilentlyOnCMD ($command) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /b \"\" $command > nul", 'r'));
        } else {
            exec("nohup $command > /dev/null 2>&1 &");
        }
    }

    public static function execSilentlyOnCLI ($command) {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen('start /b "" php ' .$_SERVER['DOCUMENT_ROOT'].BASEPATH."cli.php $command > nul", 'r'));
        } else {
            exec('nohup php ' .$_SERVER['DOCUMENT_ROOT'].BASEPATH."cli.php $command > /dev/null 2>&1 &");
        }
    }

    public static function searchForCLITasks () {
        $cli_regex = '/act=(.*) rev=(.*) key=.+ (\d+)/i'; // Требуется искать все действия по задачам
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $result = shell_exec('WMIC path win32_process get Caption,Processid,Commandline');
        } else {
            $result = shell_exec('ps -Ao cmd,pid');
        }
        preg_match_all($cli_regex, $result, $matches, PREG_SET_ORDER);
        return $matches;
    }

    public static function killTaskByID ($id) {
        $id = (int)$id;
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("taskkill /f /pid $id");
        } else {
            exec("kill -9 $id");
        }
    }
}