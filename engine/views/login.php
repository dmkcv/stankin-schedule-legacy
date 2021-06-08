<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Администрирование - "Расписание"</title>
    <link href="<?=$this->e(ROOT_URL)?>assets/css/min.css" rel="stylesheet" type="text/css">
    <link href="<?=$this->e(ROOT_URL)?>assets/css/login.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="wrapper">
        <form class="form-signin" method="post" action="admin/login">
            <h2 class="form-signin-heading"><i>Администрирование компонента API "Расписание"</i></h2>
            <h3>Представьтесь, пожалуйста</h3>
            <?php if ($this->e($msg)): ?><message class="warning">Логин или пароль введены неверно</message><?php endif ?>
            <input type="text" class="form-control" name="login" placeholder="Логин" required="" autofocus="" />
            <input type="password" class="form-control" name="password" placeholder="Пароль" required=""/>
            <button class="btn btn-b btn-sm btn-primary btn-block" type="submit">Войти</button>
        </form>
    </div>
</div>
</body>
</html>