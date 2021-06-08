<?php

/**
 * Project: raspprod
 * Date: 02.04.2017
 * Time: 4:22
 */
class Filler
{
    protected $sarray;
    protected $db_scheme;
    protected $db_name;
    protected $db_scheme_path = STORAGE_DIR . '/base.sql';
    protected $db_file_path;
    protected $path;

    public function __construct(array $array) {
        $this->sarray = $array;
        if (file_exists($this->db_scheme_path)) {
            $rev = $this->sarray['settings']['rev'];
            $this->path = STORAGE_RAW_DIR.'/'.$rev.'/';
            $this->db_scheme = file_get_contents($this->db_scheme_path);
            $this->db_name = DB_PREFIX. 'rasp_' . substr($rev, 0, 8);
            $this->db_file_path = $this->path . $this->db_name . '.db';
        } else {
            throw new \RuntimeException('Filler: DB template is missing');
        }
    }

    private function initDirectory () {
        if (file_exists($this->path)) {
            @delete_dir($this->path); // Удаляем, если она уже есть...
        }
        if (!file_exists($this->path)) {
            mkdirs($this->path, 0755); // И создаём обратно
        }
    }

    public function fillTimes () {
        $result = array();
        for ($i = 1; $i <= count ($this->sarray['simple']['times']) - 1; $i++) {
            $result[] = array(
                'id' =>$i,
                'time' =>(string)$this->sarray['simple']['times'][$i][0]);
        }
        return $result;
    }

    private function fillClasses () {
        $result = array();
        foreach ($this->sarray['simple']['classes'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'name' =>(string)$c->name,
                'normalized_name' =>normalize_name((string)$c->name), // Создание чистого имени для процесса миграции
                'speciality_id' =>(int)$c->speciality_id,
                'semester' =>(int)$c->semester);
        }
        return $result;
    }

    private function fillSubjects () {
        $result = array();
        foreach ($this->sarray['simple']['subjects'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'short_name' =>(string)$c->short_name,
                'full_name' =>(string)$c->full_name);
        }
        return $result;
    }

    private function fillChairs () {
        $result = array();
        foreach ($this->sarray['simple']['chairs'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'short_name' =>(string)$c->short_name,
                'full_name' =>(string)$c->full_name);
        }
        return $result;
    }

    private function fillSpecialities () {
        $result = array();
        foreach ($this->sarray['simple']['specialities'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'short_name' =>(string)$c->short_name,
                'full_name' =>(string)$c->full_name);
        }
        return $result;
    }

    private function fillStudyTypes () {
        $result = array();
        foreach ($this->sarray['simple']['study_types'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'full_name' =>(string)$c->full_name);
        }
        return $result;
    }

    private function fillRooms () {
        $result = array();
        foreach ($this->sarray['simple']['rooms'] as $c) {
            $result[] = array(
                'id' =>(int)$c->id,
                'name' =>(string)$c->name,
                'capacity' =>(int)$c->capacity,
                'building' =>(string)$c->building,
                'chair_id' =>(int)$c->chair_id);
        }
        return $result;
    }

    private function fillTeachers () {
        $result = array();
        foreach ($this->sarray['simple']['teachers'] as $c) {
            if ((int)$c->person->id > 0) {
                $result[] = array(
                    'id' =>(int)$c->person->id,
                    'surname' =>((string)$c->person->surname == '=') ? 'Преподаватель' : (string)$c->person->surname,
                    'first_name' =>((string)$c->person->first_name == 'Fake' || !(string)$c->person->first_name) ? 'И' : (string)$c->person->first_name,
                    'second_name' =>(string)$c->person->second_name ?: 'О',
                    'class_id' =>(int)$c->class_id,
                    'subject_id' =>(int)$c->subject_id,
                    'room_id' =>(int)$c->room_id,
                    'chair_id' =>(int)$c->chair_id,
                    'status' =>(string)$c->status);
            }
        }
        return $result;
    }

