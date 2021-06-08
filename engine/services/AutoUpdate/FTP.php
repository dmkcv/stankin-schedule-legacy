<?php

/**
 * Project: raspprod
 * Date: 06.06.2017
 * Time: 12:34
 */
namespace Helpers\AutoUpdate;

class FTP
{
    private $_host;
    private $_port;
    private $_pwd;
    public $_stream;
    private $_timeout;
    private $_user;
    public $error;
    public $passive = false;
    public $ssl = false;
    public $system_type;

    /**
     * Инициализация соединения
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param int $port
     * @param int $timeout (сек)
     */
    public function  __construct($host = null, $user = null, $password = null, $port = 21, $timeout = 90) {
        $this->_host = $host;
        $this->_user = $user;
        $this->_pwd = $password;
        $this->_port = (int)$port;
        $this->_timeout = (int)$timeout;
    }

    /**
     * Автозакрытие соединения
     */
    public function  __destruct() {
        $this->close();
    }

    /**
     * Change currect directory on FTP server
     *
     * @param string $directory
     * @return bool
     */
    public function cd($directory = null) {
        if(ftp_chdir($this->_stream, $directory)) {
            return true;
        } else {
            $this->error = "Не удалось перейти к папке \"{$directory}\"";
            return false;
        }
    }

    /**
     * Close FTP connection
     */
    public function close() {
        if($this->_stream) {
            ftp_close($this->_stream);
            $this->_stream = false;
        }
    }

    /**
     * Connect to FTP server
     *
     * @return bool
     */
    public function connect() {
        // не SSL-соединение
        if(!$this->ssl) {
            if(!$this->_stream = ftp_connect($this->_host, $this->_port, $this->_timeout)) {
                $this->error = "Не удалось подключиться к {$this->_host}";
                return false;
            }
            // SSL
        } elseif(function_exists('ftp_ssl_connect')) {
            if(!$this->_stream = ftp_ssl_connect($this->_host, $this->_port, $this->_timeout)) {
                $this->error = "Не удалось подключиться к {$this->_host} (SSL)";
                return false;
            }
        } else {
            $this->error = "Не удалось подключиться к {$this->_host} (неверный тип)";
            return false;
        }

        if(ftp_login($this->_stream, $this->_user, $this->_pwd)) {
            //passive mode
            ftp_pasv($this->_stream, (bool)$this->passive);
            $this->system_type = ftp_systype($this->_stream);
            return true;
        } else {
            $this->error = "Не удалось подключиться к {$this->_host} (ошибка авторизации)";
            return false;
        }
    }

    /**
     * Download file from server
     *
     * @param string $remote_file
     * @param string $local_file
     * @param int $mode
     * @return bool
     */
    public function get($remote_file = null, $local_file = null, $mode = FTP_BINARY) {
        if(ftp_get($this->_stream, $local_file, $remote_file, $mode)) {
            return true;
        } else {
            $this->error = "Не удалось скачать файл \"{$remote_file}\"";
            return false;
        }
    }

    /**
     * mdtm
     *
     * @param string $remote_file
     * @return bool
     * @internal param string $local_file
     * @internal param int $mode
     */
    public function mdtm($remote_file) {
        if(($buff = ftp_mdtm($this->_stream, $remote_file)) != -1) {
            return $buff;
        } else {
            $this->error = "Не удалось получить время изменения файла \"{$remote_file}\"";
            return false;
        }
    }

    /**
     * size
     *
     * @param string $remote_file
     * @return bool
     * @internal param string $local_file
     * @internal param int $mode
     */
    public function size($remote_file) {
        if(($buff = ftp_size($this->_stream, $remote_file)) != -1) {
            return $buff;
        } else {
            $this->error = "Не удалось получить размер файла \"{$remote_file}\"";
            return false;
        }
    }

    /**
     * Upload file to server
     *
     * @param null $local_file
     * @param null $remote_file
     * @param int $mode
     * @return bool
     */
    public function put($local_file = null, $remote_file = null, $mode = FTP_BINARY)
    {
        if (ftp_put($this->_stream, $remote_file, $local_file, $mode)) {
            return true;
        } else {
            $this->error = "Не удалось выгрузить файл \"{$local_file}\"";
            return false;
        }
    }

    /**
     * Get list of files/directories in directory
     *
     * @param string $directory
     * @return array
     */
    public function ls($directory = null) {
        $list = array();
        if($list = ftp_rawlist($this->_stream, $directory)) {
            return $list;
        } else {
            $this->error = 'Не удалось получить список файлов в папке';
            return array();
        }
    }

    public function ls_array($directory = '.') {
        if (is_array($children = $this->ls($directory))) {
            $items = array();

            foreach ($children as $child) {
                $chunks = preg_split("/\s+/", $child);
                list($item['rights'], $item['number'], $item['user'], $item['group'], $item['size'], $item['month'], $item['day'], $item['time']) = $chunks;
                $item['type'] = $chunks[0]{0} === 'd' ? 'directory' : 'file';
                $item['year'] = $item['time'];
                $item['year'] = preg_replace('/\d{2}:\d{2}/', date('Y'), $item['year']);
                $item['time'] = preg_replace('/\d{4}/', '00:00', $item['time']);
                $item['unixtime'] = strtotime($item['day'].'.'.$item['month'].'.'.$item['year'].' '.$item['time']);
                array_splice($chunks, 0, 8);
                $items[implode(' ', $chunks)] = $item;
            }

            return $items;
        }
        return false;
    }
}