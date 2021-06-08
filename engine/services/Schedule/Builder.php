<?php

/**
 * Project: raspprod
 * Date: 02.04.2017
 * Time: 15:32
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Builder
{
    protected $sarray;
    protected $path;
    protected $files;
    protected $functions;
    protected $filelist = array();
    protected $db_name;
    protected $logger;
    protected $db_file_path;
    protected $timer;
    protected $rev;

    public function __construct(array $array) {
        $this->sarray = $array;
        $this->files = array('settings' => 'settings.json', 'classes' => 'classes.json', 'chairs' => 'chairs.json',
            'subjects' => 'subjects.json', 'specialities' => 'specialities.json', 'study_types' => 'study_types.json',
            'rooms' => 'rooms.json', 'teachers' => 'teachers.json', 'times' => 'times.json');
        $this->functions = array('settings' => 'generateSettings', 'classes' => 'generateClasses', 'chairs' => 'generateChairs',
            'subjects' => 'generateSubjects', 'specialities' => 'generateSpecialities', 'study_types' => 'generateStudyTypes',
            'rooms' => 'generateRooms', 'teachers' => 'generateTeachers', 'times' => 'generateTimes');

        if ($this->sarray['settings'][0]['rev'] && preg_match('/^[a-f0-9]{32}$/i', $this->sarray['settings'][0]['rev'])) {
            $this->rev = $this->sarray['settings'][0]['rev'];
            $this->db_name = DB_PREFIX. 'rasp_' . substr($this->rev, 0, 8);
            $this->filelist = $this->files;
            $this->path = STORAGE_RAW_DIR.'/'.$this->rev.'/';
            $this->db_file_path = $this->path . $this->db_name . '.db';
            $this->logger = new Logger($this->rev);
            foreach ($this->sarray['classes'] as $c) {
                $this->filelist[] = 'schedule_class_' .$c['id']. '.json';
            }
            foreach ($this->sarray['rooms'] as $c) {
                $this->filelist[] = 'schedule_room_' .$c['id']. '.json';
            }
            foreach ($this->sarray['teachers'] as $c) {
                $this->filelist[] = 'schedule_teacher_' .$c['id']. '.json';
            }
        } else {
            throw new \RuntimeException("Builder: Can't identify revision from array");
        }
    }

    private function generateChecklist () {
        $files = array_values($this->filelist);
        file_put_contents($this->path. 'checklist.txt', implode(';', $files));
    }

    private function generateSettings () {
        $file = $this->sarray['settings'][0];
        $file ['generated'] = time();
        return $file;
    }

    private function generateClasses () {
        return $this->sarray['classes'];
    }

    private function generateChairs () {
        return $this->sarray['chairs'];
    }

    private function generateSubjects () {
        return $this->sarray['subjects'];
    }

    private function generateSpecialities () {
        return $this->sarray['specialities'];
    }

    private function generateStudyTypes () {
        return $this->sarray['study_types'];
    }

    private function generateRooms () {
        $response = filter_multi_output($this->sarray['rooms'],
            array('id', 'name', 'capacity'),
            array());
        return $response;
    }

    private function generateTeachers () {
        $response = filter_multi_output($this->sarray['teachers'],
            array('id', 'surname', 'first_name', 'second_name', 'chair_id', 'status'),
            array());
        return $response;
    }

    private function generateTimes () {
        return $this->sarray['times'];
    }

    public function buildStatic () {
        $this->generateChecklist();
        $this->timer = time();
        $this->logger->pushHandler(new StreamHandler($this->path.'build.log', Logger::DEBUG));
        $this->logger->info('Dynamic build process started', array('date' => date('d.m.Y H:i:s', $this->timer)));

        foreach ($this->functions as $k=>$v) {
            $tmp = call_user_func(array(__CLASS__, $v));
            $result = file_put_contents($this->path.$this->files[$k], json_encode($tmp));
            ($result && $result > 0) ? $this->logger->info($v. ':OK', array('size' => $result)) : $this->logger->warning($v. ':FAIL');
        }
    }

    public function buildSchedule () {
        set_time_limit(4000);
        // В этот чудесный момент файл базы уже занят и открыт на запись
        $squery = 'SELECT s.load_id AS lid, s.day AS day, s.hour AS hour, lc.class as `group`, w.`group` AS `subgroup`, t.surname || " " || t.first_name || "." || t.second_name || "." AS teacher_name, l.teacher_id AS teacher_id, l.subject_id AS subject_id, l.study_type_id AS type_id, sub.full_name AS subject_name, w.week AS week, r.name AS room_name, r.id as room_id, l.pair_type AS pair, st.full_name as type_name
                        FROM loads_classes AS lc
                        JOIN loads_weeks AS w ON w.real_load_id=lc.real_load_id
                        JOIN loads AS l ON lc.real_load_id=l.id OR lc.load_id=l.id AND (l.`group` = w.`group`)
                        LEFT JOIN sched AS s ON (lc.real_load_id = s.load_id OR lc.load_id = s.load_id) AND (w.`group` = s.`group` OR w.`group` = 0)
                        LEFT JOIN teachers AS t ON t.id = l.teacher_id
                        LEFT JOIN subjects AS sub ON sub.id = l.subject_id
                        LEFT JOIN rooms AS r ON s.room_id = r.id
                        LEFT JOIN study_types AS st ON l.study_type_id = st.id
                        WHERE (week >= begin_date AND week <= end_date)';
        $tmp = \Registry::get('dbfiller')->query ($squery, array(), 'assoc');
        foreach ($this->sarray['classes'] as $c) {
            $tmp_class = $tmp_class_ids = array();
            $tmp_class_ids = array_keys(array_column($tmp, 'group'), $c['id']);
            foreach ($tmp_class_ids as $k => $v) {
                $tmp_class[] = $tmp[$v];
            }
            $group_func = function($subarray) { return group_by_prefix($subarray, array('teacher','subject','type','room'), '_'); };
            $tmp_class = array_map($group_func, $tmp_class);
            $result = file_put_contents($this->path. 'schedule_class_' .$c['id']. '.json', json_encode($tmp_class));
            ($result && $result > 0) ? $this->logger->info('Group:' .$c['id']. ':OK', array('size' => $result)) : $this->logger->warning('Group:' .$c['id']. ':FAIL');
        }

        $squery = 'SELECT s.load_id AS lid, s.day AS day, s.hour AS hour, lc.class as `group`, w.`group` AS `subgroup`, t.surname || " " || t.first_name || "." || t.second_name || "." AS teacher_name, l.teacher_id AS teacher_id, l.subject_id AS subject_id, l.study_type_id AS type_id, sub.full_name AS subject_name, w.week AS week, r.name AS room_name, r.id as room_id, l.pair_type AS pair, st.full_name as type_name
                        FROM loads_classes AS lc
                        JOIN loads_weeks AS w ON w.real_load_id=lc.real_load_id
                        JOIN loads AS l ON lc.real_load_id=l.id OR lc.load_id=l.id AND (l.`group` = w.`group`)
                        LEFT JOIN sched AS s ON (lc.real_load_id = s.load_id OR lc.load_id = s.load_id) AND (w.`group` = s.`group` OR w.`group` = 0)
                        LEFT JOIN teachers AS t ON t.id = l.teacher_id
                        LEFT JOIN subjects AS sub ON sub.id = l.subject_id
                        LEFT JOIN rooms AS r ON s.room_id = r.id
                        LEFT JOIN study_types AS st ON l.study_type_id = st.id
                        WHERE (week >= begin_date AND week <= end_date)';
        $tmp = \Registry::get('dbfiller')->query ($squery, array(), 'assoc');
        foreach ($this->sarray['teachers'] as $t) {
            $tmp_teacher = $tmp_teacher_ids = array();
            $tmp_teacher_ids = array_keys(array_column($tmp, 'teacher_id'), $t['id']);
            foreach ($tmp_teacher_ids as $k => $v) {
                $tmp_teacher[] = $tmp[$v];
            }
            $group_result = group_records ($tmp_teacher, array('lid', 'day', 'hour', 'week'), 'group', 'groups');
            $group_func = function($subarray) { return group_by_prefix($subarray, array('teacher','subject','type','room'), '_'); };
            $group_result = array_map($group_func, $group_result);
            $result = file_put_contents($this->path. 'schedule_teacher_' .$t['id']. '.json', json_encode($group_result));
            ($result && $result > 0) ? $this->logger->info('Teacher:' .$t['id']. ':OK', array('size' => $result)) : $this->logger->warning('Teacher:' .$t['id']. ':FAIL');
        }

        $tmp = array();
        $squery = 'SELECT s.load_id AS lid, s.day AS day, s.hour AS hour, lc.class as `group`, w.`group` AS `subgroup`, t.surname || " " || t.first_name || "." || t.second_name || "." AS teacher_name, l.teacher_id AS teacher_id, l.subject_id AS subject_id, l.study_type_id AS type_id, sub.full_name AS subject_name, w.week AS week, r.name AS room_name, r.id as room_id, l.pair_type AS pair, st.full_name as type_name
                        FROM loads_classes AS lc
                        LEFT JOIN sched AS s ON lc.real_load_id = s.load_id OR lc.load_id = s.load_id
                        LEFT JOIN loads AS l ON lc.real_load_id=l.id OR lc.load_id=l.id
                        LEFT JOIN teachers AS t ON t.id = l.teacher_id
                        LEFT JOIN subjects AS sub ON sub.id = l.subject_id
                        JOIN loads_weeks AS w ON w.real_load_id=lc.real_load_id
                        LEFT JOIN rooms AS r ON s.room_id = r.id
                        LEFT JOIN study_types AS st ON l.study_type_id = st.id
                        WHERE ((pair_type = "pkYes" AND l.`group` = w.`group`) OR (pair_type != "pkYes") OR (pair_type = "pkYes" AND study_type_id != 300)) AND ((begin_date = 0 AND end_date = 0) OR (week >= begin_date AND week <= end_date))';
        $tmp = \Registry::get('dbfiller')->query ($squery, array(), 'assoc');
        foreach ($this->sarray['rooms'] as $r) {
            $tmp_room = $tmp_room_ids = array();
            $tmp_room_ids = array_keys(array_column($tmp, 'room_id'), $r['id']);
            foreach ($tmp_room_ids as $k => $v) {
                $tmp_room[] = $tmp[$v];
            }
            $group_result = group_records ($tmp_room, array('lid', 'day', 'hour', 'week'), 'group', 'groups');
            $group_func = function($subarray) { return group_by_prefix($subarray, array('teacher','subject','type','room'), '_'); };
            $group_result = array_map($group_func, $group_result);
            $result = file_put_contents($this->path. 'schedule_room_' .$r['id']. '.json', json_encode($group_result));
            ($result && $result > 0) ? $this->logger->info('Room:' .$r['id']. ':OK', array('size' => $result)) : $this->logger->warning('Room:' .$r['id']. ':FAIL');
        }
    }

    public function isGeneratedContentValid () {
        $success = true;
        $badfile = '';

        foreach (array_values($this->filelist) as $file) {
            json_decode(file_get_contents($this->path.$file));
            if (json_last_error() === JSON_ERROR_NONE) {
            } else {
                $badfile = $this->path.$file;
                $success = false;
            }
        }
        $success ? $this->logger->info('Checked:VALID') : $this->logger->warning('Checked:FAIL', array ('file' => $badfile));
        $this->logger->info('Build process finished', array('date' => date('d.m.Y H:i:s'), 'elapsed_seconds' => time() - $this->timer, 'memory' => memory_get_peak_usage()));
        return $success ? true : $badfile;
    }
}