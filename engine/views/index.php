<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REST API для расписания Станкина</title>
    <link href="<?=$this->e(ROOT_URL)?>assets/min.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="wrapper">
        <h2 class="form-signin-heading"><i>REST API для расписания Станкина</i></h2>
        <h3>Здравстуйте!</h3>
        <span>Сейчас API <?php if (LATEST_REV): ?><span class="on">работает</span> (используя расписание с ревизией №<?=substr(LATEST_REV,0,8)?>) и Вы <?php else: ?><span class="off">не работает</span>, но Вы все равно <?php endif ?> можете ознакомиться с <a href = "manual/">документацией</a> по его использованию :3</span></span></div>
    </div>
</div>
</body>
</html>