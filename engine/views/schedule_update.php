<?php $this->layout('shared/admin_layout', ['title' => 'Автообновление расписания']) ?>
<div class="container">
    <div class="wrapper">
            <div class="col-sm-5">
                <h3 class="page-header">Параметры автообновления расписания</h3>
                <?php if ($this->e($msg)): ?><div class="row"><message class="great"><b>Настройки успешно сохранены</b></b></message></div><?php endif ?>
                <form id="settings" role="form" method="post" action="actions/save_ausettings">
                    <div class="form-group float-label-control">
                        <label for="">Вызов автообновления</label>
                        <label class="radio-inline"><input type="radio" id="status" name="au_status" value="1" <?php if ($settings['au_status']): ?>checked<?php endif ?>>&nbsp; Разрешен</label>&nbsp;
                        <label class="radio-inline"><input type="radio" id="status" name="au_status" value="0" <?php if (!$settings['au_status']): ?>checked<?php endif ?>>&nbsp; Запрещен</label>
                    </div>
                    <div class="form-group float-label-control">
                        <label for="">Стратегия обновления</label>
                        <label class="radio-inline"><input type="radio" id="strategy" name="au_strategy" value="1" <?php if ($settings['au_strategy'] == 1): ?>checked<?php endif ?>>&nbsp; Получать указанный файл</label>&nbsp;
                        <label class="radio-inline"><input type="radio" id="strategy" name="au_strategy" value="2" <?php if ($settings['au_strategy'] == 2): ?>checked<?php endif ?>>&nbsp; Получать самый новый файл из папки</label>
                    </div>
                    <div class="form-group float-label-control">
                        <label for="">Адрес FTP-сервера</label>
                        <input type="text" name="au_url" id="url" required class="form-control setting-box" placeholder="sync.server.ru" value="<?=$this->e($settings['au_url'])?>">
                    </div>
                    <div class="form-group float-label-control">
                        <label for="">Логин</label>
                        <input type="text" name="au_login" id="login" required class="form-control setting-box" placeholder="demologin" value="<?=$this->e($settings['au_login'])?>">
                    </div>
                    <div class="form-group float-label-control">
                        <label for="">Пароль</label>
                        <input type="password" name="au_password" id="password"  required class="form-control setting-box" placeholder="" value="<?php if ($settings['au_password']): ?>*********<?php endif ?>">
                    </div>
                    <div class="form-group float-label-control">
                        <label for="">Путь к файлу и папке расписания</label>
                        <input type="text" name="au_path" id="filepath" required class="form-control setting-box" placeholder="files/upd/sched.xml" value="<?=$this->e($settings['au_path'])?>">
                    </div>
                    <div class="row">
                        <div class="btn-group">
                            <button type="button" id="testconnect" class="btn btn-c btn-sm smooth"><i class="fa fa-question-circle" aria-hidden="true"></i> Тест подключения</button>
                            <button type="button" id="updatenow" class="btn btn-a btn-sm smooth" <?php if (!$settings['au_status']): ?>disabled<?php endif ?>><i class="fa fa-refresh" aria-hidden="true"></i> Обновить сейчас</button>
                            <button type="button" id="savesettings" class="btn btn-b btn-sm smooth"><i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить параметры</button>
                        </div>
                        </div>
                    <div class="form-group float-label-control">
                        <div class="row"><div id="testresult"></div></div>
                    </div>

                </form>
            </div>
            <div class="col-sm-7">
                <h3 class="page-header">Журнал обновления <i>- показаны последние 25 записей</i></h3>
                <table class="table">
                    <?php if ($logs): ?>
                    <thead><tr><th>#</th><th>Подключение</th><th>Результат</th><th>Дата/время</th><th>Начальная/итоговая ревизия</th><th>Действия</th></tr></thead>
                    <tbody>
                    <?php foreach($logs as $l): ?>
                    <tr><td><?=$this->e($l['id'])?></td>
                        <td><?php if ($l['result'] > 0 && $l['success']): ?><i class="fa fa-check" aria-hidden="true"></i> ОК<?php else: ?><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Неудачно<?php endif ?></td>
                        <td><?php if ($l['result'] > 0 && $l['success']): ?><?php if ($l['revupdated']): ?><i class="fa fa-arrow-up" aria-hidden="true"></i> Обновлено<?php else: ?><i class="fa fa-bars" aria-hidden="true"></i> Без изменений<?php endif ?><?php else: ?><?=$this->e($l['error'])?><?php endif ?></td>
                        <td><?=$this->e($l['time'])?></td>
                        <td><?php if ($l['revupdated']): ?><?=$this->e(substr($l['oldrev'],0,8))?>/<?=$this->e(substr($l['newrev'],0,8))?><?php else: ?><?=$this->e(substr($l['oldrev'],0,8))?>/<?=$this->e(substr($l['oldrev'],0,8))?><?php endif ?></td>
                        <td><?php if ($l['revupdated']): ?><a target="_blank"href = "../admin/actions/rev/<?=$this->e($l['newrev'])?>/journal"class="btn btn-a btn-sm smooth"><i class="fa fa-info-circle" aria-hidden="true"></i></a><?php else: ?>-<?php endif ?></td>
                    </tr>
                    </tr>
                    <?php endforeach ?>
                    </tbody>
                    <?php else: ?><p><i>Нет записей журнала.</i></p><?php endif ?>
                </table>
            </div>
        </div>
</div>
<script src="<?=$this->e(ROOT_URL)?>assets/js/ftp_settings.js"></script>