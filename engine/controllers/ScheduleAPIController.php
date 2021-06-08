<?php

/**
 * Project: raspprod
 * Date: 02.04.2017
 * Time: 20:52
 */
class ScheduleAPIController
{
    private static function getSettingsForInternal () {
        return json_decode(file_get_contents(REV_PATH. '/settings.json'), true);
    }

    /**
     * @api {get} /schedule/group/:i Расписание для группы
     * @apiVersion 0.1.1
     * @apiName GetByGroup
     * @apiGroup Schedules
     * @apiSampleRequest off
     *
     * @apiParam {Int} id ID группы
     * @apiParam {String} [time] Номер недели или конкретная дата
     * @apiParam {Int} [type] Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/schedule/group/25?time=6&type=1
     *
     * @apiDescription Возвращает массив с расписанием группы на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>).
     * Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.
     *
     * @apiSuccess {Int} lid ID конкретной "нагрузки" (повторяющегося события)
     * @apiSuccess {Int} day  День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)
     * @apiSuccess {Int} hour  Номер пары (ID слота времени в /timetable)
     * @apiSuccess {Int} group ID группы
     * @apiSuccess {Int} subgroup ID подгруппы (<code>0</code> - вся группа, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)
     * @apiSuccess {Array[]} teacher  Блок преподавателя
     * @apiSuccess {Int} teacher.id  ID преподавателя
     * @apiSuccess {String} teacher.name  ФИО преподавателя
     * @apiSuccess {Array[]} subject  Блок предмета
     * @apiSuccess {Int} subject.id  ID предмета
     * @apiSuccess {String} subject.name Название предмета
     * @apiSuccess {Array[]} type  Блок типа занятия
     * @apiSuccess {Int} type.id  ID типа занятия
     * @apiSuccess {String} type.name  Название типа занятия
     * @apiSuccess {Int} week  Номер недели с начала семестра
     * @apiSuccess {Array[]} room  Блок аудитории
     * @apiSuccess {Int} room.id  ID аудитории
     * @apiSuccess {String} room.name  Номер аудитории
     * @apiSuccess {Bool} pair  Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *
     *      [
     *          {
                    "lid":82,
                    "day":5,
                    "hour":2,
                    "group":141,
                    "subgroup":0,
                    "week":2,
                    "pair":false,
                    "teacher":{
                        "id":375,
                        "name":"Байкова Е.А."
                        },
                    "subject":{
                        "id":20,
                        "name":"Анализ состояния производства при сертификации продукции"
                        },
                    "type":{
                        "id":100,
                        "name":"лекция"
                        },
                    "room":{
                        "id":73,
                        "name":"324"
                        }
                    }
     *          ...
     *      ]
     *
     */
    public static function getScheduleByGroup ($id) {
        $result = array();
        $week = 1;
        $time = get_http_value('time', $_GET, 'alnum');
        $type = get_http_value('type', $_GET, 'int', 1);
        $name = get_http_value('name', $_GET); // Для поиска группы по имени

        $class_file = REV_PATH. '/classes.json';
        $class_array = json_decode(file_get_contents($class_file), true);

        if ($name) { // Решение поиска группы, если у неё изменился ID после смены расписания, базовый вариант миграции
            $real_group = array_search($name, array_column($class_array, 'name'));
            if ($real_group) {
                $id = $class_array[$real_group]['id'];
            }
        }

        $readable_name =  $class_array[$id]['name'];
        $file = REV_PATH."/schedule_class_{$id}.json";

        if (file_exists($file)) {
            $sarray = array_unique(json_decode(file_get_contents($file), true), SORT_REGULAR);

            if (!$time) {
                foreach ($sarray as $l) {
                    $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                    $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                    $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                    $result[] = $l;
                }
            } else {
                $begin_week = new DateTime(self::getSettingsForInternal()['begin_date']);
                $begin_week_num = $begin_week->format('W');

                switch ($type) {
                    case 1:
                        $week = (int)$time; // Уже хорошо, т.к. передана неделя от начала семестра
                        break;
                    case 2:
                        $week = (int)$time - $begin_week_num; // Передана неделя от начала года
                        break;
                    case 3:
                        $date_regex = '/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
                        preg_match($date_regex, $time, $matches);
                        $time = array_key_exists(0, $matches) ? $matches[0] : self::getSettingsForInternal()['begin_date'];
                        $date_week = new DateTime($time);
                        $date_week_num = $date_week->format('W');
                        $week = ($date_week_num + 1) - $begin_week_num; // Передана дата в формате ДД.ММ.ГГГГ
                        break;
                }

                if (!empty($week)) {
                    foreach ($sarray as $l) {
                        if ($l['week'] == $week) {
                            $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                            $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                            $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                            $result[] = $l;
                        }
                    }
                }
            }
        }

        Registry::get('response')
            ->write(json_encode($result))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->header('X-Begin-Date', REV_BEGIN)
            ->header('X-End-Date', REV_END)
            ->header('X-Begin-Date-H', REV_BEGIN_HUMAN)
            ->header('X-End-Date-H', REV_END_HUMAN)
            ->header('X-Group-Id', (int)$id)
            ->header('X-Group-Name', base64_encode($readable_name))
            ->status($result ? 200 : 404)
            ->send();
    }

