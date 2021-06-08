<?php

/**
 * Project: raspprod
 * Date: 02.04.2017
 * Time: 20:52
 */
class BaseAPIController
{
    /**
     * @api {get} /settings Получение базовой информации о доступном расписании
     * @apiVersion 0.1.1
     * @apiName GetSettings
     * @apiGroup Settings
     *
     * @apiSuccess {String} rev Ревизия текущего расписания
     * @apiSuccess {Date} begin_date  Дата начала актуальности расписания (обычно начало семестра)
     * @apiSuccess {Date} end_date  Дата конца актуальности расписания (соотвественно, конец семестра)
     * @apiSuccess {Int} generated  Время генерации расписания на сервере API
     * @apiSuccess {Int} updated  Время последнего успешного автообновления расписания
     *
     * @apiDescription Информация может пригодиться при кэшировании расписания и для быстрой проверки актуальности.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      {
     *      "rev":"63c21be72564134989c2d1ddadbb2cca",
     *      "begin_date":"01.09.2016",
     *      "end_date":"25.12.2016",
     *      "generated":1491343159,
     *      "updated":1491349128
     *      }
     */
    public static function getSettings () {
        $settings = file_get_contents(REV_PATH. '/settings.json');
        $sarray = json_decode($settings, true);
        if (array_key_exists('rev', $sarray)) $sarray['updated'] = ScheduleActions::getUpdateInfo($sarray['rev']);
        Registry::get('response')
            ->write(json_encode($sarray))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->header('X-Begin-Date', strtotime($sarray['begin_date']))
            ->header('X-End-Date', strtotime($sarray['end_date']))
            ->send();
    }