    private function fillSchedules () {
        $result = array();
        $startdate = $this->sarray['settings']['begin_date'];
        $startwn = new \DateTime($startdate);
        $startwnm = $startwn->format('W');

        foreach ($this->sarray['simple']['scheds'] as $c) {
            $beginwn = new \DateTime((string)$c->begin_date);
            $beginwnm = $beginwn->format('W');
            $endwn = new \DateTime((string)$c->end_date);
            $endwnm = $endwn->format('W');

            $result[] = array(
                'fixed' =>((string)$c->fixed == 'yes') ? '1' : '0',
                'day' =>(int)$c->day,
                'hour' =>(int)$c->hour,
                'group' =>(int)$c->group + 1, // Внимание! Пары без групп получают флаг 1
                'load_id' =>(int)$c->load_id,
                'room_id' =>(int)$c->room_id,
                'begin_date' =>((int)$beginwnm - (int)$startwnm) + 1,
                'end_date' =>((int)$endwnm - (int)$startwnm) + 1,
            );

        }
        return $result;
    }

    private function fillLoads () {
        $result = $resultlink = $resultweek = $summary = array();

        foreach ($this->sarray['complex']['loads'] as $c) {
            if (array_key_exists('1', $c['groups']['group'])) {
                $count = count($c['groups']['group']);
                for ($i = 0; $i<$count; $i++) {
                    $c['same_time'] = ($c['same_time'] == 'yes') ? '1' : '0';
                    $fakeid = ($c['id'] * 111) + $i;
                    $result[] = array(
                        'id' =>$fakeid,
                        'same_time' =>$c['same_time'],
                        'teacher_id' =>$c['groups']['group'][$i]['teacher_id'],
                        'subject_id' =>$c['groups']['group'][$i]['subject_id'],
                        'room_id' =>'-1',
                        'week_type' =>$c['groups']['group'][$i]['week_type'],
                        'pair_type' =>$c['groups']['group'][$i]['pair_type'],
                        'study_type_id' =>$c['groups']['group'][$i]['study_type_id'],
                        'group' =>$i + 1, // Иначе будет псевдо-группа А для сдвоенных общих пар
                        'real_load_id' =>$c['id']);

                    if (@array_key_exists('1', $c['klass_id_list']['int'])) {
                        $count = count($c['klass_id_list']['int']);
                        for ($j = 0; $j<$count; $j++) {
                            $resultlink[] = array(
                                'class' =>$c['klass_id_list']['int'][$j],
                                'load_id' =>$fakeid,
                                'real_load_id' =>$c['id']);
                        }

                    } else {
                        $resultlink[] = array('class' =>$c['klass_id_list']['int'], 'load_id' =>$fakeid, 'real_load_id' =>$c['id']);
                    }

                    for ($j = 0, $jMax = count(@$c['groups']['group'][$i]['hour_per_week_list']['int']); $j < $jMax; $j++) {
                        if ($c['groups']['group'][$i]['hour_per_week_list']['int'][$j] > 0) {
                            $resultweek[] = array('group' =>$i + 1,
                                'week' =>$j+1,
                                'load_id' =>$fakeid,
                                'real_load_id' =>$c['id'],
                                'hours' =>$c['groups']['group'][$i]['hour_per_week_list']['int'][$j]);
                        }
                    }

                }
            }
            else {
                $c['same_time'] = ($c['same_time'] == 'yes') ? '1' : '0';
                $result[] = array('id' =>$c['id'],
                    'same_time' =>$c['same_time'],
                    'teacher_id' =>$c['groups']['group']['teacher_id'],
                    'subject_id' =>$c['groups']['group']['subject_id'],
                    'room_id' =>'-1',
                    'week_type' =>$c['groups']['group']['week_type'],
                    'pair_type' =>$c['groups']['group']['pair_type'],
                    'study_type_id' =>$c['groups']['group']['study_type_id'],
                    'group' =>'1',
                    'real_load_id' =>$c['id']);

                if (@array_key_exists('1', $c['klass_id_list']['int'])) {
                    $count = count($c['klass_id_list']['int']);
                    for ($i = 0; $i<$count; $i++) {
                        $resultlink[] = array('class' =>$c['klass_id_list']['int'][$i],
                            'load_id' =>$c['id'],
                            'real_load_id' =>$c['id']);
                    }

                } else {
                    $resultlink[] = array('class' =>$c['klass_id_list']['int'],
                        'load_id' =>$c['id'],
                        'real_load_id' =>$c['id']);
                }

                for ($j = 0, $jMax = count($c['groups']['group']['hour_per_week_list']['int']); $j < $jMax; $j++) {
                    if ($c['groups']['group']['hour_per_week_list']['int'][$j] > 0) {
                        $resultweek[] = array('group' =>0,
                            'week' =>$j+1, 'load_id' =>$c['id'],
                            'real_load_id' =>$c['id'],
                            'hours' =>$c['groups']['group']['hour_per_week_list']['int'][$j]);
                    }
                }
            }
        }
        $summary = array('loads' => $result, 'l_classes' =>$resultlink, 'l_weeks' =>$resultweek);
        return $summary;
    }