    /**
     * @api {get} /schedule/teacher/:i Расписание для преподавателя
     * @apiVersion 0.1.1
     * @apiName GetByTeacher
     * @apiGroup Schedules
     * @apiSampleRequest off
     *
     * @apiParam {Int} id ID преподавателя
     * @apiParam {String} [time] Номер недели или конкретная дата
     * @apiParam {Int} [type] Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/schedule/teacher/160
     *
     * @apiDescription Возвращает массив с расписанием преподавателя на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>).
     * Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.
     *
     * @apiSuccess {Int} lid ID конкретной "нагрузки" (повторяющегося события)
     * @apiSuccess {Int} day  День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)
     * @apiSuccess {Int} hour  Номер пары (ID слота времени в /timetable)
     * @apiSuccess {Int} subgroup ID подгруппы (<code>0</code> - без разделения, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)
     * @apiSuccess {Array[]} teacher  Блок преподавателя
     * @apiSuccess {Int} teacher.id  ID преподавателя
     * @apiSuccess {String} teacher.name  ФИО преподавателя
     * @apiSuccess {Array[]} subject  Блок предмета
     * @apiSuccess {Int} subject.id  ID предмета
     * @apiSuccess {String} subject.name Название предмета
     * @apiSuccess {Array[]} type  Блок типа занятия
     * @apiSuccess {Int} type.id  ID типа занятия
     * @apiSuccess {String} type.name  Название типа занятия
     * @apiSuccess {Int} week  Номер недели с начала семестра
     * @apiSuccess {Array[]} room  Блок аудитории
     * @apiSuccess {Int} room.id  ID аудитории
     * @apiSuccess {String} room.name  Номер аудитории
     * @apiSuccess {Bool} pair  Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)
     * @apiSuccess {Array[]} groups  Массив с ID и названиями групп, присутствующих на паре
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *
     *      [
     *         {
                "lid":271,
                "day":5,
                "hour":3,
                "subgroup":1,
                "week":10,
                "pair":true,
                "teacher":{
                    "id":141,
                    "name":"Позднеев Б.М."
                },
                "subject":{
                    "id":36,
                    "name":"Высокоэффективные технологии и оборудование современных производств"
                },
                "type":{
                    "id":300,
                    "name":"лабораторная работа"
                },
                "room":{
                    "id":29,
                    "name":"126"
                },
                "groups":[
                {
                    "id":56,
                    "name":"ИДБ-13-12"
                }
                ]
     *      ]
     *
     */
    public static function getScheduleByTeacher ($id) {
        $result = array();
        $week = 1;
        $time = get_http_value('time', $_GET, 'alnum');
        $type = get_http_value('type', $_GET, 'int', 1);
        $file = REV_PATH."/schedule_teacher_{$id}.json";
        $cfile = REV_PATH. '/classes.json';

        if (file_exists($file)) {
            $sarray = array_unique(json_decode(file_get_contents($file), true), SORT_REGULAR);
            $carray = json_decode(file_get_contents($cfile), true);

            if (!$time) {
                foreach ($sarray as $l) {
                    $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                    $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                    $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                    $result[] = $l;
                }
            } else {
                $begin_week = new DateTime(self::getSettingsForInternal()['begin_date']);
                $begin_week_num = $begin_week->format('W');

                switch ($type) {
                    case 1:
                        $week = (int)$time; // Уже хорошо, т.к. передана неделя от начала семестра
                        break;
                    case 2:
                        $week = (int)$time - $begin_week_num; // Передана неделя от начала года
                        break;
                    case 3:
                        $date_regex = '/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
                        preg_match($date_regex, $time, $matches);
                        $time = array_key_exists(0, $matches) ? $matches[0] : self::getSettingsForInternal()['begin_date'];
                        $date_week = new DateTime($time);
                        $date_week_num = $date_week->format('W');
                        $week = ($date_week_num + 1) - $begin_week_num; // Передана дата в формате ДД.ММ.ГГГГ
                        break;
                }

                if (!empty($week)) {
                    foreach ($sarray as $l) {
                        if ($l['week'] == $week) {
                            $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                            $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                            $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                            $result[] = $l;
                        }
                    }
                }
            }


            for ($i = 0, $iMax = count($result); $i < $iMax; $i++) {
                $classes_inject = array();
                foreach ($result[$i]['groups'] as $class) {
                    $key = array_search($class, array_column($carray, 'id'));
                    $classes_inject[] = array('id' => $class, 'name' => $carray[$key]['name']);
                }
                unset ($result[$i]['groups']);
                $result[$i]['groups'] = $classes_inject;
            }

            $result = array_unique($result, SORT_REGULAR);
        }

        Registry::get('response')
            ->write(json_encode($result))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->status($result ? 200 : 404)
            ->send();
    }

