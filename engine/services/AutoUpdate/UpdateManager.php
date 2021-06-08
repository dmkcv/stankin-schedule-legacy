<?php
/**
 * Project: raspprod
 * Date: 17.09.2017
 * Time: 3:38
 */

namespace Helpers\AutoUpdate;


use Models\Update;

class UpdateManager
{
    public $handle;

    public function __construct(array $settings = null)
    {
        if (!$settings) $settings = Update::getSettings();
        $this->handle = new UpdateService($settings);
    }

    public function testConnect () {
        $ftp = $this->handle;
        $response = array ('result' => false, 'mtime' => null, 'localmtime' => null , 'filesize' => null, 'msg' => '');
        $msg = '';
        $result = false;
        try {
            $ftp->connect();
            $temp_name = STORAGE_DIR . '/temp/ftp/' . md5(time() . mt_rand(1,999));
            switch ($ftp->getStrategy()) {
                case 1: // Указанный файл
                    $response['mtime'] = date ('d.m.Y H:i:s', $ftp->checkFileModTime());
                    $remote_size = $ftp->checkFileSize();
                    break;

                case 2: // Самый новый файл из директории
                    $newest_file = $ftp->findNewestFileInDirectory();
                    $new_path = $ftp->getPath().DIRECTORY_SEPARATOR.$newest_file;
                    $ftp->setNewPath($new_path);
                    $response['mtime'] = date ('d.m.Y H:i:s', $ftp->checkFileModTime());
                    $response['filename'] = $newest_file;
                    $remote_size = $ftp->checkFileSize();
                    break;

                default:
                    throw new \RuntimeException('Не задана стратегия обновления');
                    break;
            }
            if ($remote_size < 31457280 && $remote_size > 1) { // Лимит в 30 мегабайт для теста
                if ($ftp->getFile($temp_name)) {
                    if (file_exists($temp_name)) {
                        $response['localmtime'] = date ('d.m.Y H:i:s', filectime($temp_name));
                        $response['filesize'] = filesize($temp_name);
                        if ($response['filesize'] != $remote_size) {
                            $msg = 'Файл получен успешно, но размеры отличаются (локальный: ' .$response['filesize']." <> удаленный: $remote_size)";
                        }
                        unlink($temp_name);
                        $result = true;
                    } else {
                        $msg = 'Не удалось найти загруженный файл';
                    }
                } else {
                    $msg = 'Не удалось загрузить файл';
                }
            } else {
                $msg = 'Тестовый файл должен быть размером до 30 мегабайт';
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
        }
        $response['result'] = $result;
        $response['msg'] = $msg;
        return $response;
    }

    public function performUpdate ()
    {
        $ftp = $this->handle;
        $temp_name = STORAGE_DIR . '/temp/ftp/' . md5(time() . mt_rand(1, 999));
        $event = array('time' => time(), 'result' => -1, 'error' => '', 'success' => 0, 'revupdated' => 0, 'filemtime' => '', 'newrev' => '', 'oldrev' => LATEST_REV);
        $temp_data = [];
        try {
            $mutex = new \FileMutex('updateProcess');
            $result = false;
            if (!$mutex->isLocked()) {
                $mutex->getLock();
                try {
                    $ftp->connect();
                    switch ($ftp->getStrategy()) {
                        case 1: // Указанный файл
                            $response['mtime'] = date('d.m.Y H:i:s', $ftp->checkFileModTime());
                            $remote_size = $ftp->checkFileSize();
                            break;

                        case 2: // Самый новый файл из директории
                            $newest_file = $ftp->findNewestFileInDirectory();
                            $new_path = $ftp->getPath() . DIRECTORY_SEPARATOR . $newest_file;
                            $ftp->setNewPath($new_path); // К пути добавляется имя самого свежего файла
                            $response['mtime'] = date('d.m.Y H:i:s', $ftp->checkFileModTime());
                            $response['filename'] = $newest_file;
                            $remote_size = $ftp->checkFileSize();
                            break;

                        default:
                            throw new \RuntimeException('Не задана стратегия обновления');
                            break;
                    }
                    if ($remote_size < 60457280 && $remote_size > 1) { // Лимит в 60 мегабайт
                        if ($ftp->getFile($temp_name)) {
                            if (file_exists($temp_name)) {
                                $ftp->close();
                                $temp_data['localmtime'] = $event['filemtime'] = filectime($temp_name);
                                $temp_data['filesize'] = filesize($temp_name);
                                $temp_data['md5'] = md5_file($temp_name);
                                if ($temp_data['filesize'] != $remote_size) {
                                    $event['error'] = 'Файл получен успешно, но размеры отличаются (локальный: ' . $temp_data['filesize'] . " <> удаленный: $remote_size)";
                                } else if (LATEST_REV != $temp_data['md5']) { // Файлы всё же отличаются, продолжаем
                                    $move = \ScheduleXML::moveFile($temp_name); // Здесь и далее не используется запуск одной функцией по причине необходимости записи события по завершении
                                    if (file_exists($move[0])) {
                                        \Schedule::addToDB($move[0], $temp_data['md5']);
                                        $process = \StaticGenerator::generateFromFile($temp_data['md5']);
                                        if ($process) {
                                            $event['result'] = 200;
                                            $event['success'] = 1;
                                            $event['revupdated'] = 1;
                                            $event['newrev'] = $temp_data['md5'];
                                            if (\Utility::checkRevision($temp_data['md5'])) {
                                                \ScheduleActions::enableByRev($temp_data['md5']);
                                                \ScheduleActions::setUpdateInfo($temp_data['md5']);
                                                \Registry::get('log')->info('Autoupdate OK', $event);
                                                \Registry::get('log')->info($temp_data['md5'] . ':Enabled by autoupdater');
                                                $result = true;
                                            } else {
                                                unlink($temp_name);
                                                throw new \RuntimeException('Ошибка при валидации загруженного файла');
                                            }
                                        } else {
                                            unlink($move[0]);
                                            throw new \RuntimeException('Неопознанная ошибка при обработке, см. системный журнал');
                                        }
                                    } else {
                                        unlink($temp_name);
                                        throw new \RuntimeException('Ошибка при первичной валидации или перемещении загруженного файла');
                                    }
                                } else {
                                    $event['result'] = 200;
                                    $event['success'] = 1;
                                    unlink($temp_name); // Неизменившиеся просто удалять
                                    $result = true;
                                }
                            } else {

                                throw new \RuntimeException('Не удалось найти загруженный файл');
                            }
                        } else {
                            throw new \RuntimeException('Не удалось загрузить файл');
                        }
                    } else {
                        throw new \RuntimeException('Файл должен быть размером до 60 мегабайт');
                    }
                    if ($event['revupdated'] == 0) \Registry::get('log')->info('Autoupdate OK, BUT NOT MODIFIED', $event);
                } catch (\Exception $e) {
                    $event['error'] = $e->getMessage();
                    \Registry::get('log')->warning('Autoupdate FAIL', $event);
                }
                Update::setEvent($event);
                $mutex->releaseLock();
            } else {
                throw new \RuntimeException('Установлена файловая блокировка (mutex)');
            }
        } catch (\Exception $e) {
            $event['error'] = $e->getMessage();
            \Registry::get('log')->warning('Autoupdate FAIL', $event);
            Update::setEvent($event);
        }
        return $result;
    }

    public function launchUpdateProcess () {
        if (Update::isAllowed()) {
            return $this->performUpdate();
        } else {
            return false;
        }
    }

    public static function startManualUpdate () {
        \TaskManager::execSilentlyOnCLI('act=upd rev=null key=' .CLI_KEY);
        return true;
    }
}