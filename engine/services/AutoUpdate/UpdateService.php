<?php

/**
 * Project: raspprod
 * Date: 06.06.2017
 * Time: 13:25
 */
namespace Helpers\AutoUpdate;

class UpdateService
{
    protected $host;
    protected $login;
    protected $password;
    protected $path;
    protected $connection;
    protected $strategy;

    public function __construct(array $config = null) {
        if (!function_exists('ftp_connect')) { throw new \RuntimeException('PHP FTP module is not enabled'); }
        if ($config) {
            if ($config['au_url'] && $config['au_login'] && $config['au_password'] && $config['au_path'] && $config['au_strategy']) {
                $this->host = $config['au_url'];
                $this->login = $config['au_login'];
                $this->password = $config['au_password'];
                $this->path = $config['au_path'];
                $this->strategy = $config['au_strategy'];
                $this->connection = new FTP ($this->host, $this->login, $this->password);
            } else {
                throw new \RuntimeException('Autoupdater settings is not defined');
            }
        } else {
            throw new \RuntimeException('Failed to get settings');
        }
    }

    public function connect () {
        if (!@$this->connection->connect()) {
            throw new \RuntimeException($this->connection->error);
        } else {
            return true;
        }
    }

    public function close () {
        @$this->connection->close();
        return true;
    }

    public function getStrategy () {
        return $this->strategy;
    }

    public function getPath () {
        return $this->path;
    }

    public function setNewPath ($path) {
        $this->path = $path;
    }

    public function checkFileModTime () {
        if (!$mdtm = @$this->connection->mdtm($this->path)) {
            throw new \RuntimeException($this->connection->error);
        } else {
            return $mdtm;
        }
    }

    public function checkFileSize () {
        if (!$size = @$this->connection->size($this->path)) {
            throw new \RuntimeException('Файл не найден или пуст');
        } else {
            return $size;
        }
    }

    public function getFile ($localpath) {
        if (!@$this->connection->get($this->path, $localpath)) {
            throw new \RuntimeException($this->connection->error);
        } else {
            return true;
        }
    }

    public function getDirectoryListing () {
        if (!$ls = @$this->connection->ls_array($this->path)) {
            throw new \RuntimeException($this->connection->error);
        } else {
            return $ls;
        }
    }

    public function findNewestFileInDirectory () {
        $listing = $this->getDirectoryListing();
        if (!empty($listing)) {
            $max = 0;
            $file = false;
            foreach ($listing as $k => $v) {
                if (($v['type'] === 'file') && $v['unixtime'] > $max) {
                    $max = $v['unixtime'];
                    $file = $k;
                }
            }
            return $file;
        } else {
            return false;
        }
    }
}