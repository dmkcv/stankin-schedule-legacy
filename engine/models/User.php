<?php
/**
 * User: kochevd
 * Date: 15.11.2017
 * Time: 15:42
 */

class User
{
    public static function getByLogin ($login) {
        $user = Registry::get('db')->query('SELECT * FROM `users` WHERE `login`=?', [$login], 'row');
        return $user;
    }

    public static function authenticateWithPassword ($login, $password) {
        $user = self::getByLogin($login);
        if ($user) {
            if (password_verify ($password, $user['password'])) {
                return $user['id'];
            } else {
                return false;
            }
        }
    }
}