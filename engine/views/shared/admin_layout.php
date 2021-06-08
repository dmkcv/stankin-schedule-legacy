<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$this->e($title)?></title>
    <link href="<?=$this->e(ROOT_URL)?>assets/css/min.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/css/bootstrap-plugin.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/css/login.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/css/admin.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="<?=$this->e(ROOT_URL)?>assets/js/libs/jquery-3.2.1.min.js"></script>
    <script src="<?=$this->e(ROOT_URL)?>assets/js/general.js"></script>
    <script src="<?=$this->e(ROOT_URL)?>assets/js/utils.js"></script>
</head>
<body>
<nav class="nav" tabindex="-1" onclick="this.focus()">
    <div class="container">
        <a class="pagename current" href="#">API: расписание</a>
        <a href="<?=$this->e(ROOT_URL)?>admin/dashboard"><i class="fa fa-calendar" aria-hidden="true"></i> Панель управления</a>
        <div class="dropdown wpd btn dropbutton"><i class="fa fa-cog" aria-hidden="true"></i> Утилиты
            <div class="pulldown broad-pulldown">
                <ul>
                    <li><a href="<?=$this->e(ROOT_URL)?>admin/scheduleupdate"><i class="fa fa-magic" aria-hidden="true"></i> Автообновление расписания</a></li>
                    <hr>
                    <li><a onclick="PopupCenter('taskmanager', '', '1000', '400');"><i class="fa fa-tasks" aria-hidden="true"></i> Менеджер процессов</a></li>
                    <li><a href="<?=$this->e(ROOT_URL)?>admin/sysjournal"><i class="fa fa-file-text-o" aria-hidden="true"></i> Общий журнал</a></li>
                </ul>
            </div>
        </div>
        <a href="../admin/logout/<?=$this->e(gen_logout_key())?>"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a>
    </div>
</nav>
<button class="btn-close btn btn-sm">×</button>
<?=$this->section('content')?>
</body>
</html>