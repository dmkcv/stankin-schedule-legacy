<?php
/**
 * Библиотека блокировки на основе временных файлов (mutex-style)
 */
 
class FileMutex {
    public $writeablePath = '';
    public $lockName = '';
    public $fileHandle = null;

    public function __construct($lockName, $writeablePath = null){
        $this->lockName = $lockName;
        if($writeablePath == null){
            $this->writeablePath = $this->findWriteablePath();
        } else {
            $this->writeablePath = $writeablePath;
        }
    }

    public function getLock(){
        return fwrite($this->getFileHandle(), 'locked'.time());
    }

    public function getFileHandle(){
        if($this->fileHandle == null){
            $this->fileHandle = fopen($this->getLockFilePath(), 'c');
        }
        return $this->fileHandle;
    }

    public function releaseLock(){
        fclose($this->getFileHandle());
        return unlink($this->getLockFilePath());
    }

    public function getLockFilePath(){
        return $this->writeablePath . DIRECTORY_SEPARATOR . md5($this->lockName);
    }

    public function isLocked(){
        return file_exists($this->getLockFilePath());
    }

    public function findWriteablePath(){
        $path = sys_get_temp_dir();
        if(!is_writable($path)){
            throw new \RuntimeException('Не найден путь для записи временного файла.');
        }
        
        return $path;
    }
}
