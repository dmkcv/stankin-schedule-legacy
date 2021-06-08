<?php
/**
 * Project: raspprod
 * Date: 20.07.2017
 * Time: 18:45
 */

class StaticGenerator
{
    public static function generateFromFile ($rev_key) {
        $path = STORAGE_DIR. '/temp/' .$rev_key. '.xml';
        if (file_exists($path)) {
            $parser = new Parser($path);
            $filler = new Filler($parser->mainContent());
            $filler->constructDB();
            $builder = new Builder($filler->prepareContent());
            $builder->buildStatic();
            $builder->buildSchedule();
            if ($builder->isGeneratedContentValid()) {
                file_put_contents(STORAGE_RAW_DIR. '/' .$rev_key. '/checked.txt', 'ok');
                \Registry::get('log')->info ($rev_key. ':SUCCESS');
                return true;
            } else {
                \Registry::get('log')->warning ($rev_key. ':FAIL to validate revision');
                http_response_code(503);
                return false;
            }
        } else {
            \Registry::get('log')->warning ($rev_key. ':FAIL to find revision file');
            http_response_code(503);
            return false;
        }
    }
}