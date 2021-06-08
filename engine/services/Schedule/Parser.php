<?php

/**
 * Project: raspprod
 * Date: 02.04.2017
 * Time: 3:41
 */
class Parser
{
    private $format = 136;
    protected $rawarray;
    protected $sarray;
    protected $revision;
    protected $mtime;
    protected $path;

    public function __construct($xml) {
        if (file_exists($xml)) {
            $content = simplexml_load_string(file_get_contents($xml));
            $this->revision = md5_file ($xml);
            $this->mtime = filemtime ($xml);
            $this->path = $xml;
            if ($content === false) {
                \Registry::get('log')->warning ($this->revision. ':Failed to load XML');
                throw new \RuntimeException('Parser: XML is not valid');
            } else {
                $fileformat = $content->xpath('/timetable/general/format')[0];
                if ($fileformat == $this->format) {
                    $this->rawarray = $content;
                } else {
                    \Registry::get('log')->warning ($this->revision. ':Failed to get file format');
                    throw new \RuntimeException('Parser: Can\'t identify schedule format');
                }
            }
        } else {
            throw new \RuntimeException('Parser: XML is missing');
        }
    }

    public function returnSettings () {
        return array ('rev' => $this->revision, 'mtime' => $this->mtime, 'begin_date' => $this->rawarray->xpath('/timetable/term/begin_date')[0], 'end_date' => $this->rawarray->xpath('/timetable/term/end_date')[0]);
    }

    public function returnFileName () {
        return basename($this->path);
    }

    public function mainContent () {
        $result = array();
        $result['simple'] = [];
        $result['complex'] = [];
        $simple_structures = array ('class' => 'classes', 'subject' => 'subjects', 'chair' => 'chairs', 'speciality' => 'specialities',
             'room' => 'rooms', 'teacher' => 'teachers', 'sched' => 'scheds', 'study_type' => 'study_types', 'time' => 'times');
        $complex_structures = array ('load' => 'loads');
        foreach ($simple_structures as $k=>$v) {
            $entities = $this->rawarray->xpath("/timetable/$v/$k");
            $result['simple'] = array_merge($result['simple'], array($v => $entities));
        }
        foreach ($complex_structures as $k=>$v) {
            $entities = json_decode(json_encode($this->rawarray->xpath("/timetable/$v/$k")), true);
            $result['complex'] = array_merge($result['complex'], array($v => $entities));
        }
        $result = array_merge($result, array('settings' => $this->returnSettings()));
        return $result;
    }
}