    /**
     * @api {get} /groups Группы
     * @apiVersion 0.1.1
     * @apiName GetGroups
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID группы
     * @apiSuccess {String} name  Название группы
     * @apiSuccess {Int} speciality_id  ID специальности, смотри /specialities
     * @apiSuccess {Int} semester  Номер семестра, на котором обучается группа
     *
     * @apiDescription Возвращает массив групп без разделения по страницам.
     * Некоторые группы могут дублироваться, подразделяясь на большие подгруппы по спецальностям.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *      {"id":"0",
     *       "name":"ИДБ-13-12",
     *       "speciality_id":"48",
     *       "semester":"5"},
     *       ...
     *     ]
     */
    public static function getClasses () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/classes.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /chairs Кафедры
     * @apiVersion 0.1.1
     * @apiName GetChairs
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID кафедры
     * @apiSuccess {String} short_name  Краткое название кафедры (аббревиатура)
     * @apiSuccess {String} full_name  Полное название кафедры
     *
     * @apiDescription Возвращает массив кафедр без разделения по страницам.
     * Может быть использовано для связывания преподавателей по кафедрам.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      [
     *          {"id":"0",
     *           "short_name":"АСОИиУ",
     *           "full_name":"Автоматиз. системы обработки инф. и управления"}
     *       ...
     *      ]
     */
    public static function getChairs () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/chairs.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /specialities Специальности
     * @apiVersion 0.1.1
     * @apiName GetSpecialities
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID специальности
     * @apiSuccess {String} short_name  Краткое название специальности
     * @apiSuccess {String} full_name  Полное название специальности
     *
     * @apiDescription Возвращает массив специальностей без разделения по страницам.
     * В исходном виде группы разделены по специальностям, но в данной версии API у групп они не отражены.
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      [
     *          {"id":"131",
     *          "short_name":"38.03.02",
     *          "full_name":"Менеджмент"},
     *           ...
     *      ]
     */
    public static function getSpecialities () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/specialities.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /studytypes Типы занятий
     * @apiVersion 0.1.1
     * @apiName GetStudyTypes
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID типа
     * @apiSuccess {String} full_name  Полное название типа
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      [
     *         {"id":"1",
     *          "full_name":"лекция"},
     *           ...
     *      ]
     */
    public static function getStudyTypes () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/study_types.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /rooms Аудитории
     * @apiVersion 0.1.1
     * @apiName GetRooms
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID аудитории
     * @apiSuccess {String} name  Номер аудитории
     * @apiSuccess {Int} capacity  Количество мест в аудитории
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      [
     *         {"id":"0",
     *          "name":"0202",
     *          "capacity":"30"},
     *           ...
     *      ]
     */
    public static function getRooms () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/rooms.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /timetable График пар
     * @apiVersion 0.1.1
     * @apiName GetTimetable
     * @apiGroup Dictionaries
     *
     * @apiSuccess {Int} id ID промежутка, номер пары
     * @apiSuccess {String} time  Временной интервал
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *      [
     *         {"id":1,
     *          "time":"08:30 - 10:10"},
     *           ...
     *      ]
     */
    public static function getTimes () {
        Registry::get('response')
            ->write(file_get_contents(REV_PATH. '/times.json'))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /teachers Преподаватели
     * @apiVersion 0.1.1
     * @apiName GetTeachers
     * @apiGroup Dictionaries
     *
     * @apiParam {Int} id Перечень ID преподавателей через запятую
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/teachers?id=2,294,361
     *
     * @apiDescription Возвращает массив информации о преподавателях с указанием кафедры и "роли" в конкретном расписании.
     * В случае, если преподаватель не задан на предмет (в диспетчерской), то будет возвращена пустая ("Фамилия И.О.") карточка с "ролью" <code>tsFake</code>.
     *
     * @apiSuccess {Int} count Количество записей в массиве ответа
     * @apiSuccess {Array[]} teachers       Массив информации о преподавателях
     * @apiSuccess {Int} teachers.id ID преподавателя
     * @apiSuccess {String} teachers.surname  Фамилия
     * @apiSuccess {String} teachers.first_name  Первая буква имени
     * @apiSuccess {String} teachers.second_name  Первая буква отчества
     * @apiSuccess {Int} teachers.chair_id  ID кафедры, к которой приписан предподаватель
     * @apiSuccess {String} teachers.status  "Роль" в расписании: <code>tsRegular</code> - конкретное лицо с ФИО, <code>tsFake</code> - заглушка без каких-либо данных (используется при заменах и сложных конфигурациях занятий)
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *
     *         {"count":3,
     *          "teachers":[
     *              {"id":"2",
     *                  "surname":"Преподаватель",
     *                  "first_name":"И",
     *                  "second_name":"О",
     *                  "chair_id":"-1",
     *                  "status":"tsFake"},
     *             {"id":"294",
     *                  "surname":"Левин",
     *                  "first_name":"М",
     *                  "second_name":"В",
     *                  "chair_id":"7",
     *                  "status":"tsRegular"},
     *              {"id":"361",
     *                  "surname":"Поляков",
     *                  "first_name":"С",
     *                  "second_name":"Д",
     *                  "chair_id":"7",
     *                  "status":"tsRegular"}]}
     */
    public static function getTeacherList ($cid = null) {
        $result = array();
        $id = (!$cid) ? get_http_value('id', $_GET, 'csd') : $cid;
        $ids = array_unique(array_filter(explode(',', $id)));
        $tarray = json_decode(file_get_contents(REV_PATH. '/teachers.json'), true);
        if (!empty($ids)) {
            foreach ($ids as $i) {
                $key = array_search($i, array_column($tarray, 'id'));
                if (is_int($key)) {
                    $result[] = $tarray[$key];
                }
            }
        }
        Registry::get('response')
            ->write(json_encode(array('count' =>count($result), 'teachers' =>$result)))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /suggestions/classes Поиск по группам
     * @apiVersion 0.1.1
     * @apiName GetClassesSuggestions
     * @apiGroup Suggestions
     *
     * @apiSuccess {String} search  Строка для поиска
     *
     * @apiDescription Возвращает массив групп, содержащих в имени запрошенную подстроку
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/suggestions/classes?search=1312
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *      {"id":"0",
     *       "name":"ИДБ-13-12",
     *       "speciality_id":"48",
     *       "semester":"5"}
     *     ]
     */
    public static function getClassesSuggestions () {
        $result = array();
        $string = get_http_value('search', $_GET, 'alnum');
        $carray = json_decode(file_get_contents(REV_PATH. '/classes.json'), true);
        $ac = array_column($carray, 'name', 'id');
        foreach ($ac as $key => $group) {
            $string = preg_replace('/[^[:alnum:][:space:]]/u', '', $string);
            $string = preg_replace("/\s/", '', $string);
            $group = preg_replace('/[^[:alnum:][:space:]]/u', '', $group);
            if (mb_stripos($group, $string, 0, 'UTF-8') !== false) {
                $result[] = $carray[$key];
            }
        }
        krsort($result);
        $result = array_values($result);
        Registry::get('response')
            ->write(json_encode($result))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }

    /**
     * @api {get} /suggestions/teachers Поиск по преподавателям
     * @apiVersion 0.1.1
     * @apiName GetTeachersSuggestions
     * @apiGroup Suggestions
     *
     * @apiSuccess {String} search  Строка для поиска
     *
     * @apiDescription Возвращает массив преподавателей, содержащих в фамилии запрошенную подстроку
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/suggestions/teachers?search=левч
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *      {"id":"296",
     *      "surname":"Левчук",
     *      "first_name":"В",
     *      "second_name":"И",
     *      "chair_id":"21",
     *      "status":"tsRegular"},
     *      {"id":"295",
     *      "surname":"Левченко",
     *      "first_name":"А",
     *      "second_name":"Н",
     *      "chair_id":"7",
     *      "status":"tsRegular"}
     *     ]
     */
    public static function getTeachersSuggestions () {
        $result = array();
        $string = get_http_value('search', $_GET, 'alnum');
        $carray = json_decode(file_get_contents(REV_PATH. '/teachers.json'), true);
        $ac = array_column($carray, 'surname', 'id');
        foreach ($ac as $key => $teacher) {
            $string = preg_replace('/[^[:alnum:][:space:]]/u', '', $string);
            $teacher = preg_replace('/[^[:alnum:][:space:]]/u', '', $teacher);
            if (mb_stripos($teacher, $string, 0, 'UTF-8') !== false) {
                $result[] = $carray[array_search($key, array_column($carray, 'id'))];
            }
        }
        foreach ($result as $t) {
            if (mb_strtolower(mb_substr($t['surname'], 0, mb_strlen($string, 'UTF-8'), 'UTF-8'), 'UTF-8') == mb_strtolower($string, 'UTF-8')) {
                array_unshift($result, $t);
                $result = array_values(array_unique($result, SORT_REGULAR));
            }
        }
        Registry::get('response')
            ->write(json_encode($result))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->send();
    }
}