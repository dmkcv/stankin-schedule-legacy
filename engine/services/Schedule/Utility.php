<?php

/**
 * Project: raspprod
 * Date: 04.04.2017
 * Time: 15:47
 */
class Utility
{
    public static function checkRevision ($rev) {
        $file = file_exists(STORAGE_RAW_DIR.'/'.$rev.'/checked.txt') ? true : false;
        $db = file_exists(STORAGE_RAW_DIR.'/'.$rev.'/'.DB_PREFIX. 'rasp_' . substr($rev, 0, 8). '.db') ? true : false;
        return array('file' =>$file, 'db' =>$db);
    }

    public static function checkIfRevisionHaveBuildLog ($rev) {
        $warnings = false;
        $file = file_exists(STORAGE_RAW_DIR.'/'.$rev.'/build.log') ? true : false;
        if ($file) {
            $file_content = file_get_contents(STORAGE_RAW_DIR.'/'.$rev.'/build.log');
            if (stripos($file_content, 'WARNING') !== false || stripos($file_content, 'ERROR') !== false) { $warnings = true; }
        }
        return array('log' =>$file, 'warnings' =>$warnings);
    }

    public static function getRevisionBuildLog ($rev) {
        $rev_array = array();
        $rev_string = false;
        $file = file_exists(STORAGE_RAW_DIR.'/'.$rev.'/build.log') ? true : false;
        if ($file) {
           $file_content = file_get_contents(STORAGE_RAW_DIR.'/'.$rev.'/build.log');
        } else { return false; }
        $rev_regex = '/(.*)'.$rev.'(.*)/i';
        preg_match_all($rev_regex, file_get_contents(STORAGE_DIR.'/app.log'), $rev_matches, PREG_SET_ORDER);
        if (count($rev_matches) > 0) {
            foreach ($rev_matches as $r) {
                $rev_array[] = $r[0];
            }
            $rev_string = implode('<br>',$rev_array);
        }
        return array('log' =>$file_content, 'rev_main' =>$rev_string);
    }

    public static function getSystemLog () {
        $file = file_exists(STORAGE_DIR.'/app.log') ? true : false;
        if ($file) {
            $file_content = file_get_contents(STORAGE_DIR.'/app.log');
        } else { return false; }
        return $file_content;
    }

    public static function checkRevisionBuildProgress ($rev) {
        $file = file_exists(STORAGE_RAW_DIR.'/'.$rev.'/checklist.txt') ? true : false;
        if ($file) {
            $file_content = file_get_contents(STORAGE_RAW_DIR.'/'.$rev.'/checklist.txt');
        } else { return false; }
        $file_iterator = new \FilesystemIterator(STORAGE_RAW_DIR.'/'.$rev.'/', \FilesystemIterator::SKIP_DOTS);
        return round((iterator_count($file_iterator) / (substr_count($file_content, ';') + 1)) * 100);
    }
}