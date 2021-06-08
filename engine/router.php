<?php
    $router = new AltoRouter();

    $router->setBasePath(BASEPATH);
    $router->addMatchTypes(array('CsD' => '[0-9,]++'));

    $router->map('GET', '', 'AdminController::renderIndex');

    if (preg_match("/\/admin/i", $_SERVER['REQUEST_URI'])) {
        session_start(); // Сессия стартует, только если запрошена админка
    }

    // Для неавторизованного работает логин
    if (@!array_key_exists('loggedIn',$_SESSION)) {
        $router->map('GET', 'admin', 'AdminController::renderLogin');
        $router->map('POST', 'admin/login', 'AdminController::handleLogin');
    }

    // Администратор авторизован
    if (@array_key_exists('loggedIn',$_SESSION)) {
     $router->map('GET', 'admin', function() {
            _redirect('admin/dashboard');
     });

    $router->map('GET', 'admin/dashboard', 'ScheduleController::renderDashboard');
    $router->map('POST', 'admin/actions/handlexml', 'ScheduleController::handleXMLUpload');

    $router->map('GET', 'admin/logout/[i:c]', function( $c ) {
        return AdminController::handleLogout($c);
    });

    // Действия с расписанием
    $router->map('GET', 'admin/actions/[i:id]/disable', function( $id ) {
        return ScheduleController::disableByID($id);
    });

    $router->map('GET', 'admin/actions/[i:id]/enable', function( $id ) {
        return ScheduleController::enableByID($id);
    });

    $router->map('GET', 'admin/actions/[i:id]/remove', function( $id ) {
        return ScheduleController::removeByID($id);
    });

    $router->map('GET', 'admin/actions/[i:id]/journal', function( $id ) {
        return ScheduleController::renderJournal($id);
    });

    $router->map('GET', 'admin/actions/rev/[a:rev]/journal', function( $rev ) {
        return ScheduleController::renderJournalByRev($rev);
    });

    $router->map('GET', 'admin/actions/[i:id]/journal/download', function( $id ) {
        return ScheduleController::downloadBuildLog($id);
    });

    $router->map('GET', 'admin/sysjournal', 'ScheduleController::renderJournal');

    // Менеджер задач и действия с процессами
    $router->map('GET', 'admin/taskmanager', 'TaskManagerController::renderManager');

    $router->map('GET', 'admin/actions/task/[i:id]/kill', function( $id ) {
            return TaskManagerController::killProcess($id);
    });

    $router->map('GET', 'admin/actions/task/[i:id]/restart', function( $id ) {
            return TaskManagerController::restartProcess($id);
    });

    $router->map('GET', 'admin/actions/task/check_revision', 'TaskManagerController::checkRevisionProcessing');
    $router->map('GET', 'admin/actions/task/restart_revision', 'TaskManagerController::restartRevision');

    // Автообновление
     $router->map('GET', 'admin/scheduleupdate', 'ScheduleUpdateController::renderSettings');
     $router->map('GET', 'admin/scheduleupdate/update_dynamic', 'ScheduleUpdateController::startUpdate');
     $router->map('POST', 'admin/scheduleupdate/test_dynamic', 'ScheduleUpdateController::testConnection');
     $router->map('POST', 'admin/actions/save_ausettings', 'ScheduleUpdateController::saveAUSettings');
}

if (LATEST_REV) { // Если расписание включено, то методы доступны любому пользователю
    $router->map('GET', 'api/settings', 'BaseAPIController::getSettings');
    $router->map('GET', 'api/groups', 'BaseAPIController::getClasses');
    $router->map('GET', 'api/chairs', 'BaseAPIController::getChairs');
    $router->map('GET', 'api/specialities', 'BaseAPIController::getSpecialities');
    $router->map('GET', 'api/studytypes', 'BaseAPIController::getStudyTypes');
    $router->map('GET', 'api/rooms', 'BaseAPIController::getRooms');
    $router->map('GET', 'api/timetable', 'BaseAPIController::getTimes');

    $router->map('GET', 'api/teachers', 'BaseAPIController::getTeacherList');
    $router->map('GET', 'api/teachers/[CsD:id]', function( $id ) {
        return BaseAPIController::getTeacherList($id);
    });

    $router->map('GET', 'api/schedule/group/[i:id]', function( $id ) {
        return ScheduleAPIController::getScheduleByGroup($id);
    });

    $router->map('GET', 'api/schedule/teacher/[i:id]', function( $id ) {
        return ScheduleAPIController::getScheduleByTeacher($id);
    });

    $router->map('GET', 'api/schedule/room/[i:id]', function( $id ) {
        return ScheduleAPIController::getScheduleByRoom($id);
    });

    $router->map('GET', 'api/suggestions/groups', 'BaseAPIController::getClassesSuggestions');
    $router->map('GET', 'api/suggestions/teachers', 'BaseAPIController::getTeachersSuggestions');
}

$match = $router->match();

if( $match && is_callable( $match['target'] ) ) {
    call_user_func_array( $match['target'], $match['params'] );
} else {
    http_response_code(404);
    exit('404');
}