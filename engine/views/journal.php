<?php $this->layout('shared/admin_layout', ['title' => 'Журнал обработки']) ?>
<script src="<?=$this->e(ROOT_URL)?>assets/js/libs/mark.min.js"></script>
<div class="container">
    <div class="wrapper">
        <h3><?php if ($syslog || empty($syslog) && empty($rev)): ?>Общий журнал системы:<?php else: ?>Журнал обработки файла с ревизией <i><?=$rev?></i><?php endif ?></h3>
        <message class="great"><b>Упомнинания ошибок (если они есть) подсвечены <mark>желтым</mark> маркером</b></p>
        <p><i>Пример ошибки при обработке:</i> [2017-01-01 00:00:01] raspprod.WARNING: %ревизия%:FAIL to construct DB {"err":"Unknown column 'mtime' in 'field list'"}</p></message>
        <?php if ($log['log']): ?>
        <h4>Собственный журнал ревизии:</h4>
        <p><pre><?=$log['log']?></pre></p>
        <p><a href = "journal/download" class="btn btn-b btn-sm smooth">Скачать журнал</a></p>
        <?php endif ?>
        <?php if (!$log['log'] && !$syslog): ?><p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <i>Такой ревизии или запрошенного журнала нет на сервере.</i></p>
        <?php endif ?>
        <?php if ($log['rev_main']): ?><hr>
        <h4>Упоминания в общем журнале системы:</h4>
        <p><pre><?=$log['rev_main']?></pre></p>
        <?php endif ?>
        <?php if ($syslog): ?><hr>
            <p><pre><?=$syslog?></pre></p>
        <?php endif ?>
    </div>
</div>
<script>
    var context = document.querySelector(".wrapper");
    var instance = new Mark(context);
    instance.markRegExp(/warning|error/gmi);
</script>