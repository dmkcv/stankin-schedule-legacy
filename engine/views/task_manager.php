<?php $this->layout('shared/admin_nohead_layout', ['title' => 'Менеджер задач']) ?>
<?php if ($this->e(@$msg)): ?><message class="<?=$this->e($msg_type)?>"><b><?=$this->e($msg)?></b> Страница обновится через 5 секунд...</message><div class="form-group">&nbsp;</div><?php endif ?>
<?php if ($tasks): ?>
<table class="table">
        <thead>
        <tr>
            <th>ID процесса</th>
            <th>Команда</th>
            <th>Ревизия</th>
            <th>Полный аргумент процесса</th>
            <th>Действия</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach($tasks as $t): ?>
    <tr>
        <td><?=$this->e($t[3])?></td>
        <td><?=$this->e($t[1])?></td>
        <td><?=$this->e($t[2])?></td>
        <td><?=$this->e(str_replace(CLI_KEY, '<CLI_KEY>', $t[0]))?></td>
        <td><a onclick = "return confirm ('Это действие прервет процесс обработки! Продолжить?')" class="btn btn-c btn-sm smooth" href = "../admin/actions/task/<?=$this->e($t[3])?>/kill">Завершить</a>
            <a onclick = "return confirm ('Это действие прервет процесс обработки (но перезапустит его же)! Продолжить?')" class="btn btn-c btn-sm smooth" href = "../admin/actions/task/<?=$this->e($t[3])?>/restart">Перезапуск</a></td>
    </tr>
<?php endforeach ?>
</tbody>
</table>
<div class="center-block col-md-4 text-center" style="float: none;">
    <div class="form-group">&nbsp;</div>
    <div><input type="button" class="btn btn-a btn-sm" value="Закрыть" onclick="window.close();"></div>
</div>
<?php else: ?>
    <div class="row">
        <div class="center-block col-md-4 text-center" style="float: none;">
            <i>Нет активных процессов или задач, выполняемых в фоне</i>
            <div class="form-group">&nbsp;</div>
            <div><input type="button" class="btn btn-a btn-sm" value="Закрыть" onclick="window.close();"></div>
        </div>
    </div>
    <script>
        setInterval(function(){window.location = window.location.pathname}, 10000);
    </script>
<?php endif ?>
<?php if (@$inj_refresh): ?>
<script>
    setInterval(function(){window.location = "../../../taskmanager"}, 5000);
</script>
<?php endif ?>