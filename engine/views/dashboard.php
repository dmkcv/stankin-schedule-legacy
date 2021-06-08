<?php $this->layout('shared/admin_layout', ['title' => 'Работа с расписанием']) ?>
<div class="container">
    <?php if ($this->e($msg)): ?><div class="row"><message class="great">Расписание успешно загружено и отправлено на обработку. Она займёт от <b>четырех до восьми минут</b>. При успешном завершении статус обработки на странице будет обновлен. Пока же можно откинуться на спинку кресла и попить чай.</message></div><?php endif ?>
    <div class="row">
        <div class="col c4"><span class="status">Статус API расписания: <?php if (LATEST_REV): ?><span class="on">работает</span><?php else: ?><span class="off">не работает</span><?php endif ?></span></div>
        <div class="col c8 add"><a class="btn btn-b btn-sm smooth upload"><i class="fa fa-plus-square" aria-hidden="true"></i> Загрузить новое расписание</a></div>
        <form action="../admin/actions/handlexml" method="post" enctype="multipart/form-data">
            <input class="fupload" id="xml" type="file" accept="text/xml" onchange="this.form.submit()" name="xml"/>
            <form>
    </div>
    <?php if ($files): ?>
    <table class="table">
        <thead>
        <tr>
            <th>Файл</th>
            <th>Актуальность</th>
            <th>Ревизия</th>
            <th>Статус</th>
            <th>Обработан</th>
            <th>Загружен/Модиф.</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($files as $f): ?>
        <tr>
            <td><?=$this->e($f['name'])?><?php if ($f['buildlog']['warnings']): ?><p><sm-message class="error"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Обнаружены ошибки обработки</sm-message></p><?php endif ?></td>
            <td><?=$this->e($f['date_from'])?><hr><?=$this->e($f['date_to'])?></td>
            <td><?=$this->e($f['revision'])?></td>
            <td><?php if (LATEST_REV == $f['revision']): ?><span class="on">активен</span><?php else: ?>выключен<?php endif ?></td>
            <td><i class="fa fa-file-code-o" aria-hidden="true"></i> Файлы <?php if ($f['check']['file']): ?><span class="on"><i class="fa fa-check" aria-hidden="true"></i></span><?php else: ?><i class="fa fa-times" aria-hidden="true"></i><?php endif ?> <hr> <i class="fa fa-database" aria-hidden="true"></i> БД <?php if ($f['check']['db']): ?><span class="on"><i class="fa fa-check" aria-hidden="true"></i></span><?php else: ?><i class="fa fa-times" aria-hidden="true"></i><?php endif ?></td>
            <td><?=$this->e($f['added'])?><hr><?php if ($f['mtime_unix'] > 149000000): ?><?=$this->e($f['mtime'])?><?php else: ?>нет данных<?php endif ?></td>
            <td><?php if ($f['check']['file']): ?><?php if (LATEST_REV == $f['revision']): ?><a class="btn btn-a btn-sm smooth"href = "../admin/actions/<?=$this->e($f['id'])?>/disable"><i class="fa fa-toggle-on" aria-hidden="true"></i> Выключить</a><?php else: ?><a class="btn btn-b btn-sm smooth"href = "../admin/actions/<?=$this->e($f['id'])?>/enable"><i class="fa fa-toggle-off" aria-hidden="true"></i> Включить</a><?php endif ?> <a onclick = "return confirm ('Это действие удалит все файлы и соответствующую базу данных! Продолжить?')" class="btn btn-c btn-sm smooth" href = "../admin/actions/<?=$this->e($f['id'])?>/remove"><i class="fa fa-trash-o" aria-hidden="true"></i> Удалить</a><?php else: ?><div><i class="fa fa-cogs" aria-hidden="true"></i> Обработка...</div><div><progress max="100" value="<?=$this->e($f['buildprogress'])?>">Обработано <span id="value"><?=$this->e($f['buildprogress'])?></span>%</progress>&nbsp;<a class="btn btn-b btn-sm smooth revcheck" data-rev="<?=$this->e($f['revision'])?>"><i class="fa fa-question status-icon<?=$this->e($f['revision'])?>" aria-hidden="true"></i> Статус</a></div><?php endif ?><div id="status<?=$this->e($f['revision'])?>"></div>
                <hr><div class="btn-group"><?php if ($f['buildlog']['log']): ?><a class="btn btn-a btn-sm smooth" target="_blank"href = "../admin/actions/<?=$this->e($f['id'])?>/journal"><i class="fa fa-info-circle" aria-hidden="true"></i> Журнал</a><?php endif ?> <a class="btn btn-a btn-sm smooth revrestart" data-rev="<?=$this->e($f['revision'])?>"><i class="fa fa-refresh restart-icon<?=$this->e($f['revision'])?>" aria-hidden="true"></i> Перезапуск</a></div>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php else: ?>
        <p>Файлов нет, загрузите новый :)</p>
    <?php endif ?>
</div>
<script src="<?=$this->e(ROOT_URL)?>assets/js/dashboard.js"></script>