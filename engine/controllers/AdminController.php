<?php

/**
 * Project: raspprod
 * Date: 04.04.2017
 * Time: 5:21
 */
class AdminController
{
    public static function renderIndex () {
        $page = Registry::get('template')->render('index', []);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function renderLogin () {
        $msg = get_http_value('msg', $_GET, 'bool');
        $page = Registry::get('template')->render('login', ['msg' => $msg]);
        Registry::get('response')
            ->write($page)
            ->send();
    }

    public static function handleLogout ($c) {
        $key = intval(substr(md5(date('H w y').CLI_KEY), 0, 8), 16);
        if ($c == $key) {
            session_destroy();
            _redirect('../../admin');
        }
    }

    public static function handleLogin () {
        $invalid_login = true;
        $login = get_http_value('login', $_POST, 'alnum');
        $password = get_http_value('password', $_POST, 'alnum');
        if (!empty($login) && !empty($password)) {
            $user = User::authenticateWithPassword($login, $password);
            if ($user) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['userid'] = (int)$user;
                $invalid_login = false;
            }
        } else {
            $invalid_login = true;
        }
        if ($invalid_login !== false) {
            _redirect('../admin?msg=1');
        } elseif (array_key_exists('loggedIn',$_SESSION)) {
            _redirect('../admin/dashboard');
        } else {
            _redirect('../admin');
        }
    }
}