    /**
     * @api {get} /schedule/room/:i Расписание для аудитории
     * @apiVersion 0.1.1
     * @apiName GetByRoom
     * @apiGroup Schedules
     * @apiSampleRequest off
     *
     * @apiParam {Int} id ID аудитории
     * @apiParam {String} [time] Номер недели или конкретная дата
     * @apiParam {Int} [type] Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ
     *
     * @apiExample {curl} Пример запроса:
     *     curl -i https://schedule/api/schedule/room/19
     *
     * @apiDescription Возвращает массив с расписанием загрузки аудитории на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>).
     * Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.
     *
     * @apiSuccess {Int} lid ID конкретной "нагрузки" (повторяющегося события)
     * @apiSuccess {Int} day  День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)
     * @apiSuccess {Int} hour  Номер пары (ID слота времени в /timetable)
     * @apiSuccess {Int} group ID подгруппы (<code>0</code> - без разделения, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)
     * @apiSuccess {Array[]} teacher  Блок преподавателя
     * @apiSuccess {Int} teacher.id  ID преподавателя
     * @apiSuccess {String} teacher.name  ФИО преподавателя
     * @apiSuccess {Array[]} subject  Блок предмета
     * @apiSuccess {Int} subject.id  ID предмета
     * @apiSuccess {String} subject.name Название предмета
     * @apiSuccess {Array[]} type  Блок типа занятия
     * @apiSuccess {Int} type.id  ID типа занятия
     * @apiSuccess {String} type.name  Название типа занятия
     * @apiSuccess {Int} week  Номер недели с начала семестра
     * @apiSuccess {Array[]} room  Блок аудитории
     * @apiSuccess {Int} room.id  ID аудитории
     * @apiSuccess {String} room.name  Номер аудитории
     * @apiSuccess {Bool} pair  Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)
     * @apiSuccess {Array[]} groups  Массив с ID и именами групп, присутствующих в аудитории
     *
     * @apiSuccessExample {json} Success-Response:
     *     HTTP/1.1 200 OK
     *
     *      [
     *         {
                "lid":682,
                "day":3,
                "hour":5,
                "subgroup":0,
                "week":1,
                "pair":false,
                "teacher":{
                "id":367,
                "name":"Гринман И.Р."
                },
                "subject":{
                    "id":113,
                    "name":"Коммерческое право"
                },
                "type":{
                    "id":100,
                    "name":"лекция"
                },
                "room":{
                    "id":106,
                    "name":"441"
                },
                "groups":[
                {
                    "id":183,
                    "name":"ЭПМ-10-1"
                },
                {
                    "id":186,
                    "name":"ЭЭТ-10-5"
                }
                ]
                },
     *          ...
     *      ]
     *
     */
    public static function getScheduleByRoom ($id) {
        $result = array();
        $week = 1;
        $time = get_http_value('time', $_GET, 'alnum');
        $type = get_http_value('type', $_GET, 'int', 1);
        $file = REV_PATH."/schedule_room_{$id}.json";
        $cfile = REV_PATH. '/classes.json';

        if (file_exists($file)) {
            $sarray = array_unique(json_decode(file_get_contents($file), true), SORT_REGULAR);
            $carray = json_decode(file_get_contents($cfile), true);

            if (!$time) {
                foreach ($sarray as $l) {
                    $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                    $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                    $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                    $result[] = $l;
                }
            } else {
                $begin_week = new DateTime(self::getSettingsForInternal()['begin_date']);
                $begin_week_num = $begin_week->format('W');

                switch ($type) {
                    case 1:
                        $week = (int)$time; // Уже хорошо, т.к. передана неделя от начала семестра
                        break;
                    case 2:
                        $week = (int)$time - $begin_week_num; // Передана неделя от начала года
                        break;
                    case 3:
                        $date_regex = '/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/';
                        preg_match($date_regex, $time, $matches);
                        $time = array_key_exists(0, $matches) ? $matches[0] : self::getSettingsForInternal()['begin_date'];
                        $date_week = new DateTime($time);
                        $date_week_num = $date_week->format('W');
                        $week = ($date_week_num + 1) - $begin_week_num; // Передана дата в формате ДД.ММ.ГГГГ
                        break;
                }

                if (!empty($week)) {
                    foreach ($sarray as $l) {
                        if ($l['week'] == $week) {
                            $l['pair'] = ($l['pair'] == 'pkYes') ? true : false;
                            $l['room']['name'] = (!empty($l['room']['name'])) ? $l['room']['name'] : '';
                            $l['room']['id'] = (!empty($l['room']['id'])) ? $l['room']['id'] : '';
                            $result[] = $l;
                        }
                    }
                }
            }


            for ($i = 0, $iMax = count($result); $i < $iMax; $i++) {
                $classes_inject = array();
                foreach ($result[$i]['groups'] as $class) {
                    $key = array_search($class, array_column($carray, 'id'));
                    $classes_inject[] = array('id' => $class, 'name' => $carray[$key]['name']);
                }
                unset ($result[$i]['groups']);
                $result[$i]['groups'] = $classes_inject;
            }
        }

        Registry::get('response')
            ->write(json_encode($result))
            ->header('Content-Type', 'application/json')
            ->header('X-Revision', LATEST_REV)
            ->status($result ? 200 : 404)
            ->send();
    }
}