<?php

/**
 * Project: raspprod
 * Date: 21.07.2017
 * Time: 18:28
 */
class ScheduleActions
{
    public static function getByID ($id) {
        $rev = Registry::get('db')->query('SELECT revision FROM schedules WHERE id = ?i', [$id], 'el');
        return $rev ?: false;
    }

    public static function isExists ($rev) {
        $rev = Registry::get('db')->query('SELECT revision FROM schedules WHERE revision = ?', [$rev], 'el');
        $file = file_exists(UPLOAD_DIR . $rev.'.xml');
        return $rev && $file ?: false;
    }

    public static function add (array $data) {
        $add = Registry::get('db')->query('INSERT INTO schedules (revision, added, active, status, date_from, date_to, name, mtime) VALUES (?,?i,?i,?i,?i,?i,?,?)', $data, 'ar');
        return $add ?: false;
    }

    /**
     * @return mixed
     */
    public static function getAll () {
        $all_scheds = Registry::get('db')->query("SELECT id, name, revision, FROM_UNIXTIME(`added`, '%d.%m.%Y %T') as added, active, status, FROM_UNIXTIME(`date_from`, '%d.%m.%Y') as date_from, FROM_UNIXTIME(`date_to`, '%d.%m.%Y') as date_to, FROM_UNIXTIME(`mtime`, '%d.%m.%Y %T') as mtime, mtime as mtime_unix FROM `schedules` ORDER BY active DESC", [], 'assoc');
        return $all_scheds ?: false;
    }

    public static function removeSingleFromDB ($rev) {
        $rem = Registry::get('db')->query('DELETE FROM schedules WHERE revision = ?', [$rev], 'ar');
        return $rem ?: false;
    }

    public static function disableByID ($id) {
        Registry::get('db')->query('UPDATE schedules SET active = 0 WHERE id = ?i', [$id], 'ar');
        file_put_contents(STORAGE_DIR. '/latest', '');
    }

    public static function enableByID ($id) {
        $rev = Registry::get('db')->query('SELECT revision FROM schedules WHERE id = ?i', [$id], 'el');
        Registry::get('db')->query('UPDATE schedules SET active = 0', [], 'ar');
        Registry::get('db')->query('UPDATE schedules SET active = 1 WHERE id = ?i', [$id], 'ar');
        file_put_contents(STORAGE_DIR. '/latest', $rev);
    }

    public static function removeByID ($id) {
        $rev = Registry::get('db')->query('SELECT revision FROM schedules WHERE id = ?i', [$id], 'el');
        Registry::get('db')->query('DELETE FROM schedules WHERE id = ?i', [$id], 'ar');
        ScheduleXML::removeByRev($rev);
    }

    public static function enableByRev ($rev) {
        $id = Registry::get('db')->query('SELECT revision FROM schedules WHERE revision = ?i', [$rev], 'el');
        Registry::get('db')->query('UPDATE schedules SET active = 0', [], 'ar');
        Registry::get('db')->query('UPDATE schedules SET active = 1 WHERE id = ?i', [$id], 'ar');
        file_put_contents(STORAGE_DIR. '/latest', $rev);
    }

    public static function setUpdateInfo ($rev) {
        file_put_contents(STORAGE_DIR. '/updated', $rev.':'.time());
    }

    public static function getUpdateInfo ($input_rev) {
        if (file_exists(STORAGE_DIR. '/updated')) {
            $updated = file_get_contents(STORAGE_DIR . '/updated');
            if ($updated) {
                $updated = explode(':', $updated);
                list ($revision, $time) = $updated;
                $time = (int)$time;
                if ($revision !== null && !empty($revision)) {
                    if ($revision == $input_rev) {
                        return (!empty($time) && $time > 0) ? $time : null;
                    }
                } else {
                    return null;
                }
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}