<?php
/**
 * Project: raspprod
 * Date: 03.08.2017
 * Time: 2:48
 */

class Task
{
    public static function listTasks () {
        $tasks = TaskManager::searchForCLITasks();
        return (count($tasks) > 0)  ? $tasks : false;
    }

    public static function returnMetainfo () {
        $tasks = self::listTasks();
        return $tasks ? array ('ids' => array_column($tasks, 3), 'revs' => array_column($tasks, 2)) : false;
    }

    public static function searchIDbyRevision ($rev) {
        $tasks = self::listTasks();
        $rev_id = @array_search($rev, @array_column($tasks, 2));
        return ($rev_id !== false) ? $tasks[$rev_id][3] : false;
    }

    public static function killProcess ($id) {
        $allowed = self::returnMetainfo()['ids'];
        if ($allowed && in_array((int)$id, $allowed)) {
            TaskManager::killTaskByID($id);
            return true;
        } else {
            return false;
        }
    }

    public static function restartProcess ($id) {
        $tasks = self::listTasks();
        $id_build = array_search($id, array_column($tasks, 3)); // Поиск строки по ID
        $array_build = explode(' ', $tasks[$id_build][0]); // Разбор аргументов для сборки, так как возможны дополнительные команды со своим синтаксисом
        array_pop($array_build); // Удаление ID
        $string_build = trim(implode(' ', $array_build)); // Пересборка аргументов
        if (is_int($id_build) && strlen($string_build) > 10) {
            self::killProcess($id);
            sleep (2);
            TaskManager::execSilentlyOnCLI($string_build);
            return true;
        } else {
            return false;
        }
    }

    public static function startRevisionBuild ($rev) {
        TaskManager::execSilentlyOnCLI('act=build rev=' .$rev. ' key=' .CLI_KEY);
        return true;
    }
}