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
    <script src="<?=$this->e(ROOT_URL)?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=$this->e(ROOT_URL)?>assets/js/general.js"></script>
    <script src="<?=$this->e(ROOT_URL)?>assets/js/utils.js"></script>
</head>
<body>
<?=$this->section('content')?>
</body>
</html>