<?php

/**
 * Project: raspprod
 * Date: 21.07.2017
 * Time: 18:28
 */
class Schedule
{
    public static function processFromFile ($file) {
        if (!empty($file)) {
            $move = ScheduleXML::moveFile($file);
            if ($move) {
                list($file, $rev) = $move;
                if (self::addToQueue($file, $rev)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function addToQueue ($file, $rev) {
        $process = self::addToDB($file, $rev);
        if ($process) {
            Task::startRevisionBuild($rev);
            return true;
        } else {
            return false;
        }
    }

    public static function addToDB ($file, $rev) {
        $parser = new Parser($file);
        $file_name = $parser->returnFileName();
        $parser = $parser->returnSettings();
        $rm = ScheduleActions::removeSingleFromDB($rev);
        $db = ScheduleActions::add(array($rev, time(), 0, 0, strtotime($parser['begin_date']), strtotime($parser['end_date']), $file_name, $parser['mtime']));
        return ($rm && $db);
    }
}