    public function prepareContent () {
        $content = array (
            'classes' => $this->fillClasses(),
            'chairs' => $this->fillChairs(),
            'loads' => $this->fillLoads()['loads'],
            'loads_classes' => $this->fillLoads()['l_classes'],
            'loads_weeks' => $this->fillLoads()['l_weeks'],
            'rooms' => $this->fillRooms(),
            'sched' => $this->fillSchedules(),
            'settings' =>array($this->sarray['settings']),
            'specialities' => $this->fillSpecialities(),
            'subjects' => $this->fillSubjects(),
            'study_types' => $this->fillStudyTypes(),
            'teachers' => $this->fillTeachers(),
            'times' => $this->fillTimes());
        return $content;
    }

    private function initDB () {
        $this->initDirectory(); // Функция переехала сюда из билдера, так как требуется создавать папку для базы уже на этом этапе

        try { // Удаляем существующую базу ревизии из MySQL, но ничего не создаём.
            \Registry::get('db')->getImplementationConnection()->multi_query("DROP DATABASE IF EXISTS {$this->db_name};");
        } catch (go\DB\Exceptions\Query $e) {
            // Результат не важен, так как после обновления всех ревизий старых баз больше не останется
            \Registry::get('log')->warning ($this->sarray['settings']['rev']. ':FAIL to delete existing DB', array('error' => $e->getError()));
        }
        @unlink($this->db_file_path);
        if (!file_exists($this->db_file_path)) {
            \Registry::get('log')->debug ($this->sarray['settings']['rev'] . ':DB file is not exist - OK to processed');
            return true;
        } else {
            \Registry::get('log')->warning ($this->sarray['settings']['rev']. ':FAIL to delete existing DB file');
            return false;
        }
    }

    public function constructDB () {
        try {
            $this->initDB();
        }
        catch (go\DB\Exceptions\Query $e) {
            \Registry::get('log')->warning ($this->sarray['settings']['rev'] . ':FAIL to init DB', array('error' => $e->getError()));
            return false;
        }

        try {
            init_file_db($this->db_file_path); // Создаем новую базу в файле и устанавливаем указатель
            \Registry::get('dbfiller')->getImplementationConnection()->query($this->db_scheme); // Заполняем базу из схемы
        } catch (go\DB\Exceptions\Query $e) {
            \Registry::get('log')->warning ($this->sarray['settings']['rev']. ':FAIL to create or fill DB scheme', array('error' => $e->getError()));
        }

        $content = $this->prepareContent();
        foreach ($content as $k=>$v) {
            try {
                $tmp = \Registry::get('dbfiller')->getTable($k);
                $tmp->multiInsert($v);
            } catch (go\DB\Exceptions\Query $e) {
                \Registry::get('log')->warning ($this->sarray['settings']['rev']. ':FAIL to construct DB', array('error' => $e->getError()));
                exit('Failed to construct DB');
            }
        }
        \Registry::get('log')->debug ($this->sarray['settings']['rev']. ':DB successfully constructed');
        return true;
    }
}