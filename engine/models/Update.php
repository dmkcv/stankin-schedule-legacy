<?php
/**
 * Project: raspprod
 * Date: 01.09.2017
 * Time: 3:57
 */

namespace Models;

class Update
{
    protected static $update_settings = array('au_status', 'au_url', 'au_login', 'au_password', 'au_path', 'au_strategy');

    // Сохранение настроек
    public static function saveSettings (array $settings) {
        if ($settings['au_password'] === '*********') unset ($settings['au_password']);
        foreach (self::$update_settings as $s) {
            if (array_key_exists($s, $settings)) {
                Settings::setByName($s, $settings[$s]);
            }
        }
    }

    // Получение настроек
    public static function getSettings () {
        $settings = [];
        foreach (self::$update_settings as $s) {
            $settings[$s] = Settings::getByName($s);
        }
        return $settings;
    }

    // Лог события
    public static function setEvent (array $event) {
        $log = \Registry::get('db')->query('INSERT INTO `log` SET ?set', [$event])->id();
        return $log ?: false;
    }

    /**
     * Получение логов
     * @return mixed
     */
    public static function getEventLog () {
        $log = \Registry::get('db')->query('SELECT * FROM `log` ORDER BY `time` DESC LIMIT 25', [], 'assoc');
        return $log ?: false;
    }

    // Разрешено ли?
    public static function isAllowed () {
        return (bool)Settings::getByName('au_status');
    }
}