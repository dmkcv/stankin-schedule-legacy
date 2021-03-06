define({ "api": [
  {
    "type": "get",
    "url": "/chairs",
    "title": "Кафедры",
    "version": "0.1.1",
    "name": "GetChairs",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID кафедры</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "short_name",
            "description": "<p>Краткое название кафедры (аббревиатура)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>Полное название кафедры</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n [\n     {\"id\":\"0\",\n      \"short_name\":\"АСОИиУ\",\n      \"full_name\":\"Автоматиз. системы обработки инф. и управления\"}\n  ...\n ]",
          "type": "json"
        }
      ]
    },
    "description": "<p>Возвращает массив кафедр без разделения по страницам. Может быть использовано для связывания преподавателей по кафедрам.</p>",
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/groups",
    "title": "Группы",
    "version": "0.1.1",
    "name": "GetGroups",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID группы</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Название группы</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "speciality_id",
            "description": "<p>ID специальности, смотри /specialities</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "semester",
            "description": "<p>Номер семестра, на котором обучается группа</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n {\"id\":\"0\",\n  \"name\":\"ИДБ-13-12\",\n  \"speciality_id\":\"48\",\n  \"semester\":\"5\"},\n  ...\n]",
          "type": "json"
        }
      ]
    },
    "description": "<p>Возвращает массив групп без разделения по страницам. Некоторые группы могут дублироваться, подразделяясь на большие подгруппы по спецальностям.</p>",
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/rooms",
    "title": "Аудитории",
    "version": "0.1.1",
    "name": "GetRooms",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Номер аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "capacity",
            "description": "<p>Количество мест в аудитории</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n [\n    {\"id\":\"0\",\n     \"name\":\"0202\",\n     \"capacity\":\"30\"},\n      ...\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/specialities",
    "title": "Специальности",
    "version": "0.1.1",
    "name": "GetSpecialities",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID специальности</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "short_name",
            "description": "<p>Краткое название специальности</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>Полное название специальности</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n [\n     {\"id\":\"131\",\n     \"short_name\":\"38.03.02\",\n     \"full_name\":\"Менеджмент\"},\n      ...\n ]",
          "type": "json"
        }
      ]
    },
    "description": "<p>Возвращает массив специальностей без разделения по страницам. В исходном виде группы разделены по специальностям, но в данной версии API у групп они не отражены.</p>",
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/studytypes",
    "title": "Типы занятий",
    "version": "0.1.1",
    "name": "GetStudyTypes",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID типа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "full_name",
            "description": "<p>Полное название типа</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n [\n    {\"id\":\"1\",\n     \"full_name\":\"лекция\"},\n      ...\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/teachers",
    "title": "Преподаватели",
    "version": "0.1.1",
    "name": "GetTeachers",
    "group": "Dictionaries",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>Перечень ID преподавателей через запятую</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/teachers?id=2,294,361",
        "type": "curl"
      }
    ],
    "description": "<p>Возвращает массив информации о преподавателях с указанием кафедры и &quot;роли&quot; в конкретном расписании. В случае, если преподаватель не задан на предмет (в диспетчерской), то будет возвращена пустая (&quot;Фамилия И.О.&quot;) карточка с &quot;ролью&quot; <code>tsFake</code>.</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "count",
            "description": "<p>Количество записей в массиве ответа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "teachers",
            "description": "<p>Массив информации о преподавателях</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "teachers.id",
            "description": "<p>ID преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teachers.surname",
            "description": "<p>Фамилия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teachers.first_name",
            "description": "<p>Первая буква имени</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teachers.second_name",
            "description": "<p>Первая буква отчества</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "teachers.chair_id",
            "description": "<p>ID кафедры, к которой приписан предподаватель</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teachers.status",
            "description": "<p>&quot;Роль&quot; в расписании: <code>tsRegular</code> - конкретное лицо с ФИО, <code>tsFake</code> - заглушка без каких-либо данных (используется при заменах и сложных конфигурациях занятий)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\n    {\"count\":3,\n     \"teachers\":[\n         {\"id\":\"2\",\n             \"surname\":\"Преподаватель\",\n             \"first_name\":\"И\",\n             \"second_name\":\"О\",\n             \"chair_id\":\"-1\",\n             \"status\":\"tsFake\"},\n        {\"id\":\"294\",\n             \"surname\":\"Левин\",\n             \"first_name\":\"М\",\n             \"second_name\":\"В\",\n             \"chair_id\":\"7\",\n             \"status\":\"tsRegular\"},\n         {\"id\":\"361\",\n             \"surname\":\"Поляков\",\n             \"first_name\":\"С\",\n             \"second_name\":\"Д\",\n             \"chair_id\":\"7\",\n             \"status\":\"tsRegular\"}]}",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/timetable",
    "title": "График пар",
    "version": "0.1.1",
    "name": "GetTimetable",
    "group": "Dictionaries",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID промежутка, номер пары</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "time",
            "description": "<p>Временной интервал</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n [\n    {\"id\":1,\n     \"time\":\"08:30 - 10:10\"},\n      ...\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Dictionaries"
  },
  {
    "type": "get",
    "url": "/schedule/group/:i",
    "title": "Расписание для группы",
    "version": "0.1.1",
    "name": "GetByGroup",
    "group": "Schedules",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID группы</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "time",
            "description": "<p>Номер недели или конкретная дата</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "type",
            "description": "<p>Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/schedule/group/25?time=6&type=1",
        "type": "curl"
      }
    ],
    "description": "<p>Возвращает массив с расписанием группы на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>). Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "lid",
            "description": "<p>ID конкретной &quot;нагрузки&quot; (повторяющегося события)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "day",
            "description": "<p>День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "hour",
            "description": "<p>Номер пары (ID слота времени в /timetable)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "group",
            "description": "<p>ID группы</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "subgroup",
            "description": "<p>ID подгруппы (<code>0</code> - вся группа, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "teacher",
            "description": "<p>Блок преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "teacher.id",
            "description": "<p>ID преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teacher.name",
            "description": "<p>ФИО преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "subject",
            "description": "<p>Блок предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "subject.id",
            "description": "<p>ID предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subject.name",
            "description": "<p>Название предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "type",
            "description": "<p>Блок типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "type.id",
            "description": "<p>ID типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type.name",
            "description": "<p>Название типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "week",
            "description": "<p>Номер недели с начала семестра</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "room",
            "description": "<p>Блок аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "room.id",
            "description": "<p>ID аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "room.name",
            "description": "<p>Номер аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Bool",
            "optional": false,
            "field": "pair",
            "description": "<p>Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\n [\n     {\n                \"lid\":82,\n                \"day\":5,\n                \"hour\":2,\n                \"group\":141,\n                \"subgroup\":0,\n                \"week\":2,\n                \"pair\":false,\n                \"teacher\":{\n                    \"id\":375,\n                    \"name\":\"Байкова Е.А.\"\n                    },\n                \"subject\":{\n                    \"id\":20,\n                    \"name\":\"Анализ состояния производства при сертификации продукции\"\n                    },\n                \"type\":{\n                    \"id\":100,\n                    \"name\":\"лекция\"\n                    },\n                \"room\":{\n                    \"id\":73,\n                    \"name\":\"324\"\n                    }\n                }\n     ...\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/ScheduleAPIController.php",
    "groupTitle": "Schedules"
  },
  {
    "type": "get",
    "url": "/schedule/room/:i",
    "title": "Расписание для аудитории",
    "version": "0.1.1",
    "name": "GetByRoom",
    "group": "Schedules",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID аудитории</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "time",
            "description": "<p>Номер недели или конкретная дата</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "type",
            "description": "<p>Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/schedule/room/19",
        "type": "curl"
      }
    ],
    "description": "<p>Возвращает массив с расписанием загрузки аудитории на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>). Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "lid",
            "description": "<p>ID конкретной &quot;нагрузки&quot; (повторяющегося события)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "day",
            "description": "<p>День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "hour",
            "description": "<p>Номер пары (ID слота времени в /timetable)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "group",
            "description": "<p>ID подгруппы (<code>0</code> - без разделения, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "teacher",
            "description": "<p>Блок преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "teacher.id",
            "description": "<p>ID преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teacher.name",
            "description": "<p>ФИО преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "subject",
            "description": "<p>Блок предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "subject.id",
            "description": "<p>ID предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subject.name",
            "description": "<p>Название предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "type",
            "description": "<p>Блок типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "type.id",
            "description": "<p>ID типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type.name",
            "description": "<p>Название типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "week",
            "description": "<p>Номер недели с начала семестра</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "room",
            "description": "<p>Блок аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "room.id",
            "description": "<p>ID аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "room.name",
            "description": "<p>Номер аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Bool",
            "optional": false,
            "field": "pair",
            "description": "<p>Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "groups",
            "description": "<p>Массив с ID и именами групп, присутствующих в аудитории</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\n [\n    {\n            \"lid\":682,\n            \"day\":3,\n            \"hour\":5,\n            \"subgroup\":0,\n            \"week\":1,\n            \"pair\":false,\n            \"teacher\":{\n            \"id\":367,\n            \"name\":\"Гринман И.Р.\"\n            },\n            \"subject\":{\n                \"id\":113,\n                \"name\":\"Коммерческое право\"\n            },\n            \"type\":{\n                \"id\":100,\n                \"name\":\"лекция\"\n            },\n            \"room\":{\n                \"id\":106,\n                \"name\":\"441\"\n            },\n            \"groups\":[\n            {\n                \"id\":183,\n                \"name\":\"ЭПМ-10-1\"\n            },\n            {\n                \"id\":186,\n                \"name\":\"ЭЭТ-10-5\"\n            }\n            ]\n            },\n     ...\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/ScheduleAPIController.php",
    "groupTitle": "Schedules"
  },
  {
    "type": "get",
    "url": "/schedule/teacher/:i",
    "title": "Расписание для преподавателя",
    "version": "0.1.1",
    "name": "GetByTeacher",
    "group": "Schedules",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Int",
            "optional": false,
            "field": "id",
            "description": "<p>ID преподавателя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "time",
            "description": "<p>Номер недели или конкретная дата</p>"
          },
          {
            "group": "Parameter",
            "type": "Int",
            "optional": true,
            "field": "type",
            "description": "<p>Тип данных в параметре <code>time</code>: <code>1</code> - номер недели с начала семестра, <code>2</code> - номер недели с начала года, <code>3</code> - дата в формате ДД.ММ.ГГГГ</p>"
          }
        ]
      }
    },
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/schedule/teacher/160",
        "type": "curl"
      }
    ],
    "description": "<p>Возвращает массив с расписанием преподавателя на весь семестр или на заданную неделю (передаётся в параметре <code>time</code>). Возможно использование с опциональным параметром <code>extended</code>, позволяющее не делать уточняющие запросы к справочным методам API.</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "lid",
            "description": "<p>ID конкретной &quot;нагрузки&quot; (повторяющегося события)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "day",
            "description": "<p>День недели (<code>1</code> - понедельник, ..., <code>6</code> - суббота)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "hour",
            "description": "<p>Номер пары (ID слота времени в /timetable)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "subgroup",
            "description": "<p>ID подгруппы (<code>0</code> - без разделения, <code>1</code> - подгруппа А, <code>2</code> - подгруппа Б)</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "teacher",
            "description": "<p>Блок преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "teacher.id",
            "description": "<p>ID преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "teacher.name",
            "description": "<p>ФИО преподавателя</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "subject",
            "description": "<p>Блок предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "subject.id",
            "description": "<p>ID предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "subject.name",
            "description": "<p>Название предмета</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "type",
            "description": "<p>Блок типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "type.id",
            "description": "<p>ID типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type.name",
            "description": "<p>Название типа занятия</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "week",
            "description": "<p>Номер недели с начала семестра</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "room",
            "description": "<p>Блок аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "room.id",
            "description": "<p>ID аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "room.name",
            "description": "<p>Номер аудитории</p>"
          },
          {
            "group": "Success 200",
            "type": "Bool",
            "optional": false,
            "field": "pair",
            "description": "<p>Флаг спаренности занятия (чаще всего он проставлен у лабораторных работ, идущие подряд одинаковые пары такого флага обычно не имеют; спаренные занятия имеют идентичные <code>lid</code>)</p>"
          },
          {
            "group": "Success 200",
            "type": "Array[]",
            "optional": false,
            "field": "groups",
            "description": "<p>Массив с ID и названиями групп, присутствующих на паре</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n\n [\n    {\n            \"lid\":271,\n            \"day\":5,\n            \"hour\":3,\n            \"subgroup\":1,\n            \"week\":10,\n            \"pair\":true,\n            \"teacher\":{\n                \"id\":141,\n                \"name\":\"Позднеев Б.М.\"\n            },\n            \"subject\":{\n                \"id\":36,\n                \"name\":\"Высокоэффективные технологии и оборудование современных производств\"\n            },\n            \"type\":{\n                \"id\":300,\n                \"name\":\"лабораторная работа\"\n            },\n            \"room\":{\n                \"id\":29,\n                \"name\":\"126\"\n            },\n            \"groups\":[\n            {\n                \"id\":56,\n                \"name\":\"ИДБ-13-12\"\n            }\n            ]\n ]",
          "type": "json"
        }
      ]
    },
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/ScheduleAPIController.php",
    "groupTitle": "Schedules"
  },
  {
    "type": "get",
    "url": "/settings",
    "title": "Получение базовой информации о доступном расписании",
    "version": "0.1.1",
    "name": "GetSettings",
    "group": "Settings",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "rev",
            "description": "<p>Ревизия текущего расписания</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "begin_date",
            "description": "<p>Дата начала актуальности расписания (обычно начало семестра)</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "end_date",
            "description": "<p>Дата конца актуальности расписания (соотвественно, конец семестра)</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "generated",
            "description": "<p>Время генерации расписания на сервере API</p>"
          },
          {
            "group": "Success 200",
            "type": "Int",
            "optional": false,
            "field": "updated",
            "description": "<p>Время последнего успешного автообновления расписания</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n {\n \"rev\":\"63c21be72564134989c2d1ddadbb2cca\",\n \"begin_date\":\"01.09.2016\",\n \"end_date\":\"25.12.2016\",\n \"generated\":1491343159,\n \"updated\":1491349128\n }",
          "type": "json"
        }
      ]
    },
    "description": "<p>Информация может пригодиться при кэшировании расписания и для быстрой проверки актуальности.</p>",
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Settings"
  },
  {
    "type": "get",
    "url": "/suggestions/classes",
    "title": "Поиск по группам",
    "version": "0.1.1",
    "name": "GetClassesSuggestions",
    "group": "Suggestions",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "search",
            "description": "<p>Строка для поиска</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n {\"id\":\"0\",\n  \"name\":\"ИДБ-13-12\",\n  \"speciality_id\":\"48\",\n  \"semester\":\"5\"}\n]",
          "type": "json"
        }
      ]
    },
    "description": "<p>Возвращает массив групп, содержащих в имени запрошенную подстроку</p>",
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/suggestions/classes?search=1312",
        "type": "curl"
      }
    ],
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Suggestions"
  },
  {
    "type": "get",
    "url": "/suggestions/teachers",
    "title": "Поиск по преподавателям",
    "version": "0.1.1",
    "name": "GetTeachersSuggestions",
    "group": "Suggestions",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "search",
            "description": "<p>Строка для поиска</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n[\n {\"id\":\"296\",\n \"surname\":\"Левчук\",\n \"first_name\":\"В\",\n \"second_name\":\"И\",\n \"chair_id\":\"21\",\n \"status\":\"tsRegular\"},\n {\"id\":\"295\",\n \"surname\":\"Левченко\",\n \"first_name\":\"А\",\n \"second_name\":\"Н\",\n \"chair_id\":\"7\",\n \"status\":\"tsRegular\"}\n]",
          "type": "json"
        }
      ]
    },
    "description": "<p>Возвращает массив преподавателей, содержащих в фамилии запрошенную подстроку</p>",
    "examples": [
      {
        "title": "Пример запроса:",
        "content": "curl -i https://schedule/api/suggestions/teachers?search=левч",
        "type": "curl"
      }
    ],
    "filename": "g:/WebServers_php7/home/so.zz/www/raspprod/engine/controllers/BaseAPIController.php",
    "groupTitle": "Suggestions"
  }
] });
