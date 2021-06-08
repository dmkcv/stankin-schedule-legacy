<?php
/**
 * Project: raspprod
 * Date: 04.09.2017
 * Time: 16:26
 */

namespace Models;


class Settings
{
    /**
     * Обновляет поле настроек или создаёт новую запись с заданным именем
     * @param $name - имя поля
     * @param $value - значение
     * @return mixed ID поля или false в случае ошибки разрешения обновления
     */
    public static function setByName ($name, $value) {
        $set = \Registry::get('db')->query('INSERT INTO `settings` SET `setting_name`=?, `setting_value`=? ON DUPLICATE KEY UPDATE `setting_value`=?', [$name, $value, $value], 'id');
        return $set ?: false;
    }

    /**
     * Получает значение поля настроек
     * @param $name - имя поля
     * @return mixed значение поля или false, если такого нет в базе
     */
    public static function getByName ($name) {
        $set = \Registry::get('db')->query('SELECT `setting_value` FROM `settings` WHERE `setting_name`=?', [$name], 'el');
        return $set ?: false;
    }
}